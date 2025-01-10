<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light"> Data </span> Siswa</h4>
        <?php
        include ("koneksi.php");

        class StudentDisplay {
            private $conn;
            
            public function __construct($conn) {
                $this->conn = $conn;
            }
            
            public function displayStudentsByClass($id_kelas) {
                error_log("Fetching students for class ID: " . $id_kelas);
                
                $sql = "SELECT s.*, k.nama_kelas, ku.nama_kursus 
                        FROM tb_siswa s
                        JOIN tbl_pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran
                        JOIN tb_kelas k ON p.id_kelas = k.id_kelas
                        JOIN tbl_kursus ku ON p.id_kursus = ku.id_kursus
                        WHERE p.id_kelas = ?";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $id_kelas);
                $stmt->execute();
                $result = $stmt->get_result();
                
                error_log("Found " . $result->num_rows . " students for class ID: " . $id_kelas);
                
                if ($result && $result->num_rows > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-hover table-bordered table-striped">';
                    echo '<thead class="bg-info">';
                    echo '<tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Kursus</th>
                            <th>Status</th>
                            <th>Sisa Pertemuan</th>
                            <th>Action</th>
                        </tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . htmlspecialchars($row['nama_siswa'] ?? '') . '</td>
                                <td>' . htmlspecialchars($row['nama_kelas'] ?? '') . '</td>
                                <td>' . htmlspecialchars($row['nama_kursus'] ?? '') . '</td>
                                <td>' . htmlspecialchars($row['status'] ?? '') . '</td>
                                <td>' . htmlspecialchars($row['sisa_pertemuan'] ?? '') . '</td>
                                <td>
                                    <a onclick="return confirm(\'Yakin akan hapus data ini?\')" href="siswa_hapus.php?id=' . ($row['id_siswa'] ?? '') . '" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p class="text-muted">Tidak ada data siswa untuk kelas ini.</p>';
                }
            }
            
            public function displayAllClassTabs() {
                $classes = $this->getClasses();
                
                if (!$classes) {
                    echo "Error fetching classes: " . mysqli_error($this->conn);
                    return;
                }
                
                $classesData = [];
                while ($row = mysqli_fetch_array($classes)) {
                    if (!empty($row['id_kelas']) && !empty($row['nama_kelas'])) {  // Only add if required data exists
                        $classesData[] = $row;
                    }
                }
                
                if (empty($classesData)) {
                    echo '<div class="alert alert-warning">Tidak ada data kelas yang tersedia.</div>';
                    return;
                }
                
                // Display tabs
                echo '<ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-bottom:10px;">';
                foreach ($classesData as $index => $class) {
                    $isActive = $index === 0 ? 'active' : '';
                    $classId = htmlspecialchars($class['id_kelas'] ?? '');
                    $className = htmlspecialchars($class['nama_kelas'] ?? '');
                    
                    if (!empty($classId) && !empty($className)) {
                        echo '<li class="nav-item" role="presentation">';
                        echo '<a class="nav-link ' . $isActive . '" ';
                        echo 'id="tab-' . $className . '" ';
                        echo 'data-bs-toggle="tab" ';
                        echo 'href="#class-' . $classId . '" ';
                        echo 'role="tab">';
                        echo 'Kelas ' . $className;
                        echo '</a>';
                        echo '</li>';
                    }
                }
                echo '</ul>';
                
                // Display content
                echo '<div class="tab-content" id="myTabContent">';
                foreach ($classesData as $index => $class) {
                    $isActive = $index === 0 ? 'show active' : '';
                    $classId = htmlspecialchars($class['id_kelas'] ?? '');
                    $className = htmlspecialchars($class['nama_kelas'] ?? '');
                    
                    if (!empty($classId) && !empty($className)) {
                        echo '<div class="tab-pane fade ' . $isActive . '" ';
                        echo 'id="class-' . $classId . '" ';
                        echo 'role="tabpanel">';
                        echo '<div class="card">';
                        echo '<div class="card-body">';
                        echo '<br>';
                        echo '<label>Kelas: ' . $className . '</label>';
                        $this->displayStudentsByClass($class['id_kelas']);
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
            
            public function getClasses() {
                $sql = "SELECT id_kelas, nama_kelas FROM tb_kelas ORDER BY id_kelas ASC";
                return mysqli_query($this->conn, $sql);
            }
        }
        
        // Usage
        $display = new StudentDisplay($conn);
        $display->displayAllClassTabs();
        ?>
    </div>
</div>
        
<!-- Include necessary scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
