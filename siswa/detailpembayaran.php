<?php
// Pastikan file ini diakses melalui parameter id yang valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href = 'pembayaran.php';</script>";
    exit;
}

$id = intval($_GET['id']); // Sanitasi input ID

// Query untuk mengambil data pembayaran berdasarkan ID
$query = "SELECT tb_spp.*, tb_pengguna.nama 
          FROM tb_spp 
          JOIN tb_pengguna ON tb_spp.idpengguna = tb_pengguna.id_pengguna
          WHERE tb_spp.idspp = ?";
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
?>

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Detail </span> Pembayaran</h4>

        <!-- Card Detail Pembayaran -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>ID SPP</th>
                        <td><?php echo htmlspecialchars($data['idspp']); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Pengguna</th>
                        <td><?php echo htmlspecialchars($data['nama']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>Rp <?php echo number_format($data['jumlah'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $data['status']; ?></td>
                    </tr>
                    <tr>
                        <th>Bukti Pembayaran</th>
                        <td>
                            <img src="../img/<?php echo htmlspecialchars($data['foto']); ?>" alt="Bukti Pembayaran" class="img-thumbnail" width="300">
                        </td>
                    </tr>
                </table>

                <div class="mt-4">
                    <a href="?page=riwayatpembayaran" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>