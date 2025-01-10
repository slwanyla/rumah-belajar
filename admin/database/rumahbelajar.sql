-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20221031.25fe766a26
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 10:50 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

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
-- Table structure for table `tb_guru`
--

CREATE TABLE `tb_guru` (
  `id_guru` int(3) NOT NULL,
  `nip` varchar(10) NOT NULL,
  `nama_guru` varchar(40) NOT NULL,
  `kode_guru` varchar(5) NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `tempat_lahir` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `agama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_guru`
--

INSERT INTO `tb_guru` (`id_guru`, `nip`, `nama_guru`, `kode_guru`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `agama`) VALUES
(19, '16102010', 'MISNAH, S.PD', 'J', 'PEREMPUAN', 'BUNTUSIAPA', '1984-03-07', 'LUWU TIMUR', 'ISLAM'),
(25, '16102014', 'JAMALUDDIN, S.PD', 'N', 'LAKI-LAKI', 'LUWU', '1985-01-07', 'LUWU TIMUR', 'ISLAM');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jadwal`
--

CREATE TABLE `tb_jadwal` (
  `id_jadwal` int(5) NOT NULL,
  `id_mengajar` int(4) NOT NULL,
  `hari` varchar(7) NOT NULL,
  `jam_mulai` varchar(10) NOT NULL,
  `jam_berakhir` varchar(10) NOT NULL,
  `id_kelas` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_jadwal`
--

INSERT INTO `tb_jadwal` (`id_jadwal`, `id_mengajar`, `hari`, `jam_mulai`, `jam_berakhir`, `id_kelas`) VALUES
(100, 41, 'Senin', '13:00', '13:40', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` int(3) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `Jenjang` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `kelas`, `Jenjang`) VALUES
(12, 'VII', 'SMP'),
(13, 'VIII', 'SMP'),
(14, 'IX', 'SMP'),
(17, 'X', 'SMA'),
(18, 'XI', 'SMA'),
(19, 'XII', 'SMA');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id_pengguna` int(3) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `username` varchar(10) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `status` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `username`, `pass`, `status`) VALUES
(26, 'Admin', 'admin', 'admin', 'admin'),
(56, 'SISWA1', '123', '123', 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `tb_presensih_guru`
--

CREATE TABLE `tb_presensih_guru` (
  `id_presensih` int(10) NOT NULL,
  `tanggal` date NOT NULL,
  `nip` varchar(10) NOT NULL,
  `ket` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_presensih_guru`
--

INSERT INTO `tb_presensih_guru` (`id_presensih`, `tanggal`, `nip`, `ket`) VALUES
(1, '2024-12-12', '123', 'H'),
(2, '2024-12-12', '123456', ''),
(3, '2024-12-12', '16102001', ''),
(4, '2024-12-12', '16102002', ''),
(5, '2024-12-12', '16102003', ''),
(6, '2024-12-12', '16102004', ''),
(7, '2024-12-12', '16102005', ''),
(8, '2024-12-12', '16102006', ''),
(9, '2024-12-12', '16102007', ''),
(10, '2024-12-12', '16102008', ''),
(11, '2024-12-12', '16102009', ''),
(12, '2024-12-12', '16102010', ''),
(13, '2024-12-12', '16102011', ''),
(14, '2024-12-12', '16102012', ''),
(15, '2024-12-12', '16102013', ''),
(16, '2024-12-12', '16102014', ''),
(17, '2024-12-13', '16102010', 'H'),
(18, '2024-12-13', '16102014', 'S');

-- --------------------------------------------------------

--
-- Table structure for table `tb_presensih_siswa`
--

CREATE TABLE `tb_presensih_siswa` (
  `id_presensih` int(10) NOT NULL,
  `tanggal` date NOT NULL,
  `nis` varchar(10) NOT NULL,
  `ket` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_presensih_siswa`
--

INSERT INTO `tb_presensih_siswa` (`id_presensih`, `tanggal`, `nis`, `ket`) VALUES
(1, '2024-12-12', '123', 'H'),
(2, '2024-12-13', '123', 'H');

-- --------------------------------------------------------

--
-- Table structure for table `tb_presensi_guru`
--

CREATE TABLE `tb_presensi_guru` (
  `id_presensi` int(10) NOT NULL,
  `id_jadwal` int(5) NOT NULL,
  `tanggal` date NOT NULL,
  `nip` varchar(10) NOT NULL,
  `ket` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_presensi_guru`
--

INSERT INTO `tb_presensi_guru` (`id_presensi`, `id_jadwal`, `tanggal`, `nip`, `ket`) VALUES
(1, 0, '2024-12-12', '123', 'H'),
(2, 0, '2024-12-12', '123456', ''),
(3, 0, '2024-12-12', '16102001', ''),
(4, 0, '2024-12-12', '16102002', ''),
(5, 0, '2024-12-12', '16102003', ''),
(6, 0, '2024-12-12', '16102004', ''),
(7, 0, '2024-12-12', '16102005', ''),
(8, 0, '2024-12-12', '16102006', ''),
(9, 0, '2024-12-12', '16102007', ''),
(10, 0, '2024-12-12', '16102008', ''),
(11, 0, '2024-12-12', '16102009', ''),
(12, 0, '2024-12-12', '16102010', ''),
(13, 0, '2024-12-12', '16102011', ''),
(14, 0, '2024-12-12', '16102012', ''),
(15, 0, '2024-12-12', '16102013', ''),
(16, 0, '2024-12-12', '16102014', ''),
(17, 0, '2024-12-13', '16102010', 'H'),
(18, 0, '2024-12-13', '16102014', 'S');

-- --------------------------------------------------------

--
-- Table structure for table `tb_presensi_siswa`
--

CREATE TABLE `tb_presensi_siswa` (
  `id_presensi` int(10) NOT NULL,
  `id_jadwal` int(5) NOT NULL,
  `tanggal` date NOT NULL,
  `nis` varchar(10) NOT NULL,
  `ket` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_presensi_siswa`
--

INSERT INTO `tb_presensi_siswa` (`id_presensi`, `id_jadwal`, `tanggal`, `nis`, `ket`) VALUES
(1, 0, '2024-12-12', '123', 'H'),
(2, 0, '2024-12-13', '123', 'H');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `id_siswa` int(3) NOT NULL,
  `nis` varchar(10) NOT NULL,
  `nama_siswa` varchar(40) NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `tempat_lahir` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(500) NOT NULL,
  `agama` varchar(10) NOT NULL,
  `nama_ortu` varchar(40) NOT NULL,
  `id_kelas` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`id_siswa`, `nis`, `nama_siswa`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `agama`, `nama_ortu`, `id_kelas`) VALUES
(3, '123', 'SISWA1', 'LAKI-LAKI', 'MUSI BANYUASIN', '2000-11-01', 'BANYUASIN', 'ISLAM', 'ALUCARD', 14);

-- --------------------------------------------------------

--
-- Table structure for table `tb_spp`
--

CREATE TABLE `tb_spp` (
  `idspp` int(11) NOT NULL,
  `idpengguna` int(11) NOT NULL,
  `jumlah` varchar(250) NOT NULL,
  `tanggal` date NOT NULL,
  `foto` text NOT NULL,
  `status` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_spp`
--

INSERT INTO `tb_spp` (`idspp`, `idpengguna`, `jumlah`, `tanggal`, `foto`, `status`) VALUES
(3, 56, '100000', '2024-12-13', 'inf.png', 'Diterima');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_guru`
--
ALTER TABLE `tb_guru`
  ADD PRIMARY KEY (`nip`),
  ADD UNIQUE KEY `id_guru` (`id_guru`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `kode_guru_2` (`kode_guru`),
  ADD KEY `kode_guru` (`kode_guru`);

--
-- Indexes for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_mengajar` (`id_mengajar`),
  ADD KEY `id_mengajar_2` (`id_mengajar`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `username_2` (`username`),
  ADD KEY `username_3` (`username`);

--
-- Indexes for table `tb_presensih_guru`
--
ALTER TABLE `tb_presensih_guru`
  ADD PRIMARY KEY (`id_presensih`);

--
-- Indexes for table `tb_presensih_siswa`
--
ALTER TABLE `tb_presensih_siswa`
  ADD PRIMARY KEY (`id_presensih`);

--
-- Indexes for table `tb_presensi_guru`
--
ALTER TABLE `tb_presensi_guru`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `tb_presensi_siswa`
--
ALTER TABLE `tb_presensi_siswa`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nis`),
  ADD UNIQUE KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_kelas_2` (`id_kelas`);

--
-- Indexes for table `tb_spp`
--
ALTER TABLE `tb_spp`
  ADD PRIMARY KEY (`idspp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_guru`
--
ALTER TABLE `tb_guru`
  MODIFY `id_guru` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  MODIFY `id_jadwal` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id_pengguna` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tb_presensih_guru`
--
ALTER TABLE `tb_presensih_guru`
  MODIFY `id_presensih` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_presensih_siswa`
--
ALTER TABLE `tb_presensih_siswa`
  MODIFY `id_presensih` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_presensi_guru`
--
ALTER TABLE `tb_presensi_guru`
  MODIFY `id_presensi` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_presensi_siswa`
--
ALTER TABLE `tb_presensi_siswa`
  MODIFY `id_presensi` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  MODIFY `id_siswa` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_spp`
--
ALTER TABLE `tb_spp`
  MODIFY `idspp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD CONSTRAINT `tb_jadwal_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_jadwal_ibfk_2` FOREIGN KEY (`id_mengajar`) REFERENCES `tb_mengajar` (`id_mengajar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
