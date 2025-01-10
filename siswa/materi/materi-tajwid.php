<?php
require_once '../admin/koneksi.php';

$id_pengguna = $_SESSION['id_pengguna'];

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
AND k.nama_kursus LIKE '%tajwid%'
AND (
    (s.exp_date IS NULL AND s.status = 'aktif')
    OR (s.exp_date >= CURRENT_DATE())
)
ORDER BY s.id_siswa";

$stmt_siswa = $conn->prepare($query_siswa);
$stmt_siswa->bind_param("i", $id_pengguna);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi Pembelajaran</title>
    <style>
        :root {
            --primary-color: #2196f3;
            --success-color: #4caf50;
            --warning-color: #ff5722;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.15);
            --border-radius: 8px;
            --spacing-sm: 10px;
            --spacing-md: 20px;
            --spacing-lg: 30px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-md);
        }

        .page-header {
            padding: var(--spacing-md) 0;
            margin-bottom: var(--spacing-lg);
        }

        .page-title {
            font-size: 1.5rem;
            color: #666;
            
        }

        .materials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .material-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            padding: var(--spacing-md);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }

        .material-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .lesson-number {
            position: absolute;
            top: -8px; /* Changed from -12px to -8px to move it down */
            left: var(--spacing-md);
            background: var(--primary-color);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .material-title {
            font-size: 1.125rem;
            margin:  calc(var(--spacing-md) + 4px) 0 var(--spacing-sm);
        }

        .material-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            margin-bottom: var(--spacing-sm);
        }

        .status-active {
            background-color: #e3f2fd;
            color: var(--primary-color);
        }

        .status-completed {
            background-color: #e8f5e9;
            color: var(--success-color);
        }

        .material-actions {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-sm);
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            gap: 8px;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-download {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-zoom {
            background-color: var(--success-color);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: var(--spacing-lg) var(--spacing-md);
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin: var(--spacing-md) auto;
            max-width: 600px;
        }

        .empty-state-text {
            font-size: 1.125rem;
            color: #666;
            margin-bottom: var(--spacing-sm);
        }

        .empty-state-subtext {
            color: #888;
            margin-bottom: var(--spacing-md);
        }

        @media (max-width: 768px) {
            .materials-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 0 var(--spacing-sm);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <span class="text-muted">Materi</span> Pembelajaran
            </h1>
        </header>

        <?php if ($result_siswa->num_rows > 0): ?>
            <?php while ($siswa_info = $result_siswa->fetch_assoc()): ?>
                <section class="course-section">
                    <h2 class="section-title">
                        <?= htmlspecialchars($siswa_info['nama_kelas']) ?>
                    </h2>

                    <?php
                    $query_materi = "SELECT 
                    m.*,
                    p.status_pertemuan,
                    p.tanggal_pertemuan,
                    p.id_pertemuan
                FROM tb_materi m
                INNER JOIN tb_pertemuan p ON m.id_materi = p.id_materi 
                WHERE p.id_siswa = ?
                AND m.id_kursus = ?
                AND (m.id_kelas = ? OR m.id_kelas IS NULL)
                AND p.tanggal_pertemuan IS NOT NULL
                ORDER BY p.tanggal_pertemuan ASC, m.id_materi ASC";

            $stmt_materi = $conn->prepare($query_materi);
            $stmt_materi->bind_param("iii", 
                $siswa_info['id_siswa'],
                $siswa_info['id_kursus'],
                $siswa_info['id_kelas']
            );
            $stmt_materi->execute();
            $result_materi = $stmt_materi->get_result();
                    ?>

                    <?php if ($result_materi->num_rows > 0): ?>
                        <div class="materials-grid">
                            <?php 
                            $lesson_number = 1;
                            while ($materi = $result_materi->fetch_assoc()): 
                            ?>
                                <article class="material-card">
                                    <div class="lesson-number">Pertemuan ke-<?= $lesson_number ?></div>
                                    <h3 class="material-title">
                                        <?= htmlspecialchars($materi['judul_materi']) ?>
                                    </h3>
                                    
                                    <?php if (!empty($materi['deskripsi'])): ?>
                                        <p class="material-description">
                                            <?= htmlspecialchars($materi['deskripsi']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="material-status <?= $materi['status_pertemuan'] === 'belum' ? 'status-active' : 'status-completed' ?>">
                                        <?= $materi['status_pertemuan'] === 'belum' ? 'Belum Dipelajari' : 'Sudah Dipelajari' ?>
                                        <?php if ($materi['tanggal_pertemuan']): ?>
                                            <br>
                                            <small>Tanggal: <?= date('d/m/Y', strtotime($materi['tanggal_pertemuan'])) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="material-actions">
                                        <?php if (!empty($materi['file_materi'])): ?>
                                            <a href="<?= htmlspecialchars($materi['file_materi']) ?>" 
                                               class="btn btn-download" 
                                               target="_blank">
                                                <i class="fas fa-download"></i> Download Materi
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($materi['link_platform'])): ?>
                                            <?php if ($materi['status_pertemuan'] === 'belum'): ?>
                                                <a href="<?= htmlspecialchars($materi['link_platform']) ?>" 
                                                   class="btn btn-zoom" 
                                                   target="_blank">
                                                    <i class="fas fa-video"></i> Join Zoom Meeting
                                                </a>
                                            <?php else: ?>
                                                <div class="completed-text">
                                                    <i class="fas fa-check-circle"></i> Pertemuan Selesai
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php 
                            $lesson_number++;
                            endwhile; 
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <h2 class="empty-state-text">Belum Ada Materi Matematika Tersedia</h2>
                            <p class="empty-state-subtext">
                                Materi pembelajaran matematika akan muncul di sini setelah guru mengunggahnya.
                                Silakan cek kembali nanti.
                            </p>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>