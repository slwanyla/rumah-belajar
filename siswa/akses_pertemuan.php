<?php
require_once '../admin/koneksi.php';

$id_pertemuan = $_GET['id_pertemuan']; // Dapatkan ID pertemuan dari URL

$query = "
    SELECT m.link_zoom 
    FROM tb_pertemuan p
    JOIN tb_materi m ON p.id_materi = m.id_materi
    WHERE p.id_pertemuan = ? AND p.status_pertemuan = 'belum'
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pertemuan);
$stmt->execute();
$result = $stmt->get_result();

if ($data = $result->fetch_assoc()) {
    echo "Link Zoom: <a href='" . $data['link_zoom'] . "'>Join Zoom</a>";
} else {
    echo "Link tidak tersedia.";
}
?>