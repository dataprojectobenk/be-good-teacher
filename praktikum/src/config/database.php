<?php
// praktikum/src/config/database.php
// Konfigurasi Koneksi Basis Data MySQL (MariaDB) - Lab Classroom Praktikum RPL

function getDatabaseConnection(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $uploadsDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }

    $host = '127.0.0.1';
    $port = '3306';
    $db   = 'db_lab_praktikum';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    try {
        // Coba koneksi langsung ke basis data target
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Jika basis data belum ada, buat otomatis melalui koneksi server
            $serverDsn = "mysql:host={$host};port={$port};charset={$charset}";
            $serverPdo = new PDO($serverDsn, $user, $pass, $options);
            $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            unset($serverPdo);

            $pdo = new PDO($dsn, $user, $pass, $options);
        }

        initDatabaseTables($pdo);

        return $pdo;
    } catch (PDOException $e) {
        die("Koneksi basis data MySQL gagal: " . htmlspecialchars($e->getMessage()));
    }
}

function initDatabaseTables(PDO $pdo): void {
    static $initialized = false;
    if ($initialized) {
        return;
    }

    // 1. Tabel Pengguna (users)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `nis` VARCHAR(50) NOT NULL UNIQUE,
            `nama` VARCHAR(150) NOT NULL,
            `kelas` VARCHAR(50) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('siswa', 'admin') NOT NULL DEFAULT 'siswa',
            `registered_ip` VARCHAR(50) DEFAULT NULL,
            `registered_hostname` VARCHAR(150) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Tabel Soal Praktikum (soal)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `soal` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `judul` VARCHAR(255) NOT NULL,
            `tingkat` VARCHAR(10) NOT NULL,
            `kategori` VARCHAR(50) NOT NULL,
            `sesi` VARCHAR(50) NOT NULL,
            `file_soal_path` VARCHAR(255) NOT NULL,
            `file_sql_path` VARCHAR(255) DEFAULT NULL,
            `tipe_pengumpulan` VARCHAR(50) NOT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 3. Tabel Pengumpulan Tugas (pengumpulan)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `pengumpulan` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `soal_id` INT NOT NULL,
            `tipe_jawaban` VARCHAR(50) NOT NULL,
            `jawaban_konten` TEXT NOT NULL,
            `catatan_siswa` TEXT DEFAULT NULL,
            `waktu_kirim` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `client_ip` VARCHAR(50) NOT NULL,
            `client_hostname` VARCHAR(150) NOT NULL,
            `client_pc_user` VARCHAR(150) DEFAULT NULL,
            `user_agent` TEXT DEFAULT NULL,
            `nilai` INT DEFAULT NULL,
            `feedback_guru` TEXT DEFAULT NULL,
            CONSTRAINT `fk_pengumpulan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_pengumpulan_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Seeding Admin Default jika belum ada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `role` = 'admin'");
    $stmt->execute();
    $adminCount = (int)$stmt->fetchColumn();
    $stmt->closeCursor();

    if ($adminCount === 0) {
        $insertAdmin = $pdo->prepare("
            INSERT INTO `users` (`nis`, `nama`, `kelas`, `password`, `role`, `registered_ip`, `registered_hostname`) 
            VALUES (:nis, :nama, :kelas, :password, :role, '127.0.0.1', 'SERVER-LAB')
        ");
        $insertAdmin->execute([
            ':nis' => 'admin',
            ':nama' => 'Guru Penguji Lab',
            ':kelas' => 'Pengajar',
            ':password' => password_hash('admin123', PASSWORD_BCRYPT),
            ':role' => 'admin'
        ]);
        $insertAdmin->closeCursor();
    }

    // Daftar Paket Soal Lengkap: Kelas XI & Kelas XII
    $soalList = [
        // === KELAS XI ===
        [
            'id' => 1,
            'judul' => 'Praktikum 1 - Web PHP: Autentikasi Member & Proteksi Dashboard',
            'tingkat' => 'XI',
            'kategori' => 'Web (PHP)',
            'sesi' => 'Praktikum 1',
            'file_soal_path' => 'xi/praktikum-1/WEB_PHP/SOAL_PRAKTIKUM_1_WEB.md',
            'file_sql_path' => 'xi/praktikum-1/WEB_PHP/database_pelanggan.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 1
        ],
        [
            'id' => 2,
            'judul' => 'Praktikum 1 - Desktop C# WinForms: Registrasi & Login Petugas Loket',
            'tingkat' => 'XI',
            'kategori' => 'Desktop (C#)',
            'sesi' => 'Praktikum 1',
            'file_soal_path' => 'xi/praktikum-1/DESKTOP_CSHARP/SOAL_PRAKTIKUM_1_DESKTOP.md',
            'file_sql_path' => 'xi/praktikum-1/DESKTOP_CSHARP/database_petugas.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 1
        ],
        [
            'id' => 3,
            'judul' => 'Praktikum 1 - Basis Data SQL: DDL, Foreign Key & Insert Rental Mobil',
            'tingkat' => 'XI',
            'kategori' => 'Basis Data',
            'sesi' => 'Praktikum 1',
            'file_soal_path' => 'xi/praktikum-1/DATABASE/SOAL_PRAKTIKUM_1_DATABASE.md',
            'file_sql_path' => null,
            'tipe_pengumpulan' => 'file_sql',
            'is_active' => 1
        ],
        [
            'id' => 4,
            'judul' => 'Praktikum 2 - Web PHP: Pengelolaan Menu Kafe (CRUD 1 Tabel)',
            'tingkat' => 'XI',
            'kategori' => 'Web (PHP)',
            'sesi' => 'Praktikum 2',
            'file_soal_path' => 'xi/praktikum-2/WEB_PHP/SOAL_PRAKTIKUM_2_WEB.md',
            'file_sql_path' => 'xi/praktikum-2/WEB_PHP/database_menu.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 0
        ],
        [
            'id' => 5,
            'judul' => 'Praktikum 2 - Desktop C# WinForms: Pengelolaan Stok Gudang (CRUD 1 Tabel)',
            'tingkat' => 'XI',
            'kategori' => 'Desktop (C#)',
            'sesi' => 'Praktikum 2',
            'file_soal_path' => 'xi/praktikum-2/DESKTOP_CSHARP/SOAL_PRAKTIKUM_2_DESKTOP.md',
            'file_sql_path' => 'xi/praktikum-2/DESKTOP_CSHARP/database_bahan_baku.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 0
        ],
        [
            'id' => 6,
            'judul' => 'Praktikum 2 - Basis Data SQL: DML Bersyarat, WHERE Lanjutan & Agregasi',
            'tingkat' => 'XI',
            'kategori' => 'Basis Data',
            'sesi' => 'Praktikum 2',
            'file_soal_path' => 'xi/praktikum-2/DATABASE/SOAL_PRAKTIKUM_2_DATABASE.md',
            'file_sql_path' => 'xi/praktikum-2/DATABASE/skema_awal_praktikum_2.sql',
            'tipe_pengumpulan' => 'file_sql',
            'is_active' => 0
        ],

        // === KELAS XII ===
        [
            'id' => 7,
            'judul' => 'Praktikum 1 - Web Laravel: Autentikasi Pasien & Proteksi Route',
            'tingkat' => 'XII',
            'kategori' => 'Web (Laravel)',
            'sesi' => 'Praktikum 1',
            'file_soal_path' => 'xii/praktikum-1/WEB_LARAVEL/SOAL_PRAKTIKUM_1_LARAVEL_XII.md',
            'file_sql_path' => 'xii/praktikum-1/WEB_LARAVEL/database_pasien.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 1
        ],
        [
            'id' => 8,
            'judul' => 'Praktikum 1 - Desktop C# WinForms: Registrasi & Login Petugas Apotek',
            'tingkat' => 'XII',
            'kategori' => 'Desktop (C#)',
            'sesi' => 'Praktikum 1',
            'file_soal_path' => 'xii/praktikum-1/DESKTOP_CSHARP/SOAL_PRAKTIKUM_1_DESKTOP_XII.md',
            'file_sql_path' => 'xii/praktikum-1/DESKTOP_CSHARP/database_apoteker.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 1
        ],
        [
            'id' => 9,
            'judul' => 'Praktikum 1 - Basis Data SQL: DDL, Foreign Key & Insert Nusantara Cargo',
            'tingkat' => 'XII',
            'kategori' => 'Basis Data',
            'sesi' => 'Praktikum 1',
            'file_soal_path' => 'xii/praktikum-1/DATABASE/SOAL_PRAKTIKUM_1_DATABASE_XII.md',
            'file_sql_path' => null,
            'tipe_pengumpulan' => 'file_sql',
            'is_active' => 1
        ],
        [
            'id' => 10,
            'judul' => 'Praktikum 2 - Web Laravel: Pengelolaan Katalog Obat (CRUD 1 Tabel)',
            'tingkat' => 'XII',
            'kategori' => 'Web (Laravel)',
            'sesi' => 'Praktikum 2',
            'file_soal_path' => 'xii/praktikum-2/WEB_LARAVEL/SOAL_PRAKTIKUM_2_LARAVEL_XII.md',
            'file_sql_path' => 'xii/praktikum-2/WEB_LARAVEL/database_obat.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 0
        ],
        [
            'id' => 11,
            'judul' => 'Praktikum 2 - Desktop C# WinForms: Pengelolaan Stok Suplemen & Alkes (CRUD 1 Tabel)',
            'tingkat' => 'XII',
            'kategori' => 'Desktop (C#)',
            'sesi' => 'Praktikum 2',
            'file_soal_path' => 'xii/praktikum-2/DESKTOP_CSHARP/SOAL_PRAKTIKUM_2_DESKTOP_XII.md',
            'file_sql_path' => 'xii/praktikum-2/DESKTOP_CSHARP/database_suplemen.sql',
            'tipe_pengumpulan' => 'github_link',
            'is_active' => 0
        ],
        [
            'id' => 12,
            'judul' => 'Praktikum 2 - Basis Data SQL: DML Bersyarat, WHERE Lanjutan & Agregasi Kargo',
            'tingkat' => 'XII',
            'kategori' => 'Basis Data',
            'sesi' => 'Praktikum 2',
            'file_soal_path' => 'xii/praktikum-2/DATABASE/SOAL_PRAKTIKUM_2_DATABASE_XII.md',
            'file_sql_path' => 'xii/praktikum-2/DATABASE/skema_awal_praktikum_2_xii.sql',
            'tipe_pengumpulan' => 'file_sql',
            'is_active' => 0
        ]
    ];

    // Cek apakah data soal sudah diisi
    $stmtCheckSoal = $pdo->query("SELECT COUNT(*) FROM `soal`");
    $existingSoalCount = (int)$stmtCheckSoal->fetchColumn();
    $stmtCheckSoal->closeCursor();

    if ($existingSoalCount < count($soalList)) {
        $upsertSoal = $pdo->prepare("
            INSERT INTO `soal` (`id`, `judul`, `tingkat`, `kategori`, `sesi`, `file_soal_path`, `file_sql_path`, `tipe_pengumpulan`, `is_active`)
            VALUES (:id, :judul, :tingkat, :kategori, :sesi, :file_soal_path, :file_sql_path, :tipe_pengumpulan, :is_active)
            ON DUPLICATE KEY UPDATE
                `judul` = VALUES(`judul`),
                `tingkat` = VALUES(`tingkat`),
                `kategori` = VALUES(`kategori`),
                `sesi` = VALUES(`sesi`),
                `file_soal_path` = VALUES(`file_soal_path`),
                `file_sql_path` = VALUES(`file_sql_path`),
                `tipe_pengumpulan` = VALUES(`tipe_pengumpulan`)
        ");

        foreach ($soalList as $s) {
            $upsertSoal->execute($s);
        }
        $upsertSoal->closeCursor();
    }

    $initialized = true;
}
