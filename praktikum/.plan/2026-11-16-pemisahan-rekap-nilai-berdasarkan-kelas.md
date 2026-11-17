# Rencana Implementasi: Pemisahan Rekapitulasi Nilai Siswa Berdasarkan Kelas

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:**  
- `src/views/admin_nilai.php` (Pemisahan tabel buku nilai, tab navigasi kelas, dan statistik per kelas)  
- `index.php` (Penyempurnaan filter ekspor CSV per kelas)  

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Permasalahan Saat Ini**:
   - Pada halaman `admin_nilai.php`, ketika guru membuka buku nilai secara default, seluruh siswa dari semua kelas (`XI-PPLG` sebanyak 6 siswa dan `XII-PPLG` sebanyak 15 siswa) digabungkan dalam satu tabel panjang.
   - Hal ini menyulitkan guru dalam merekap nilai raport karena kurikulum, paket soal, dan wali kelas untuk Kelas XI dan Kelas XII berbeda.
   
2. **Kebutuhan Solusi Pemisahan Berbasis Kelas**:
   - **Tab Navigasi Kelas Cepat**:
     - `📘 Kelas XI-PPLG (6 Siswa)`: Fokus khusus penilaian siswa kelas XI beserta paket soal tingkat XI.
     - `📙 Kelas XII-PPLG (15 Siswa)`: Fokus khusus penilaian siswa kelas XII beserta paket soal tingkat XII.
     - `🎓 Seluruh Kelas (Tampilan Terpisah)`: Menyajikan dua blok/seksi tabel terpisah (bukan digabung dalam satu tabel).
   - **Blok / Seksi Mandiri per Kelas**:
     - Setiap kelas memiliki kartu statistik analitik tersendiri (Rata-rata Kelas, Tingkat Ketuntasan KKM, Nilai Min/Max).
     - Filter paket soal yang relevan dengan tingkat kelas masing-masing (Kelas XI hanya menampilkan soal XI, Kelas XII hanya menampilkan soal XII).
     - Tabel daftar nilai yang memuat data siswa khusus kelas tersebut.
   - **Cetak & Ekspor Mandiri per Kelas**:
     - Tombol cetak langsung untuk masing-masing kelas dengan jeda halaman cetak (`break-after: page;`) agar rapi saat dicetak ke PDF/kertas fisik.
     - Tombol ekspor CSV khusus untuk kelas yang sedang dibuka.

---

## 2. Kebutuhan Sistem & Dependensi

- MySQL `db_lab_praktikum` (tabel `users`, `pengumpulan`, `soal`).
- Native PHP 8.2 & PDO grouping query.
- CSS `@media print` dengan pemisah halaman cetak (*page break per class*).

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                 # Handler action=export_nilai_csv dengan nama berkas spesifik kelas
└── src/
    └── views/
        └── admin_nilai.php   # Tampilan Buku Nilai terpisah per kelas (Tab Kelas XI & XII, tabel mandiri, statistik per kelas)
```

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Struktur Data & Query Pengelompokan Berbasis Kelas pada `admin_nilai.php`**
  - [x] Buat query pengelompokan siswa dan pengumpulan terstruktur per kelas (`XI-PPLG` dan `XII-PPLG`).
  - [x] Hitung statistik analitik terpisah untuk masing-masing kelas (Rata-rata, KKM, Min, Max).
- [x] **Fase 2: Desain Antarmuka Tab Navigasi Kelas & Filter Relevan**
  - [x] Buat tab navigasi: `📘 Kelas XI-PPLG`, `📙 Kelas XII-PPLG`, dan `🎓 Semua Kelas`.
  - [x] Sesuaikan dropdown filter paket soal agar hanya menampilkan soal yang relevan dengan kelas yang aktif.
- [x] **Fase 3: Pemisahan Seksi Tabel Buku Nilai & Kontrol Cetak / Ekspor per Kelas**
  - [x] Tampilkan tabel nilai dalam kartu/seksi terpisah dengan penanda visual warna khas (Biru untuk XI-PPLG, Oranye untuk XII-PPLG).
  - [x] Pasang tombol cetak per kelas (`window.print()`) dan tombol ekspor CSV khusus kelas tersebut.
  - [x] Tambahkan aturan CSS pemisah halaman cetak (*print page-break*).
- [x] **Fase 4: Verifikasi & Pengujian Fungsional**
  - [x] Uji tampilan tab Kelas XI-PPLG (pastikan hanya memuat 6 siswa kelas XI).
  - [x] Uji tampilan tab Kelas XII-PPLG (pastikan hanya memuat 15 siswa kelas XII dan nilai praktikum 1).
  - [x] Uji ekspor CSV per kelas dan uji cetak print-to-PDF.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Penambahan Kelas Baru di Masa Depan** | Tampilan hardcoded tidak mendeteksi kelas baru | Pengelompokan kelas dibuat dinamis berdasarkan data `DISTINCT kelas FROM users WHERE role = 'siswa'`. |
| **Pencetakan Campur Aduk** | Tabel kelas XI dan XII tercetak bertumpuk di satu halaman | Tambahkan CSS `@media print { .class-section { page-break-after: always; break-after: page; } }`. |
