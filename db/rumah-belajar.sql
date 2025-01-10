-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 09, 2025 at 08:32 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rumahbelajar`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_harga`
--

CREATE TABLE `tbl_harga` (
  `id_harga` int NOT NULL,
  `id_kursus` int NOT NULL,
  `pertemuan` int NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_harga`
--

INSERT INTO `tbl_harga` (`id_harga`, `id_kursus`, `pertemuan`, `harga`) VALUES
(1, 1, 8, '0.00'),
(2, 1, 12, '0.00'),
(3, 2, 8, '200.00'),
(4, 2, 12, '300.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kursus`
--

CREATE TABLE `tbl_kursus` (
  `id_kursus` int NOT NULL,
  `nama_kursus` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kursus`
--

INSERT INTO `tbl_kursus` (`id_kursus`, `nama_kursus`) VALUES
(1, 'Tajwid'),
(2, 'Matematika');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pendaftaran`
--

CREATE TABLE `tbl_pendaftaran` (
  `id_pendaftaran` int NOT NULL,
  `id_kursus` int NOT NULL,
  `id_harga` int NOT NULL,
  `id_kelas` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `tanggal_pendaftaran` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_guru`
--

CREATE TABLE `tb_guru` (
  `id_guru` int NOT NULL,
  `nama_guru` varchar(40) NOT NULL,
  `id_pengguna` int NOT NULL,
  `id_kursus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_guru`
--

INSERT INTO `tb_guru` (`id_guru`, `nama_guru`, `id_pengguna`, `id_kursus`) VALUES
(36, 'SUHAILAH', 149, 1),
(37, 'ulfah', 187, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` int NOT NULL,
  `nama_kelas` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kelas`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `tb_materi`
--

CREATE TABLE `tb_materi` (
  `id_materi` int NOT NULL,
  `id_kursus` int NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `judul_materi` varchar(255) NOT NULL,
  `deskripsi` text,
  `file_materi` varchar(255) DEFAULT NULL,
  `link_platform` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_materi`
--

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
(10, 2, 2, 'Bagi bagian', NULL, 'uploads/materi/Pertemuan 4 - Algoritma Rekursif.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(11, 2, 2, 'trapesium', NULL, 'uploads/materi/Normalisasi.pptx', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(12, 2, 2, 'trapesium', NULL, 'uploads/materi/Conseptual DB, Logical Model  Physical model.pptx', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(13, 2, 2, 'trapesium', NULL, 'uploads/materi/Conseptual DB, Logical Model  Physical model.pptx', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(16, 1, NULL, 'hukum ikhfa', NULL, 'uploads/materi/ADBO Pertemuan 4 Use Case_compressed.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(17, 2, 2, 'algoritma', NULL, 'uploads/materi/ADBO 1_compressed.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(18, 2, 2, 'trigonometri', NULL, '../siswa/uploads/materi/Normalisasi.pptx', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(19, 2, 9, 'mtk 2', NULL, '../siswa/uploads/materi/CCNA_ITN_Chp1.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(20, 2, 9, 'matematika 3', NULL, '../siswa/uploads/materi/Modul Praktikum ADBO 2023_compressed .pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(22, 2, 9, 'matematika 3', NULL, '../siswa/uploads/materi/(3) Hirarki Chomsky 2024.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(26, 2, 9, 'barisan dan deret', NULL, '../siswa/uploads/materi/(11) Aturan Produksi_FSA 2024.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(27, 2, 9, 'matematika 4', NULL, '../siswa/uploads/materi/(14) Push Down Automata (PDA) 2024.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(28, 1, NULL, 'hukum idhgam', NULL, '../siswa/uploads/materi/14 Graf (Lanjutan) IF23.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(29, 1, NULL, 'hukum  iqlab', NULL, '../siswa/uploads/materi/04-05 HIMPUNAN IF23.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(30, 2, 8, 'persamaan kuadrat', NULL, '../siswa/uploads/materi/12 Perkalian Silang Vektor.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(31, 2, 8, 'bangun ruang', NULL, '../siswa/uploads/materi/07 Ekspansi Kofaktor.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(32, 2, 8, 'matriks', NULL, '../siswa/uploads/materi/1-Pengenalan Struktur Data.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(33, 2, 8, 'persamaan linear', NULL, '../siswa/uploads/materi/06-07 relasi dan fungsi IF23.pdf', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(34, 2, 8, 'limit fungsi', NULL, '../siswa/uploads/materi/Screenshot 2023-11-03 111917.png', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(35, 2, 8, 'vektor', NULL, '../siswa/uploads/materi/Screenshot 2023-11-03 111917.png', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(36, 2, 8, 'mtk 5', NULL, '../siswa/uploads/materi/Screenshot 2023-11-03 111917.png', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 '),
(37, 2, 8, 'matematika 6', NULL, '../siswa/uploads/materi/Screenshot 2023-11-03 111917.png', 'https://us04web.zoom.us/j/79963199549?pwd=FoNXOcz7fVTXD80YnGD2P0qHJSpblo.1 ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_payment`
--

CREATE TABLE `tb_payment` (
  `id_payment` int NOT NULL,
  `id_pendaftaran` int DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `tanggal_payment` date NOT NULL,
  `bukti_payment` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_rek` varchar(20) NOT NULL,
  `metode_payment` varchar(25) NOT NULL,
  `status_pembayaran` enum('invalid','valid') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'invalid',
  `status_konfirmasi` enum('pending','confirmed','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id_pengguna` int NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(10) NOT NULL,
  `pass` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `peran` varchar(13) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nomor_hp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `email`, `username`, `pass`, `peran`, `nomor_hp`) VALUES
(138, 'mimin', 'admin@eadmin.com', 'min', '$2y$10$GYaTUchr6rU9ud6VDPmIWeYX6W30fCQvek3giamBm3BWvEHBnfQpC', 'admin', '3233123'),
(149, 'suhailah', 'hela@gmail.com', 'hela', '$2y$10$qJFDskHbFrilzhPft1mns.zXkroaUb8X79XJRkzVGjrhZ2XUbFqte', 'guru', '525371178'),
(187, 'ulfah', 'ulfah@gmail.com', 'ulfah', '$2y$10$7TE2rQyu/Hx51Bi6.gWQ5eezYRlD.U4FGI49QjYYK..ZzMW0BObFe', 'guru', '089327627'),
(220, 'nani', 'nani@gmail.com', 'nani', '$2y$10$uaNVlUJUhM0qah4saso1tet37a.0ObKGTowmheUCRmhK2abQrRVgO', 'admin', '081234567890');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pertemuan`
--

CREATE TABLE `tb_pertemuan` (
  `id_pertemuan` int NOT NULL,
  `id_siswa` int NOT NULL,
  `id_materi` int DEFAULT '0',
  `tanggal_pertemuan` date NOT NULL,
  `status_pertemuan` enum('belum','sudah') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'belum',
  `rekaman_zoom` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `id_siswa` int NOT NULL,
  `id_pendaftaran` int NOT NULL,
  `nama_siswa` varchar(40) NOT NULL,
  `status` enum('aktif','non aktif') NOT NULL DEFAULT 'non aktif',
  `sisa_pertemuan` int DEFAULT NULL,
  `exp_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_harga`
--
ALTER TABLE `tbl_harga`
  ADD PRIMARY KEY (`id_harga`),
  ADD KEY `id_kursus` (`id_kursus`);

--
-- Indexes for table `tbl_kursus`
--
ALTER TABLE `tbl_kursus`
  ADD PRIMARY KEY (`id_kursus`);

--
-- Indexes for table `tbl_pendaftaran`
--
ALTER TABLE `tbl_pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD KEY `id_harga` (`id_harga`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_kursus` (`id_kursus`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `tb_guru`
--
ALTER TABLE `tb_guru`
  ADD PRIMARY KEY (`id_guru`) USING BTREE,
  ADD KEY `id_pengguna` (`id_pengguna`),
  ADD KEY `tb_guru_ibfk_3` (`id_kursus`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tb_materi`
--
ALTER TABLE `tb_materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `id_kursus` (`id_kursus`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `tb_payment`
--
ALTER TABLE `tb_payment`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `tb_payment_ibfk_1` (`id_pendaftaran`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `tb_pertemuan`
--
ALTER TABLE `tb_pertemuan`
  ADD PRIMARY KEY (`id_pertemuan`),
  ADD KEY `tb_pertemuan_ibfk_1` (`id_siswa`),
  ADD KEY `id_materi` (`id_materi`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD KEY `id_pendaftaran` (`id_pendaftaran`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_harga`
--
ALTER TABLE `tbl_harga`
  MODIFY `id_harga` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_kursus`
--
ALTER TABLE `tbl_kursus`
  MODIFY `id_kursus` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_pendaftaran`
--
ALTER TABLE `tbl_pendaftaran`
  MODIFY `id_pendaftaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `tb_guru`
--
ALTER TABLE `tb_guru`
  MODIFY `id_guru` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tb_materi`
--
ALTER TABLE `tb_materi`
  MODIFY `id_materi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tb_payment`
--
ALTER TABLE `tb_payment`
  MODIFY `id_payment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `tb_pertemuan`
--
ALTER TABLE `tb_pertemuan`
  MODIFY `id_pertemuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_harga`
--
ALTER TABLE `tbl_harga`
  ADD CONSTRAINT `tbl_harga_ibfk_1` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tbl_pendaftaran`
--
ALTER TABLE `tbl_pendaftaran`
  ADD CONSTRAINT `tbl_pendaftaran_ibfk_1` FOREIGN KEY (`id_harga`) REFERENCES `tbl_harga` (`id_harga`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tbl_pendaftaran_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tbl_pendaftaran_ibfk_3` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tbl_pendaftaran_ibfk_4` FOREIGN KEY (`id_pengguna`) REFERENCES `tb_pengguna` (`id_pengguna`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tb_guru`
--
ALTER TABLE `tb_guru`
  ADD CONSTRAINT `tb_guru_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `tb_pengguna` (`id_pengguna`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tb_guru_ibfk_3` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tb_materi`
--
ALTER TABLE `tb_materi`
  ADD CONSTRAINT `tb_materi_ibfk_1` FOREIGN KEY (`id_kursus`) REFERENCES `tbl_kursus` (`id_kursus`),
  ADD CONSTRAINT `tb_materi_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tb_payment`
--
ALTER TABLE `tb_payment`
  ADD CONSTRAINT `tb_payment_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `tbl_pendaftaran` (`id_pendaftaran`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tb_pertemuan`
--
ALTER TABLE `tb_pertemuan`
  ADD CONSTRAINT `tb_pertemuan_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tb_pertemuan_ibfk_2` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `tbl_pendaftaran` (`id_pendaftaran`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
