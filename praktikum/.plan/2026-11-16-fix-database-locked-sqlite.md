# Rencana Implementasi: Perbaikan Masalah "General error: 5 database is locked" (SQLite)

**Tanggal:** 2026-11-16  
**Status:** Menunggu Persetujuan Pengguna  
**Target File:** `src/config/database.php`, `index.php`  
**Error Sumber:** `Fatal error: Uncaught PDOException: SQLSTATE[HY000]: General error: 5 database is locked in C:\xampp\htdocs\index.php:58`

---

## 1. Analisis Akar Masalah (Root Cause Analysis)

Berdasarkan pesan error pada `index.php:58` (`$stmtInsert->execute(...)`) dan analisis arsitektur aplikasi:
1. **Tidak Ada `busy_timeout`**: Driver PDO SQLite di PHP secara default memiliki `busy_timeout = 0`. Jika berkas basis data sedang dikunci (bahkan selama 1 milidetik), SQLite langsung melempar eksepsi `SQLSTATE[HY000]: General error: 5 database is locked` tanpa menunggu antrean selesai.
2. **Journal Mode Masih Standar (`DELETE`)**: SQLite beroperasi pada mode rollback journal klasik. Pada mode ini, kunci pembacaan (*shared read lock*) memblokir operasi penulisan (*exclusive write lock*), dan sebaliknya.
3. **Cursor Statement Tidak Ditutup (`closeCursor`)**: Pada alur registrasi (`index.php:43-58`):
   ```php
   $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM users WHERE nis = :nis");
   $stmtCek->execute([':nis' => $nis]);
   if ($stmtCek->fetchColumn() > 0) { ... }
   // $stmtCek cursor masih terbuka!
   $stmtInsert = $pdo->prepare("INSERT INTO users ...");
   $stmtInsert->execute([...]); // Error meledak di baris ini
   ```
   Eksekusi `fetchColumn()` tidak menutup cursor pembacaan secara otomatis pada PDO SQLite. Hal ini menyebabkan koneksi mempertahankan *shared lock* saat `$stmtInsert->execute()` berupaya mempromosikan kunci menjadi *reserved/exclusive lock*.
4. **Instansiasi Multi-Koneksi (Ketiadaan Singleton)**: `getDatabaseConnection()` dipanggil berulang kali dalam satu siklus request (misalnya sekali di `index.php`, dan dipanggil kembali di dalam `src/views/siswa_dashboard.php` / `src/views/admin_dashboard.php`). Setiap pemanggilan membuat objek `new PDO(...)` baru yang bersaing mengunci berkas yang sama.
5. **Eksekusi DDL & Seeding Berulang pada Setiap Request**: Fungsi `initDatabaseTables($pdo)` dieksekusi setiap kali `getDatabaseConnection()` dipanggil. Ini menjalankan operasi `CREATE TABLE`, `ALTER TABLE`, dan iterasi 12 kali `INSERT OR REPLACE INTO soal` pada setiap HTTP request, menimbulkan *lock storm* ketika terjadi request paralel (misalnya aset halaman atau akses multi-pengguna).

---

## 2. Tujuan & Ruang Lingkup Perbaikan

- Menerapkan konfigurasi `PRAGMA` performa tinggi pada SQLite di `src/config/database.php`:
  - `PRAGMA busy_timeout = 5000;` (memberikan toleransi antrean 5 detik sebelum melempar exception).
  - `PRAGMA journal_mode = WAL;` (Write-Ahead Logging, memungkinkan pembaca dan penulis bekerja bersamaan tanpa saling memblokir).
  - `PRAGMA synchronous = NORMAL;` (mengoptimalkan throughput penulisan pada mode WAL).
  - `PRAGMA foreign_keys = ON;` (menegakkan integritas referensial).
- Menerapkan arsitektur **Singleton Pattern** pada `getDatabaseConnection()` agar koneksi PDO dapat digunakan kembali (*reused*) dalam satu request lifecycle.
- Menambahkan pemanggilan `$stmt->closeCursor()` dan membersihkan variabel statement pada seluruh operasi query di `index.php` sebelum menjalankan perintah `INSERT` / `UPDATE`.
- Mengoptimalkan `initDatabaseTables()` agar tidak menjalankan DDL & loop 12 *upsert* pada setiap HTTP request jika skema tabel sudah siap.

---

## 3. Kebutuhan Sistem & Dependensi

- **Lingkungan**: XAMPP (Apache + PHP 8.2+).
- **Ekstensi**: `pdo_sqlite` (bawaan PHP XAMPP).
- **Izin Direktori**: Direktori `C:\xampp\htdocs\src\` harus memiliki hak akses baca-tulis (read/write) agar SQLite dapat membuat berkas `-wal` dan `-shm`.

---

## 4. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                  # Ditambahkan $stmt->closeCursor() & free statement
└── src/
    ├── config/
    │   └── database.php       # Singleton PDO, PRAGMA WAL, busy_timeout, optimasi init
    ├── database.sqlite        # Berkas basis data utama
    ├── database.sqlite-wal    # (Otomatis dibuat oleh SQLite WAL)
    └── database.sqlite-shm    # (Otomatis dibuat oleh SQLite WAL)
```

---

## 5. Tahapan Eksekusi Bertahap (Checklist)

- [ ] **Fase 1: Konfigurasi SQLite PRAGMA & Singleton Pattern**
  - Ubah `getDatabaseConnection()` di `src/config/database.php` menggunakan `static $pdo = null;`.
  - Eksekusi `PRAGMA journal_mode = WAL;`, `PRAGMA busy_timeout = 5000;`, dan `PRAGMA synchronous = NORMAL;`.
- [ ] **Fase 2: Optimasi Inisialisasi Database (`initDatabaseTables`)**
  - Berikan pengaman agar proses pembuatan tabel dan pengisian paket soal hanya berjalan jika tabel `users` / `soal` belum ada atau flag migrasi belum diset.
- [ ] **Fase 3: Penambahan `closeCursor()` pada `index.php`**
  - Baris 45: Tambahkan `$stmtCek->closeCursor(); unset($stmtCek);` sebelum query registrasi.
  - Baris 85: Tambahkan `$stmtUser->closeCursor();` pada login.
  - Baris 117 & 171: Tambahkan `$stmtSoal->closeCursor();` dan `$stmtCekSub->closeCursor();` sebelum operasi submit jawaban.
  - Baris 257 & 284: Tambahkan `$stmt->closeCursor();` pada aksi download.
- [ ] **Fase 4: Verifikasi & Pengujian Fungsional**
  - Uji pendaftaran siswa baru (registrasi) melalui simulasi POST.
  - Pastikan tidak ada berkas `.sqlite-journal` yang terkunci.
  - Verifikasi pembentukan file WAL dan eksekusi query concurrent tanpa exception.

---

## 6. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Izin Tulis Berkas WAL** | Apache tidak dapat membuat file `.sqlite-wal` | Memastikan folder `src/` memiliki permission tulis untuk user web server di Windows. |
| **Data Soal Terlewat** | Data soal baru tidak masuk jika init dimatikan sepenuhnya | Cek jumlah data soal di tabel `soal`; hanya lakukan *seeding* jika tabel kosong (`COUNT(*) === 0`). |
| **Rollback Journal Lama Tertinggal** | File `.sqlite-journal` lama menghalangi transisi ke WAL | Hapus atau bersihkan berkas rollback lama saat transisi awal ke mode WAL jika tidak ada transaksi aktif. |
