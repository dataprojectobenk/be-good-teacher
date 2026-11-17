-- ========================================================
-- SKEMA AWAL PRAKTIKUM 2 BASIS DATA
-- Studi Kasus: TransJava Rent Car
-- Gunakan skrip ini untuk menyiapkan basis data awal
-- sebelum siswa mengerjakan soal DML, WHERE, dan Agregasi.
-- ========================================================

CREATE DATABASE IF NOT EXISTS `db_transjava_rent`;
USE `db_transjava_rent`;

-- Hapus tabel anak terlebih dahulu untuk menjaga integritas foreign key
DROP TABLE IF EXISTS `tb_transaksi_sewa`;
DROP TABLE IF EXISTS `tb_mobil`;
DROP TABLE IF EXISTS `tb_pelanggan_rental`;

-- 1. Tabel Master Mobil
CREATE TABLE `tb_mobil` (
    `id_mobil` VARCHAR(10) PRIMARY KEY,
    `no_polisi` VARCHAR(15) NOT NULL UNIQUE,
    `merk` VARCHAR(50) NOT NULL,
    `tipe` VARCHAR(30) NOT NULL,
    `tahun_buat` INT NOT NULL,
    `tarif_harian` DECIMAL(12,2) NOT NULL,
    `status_mobil` ENUM('Tersedia', 'Disewa', 'Perawatan') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel Master Pelanggan
CREATE TABLE `tb_pelanggan_rental` (
    `id_penyewa` VARCHAR(10) PRIMARY KEY,
    `no_ktp` VARCHAR(20) NOT NULL UNIQUE,
    `nama_penyewa` VARCHAR(100) NOT NULL,
    `no_telepon` VARCHAR(20) NOT NULL,
    `alamat` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Transaksi Sewa
CREATE TABLE `tb_transaksi_sewa` (
    `no_transaksi` VARCHAR(15) PRIMARY KEY,
    `id_penyewa` VARCHAR(10) NOT NULL,
    `id_mobil` VARCHAR(10) NOT NULL,
    `tgl_sewa` DATE NOT NULL,
    `tgl_kembali` DATE NOT NULL,
    `lama_hari` INT NOT NULL,
    `total_bayar` DECIMAL(12,2) NOT NULL,
    `status_transaksi` ENUM('Berjalan', 'Selesai', 'Batal') DEFAULT 'Berjalan',
    CONSTRAINT `fk_sewa_pelanggan` FOREIGN KEY (`id_penyewa`) REFERENCES `tb_pelanggan_rental` (`id_penyewa`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_sewa_mobil` FOREIGN KEY (`id_mobil`) REFERENCES `tb_mobil` (`id_mobil`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Injeksi Data Master Mobil
INSERT INTO `tb_mobil` (`id_mobil`, `no_polisi`, `merk`, `tipe`, `tahun_buat`, `tarif_harian`, `status_mobil`) VALUES
('M01', 'B 1234 ABC', 'Toyota Avanza', 'MPV', 2021, 350000, 'Tersedia'),
('M02', 'B 5678 DEF', 'Mitsubishi Xpander', 'MPV', 2023, 400000, 'Disewa'),
('M03', 'B 9012 GHI', 'Honda Brio Satya', 'City Car', 2020, 300000, 'Tersedia'),
('M04', 'B 3456 JKL', 'Toyota Innova Reborn', 'Premium MPV', 2022, 600000, 'Disewa'),
('M05', 'B 7890 MNO', 'Toyota Fortuner GR', 'SUV', 2024, 950000, 'Disewa'),
('M06', 'D 1122 XYZ', 'Honda HR-V', 'SUV', 2023, 550000, 'Tersedia'),
('M07', 'D 3344 KLM', 'Daihatsu Sigra', 'City Car', 2019, 250000, 'Perawatan'),
('M08', 'AB 5566 JQ', 'Toyota HiAce Commuter', 'Minibus', 2022, 1100000, 'Tersedia');

-- Injeksi Data Master Pelanggan
INSERT INTO `tb_pelanggan_rental` (`id_penyewa`, `no_ktp`, `nama_penyewa`, `no_telepon`, `alamat`) VALUES
('P01', '3201123456780001', 'Bambang Prasetyo', '081211112222', 'Jl. Sudirman No. 12, Jakarta'),
('P02', '3201987654320002', 'Ratna Sari Dewi', '081333334444', 'Jl. Gatot Subroto No. 45, Bandung'),
('P03', '3201555566660003', 'Hendra Gunawan', '085677778888', 'Jl. Pemuda No. 88, Semarang'),
('P04', '3201444433330004', 'Maya Indah Permata', '087899990000', 'Jl. Malioboro No. 05, Yogyakarta'),
('P05', '3201777788880005', 'Agus Setiawan', '081988887777', 'Jl. Diponegoro No. 20, Surabaya');

-- Injeksi Data Transaksi Sewa
INSERT INTO `tb_transaksi_sewa` (`no_transaksi`, `id_penyewa`, `id_mobil`, `tgl_sewa`, `tgl_kembali`, `lama_hari`, `total_bayar`, `status_transaksi`) VALUES
('TRX001', 'P01', 'M01', '2026-11-01', '2026-11-03', 2, 700000, 'Selesai'),
('TRX002', 'P02', 'M02', '2026-11-02', '2026-11-05', 3, 1200000, 'Berjalan'),
('TRX003', 'P01', 'M04', '2026-11-04', '2026-11-07', 3, 1800000, 'Selesai'),
('TRX004', 'P03', 'M03', '2026-11-05', '2026-11-06', 1, 300000, 'Selesai'),
('TRX005', 'P04', 'M05', '2026-11-07', '2026-11-10', 3, 2850000, 'Berjalan'),
('TRX006', 'P05', 'M04', '2026-11-08', '2026-11-10', 2, 1200000, 'Berjalan'),
('TRX007', 'P02', 'M08', '2026-11-09', '2026-11-11', 2, 2200000, 'Selesai'),
('TRX008', 'P03', 'M07', '2026-11-10', '2026-11-11', 1, 250000, 'Batal');
