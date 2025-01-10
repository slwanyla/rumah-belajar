<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Input Data </span> Siswa</h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label class="mb-2">NIS</label>
                                        <input class="form-control mb-2" id="field2" name="nis" placeholder="NIS" required oninput="updateUsername()">
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Nama Siswa</label>
                                        <input class="form-control mb-2" name="nama_siswa" placeholder="Nama Siswa" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-control mb-2" required>
                                            <option value="">Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Agama</label>
                                        <input class="form-control mb-2" name="agama" placeholder="Agama" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Tempat Lahir</label>
                                        <input class="form-control mb-2" name="tempat_lahir" placeholder="Tempat Lahir" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Tanggal Lahir</label>
                                        <input type="date" class="form-control mb-2" name="tanggal_lahir" id="from">
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Alamat</label>
                                        <input class="form-control mb-2" name="alamat" placeholder="Alamat" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Nama Orang Tua/Wali</label>
                                        <input class="form-control mb-2" name="nama_ortu" placeholder="Nama Orang Tua/Wali" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Kelas</label>
                                        <select name="kelas" class="form-control mb-2" required>
                                            <option value="">Pilih Kelas</option>
                                            <?php
                                            $query = mysqli_query($conn, "select * from tb_kelas order by kelas asc");
                                            while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                                <option value="<?php echo $row['id_kelas']; ?>"><?php echo $row['kelas']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <h3 class="page-header mt-3" style="margin-top:-5px;">
                                        Input Account Siswa
                                    </h3>
                                    <div class="form-group">
                                        <label class="mb-2">Username (*masukkan NIS)</label>
                                        <input class="form-control mb-2" id="result2" name="username" placeholder="Username" readonly="readonly" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Password</label>
                                        <input type="password" class="form-control mb-2" name="pass" placeholder="Password" required>
                                    </div>
                                    <input type="submit" name="input" class="btn btn-primary" value="Input" />
                                </form>

                                <script type="text/javascript">
                                    function updateUsername() {
                                        // Set the value of "result2" field to match the value of "field2" field
                                        document.getElementById('result2').value = document.getElementById('field2').value;
                                    }
                                </script>

                                <?php
                                if (@$_POST['input']) {
                                    function ubahformatTgl($tanggal)
                                    {
                                        $pisah = explode('/', $tanggal);
                                        $urutan = array($pisah[2], $pisah[0], $pisah[1]);
                                        $satukan = implode('-', $urutan);
                                        return $satukan;
                                    }

                                    $username = $_POST['username'];
                                    $pass = $_POST['pass'];
                                    $peran = 'siswa';
                                    $nis = $_POST['nis'];
                                    $nama_siswa = strtoupper($_POST['nama_siswa']);
                                    $jenis_kelamin = strtoupper($_POST['jenis_kelamin']);
                                    $tempat_lahir = strtoupper($_POST['tempat_lahir']);
                                    $tgl = @$_POST['tanggal_lahir'];
                                    $alamat = strtoupper($_POST['alamat']);
                                    $agama = strtoupper($_POST['agama']);
                                    $nama_ortu = strtoupper($_POST['nama_ortu']);
                                    $kelas = strtoupper($_POST['kelas']);

                                    $tanggal_lahir = ubahformatTgl($tgl);

                                    $query1 = mysqli_query($conn, "insert into tb_pengguna(username, nama, pass, peran) values('$username', '$nama_siswa', '$pass', '$status')") or die(mysqli_error($conn));
                                    $query = mysqli_query($conn, "insert into tb_siswa(nis, nama_siswa, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, agama, nama_ortu, id_kelas) values('$nis','$nama_siswa', '$jenis_kelamin',  '$tempat_lahir', '$tgl', '$alamat', '$agama', '$nama_ortu', '$kelas')") or die(mysqli_error($conn));

                                    if ($query) {
                                ?>
                                        <script type="text/javascript">
                                            alert("Input Data Sukses !")
                                            window.location = "?page=inputsiswa";
                                        </script>
                                    <?php
                                    } else {
                                    ?>
                                        <script type="text/javascript">
                                            alert("Input Data Gagal !")
                                            window.location = "?page=inputsiswa";
                                        </script>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>