<?php
session_start();
require_once "../admin/koneksi.php";

// Kelas Pembayaran
class Pembayaran {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function validateFile($file) {
        // Validasi ukuran file (5MB)
        $max_size = 5 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            return "Ukuran file terlalu besar. Maksimal 5MB.";
        }

        // Validasi tipe file
        $allowed_types = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_types)) {
            return "Tipe file tidak diizinkan. Hanya jpg, jpeg, png.";
        }

        return true;
    }

    public function uploadBuktiPayment($file) {
        $upload_dir = __DIR__ . '/bukti_payment/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Ambil nomor terakhir dari database
        $query = "SELECT MAX(CAST(SUBSTRING_INDEX(bukti_payment, '.', 1) AS UNSIGNED)) as last_number FROM tb_payment";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        $next_number = ($row['last_number'] ?? 0) + 1;
        
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = $next_number . '.' . $file_ext;
        $upload_path = $upload_dir . $new_filename;
        
        // Cek apakah file dengan nama tersebut sudah ada
        while (file_exists($upload_path)) {
            $next_number++;
            $new_filename = $next_number . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;
        }

        // Upload file dengan error handling
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            error_log("Gagal upload file ke: " . $upload_path);
            return false;
        }

        // Verifikasi file setelah upload
        if (!file_exists($upload_path)) {
            error_log("File tidak ditemukan setelah upload: " . $upload_path);
            return false;
        }

        // Set permission yang benar
        chmod($upload_path, 0644);

        return $new_filename;
    }

    public function savePayment($id_pendaftaran, $total, $bukti_payment, $no_rek, $metode_payment) {
        try {
            $query = "INSERT INTO tb_payment (id_pendaftaran, total, tanggal_payment, bukti_payment, no_rek, metode_payment, status_pembayaran, status_konfirmasi) 
                      VALUES (?, ?, CURRENT_DATE(), ?, ?, ?, 'valid', 'pending')";
            
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }
            
            $stmt->bind_param("idsss", 
                $id_pendaftaran, 
                $total, 
                $bukti_payment, 
                $no_rek, 
                $metode_payment
            );
            
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Error in savePayment: " . $e->getMessage());
            return false;
        }
    }

    // Method saveToSiswa tetap sama seperti sebelumnya
    public function saveToSiswa($id_pendaftaran) {
        // Ambil id_pengguna dan nama dari tb_pengguna berdasarkan id_pendaftaran
        $query_pengguna = "
            SELECT tb_pengguna.nama 
            FROM tbl_pendaftaran 
            JOIN tb_pengguna ON tbl_pendaftaran.id_pengguna = tb_pengguna.id_pengguna 
            WHERE tbl_pendaftaran.id_pendaftaran = ?";
        $stmt_pengguna = $this->conn->prepare($query_pengguna);
        $stmt_pengguna->bind_param("s", $id_pendaftaran);
        $stmt_pengguna->execute();
        $result = $stmt_pengguna->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nama_siswa = $row['nama'];
        } else {
            $nama_siswa = "Unknown";
        }

        // Masukkan data ke tb_siswa
        $query_siswa = "INSERT INTO tb_siswa (id_pendaftaran, nama_siswa, sisa_pertemuan) VALUES (?, ?, NULL)";
        $stmt_siswa = $this->conn->prepare($query_siswa);
        $stmt_siswa->bind_param("ss", $id_pendaftaran, $nama_siswa);
        return $stmt_siswa->execute();
    }
}

// Proses Pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment'])) {
    $pembayaran = new Pembayaran($conn); 
    
    // Validasi file upload
    if (!isset($_FILES['bukti_payment']) || $_FILES['bukti_payment']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Upload file gagal. Silakan coba lagi.";
        header("Location: payment.php");
        exit;
    }

    // Validasi file
    $validate_result = $pembayaran->validateFile($_FILES['bukti_payment']);
    if ($validate_result !== true) {
        $_SESSION['error'] = $validate_result;
        header("Location: payment.php");
        exit;
    }

    // Upload bukti pembayaran
    $bukti_payment = $pembayaran->uploadBuktiPayment($_FILES['bukti_payment']);
    if (!$bukti_payment) {
        $_SESSION['error'] = "Gagal mengupload file.";
        header("Location: payment.php");
        exit;
    }

    // Validasi data pembayaran
    if (!isset($_SESSION['id_pendaftaran']) || !isset($_SESSION['total'])) {
        $_SESSION['error'] = "Data pembayaran tidak lengkap.";
        header("Location: payment.php");
        exit;
    }

    // Simpan pembayaran
    $result = $pembayaran->savePayment(
        $_SESSION['id_pendaftaran'],
        $_SESSION['total'],
        $bukti_payment,
        $_POST  ['no_rek'],
        $_POST['metode_payment']
        
    );

    if ($result) {
        $siswaResult = $pembayaran->saveToSiswa($_SESSION['id_pendaftaran']);

        if ($siswaResult) {
            $_SESSION['payment_success'] = true;
            header("Location: ../tambah_kursus.php"); // Redirect to new course selection page
            exit;
        } else {
            $_SESSION['error'] = "Pembayaran berhasil, tetapi gagal menambahkan data siswa.";
            header("Location: payment.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Gagal menyimpan pembayaran.";
        header("Location: payment.php");
        exit;
    }
}
?>
