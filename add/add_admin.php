<?php
// Koneksi ke database
require "../admin/koneksi.php";

// Data admin yang akan ditambahkan
$username = "nani";
$nama_lengkap = "nani";
$email = "nani@nani.com";
$pass = "1234";
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT); // Hash password sebelum disimpan
$peran = "admin";
$nomor_hp = "3233123";

// Query untuk memasukkan data admin ke tabel
$query = "INSERT INTO tb_pengguna (username, nama, email, pass, status, nomor_hp) VALUES (?, ?, ?, ?, ?, ?)";

// Persiapan statement menggunakan mysqli
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Gagal mempersiapkan query: " . mysqli_error($conn));
}

// Mengikat parameter ke statement
mysqli_stmt_bind_param($stmt, "ssssss", $username, $nama_lengkap, $email, $hashedPassword, $peran, $nomor_hp);

// Eksekusi query
if (mysqli_stmt_execute($stmt)) {
    echo "Admin berhasil ditambahkan!";
} else {
    echo "Gagal menambahkan admin: " . mysqli_error($conn);
}
?>