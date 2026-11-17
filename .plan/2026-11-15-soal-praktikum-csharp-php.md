# RENCANA PELAKSANAAN: PENYUSUNAN SOAL PRAKTIKUM KELAS XI (C# WINFORM, PHP NATIVE, DAN DATABASE) - FINAL UPDATE 4

**Tanggal:** 2026-11-15  
**Target Pembaca/Siswa:** Siswa SMK/SMA Kelas XI Jurusan Rekayasa Perangkat Lunak (RPL) / PPLG  
**Ruang Lingkup Materi:**  
1. **Pemrograman Desktop:** C# Windows Forms (.NET Desktop, Instruksi Fungsional/Behavioral).  
2. **Pemrograman Web:** PHP Native (Web Prosedural, raw HTML tanpa atribut `name` dan tanpa komentar petunjuk).  
3. **Basis Data (Tersendiri):** SQL MySQL/MariaDB (Praktikum 1: Kamus data relasional acak; Praktikum 2: Tanpa petunjuk sintaks, dilengkapi tabel Hasil Akhir yang Diharapkan).  
**Lokasi Direktori:** `praktikum/xi/`  
**Status Dokumen:** `Selesai Diimplementasikan (Completed)`

---

## 1. PEMBAGIAN STUDI KASUS & MATERI UJI

### 1.1 Studi Kasus 1 (Pemrograman Web & Desktop): Sistem Manajemen Kafe "Kopi Nusantara"
- **Praktikum 1:** Login, Register, dan Proteksi Dashboard.
- **Praktikum 2:** Single Table CRUD Katalog Menu (Web) & Stok Bahan Baku (Desktop) dengan algoritma percabangan dan perulangan.

### 1.2 Studi Kasus 2 (Khusus Modul Database Terpisah): Sistem Rental Mobil "TransJava Rent Car"
- **Praktikum 1 Database:** Kamus data lengkap dengan urutan: (1) `tb_mobil`, (2) `tb_transaksi_sewa`, (3) `tb_pelanggan_rental`.
- **Praktikum 2 Database:** Kebutuhan bisnis murni tanpa sintaks SQL langsung, disertai tabel hasil akhir yang diharapkan.

---

## 2. STRUKTUR BERKAS LENGKAP PADA FOLDER `praktikum/xi/`

```
praktikum/
└── xi/
    ├── RUBRIK_PENILAIAN_GURU.md
    ├── praktikum-1/
    │   ├── DESKTOP_CSHARP/
    │   │   ├── SOAL_PRAKTIKUM_1_DESKTOP.md
    │   │   └── database_petugas.sql
    │   ├── WEB_PHP/
    │   │   ├── SOAL_PRAKTIKUM_1_WEB.md
    │   │   ├── database_pelanggan.sql
    │   │   └── raw_html/
    │   │       ├── login.html
    │   │       ├── register.html
    │   │       └── dashboard.html
    │   └── DATABASE/
    │       └── SOAL_PRAKTIKUM_1_DATABASE.md
    └── praktikum-2/
        ├── DESKTOP_CSHARP/
        │   ├── SOAL_PRAKTIKUM_2_DESKTOP.md
        │   └── database_bahan_baku.sql
        ├── WEB_PHP/
        │   ├── SOAL_PRAKTIKUM_2_WEB.md
        │   ├── database_menu.sql
        │   └── raw_html/
        │       ├── index.html
        │       ├── tambah.html
        │       └── edit.html
        └── DATABASE/
            ├── SOAL_PRAKTIKUM_2_DATABASE.md
            └── skema_awal_praktikum_2.sql
```

---

## 3. CHECKLIST FINAL IMPLEMENTASI

- [x] Folder `praktikum-1`, `praktikum-2`, dan `RUBRIK_PENILAIAN_GURU.md` dipindahkan ke `praktikum/xi/`.
- [x] Template raw HTML Praktikum 1 & 2 bebas dari atribut `name` dan tanpa komentar petunjuk.
- [x] Naskah soal C# WinForms Praktikum 1 & 2 mengacu pada format spesifikasi kebutuhan sistem / behavioral.
- [x] Naskah soal PHP Native Praktikum 1 & 2 mengacu pada format spesifikasi kebutuhan sistem / behavioral.
- [x] Naskah soal Basis Data Praktikum 1 menyajikan urutan entitas `tb_mobil`, `tb_transaksi_sewa`, baru `tb_pelanggan_rental`.
- [x] Naskah soal Basis Data Praktikum 2 menyajikan narasi kebutuhan sistem murni tanpa petunjuk sintaks SQL, disertai tabel hasil akhir yang diharapkan untuk setiap nomor.
- [x] Seluruh berkas panduan guru dan rubrik penilaian telah diselaraskan.
