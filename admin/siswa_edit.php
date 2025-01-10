<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Edit Data </span> Siswa</h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <?php
                    $id = $_GET['id'];
                    $qrykoreksi = mysqli_query($conn, "select * from tb_siswa where id_siswa='$id'");
                    $data = mysqli_fetch_object($qrykoreksi);
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label class="mb-2">Nama Siswa</label>
                                        <input class="form-control mb-2" name="nama_siswa" value="<?php echo $data->nama_siswa; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Kelas</label>
                                        <select name="nama_kelas" class="form-control mb-2" required>
                                            <option value="">Pilih Kelas</option>
                                            <?php
                                            $query = mysqli_query($conn, "SELECT * FROM tb_kelas order by nama_kelas asc");
                                            while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                                <option value="<?php echo $row['id_kelas']; ?>"><?php echo $row['nama_kelas']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <input type="submit" name="edit" class="btn btn-primary" value="Edit" />
                                    <a href="?page=lihatsiswa" class="btn btn-secondary">Kembali</a>
                                </form>

                                <?php
                                if (@$_POST['edit']) {
                                    $nama_siswa = strtoupper($_POST['nama_siswa']);
                                    $kelas = strtoupper($_POST['nama_kelas']);

                                    $query = mysqli_query($conn, "UPDATE tb_siswa SET nis='$nis', nama_siswa='$nama_siswa', jenis_kelamin='$jenis_kelamin',
                            tempat_lahir='$tempat_lahir', tanggalw_lahir='$tanggal_lahir', alamat='$alamat', agama='$agama',
                            nama_ortu='$nama_ortu', id_kelas='$kelas' WHERE id_siswa='$id'") or die(mysqli_error($koneksi));

                                    if ($query) {
                                ?>
                                        <script type="text/javascript">
                                            alert("Edit Data Sukses !")
                                            window.location = "?page=lihatsiswa";
                                        </script>
                                    <?php
                                    } else {
                                    ?>
                                        <script type="text/javascript">
                                            alert("Edit Data Gagal !")
                                            window.location = "?page=lihatsiswa";
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