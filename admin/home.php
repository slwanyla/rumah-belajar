<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
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

        <!-- Cards Row -->
        <div class="row">
            <!-- Jumlah Kelas Card -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center shadow-sm border-0" style="background-color: #f0f9ff; border-radius: 15px;">
                    <div class="card-body">
                        <i class="fas fa-chalkboard fa-2x mb-3" style="color: #007bff;"></i>
                        <?php
                        $kelasQuery = mysqli_query($conn, "SELECT COUNT(*) AS jumlah_kelas FROM tb_kelas");
                        $kelasData = mysqli_fetch_assoc($kelasQuery);
                        ?>
                        <h5 class="card-title text-primary">Jumlah Kelas</h5>
                        <h3 class="card-text"><?= $kelasData['jumlah_kelas'] ?></h3>
                    </div>
                </div>
            </div>

            <!-- Jumlah Guru Card -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center shadow-sm border-0" style="background-color: #fff7e6; border-radius: 15px;">
                    <div class="card-body">
                        <i class="fas fa-user-tie fa-2x mb-3" style="color: #ffa500;"></i>
                        <?php
                        $guruQuery = mysqli_query($conn, "SELECT COUNT(*) AS jumlah_guru FROM tb_guru");
                        $guruData = mysqli_fetch_assoc($guruQuery);
                        ?>
                        <h5 class="card-title text-warning">Jumlah Guru</h5>
                        <h3 class="card-text"><?= $guruData['jumlah_guru'] ?></h3>
                    </div>
                </div>
            </div>

            <!-- Jumlah Siswa Card -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center shadow-sm border-0" style="background-color: #e6f7ff; border-radius: 15px;">
                    <div class="card-body">
                        <i class="fas fa-user-graduate fa-2x mb-3" style="color: #17a2b8;"></i>
                        <?php
                        $siswaQuery = mysqli_query($conn, "SELECT COUNT(*) AS jumlah_siswa FROM tb_siswa");
                        $siswaData = mysqli_fetch_assoc($siswaQuery);
                        ?>
                        <h5 class="card-title text-info">Jumlah Siswa</h5>
                        <h3 class="card-text"><?= $siswaData['jumlah_siswa'] ?></h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Chart Section -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Grafik</h5>
                        <canvas id="dataChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FontAwesome and Chart.js CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data from PHP
    const dataValues = {
        jumlahKelas: <?= $kelasData['jumlah_kelas'] ?>,
        jumlahGuru: <?= $guruData['jumlah_guru'] ?>,
        jumlahSiswa: <?= $siswaData['jumlah_siswa'] ?>,
    };

    // Chart.js Configuration
    const ctx = document.getElementById('dataChart').getContext('2d');
    const dataChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Kelas', 'Guru', 'Siswa'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    dataValues.jumlahKelas,
                    dataValues.jumlahGuru,
                    dataValues.jumlahSiswa
                ],
                backgroundColor: ['#007bff', '#ffa500', '#17a2b8'],
                borderColor: ['#007bff', '#ffa500', '#17a2b8'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>