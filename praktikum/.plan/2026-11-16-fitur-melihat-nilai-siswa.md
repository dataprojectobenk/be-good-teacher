# Rencana Implementasi: Fitur Rekapitulasi & Transkrip Nilai Siswa (Dual-Role: Guru & Siswa)

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:**  
- `index.php` (Routing `page=nilai`)  
- `src/views/layout_header.php` (Menu Navigasi Nilai untuk Guru & Siswa)  
- `src/views/admin_nilai.php` (Buku Nilai & Rekapitulasi Nilai Siswa per Kelas untuk Guru)  
- `src/views/siswa_nilai.php` (Transkrip & Rapor Nilai Praktikum Mandiri untuk Siswa)  

---

## 1. Analisis Kebutuhan & Ruang Lingkup

Pengguna meminta penambahan **"fitur untuk melihat nilai siswa"**. Berdasarkan arsitektur multi-peran aplikasi laboratorium (Guru dan Siswa), fitur ini dipecah menjadi dua kebutuhan utama:

1. **Kebutuhan Sisi Siswa (Student Grade Portal)**:
   - Siswa membutuhkan halaman khusus yang menyajikan transkrip nilai mandiri secara terpusat tanpa harus membuka kartu soal satu per satu.
   - Menampilkan:
     - Kartu Ringkasan Akademik: Rata-rata nilai praktikum, jumlah tugas yang sudah dinilai, predikat kelulusan (A / B / C / Remedial).
     - Tabel Rincian Nilai: Judul soal, tanggal pengumpulan, skor nilai (0–100), status kelulusan KKM (KKM = 75), dan catatan/umpan balik dari guru penguji.
     - Kemudahan cetak lembar nilai (*print-friendly*).

2. **Kebutuhan Sisi Guru / Administrator (Teacher Gradebook & Analytics)**:
   - Guru membutuhkan halaman Buku Nilai (Gradebook) terpadu untuk merekapitulasi seluruh nilai siswa per kelas (`XI-PPLG` dan `XII-PPLG`).
   - Menampilkan:
     - Filter berbasis kelas (`Semua Kelas`, `XI-PPLG`, `XII-PPLG`) dan filter paket soal.
     - Metrik Statistik Kelas: Rata-rata kelas, nilai tertinggi, nilai terendah, persentase ketuntasan KKM.
     - Tabel Rekapitulasi Nilai Siswa: No, NIS, Nama Lengkap, Kelas, Nilai Tiap Sesi Ujian, Rata-rata Akhir, Status Ketuntasan (Tuntas / Remedial / Belum Mengumpulkan).
     - Tombol Aksi: Cetak Laporan Nilai (`window.print()`) dan Ekspor ke format CSV / Excel untuk keperluan administrasi pengolahan nilai raport sekolah.

---

## 2. Kebutuhan Sistem & Dependensi

- Basis data MySQL `db_lab_praktikum` (tabel `pengumpulan`, `users`, `soal`).
- Native PHP 8.2 & PDO query agregasi (`AVG`, `MAX`, `MIN`, `COUNT`).
- CSS Media `@media print` untuk format cetak lembar nilai bersih tanpa elemen navbar.
- Format CSV export streaming menggunakan fungsi native PHP (`fputcsv`).

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                      # Registrasi route 'page=nilai' & handler ekspor CSV 'action=export_nilai_csv'
└── src/
    └── views/
        ├── layout_header.php      # Penambahan link navbar: "📊 Rekap Nilai" (Guru) & "📊 Nilai Saya" (Siswa)
        ├── admin_nilai.php        # Tampilan Buku Nilai Guru (Tabel Rekap, Statistik Kelas, Filter, Print & Export)
        └── siswa_nilai.php        # Tampilan Transkrip Nilai Siswa (Rapor Nilai Mandiri, Kartu Rata-rata, Feedback)
```

### Rincian Alur Navigasi & Data:
1. **Navigasi Global (`layout_header.php`)**:
   - Jika `role === 'admin'`: Tambahkan menu `index.php?page=nilai` dengan label **"📊 Rekap Nilai"**.
   - Jika `role === 'siswa'`: Tambahkan menu `index.php?page=nilai` dengan label **"📊 Nilai Saya"**.
2. **Router (`index.php`)**:
   - `case 'nilai'`:
     - Jika `isAdmin()`: memuat `src/views/admin_nilai.php`.
     - Jika Siswa: memuat `src/views/siswa_nilai.php`.
   - `action === 'export_nilai_csv'`: Menghasilkan berkas CSV rekap nilai kelas yang dapat langsung diunduh dan dibuka di Microsoft Excel.

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Penambahan Routing & Menu Navigasi Global**
  - [x] Perbarui `index.php` untuk menangani perutean `page=nilai` dan aksi `export_nilai_csv`.
  - [x] Perbarui `src/views/layout_header.php` dengan menambahkan tautan menu "📊 Rekap Nilai" (Guru) dan "📊 Nilai Saya" (Siswa).
- [x] **Fase 2: Pembuatan Tampilan Transkrip Nilai Siswa (`src/views/siswa_nilai.php`)**
  - [x] Tampilkan kartu statistik hasil belajar mandiri (Rata-rata Nilai, Tugas Tuntas, Tugas Remedial, Menunggu Penilaian).
  - [x] Susun tabel riwayat pengerjaan soal, nilai akhir, predikat, dan feedback guru.
  - [x] Sediakan tombol cetak transkrip nilai mandiri (*print-friendly*).
- [x] **Fase 3: Pembuatan Buku Rekapitulasi Nilai Guru (`src/views/admin_nilai.php`)**
  - [x] Susun kartu statistik analitik kelas (Nilai Rata-rata, Tertinggi, Terendah, Tingkat Kelulusan KKM).
  - [x] Bangun filter interaktif berdasarkan Kelas (`Semua`, `XI-PPLG`, `XII-PPLG`) dan filter Soal.
  - [x] Tampilkan tabel matriks rekap nilai siswa lengkap dengan indikator tuntas/remedial.
  - [x] Integrasikan tombol Cetak Rekap Nilai dan tombol Ekspor CSV untuk arsip guru.
- [x] **Fase 4: Verifikasi & Uji Fungsionalitas**
  - [x] Verifikasi tampilan dari sudut pandang akun Guru (`admin`).
  - [x] Verifikasi tampilan dari sudut pandang akun Siswa (contoh: Fahrizal, Rifqi, dsb).
  - [x] Uji ekspor CSV nilai dan pastikan kompatibel dibuka di Microsoft Excel.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Kebocoran Data Antar Siswa** | Siswa dapat melihat nilai siswa lain | Pada `siswa_nilai.php`, query secara ketat dibatasi `WHERE user_id = :user_id` dari sesi login siswa aktif. |
| **Siswa Belum Mengumpulkan Tugas** | Nilai NULL menyebabkan error perhitungan matematika | Gunakan `COALESCE`, casting angka aman, dan penandaan status eksplisit *"Belum Mengumpulkan"* atau *"Menunggu Diperiksa"*. |
| **Tingkat KKM Berbeda Antar Sekolah** | Standar kelulusan tidak fleksibel | Ditetapkan KKM standar RPL 75 dengan indikator visual yang jelas (Hijau: Tuntas, Merah: Remedial). |
