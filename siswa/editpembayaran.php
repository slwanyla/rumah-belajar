<?php
// Pastikan file ini diakses melalui parameter id yang valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href = 'pembayaran.php';</script>";
    exit;
}

$id = intval($_GET['id']); // Sanitasi input ID

// Query untuk mengambil data pembayaran berdasarkan ID
$query = "SELECT tb_payment.*, tb_pengguna.nama 
          FROM tb_payment 
          JOIN tb_pengguna ON tb_payment.id_pendaftaran = tbl_pendaftaran.id_pendaftaran
          JOIN tbl_pendaftaran ON tbl_pendaftaran.id_pengguna = tb_pengguna.id_pengguna
          WHERE tb_payment.id_payment = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah data ditemukan
if ($result->num_rows == 0) {
    echo "<script>alert('Data pembayaran tidak ditemukan!'); window.location.href = 'pembayaran.php';</script>";
    exit;
}

$data = $result->fetch_assoc();
$stmt->close();

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $status = 'Menunggu Konfirmasi Admin';

    // Proses unggah file jika ada file baru
    $foto = $_FILES['foto'];
    $uploadDir = '../img/';
    $uploadFile = $data['foto']; // Default ke file lama

    if (!empty($foto['name'])) {
        $uploadFile = $uploadDir . basename($foto['name']);
        if (!move_uploaded_file($foto['tmp_name'], $uploadFile)) {
            echo "<script>alert('Gagal mengunggah bukti pembayaran baru!'); window.history.back();</script>";
            exit;
        }
    }

    // Query update data
    $updateQuery = "UPDATE tb_spp SET tanggal = ?, jumlah = ?, status = ?, foto = ? WHERE idspp = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('sissi', $tanggal, $jumlah, $status, $uploadFile, $id);

    if ($updateStmt->execute()) {
        echo "<script>alert('Data pembayaran berhasil diperbarui!'); window.location.href = '?page=riwayatpembayaran';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data pembayaran!'); window.history.back();</script>";
    }

    $updateStmt->close();
    $conn->close();
    exit;
}
?>

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Edit </span> Pembayaran</h4>

        <!-- Card Edit Pembayaran -->
        <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo htmlspecialchars($data['tanggal']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?php echo htmlspecialchars($data['jumlah']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Bukti Pembayaran</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <img src="../img/<?php echo htmlspecialchars($data['foto']); ?>" alt="Bukti Pembayaran" class="img-thumbnail" width="300">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="?page=riwayatpembayaran" class="btn btn-secondary ms-2">Kembali</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>