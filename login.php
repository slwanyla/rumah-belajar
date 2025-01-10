<?php
session_start();
include "admin/koneksi.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['pass'];

    // Cek keberadaan username atau email
    $stmt = $conn->prepare("SELECT * FROM tb_pengguna WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['pass'])) {
        // Set session untuk login terlebih dahulu
        $_SESSION['id_pengguna'] = $user['id_pengguna'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['peran'] = $user['peran'];
        $_SESSION['login'] = true; 
        $_SESSION['id_siswa'] = $user['id_siswa'];// Tambahkan flag login
        $_SESSION['id_guru'] = $user['id_guru'];// Tambahkan flag login
        $_SESSION['id_admin'] = $user['id_admin'];

        if ($user['peran'] === 'guru') {
          // Get guru ID from tb_guru
          $stmt_guru = $conn->prepare("SELECT id_guru FROM tb_guru WHERE id_pengguna = ?");
          $stmt_guru->bind_param("i", $user['id_pengguna']);
          $stmt_guru->execute();
          $result_guru = $stmt_guru->get_result();
          
          if ($guru = $result_guru->fetch_assoc()) {
              $_SESSION['id_guru'] = $guru['id_guru'];
              error_log("Set guru ID in session: " . $guru['id_guru']);
          } else {
              error_log("Failed to find guru record for pengguna ID: " . $user['id_pengguna']);
          }
        }
        // Jika peran adalah siswa, periksa status pembayaran
        if ($user['peran'] === 'siswa') {
            // Ambil id_pendaftaran pengguna
            $stmt_pendaftaran = $conn->prepare("SELECT id_pendaftaran FROM tbl_pendaftaran WHERE id_pengguna = ?");
            $stmt_pendaftaran->bind_param("i", $user['id_pengguna']);
            $stmt_pendaftaran->execute();
            $result_pendaftaran = $stmt_pendaftaran->get_result();
            $pendaftaran = $result_pendaftaran->fetch_assoc();

            $stmt_siswa = $conn->prepare("SELECT ts.id_siswa, ts.nama_siswa, ts.status 
                        FROM tb_siswa ts
                        INNER JOIN tbl_pendaftaran tp ON ts.id_pendaftaran = tp.id_pendaftaran
                        WHERE tp.id_pengguna = ? AND ts.status = 'aktif'");
            $stmt_siswa->bind_param("i", $user['id_pengguna']);
            $stmt_siswa->execute();
            $result_siswa = $stmt_siswa->get_result();
            $siswa = $result_siswa->fetch_assoc();

            
            if ($siswa) {
              // Set session siswa
              $_SESSION['id_siswa'] = $siswa['id_siswa'];
              $_SESSION['nama_siswa'] = $siswa['nama_siswa'];
            }

            if ($pendaftaran) {
                // Ambil status pembayaran dari tb_payment
                $stmt_payment = $conn->prepare("SELECT status_pembayaran FROM tb_payment WHERE id_pendaftaran = ?");
                $stmt_payment->bind_param("i", $pendaftaran['id_pendaftaran']);
                $stmt_payment->execute();
                $result_payment = $stmt_payment->get_result();
                $payment = $result_payment->fetch_assoc();   
            }

        }

        // Arahkan user sesuai peran
        if ($user['peran'] == 'admin') {
          header("location: ./admin/index.php");
      } elseif ($user['peran'] == 'guru') {
          header("location: ./guru/index.php");
      } elseif ($user['peran'] == 'siswa') {
          header("location: ./siswa/index.php");
      } else {
          $_SESSION['error'] = "Akses ditolak";
          header("location: login.php");
      }
        exit;
    } else {
        $_SESSION['error'] = $user ? "Password salah." : "Username atau email tidak ditemukan.";
        header("location: login.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap -->
  <link rel="icon" type="image/png" href="img/logo1.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/cyborg/bootstrap.min.css"
    integrity="sha384-nEnU7Ae+3lD52AK+RGNzgieBWMnEfgTbRHIwEvp1XXPdqdO6uLTd/NwXbzboqjc2" crossorigin="anonymous">
` 
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    body {
      background-color: #f5f0bb;
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      height: 100vh;
      color: #fff;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
    }


    .navbar {
      background-color: #73A9AD !important;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }



    .clock {
      color: #90C8AC;
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 26px;
      animation: fadeIn 1s;
    }

    .login-container {
      margin-top: 100px;
      /* Atur sesuai kebutuhan */
      border-radius: 20px;
      background-color: rgba(50, 50, 50, 0.5);
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
      animation: slideIn 0.5s forwards;
      padding: 20px;
      max-width: 1000px;
      color: white;
    }

    .title {
      text-align: center;
      font-size: 36px;
      font-weight: 800;
      animation: fadeIn 1s;
      color: black;
      text-shadow: 2px 2px 0px white;
    }

    .card {
      background-color: rgba(200, 200, 200, 0.5);
    }

    label {
      color: white;
    }

    @keyframes slideIn {
      from {
        transform: translateY(-200%);
      }

      to {
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark w-100">
    <a class="navbar-brand" href="#"><i class="fa-solid fa-book"></i> Rumah Belajar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="fas fa-house"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php"><i class="fas fa-sign-in"></i>Register</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="clock py-5" id="clock"></div>
  <script>
    function updateClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('clock').innerText = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock(); // Initialize clock immediately
  </script>
  <!-- Tampilkan pesan sukses atau error jika ada -->
  <div class="login-container">
    <div class="card">
      <div class="card-header text-center">
        <h4 class="text-white">Login</h4>
      </div>
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
        <div class="card-body">
          <form action="login.php" method="POST">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" name="username" class="form-control" required  autocomplete="off">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="pass" class="form-control" required>
            </div>
            <button  name="login" type="submit" class="btn btn-primary btn-block">Login</button>
          </form>
          <div class="text-center mt-3">
            <p>Belum memiliki akun? <b><a href="register.php" class="btn btn-warning">Daftar di
                  sini</a></b></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  

  <div class="container text-center py-5 mt-5">
    <p class="mb-0">&copy; 2024 Rumah Belajar. All Rights Reserved.</p>
    <div class="social-icons mt-3">
      <a href="#" class="mx-2"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="mx-2"><i class="fab fa-twitter"></i></a>
      <a href="#" class="mx-2"><i class="fab fa-instagram"></i></a>
      <a href="#" class="mx-2"><i class="fab fa-linkedin-in"></i></a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>  
</body>

</html>