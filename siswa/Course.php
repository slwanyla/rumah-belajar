<?php
require_once "../admin/koneksi.php";
$id_siswa = $_SESSION['id_siswa'];

// Modified query to use correct table structure
$query_siswa = "SELECT 
                s.id_siswa,
                p.id_kelas, 
                p.id_kursus, 
                k.nama_kelas,
                COALESCE(ku.nama_kursus, '') as nama_kursus
            FROM tb_siswa s
            JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
            JOIN tb_kelas k ON p.id_kelas = k.id_kelas
            LEFT JOIN tbl_kursus ku ON p.id_kursus = ku.id_kursus
            WHERE s.status = 'aktif'
            AND s.exp_date >= CURRENT_DATE()
            AND s.id_siswa = ?
            UNION
            SELECT 
                s.id_siswa,
                p2.id_kelas,
                p2.id_kursus,
                k2.nama_kelas,
                COALESCE(ku2.nama_kursus, '') as nama_kursus
            FROM tb_siswa s
            JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
            JOIN tbl_pendaftaran p2 ON p.id_pengguna = p2.id_pengguna
            JOIN tb_kelas k2 ON p2.id_kelas = k2.id_kelas
            LEFT JOIN tbl_kursus ku2 ON p2.id_kursus = ku2.id_kursus
            WHERE s.status = 'aktif'
            AND s.exp_date >= CURRENT_DATE()
            AND s.id_siswa = ?
            AND p2.id_pendaftaran != s.id_pendaftaran";

// Prepare statement
$stmt = mysqli_prepare($conn, $query_siswa);
if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($conn));
}

// Bind parameters - need to bind twice because we have two placeholders
mysqli_stmt_bind_param($stmt, "ii", $id_siswa, $id_siswa);

// Execute the statement
if (!mysqli_stmt_execute($stmt)) {
    die("Error executing statement: " . mysqli_stmt_error($stmt));
}

// Get the result
$result_siswa = mysqli_stmt_get_result($stmt);
if (!$result_siswa) {
    die("Error getting result: " . mysqli_error($conn));
}

// Store all courses in an array
$user_courses = [];

while ($row = mysqli_fetch_assoc($result_siswa)) {
    $user_courses[] = [
        'id_kelas' => $row['id_kelas'],
        'id_kursus' => $row['id_kursus']
    ];
}

// Close the statement
mysqli_stmt_close($stmt);

// Functions remain the same
function bolehAksesMateri($user_courses, $kelas_target) {
    if (!is_array($user_courses)) return false;
    
    foreach ($user_courses as $course) {
        if ($course['id_kelas'] == $kelas_target && $course['id_kursus'] == 2) {
            return true;
        }
    }
    return false;
}

function bolehAksesTajwid($user_courses) {
    if (!is_array($user_courses)) return false;
    
    foreach ($user_courses as $course) {
        if ($course['id_kursus'] == 1) { // 1 = tajwid
            return true;
        }
    }
    return false;
}
?>
<style>
  .cards-container {
      display: flex;
      flex-wrap: wrap; /* Allow cards to wrap to the next line */
      justify-content: flex-end; /* Align cards to the right */
      gap: 20px; /* Space between cards */
   }
   .card {
      max-width: 200px; /* Set a max width for the cards */
      margin: 10px; /* Add some margin around each card */
   }
   .card-welcome {
      background-color: white; /* Contoh: Warna background berbeda */
      box-shadow: none; 
      border-radius: 12px;        /* Hilangkan shadow */
      margin: 0;                /* Sesuaikan margin */
      padding: 20px;            /* Atur padding */
   }
   

