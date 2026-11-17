-- ========================================================
-- SKEMA AWAL PRAKTIKUM 2 BASIS DATA KELAS XII
-- Studi Kasus: Nusantara Cargo Express
-- Gunakan skrip ini untuk menyiapkan basis data awal
-- sebelum siswa mengerjakan soal DML, WHERE, dan Agregasi.
-- ========================================================

CREATE DATABASE IF NOT EXISTS `db_nusantara_cargo`;
USE `db_nusantara_cargo`;

DROP TABLE IF EXISTS `tb_resi_pengiriman`;
DROP TABLE IF EXISTS `tb_armada_kargo`;
DROP TABLE IF EXISTS `tb_pelanggan_kargo`;

-- 1. Tabel Master Armada
CREATE TABLE `tb_armada_kargo` (
    `id_armada` VARCHAR(10) PRIMARY KEY,
    `no_polisi` VARCHAR(15) NOT NULL UNIQUE,
    `jenis_kendaraan` VARCHAR(30) NOT NULL,
    `kapasitas_kg` INT NOT NULL,
    `tarif_per_kg` DECIMAL(10,2) NOT NULL,
    `status_armada` ENUM('Tersedia', 'Operasional', 'Perawatan') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel Master Pelanggan Kargo
CREATE TABLE `tb_pelanggan_kargo` (
    `id_pengirim` VARCHAR(10) PRIMARY KEY,
    `no_ktp` VARCHAR(20) NOT NULL UNIQUE,
    `nama_pengirim` VARCHAR(100) NOT NULL,
    `no_telepon` VARCHAR(20) NOT NULL,
    `kota_asal` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Transaksi Resi Pengiriman
CREATE TABLE `tb_resi_pengiriman` (
    `no_resi` VARCHAR(15) PRIMARY KEY,
    `id_pengirim` VARCHAR(10) NOT NULL,
    `id_armada` VARCHAR(10) NOT NULL,
    `tgl_kirim` DATE NOT NULL,
    `berat_kg` DECIMAL(8,2) NOT NULL,
    `total_ongkir` DECIMAL(12,2) NOT NULL,
    `status_kirim` ENUM('Diproses', 'Dalam Perjalanan', 'Terkirim', 'Batal') DEFAULT 'Diproses',
    CONSTRAINT `fk_kargo_pengirim` FOREIGN KEY (`id_pengirim`) REFERENCES `tb_pelanggan_kargo` (`id_pengirim`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_kargo_armada` FOREIGN KEY (`id_armada`) REFERENCES `tb_armada_kargo` (`id_armada`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Injeksi Data Armada Kargo
INSERT INTO `tb_armada_kargo` (`id_armada`, `no_polisi`, `jenis_kendaraan`, `kapasitas_kg`, `tarif_per_kg`, `status_armada`) VALUES
('ARM-01', 'B 9101 KRG', 'Blind Van GranMax', 1000, 10000.00, 'Tersedia'),
('ARM-02', 'B 9202 KRG', 'Truk Engkel CDE', 2500, 8000.00, 'Operasional'),
('ARM-03', 'B 9303 KRG', 'Truk Double CDD', 5000, 7000.00, 'Tersedia'),
('ARM-04', 'D 8404 KRG', 'Truk Fuso Box', 10000, 6000.00, 'Operasional'),
('ARM-05', 'L 7505 KRG', 'Truk Tronton Wingbox', 20000, 5000.00, 'Tersedia'),
('ARM-06', 'B 9606 KRG', 'Blind Van BlindBox', 1200, 9500.00, 'Tersedia'),
('ARM-07', 'D 8707 KRG', 'Pickup L300 Box', 1500, 8500.00, 'Perawatan'),
('ARM-08', 'L 7808 KRG', 'Truk Trailer Kontainer', 30000, 4500.00, 'Tersedia');

-- Injeksi Data Pelanggan Kargo
INSERT INTO `tb_pelanggan_kargo` (`id_pengirim`, `no_ktp`, `nama_pengirim`, `no_telepon`, `kota_asal`) VALUES
('CUST-01', '3171012345670001', 'PT Sinar Logistik Abadi', '081112223334', 'Jakarta'),
('CUST-02', '3273023456780002', 'CV Bandung Tekstil Mandiri', '081223344556', 'Bandung'),
('CUST-03', '3374034567890003', 'Hendro Wicaksono Santoso', '081334455667', 'Semarang'),
('CUST-04', '3578045678900004', 'PT Surabaya Makmur Sentosa', '081556677889', 'Surabaya'),
('CUST-05', '3171056789010005', 'Dewi Lestari Nusantara', '081778899001', 'Jakarta');

-- Injeksi Data Transaksi Resi
INSERT INTO `tb_resi_pengiriman` (`no_resi`, `id_pengirim`, `id_armada`, `tgl_kirim`, `berat_kg`, `total_ongkir`, `status_kirim`) VALUES
('EXP-001', 'CUST-01', 'ARM-01', '2026-11-01', 45.00, 450000.00, 'Terkirim'),
('EXP-002', 'CUST-02', 'ARM-02', '2026-11-02', 320.00, 2560000.00, 'Dalam Perjalanan'),
('EXP-003', 'CUST-01', 'ARM-03', '2026-11-03', 750.00, 5250000.00, 'Terkirim'),
('EXP-004', 'CUST-03', 'ARM-01', '2026-11-04', 80.00, 800000.00, 'Terkirim'),
('EXP-005', 'CUST-04', 'ARM-04', '2026-11-05', 1850.00, 11100000.00, 'Dalam Perjalanan'),
('EXP-006', 'CUST-05', 'ARM-03', '2026-11-06', 400.00, 2800000.00, 'Terkirim'),
('EXP-007', 'CUST-02', 'ARM-05', '2026-11-07', 3500.00, 17500000.00, 'Terkirim'),
('EXP-008', 'CUST-03', 'ARM-07', '2026-11-08', 120.00, 1020000.00, 'Batal');
