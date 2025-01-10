<?php
require '../vendor/autoload.php';
include("koneksi.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Payment {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getPaymentDetails($id) {
        $query = "SELECT 
                    tb_payment.*,
                    tb_pengguna.nama,
                    tb_pengguna.nomor_hp,
                    tb_pengguna.email,
                    tbl_kursus.nama_kursus,
                    tbl_harga.pertemuan
                  FROM tb_payment 
                  JOIN tbl_pendaftaran ON tb_payment.id_pendaftaran = tbl_pendaftaran.id_pendaftaran 
                  JOIN tb_pengguna ON tbl_pendaftaran.id_pengguna = tb_pengguna.id_pengguna 
                  JOIN tbl_kursus ON tbl_pendaftaran.id_kursus = tbl_kursus.id_kursus
                  JOIN tbl_harga ON tbl_pendaftaran.id_harga = tbl_harga.id_harga
                  WHERE tb_payment.id_payment = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updatePaymentStatus($id, $status) {
        try {
            $this->conn->begin_transaction();

            // Get payment details before update
            $payment_data = $this->getPaymentDetails($id);
            if (!$payment_data) {
                throw new Exception("Payment data not found");
            }

            $query = "UPDATE tb_payment SET status_konfirmasi = ? WHERE id_payment = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $status, $id);
            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate status pembayaran");
            }

            if ($status === 'confirmed') {
                // Hitung exp_date
                $exp_date = date('Y-m-d', strtotime('+1 month'));
                
                // Update student status
                $query_update_siswa = "UPDATE tb_siswa s
                    JOIN tb_payment p ON s.id_pendaftaran = p.id_pendaftaran
                    JOIN tbl_pendaftaran tp ON p.id_pendaftaran = tp.id_pendaftaran
                    JOIN tbl_harga h ON tp.id_harga = h.id_harga
                    SET s.status = 'aktif',
                        s.sisa_pertemuan = h.pertemuan,
                        s.exp_date = ?
                    WHERE p.id_payment = ?";
                
                $stmt_update_siswa = $this->conn->prepare($query_update_siswa);
                $stmt_update_siswa->bind_param('si', $exp_date, $id);
                
                if (!$stmt_update_siswa->execute()) {
                    throw new Exception("Gagal mengupdate status siswa");
                }

                // Send confirmation email
                $email_sent = $this->sendConfirmationEmail($payment_data);
                if (!$email_sent) {
                    error_log("Warning: Confirmation email failed for payment ID: " . $id);
                }
            } elseif ($status === 'rejected') {
                // Send rejection email
                $email_sent = $this->sendRejectionEmail($payment_data);
                if (!$email_sent) {
                    error_log("Warning: Rejection email failed for payment ID: " . $id);
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error in updatePaymentStatus: " . $e->getMessage());
            return false;
        }
    }

    private function sendConfirmationEmail($data) {
        if (empty($data['email'])) {
            error_log("Email address not found for user: " . $data['nama']);
            return false;
        }

        $mail = new PHPMailer(true);
    
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rumahbelajar446@gmail.com';
            $mail->Password = 'winh nrqr clst ibky';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
    
            // Recipients
            $mail->setFrom('rumahbelajar446@gmail.com', 'Rumah Belajar');
            $mail->addAddress($data['email'], $data['nama']);
    
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Konfirmasi Pembayaran Rumah Belajar';
            
            $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #4A90E2;'>Konfirmasi Pembayaran Rumah Belajar</h2>
                <p>Halo <strong>{$data['nama']}</strong>,</p>
                <p>Pembayaran Anda untuk kursus <strong>{$data['nama_kursus']}</strong> telah berhasil dikonfirmasi.</p>
                
                <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h3 style='color: #333; margin-top: 0;'>Detail Pembayaran:</h3>
                    <ul style='list-style: none; padding: 0;'>
                        <li>Jumlah Pertemuan: {$data['pertemuan']}x</li>
                        <li>Total Pembayaran: Rp " . number_format($data['total'], 0, ',', '.') . "</li>
                        <li>Tanggal: " . date('d-m-Y', strtotime($data['tanggal_payment'])) . "</li>
                    </ul>
                </div>
                
                <p>Silakan login, anda sekarang bisa mengakses kursus.</p>
                <p>Terima kasih telah bergabung dengan Rumah Belajar! 🎓</p>
            </div>";
    
            $mail->Body = $body;
            $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $body));
    
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    private function sendRejectionEmail($data) {
        if (empty($data['email'])) {
            error_log("Email address not found for user: " . $data['nama']);
            return false;
        }

        $mail = new PHPMailer(true);
    
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rumahbelajar446@gmail.com';
            $mail->Password = 'winh nrqr clst ibky';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
    
            // Recipients
            $mail->setFrom('rumahbelajar446@gmail.com', 'Rumah Belajar');
            $mail->addAddress($data['email'], $data['nama']);
    
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Pembayaran Ditolak - Rumah Belajar';
            
            $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #FF4444;'>Pemberitahuan Pembayaran Ditolak</h2>
                <p>Halo <strong>{$data['nama']}</strong>,</p>
                <p>Mohon maaf, pembayaran Anda untuk kursus <strong>{$data['nama_kursus']}</strong> tidak dapat kami proses.</p>
                
                <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h3 style='color: #333; margin-top: 0;'>Detail Pembayaran:</h3>
                    <ul style='list-style: none; padding: 0;'>
                        <li>Jumlah Pertemuan: {$data['pertemuan']}x</li>
                        <li>Total Pembayaran: Rp " . number_format($data['total'], 0, ',', '.') . "</li>
                        <li>Tanggal: " . date('d-m-Y', strtotime($data['tanggal_payment'])) . "</li>
                    </ul>
                </div>
                
                <p>Mohon untuk melakukan pembayaran ulang dengan:</p>
                <ul>
                    <li>Memastikan bukti transfer jelas dan terbaca</li>
                    <li>Memastikan jumlah transfer sesuai</li>
                    <li>Memastikan transfer ke nomor rekening yang benar</li>
                </ul>
                
                <p>Jika ada pertanyaan, silakan hubungi kami melalui WhatsApp di nomor yang tertera di website.</p>
                <p>Terima kasih atas pengertian Anda.</p>
            </div>";
    
            $mail->Body = $body;
            $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $body));
    
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}


