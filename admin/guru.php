<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Input Data </span> Guru</h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                    require_once '../admin/koneksi.php'; // Koneksi database

                                    if (isset($_POST['input'])) {
                                        // Ambil data dari form
                                        $nama_guru = $_POST['nama_guru'];
                                        $id_kursus = $_POST['id_kursus']; // ID kursus dari form
                                        $username = $_POST['username']; // Username dari form

                                        // Debugging input form
                                        var_dump($nama_guru, $id_kursus, $username);

                                        // Ambil id_pengguna berdasarkan username
                                        $query1 = $conn->prepare("SELECT id_pengguna FROM tb_pengguna WHERE username = ?");
                                        $query1->bind_param("s", $username);
                                        $query1->execute();
                                        $result = $query1->get_result();

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $id_pengguna = $row['id_pengguna']; // Dapatkan id_pengguna

                                            // Debug id_pengguna
                                            var_dump($id_pengguna);

                                            // Simpan data guru ke tabel tb_guru
                                            $query2 = $conn->prepare("INSERT INTO tb_guru (nama_guru, id_kursus, id_pengguna) VALUES (?, ?, ?)");
                                            $query2->bind_param("sii", $nama_guru, $id_kursus, $id_pengguna);

                                            if ($query2->execute()) {
                                                echo "<script>alert('Input Data Guru Sukses!'); window.location = '?page=inputguru';</script>";
                                            } else {
                                                echo "<script>alert('Input Data Guru Gagal!'); window.location = '?page=inputguru';</script>";
                                            }
                                        } else {
                                            echo "<script>alert('Username tidak ditemukan!'); window.location = '?page=inputguru';</script>";
                                        }
                                    }
                                ?>

                                <form method="post">
                                    <div class="form-group">
                                        <label class="mb-2">Nama Guru</label>
                                        <input class="form-control mb-2" name="nama_guru" placeholder="Nama Guru" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Kursus</label>
                                        <select name="id_kursus" class="form-control mb-2" required>
                                            <option value="">Pilih Kursus</option>
                                            <option value="1">Tajwid</option>
                                            <option value="2">Matematika</option>
                                            <!-- Tambahkan kursus lain sesuai database -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Username</label>
                                        <input class="form-control mb-2" name="username" placeholder="Username" required>
                                    </div>
                                    <input type="submit" name="input" class="btn btn-primary" value="Input" />
                                </form>
