-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2024 at 09:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inspektorat_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `nomor_handphone` varchar(20) DEFAULT NULL,
  `jenis_pengaduan` enum('lkpd','kinerja','lainnya') NOT NULL,
  `isi_pengaduan` text NOT NULL,
  `lampiran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('inspektorat','perangkat_daerah') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `password`, `role`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'inspektorat');

-- --------------------------------------------------------

--
-- Table structure for table `perangkat_daerah`
--

CREATE TABLE `perangkat_daerah` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perangkat_daerah`
--

INSERT INTO `perangkat_daerah` (`id`, `nama`, `password`) VALUES
(1, 'asdasd', '$2y$10$N5kW8SujNCkzONhkB8K/UuxDqsp.9Fmo8OVCdlRW5TmIl1QK8EWC6'),
(3, 'aaa', '$2y$10$DC6a1Le7bu/dnySOnmFWle9H2PrgriNzPMnRL53ZxTxp/UN0y./DS'),
(4, 'aa', '$2y$10$fuyoVn1iyjeRt8O5shJdYurnKKCom.B4gW8f6dhFDFRGSk61xQur2');

-- --------------------------------------------------------

--
-- Table structure for table `rekomendasi`
--

CREATE TABLE `rekomendasi` (
  `id` int(11) NOT NULL,
  `temuan_id` int(11) DEFAULT NULL,
  `uraian` text NOT NULL,
  `sifat` enum('finansial','non_finansial') NOT NULL,
  `nilai` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temuan`
--

CREATE TABLE `temuan` (
  `id` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `jenis_laporan` enum('lkpd','kinerja','lainnya') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `rekomendasi_total` decimal(15,2) DEFAULT NULL,
  `lampiran_pdf` varchar(255) DEFAULT NULL,
  `id_perangkat_daerah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temuan`
--

INSERT INTO `temuan` (`id`, `tahun`, `jenis_laporan`, `judul`, `jumlah`, `nilai`, `rekomendasi_total`, `lampiran_pdf`, `id_perangkat_daerah`) VALUES
(2, '2005', 'lkpd', '123', 123, 123.00, 123.00, 'my-image (1).png', 1),
(3, '2005', 'lkpd', '123', 123, 123.00, 123.00, 'my-image (1).png', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tindak_lanjut`
--

CREATE TABLE `tindak_lanjut` (
  `id` int(11) NOT NULL,
  `temuan_id` int(11) DEFAULT NULL,
  `uraian` text NOT NULL,
  `sifat` enum('finansial','non_finansial') NOT NULL,
  `periode` text NOT NULL,
  `bukti_tindak_lanjut` varchar(255) DEFAULT NULL,
  `id_perangkat_daerah` int(11) NOT NULL,
  `id_uraian` int(11) NOT NULL,
  `status` enum('belum','sesuai','dikembalikan ke dinas','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uraian_rekomendasi`
--

CREATE TABLE `uraian_rekomendasi` (
  `id` int(11) NOT NULL,
  `id_temuan` int(11) NOT NULL,
  `uraian` text NOT NULL,
  `sifat` enum('finansial','non finansial') NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uraian_rekomendasi`
--

INSERT INTO `uraian_rekomendasi` (`id`, `id_temuan`, `uraian`, `sifat`, `nilai`, `created_at`) VALUES
(3, 2, 'aaa', 'finansial', 123.00, '2024-11-07 18:16:22'),
(4, 2, 'asd', 'non finansial', 123.00, '2024-11-07 19:22:19'),
(5, 3, 'asd', 'finansial', 123.00, '2024-11-07 19:39:42');

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_tindak`
--

CREATE TABLE `verifikasi_tindak` (
  `id` int(11) NOT NULL,
  `temuan_id` int(11) DEFAULT NULL,
  `perangkat_daerah_id` int(11) DEFAULT NULL,
  `status` enum('belum','sudah_sesuai','dikembalikan_ke_dinas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perangkat_daerah`
--
ALTER TABLE `perangkat_daerah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temuan_id` (`temuan_id`);

--
-- Indexes for table `temuan`
--
ALTER TABLE `temuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temuan_id` (`temuan_id`),
  ADD KEY `fk_id_perangkat_daerah` (`id_perangkat_daerah`),
  ADD KEY `fk_id_uraian` (`id_uraian`);

--
-- Indexes for table `uraian_rekomendasi`
--
ALTER TABLE `uraian_rekomendasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_temuan` (`id_temuan`);

--
-- Indexes for table `verifikasi_tindak`
--
ALTER TABLE `verifikasi_tindak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temuan_id` (`temuan_id`),
  ADD KEY `perangkat_daerah_id` (`perangkat_daerah_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `perangkat_daerah`
--
ALTER TABLE `perangkat_daerah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temuan`
--
ALTER TABLE `temuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `uraian_rekomendasi`
--
ALTER TABLE `uraian_rekomendasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `verifikasi_tindak`
--
ALTER TABLE `verifikasi_tindak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  ADD CONSTRAINT `rekomendasi_ibfk_1` FOREIGN KEY (`temuan_id`) REFERENCES `temuan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  ADD CONSTRAINT `fk_id_perangkat_daerah` FOREIGN KEY (`id_perangkat_daerah`) REFERENCES `perangkat_daerah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_uraian` FOREIGN KEY (`id_uraian`) REFERENCES `uraian_rekomendasi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tindak_lanjut_ibfk_1` FOREIGN KEY (`temuan_id`) REFERENCES `temuan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uraian_rekomendasi`
--
ALTER TABLE `uraian_rekomendasi`
  ADD CONSTRAINT `uraian_rekomendasi_ibfk_1` FOREIGN KEY (`id_temuan`) REFERENCES `temuan` (`id`);

--
-- Constraints for table `verifikasi_tindak`
--
ALTER TABLE `verifikasi_tindak`
  ADD CONSTRAINT `verifikasi_tindak_ibfk_1` FOREIGN KEY (`temuan_id`) REFERENCES `temuan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verifikasi_tindak_ibfk_2` FOREIGN KEY (`perangkat_daerah_id`) REFERENCES `perangkat_daerah` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
