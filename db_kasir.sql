-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Des 2025 pada 07.00
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kasir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL,
  `nama_toko` varchar(100) NOT NULL,
  `alamat_toko` text NOT NULL,
  `telp_toko` varchar(50) NOT NULL,
  `footer_struk` varchar(255) NOT NULL,
  `pajak_persen` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_toko`, `alamat_toko`, `telp_toko`, `footer_struk`, `pajak_persen`, `updated_at`) VALUES
(1, 'Kopi Senja ', 'Jl. Teknologi No. 45, Jakarta Selatan', '0812-3456-7890', 'Terima Kasih! Password Wifi: kopienak', 12, '2025-12-11 10:41:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `kode_produk` varchar(20) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `tags` varchar(255) DEFAULT '',
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `kode_produk`, `nama_produk`, `kategori`, `tags`, `harga`, `stok`, `gambar`) VALUES
(1, 'K001', 'Kopi Susu Gula Aren', 'Minuman', 'kopi,ice,best seller', 18000, 88, 'menu_1765423797.png'),
(2, 'K002', 'Nasi Goreng Seafood', 'Makanan', '', 25000, 37, 'menu_1765424380.png'),
(4, 'K004', 'Ice Lemon Tea', 'Minuman', '', 10000, 86, 'menu_1765423873.png'),
(5, 'K001', ' Cappuccino Espresso', 'Minuman', 'kopi,hot,best seller', 18000, 45, 'menu_1765424448.png'),
(6, 'M001', 'Nasi Goreng Spesial', 'Makanan', '', 25000, 19, 'menu_1765424371.png'),
(7, 'S001', 'Kentang Goreng', 'Snack', '', 12000, 22, 'menu_1765424073.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `no_struk` varchar(50) DEFAULT NULL,
  `nama_pelanggan` varchar(50) NOT NULL DEFAULT 'Umum',
  `tipe_pesanan` enum('Dine In','Take Away') NOT NULL DEFAULT 'Dine In',
  `tgl_transaksi` datetime DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT 0,
  `pajak` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bayar` int(11) DEFAULT NULL,
  `kembali` int(11) DEFAULT NULL,
  `metode_pembayaran` enum('Tunai','QRIS') NOT NULL DEFAULT 'Tunai',
  `kasir_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `no_struk`, `nama_pelanggan`, `tipe_pesanan`, `tgl_transaksi`, `total_bayar`, `diskon`, `pajak`, `bayar`, `kembali`, `metode_pembayaran`, `kasir_id`) VALUES
(1, 'TRX-20251204052805', 'Umum', 'Dine In', '2025-12-04 05:28:05', 35000, 0, 0.00, 50000, 15000, 'Tunai', 1),
(2, 'INV-251204054316', 'Umum', 'Dine In', '2025-12-04 05:43:16', 65000, 0, 0.00, 100000, 35000, 'Tunai', 2),
(3, 'INV-251204055011', 'Umum', 'Dine In', '2025-12-04 05:50:11', 72000, 0, 0.00, 100000, 28000, 'Tunai', 2),
(4, 'INV-251204063803', 'Umum', 'Dine In', '2025-12-04 06:38:03', 50350, 2650, 0.00, 60000, 9650, 'Tunai', 2),
(5, 'INV-251204181105', 'Umum', 'Dine In', '2025-12-04 18:11:05', 81000, 9000, 0.00, 100000, 19000, 'Tunai', 2),
(6, 'INV-251211015821', 'Umum', 'Dine In', '2025-12-11 01:58:21', 18000, 0, 0.00, 20000, 2000, 'Tunai', 2),
(7, 'INV-251211020147', 'Umum', 'Dine In', '2025-12-11 02:01:47', 43000, 0, 0.00, 50000, 7000, 'Tunai', 2),
(8, 'INV-251211020515', 'Umum', 'Dine In', '2025-12-11 02:05:15', 71000, 0, 0.00, 100000, 29000, 'Tunai', 1),
(9, 'INV-251211020930', 'Umum', 'Dine In', '2025-12-11 02:09:30', 43000, 0, 0.00, 50000, 7000, 'Tunai', 1),
(10, 'INV-251211022031', 'Umum', 'Dine In', '2025-12-11 02:20:31', 10000, 0, 0.00, 20000, 10000, 'Tunai', 1),
(11, 'INV-251211023551', 'Umum', 'Dine In', '2025-12-11 02:35:51', 20000, 0, 0.00, 20000, 0, 'Tunai', 2),
(12, 'INV-251211085505', 'Umum', 'Dine In', '2025-12-11 08:55:05', 18000, 0, 0.00, 50000, 32000, 'Tunai', 1),
(13, 'INV-251211085733', 'Umum', 'Dine In', '2025-12-11 08:57:33', 20000, 0, 0.00, 20000, 0, 'Tunai', 2),
(14, 'INV-251211091615', 'Pelanggan Umum', 'Dine In', '2025-12-11 09:16:15', 22000, 0, 0.00, 25000, 3000, 'Tunai', 2),
(15, 'INV-251211091651', 'adi', 'Dine In', '2025-12-11 09:16:51', 25000, 0, 0.00, 30000, 5000, 'Tunai', 2),
(16, 'INV-251211093145', 'adi', 'Dine In', '2025-12-11 09:31:45', 24000, 0, 0.00, 24000, 0, 'Tunai', 2),
(17, 'INV-251211093417', 'adi', 'Dine In', '2025-12-11 09:34:17', 25000, 0, 0.00, 25000, 0, 'Tunai', 2),
(18, 'INV-251211094259', 'adi', 'Dine In', '2025-12-11 09:42:59', 67200, 0, 7200.00, 67200, 0, 'QRIS', 1),
(19, 'INV-251211094942', 'Umum', 'Dine In', '2025-12-11 09:49:42', 87360, 0, 9360.00, 87360, 0, 'Tunai', 1),
(20, 'INV-251211095730', 'Umum', 'Dine In', '2025-12-11 09:57:30', 60480, 0, 6480.00, 60480, 0, 'Tunai', 1),
(21, 'INV-251211100230', 'adi', 'Dine In', '2025-12-11 10:02:30', 26880, 0, 2880.00, 26880, 0, 'Tunai', 1),
(22, 'INV-251211102228', 'Umum', 'Dine In', '2025-12-11 10:22:28', 71680, 0, 7680.00, 100000, 28320, 'Tunai', 1),
(23, 'INV-251211102319', 'Umum', 'Dine In', '2025-12-11 10:23:19', 40320, 0, 4320.00, 50000, 9680, 'Tunai', 2),
(24, 'INV-251211104156', 'Ayu', 'Dine In', '2025-12-11 10:41:56', 72800, 0, 7800.00, 100000, 27200, 'Tunai', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `harga_saat_ini` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id`, `id_transaksi`, `id_produk`, `harga_saat_ini`, `qty`, `subtotal`) VALUES
(1, 1, 4, 10000, 1, 10000),
(2, 1, 2, 25000, 1, 25000),
(3, 2, 2, 25000, 1, 25000),
(4, 2, 1, 18000, 1, 18000),
(5, 2, 4, 10000, 1, 10000),
(6, 2, 3, 12000, 1, 12000),
(7, 3, 4, 10000, 1, 10000),
(8, 3, 3, 12000, 1, 12000),
(9, 3, 2, 25000, 2, 50000),
(10, 4, 1, 18000, 1, 18000),
(11, 4, 2, 25000, 1, 25000),
(12, 4, 4, 10000, 1, 10000),
(13, 5, 3, 12000, 1, 12000),
(14, 5, 2, 25000, 2, 50000),
(15, 5, 1, 18000, 1, 18000),
(16, 5, 4, 10000, 1, 10000),
(17, 6, 1, 18000, 1, 18000),
(18, 7, 1, 18000, 1, 18000),
(19, 7, 2, 25000, 1, 25000),
(20, 8, 5, 18000, 1, 18000),
(21, 8, 2, 25000, 1, 25000),
(22, 8, 1, 18000, 1, 18000),
(23, 8, 4, 10000, 1, 10000),
(24, 9, 2, 25000, 1, 25000),
(25, 9, 5, 18000, 1, 18000),
(26, 10, 4, 10000, 1, 10000),
(27, 11, 4, 10000, 2, 20000),
(28, 12, 1, 18000, 1, 18000),
(29, 13, 4, 10000, 2, 20000),
(30, 14, 3, 12000, 1, 12000),
(31, 14, 4, 10000, 1, 10000),
(32, 15, 2, 25000, 1, 25000),
(33, 16, 3, 12000, 1, 12000),
(34, 16, 7, 12000, 1, 12000),
(35, 17, 2, 25000, 1, 25000),
(36, 18, 3, 12000, 1, 12000),
(37, 18, 7, 12000, 1, 12000),
(38, 18, 1, 18000, 2, 36000),
(39, 19, 4, 10000, 1, 10000),
(40, 19, 2, 25000, 2, 50000),
(41, 19, 5, 18000, 1, 18000),
(42, 20, 7, 12000, 2, 24000),
(43, 20, 3, 12000, 1, 12000),
(44, 20, 1, 18000, 1, 18000),
(45, 21, 7, 12000, 1, 12000),
(46, 21, 3, 12000, 1, 12000),
(47, 22, 7, 12000, 2, 24000),
(48, 22, 1, 18000, 1, 18000),
(49, 22, 3, 12000, 1, 12000),
(50, 22, 4, 10000, 1, 10000),
(51, 23, 5, 18000, 2, 36000),
(52, 24, 4, 10000, 1, 10000),
(53, 24, 1, 18000, 1, 18000),
(54, 24, 7, 12000, 1, 12000),
(55, 24, 6, 25000, 1, 25000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','kasir') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Admin Kafe', 'admin', '$2y$10$m.axBhITNxA7UGXq.no74eywfIm8ZTz1FXhcr9owqJDgGfKhofiFK', 'admin'),
(2, 'Kasir 1', 'kasir', '$2y$10$3sjHkCEQF6axxbH/GI9CKuS1TGLV8wut9GAgrNCsMx87UBZz0b39G', 'kasir'),
(3, 'Juragan Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(4, 'Staff Kasir', 'kasir', 'c7911af3adbd12a035b289556d96470a', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