// Pastikan file ini diakses melalui parameter id yang valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href = '../payment.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$paymentHandler = new Payment($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status_konfirmasi'];
    if ($paymentHandler->updatePaymentStatus($id, $status)) {
        echo "<script>alert('Status pembayaran berhasil diperbarui!'); window.location.href = '?page=detailpembayaran&id=$id';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status pembayaran!'); window.history.back();</script>";
    }
}

$data = $paymentHandler->getPaymentDetails($id);
?>

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Detail </span> Pembayaran Payment</h4>

        <!-- Card Detail Pembayaran -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>ID Payment</th>
                        <td><?php echo htmlspecialchars($data['id_payment']); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Pengguna</th>
                        <td><?php echo htmlspecialchars($data['nama']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo date('d-m-Y', strtotime($data['tanggal_payment'])); ?></td>
                    </tr>
                    <tr>
                        <th>Nomor HP</th>
                        <td><?php echo htmlspecialchars($data['nomor_hp']); ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>Rp <?php echo number_format($data['total'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo htmlspecialchars($data['status_konfirmasi']); ?></td>
                    </tr>
                    <tr>
                        <th>Bukti Pembayaran</th>
                        <td>
                            <img src="../payment/bukti_payment/<?php echo htmlspecialchars($data['bukti_payment']); ?>" alt="Bukti Pembayaran" class="img-thumbnail" width="300">
                        </td>
                    </tr>
                </table>

                <!-- Form Update Status -->
                <form method="post" class="mt-4">
                    <div class="mb-3">
                        <label for="status_konfirmasi" class="form-label">Ubah Status</label>
                        <select name="status_konfirmasi" id="status_konfirmasi" class="form-control" required>
                            <option value="pending" <?php echo $data['status_konfirmasi'] === 'pending' ? 'selected' : ''; ?>>Menunggu Konfirmasi Admin</option>
                            <option value="confirmed" <?php echo $data['status_konfirmasi'] === 'confirmed' ? 'selected' : ''; ?>>Diterima</option>
                            <option value="rejected" <?php echo $data['status_konfirmasi'] === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="?page=riwayatpembayaran" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>