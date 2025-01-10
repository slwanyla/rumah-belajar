<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Halaman </span> Report Data Guru</h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                $query = "
                                    SELECT  g.id_guru, g.nama_guru, k.nama_kursus
                                    FROM tb_guru g
                                    JOIN tbl_kursus k ON g.id_kursus = k.id_kursus
                                    ORDER BY g.nama_guru ASC;
                                ";
                                $result = mysqli_query($conn, $query);
                                if (!$result) {
                                    die("Query Error: " . mysqli_error($conn));
                                }
                                ?>
                                <i><a href="index.php?page=inputguru">Data Baru Guru</a></i><br><br>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead class="bg-info">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Mengajar Kursus</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($raw = mysqli_fetch_assoc($result)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($raw['nama_guru']); ?></td>
                                                    <td><?php echo htmlspecialchars($raw['nama_kursus']); ?></td>
                                                    <td>
                                                        <i>
                                                            <a href="?page=editguru&id=<?php echo $raw['id_guru']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a> &nbsp; 
                                                            <a onclick="return confirm('Yakin akan hapus data ini ?')" href="guru_hapus.php?id=<?php echo $raw['id_guru']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                                                        </i>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
