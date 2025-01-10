<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Data Pembayaran </span> Payment</h4>

        <!-- Table Displaying Filtered Data -->
        <div class="tab-content" id="myTabContent">
            <?php
            class PaymentManagement {
                private $conn;
                
                public function __construct($conn) {
                    $this->conn = $conn;
                }
                
                // Method untuk mengambil semua data pembayaran
                public function getAllPayments() {
                    $query = "SELECT tb_payment.*, tb_pengguna.nama 
                            FROM tb_payment
                            JOIN tbl_pendaftaran ON tb_payment.id_pendaftaran = tbl_pendaftaran.id_pendaftaran
                            JOIN tb_pengguna ON tbl_pendaftaran.id_pengguna = tb_pengguna.id_pengguna
                            ORDER BY tb_payment.tanggal_payment DESC";
                            
                    $result = mysqli_query($this->conn, $query);
                    return $result;
                }
                
                // Method untuk menghapus pembayaran
                public function deletePayment($id) {
                    try {
                        $id = intval($id);
                        
                        // Ambil info file bukti pembayaran
                        $query = "SELECT bukti_payment FROM tb_payment WHERE id_payment = ?";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param('i', $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows == 0) {
                            throw new Exception("Data pembayaran tidak ditemukan!");
                        }
                        
                        $data = $result->fetch_assoc();
                        
                        // Hapus file bukti pembayaran jika ada
                        if (!empty($data['bukti_payment'])) {
                            $file_path = '../payment/bukti_payment/' . $data['bukti_payment'];
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                        }
                        
                        // Hapus data dari database
                        $query = "DELETE FROM tb_payment WHERE id_payment = ?";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param('i', $id);
                        
                        if (!$stmt->execute()) {
                            throw new Exception("Gagal menghapus data pembayaran!");
                        }
                        
                        return true;
                        
                    } catch (Exception $e) {
                        throw $e;
                    }
                }
            }
            $paymentManagement = new PaymentManagement($conn);
            $result = $paymentManagement->getAllPayments();
            ?>

            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>ID Payment</th>
                                    <th>Nama Pengguna</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars ($row['id_payment']); ?></td>
                                        <td><?php echo htmlspecialchars ($row['nama']); ?></td>
                                        <td>
                                            <img src="../payment/bukti_payment/<?php echo htmlspecialchars ($row['bukti_payment']); ?>" alt="Foto Pengguna" class="img-thumbnail" width="100">
                                            
                                        </td>
                                        <td><?php echo htmlspecialchars ($row['metode_payment']); ?></td>
                                        <td><?php echo htmlspecialchars ($row['status_konfirmasi']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_payment']))); ?></td>
                                        <td>
                                            <a href="?page=detailpembayaran&id=<?php echo htmlspecialchars ($row['id_payment']); ?>" class="btn btn-primary btn-sm">Detail</a>
                                            <a href="?page=hapuspembayaran&id=<?php echo htmlspecialchars ($row['id_payment']); ?>" class="btn btn-danger btn-sm">Hapus</a>
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