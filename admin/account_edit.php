<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Edit Data </span> Account</h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                $id = @$_GET['id'];
                                $qrykoreksi = mysqli_query($conn, "select * from tb_pengguna where id_pengguna='$id'");
                                $data = mysqli_fetch_array($qrykoreksi);

                                if ($data['peran'] == 'guru') {
                                ?>
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="mb-2">Username</label>
                                            <input class="form-control mb-2" name="nomor_hp" value="<?php echo $data['nomor_hp']; ?>" readonly="readonly">
                                        </div>
                                        <input type="submit" name="edit" class="btn btn-default" value="Input" />
                                    </form>
                                <?php
                                } else {
                                ?>

                                    <form method="post">
                                        <div class="form-group">
                                            <label class="mb-2">No HP</label>
                                            <input class="form-control mb-2" name="nomor_hp" value="<?php echo $data['nomor_hp']; ?>">
                                        </div>
                                        <input type="submit" name="edit" class="btn btn-primary" value="Input" />
                                    </form>

                                    <!-- Sript Update Data -->
                                    <?php
                                }
                                if (@$_POST['edit']) {
                                    $no_hp = $_POST['nomor_hp'];
                                   

                                    $query = mysqli_query($conn, "UPDATE tb_pengguna SET nomor_hp='$no_hp' WHERE id_pengguna='$id'") or die(mysqli_error($conn));

                                    if ($query) {
                                    ?>
                                        <script type="text/javascript">
                                            alert("Edit Data Sukses !")
                                            window.location = "?page=account";
                                        </script>
                                    <?php
                                    } else {
                                    ?>
                                        <script type="text/javascript">
                                            alert("Edit Data Gagal !")
                                            window.location = "?page=account";
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