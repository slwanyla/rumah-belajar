<?php
session_start();
require './admin/koneksi.php';

// Kelas User
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserByCredentials($username, $email, $nomor_hp) {
      $stmt = $this->conn->prepare("SELECT * FROM tb_pengguna WHERE username = ? OR email = ? OR nomor_hp = ?");
      $stmt->bind_param("sss", $username, $email, $nomor_hp);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_assoc();
    }

    public function register($nama, $hp, $username, $email, $password) {
       
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tb_pengguna (nama, nomor_hp, username, email, pass, peran) VALUES (?, ?, ?, ?, ?, 'siswa')");
        $stmt->bind_param("sssss", $nama, $hp, $username, $email, $hashedPassword);
        $stmt->execute();
        return $stmt->insert_id;
    }
    
}

// Kelas Course
class Course {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCourseByName($courseName) {
        $courseName = strtolower(trim($courseName));
        $stmt = $this->conn->prepare("SELECT * FROM tbl_kursus WHERE LOWER(TRIM(nama_kursus)) = ?");
        $stmt->bind_param("s", $courseName);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getPriceByCourseId($id_kursus) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_harga WHERE id_kursus = ?");
        $stmt->bind_param("i", $id_kursus);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getPriceByCourseIdAndPertemuan($id_kursus, $pertemuan) {
      $stmt = $this->conn->prepare("SELECT * FROM tbl_harga WHERE id_kursus = ? AND pertemuan = ?");
      $stmt->bind_param("ii", $id_kursus, $pertemuan);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_assoc();
    }

    public function getKelasId($kelas) {
        $kelas = strtolower(trim($kelas));
        $stmt = $this->conn->prepare("SELECT id_kelas FROM tb_kelas WHERE LOWER(TRIM(nama_kelas)) = ?");
        $stmt->bind_param("s", $kelas);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['id_kelas'] ?? null;
    }

