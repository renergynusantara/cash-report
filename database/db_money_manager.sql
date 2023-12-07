-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2023 at 06:44 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_money_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pemasukan`
--

CREATE TABLE `kategori_pemasukan` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori_pemasukan`
--

INSERT INTO `kategori_pemasukan` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Gaji'),
(2, 'Bonus'),
(5, 'Orang Tua');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pengeluaran`
--

CREATE TABLE `kategori_pengeluaran` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori_pengeluaran`
--

INSERT INTO `kategori_pengeluaran` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Makan dan Minum'),
(3, 'Tempat Tinggal'),
(5, 'Entertainment');

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id_pemasukan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id_pemasukan`, `tanggal`, `deskripsi`, `id_kategori`, `jumlah`, `id_user`) VALUES
(1, '2023-11-30', 'Gaji', 1, 5500000, 1),
(2, '2023-11-30', 'Bonus', 2, 500000, 1),
(3, '2023-11-29', 'Bunga', 2, 775, 1),
(4, '2023-11-30', 'Gaji', 1, 5550000, 3),
(6, '2023-11-30', 'Bunga', 2, 775, 3),
(7, '2023-11-28', 'Bonus', 2, 500000, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `tanggal`, `deskripsi`, `id_kategori`, `jumlah`, `id_user`) VALUES
(25, '2023-11-27', 'Makan Malam', 1, 25000, 1),
(26, '2023-11-27', 'Maksi', 1, 15000, 1),
(28, '2023-11-27', 'Makan malam', 1, 20000, 4),
(29, '2023-11-28', 'Netflix', 5, 30000, 1),
(30, '2023-11-28', 'Makan Malam', 1, 17500, 1),
(31, '2023-11-27', 'Kos', 3, 2100000, 1),
(32, '2023-11-28', 'Makan Siang', 1, 23500, 4),
(33, '2023-11-28', 'Netflix', 5, 30000, 4),
(34, '2023-11-28', 'Spotify', 5, 14500, 4),
(35, '2023-11-27', 'Disney Hotstar', 5, 45000, 4),
(36, '2023-11-28', 'Kos', 3, 2100000, 4),
(39, '2023-11-30', 'Makan malam', 1, 23500, 3),
(40, '2023-11-28', 'Netflix', 5, 35000, 3),
(41, '2023-11-29', 'Kos', 3, 2100000, 3),
(42, '2023-11-30', 'Coba', 1, 500000, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'aktif',
  `role` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `email`, `username`, `password`, `status`, `role`) VALUES
(1, 'coba@mail.com', 'coba', '$2y$10$3NjDw.zrzyMcUsgh9N8u7eeoWrW1kC96IMbiAYY5LdPy37g1ndRwu', 'aktif', 'user'),
(2, 'admin@mail.com', 'admin', '$2y$10$ieHjn.ma0jIz9uOfNeNoHugQbU/z1L4oO3iZ2AosYXp/fGVayjaRi', 'aktif', 'admin'),
(3, 'budi@mail.com', 'budi', '$2y$10$yQVYoNb7yA6yJjAEwX1nkeiFMh9T6Hr9X5fT0hrv3R7tnZftYdvua', 'aktif', 'user'),
(4, 'andi@mail.com', 'andi', '$2y$10$aKX8y23dqC4dsjCOhnqFPOb6KJJzIUImUVqzyACKrH2IdKZe6DvhW', 'aktif', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori_pemasukan`
--
ALTER TABLE `kategori_pemasukan`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kategori_pengeluaran`
--
ALTER TABLE `kategori_pengeluaran`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id_pemasukan`),
  ADD KEY `fk_id_user_pemasukan` (`id_user`),
  ADD KEY `fk_id_kategori_pemasukan` (`id_kategori`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `fk_id_kategori_pengeluaran` (`id_kategori`),
  ADD KEY `fk_id_user_pengeluaran` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori_pemasukan`
--
ALTER TABLE `kategori_pemasukan`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori_pengeluaran`
--
ALTER TABLE `kategori_pengeluaran`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id_pemasukan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `fk_id_kategori_pemasukan` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_pemasukan` (`id_kategori`),
  ADD CONSTRAINT `fk_id_user_pemasukan` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `fk_id_kategori_pengeluaran` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_pengeluaran` (`id_kategori`),
  ADD CONSTRAINT `fk_id_user_pengeluaran` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
