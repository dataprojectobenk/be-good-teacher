-- Database: db_kopi_gudang
-- Tabel: tb_bahan_baku (Single Table, Tanpa Relasi)

CREATE DATABASE IF NOT EXISTS `db_kopi_gudang`;
USE `db_kopi_gudang`;

DROP TABLE IF EXISTS `tb_bahan_baku`;
CREATE TABLE `tb_bahan_baku` (
    `id_bahan` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_bahan` VARCHAR(20) NOT NULL UNIQUE,
    `nama_bahan` VARCHAR(100) NOT NULL,
    `kategori` ENUM('Biji Kopi', 'Susu & Dairy', 'Sirup & Pemanis', 'Kemasan') NOT NULL,
    `stok` INT NOT NULL,
    `satuan` VARCHAR(20) NOT NULL,
    `harga_satuan` INT NOT NULL,
    `batas_minimum` INT NOT NULL,
    `terakhir_update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Dummy Awal
INSERT INTO `tb_bahan_baku` (`id_bahan`, `kode_bahan`, `nama_bahan`, `kategori`, `stok`, `satuan`, `harga_satuan`, `batas_minimum`) VALUES
(1, 'BB-001', 'Biji Kopi Arabika Gayo Green Beans', 'Biji Kopi', 15, 'Kg', 120000, 10),
(2, 'BB-002', 'Susu Segar Pasteurisasi Fresh Milk', 'Susu & Dairy', 8, 'Liter', 22000, 12),
(3, 'BB-003', 'Sirup Karamel Premium 750ml', 'Sirup & Pemanis', 4, 'Botol', 85000, 5),
(4, 'BB-004', 'Paper Cup Hot 8oz Double Wall', 'Kemasan', 250, 'Pcs', 1200, 100),
(5, 'BB-005', 'Biji Kopi Robusta Temanggung', 'Biji Kopi', 25, 'Kg', 75000, 15);
