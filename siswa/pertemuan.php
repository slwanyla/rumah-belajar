<?php
require_once '../admin/koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("location: ../login.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];

// Modified query to get all active courses for the user
$query_siswa = "SELECT 
    s.id_siswa,
    s.nama_siswa,
    s.status,
    s.sisa_pertemuan,
    s.exp_date,
    k.nama_kursus,
    k.id_kursus,
    p.id_pendaftaran,
    p.id_kelas,
    kl.nama_kelas
FROM tb_siswa s 
JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
JOIN tbl_kursus k ON p.id_kursus = k.id_kursus
JOIN tb_kelas kl ON p.id_kelas = kl.id_kelas
WHERE p.id_pengguna = ? 
AND (
    (s.exp_date IS NULL AND s.status = 'aktif')
    OR (s.exp_date >= CURRENT_DATE())
)
ORDER BY s.id_siswa";

// Add an automated status update query that runs at the start of the script
$auto_update_status = "UPDATE tb_siswa 
                      SET status = 'non aktif'
                      WHERE exp_date < CURRENT_DATE()
                      AND status = 'aktif'";
$conn->query($auto_update_status);
$stmt_siswa = $conn->prepare($query_siswa);
$stmt_siswa->bind_param("i", $id_pengguna);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();

date_default_timezone_set('Asia/Jakarta');
$current_time = new DateTime('now');
$cutoff_time = new DateTime('today 23:00');
$is_registration_open = $current_time <= $cutoff_time;

// Handle form submission
if (isset($_POST['submit_pertemuan'])) {
    $tanggal = $_POST['tanggal_pertemuan'];
    $id_siswa = $_POST['id_siswa'];
    
    // Verify this id_siswa belongs to the logged-in user and get course info
    $verify_query = "SELECT 
        s.id_siswa,
        s.id_pendaftaran,
        p.id_kursus,
        p.id_kelas
    FROM tb_siswa s
    JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
    WHERE s.id_siswa = ? AND p.id_pengguna = ?";
    
    $verify_stmt = $conn->prepare($verify_query);
    $verify_stmt->bind_param("ii", $id_siswa, $id_pengguna);
    $verify_stmt->execute();
    $siswa_result = $verify_stmt->get_result();
    
    if ($siswa_result->num_rows === 0) {
        die("Unauthorized access");
    }
    
    $siswa_data = $siswa_result->fetch_assoc();
    $meeting_time = new DateTime($tanggal . ' 19:00:00');
    $meeting_datetime = $meeting_time->format('Y-m-d H:i:s');

    // Get next unfinished material for this specific course and class
    $query_materi = "SELECT m.id_materi 
                FROM tb_materi m
                WHERE m.id_kursus = ? -- kursus yang baru didaftar
                AND (
                    -- Kondisi 1: Jika materi spesifik untuk kelas tertentu
                    (m.id_kelas = ? AND m.id_kelas IS NOT NULL)
                    OR 
                    -- Kondisi 2: Jika materi berlaku untuk semua kelas (seperti kasus Tajwid)
                    (m.id_kelas IS NULL)
                )
                AND m.id_materi > (
                    -- Ambil materi terakhir yang pernah diambil untuk kursus ini
                    SELECT COALESCE(MAX(p.id_materi), 0)
                    FROM tb_pertemuan p
                    JOIN tb_siswa s ON p.id_siswa = s.id_siswa
                    JOIN tbl_pendaftaran reg ON s.id_pendaftaran = reg.id_pendaftaran
                    WHERE reg.id_pengguna = ?
                    AND reg.id_kursus = ?
                    -- Untuk kasus non-Tajwid, hanya ambil materi dari kelas yang sama
                    AND (
                        (m.id_kelas IS NOT NULL AND reg.id_kelas = ?)
                        OR 
                        (m.id_kelas IS NULL)
                    )
                )
                ORDER BY m.id_materi ASC
                LIMIT 1";
    $stmt_materi = $conn->prepare($query_materi);
    $stmt_materi->bind_param("iiiii", 
            $siswa_data['id_kursus'],    // kursus baru
            $siswa_data['id_kelas'],     // kelas baru
            $id_pengguna,                // user id
            $siswa_data['id_kursus'],    // kursus untuk cek history
            $siswa_data['id_kelas']      // kelas untuk cek history
        ); 
        
    $stmt_materi->execute();
    $materi_result = $stmt_materi->get_result();

    if ($materi_result->num_rows > 0) {
        $id_materi = $materi_result->fetch_assoc()['id_materi'];
    } else {
        $id_materi = NULL;
    }

    // Start transaction
    $conn->begin_transaction();

    try {

        $check_query = "SELECT sisa_pertemuan FROM tb_siswa WHERE id_siswa = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $id_siswa);
        $check_stmt->execute();
        $sisa_pertemuan = $check_stmt->get_result()->fetch_assoc()['sisa_pertemuan'];
        // Insert new meeting
        $query = "INSERT INTO tb_pertemuan (id_siswa, id_materi, tanggal_pertemuan, status_pertemuan) 
                  VALUES (?, ?, ?, 'belum')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $id_siswa, $id_materi, $meeting_datetime);
        $stmt->execute();

        // Update remaining meetings
        $update_query = "UPDATE tb_siswa SET 
                        sisa_pertemuan = GREATEST(sisa_pertemuan - 1, 0)
                        WHERE id_siswa = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $id_siswa);
        $update_stmt->execute();


        if ($sisa_pertemuan == 0) { // Changed from 0 to 1 since we're checking before decrement
            // Set expiration date to the day after the meeting
            $exp_date = date('Y-m-d', strtotime($tanggal . ' +1 day'));
            $status_update = "UPDATE tb_siswa 
                             SET exp_date = ?
                             WHERE id_siswa = ?";
            $status_stmt = $conn->prepare($status_update);
            $status_stmt->bind_param("si", $exp_date, $id_siswa);
            $status_stmt->execute();
        }
    
        $conn->commit();
        $success = "Pertemuan berhasil ditambahkan! Pertemuan akan dimulai jam 19:00.";
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Gagal menambahkan pertemuan: " . $e->getMessage();
    }
}  
    

