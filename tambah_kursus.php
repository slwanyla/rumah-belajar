<?php
session_start();
require_once "./admin/koneksi.php";

// cek pembayaran telah sukses
if (!isset($_SESSION['payment_success'])) {
    header("Location: login.php");
    exit;
}

$_SESSION['payment_notification'] = "Pembayaran berhasil! Silakan menunggu konfirmasi dari admin. Kami akan mengirimkan notifikasi ke email Anda. Harap pantau inbox email Anda secara berkala untuk informasi lebih lanjut tentang kursus Anda.";

if (isset($_POST['setAddCourse'])) {
    $_SESSION['previous_add_course'] = true;
    echo "success";
    exit;
}

// Check if user previously chose to add another course
if (isset($_SESSION['previous_add_course']) && $_SESSION['previous_add_course'] === true) {
    // If they previously chose to add course, redirect directly to login
    unset($_SESSION['previous_add_course']); // Clear the flag
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/cyborg/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            
            background-color: #f5f0bb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>
<script>
        Swal.fire({
            icon: 'success',
            title: 'Pembayaran Berhasil!',
            text: 'Kursus Anda telah berhasil didaftarkan.',
            showDenyButton: true,
            confirmButtonText: 'Tambah Kursus',
            denyButtonText: 'Lanjut ke Login',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {

                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'setAddCourse=1'
                }).then(() => {
                // Redirect ke halaman tambah kursus
                    window.location.href = "tambah.php";
                 });
            } else if (result.isDenied) {
                // Redirect ke halaman login
                window.location.href = "login.php";
            }
        });
    </script>
</body>
</html>
