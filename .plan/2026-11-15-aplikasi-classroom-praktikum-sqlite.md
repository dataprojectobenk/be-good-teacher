# RENCANA PELAKSANAAN: APLIKASI CLASSROOM PRAKTIKUM LOKAL (PHP NATIVE & SQLITE) - SELESAI (REVISI 2)

**Tanggal:** 2026-11-15  
**Target Pengguna:** Siswa dan Guru SMK Kelas XI / XII di Laboratorium Komputer (Jaringan LAN Lokal)  
**Lingkungan Teknis:** PHP Native (PDO SQLite), Berkas Basis Data Mandiri SQLite (`src/database.sqlite`), Web Server Lokal (`run_server.bat` -> `php -S 0.0.0.0:8080`).  
**Status Dokumen:** `Selesai Diimplementasikan (Completed)`

---

## 1. PEMBAHARUAN FITUR UTAMA (REVISI 2)

### 1.1 Halaman Default / Landing Page Utama: Prosedur Pembersihan Akun
- Setiap kali pengguna mengakses alamat server web lab (`index.php` tanpa parameter), sistem secara otomatis membuka **Halaman Petunjuk Pembersihan Akun Komputer Lab (`petunjuk_lab.php`)**.
- Halaman ini bertindak sebagai gerbang onboarding awal di mana siswa diwajibkan membaca dan mempraktikkan 3 prosedur kritis:
  1. Menghapus entri `git:https://github.com` dan `vscode` di *Windows Credentials*.
  2. Melakukan *Sign Out* akun GitHub siswa sebelumnya di Visual Studio Code.
  3. Mengatur identitas Git lokal (`git config --global user.name` & `user.email`).
- Disediakan tombol aksi navigasi cepat di bagian atas dan bawah halaman:
  - Siswa belum login: Tombol **Masuk (Login)** dan **Daftar Siswa Baru**.
  - Siswa/Guru sudah login: Tombol **Menuju Dashboard Soal Praktikum**.

### 1.2 Penampil & Pengunduh Berkas Data Dummy SQL
- Setiap soal praktikum yang membutuhkan skrip basis data kini dilengkapi dua tombol aksi:
  1. **🗄️ Lihat Data Dummy SQL (`index.php?page=view_sql&id=...`):** Membuka penampil kode SQL langsung di peramban web lengkap dengan penomoran, pewarnaan sintaks, dan tombol *Salin Seluruh SQL (Copy to Clipboard)*.
  2. **💾 Unduh Data Dummy (.sql) (`index.php?action=download_sql&id=...`):** Mengunduh berkas `.sql` asli langsung dari server ke komputer siswa.
- Tombol ini tersedia pada kartu soal di **Dashboard Siswa** dan di bilah aksi atas pada **Halaman Baca Soal**.

---

## 2. CHECKLIST FINAL IMPLEMENTASI

- [x] Perutean `praktikum/index.php` default membuka `src/views/petunjuk_lab.php`.
- [x] Kolom `file_sql_path` ditambahkan dan terisi di tabel `soal` pada basis data SQLite.
- [x] Berkas penampil SQL mandiri `src/views/view_sql.php` dengan tombol salin dan unduh telah dibangun.
- [x] Tombol lihat dan unduh data dummy SQL terintegrasi di `siswa_dashboard.php` dan `view_soal.php`.
- [x] Skrip peluncur `run_server.bat` siap pakai untuk jaringan LAN lokal.
