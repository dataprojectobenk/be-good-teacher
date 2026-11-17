-- Database: db_kopi_katalog
-- Tabel: tb_menu (Single Table, Tanpa Relasi)

CREATE DATABASE IF NOT EXISTS `db_kopi_katalog`;
USE `db_kopi_katalog`;

DROP TABLE IF EXISTS `tb_menu`;
CREATE TABLE `tb_menu` (
    `id_menu` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_menu` VARCHAR(20) NOT NULL UNIQUE,
    `nama_menu` VARCHAR(100) NOT NULL,
    `kategori` ENUM('Kopi', 'Non-Kopi', 'Makanan Ringan') NOT NULL,
    `harga` INT NOT NULL,
    `status_promo` ENUM('Ya', 'Tidak') NOT NULL DEFAULT 'Tidak',
    `terakhir_diperbarui` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Dummy Awal
INSERT INTO `tb_menu` (`id_menu`, `kode_menu`, `nama_menu`, `kategori`, `harga`, `status_promo`) VALUES
(1, 'CF-001', 'Espresso Single Origin Gayo', 'Kopi', 25000, 'Ya'),
(2, 'NC-002', 'Matcha Latte Signature', 'Non-Kopi', 28000, 'Tidak'),
(3, 'CF-003', 'Caramel Macchiato Ice', 'Kopi', 32000, 'Ya'),
(4, 'SN-004', 'Croissant Butter Almond', 'Makanan Ringan', 24000, 'Tidak'),
(5, 'NC-005', 'Earl Grey Milk Tea', 'Non-Kopi', 26000, 'Tidak');