    public function registerCourse($id_user, $id_harga, $id_kelas, $id_kursus, $tanggal) {
        $stmt = $this->conn->prepare("INSERT INTO tbl_pendaftaran (id_pengguna, id_harga, id_kelas, id_kursus, tanggal_pendaftaran) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $id_user, $id_harga, $id_kelas, $id_kursus, $tanggal);
        $stmt->execute();
        return $stmt->insert_id;
    }

    
  

}

// Inisialisasi Kelas
$user = new User($conn);
$course = new Course($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
  $nama_lengkap = $_POST['nama_lengkap'];
  $nomor_hp = $_POST['nomor_hp'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['pass'];
  $kelas = $_POST['kelas'];
  date_default_timezone_set('Asia/Jakarta');
  $tanggal_pendaftaran = date('Y-m-d H:i:s');
  $kursus = $_POST['kursus'] ?? [];
  $id_pendaftaran = $_POST['id_pendaftaran'];

  

  try {
      // Cek apakah user sudah terdaftar
      $existing_user = $user->getUserByCredentials($username, $email, $nomor_hp);
      $id_kelas = $course->getKelasId($kelas);
      
      if (!$id_kelas) {
          $_SESSION['error'] = "Kelas tidak ditemukan.";
          header("location: register.php");
          exit;
      }

      // Jika user sudah ada
      if ($existing_user) {
          
          // Gunakan ID user yang sudah ada
          $id_user = $existing_user['id_pengguna'];
          $_SESSION['id_pengguna'] = $id_user;
      } else {
          // Jika user baru, lakukan registrasi
          $id_user = $user->register($nama_lengkap, $nomor_hp, $username, $email, $password);
          if (!$id_user) {
              throw new Exception("Proses penyimpanan user gagal!");
          }
      }

      $total_bayar = 0;
      $pertemuan = [];
      $id_pendaftaran = null;

      // Proses pendaftaran kursus
      foreach ($kursus as $kursus1) {
          $dataKursus = $course->getCourseByName($kursus1);
          if (!$dataKursus) {
              $_SESSION['error'] = "Kursus $kursus1 tidak ditemukan.";
              header("location: register.php");
              exit;
          }
          
          $id_kursus = $dataKursus['id_kursus'];
          $pertemuan_kursus = $_POST["pertemuan_" . strtolower($kursus1)] ?? null; // Pastikan nama sesuai


          if ($pertemuan_kursus) {
              $dataHarga = $course->getPriceByCourseIdAndPertemuan($id_kursus, $pertemuan_kursus);
              if (!$dataHarga) {
                  throw new Exception("Harga tidak ditemukan untuk kursus.");
              }

              $id_pendaftaran = $course->registerCourse($id_user, $dataHarga['id_harga'], $id_kelas, $id_kursus, $tanggal_pendaftaran);
              $total_bayar += $dataHarga['harga'];
              $pertemuan[] = $pertemuan_kursus;
          } else {
              $_SESSION['error'] = "Silakan pilih pertemuan untuk kursus $kursus1.";
              header("location: register.php");
              exit;
          }
      }

      if (!$id_pendaftaran) {
          throw new Exception("Gagal mendapatkan ID pendaftaran");
      }

      $_SESSION['kursus'] = implode(", ", $kursus);
      $_SESSION['pertemuan'] = implode(", ", $pertemuan);
      $_SESSION['total'] = $total_bayar;
      $_SESSION['id_pendaftaran'] = $id_pendaftaran;
      $_SESSION['nama'] = $nama_lengkap;
      $_SESSION['id_pengguna'] = $id_user;
      $_SESSION['id_kelas'] = $id_kelas;

      $_SESSION['success'] = "Pendaftaran berhasil! Silakan lakukan pembayaran.";
      header("location: ../payment/payment.php");
      exit;
  } catch (Exception $e) {
      error_log($e->getMessage());
      $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
      header("location: register.php");
      exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" type="image/png" href="../img/logo1.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/cyborg/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    body {
      background-color: #f5f0bb;
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      color: #fff;
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
      border-radius: 20px;
      background-color: rgba(50, 50, 50, 0.5);
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
      animation: slideIn 0.5s forwards;
      padding: 20px;
      color: black;
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

    .container {
      padding-top: 100px;
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
  <!-- Alert Scripts -->
  <?php if (isset($_SESSION['success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Register Berhasil',
      text: 'Register berhasil! Silakan Melakukan Pembayaran.',
      confirmButtonText: 'Ok',
      html: '<?php echo $_SESSION['success']; ?>'
    });
    <?php unset($_SESSION['success']); ?>
  </script>
  <?php elseif (isset($_SESSION['error'])): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Register Gagal',
        text: '<?php echo $_SESSION['error']; ?>'
      });
      <?php unset($_SESSION['error']); ?>
    </script>
  <?php endif; ?>

  <nav class="navbar navbar-expand-lg navbar-dark w-100">
    <a class="navbar-brand" href="index.php"><i class="fa-solid fa-book"></i> Rumah Belajar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php"><i class="fas fa-house"></i> Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php"><i class="fas fa-sign-in"></i> Login</a>
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
    updateClock();
  </script>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 mt-2">
        <div class="login-container">
          <div class="card">
            <div class=" card-header text-center">
              <h4 class="text-white">Register</h4>
            </div>
             <!-- Tampilkan pesan sukses atau error jika ada -->
            <div class="d-flex justify-content-center">
              <div class="text-center col-md-8">
                  <?php
                  if (isset($success)) {
                      echo "<div class='alert alert-success'>$success</div>";
                  }
                  if (isset($error)) {
                       echo "<div class='alert alert-danger'>$error</div>";
                  }
                  ?>
              </div>
            </div>
            <div class="card-body">
              <form method="POST" action="register.php">
                <div class="form-group">
                  <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                  <label for="nomor_hp" class="form-label">Nomor HP</label>
                  <input type="number" class="form-control" id="nomor_hp" name="nomor_hp" placeholder="Nomor HP" required>
                </div>
                <div class="form-group">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="pass" name="pass" placeholder="Password" required>
                </div>
                <div class="form-group">
                  <label>Pilih Kursus</label><br>
                  <input type="checkbox" name="kursus[]" value="matematika" id="kursus_mat" onclick="toggleKursus('Matematika')"> 
                  <label for="kursus_mat">Matematika</label>
                  <input type="checkbox" name="kursus[]" value="tajwid" id="kursus_taj" onclick="toggleKursus('Tajwid')"> 
                  <label for="kursus_taj">Tajwid</label>
                </div>
                <div id="pertemuan" class="form-group" style="display:none;">
                    <div id="pertemuan_mat" style="display:none;">
                      <label>Pertemuan Matematika</label>
                      <div id="pertemuan_options_mat"></div>
                    </div>
                    <div id="pertemuan_taj" style="display:none;">
                      <label>Pertemuan Tajwid</label>
                      <div id="pertemuan_options_taj"></div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="kelas">Pilih Kelas</label>
                  <select name="kelas" id="kelas" class="form-control" required>
                    <option value="">Pilih Kelas</option>
                    <option value="TK">TK</option>
                    <option value="1 SD">Kelas 1 SD</option>
                    <option value="2 SD">Kelas 2 SD</option>
                    <option value="3 SD">Kelas 3 SD</option>
                    <option value="4 SD">Kelas 4 SD</option>
                    <option value="5 SD">Kelas 5 SD</option>
                    <option value="6 SD">Kelas 6 SD</option>
                    <option value="1 SMP">Kelas 1 SMP</option>
                    <option value="2 SMP">Kelas 2 SMP</option>
                    <option value="3 SMP">Kelas 3 SMP</option>
                  </select>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
              </form>
              <div class="text-center mt-3">
                  <p>Sudah memiliki akun? <b><a href="login.php" class="btn btn-success">Login</a></b></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
        <script>
          function toggleKursus(kursus) {
               // Get all course checkboxes
              const checkboxes = document.querySelectorAll('input[name="kursus[]"]');
              const clicked = document.getElementById(`kursus_${kursus.substring(0,3).toLowerCase()}`);
              
              // Show/hide pertemuan div based on any checkbox being checked
              const pertemuanDiv = document.getElementById('pertemuan');
              const pertemuanMat = document.getElementById('pertemuan_mat');
              const pertemuanTaj = document.getElementById('pertemuan_taj');

              // Clear all pertemuan options first
              document.getElementById('pertemuan_options_mat').innerHTML = '';
              document.getElementById('pertemuan_options_taj').innerHTML = '';

              // Uncheck other checkboxes
              checkboxes.forEach(checkbox => {
                  if (checkbox !== clicked) {
                      checkbox.checked = false;
                  }
              });

              // Show/hide appropriate sections
              pertemuanDiv.style.display = clicked.checked ? 'block' : 'none';
              pertemuanMat.style.display = (kursus === 'Matematika' && clicked.checked) ? 'block' : 'none';
              pertemuanTaj.style.display = (kursus === 'Tajwid' && clicked.checked) ? 'block' : 'none';

              // Update pertemuan options if checkbox is checked
              if (clicked.checked) {
                  updatePertemuan(kursus);
              }
          }


          function updatePertemuan(kursus) {
              console.log("Kursus yang dipilih: ", kursus); 
              var pertemuanOptions = [];
              var pertemuanOptionsDiv;

              if (kursus === "Matematika") {
                  pertemuanOptions = [
                      { value: "8", text: "8x pertemuan RP200.000/bulan" },
                      { value: "12", text: "12x pertemuan RP300.000/bulan" },
                  ];
                  pertemuanOptionsDiv = document.getElementById('pertemuan_options_mat');
              } else if (kursus === "Tajwid") {
                  pertemuanOptions = [
                      { value: "8", text: "8x pertemuan (seikhlasnya)/bulan" },
                      { value: "12", text: "12x pertemuan (seikhlasnya)/bulan" }
                  ];
                  pertemuanOptionsDiv = document.getElementById('pertemuan_options_taj');
              }

              // Bersihkan opsi sebelumnya
              pertemuanOptionsDiv.innerHTML = '';

              // Tambahkan opsi ke div
              pertemuanOptions.forEach(function(option) {
                  var radioDiv = document.createElement("div");
                  radioDiv.className = "form-check";

                  var input = document.createElement("input");
                  input.type = "radio";
                  input.className = "form-check-input";
                  input.id = `pertemuan_${kursus}_${option.value}`;
                  input.name = `pertemuan_${kursus.toLowerCase()}`; // Pastikan nama sesuai
                  input.value = option.value;

                  var label = document.createElement("label");
                  label.className = "form-check-label";
                  label.htmlFor = input.id;
                  label.textContent = option.text;

                  radioDiv.appendChild(input);
                  radioDiv.appendChild(label);
                  pertemuanOptionsDiv.appendChild(radioDiv);
              });
          }
              // Fungsi validasi saat form disubmit
          document.addEventListener('DOMContentLoaded', function () {
              // Ambil form
              const form = document.querySelector('form');

              // Event listener untuk validasi form
              form.addEventListener('submit', function (e) {
                  let valid = true; // Flag untuk validasi

                  // Validasi kursus
                  const kursusChecked = document.querySelectorAll('input[name="kursus[]"]:checked');
                  if (kursusChecked.length === 0) {
                      alert('Harap pilih minimal satu kursus!');
                      valid = false;
                  }

                  // Validasi jumlah pertemuan untuk kursus yang dipilih
                  kursusChecked.forEach((checkbox) => {
                      const kursus = checkbox.value; // Nama kursus (e.g., matematika/tajwid)
                      const pertemuanChecked = document.querySelector(`input[name="pertemuan_${kursus}"]:checked`);
                      if (!pertemuanChecked) {
                          alert(`Harap pilih jumlah pertemuan untuk kursus ${kursus.charAt(0).toUpperCase() + kursus.slice(1)}!`);
                          valid = false;
                      }
                  });

                  // Jika ada validasi yang gagal, hentikan pengiriman form
                  if (!valid) {
                      e.preventDefault(); // Hentikan pengiriman form
                  }
              });
          });
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
