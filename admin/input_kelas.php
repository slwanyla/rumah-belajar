<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Input Data </span> Kelas</h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                    include 'koneksi.php'; // Koneksi database

                                    if (isset($_POST['input'])) {
                                        // Ambil data dari form
                                        $nama_kelas = $_POST['nama_kelas'];

                                        // Simpan data kelas ke tabel tb_kelas
                                        $query = $conn->prepare("INSERT INTO tb_kelas (nama_kelas) VALUES (?)");
                                        $query->bind_param("s", $nama_kelas);

                                        if ($query->execute()) {
                                            echo "<script>alert('Input Data Kelas Sukses!'); window.location = '?page=inputkelas';</script>";
                                        } else {
                                            echo "<script>alert('Input Data Kelas Gagal!'); window.location = '?page=inputkelas';</script>";
                                        }
                                    }
                                ?>

                                <form method="post">
                                    <div class="form-group">
                                        <label class="mb-2">Nama Kelas</label>
                                        <input class="form-control mb-2" name="nama_kelas" placeholder="Nama Kelas" maxlength="10" required>
                                        <small class="text-muted">Maksimal 10 karakter</small>
                                    </div>
                                    <input type="submit" name="input" class="btn btn-primary" value="Input" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>