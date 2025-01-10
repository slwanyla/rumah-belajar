<?php
include ("koneksi.php");

$kode = $_GET['id'] ?? '';

// Get student data
$sql = "SELECT s.id_pendaftaran, p.id_kelas 
        FROM tb_siswa s
        LEFT JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
        WHERE s.id_siswa = '$kode'";
$siswa = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$data = mysqli_fetch_array($siswa);

try {
    mysqli_begin_transaction($conn);

    // Hapus data pertemuan dulu
    mysqli_query($conn, "DELETE FROM tb_pertemuan WHERE id_siswa = '$kode'")
        or die(mysqli_error($conn));
    
    // Hapus data siswa
    mysqli_query($conn, "DELETE FROM tb_siswa WHERE id_siswa = '$kode'")
        or die(mysqli_error($conn));
    
    // Hapus data terkait pendaftaran jika ada
    if (!empty($data['id_pendaftaran'])) {
        mysqli_query($conn, "DELETE FROM tb_payment WHERE id_pendaftaran = '$data[id_pendaftaran]'") 
            or die(mysqli_error($conn));
        
        mysqli_query($conn, "DELETE FROM tbl_pendaftaran WHERE id_pendaftaran = '$data[id_pendaftaran]'")
            or die(mysqli_error($conn));
    }

    // Commit transaksi
    mysqli_commit($conn);

    // Redirect setelah berhasil
    echo "<script type='text/javascript'>
        alert('Data berhasil dihapus');
        window.location = 'index.php?page=lihatsiswa#class-" . $data['id_kelas'] . "';
    </script>";
} catch (Exception $e) {
    // Rollback transaksi jika terjadi error
    mysqli_rollback($conn);

    // Error handling
    echo "<script type='text/javascript'>
        alert('Gagal menghapus data: " . addslashes($e->getMessage()) . "');
        window.location = 'index.php?page=lihatsiswa';
    </script>";
}
?>
