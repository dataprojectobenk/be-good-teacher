-- Database: db_kopi_kasir
-- Tabel: tb_petugas

CREATE DATABASE IF NOT EXISTS `db_kopi_kasir`;
USE `db_kopi_kasir`;

DROP TABLE IF EXISTS `tb_petugas`;
CREATE TABLE `tb_petugas` (
    `id_petugas` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(100) NOT NULL,
    `nama_petugas` VARCHAR(100) NOT NULL,
    `shift` ENUM('Pagi', 'Sore', 'Malam') NOT NULL DEFAULT 'Pagi',
    `role` ENUM('Kasir', 'Barista', 'Supervisor') NOT NULL DEFAULT 'Kasir',
    `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
    `waktu_dibuat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Dummy Awal
-- Catatan kata sandi:
-- kasir01 : pass123 (status: 1 = Aktif)
-- barista01 : seduh456 (status: 1 = Aktif)
-- kasir_cuti : istirahat (status: 0 = Nonaktif/Cuti)

INSERT INTO `tb_petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `shift`, `role`, `status_aktif`, `waktu_dibuat`) VALUES
(1, 'kasir01', 'pass123', 'Ahmad Fauzi', 'Pagi', 'Kasir', 1, '2026-11-01 07:00:00'),
(2, 'barista01', 'seduh456', 'Dewi Lestari', 'Sore', 'Barista', 1, '2026-11-02 14:30:00'),
(3, 'kasir_cuti', 'istirahat', 'Rian Hidayat', 'Malam', 'Kasir', 0, '2026-11-03 21:00:00');
