<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tambah </span> Pembayaran SPP</h4>

        <!-- Form Tambah Pembayaran -->
        <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>
                    <!-- jumlah -->
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="buktiPembayaran" class="form-label">Bukti Pembayaran</label>
                        <input type="file" name="foto" id="buktiPembayaran" class="form-control" accept="image/*" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_pengguna = $_SESSION['siswa']; // Ambil id_pengguna dari sesi pengguna
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $status = 'Menunggu Konfirmasi Admin'; // Status default

    // Proses unggah file
    $foto = $_FILES['foto'];
    $uploadDir = '../img/'; // Direktori untuk menyimpan file
    $uploadFile = $uploadDir . basename($foto['name']);
    $namafile = basename($foto['name']);

    // Validasi file
    if (move_uploaded_file($foto['tmp_name'], $uploadFile)) {
        // Simpan data ke database
        $query = "INSERT INTO tb_spp (idpengguna, tanggal, jumlah, foto, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isiss', $id_pengguna, $tanggal, $jumlah, $namafile, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Data pembayaran berhasil disimpan!'); window.location.href = '?page=riwayatpembayaran';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan data pembayaran!'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Gagal mengunggah bukti pembayaran!'); window.history.back();</script>";
    }

    $conn->close();
}
?>