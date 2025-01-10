<?php
include "koneksi.php";

class Account {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    // Fungsi untuk mengecek duplikasi
    function cekDuplikasi($username, $email, $no_hp) {
        $query = mysqli_query($this->conn, 
            "SELECT * FROM tb_pengguna 
             WHERE username = '$username' 
             OR email = '$email' 
             OR nomor_hp = '$no_hp'"
        );
        
        if(mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);
            if($row['username'] == $username) {
                return "Username sudah digunakan";
            }
            if($row['email'] == $email) {
                return "Email sudah digunakan";
            }
            if($row['nomor_hp'] == $no_hp) {
                return "Nomor HP sudah digunakan";
            }
        }
        return false;
    }

    function simpan($username, $pass, $peran, $nama, $email, $no_hp) {
        // Cek duplikasi terlebih dahulu
        $cekDuplikasi = $this->cekDuplikasi($username, $email, $no_hp);
        if($cekDuplikasi) {
            return ['success' => false, 'message' => $cekDuplikasi];
        }

        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $query = mysqli_query($this->conn, 
            "INSERT INTO tb_pengguna(username, pass, peran, nama, email, nomor_hp) 
             VALUES('$username','$hashed_password','$peran', '$nama', '$email', '$no_hp')"
        );
        
        return ['success' => true, 'message' => 'Data berhasil disimpan'];
    }

    function tampil() {
        $data = mysqli_query($this->conn, "SELECT username, peran FROM tb_pengguna ORDER BY username DESC");
        return $data;
    }
}

$account = new Account($conn);

// Proses input data
if (@$_POST['input']) {
    $peran = $_POST['peran'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $no_hp = $_POST['nomor_hp'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $simpan = $account->simpan($username, $pass, $peran, $nama, $email, $no_hp);

    if ($simpan['success']) {
        echo "<script>
            alert('Input Data Sukses!');
            window.location = '?page=account';
        </script>";
    } else {
        echo "<script>
            alert('" . $simpan['message'] . "');
            window.location = '?page=account';
        </script>";
    }
}
?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Halaman </span> Account</h4>
        <div class="row">
            <!-- Form Input -->
            <div class="col-lg-5 col-md-12 col-sm-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label class="mb-2">Peran</label>
                                <select name="peran" class="form-control mb-2" required>
                                    <option value="">Pilih Peran</option>
                                    <option value="admin">Admin</option>
                                    <option value="guru">Guru</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="mb-2">Nama</label>
                                <input class="form-control mb-2" name="nama" placeholder="Nama" required>
                            </div>
                            <div class="form-group">
                                <label class="mb-2">Username</label>
                                <input class="form-control mb-2" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <label class="mb-2">Email</label>
                                <input type="email" class="form-control mb-2" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <label class="mb-2">Password</label>
                                <input type="password" class="form-control mb-2" name="pass" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <label class="mb-2">Nomor HP</label>
                                <input type="text" class="form-control mb-2" name="nomor_hp" placeholder="Nomor HP" required>
                            </div>
                            <input type="submit" name="input" class="btn btn-primary" value="Input" />
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="col-lg-7 col-md-12 col-sm-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="page-header">Data Pengguna</h3>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Peran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $view = $account->tampil();
                                    $no = 0;
                                    while ($row = mysqli_fetch_array($view)) {
                                        echo "<tr>
                                            <td>" . ++$no . "</td>
                                            <td>{$row['username']}</td>
                                            <td>{$row['peran']}</td>
                                        </tr>";
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