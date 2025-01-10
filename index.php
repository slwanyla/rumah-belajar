<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    

    <!---Bootstrap CSS--->
    <link rel="icon" type="image/png" href="img/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!---ionicons-->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="style.css">
    
</head>
<body>
  <!----Navbar--->
  <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid  ">
      <a class="navbar-brand" href="#">Rumah Belajar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Daftar</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <section class="hero-section">
    <div class="hero-content d-md-block">
      <h1>Selamat Datang di Rumah Belajar</h1>
      <p>Di sini kamu bisa belajar tanpa rasa khawatir, dengan pengajaran yang bikin kamu merasa di rumah sendiri!</p>
    </div>
  </section>


  <!--icon-->
  <div class="container">
    <div class="row text-center">
      <h2 class="mt-4 mb-4">Les Private di Rumah Belajar</h2>
      <div class="col-lg-4 d-md-block">
        <img src="img/like.png" class="icon-img ">
        <b style="color: #2193b0;">Para Guru Sudah Terlatih</b>
        <p>Memiliki kesabaran dalam membimbing dan memahami kebutuhan murid</p>
      </div>

      <div class="col-lg-4">
        <img src="img/com.png" class="icon-img">
        <b style="color: #2193b0;">Akses Materi Pelajaran yang Bisa Diulang Kapan Saja</b>
        <p>Setiap materi dan rekaman zoom yang telah diajarkan dapat diakses kapan saja.</p>
      </div>

      <div class="col-lg-4">
        <img src="img/crown.png" class="icon-img">
        <b style="color: #2193b0;">Konsultasi dan Dukungan 24/7</b>
        <p>Kami siap membantu kapan saja, memberikan dukungan dan konsultasi untuk memastikan kamu bisa belajar dengan lancar dan sukses.</p><br>
      </div>
    </div>
  </div>
    <!--tentang kami-->
  <div class="about-container">
    <div class="row text-center">
      <div data-aos="zoom-in-up">
        <h2 class="mb-4">Les Private Online TK-SMP</h2>
      </div>
      <p>Rumah belajar membuka les private online untuk TK-SMP, untuk sekarang kami fokus pada 2 mata pelajaran: <b>Matematika</b> dan <b>Tajwid.<br></b>
       Dengan pengajaran yang mudah dipahami dan yang menyenangkan, kami memastikan murid belajar dengan percaya diri, nyaman, dan mencapai hasil yang maksimal.<br></p>
       <!---kursus---->
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 >Matematika</h5>
            <p class="card-text"> Kursus Matematika di Rumah Belajar membantu anak-anak menguasai konsep-konsep dasar hingga tingkat lanjutan dengan pendekatan yang praktis dan aplikatif. 
            Setiap pelajaran disesuaikan dengan tingkat pemahaman mereka, sehingga belajar matematika menjadi lebih mudah, menyenangkan, dan relevan dengan kebutuhan sehari-hari.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5>Tajwid</h5>
            <p class="card-text"> Les Tajwid di Rumah Belajar ditujukan untuk mengajarkan cara membaca Al-Qur'an dengan benar sesuai dengan aturan tajwid.
             Dengan bimbingan langsung dari guru terlatih, anak-anak dapat memahami cara membaca Al-Qur'an dengan tepat, mulai dari dasar hingga tingkat mahir.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!---Program study-->
  <div class="programs-container">
    <div class="row text-center">
      <div class=" mb-3" data-aos="zoom-in-up" >  
        <h2>Program Private</h2>
      </div>
      <div class="col-lg-6 col-md-6 mb-4" data-aos="fade-right"> 
        <div  class="card h-100"> 
          <div class="card-body d-flex flex-column align-items-center">
            <ion-icon name="people-outline" size="large"></ion-icon>
            <h5 class="card-tittle">Pembelajaran Interaktif</h5>
            <p class="card-text">Semua murid akan mengikuti sesi pembelajaran bersama melalui Zoom. Dengan metode ini, 
            seluruh murid dapat belajar bersama dalam satu sesi yang terstruktur, 
            dengan tetap mendapatkan perhatian yang dibutuhkan masing-masing.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 mb-4" data-aos="fade-left">
        <div class="card h-100">
          <div class="card-body d-flex flex-column align-items-center">  
            <ion-icon name="time-outline" size="large"></ion-icon>
            <h5 class="card-tittle">Jadwal Tetap</h5>
            <p class="card-text">Pembelajaran dilakukan setiap malam setelah Isya, dimulai sekitar pukul 19.00 WIB.
            Jadwal tetap ini memberikan kenyamanan agar murid dapat belajar dengan fokus tanpa terganggu aktivitas harian.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 mb-4" data-aos="fade-right">
        <div class="card h-100">
          <div class="card-body d-flex flex-column align-items-center">  
            <ion-icon name="chatbubbles-outline" size="large"></ion-icon>
            <h5 class="card-tittle">Diskusi dan Tanya Jawab</h5>
            <p class="card-text">Setiap sesi diisi dengan diskusi mendalam serta kesempatan tanya jawab langsung untuk membantu siswa memahami materi secara menyeluruh.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 mb-4" data-aos="fade-left">
        <div class="card h-100">
          <div class="card-body d-flex flex-column align-items-center">  
            <ion-icon name="sparkles-outline" size="large"></ion-icon>
            <h5 class="card-tittle">Ceramah Inspiratif</h5>
            <p class="card-text">Selain pembelajaran teknis, 
            ada juga ceramah edukatif yang memberikan wawasan dan motivasi kepada siswa agar lebih semangat belajar.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!---Harga---->
  <div class="container text-center my-5">
    <h2 class="mb-4"  data-aos="zoom-in-up">Paket Kursus</h2>
    <div class="row justify-content-center">
      <!-- Kursus Matematika -->
      <div class="col-lg-5 col-md-6 mb-4 price-card">
        <div class="card shadow border-primary h-100">
          <div class="card-body">
            <h5 class="card-title text-primary">Kursus Matematika</h5>
            <p class="card-text">📅 <strong>12x Pertemuan</strong> - Rp 300.000/bulan</p>
            <p class="card-text">📅 <strong>8x Pertemuan</strong> - Rp 200.000/bulan</p>
            <a class="btn btn-primary mt-3" href="register.php?kursus=matematika">Daftar Sekarang</a>
          </div>
        </div>
      </div>
      <!-- Kursus Tajwid -->
      <div class="col-lg-5 col-md-6 mb-4 price-card">
        <div class="card shadow border-success h-100">
          <div class="card-body">
            <h5 class="card-title text-success">Kursus Tajwid</h5>
            <p class="card-text">📅 Mengikuti jadwal kursus matematika.</p>
            <p class="card-text text-muted">💰 <strong>Pembayaran Seikhlasnya</strong></p>
            <a href="register.php?kursus=tajwid" class="btn btn-success mt-3">Daftar Sekarang</a>
          </div>
        </div>
      </div>
    </div>
  </div>
      
      

  
<footer>
  
  <div class="container text-center py-4">
      <p class="mb-0">&copy; 2024 Rumah Belajar. All Rights Reserved.</p>
      <div class="social-icons mt-3">
        <a href="https://wa.me/+6288213125939" 
              target="_blank" style="display: inline-block; width: 50px; height: 50px; background-color: green; border-radius: 50%; text-align: center; line-height: 50px; color: white; font-size: 24px;">
            <i class="fab fa-whatsapp"></i>
        </a>
      </div>
    </div>
  </footer>
     
    <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

  <!-- AOS JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
     AOS.init();
  </script>
</body>
</html>
