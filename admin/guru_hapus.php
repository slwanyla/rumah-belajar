<?php
include 'koneksi.php';

// Memastikan id yang diterima adalah integer
$id_guru = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Mulai transaction
    $conn->begin_transaction();
    
    // Ambil id_pengguna dari tb_guru
    $stmt = $conn->prepare("SELECT id_pengguna FROM tb_guru WHERE id_guru = ?");
    $stmt->bind_param("i", $id_guru);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $id_pengguna = $row['id_pengguna'];
        
        // Hapus data guru
        $delete_guru = $conn->prepare("DELETE FROM tb_guru WHERE id_guru = ?");
        $delete_guru->bind_param("i", $id_guru);
        $delete_guru->execute();
        
        // Hapus data pengguna
        $delete_pengguna = $conn->prepare("DELETE FROM tb_pengguna WHERE id_pengguna = ?");
        $delete_pengguna->bind_param("i", $id_pengguna);
        $delete_pengguna->execute();
        
        // Commit transaction
        $conn->commit();
        
        echo "<script>
                alert('Data guru berhasil dihapus!');
                window.location.href = 'index.php?page=lihatguru';
              </script>";
    }
    
} catch (Exception $e) {
    // Rollback transaction jika terjadi error
    $conn->rollback();
    
    echo "<script>
            alert('Gagal menghapus data: " . $e->getMessage() . "');
            window.location.href = 'index.php?page=lihatguru';
          </script>";
}

$conn->close();
?>