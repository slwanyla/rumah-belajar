<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Edit Data </span> Guru</h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <?php
                    $id = @$_GET['id'];
                    $qrykoreksi = mysqli_query($conn, "SELECT g.*, k.id_kursus, k.nama_kursus 
                                                      FROM tb_guru g 
                                                      LEFT JOIN tbl_kursus k ON g.id_kursus = k.id_kursus 
                                                      WHERE g.id_guru='$id'");
                    $data = mysqli_fetch_object($qrykoreksi);
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label class="mb-2">Nama Guru</label>
                                        <input class="form-control mb-2" name="nama_guru" value="<?php echo $data->nama_guru; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Kursus</label>
                                        <select name="id_kursus" class="form-control mb-2" required>
                                            <option value="">Pilih Kursus</option>
                                            <?php
                                            $query_kursus = mysqli_query($conn, "SELECT * FROM tbl_kursus");
                                            while($kursus = mysqli_fetch_object($query_kursus)) {
                                                $selected = ($kursus->id_kursus == $data->id_kursus) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $kursus->id_kursus; ?>" <?php echo $selected; ?>>
                                                    <?php echo $kursus->nama_kursus; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <input type="submit" name="edit" class="btn btn-primary" value="Edit" />
                                    <a href="?page=lihatguru" class="btn btn-secondary">Kembali</a>
                                </form>

                                <?php
                                if (@$_POST['edit']) {
                                    $nama_guru = strtoupper($_POST['nama_guru']);
                                    $id_kursus = $_POST['id_kursus'];

                                    $query = mysqli_query($conn, "UPDATE tb_guru SET 
                                        nama_guru='$nama_guru', 
                                        id_kursus='$id_kursus'
                                        WHERE id_guru='$id'") or die(mysqli_error($conn));
                                    
                                    if ($query) {
                                        ?>
                                        <script type="text/javascript">
                                            alert("Input Data Sukses !")
                                            window.location = "?page=lihatguru";
                                        </script>
                                    <?php
                                    } else {
                                        ?>
                                        <script type="text/javascript">
                                            alert("Input Data Gagal !")
                                            window.location = "?page=lihatguru";
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