<?php
// update_pertemuan.php
require_once '../admin/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Cek apakah guru sudah login
if (!isset($_SESSION['id_guru'])) {
    die('Silakan login terlebih dahulu.');
}

// Ambil ID guru dari session
$id_guru = $_SESSION['id_guru'];

// Query untuk mendapatkan id_kursus yang diajarkan oleh guru
$query_kursus_guru = "
    SELECT g.id_kursus, k.nama_kursus
    FROM tb_guru g
    JOIN tbl_kursus k ON g.id_kursus = k.id_kursus
    WHERE g.id_guru = ?
";
$stmt_kursus_guru = $conn->prepare($query_kursus_guru);
$stmt_kursus_guru->bind_param("i", $id_guru);
$stmt_kursus_guru->execute();
$result_kursus_guru = $stmt_kursus_guru->get_result();

if ($result_kursus_guru->num_rows === 0) {
    die('Anda tidak memiliki kursus yang diajarkan.');
}

$kursus = $result_kursus_guru->fetch_assoc();
$id_kursus = $kursus['id_kursus'];
$nama_kursus = $kursus['nama_kursus'];

// Query untuk mendapatkan siswa berdasarkan id_kursus dengan pengurutan status
$query_siswa = "SELECT 
        s.id_siswa, 
        s.nama_siswa, 
        s.status, 
        p.id_pertemuan, 
        p.tanggal_pertemuan, 
        p.status_pertemuan 
    FROM tb_siswa s
    JOIN tbl_pendaftaran pd ON s.id_pendaftaran = pd.id_pendaftaran
    JOIN tb_pertemuan p ON s.id_siswa = p.id_siswa
    WHERE pd.id_kursus = ?
    ORDER BY 
        CASE 
            WHEN p.status_pertemuan = 'belum' THEN 1 
            ELSE 2 
        END,
        p.tanggal_pertemuan ASC,
        s.nama_siswa ASC
";
$stmt_siswa = $conn->prepare($query_siswa);
$stmt_siswa->bind_param("i", $id_kursus);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <h4 class="py-3 mb-4">
        Daftar Siswa untuk Kursus: <span class="text-primary"><?= htmlspecialchars($nama_kursus) ?></span>
    </h4>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Tanggal Pertemuan</th>
                    <th>Status Pertemuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_siswa->num_rows > 0): ?>
                    <?php while ($siswa = $result_siswa->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($siswa['nama_siswa']) ?></td>
                            <td><?= htmlspecialchars($siswa['status']) ?></td>
                            <td><?= htmlspecialchars($siswa['tanggal_pertemuan']) ?></td>
                            <td>
                                <?php if ($siswa['status_pertemuan'] === 'belum'): ?>
                                    <span class="badge bg-warning">Belum</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Sudah</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($siswa['status_pertemuan'] === 'belum'): ?>
                                    <form action="update_pertemuan.php" method="POST">
                                        <input type="hidden" name="id_pertemuan" value="<?= htmlspecialchars($siswa['id_pertemuan']); ?>">
                                        <button type="submit" class="btn btn-primary btn-sm">Tandai Hadir</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Sudah Hadir</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada siswa untuk kursus ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide alerts after 3 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.remove();
        }, 3000);
    });
});
</script>