<?php
include ("riwayatpembayaran.php");

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID tidak ditemukan!");
    }

    $paymentManagement = new PaymentManagement($conn);
    
    if ($paymentManagement->deletePayment($_GET['id'])) {
        echo "<script>
            alert('Data pembayaran berhasil dihapus!');
            window.location.href = '?page=riwayatpembayaran';
        </script>";
    }

} catch (Exception $e) {
    echo "<script>
        alert('Error: " . addslashes($e->getMessage()) . "');
        window.location.href = '?page=riwayatpembayaran';
    </script>";
}