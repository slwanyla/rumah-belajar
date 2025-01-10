<?php
session_start();
require_once "./admin/koneksi.php";

// Check if user is logged in
if (!isset($_SESSION['id_pengguna'])) {
    echo "Session ID Pengguna tidak ditemukan.";
}

class TambahKursus {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getExistingCourses($id_pengguna) {
        $query = "SELECT DISTINCT k.nama_kursus 
                 FROM tbl_pendaftaran p
                 JOIN tbl_kursus k ON p.id_kursus = k.id_kursus
                 WHERE p.id_pengguna = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row['nama_kursus'];
        }
        return $courses;
    }

    public function getAvailableCourses($id_pengguna) {
        $query = "SELECT k.nama_kursus 
                  FROM tbl_kursus k
                  WHERE k.id_kursus NOT IN (
                      SELECT p.id_kursus 
                      FROM tbl_pendaftaran p
                      INNER JOIN tb_siswa s ON p.id_pendaftaran = s.id_pendaftaran
                      WHERE s.id_siswa IS NOT NULL 
                      AND s.status = 'aktif' 
                      AND p.id_pengguna = ?
                  )";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();
        $availableCourses = [];
        while ($row = $result->fetch_assoc()) {
            $availableCourses[] = $row['nama_kursus'];
        }
        return $availableCourses;
    }

    public function getCourseIdByName($courseName) {
        $stmt = $this->conn->prepare("SELECT id_kursus FROM tbl_kursus WHERE nama_kursus = ?");
        $stmt->bind_param("s", $courseName);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data ? $data['id_kursus'] : null;
    }

    public function getHargaByCourse($kursus, $pertemuan) {
        $stmt = $this->conn->prepare("SELECT id_kursus FROM tbl_kursus WHERE nama_kursus = ?");
        $stmt->bind_param("s", $kursus);
        $stmt->execute();
        $result = $stmt->get_result();
        $kursus_data = $result->fetch_assoc();
        
        if (!$kursus_data) {
            return null;
        }

        $stmt = $this->conn->prepare("SELECT id_harga FROM tbl_harga WHERE id_kursus = ? AND pertemuan = ?");
        $stmt->bind_param("ii", $kursus_data['id_kursus'], $pertemuan);
        $stmt->execute();
        $result = $stmt->get_result();
        $harga_data = $result->fetch_assoc();
        
        return $harga_data ? $harga_data['id_harga'] : null;
    }

    public function insertNewCourse($id_pengguna, $kursus, $pertemuan, $id_kelas) {
        // Get course ID
        $stmt = $this->conn->prepare("SELECT id_kursus FROM tbl_kursus WHERE nama_kursus = ?");
        $stmt->bind_param("s", $kursus);
        $stmt->execute();
        $result = $stmt->get_result();
        $course_data = $result->fetch_assoc();

        if (!$course_data) {
            throw new Exception("Kursus tidak ditemukan.");
        }

        // Check if user is already registered
        $existing_courses = $this->getExistingCourses($id_pengguna);
        if (in_array($kursus, $existing_courses)) {
            throw new Exception("Anda sudah terdaftar untuk kursus ini.");
        }

        // Get price for selected course and meetings
        $id_harga = $this->getHargaByCourse($kursus, $pertemuan);
        if (!$id_harga) {
            throw new Exception("Harga untuk kursus {$kursus} dengan {$pertemuan} pertemuan tidak ditemukan.");
        }

        // Insert into tbl_pendaftaran
        $stmt = $this->conn->prepare(
            "INSERT INTO tbl_pendaftaran (id_pengguna, id_kursus, id_harga, id_kelas, tanggal_pendaftaran) 
             VALUES (?, ?, ?, ?, CURRENT_DATE())"
        );
        
        $stmt->bind_param("iiii", 
            $id_pengguna, 
            $course_data['id_kursus'], 
            $id_harga, 
            $id_kelas
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
}

$tambahKursus = new TambahKursus($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id_pengguna = $_SESSION['id_pengguna'];
        $kursus = $_POST['kursus'] ?? [];
        $pertemuan = [];
        
        // Validation
        if (empty($kursus)) {
            throw new Exception("Silakan pilih kursus.");
        }

        foreach ($kursus as $kursus_item) {
            $pertemuan_kursus = $_POST["pertemuan_{$kursus_item}"] ?? null;
            if (empty($pertemuan_kursus)) {
                throw new Exception("Silakan pilih pertemuan untuk kursus {$kursus_item}.");
            } else {
                $pertemuan[$kursus_item] = $pertemuan_kursus;
            }
        }

        // Get id_kelas from session
        $id_kelas = $_SESSION['id_kelas'] ?? null;
        if (!$id_kelas) {
            throw new Exception("Kelas tidak ditemukan.");
        }

        foreach ($kursus as $kursus_item) {
            $pertemuan_item = $pertemuan[$kursus_item];
            try {
                $id_pendaftaran = $tambahKursus->insertNewCourse(
                    $id_pengguna, 
                    $kursus_item, 
                    $pertemuan_item, 
                    $id_kelas
                );
                
                if (!$id_pendaftaran) {
                    throw new Exception("Gagal menambahkan kursus {$kursus_item}");
                }

                // Save registration info to session
                $_SESSION['id_pendaftaran'] = $id_pendaftaran;
                $_SESSION['kursus'] = $kursus_item;
                $_SESSION['pertemuan'] = $pertemuan_item . "x pertemuan";
                
                // Calculate total price
                if ($kursus_item == "Matematika") {
                    $harga = ($pertemuan_item == 8) ? 200000 : 300000;
                    $_SESSION['total'] = $harga;
                } else {
                    $_SESSION['total'] = 0; // For Tajwid course (pay as you wish)
                }

                

                // Redirect to payment page
                header("Location: ./payment/payment.php");
                exit;
            } catch (Exception $e) {
                throw new Exception("Error saat menambahkan kursus {$kursus_item}: " . $e->getMessage());
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: tambah.php");
        exit;
    }
}

// Get list of registered courses
$existing_courses = $tambahKursus->getExistingCourses($_SESSION['id_pengguna']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/cyborg/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f5f0bb;
            color: #fff;
            padding-top: 60px;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            background-color: rgba(200, 200, 200, 0.5);
            border-radius: 15px;
        }
        .navbar {
            background-color: #73A9AD !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="dashboard.php">
            <i class="fa-solid fa-book"></i> Rumah Belajar
        </a>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="text-white">Tambah Kursus</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="tambah.php">
                            <div class="form-group">
                                <label>Pilih Kursus</label><br>
                                <?php
                                $available_courses = $tambahKursus->getAvailableCourses($_SESSION['id_pengguna']);
                                
                                if (!empty($available_courses)) {
                                    foreach ($available_courses as $course) {
                                        $course_id = strtolower(substr($course, 0, 3));
                                        echo '<input type="checkbox" name="kursus[]" value="' . $course . '" id="kursus_' . $course_id . '" onclick="toggleKursus(\'' . $course_id . '\')"> ';
                                        echo '<label for="kursus_' . $course_id . '">' . $course . '</label><br>';
                                    }
                                } else {
                                    echo '<p class="text-muted">Anda sudah terdaftar di semua kursus yang tersedia.</p>';
                                }
                                ?>
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
                            <button type="submit" class="btn btn-primary w-100">Daftar Kursus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleKursus(kursus) {
            var pertemuanDiv = document.getElementById('pertemuan');
            var pertemuanMat = document.getElementById('pertemuan_mat');
            var pertemuanTaj = document.getElementById('pertemuan_taj');
            var pertemuanVisible = false;

            // Reset all meeting options
            document.getElementById('pertemuan_options_mat').innerHTML = '';
            document.getElementById('pertemuan_options_taj').innerHTML = '';

            // Check all selected checkboxes
            document.querySelectorAll('input[name="kursus[]"]').forEach(function(checkbox) {
                if (checkbox.checked) {
                    pertemuanVisible = true;
                    updatePertemuan(checkbox.value);
                    console.log(`Kursus selected: ${checkbox.value}`);
                }
            });

            pertemuanDiv.style.display = pertemuanVisible ? 'block' : 'none';
            pertemuanMat.style.display = document.getElementById('kursus_mat')?.checked ? 'block' : 'none';
            pertemuanTaj.style.display = document.getElementById('kursus_taj')?.checked ? 'block' : 'none';
        }

        function updatePertemuan(kursus) {
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

            pertemuanOptions.forEach(function(option) {
                var label = document.createElement("label");
                label.textContent = option.text;

                var input = document.createElement("input");
                input.type = "radio";
                input.name = `pertemuan_${kursus}`;
                input.value = option.value;

                pertemuanOptionsDiv.appendChild(input);
                pertemuanOptionsDiv.appendChild(label);
                pertemuanOptionsDiv.appendChild(document.createElement("br"));
            });

            console.log(`Updated meeting options for ${kursus}`);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>