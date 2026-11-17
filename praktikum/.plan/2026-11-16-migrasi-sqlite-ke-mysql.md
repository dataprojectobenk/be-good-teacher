# Rencana Implementasi: Migrasi Basis Data dari SQLite ke MySQL (MariaDB)

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:** `src/config/database.php`, `migrate_sqlite_to_mysql.php`, `index.php`  
**Tujuan Utama:** Mengatasi error `database is locked` secara permanen dengan memindahkan sistem basis data ke MySQL (InnoDB) dan memigrasikan seluruh data eksisting dari SQLite tanpa kehilangan data (*zero data loss*).

---

## 1. Analisis Kebutuhan & Lingkungan Sistem

1. **Akar Masalah SQLite**: File locking pada SQLite di lingkungan web concurrent (terutama di Windows/XAMPP) sangat rentan menyebabkan `SQLSTATE[HY000]: General error: 5 database is locked` ketika ada pembacaan dan penulisan simultan.
2. **Keunggulan MySQL (InnoDB)**:
   - Mendukung *row-level locking* dan konkurensi multi-user skala penuh.
   - Mengeliminasi secara permanen masalah file-locking database.
   - Layanan MySQL di XAMPP telah terdeteksi **Running** pada host `127.0.0.1:3306`, user `root`, tanpa password.
3. **Data Eksisting pada SQLite (`src/database.sqlite`)**:
   - Tabel `users`: **7 baris** (1 akun admin "Guru Penguji Lab" dan 6 akun siswa yang sudah terdaftar).
   - Tabel `soal`: **12 baris** paket praktikum lengkap (Kelas XI & XII).
   - Tabel `pengumpulan`: **0 baris** (data baru).
   Seluruh data pengguna dan soal wajib dipindahkan secara utuh dengan mempertahankan `id`, relasi, dan password hash `BCRYPT`.

---

## 2. Kebutuhan Sistem & Dependensi

- **Database Server**: MySQL 8.x / MariaDB bawaan XAMPP (`127.0.0.1:3306`).
- **Nama Basis Data Baru**: `db_lab_praktikum` (Charset: `utf8mb4`, Collation: `utf8mb4_unicode_ci`).
- **PHP Extension**: `pdo_mysql` (aktif bawaan PHP 8.2 XAMPP).
- **Kredensial**: Host: `127.0.0.1`, Port: `3306`, User: `root`, Password: `` (kosong).

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                      # Front Controller & Router
├── migrate_sqlite_to_mysql.php    # Skrip utilitas migrasi data (sekali pakai)
└── src/
    ├── config/
    │   └── database.php           # Konfigurasi PDO MySQL Singleton & Skema DDL MySQL
    ├── database.sqlite            # Berkas SQLite lama (diarsipkan sebagai backup cadangan)
    └── uploads/                   # Berkas fisik jawaban tugas siswa
```

### Skema Tabel MySQL (Engine: InnoDB)
1. **`users`**:
   - `id INT AUTO_INCREMENT PRIMARY KEY`
   - `nis VARCHAR(50) NOT NULL UNIQUE`
   - `nama VARCHAR(150) NOT NULL`
   - `kelas VARCHAR(50) NOT NULL`
   - `password VARCHAR(255) NOT NULL`
   - `role ENUM('siswa', 'admin') NOT NULL DEFAULT 'siswa'`
   - `registered_ip VARCHAR(50) DEFAULT NULL`
   - `registered_hostname VARCHAR(150) DEFAULT NULL`
   - `created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP`
2. **`soal`**:
   - `id INT AUTO_INCREMENT PRIMARY KEY`
   - `judul VARCHAR(255) NOT NULL`
   - `tingkat VARCHAR(10) NOT NULL`
   - `kategori VARCHAR(50) NOT NULL`
   - `sesi VARCHAR(50) NOT NULL`
   - `file_soal_path VARCHAR(255) NOT NULL`
   - `file_sql_path VARCHAR(255) DEFAULT NULL`
   - `tipe_pengumpulan VARCHAR(50) NOT NULL`
   - `is_active TINYINT(1) NOT NULL DEFAULT 0`
   - `created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP`
3. **`pengumpulan`**:
   - `id INT AUTO_INCREMENT PRIMARY KEY`
   - `user_id INT NOT NULL`
   - `soal_id INT NOT NULL`
   - `tipe_jawaban VARCHAR(50) NOT NULL`
   - `jawaban_konten TEXT NOT NULL`
   - `catatan_siswa TEXT DEFAULT NULL`
   - `waktu_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP`
   - `client_ip VARCHAR(50) NOT NULL`
   - `client_hostname VARCHAR(150) NOT NULL`
   - `client_pc_user VARCHAR(150) DEFAULT NULL`
   - `user_agent TEXT DEFAULT NULL`
   - `nilai INT DEFAULT NULL`
   - `feedback_guru TEXT DEFAULT NULL`
   - `CONSTRAINT fk_pengumpulan_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE`
   - `CONSTRAINT fk_pengumpulan_soal FOREIGN KEY (soal_id) REFERENCES soal(id) ON DELETE CASCADE`

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Pembuatan Basis Data & Skema MySQL**
  - Membuat basis data `db_lab_praktikum` di server MySQL lokal.
  - Membuat struktur tabel `users`, `soal`, dan `pengumpulan` dengan engine InnoDB dan relasi foreign key.
- [x] **Fase 2: Migrasi Data Utuh dari SQLite ke MySQL**
  - Membuat skrip `migrate_sqlite_to_mysql.php`.
  - Membaca 7 record `users` dari SQLite dan memasukkannya ke MySQL (mempertahankan `id`, password hash, timestamp).
  - Membaca 12 record `soal` dari SQLite dan memasukkannya ke MySQL.
  - Memindahkan data `pengumpulan` jika terdapat entri baru.
  - Menjalankan skrip via CLI dan memverifikasi integritas data (*record count match*).
- [x] **Fase 3: Refaktorisasi `src/config/database.php` ke MySQL PDO**
  - Memperbarui fungsi `getDatabaseConnection()` agar menggunakan driver `mysql:host=127.0.0.1;dbname=db_lab_praktikum;charset=utf8mb4`.
  - Menerapkan arsitektur Singleton (`static $pdo = null;`) untuk efisiensi koneksi.
  - Mengadaptasi `initDatabaseTables()` untuk DDL MySQL.
- [x] **Fase 4: Verifikasi & Pengujian Fungsional Sistem**
  - Uji registrasi siswa baru melalui form registrasi (memastikan tidak ada error locking).
  - Uji autentikasi login menggunakan akun siswa eksisting hasil migrasi (contoh: NIS `242507009`) dan akun `admin`.
  - Uji pembukaan dashboard siswa dan dashboard guru.
  - Hapus skrip migrasi sementara demi keamanan setelah seluruh pengujian sukses.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Kehilangan Data Akun Siswa** | Siswa yang sudah terdaftar tidak dapat login | Migrasi membaca langsung dari `src/database.sqlite` dan mempertahankan seluruh field `password` BCRYPT tanpa modifikasi hash. |
| **Gagal Relasi Foreign Key** | Error SQL saat import data pengumpulan | Proses import dilakukan secara berurutan: `users` dan `soal` diselesaikan terlebih dahulu sebelum `pengumpulan`. |
| **Inkompatibilitas Query SQL** | Tampilan error di aplikasi | Seluruh query di `index.php`, `admin_dashboard.php`, dan `siswa_dashboard.php` telah ditinjau dan terbukti 100% merupakan standar ANSI SQL. |
