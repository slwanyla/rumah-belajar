<?php
include "koneksi.php";
include "account.php";

if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Anda tidak memiliki akses untuk menghapus akun'
    ]);
    exit;
}

$account = new Account($conn);
$id = $_GET['id'];
$hapus = $account->hapus($id);

if ($hapus) {
    ?>
    <script type="text/javascript">
        alert("Data Berhasil Dihapus!");
        window.location.href = "index.php?page=account"; // Use absolute path
    </script>
    <?php
} else {
    ?>
    <script type="text/javascript">
        alert("Data Gagal Dihapus!");
        window.location.href = "index.php?page=account"; // Use absolute path
    </script>
    <?php
}
?>