// Riwayat Pertemuan
    
    $query_pertemuan = "SELECT 
    p.*,
    m.judul_materi,
    m.file_materi as accessible_materi,
    m.link_platform,
    p.rekaman_zoom as accessible_rekaman,
    k.nama_kursus,
    kl.nama_kelas,
    reg.tanggal_pendaftaran as periode_daftar
    FROM tb_pertemuan p
    LEFT JOIN tb_materi m ON p.id_materi = m.id_materi
    LEFT JOIN tb_siswa s ON p.id_siswa = s.id_siswa
    LEFT JOIN tbl_pendaftaran reg ON s.id_pendaftaran = reg.id_pendaftaran
    LEFT JOIN tbl_kursus k ON reg.id_kursus = k.id_kursus
    LEFT JOIN tb_kelas kl ON reg.id_kelas = kl.id_kelas
    WHERE reg.id_pengguna = ? 
    ORDER BY reg.tanggal_pendaftaran DESC, p.tanggal_pertemuan DESC";

    $stmt_pertemuan = $conn->prepare($query_pertemuan);
    $stmt_pertemuan->bind_param("i", $id_pengguna);
    $stmt_pertemuan->execute();
    $pertemuan = $stmt_pertemuan->get_result();
    
?>

<!-- HTML -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">Input Pertemuan</h4>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
                    
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <!-- Info Kursus -->
        <div class="row">
            <?php while ($siswa = $result_siswa->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($siswa['nama_kursus']) ?></h5>
                        <p class="card-text">
                            <strong>Status:</strong> <?= ucfirst($siswa['status']) ?><br>
                            <strong>Sisa Pertemuan:</strong> <?= $siswa['sisa_pertemuan'] ?>
                        </p>
                        
                        <?php if ($siswa['status'] === 'aktif' && $siswa['sisa_pertemuan'] > 0): ?>
                        <form method="POST" class="mt-3">
                            <input type="hidden" name="id_siswa" value="<?= $siswa['id_siswa'] ?>">
                            <div class="mb-4">
                                <label class="form-label">Tanggal Pertemuan</label>
                                <input type="date" 
                                       name="tanggal_pertemuan" 
                                       class="form-control" 
                                       value="<?= date('Y-m-d') ?>" 
                                       required
                                       <?= !$is_registration_open ? 'disabled' : '' ?>>
                            </div>
                            <button type="submit" 
                                    name="submit_pertemuan" 
                                    class="btn btn-primary"
                                    <?= !$is_registration_open ? 'disabled' : '' ?>>
                                Daftar Pertemuan
                            </button>
                        </form>
                        <?php else: ?>
                        <div class="alert alert-info mt-3">
                            <?php if ($siswa['status'] !== 'aktif'): ?>
                                Status kursus tidak aktif
                            <?php else: ?>
                                Sisa pertemuan sudah habis
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <?php if (!$is_registration_open): ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    Pendaftaran pertemuan sudah ditutup. Silakan daftar kembali besok sebelum jam 23:00.
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Riwayat Pertemuan -->
        <div class="card mt-4">
        <div class="card-body">
            <h5>Riwayat Pertemuan</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kursus</th>
                            <th>Kelas</th>
                            <th>Materi</th>
                            <th>Rekaman Pertemuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $current_period = null;
                        while ($row = $pertemuan->fetch_assoc()): 
                            $meeting_datetime = new DateTime($row['tanggal_pertemuan']);
                            $registration_date = new DateTime($row['periode_daftar']);
                    
                            // Header untuk setiap periode pendaftaran baru
                            if ($current_period !== $row['periode_daftar']):
                                $current_period = $row['periode_daftar'];
                        ?>
                        <tr class="table-secondary">
                            <td colspan="7" class="fw-bold">
                                Periode: <?= $registration_date->format('d/m/Y') ?> - 
                                    <?= htmlspecialchars($row['nama_kursus']) ?> 
                                    (<?= htmlspecialchars($row['nama_kelas']) ?>)
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $meeting_datetime->format('d/m/Y') ?></td>
                            <td><?= htmlspecialchars($row['nama_kursus']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td>
                                <?php if ($row['accessible_materi']): ?>
                                    <a href="<?= htmlspecialchars($row['accessible_materi']) ?>" 
                                       target="_blank"
                                       class="btn btn-sm btn-primary">
                                        <?= htmlspecialchars($row['judul_materi']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada materi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['accessible_rekaman']): ?>
                                    <a href="<?= htmlspecialchars($row['rekaman_zoom']) ?>" 
                                       target="_blank"
                                       class="btn btn-sm btn-secondary" style="gap: 8px;">
                                        <i class="fas fa-video"></i> Lihat 
                                    </a>
                                <?php elseif ($row['status_pertemuan'] === 'belum'): ?>
                                    <span class="text-muted">
                                        Rekaman akan tersedia setelah pertemuan selesai
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada rekaman</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $row['status_pertemuan'] === 'sudah' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($row['status_pertemuan']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php if ($pertemuan->num_rows === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada pertemuan</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>