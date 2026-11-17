# RENCANA PELAKSANAAN: REVISI SOAL PRAKTIKUM KELAS XII (LARAVEL, C# DESKTOP, & DATABASE) DAN INTEGRASI WEB CLASSROOM

**Tanggal:** 2026-11-15  
**Target Pembaca/Siswa:** Siswa SMK Kelas XII Jurusan Rekayasa Perangkat Lunak (RPL) / PPLG  
**Ruang Lingkup Materi:**  
1. **Pemrograman Web:** Framework **Laravel** (Routing, Blade Template, Controller, Auth/Middleware, Single-Table Eloquent/Query Builder, Raw HTML Blade tanpa atribut `name` dan tanpa komentar petunjuk).  
2. **Pemrograman Desktop:** C# Windows Forms (.NET Desktop, ADO.NET, Non-OOP / Tanpa Custom Class).  
3. **Basis Data SQL (Tersendiri):** Studi kasus mandiri (DDL, Foreign Key, Insert di Praktikum 1; DML, WHERE lanjutan, Agregat di Praktikum 2).  
4. **Integrasi Menu Web Classroom:** Penambahan filter navigasi tingkat kelas (Kelas XI & Kelas XII) pada dashboard siswa dan dashboard guru.  
**Lokasi Direktori:** `praktikum/xii/`  
**Status Dokumen:** `Selesai Diimplementasikan`

---

## 1. TUJUAN & PENYELARASAN TINGKAT KESULITAN

### 1.1 Penyesuaian Tingkat Kesulitan
Soal lama di folder `praktikum/xii` (studi kasus aplikasi SPP multi-relasi) disederhanakan agar selevel dengan pola Kelas XI:
- **Praktikum 1:** Login, Register, dan Proteksi Halaman Dashboard (Wajib Login).
- **Praktikum 2:** Operasi CRUD 1 Tabel tunggal tanpa melibatkan relasi multi-tabel.
- Format penulisan to-the-point: **Instruksi Umum**, **Data Dummy**, dan **Petunjuk Soal** (berbasis kebutuhan fungsional/perilaku sistem tanpa mendikte perintah/sintaks kode siswa).

