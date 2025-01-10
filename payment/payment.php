<?php
session_start();
require "./../admin/koneksi.php"; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/cyborg/bootstrap.min.css" integrity="sha384-nEnU7Ae+3lD52AK+RGNzgieBWMnEfgTbRHIwEvp1XXPdqdO6uLTd/NwXbzboqjc2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f5f0bb;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .navbar {
            background-color: #73A9AD !important;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .clock {
            color: #90C8AC;
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 26px;
            animation: fadeIn 1s;
        }
        .login-container {
            border-radius: 20px;
            background-color: rgba(50, 50, 50, 0.5);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            animation: slideIn 0.5s forwards;
            padding: 20px;
            color: black;
        }
        .title {
            text-align: center;
            font-size: 36px;
            font-weight: 800;
            animation: fadeIn 1s;
            color: black;
            text-shadow: 2px 2px 0px white;
        }
        .card {
            background-color: rgba(200, 200, 200, 0.5);
        }
        label {
            color: white;
        }
        .readonly-input {
            background-color: white !important;
            color: black;
            border: 1px solid #fff;
            cursor: not-allowed;
        }
        .container {
            padding-top: 100px;
        }
        @keyframes slideIn {
            from { transform: translateY(-200%); }
            to { transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil',
                text: 'Pendaftaran berhasil! Silakan Lakukan Pembayaran.',
                confirmButtonText: 'Ok',
                html: '<?php echo $_SESSION['success']; ?>'
            });
            <?php unset($_SESSION['success']); ?>
        </script>
    <?php elseif (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: '<?php echo $_SESSION['error']; ?>'
            });
            <?php unset($_SESSION['error']); ?>
        </script>
    <?php endif; ?>
    <nav class="navbar navbar-expand-lg navbar-dark w-100">
        <a class="navbar-brand" href="index.php"><i class="fa-solid fa-book"></i> Rumah Belajar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <div class="clock py-5" id="clock"></div>
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').innerText = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="login-container">
                    <div class="card">
                        <div class="card-header text-center">
                            <h4 class="text-white">Pembayaran</h4>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="text-center col-md-8">
                                <?php
                                if (isset($success)) {
                                    echo "<div class='alert alert-success'>$success</div>";
                                }
                                if (isset($error)) {
                                    echo "<div class='alert alert-danger'>$error</div>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="proses_payment.php" enctype="multipart/form-data">
                            <input type="hidden" name="id_pendaftaran" value="<?php echo isset($_SESSION['id_pendaftaran']) ? $_SESSION['id_pendaftaran'] : ''; ?>">
                                <div class="form-group">
                                    <label for="bukti_payment" class="form-label">Bukti Pembayaran</label>
                                    <input type="file" class="form-control" id="bukti_payment" name="bukti_payment" accept="image/*" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_lengkap" class="form-label">Nama lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_rek" class="form-label">No Rekening</label>
                                    <input type="number" class="form-control" id="no_rek" name="no_rek" placeholder="645214141" required>
                                </div>
                                <div class="form-group">
                                    <label for="kursus" class="form-label">Kursus yang diminati</label>
                                    <input type="text" class="form-control readonly-input" id="kursus" name="kursus" value="<?php echo isset($_SESSION['kursus']) ? htmlspecialchars($_SESSION['kursus']) : ''; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="pertemuan_dan_harga" class="form-label">Pertemuan</label>
                                    <input type="text" class="form-control readonly-input" id="pertemuan_dan_harga" name="pertemuan_dan_harga" value="<?php echo isset($_SESSION['pertemuan']) ? htmlspecialchars($_SESSION['pertemuan']) : ''; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                    <input type="text" class="form-control" id="metode_payment" name="metode_payment" placeholder="Menerima Semua Metode Pembayaran" required>
                                </div>
                                <div class="form-group">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="text" class="form-control readonly-input" id="total" name="total" value="<?php echo isset($_SESSION['total']) ? htmlspecialchars($_SESSION['total']) : ''; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="no_rek_bank" class="form-label">Gunakan Nomor Rekening ini Untuk Melakukan Pembayaran<span data-toggle="tooltip"></span></label>
                                    <input type="text" class="form-control readonly-input" id="no_rek_bank" name="no_rek_bank" value="Bank BSI 6047543170" readonly>
                                </div>
                                <button name="payment" type=" submit" class="btn btn-primary w-100">Submit</button>
                            </form>
                            <div class="text-center mt-3">
                                <p>Sudah melakukan pembayaran? <b><a href="login.php" class="btn btn-success">Login</a></b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container text-center mb-5">
        <p>© 2024 IRumah Belajar. Semua Hak Dilindungi Undang-Undang.</p>
        <div class="social-icons mt-3">
            <a href="#" class="mx-2"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="mx-2"><i class="fab fa-twitter"></i></a>
            <a href="#" class="mx-2"><i class="fab fa-instagram"></i></a>
            <a href="#" class="mx-2"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>