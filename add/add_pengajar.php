<?php
require "../db/koneksi.php";

// Detail admin
$username = "suhailah";
$nama_lengkap = "suhailah bahabzy";
$email = "suhailah@gmail.com";
$password = "0000";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Buat hash password
$peran = "pengajar";
$nomor_hp = "08892373";

// Masukkan ke database
$stmt = $db->prepare("INSERT INTO tb_pengguna (username, nama_lengkap, email, password, peran, nomor_hp) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$username, $nama_lengkap, $email, $hashedPassword, $peran, $nomor_hp]);

echo "Pengajar berhasil ditambahkan!";
?>
