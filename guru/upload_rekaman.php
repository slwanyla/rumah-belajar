<?php
require_once "../admin/koneksi.php";
$id_guru = $_SESSION['id_guru'];

// Dapatkan id_kursus yang diampu oleh guru
$query_guru = $conn->prepare("
    SELECT id_kursus 
    FROM tb_guru 
    WHERE id_guru = ?
");
$query_guru->bind_param("i", $id_guru);
$query_guru->execute();
$result_guru = $query_guru->get_result();

if ($result_guru->num_rows > 0) {
    $id_kursus = $result_guru->fetch_assoc()['id_kursus'];
} else {
    die(json_encode(["error", "Data guru tidak ditemukan."]));
}

// Proses upload rekaman
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_rekaman'])) {
    $tanggal_pertemuan = $_POST['tanggal_pertemuan'];
    $rekaman_zoom = $_FILES['rekaman_zoom'];

    if ($rekaman_zoom['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../siswa/uploads/rekaman/";
        $file_name = basename($rekaman_zoom['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($rekaman_zoom['tmp_name'], $target_path)) {
            // First, get the relevant id_pertemuan values
            $get_pertemuan = $conn->prepare("
                SELECT DISTINCT tp.id_pertemuan 
                FROM tb_pertemuan tp 
                JOIN tb_siswa s ON tp.id_siswa = s.id_siswa
                JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
                WHERE p.id_kursus = ? 
                AND tp.tanggal_pertemuan = ?
            ");
            $get_pertemuan->bind_param("is", $id_kursus, $tanggal_pertemuan);
            $get_pertemuan->execute();
            $result_pertemuan = $get_pertemuan->get_result();
            
            // Then update each pertemuan record
            $update_rekaman = $conn->prepare("
                UPDATE tb_pertemuan 
                SET rekaman_zoom = ? 
                WHERE id_pertemuan = ?
            ");
            
            $success = true;
            while ($row = $result_pertemuan->fetch_assoc()) {
                $update_rekaman->bind_param("si", $target_path, $row['id_pertemuan']);
                if (!$update_rekaman->execute()) {
                    $success = false;
                    break;
                }
            }
            
            if ($success) {
                $rekaman_message = ["success", "Rekaman Zoom berhasil diunggah!"];
            } else {
                $rekaman_message = ["error", "Gagal mengunggah rekaman: " . $conn->error];
            }
        } else {
            $rekaman_message = ["error", "Gagal mengunggah file rekaman."];
        }
    } else {
        $rekaman_message = ["error", "Tidak ada file yang diunggah."];
    }
}
?>
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

<div class="container">
            <div class="form-header">
                <i class="fas fa-video" style="color: #1a73e8;"></i>
                <h2>Upload Rekaman</h2>
            </div>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="tanggal_pertemuan">Tanggal Pertemuan</label>
                    <select id="tanggal_pertemuan" name="tanggal_pertemuan" class="form-control" required>
                        <?php
                        // Ambil tanggal pertemuan yang sesuai dengan kursus
                        $query_tanggal = $conn->prepare("
                            SELECT DISTINCT tp.tanggal_pertemuan 
                            FROM tb_pertemuan tp 
                            JOIN tb_siswa s ON tp.id_siswa = s.id_siswa
                            JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
                            WHERE tp.status_pertemuan = 'sudah' AND p.id_kursus = ?
                        ");
                        $query_tanggal->bind_param("i", $id_kursus);
                        $query_tanggal->execute();
                        $result_tanggal = $query_tanggal->get_result();
        
                        while ($row = $result_tanggal->fetch_assoc()) {
                            echo "<option value='{$row['tanggal_pertemuan']}'>{$row['tanggal_pertemuan']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <input type="file" id="rekaman_zoom" name="rekaman_zoom" class="file-input" required>
                    <label for="rekaman_zoom" class="file-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <div>Klik atau drop file rekaman di sini</div>
                        <div class="selected-file"></div>
                    </label>
                </div>

                <button type="submit" name="upload_rekaman" class="btn-submit">
                    <i class="fas fa-upload"></i> Upload Rekaman
                </button>

                <?php if (isset($rekaman_message)): ?>
                <div class="message <?php echo $rekaman_message[0]; ?>">
                    <?php echo $rekaman_message[1]; ?>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        // Handle file input display
        document.querySelectorAll('.file-input').forEach(input => {
            const label = input.nextElementSibling;
            const selectedFile = label.querySelector('.selected-file');

            input.addEventListener('change', function() {
                selectedFile.textContent = this.files[0]?.name || '';
            });

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                label.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                label.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                label.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                label.style.borderColor = '#1a73e8';
                label.style.background = '#f1f3f4';
            }

            function unhighlight() {
                label.style.borderColor = '#e0e0e0';
                label.style.background = '#f8f9fa';
            }

            label.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files;
                selectedFile.textContent = files[0]?.name || '';
            }
        });
    </script>
</body>
</html>