<?php 
require_once '../admin/koneksi.php';
session_start();  

// Cek apakah ada ID pertemuan yang dikirim
if (!isset($_POST['id_pertemuan'])) {
    $_SESSION['error_message'] = "ID Pertemuan tidak valid.";
    header("Location: index.php?page=pertemuansiswa");
    exit;
}

$id_pertemuan = $_POST['id_pertemuan'];

// Get current status
$query_status = "SELECT status_pertemuan FROM tb_pertemuan WHERE id_pertemuan = ?";
$stmt_status = $conn->prepare($query_status);
$stmt_status->bind_param("i", $id_pertemuan);
$stmt_status->execute();
$result_status = $stmt_status->get_result();
$status = $result_status->fetch_assoc();

if ($status) {
    // Update status
    $new_status = ($status['status_pertemuan'] == 'belum') ? 'sudah' : 'belum';
    
    $update_query = "UPDATE tb_pertemuan SET status_pertemuan = ? WHERE id_pertemuan = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("si", $new_status, $id_pertemuan);
    
    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Kehadiran siswa berhasil ditandai!";
    } else {
        $_SESSION['error_message'] = "Gagal mengupdate status: " . $conn->error;
    }
} else {
    $_SESSION['error_message'] = "Pertemuan tidak ditemukan.";
}

// Redirect di akhir script
header("Location: index.php?page=pertemuansiswa");
exit;
?>