</style>
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card-welcome">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <center>
                                    <h2>Selamat Datang <?php echo $_SESSION['username']; ?>, di Rumah Belajar</h2>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <section class="home-grid ">
            <h1 class="heading">My Courses</h1>
            <div class="cards-container">
               <!-- Card for Matematika -->
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/teaching.jpeg" class="card-img-top" alt="Matematika">
                  <div class="card-body">
                     <h5 class="card-title">Matematika TK</h5>
                     <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                     <?php
                     // Cek apakah siswa dari kelas dan kursus yang sesuai
                     if (bolehAksesMateri($user_courses, 1)) {
                        echo "<a href='index.php?page=materimatematikatk' class='btn btn-primary' onclick='openPage(\"materi/materi-matematika-tk.php\", \"0385750301\")' data-id='0385750301'>Start</a>";
                     } else {
                        echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas TK dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                     }
                     ?>
                       
                  </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/kls 1 sd.jpg" class="card-img-top" alt="Matematika">
                  <div class="card-body">
                     <h5 class="card-title">Matematika kls 1 sd</h5>
                     <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                     <?php
                     // Cek apakah siswa dari kelas yang sesuai
                     if (bolehAksesMateri($user_courses, 2)) {
                        echo "<a href='index.php?page=materimatematika1' class='btn btn-primary' onclick='openPage(\"materi/materi-matematika-1.php\", \"0280770381\")' data-id='0280770381'>Start</a>";
                     } else {
                        echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 1 SD dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                     }
                     ?>
                  </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/kls 2 sd.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika kls 2 sd</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 3)) {
                           echo "<a href='index.php?page=materimatematika2' class='btn btn-primary' onclick='openPage(\"materi/materi-matematika-2.php\", \"9473990020\")' data-id='9473990020'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 2 SD dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/kls 3 sd.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika kls 3 sd</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 4)) {
                           echo "<a href='index.php?page=materimatematika3' class='btn btn-primary' onclick='openPage(\"materi/materi-matematika-3.php\", \"96934883081\")' data-id='96934883081'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 3 SD dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/kls 4 sd.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika kls 4 sd</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 5)) {
                           echo "<a href='index.php?page=materimatematika4' class='btn btn-primary' onclick='openPage(\"materi/materi-matematika-4.php\", \"5062735879\")' data-id='5062735879'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 4 SD dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/kls 5 sd.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika kls 5 sd</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 6)) {
                           echo "<a href='index.php?page=materimatematika5' class='btn btn-primary' onclick='openPage(\"materi/materi-matematika-5.php\", \"7541614454\")' data-id='7541614454'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 5 SD dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/kls 6 sd.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika kls 6 sd</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 7)) {
                           echo "<a href='index.php?page=materimatematika6' class='btn btn-primary' onclick='openPage(\"materi/materi-matematika-6.php\", \"4786724620\")' data-id='4786724620'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 6 SD dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/smp kls 1.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika 1 smp</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 8)) {
                           echo "<a href='index.php?page=materismp1' class='btn btn-primary' onclick='openPage(\"materi/materi-smp-1.php\", \"2198291162\")' data-id='2198291162'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 1 SMP dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/smp kls 2.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika 2 smp</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 9)) {
                           echo "<a href='index.php?page=materismp2' class='btn btn-primary' onclick='openPage(\"materi/materi-smp-2.php\", \"6787857985\")' data-id='6787857985'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 2 SMP dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto;">
                  <img src="../img/smp kls 3.jpg" class="card-img-top" alt="Matematika">
                     <div class="card-body">
                        <h5 class="card-title">Matematika 3 smp</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Matematika.</p>
                        <?php
                        // Cek apakah siswa dari kelas yang sesuai
                        if (bolehAksesMateri($user_courses, 10)) {
                           echo "<a href='index.php?page=materismp3' class='btn btn-primary' onclick='openPage(\"materi/materi-smp-3.php\", \"4937957139\")' data-id='4937957139'>Start</a>";
                        } else {
                           echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kelas 3 SMP dan kursus Matematika untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
               <div class="card" style="max-width: 200px; margin: auto; margin-top: 20px;">
                  <img src="../img/tajwid.jpg" class="card-img-top" alt="Tajwid">
                     <div class="card-body">
                        <h5 class="card-title">Tajwid</h5>
                        <p class="card-text">Belajar dasar dan lanjutan Tajwid.</p>
                        <?php
                        if (bolehAksesTajwid($user_courses)) {
                            echo "<a href='index.php?page=materitajwid' class='btn btn-primary'>Start</a>";
                        } else {
                            echo "<button class='btn btn-secondary' onclick=\"alert('Maaf, Anda harus terdaftar di kursus Tajwid untuk akses materi ini.')\">Start</button>";
                        }
                        ?>
                     </div>
               </div>
         </section>
      </div>
   </div>
  
   <script>
      function openPage(pageUrl, id) {
         window.location.href = `${pageUrl}?id=${id}`;
      }
   </script>