### 1.2 Studi Kasus Baru yang Berbeda dengan Kelas XI
- **Studi Kasus 1 (Pemrograman Web Laravel & Desktop C#): Sistem Apotek & Layanan Obat "Apotek FarmaMedika"**
  - **Praktikum 1 (Auth & Proteksi Dashboard):**
    * *Web (Laravel):* Registrasi & Login Member Pasien (`tb_pasien`). Route terproteksi middleware auth, validasi request, flash message, looping daftar layanan kesehatan di view Blade. Disediakan *raw Blade/HTML* tanpa atribut `name` dan tanpa komentar.
    * *Desktop (C# WinForms):* Registrasi & Login Petugas Apoteker/Kasir (`tb_apoteker`). Form state guard proteksi dashboard kasir apotek, looping SOP dispensing obat pada ListBox.
    * *Database Penunjang:* `database_pasien.sql` & `database_apoteker.sql`.
  - **Praktikum 2 (CRUD 1 Tabel & Algoritma Data):**
    * *Web (Laravel):* Pengelolaan Katalog Obat (`tb_obat`). CRUD single table, kalkulasi potongan diskon kategori obat bebas/resep, looping rekapitulasi total item obat & nominal stok di view Blade. Disediakan *raw Blade/HTML* tanpa atribut `name` dan tanpa komentar.
    * *Desktop (C# WinForms):* Pengelolaan Stok Suplemen & Alkes Gudang (`tb_suplemen`). CRUD single table dengan DataGridView, penanda visual batas minimum restock (`stok <= batas_minimum`), looping akumulasi valuasi aset gudang apotek.
    * *Database Penunjang:* `database_obat.sql` & `database_suplemen.sql`.

- **Studi Kasus 2 (Khusus Basis Data SQL Terpisah): Sistem Ekspedisi Kargo "Nusantara Cargo Express"**
  - **Praktikum 1 Database:** DDL, Foreign Key (`tb_armada_kargo`, `tb_resi_pengiriman`, `tb_pelanggan_kargo`), Insert Data dummy. Kamus data disajikan dengan susunan acak untuk menguji pemahaman relasi referensial.
  - **Praktikum 2 Database:** DML (Update tarif kargo, status pengiriman, delete pembatalan), WHERE clause lanjutan, dan Agregasi data (COUNT, SUM, AVG, MAX, MIN, GROUP BY, HAVING) lengkap dengan tabel **Hasil Akhir yang Diharapkan** (*Expected Output*).

---

## 2. INTEGRASI PADA WEB CLASSROOM LOKAL

1. **Pendaftaran Paket Soal Kelas XII di SQLite (`src/config/database.php`):**
   - Menambahkan 6 paket soal Kelas XII ke dalam tabel `soal` dengan atribut `tingkat = 'XII'` dan path file SQL dummy yang bersangkutan.
2. **Menu Filter Tingkat Kelas di Web Classroom:**
   - **Dashboard Siswa (`siswa_dashboard.php`):** Menambahkan bilah filter tab: `Semua Kelas`, `Kelas XI`, dan `Kelas XII` agar siswa dapat memfilter soal sesuai tingkatannya.
   - **Dashboard Guru (`admin_dashboard.php`):** Menambahkan kolom dan filter tingkatan kelas (XI / XII) pada daftar aktivasi soal dan tabel rekap pengumpulan nilai.

---

## 3. RANCANGAN STRUKTUR BERKAS LENGKAP PADA `praktikum/xii/`

```
praktikum/
└── xii/
    ├── RUBRIK_PENILAIAN_GURU_XII.md
    ├── praktikum-1/
    │   ├── DESKTOP_CSHARP/
    │   │   ├── SOAL_PRAKTIKUM_1_DESKTOP_XII.md
    │   │   └── database_apoteker.sql
    │   ├── WEB_LARAVEL/
    │   │   ├── SOAL_PRAKTIKUM_1_LARAVEL_XII.md
    │   │   ├── database_pasien.sql
    │   │   └── raw_html/
    │   │       ├── login.blade.php
    │   │       ├── register.blade.php
    │   │       └── dashboard.blade.php
    │   └── DATABASE/
    │       └── SOAL_PRAKTIKUM_1_DATABASE_XII.md
    └── praktikum-2/
        ├── DESKTOP_CSHARP/
        │   ├── SOAL_PRAKTIKUM_2_DESKTOP_XII.md
        │   └── database_suplemen.sql
        ├── WEB_LARAVEL/
        │   ├── SOAL_PRAKTIKUM_2_LARAVEL_XII.md
        │   ├── database_obat.sql
        │   └── raw_html/
        │       ├── index.blade.php
        │       ├── tambah.blade.php
        │       └── edit.blade.php
        └── DATABASE/
            ├── SOAL_PRAKTIKUM_2_DATABASE_XII.md
            └── skema_awal_praktikum_2_xii.sql
```

---

## 4. TAHAPAN EKSEKUSI BERTAHAP (CHECKLIST FASE 1–4)

- [x] **Fase 1: Perencanaan & Persetujuan (Plan Approval)**
  - [x] Menyusun dokumen `.plan/2026-11-15-soal-praktikum-kelas-xii-laravel-classroom.md`.
  - [x] Membuka berkas rencana di editor pengguna dan disetujui untuk dieksekusi.
- [x] **Fase 2: Pembuatan Paket Praktikum 1 Kelas XII**
  - [x] Template raw HTML/Blade Praktikum 1 Laravel (`login.blade.php`, `register.blade.php`, `dashboard.blade.php`) tanpa atribut `name` dan tanpa komentar.
  - [x] Database penunjang `database_pasien.sql` dan `database_apoteker.sql`.
  - [x] Naskah soal `SOAL_PRAKTIKUM_1_LARAVEL_XII.md` (Web Laravel).
  - [x] Naskah soal `SOAL_PRAKTIKUM_1_DESKTOP_XII.md` (C# WinForms).
  - [x] Naskah soal `SOAL_PRAKTIKUM_1_DATABASE_XII.md` (Kargo: DDL, Foreign Key, Insert).
- [x] **Fase 3: Pembuatan Paket Praktikum 2 Kelas XII**
  - [x] Template raw HTML/Blade Praktikum 2 Laravel (`index.blade.php`, `tambah.blade.php`, `edit.blade.php`) tanpa atribut `name` dan tanpa komentar.
  - [x] Database penunjang `database_obat.sql`, `database_suplemen.sql`, dan `skema_awal_praktikum_2_xii.sql`.
  - [x] Naskah soal `SOAL_PRAKTIKUM_2_LARAVEL_XII.md` (Web Laravel Single-Table CRUD).
  - [x] Naskah soal `SOAL_PRAKTIKUM_2_DESKTOP_XII.md` (C# WinForms Single-Table CRUD).
  - [x] Naskah soal `SOAL_PRAKTIKUM_2_DATABASE_XII.md` (Kargo: DML, WHERE Lanjutan, Agregasi & Expected Output).
  - [x] Panduan rubrik penilaian guru `RUBRIK_PENILAIAN_GURU_XII.md`.
- [x] **Fase 4: Integrasi Menu Web Classroom**
  - [x] Pembaruan skrip database SQLite `src/config/database.php` (seeding 12 soal lengkap Kelas XI & XII).
  - [x] Penambahan bilah filter kelas (XI / XII) pada `src/views/siswa_dashboard.php` dan `src/views/admin_dashboard.php`.
  - [x] Penambahan navigasi menu langsung di bilah navbar atas `src/views/layout_header.php`.
  - [x] Penambahan fitur Quick Soal Switcher pada halaman pembacaan naskah `src/views/view_soal.php`.
