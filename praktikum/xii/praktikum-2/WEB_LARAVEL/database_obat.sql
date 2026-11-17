-- Database: db_apotek_katalog
-- Tabel: tb_obat (Single Table, Tanpa Relasi)

CREATE DATABASE IF NOT EXISTS `db_apotek_katalog`;
USE `db_apotek_katalog`;

DROP TABLE IF EXISTS `tb_obat`;
CREATE TABLE `tb_obat` (
    `id_obat` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_obat` VARCHAR(20) NOT NULL UNIQUE,
    `nama_obat` VARCHAR(100) NOT NULL,
    `kategori` ENUM('Obat Bebas', 'Obat Bebas Terbatas', 'Obat Keras') NOT NULL,
    `bentuk_sediaan` ENUM('Tablet', 'Kapsul', 'Sirup', 'Salep') NOT NULL,
    `stok` INT NOT NULL,
    `harga` INT NOT NULL,
    `status_promo` ENUM('Ya', 'Tidak') NOT NULL DEFAULT 'Tidak',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Dummy Awal
INSERT INTO `tb_obat` (`id_obat`, `kode_obat`, `nama_obat`, `kategori`, `bentuk_sediaan`, `stok`, `harga`, `status_promo`) VALUES
(1, 'OBT-001', 'Paracetamol 500mg Strip', 'Obat Bebas', 'Tablet', 150, 8000, 'Ya'),
(2, 'OBT-002', 'Amoxicillin 500mg Kaplet', 'Obat Keras', 'Kapsul', 85, 18000, 'Tidak'),
(3, 'OBT-003', 'Cetirizine 10mg Strip', 'Obat Bebas Terbatas', 'Tablet', 60, 12000, 'Ya'),
(4, 'OBT-004', 'OBH Herbal Batuk Dewasa 100ml', 'Obat Bebas', 'Sirup', 40, 22000, 'Tidak'),
(5, 'OBT-005', 'Hydrocortisone Salep 5g', 'Obat Bebas Terbatas', 'Salep', 30, 15000, 'Tidak');
