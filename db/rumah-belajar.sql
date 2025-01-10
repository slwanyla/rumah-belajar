-- --------------------------------------------------------
-- Host:                         103.176.79.109
-- Server version:               8.0.40 - MySQL Community Server - GPL
-- Server OS:                    Linux
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table rumahbelajar.tbl_harga
CREATE TABLE IF NOT EXISTS `tbl_harga` (
  `id_harga` int NOT NULL AUTO_INCREMENT,
  `id_kursus` int NOT NULL,
  `pertemuan` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_harga`),
  KEY `id_kursus` (`id_kursus`),
  CONSTRAINT `tbl_harga_ibfk_1` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rumahbelajar.tbl_harga: ~4 rows (approximately)
INSERT INTO `tbl_harga` (`id_harga`, `id_kursus`, `pertemuan`, `harga`) VALUES
	(1, 1, 8, 0.00),
	(2, 1, 12, 0.00),
	(3, 2, 8, 200.00),
	(4, 2, 12, 300.00);

-- Dumping structure for table rumahbelajar.tbl_kursus
CREATE TABLE IF NOT EXISTS `tbl_kursus` (
  `id_kursus` int NOT NULL AUTO_INCREMENT,
  `nama_kursus` varchar(15) NOT NULL,
  PRIMARY KEY (`id_kursus`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rumahbelajar.tbl_kursus: ~2 rows (approximately)
INSERT INTO `tbl_kursus` (`id_kursus`, `nama_kursus`) VALUES
	(1, 'Tajwid'),
	(2, 'Matematika');

-- Dumping structure for table rumahbelajar.tbl_pendaftaran
CREATE TABLE IF NOT EXISTS `tbl_pendaftaran` (
  `id_pendaftaran` int NOT NULL AUTO_INCREMENT,
  `id_kursus` int NOT NULL,
  `id_harga` int NOT NULL,
  `id_kelas` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `tanggal_pendaftaran` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pendaftaran`),
  KEY `id_harga` (`id_harga`),
  KEY `id_kelas` (`id_kelas`),
  KEY `id_kursus` (`id_kursus`),
  KEY `id_pengguna` (`id_pengguna`),
  CONSTRAINT `tbl_pendaftaran_ibfk_1` FOREIGN KEY (`id_harga`) REFERENCES `tbl_harga` (`id_harga`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_pendaftaran_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_pendaftaran_ibfk_3` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_pendaftaran_ibfk_4` FOREIGN KEY (`id_pengguna`) REFERENCES `tb_pengguna` (`id_pengguna`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rumahbelajar.tbl_pendaftaran: ~73 rows (approximately)
INSERT INTO `tbl_pendaftaran` (`id_pendaftaran`, `id_kursus`, `id_harga`, `id_kelas`, `id_pengguna`, `tanggal_pendaftaran`) VALUES
	(43, 2, 3, 7, 143, '2024-12-23 14:24:25'),
	(44, 2, 3, 8, 144, '2024-12-23 16:00:45'),
	(45, 2, 3, 9, 145, '2024-12-23 16:27:45'),
	(46, 2, 3, 8, 150, '2024-12-24 19:09:50'),
	(47, 1, 1, 7, 151, '2024-12-24 19:29:45'),
	(48, 2, 3, 7, 152, '2024-12-24 19:46:53'),
	(49, 2, 3, 9, 153, '2024-12-24 20:12:04'),
	(50, 2, 3, 6, 154, '2024-12-25 06:15:15'),
	(51, 2, 3, 7, 155, '2024-12-25 13:08:20'),
	(52, 2, 3, 6, 155, '2024-12-25 13:16:41'),
	(53, 2, 3, 10, 156, '2024-12-25 17:45:18'),
	(54, 2, 3, 8, 157, '2024-12-25 19:54:00'),
	(55, 2, 3, 10, 143, '2024-12-25 20:24:16'),
	(56, 2, 3, 8, 155, '2024-12-25 20:50:43'),
	(57, 2, 3, 6, 158, '2024-12-26 05:42:33'),
	(58, 1, 1, 6, 158, '2024-12-26 05:42:33'),
	(59, 2, 3, 7, 159, '2024-12-26 07:34:44'),
	(60, 2, 3, 9, 160, '2024-12-26 07:44:57'),
	(61, 2, 3, 2, 161, '2024-12-26 08:12:05'),
	(62, 1, 1, 8, 161, '2024-12-26 08:15:38'),
	(63, 2, 3, 8, 160, '2024-12-26 08:58:30'),
	(64, 1, 1, 6, 162, '2024-12-26 11:12:12'),
	(65, 2, 3, 9, 163, '2024-12-26 11:32:32'),
	(66, 2, 3, 9, 164, '2024-12-26 11:45:48'),
	(67, 1, 1, 9, 160, '2024-12-25 17:00:00'),
	(68, 2, 3, 8, 165, '2024-12-26 14:11:51'),
	(69, 2, 3, 9, 166, '2024-12-26 14:38:32'),
	(70, 1, 2, 9, 160, '2024-12-25 17:00:00'),
	(71, 2, 3, 9, 167, '2024-12-26 15:19:33'),
	(73, 2, 3, 8, 168, '2024-12-26 15:33:45'),
	(74, 2, 3, 9, 160, '2024-12-25 17:00:00'),
	(75, 2, 3, 9, 169, '2024-12-26 16:35:32'),
	(76, 2, 3, 9, 160, '2024-12-25 17:00:00'),
	(77, 1, 1, 9, 160, '2024-12-25 17:00:00'),
	(78, 2, 3, 8, 170, '2024-12-26 16:55:56'),
	(79, 2, 3, 9, 160, '2024-12-25 17:00:00'),
	(80, 1, 1, 9, 160, '2024-12-26 17:00:00'),
	(82, 2, 3, 8, 171, '2024-12-26 17:27:03'),
	(83, 2, 3, 9, 172, '2024-12-26 17:49:13'),
	(84, 2, 3, 8, 172, '2024-12-26 17:56:23'),
	(85, 2, 3, 8, 173, '2024-12-26 18:04:07'),
	(86, 2, 3, 9, 174, '2024-12-26 18:08:57'),
	(87, 2, 3, 9, 175, '2024-12-26 18:34:03'),
	(88, 2, 3, 10, 175, '2024-12-26 18:35:19'),
	(90, 1, 1, 10, 175, '2024-12-26 17:00:00'),
	(91, 1, 1, 9, 176, '2024-12-26 19:19:05'),
	(92, 2, 3, 9, 176, '2024-12-26 17:00:00'),
	(93, 2, 3, 8, 177, '2024-12-26 19:21:25'),
	(94, 1, 1, 8, 177, '2024-12-26 17:00:00'),
	(95, 2, 3, 8, 178, '2024-12-26 19:34:32'),
	(96, 1, 1, 6, 156, '2024-12-27 02:44:16'),
	(97, 2, 3, 9, 150, '2024-12-27 04:25:50'),
	(98, 2, 3, 7, 150, '2024-12-27 04:51:19'),
	(99, 2, 3, 7, 179, '2024-12-27 05:40:35'),
	(100, 2, 4, 8, 179, '2024-12-27 06:12:28'),
	(101, 1, 2, 7, 180, '2024-12-27 06:43:16'),
	(102, 2, 3, 6, 182, '2024-12-29 04:18:01'),
	(103, 2, 3, 9, 183, '2024-12-29 05:06:25'),
	(104, 2, 3, 9, 184, '2024-12-29 05:11:34'),
	(105, 2, 3, 10, 185, '2024-12-29 05:36:44'),
	(106, 2, 3, 9, 186, '2024-12-29 06:51:27'),
	(107, 2, 4, 9, 188, '2025-01-03 03:16:39'),
	(108, 2, 3, 7, 189, '2025-01-03 03:19:30'),
	(109, 2, 3, 9, 190, '2025-01-03 04:35:32'),
	(110, 2, 3, 10, 191, '2025-01-06 10:45:05'),
	(111, 1, 1, 4, 191, '2025-01-06 11:16:56'),
	(114, 1, 1, 10, 191, '2025-01-06 11:57:30'),
	(115, 2, 3, 10, 191, '2025-01-06 12:50:39'),
	(116, 2, 3, 10, 194, '2025-01-06 15:27:47'),
	(117, 1, 2, 5, 194, '2025-01-10 19:20:26'),
	(118, 1, 2, 5, 194, '2025-01-10 19:20:54'),
	(119, 1, 2, 5, 194, '2025-01-10 19:23:00'),
	(120, 1, 2, 5, 194, '2025-01-10 19:23:58'),
	(121, 2, 3, 10, 195, '2025-01-11 00:38:05'),
	(122, 1, 1, 10, 195, '2025-01-10 05:00:00'),
	(123, 2, 3, 10, 191, '2025-01-11 00:51:24');

-- Dumping structure for table rumahbelajar.tb_guru
CREATE TABLE IF NOT EXISTS `tb_guru` (
  `id_guru` int NOT NULL AUTO_INCREMENT,
  `nama_guru` varchar(40) NOT NULL,
  `id_pengguna` int NOT NULL,
  `id_kursus` int NOT NULL,
  PRIMARY KEY (`id_guru`) USING BTREE,
  KEY `id_pengguna` (`id_pengguna`),
  KEY `tb_guru_ibfk_3` (`id_kursus`),
  CONSTRAINT `tb_guru_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `tb_pengguna` (`id_pengguna`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_guru_ibfk_3` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

-- Dumping data for table rumahbelajar.tb_guru: ~2 rows (approximately)
INSERT INTO `tb_guru` (`id_guru`, `nama_guru`, `id_pengguna`, `id_kursus`) VALUES
	(36, 'SUHAILAH', 149, 1),
	(37, 'ulfah', 187, 2),
	(38, 'Pak Diki', 196, 2);

-- Dumping structure for table rumahbelajar.tb_kelas
CREATE TABLE IF NOT EXISTS `tb_kelas` (
  `id_kelas` int NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- Dumping data for table rumahbelajar.tb_kelas: ~10 rows (approximately)
INSERT INTO `tb_kelas` (`id_kelas`, `nama_kelas`) VALUES
	(1, 'TK'),
	(2, '1 SD'),
	(3, '2 SD'),
	(4, '3 SD'),
	(5, '4 SD'),
	(6, '5 SD'),
	(7, '6 SD'),
	(8, '1 SMP'),
	(9, '2 SMP'),
	(10, '3 SMP');

-- Dumping structure for table rumahbelajar.tb_materi
CREATE TABLE IF NOT EXISTS `tb_materi` (
  `id_materi` int NOT NULL AUTO_INCREMENT,
  `id_kursus` int NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `judul_materi` varchar(255) NOT NULL,
  `deskripsi` text,
  `file_materi` varchar(255) DEFAULT NULL,
  `link_platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_materi`),
  KEY `id_kursus` (`id_kursus`),
  KEY `id_kelas` (`id_kelas`),
  CONSTRAINT `tb_materi_ibfk_1` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`),
  CONSTRAINT `tb_materi_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rumahbelajar.tb_materi: ~10 rows (approximately)
INSERT INTO `tb_materi` (`id_materi`, `id_kursus`, `id_kelas`, `judul_materi`, `deskripsi`, `file_materi`, `link_platform`) VALUES
	(1, 2, 1, 'hukum nun', NULL, 'uploads/materi/Pertemuan 3 - Analisa Efisiensi Algoritma Non-Rekursif.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(2, 2, 2, 'Tambah tambahan', NULL, 'uploads/materi/Pertemuan 6 - Analisa Efisiensi Algoritma Rekursif.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(3, 2, 2, 'Kurang kurangan', NULL, 'uploads/materi/Pertemuan 1 - Pseudocode.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(4, 2, 2, 'Kurang kurangan', NULL, 'uploads/materi/Pertemuan 1 - Pseudocode.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(5, 2, 2, 'Kurang kurangan', NULL, 'uploads/materi/Pertemuan 1 - Pseudocode.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(6, 2, 2, 'Kurang kurangan', NULL, 'uploads/materi/Pertemuan 1 - Pseudocode.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(7, 2, 2, 'Kurang kurangan', NULL, 'uploads/materi/Pertemuan 1 - Pseudocode.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(8, 2, 2, 'Kurang kurangan', NULL, 'uploads/materi/Pertemuan 1 - Pseudocode.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(9, 2, 2, 'Kurang kurangan', NULL, 'uploads/materi/Pertemuan 1 - Pseudocode.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
	(10, 2, 2, 'Bagi bagian', NULL, 'uploads/materi/Pertemuan 4 - Algoritma Rekursif.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 ');

-- Dumping structure for table rumahbelajar.tb_payment
CREATE TABLE IF NOT EXISTS `tb_payment` (
  `id_payment` int NOT NULL AUTO_INCREMENT,
  `id_pendaftaran` int DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `tanggal_payment` date NOT NULL,
  `bukti_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_rek` varchar(20) NOT NULL,
  `metode_payment` varchar(25) NOT NULL,
  `status_pembayaran` enum('invalid','valid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'invalid',
  `status_konfirmasi` enum('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id_payment`),
  KEY `tb_payment_ibfk_1` (`id_pendaftaran`),
  CONSTRAINT `tb_payment_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `tbl_pendaftaran` (`id_pendaftaran`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rumahbelajar.tb_payment: ~43 rows (approximately)
INSERT INTO `tb_payment` (`id_payment`, `id_pendaftaran`, `total`, `tanggal_payment`, `bukti_payment`, `no_rek`, `metode_payment`, `status_pembayaran`, `status_konfirmasi`) VALUES
	(7, NULL, 200.00, '2024-12-23', '676989494a257.png', '0', 'gopay', 'valid', 'confirmed'),
	(9, NULL, 200.00, '2024-12-25', '676b0727b1f29.png', '0', 'mandiri', 'valid', 'pending'),
	(11, NULL, 200.00, '2024-12-25', '676b0fd1e7d11.png', '0', 'BCA', 'valid', 'confirmed'),
	(15, NULL, 200.00, '2024-12-25', '676bad9c22985.png', '0', 'BANK BSI', 'valid', 'confirmed'),
	(21, NULL, 200.00, '2024-12-26', '676c6a0403c68.png', '0', 'BANK BSI', 'valid', 'confirmed'),
	(22, NULL, 200.00, '2024-12-26', '676c703cb6190.png', '0', 'BANK BSI', 'valid', 'confirmed'),
	(23, NULL, 200.00, '2024-12-26', '676ced8870c94.png', '0', 'BANK BCI', 'valid', 'pending'),
	(24, NULL, 200.00, '2024-12-26', '676d087f5be7c.png', '0', 'BANK BSI', 'valid', 'pending'),
	(25, NULL, 200.00, '2024-12-26', '676d09a1baa40.png', '0', 'BANK BSI', 'valid', 'pending'),
	(29, NULL, 200.00, '2024-12-26', '676d10172b964.png', '0', 'BANK BSI', 'valid', 'confirmed'),
	(30, NULL, 0.00, '2024-12-26', '676d10c61442a.png', '0', 'BANK BSI', 'valid', 'pending'),
	(40, 64, 0.00, '2024-12-26', '676d3a3710350.png', '0', 'BANK BSI', 'valid', 'pending'),
	(41, 65, 200.00, '2024-12-26', '676d3eea876d9.png', '0', 'BANK BSI', 'valid', 'pending'),
	(42, 66, 200.00, '2024-12-26', '676d4207936f5.png', '0', 'dana', 'valid', 'pending'),
	(43, 68, 200.00, '2024-12-26', '676d643e4a234.png', '0', 'BANK BSI', 'valid', 'pending'),
	(44, 69, 200.00, '2024-12-26', '676d6a87da8f5.png', '0', 'dana', 'valid', 'pending'),
	(45, 71, 200.00, '2024-12-26', '676d741a957bf.png', '0', 'BANK BSI', 'valid', 'pending'),
	(46, 73, 200.00, '2024-12-26', '676d777dd430d.png', '0', 'BANK BSI', 'valid', 'pending'),
	(47, 75, 200.00, '2024-12-26', '676d8608305d0.png', '0', 'dana', 'valid', 'pending'),
	(48, 78, 200.00, '2024-12-26', '676d8ab215678.png', '0', 'BANK BSI', 'valid', 'confirmed'),
	(51, 82, 200.00, '2024-12-27', '676d9368e1cea.png', '0', 'BANK BSI', 'valid', 'pending'),
	(53, 83, 200.00, '2024-12-27', '676d973001b1e.png', '0', 'tati', 'valid', 'pending'),
	(57, 85, 200.00, '2024-12-27', '676d9ab1845aa.png', '0', 'BANK BSI', 'valid', 'pending'),
	(58, 86, 200.00, '2024-12-27', '676d9bd3c8de9.png', '0', 'dana', 'valid', 'confirmed'),
	(59, 87, 200.00, '2024-12-27', '676da1b73059b.png', '0', 'BANK BSI', 'valid', 'pending'),
	(61, 91, 0.00, '2024-12-27', '676dac409ea84.png', '0', 'rora', 'valid', 'pending'),
	(62, 93, 200.00, '2024-12-27', '676dacda7c5f7.png', '0', 'BANK BSI', 'valid', 'pending'),
	(63, 95, 200.00, '2024-12-27', '676', '898998', 'BANK BSI', 'valid', 'pending'),
	(64, 96, 0.00, '2024-12-27', '6.76e151', '898982', 'dana', 'valid', 'confirmed'),
	(70, 96, 0.00, '2024-12-27', '46', '423232', 'BANK BSI', 'valid', 'confirmed'),
	(71, 97, 200.00, '2024-12-27', '47', '435231', 'gopay', 'valid', 'pending'),
	(72, 100, 300.00, '2024-12-27', '48', '764564', 'BANK BSI', 'valid', 'confirmed'),
	(73, 101, 0.00, '2024-12-27', '50', '87829', 'BANK BSI', 'valid', 'confirmed'),
	(74, 102, 200.00, '2024-12-29', '51', '9394990', 'BANK BCA', 'valid', 'confirmed'),
	(75, 104, 200.00, '2024-12-29', '52', '57786688', 'bank bsi', 'valid', 'confirmed'),
	(76, 105, 200.00, '2024-12-29', '53', '6726727', 'BANK BSI', 'valid', 'confirmed'),
	(77, 106, 200.00, '2024-12-29', '54', '56477', 'BANK BSI', 'valid', 'confirmed'),
	(78, 107, 300.00, '2025-01-03', '55', '8980', 'BANK MANDIRI', 'valid', 'confirmed'),
	(79, 108, 200.00, '2025-01-03', '56', '28389', 'BANK BNI', 'valid', 'confirmed'),
	(80, 109, 200.00, '2025-01-03', '57', '897902', 'BANK BSI', 'valid', 'confirmed'),
	(82, 114, 0.00, '2025-01-05', '59', '087887874190', 'DANA', 'valid', 'pending'),
	(83, 115, 200.00, '2025-01-05', '60', '087887874190', 'DANA', 'valid', 'pending'),
	(84, 116, 200.00, '2025-01-05', '61', '1522200', 'transfer', 'valid', 'pending'),
	(85, 121, 200.00, '2025-01-10', '676989535.png', '1232001543', 'Cash', 'valid', 'confirmed'),
	(86, 123, 200.00, '2025-01-10', '676989536.jpg', '087887874190', 'DANA', 'valid', 'pending');

-- Dumping structure for table rumahbelajar.tb_pengguna
CREATE TABLE IF NOT EXISTS `tb_pengguna` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(10) NOT NULL,
  `pass` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `peran` varchar(13) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nomor_hp` varchar(20) NOT NULL,
  PRIMARY KEY (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=latin1;

-- Dumping data for table rumahbelajar.tb_pengguna: ~48 rows (approximately)
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `email`, `username`, `pass`, `peran`, `nomor_hp`) VALUES
	(138, 'mimin', 'admin@eadmin.com', 'min', '$2y$10$GYaTUchr6rU9ud6VDPmIWeYX6W30fCQvek3giamBm3BWvEHBnfQpC', 'admin', '3233123'),
	(141, 'nani', 'nani@nani.com', 'nani', '$2y$10$FkE40wuz1OEl.mvvT.0MHubpweTRWaXV.P1jEA4Es5dayElkhFWRq', 'admin', '3233123'),
	(143, 'labubu', 'labubu@gmail.com', 'labubu', '$2y$10$pQFZgl62.Pt2fMK9U4/8teha5f.1XWRk.VR4/VZafduCD2.WnuRgG', 'siswa', '882937932'),
	(144, 'bia', 'bia@gmail.com', 'bia', '$2y$10$1yJqFG7Fy9ddtJqYN8LQIedPDj1Q2lWigzsD.DcPUPr8towvNukQ6', 'siswa', '827981'),
	(145, 'yaya', 'yaya@gmail.com', 'yaya', '$2y$10$mWNe./Z78.UtxSqVdY//BOLwkwXMftoWahsdhF.vyh7Q/IFyAehwe', 'siswa', '52421'),
	(149, 'suhailah', 'hela@gmail.com', 'hela', '$2y$10$qJFDskHbFrilzhPft1mns.zXkroaUb8X79XJRkzVGjrhZ2XUbFqte', 'guru', '525371178'),
	(150, 'isel', 'isel@gmail.com', 'isel', '$2y$10$d1qsDeDydzRZBUR5RCpIv.PMJr9FZcCb6draR9ILk0z327hsh28gO', 'siswa', '422324'),
	(151, 'toto', 'toto@gmail.com', 'toto', '$2y$10$XwYw2OJiD4KA/CXKPmdrle8uiVyfYjc3nLDjYxX.PHK0rLWHJzSDm', 'siswa', '47182719'),
	(152, 'tuti', 'tuti@gmail.com', 'tuti', '$2y$10$huu55gud2mtpXTfa5L8g6euwqsaG8J.5/qxn0e3asb/IKiwO0GI/W', 'siswa', '834742'),
	(153, 'rio', 'rio@gmail.com', 'rio', '$2y$10$3TdytvxngtDb5oQghf/uUulBN1urnwMt0r8WLKCWMaQSqKI1zob3u', 'siswa', '48129'),
	(154, 'gio', 'gio@gmail.com', 'gio', '$2y$10$3zwW2fJyDFjGXox1xO.hV.X8NMW2uvgcTr8pDDDoxvGBEDKgBFO.m', 'siswa', '61318937'),
	(155, 'unyu', 'unyu@gmail.com', 'unyu', '$2y$10$A8HQBny.qOIVWtwOJTMR8uoAJESdlu4V.nWsvNhIrFd5ICG1BtNA.', 'siswa', '53243'),
	(156, 'salwa', 'naylasalwa775@gmail.com', 'awa', '$2y$10$bSe4UFktD/Ly.m9fNhCLxeExa19dXoSNjwCMOiioLXjU1WjO1F672', 'siswa', '088213125939'),
	(157, 'salman', 'salmansalman35556@gmail.com', 'salman', '$2y$10$SffRYQJ3SB736zGm74GbhO3flxS3r4vllq70l4wsDNLwdPd8haEee', 'siswa', '09818312'),
	(158, 'dodi', 'dodi@gmail.com', 'dodi', '$2y$10$tdEXF9FGbIj8H5D6vAgKhOX.nFBrUjrfAHSWt3Rhd5Za0nz8dkYia', 'siswa', '4234242'),
	(159, 'wini', 'wini@gmail.com', 'wini', '$2y$10$ADwiFfjYo9quLH6.N0PN6OZ.gkdwRU4MYId.0bmPbY.mz2QWdHA3i', 'siswa', '7493902'),
	(160, 'tari', 'tari@gmail.com', 'tari', '$2y$10$ICJs18rtD2/a1vFto68gxeUuqygJBkiVaOHgF7qzyhAL3R/7fXj3C', 'siswa', '87872'),
	(161, 'nana', 'nana@gmail.com', 'nana', '$2y$10$9UFaAZrLkbwDivt3TNwUd.cM4h77veZanPfMO44cBxZ8PTNJkZa4W', 'siswa', '43872749'),
	(162, 'jeki', 'jeki@gmail.com', 'jeki', '$2y$10$tpikA//5xpcGnu9irWDsTOjh0Hdn4OupXvaDybDeLVT1dGsE7/ikG', 'siswa', '47637728'),
	(163, 'jio', 'jio@gmail.com', 'jio', '$2y$10$nrQxsxUqSv.um5d94j2Siex7/UrLEsp/cjboqCcr2wc4cZm5/ZcIa', 'siswa', '32332'),
	(164, 'fufufafa', 'fufa@gmail.com', 'fufufafa', '$2y$10$vDJ/uyGYYLoyn0907n.56e4rti8pXsWyGmOxyzHtkNpxXMyVpzxhi', 'siswa', '833209'),
	(165, 'cahyo', 'cahyo@gmail.com', 'cahyo', '$2y$10$IBROBStvrY56vPQ/ej8rB.LjLeiTSmrDgLX3NOzFzOY/DogwHMDTy', 'siswa', '535234'),
	(166, 'poah', 'poah@gmail.com', 'poah', '$2y$10$Z2ro8BPJupk9kxJLM.ly4upSWmeFkoW3xdpPAHBo6kIzs5rlEWPnS', 'siswa', '8829728'),
	(167, 'roro', 'roro@gmail.com', 'roro', '$2y$10$VJIEe1prrHAmtX63NrRJjOTJfbeLg9Cw5WSed2zpJchzKYagbfpSu', 'siswa', '53534'),
	(168, 'ea', 'ea@gmail.com', 'ea', '$2y$10$BnNiTdtxBWqJ/BtmOosyMOVFnkenVMEruA8Kn/CHZ0PK028jP90H2', 'siswa', '55342'),
	(169, 'riri', 'riri@gmail.com', 'riri', '$2y$10$oLOGF41SsqfdZkF3QNQbOOdcUx34NbJAEiKtoEAD8YSaamBu05x4m', 'siswa', '76677789'),
	(170, 'qui', 'qui@gmail.com', 'qui', '$2y$10$p5EWjVUl6Q3q77hX5Ee0Eej27Ywxzz0L1TwUqjLiqBTn64Pu4y2w2', 'siswa', '53532'),
	(171, 'yoi', 'yoi@gmail.com', 'yoi', '$2y$10$EsF1yUZ1VKWE.p/tjwiEXOUZDvK1a20BUSwBbvE3oXOKWNdUZKhkO', 'siswa', '45342'),
	(172, 'tati', 'tati@gmail.com', 'tati', '$2y$10$fVAhpte9aRVEkTEWv510YODzDdSFfdakdAV01V/ZDHqJ0S1OyldI.', 'siswa', '43213'),
	(173, 'xixi', 'xixi@gmail.com', 'xixi', '$2y$10$dd.UIa.Cey7nyPLide//K.tb960ZNR.JQk6mURa0g38PsSVDekHPO', 'siswa', '3421'),
	(174, 'boni', 'boni@gmail.com', 'boni', '$2y$10$yhohc8CXDqZuCD5Z.Mgdw.tcfH2o/vtdM2GRuCS7RYlLdbcXKGplq', 'siswa', '342212'),
	(175, 'nita', 'nita@gmail.com', 'nita', '$2y$10$3V3oNSZ/3w9DTaL3eJvZduxppyravtHPuDror2YpTO1QgRMgnfWQ6', 'siswa', '498249801'),
	(176, 'rora', 'rora@gmail.com', 'rora', '$2y$10$KnYH66q9Psx.ucn47BoIc.h6s0qJ/rO4K2sLkd3SEw1ZKrpqhfI8K', 'siswa', '95209'),
	(177, 'uni', 'uni@gmail.com', 'uni', '$2y$10$QWmfRpqNK6YLWY8H0PKqhuSlOqixBnKYkNuizN4.oMUSCM5ob38Ii', 'siswa', '8989'),
	(178, 'yuna', 'yuna@gmail.com', 'yuna', '$2y$10$Rai604GA.wMY2nt257NXheJsDgaCoPIwEZaCJKIo2svh.dksaNAcq', 'siswa', '67687878'),
	(179, 'edo', 'edo@gmiil.com', 'edo', '$2y$10$qi3SA2ND5B64oselSrLUG.z1pIIHuE0.28gsG89bmzNPzuzEtLg0m', 'siswa', '82420'),
	(180, 'qana', 'qana@gmail.com', 'qana', '$2y$10$jdTjsIuoeDeg1g4nio/Xv.kiO2iC6lq/eSSWFlIr1CYffa6Iqgvkm', 'siswa', '209029'),
	(182, 'jaki', 'jaki@gamil.com', 'jaki', '$2y$10$p9LIFNBpBETpKNDsU0oQ1u24seKLAkHy4Xuw9u3H9apNHwnQEp9te', 'siswa', '7846209'),
	(183, 'nana', 'hana@hana', 'hana', '$2y$10$rASmDC58vEBeiLP0wqCHauz48Q9EDiHJjLGb31VyhBxteMRx554VK', 'siswa', '8337'),
	(184, 'ono', 'ono@gmail.com', 'ono', '$2y$10$AQ8061QNhEpXGwubSFPlXemQhW88FEtxEVr4FwGH0ooyVYcBgVyWe', 'siswa', '982728'),
	(185, 'onno', 'oono@gmail.com', 'onno', '$2y$10$4FI1WzhLUSguZ2q9uTJ3rOlt08xAi3zovTP18HuM1ts.vNRPMOn8S', 'siswa', '838747'),
	(186, 'nono', 'nono@gmail.com', 'nono', '$2y$10$WheTx8I8Hs7pkZPqk6BoSerDvsX7yPIdH8G1Mdg3d9YnhC2zI.jTi', 'siswa', '666556'),
	(187, 'ulfah', 'ulfah@gmail.com', 'ulfah', '$2y$10$7TE2rQyu/Hx51Bi6.gWQ5eezYRlD.U4FGI49QjYYK..ZzMW0BObFe', 'guru', '089327627'),
	(188, 'gina', 'gina@gmail.com', 'gina', '$2y$10$nMpdoHMSlt6dwasQWOS.S.QGhZcHLVGFKN1j3gJc5opaZYqf3.z2G', 'siswa', '0438930'),
	(189, 'fani', 'fani@gmail.com', 'fani', '$2y$10$UP0NZLWeEqD9BdIcv6SW8uBT9wDcFuqz8rJ8lkHfPUT08CQ3VXCxe', 'siswa', '73984790'),
	(190, 'hani', 'hani@gmail.com', 'hani', '$2y$10$M4PFycvNM0beh1S7FPJ3R.IrlMul/ZsFAhD/Ao58tEcbLUTR1FBxW', 'siswa', '5323431'),
	(191, 'Rifha ', 'reindawarichrifha@gmail.com', 'mpaa', '$2y$10$cls9prStH64WRfM9oOB3mul8f2kG0CFQ8orD4z2f1gesNhzGOUwE2', 'siswa', '087887874190'),
	(194, 'banu', 'banu@gmail.com', 'banu', '$2y$10$nTeZK.2mCNlqtn.fVp6.z.5lrxm6J6M/j6EX0OrdzWp663CyVch2q', 'siswa', '085714658013'),
	(195, 'Muhammad Diki Dwi Nugraha', 'diki@aiia.co.id', 'diki', '$2y$10$5pExjD2iXD9NYyaBGcXFZ.GwXPWpOvfkjCuS7H3aiBa9uh2d4g5ZW', 'siswa', '082125008160'),
	(196, 'Pak Diki', 'if23.dikiraha@dosen.ubpkarawang.ac.id', 'dikiraha', '$2y$10$.6iqiPt9jiQugkmotWI5NedyKiGi8in6hIinD150yDiaHzIlHGw7G', 'guru', '0881081929629');

-- Dumping structure for table rumahbelajar.tb_pertemuan
CREATE TABLE IF NOT EXISTS `tb_pertemuan` (
  `id_pertemuan` int NOT NULL AUTO_INCREMENT,
  `id_siswa` int NOT NULL,
  `id_materi` int DEFAULT '0',
  `tanggal_pertemuan` date NOT NULL,
  `status_pertemuan` enum('belum','sudah') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'belum',
  `rekaman_zoom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pertemuan`),
  KEY `tb_pertemuan_ibfk_1` (`id_siswa`),
  KEY `id_materi` (`id_materi`),
  CONSTRAINT `tb_pertemuan_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_pertemuan_ibfk_2` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rumahbelajar.tb_pertemuan: ~46 rows (approximately)
INSERT INTO `tb_pertemuan` (`id_pertemuan`, `id_siswa`, `id_materi`, `tanggal_pertemuan`, `status_pertemuan`, `rekaman_zoom`) VALUES
	(53, 54, NULL, '2025-01-04', 'sudah', NULL),
	(54, 54, NULL, '2025-01-03', 'sudah', NULL),
	(55, 54, NULL, '2025-01-03', 'sudah', NULL),
	(56, 54, NULL, '2025-01-03', 'sudah', NULL),
	(57, 54, NULL, '2025-01-03', 'sudah', NULL),
	(58, 53, NULL, '2025-01-03', 'belum', NULL),
	(59, 53, NULL, '2025-01-03', 'belum', NULL),
	(60, 53, NULL, '2025-01-03', 'belum', NULL),
	(61, 53, NULL, '2025-01-03', 'belum', NULL),
	(62, 53, NULL, '2025-01-03', 'belum', NULL),
	(63, 53, NULL, '2025-01-03', 'belum', NULL),
	(64, 43, NULL, '2025-01-03', 'sudah', NULL),
	(65, 43, NULL, '2025-01-03', 'sudah', NULL),
	(66, 43, NULL, '2025-01-03', 'sudah', NULL),
	(67, 54, NULL, '2025-01-03', 'sudah', NULL),
	(68, 54, NULL, '2025-01-03', 'sudah', NULL),
	(69, 54, NULL, '2025-01-03', 'sudah', NULL),
	(70, 54, NULL, '2025-01-03', 'sudah', NULL),
	(71, 54, NULL, '2025-01-03', 'sudah', NULL),
	(72, 43, NULL, '2025-01-03', 'sudah', NULL),
	(73, 43, NULL, '2025-01-10', 'sudah', NULL),
	(74, 43, NULL, '2025-01-12', 'sudah', NULL),
	(75, 43, NULL, '2025-01-12', 'sudah', NULL),
	(76, 43, NULL, '2025-01-12', 'sudah', NULL),
	(77, 58, NULL, '2025-01-10', 'sudah', NULL),
	(78, 59, NULL, '2025-01-08', 'sudah', NULL),
	(79, 59, NULL, '2025-01-11', 'sudah', NULL),
	(80, 59, NULL, '2025-01-13', 'sudah', NULL),
	(81, 59, NULL, '2025-01-18', 'sudah', NULL),
	(82, 59, NULL, '2025-01-20', 'sudah', NULL),
	(83, 59, NULL, '2025-01-22', 'sudah', NULL),
	(84, 59, NULL, '2025-01-23', 'sudah', NULL),
	(85, 59, NULL, '2025-01-23', 'sudah', NULL),
	(86, 59, NULL, '2025-01-23', 'sudah', NULL),
	(87, 60, NULL, '2025-01-04', 'sudah', NULL),
	(88, 58, NULL, '2025-01-06', 'sudah', NULL),
	(89, 58, NULL, '2025-01-14', 'sudah', NULL),
	(90, 58, NULL, '2025-01-04', 'belum', NULL),
	(91, 58, NULL, '2025-01-15', 'belum', NULL),
	(92, 58, NULL, '2025-01-15', 'belum', NULL),
	(93, 58, NULL, '2025-01-15', 'belum', NULL),
	(94, 58, NULL, '2025-01-15', 'belum', NULL),
	(95, 58, NULL, '2025-01-15', 'belum', NULL),
	(96, 58, NULL, '2025-01-15', 'sudah', NULL),
	(97, 58, NULL, '2025-01-15', 'belum', NULL),
	(98, 14, 9, '2025-01-04', 'belum', NULL),
	(99, 65, NULL, '2025-01-10', 'sudah', NULL),
	(100, 65, NULL, '2025-01-11', 'sudah', NULL);

-- Dumping structure for table rumahbelajar.tb_siswa
CREATE TABLE IF NOT EXISTS `tb_siswa` (
  `id_siswa` int NOT NULL AUTO_INCREMENT,
  `id_pendaftaran` int NOT NULL,
  `nama_siswa` varchar(40) NOT NULL,
  `status` enum('aktif','non aktif') NOT NULL DEFAULT 'non aktif',
  `sisa_pertemuan` int DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  PRIMARY KEY (`id_siswa`),
  KEY `id_pendaftaran` (`id_pendaftaran`),
  CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `tbl_pendaftaran` (`id_pendaftaran`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

-- Dumping data for table rumahbelajar.tb_siswa: ~47 rows (approximately)
INSERT INTO `tb_siswa` (`id_siswa`, `id_pendaftaran`, `nama_siswa`, `status`, `sisa_pertemuan`, `exp_date`) VALUES
	(2, 48, 'tuti', 'non aktif', 0, NULL),
	(3, 50, 'gio', 'non aktif', 0, NULL),
	(6, 55, 'labubu', 'aktif', 4, '2025-01-26'),
	(7, 56, 'unyu', 'aktif', 4, '2025-01-26'),
	(8, 58, 'dodi', 'non aktif', NULL, NULL),
	(9, 59, 'wini', 'non aktif', NULL, NULL),
	(14, 61, 'nana', 'aktif', 4, '2025-01-26'),
	(15, 62, 'nana', 'non aktif', NULL, NULL),
	(25, 64, 'jeki', 'non aktif', NULL, NULL),
	(26, 65, 'jio', 'non aktif', NULL, NULL),
	(27, 66, 'fufufafa', 'non aktif', NULL, NULL),
	(28, 68, 'cahyo', 'non aktif', NULL, NULL),
	(29, 69, 'poah', 'non aktif', NULL, NULL),
	(30, 71, 'roro', 'non aktif', NULL, NULL),
	(31, 73, 'ea', 'non aktif', NULL, NULL),
	(32, 75, 'riri', 'non aktif', NULL, NULL),
	(33, 78, 'qui', 'aktif', 4, '2025-01-27'),
	(34, 82, 'yoi', 'non aktif', NULL, NULL),
	(35, 82, 'yoi', 'non aktif', NULL, NULL),
	(36, 82, 'yoi', 'non aktif', NULL, NULL),
	(37, 82, 'yoi', 'non aktif', NULL, NULL),
	(38, 83, 'tati', 'non aktif', NULL, NULL),
	(39, 83, 'tati', 'non aktif', NULL, NULL),
	(40, 84, 'tati', 'non aktif', NULL, NULL),
	(41, 84, 'tati', 'non aktif', NULL, NULL),
	(42, 85, 'xixi', 'non aktif', NULL, NULL),
	(43, 86, 'boni', 'non aktif', 0, '2025-01-29'),
	(44, 87, 'nita', 'non aktif', NULL, NULL),
	(45, 88, 'nita', 'non aktif', NULL, NULL),
	(46, 91, 'rora', 'non aktif', NULL, NULL),
	(47, 93, 'uni', 'non aktif', NULL, NULL),
	(48, 95, 'yuna', 'non aktif', NULL, NULL),
	(49, 96, 'salwa', 'aktif', 4, '2025-01-27'),
	(50, 96, 'salwa', 'aktif', 4, '2025-01-27'),
	(51, 97, 'isel', 'non aktif', NULL, NULL),
	(52, 100, 'edo', 'non aktif', 0, '2025-01-27'),
	(53, 101, 'qana', 'non aktif', 0, '2025-01-27'),
	(54, 102, 'jaki', 'non aktif', 0, '2025-01-29'),
	(55, 104, 'ono', 'aktif', 8, '2025-01-29'),
	(56, 105, 'onno', 'aktif', 8, '2025-01-29'),
	(57, 106, 'nono', 'aktif', 8, '2025-01-29'),
	(58, 107, 'gina', 'aktif', 1, '2025-02-03'),
	(59, 108, 'fani', 'non aktif', 0, '2025-02-03'),
	(60, 109, 'hani', 'aktif', 7, '2025-02-03'),
	(62, 114, 'Rifha ', 'non aktif', NULL, NULL),
	(63, 115, 'Rifha ', 'non aktif', NULL, NULL),
	(64, 116, 'banu', 'non aktif', NULL, NULL),
	(65, 121, 'Muhammad Diki Dwi Nugraha', 'aktif', 6, '2025-02-10'),
	(66, 123, 'Rifha ', 'non aktif', NULL, NULL);

-- Dumping structure for table rumahbelajar.tb_tugas
CREATE TABLE IF NOT EXISTS `tb_tugas` (
  `id_tugas` int NOT NULL AUTO_INCREMENT,
  `id_siswa` int DEFAULT NULL,
  `id_pertemuan` int DEFAULT NULL,
  `file_tugas` varchar(255) DEFAULT NULL,
  `tanggal_upload` datetime DEFAULT NULL,
  PRIMARY KEY (`id_tugas`),
  KEY `id_siswa` (`id_siswa`),
  KEY `id_pertemuan` (`id_pertemuan`),
  CONSTRAINT `tb_tugas_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`),
  CONSTRAINT `tb_tugas_ibfk_2` FOREIGN KEY (`id_pertemuan`) REFERENCES `tb_pertemuan` (`id_pertemuan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rumahbelajar.tb_tugas: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
