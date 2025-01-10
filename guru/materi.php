<?php
require '../admin/koneksi.php';

$id_guru = $_SESSION['id_guru'];
$id_kursus = null;
$message = null;

// Dapatkan id_kursus berdasarkan id_guru
$query_guru = $conn->prepare("SELECT id_kursus FROM tb_guru WHERE id_guru = ?");
$query_guru->bind_param("i", $id_guru);
$query_guru->execute();
$result_guru = $query_guru->get_result();

if ($result_guru->num_rows > 0) {
    $id_kursus = $result_guru->fetch_assoc()['id_kursus'];
} else {
    $message = ["error", "Data guru tidak ditemukan."];
}

// Cek apakah kursus adalah Matematika (id_kursus = 2)
$is_matematika = ($id_kursus == 2);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validasi guru matematika hanya bisa upload sesuai kelas yang dipilih
    if ($is_matematika && empty($_POST['id_kelas'])) {
        $message = ["error", "Mohon pilih kelas untuk materi matematika"];
        exit;
    }
    $judul_materi = $_POST['judul_materi'];
    $link_platform = isset($_POST['link_platform']) ? $_POST['link_platform'] : null;
    $id_kelas = $is_matematika ? $_POST['id_kelas'] : null;

    // Upload file materi
    $target_path = null;
    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../siswa/uploads/materi/";
        $file_name = basename($_FILES['file_materi']['name']);
        $target_path = $upload_dir . $file_name;

        if (!move_uploaded_file($_FILES['file_materi']['tmp_name'], $target_path)) {
            $message = ["error", "Gagal mengunggah file materi."];
        }
    }

    // Simpan materi ke database
    if (!$message) {
        $conn->begin_transaction();

        try {
            // Insert materi baru
            if ($id_kelas === null) {
                $sql = "INSERT INTO tb_materi (judul_materi, file_materi, link_platform, id_kelas, id_kursus) 
                        VALUES (?, ?, ?, NULL, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $judul_materi, $target_path, $link_platform, $id_kursus);
            } else {
                $sql = "INSERT INTO tb_materi (judul_materi, file_materi, link_platform, id_kelas, id_kursus) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssii", $judul_materi, $target_path, $link_platform, $id_kelas, $id_kursus);
            }

            if ($stmt->execute()) {
                // Update pertemuan yang belum memiliki materi
                $query_pertemuan = "UPDATE tb_pertemuan p
                INNER JOIN (
                    SELECT p.id_pertemuan, MIN(m.id_materi) as next_materi_id
                    FROM tb_pertemuan p
                    JOIN tb_siswa s ON p.id_siswa = s.id_siswa
                    JOIN tbl_pendaftaran pd ON s.id_pendaftaran = pd.id_pendaftaran
                    JOIN tb_materi m ON m.id_kursus = pd.id_kursus 
                        AND (m.id_kelas = pd.id_kelas OR m.id_kelas IS NULL)
                    WHERE p.id_materi IS NULL
                    AND s.status = 'aktif'
                    AND s.sisa_pertemuan > 0
                    AND m.id_materi > COALESCE(
                        (SELECT MAX(p2.id_materi) 
                        FROM tb_pertemuan p2 
                        WHERE p2.id_siswa = p.id_siswa
                        AND p2.id_materi IS NOT NULL),
                        0
                    )
                    GROUP BY p.id_pertemuan
                ) nm ON p.id_pertemuan = nm.id_pertemuan
                SET p.id_materi = nm.next_materi_id";

                if (!$conn->query($query_pertemuan)) {
                    throw new Exception("Gagal update pertemuan: " . $conn->error);
                }

                $conn->commit();
                $message = ["success", "Materi berhasil diunggah dan ditambahkan ke pertemuan siswa secara berurutan!"];
            } else {
                throw new Exception("Gagal menyimpan materi: " . $stmt->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            $message = ["error", $e->getMessage()];
        }
    }
}
?>


    <title>Upload Materi Pembelajaran</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin-top: 80px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #1a73e8;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .form-header i {
            font-size: 48px;
            color: #1a73e8;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #5f6368;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1a73e8;
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%235f6368' viewBox='0 0 16 16'%3E%3Cpath d='M8 10l4-4H4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .file-input {
            display: none;
        }

        .file-label {
            display: block;
            padding: 12px;
            background: #f8f9fa;
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-label:hover {
            background: #f1f3f4;
            border-color: #1a73e8;
        }

        .file-label i {
            font-size: 24px;
            color: #5f6368;
            margin-bottom: 8px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background: #1557b0;
        }

        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }

        .message.success {
            background: #e6f4ea;
            color: #1e8e3e;
        }

        .message.error {
            background: #fce8e6;
            color: #d93025;
        }

        .selected-file {
            margin-top: 8px;
            font-size: 14px;
            color: #5f6368;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <i class="fas fa-book-reader"></i>
            <h1>Upload Materi Pembelajaran</h1>
        </div>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul_materi">Judul Materi</label>
                <input type="text" id="judul_materi" name="judul_materi" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="link_platform">Link (Zoom/Goggle Classroom)</label>
                <input type="text" id="link_platform" name="link_platform" class="form-control" required>
            </div>

            <?php if ($is_matematika): ?>
            <div class="form-group">
                <label for="id_kelas">Pilih Kelas</label>
                <select id="id_kelas" name="id_kelas" class="form-control" required>
                    <option value="">Pilih Kelas</option>
                    <option value="1">TK</option>
                    <option value="2">1 SD</option>
                    <option value="3">2 SD</option>
                    <option value="4">3 SD</option>
                    <option value="5">4 SD</option>
                    <option value="6">5 SD</option>
                    <option value="7">6 SD</option>
                    <option value="8">1 SMP</option>
                    <option value="9">2 SMP</option>
                    <option value="10">3 SMP</option>
                </select>
            </div>
            <?php else: ?>
                <input type="hidden" name="id_kelas" value="NULL">
            <?php endif; ?>

            <div class="form-group">
                <input type="file" id="file_materi" name="file_materi" class="file-input" required>
                <label for="file_materi" class="file-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <div>Klik atau drop file materi di sini</div>
                    <div class="selected-file"></div>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-upload"></i> Upload Materi
            </button>

            <?php if (isset($message)): ?>
            <div class="message <?php echo $message[0]; ?>">
                <?php echo $message[1]; ?>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('file_materi');
        const fileLabel = document.querySelector('.file-label');
        const selectedFile = document.querySelector('.selected-file');

        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            selectedFile.textContent = fileName || '';
        });

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileLabel.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            fileLabel.style.borderColor = '#1a73e8';
            fileLabel.style.background = '#f1f3f4';
        }

        function unhighlight(e) {
            fileLabel.style.borderColor = '#e0e0e0';
            fileLabel.style.background = '#f8f9fa';
        }

        fileLabel.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            selectedFile.textContent = files[0]?.name || '';
        }
    </script>