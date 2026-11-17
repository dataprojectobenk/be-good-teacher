# Rencana Implementasi: Revisi Rekap Nilai dengan Tab Eksklusif per Kelas (Pemisahan Total Halaman XI & XII)

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:**  
- `src/views/admin_nilai.php` (Penghapusan tampilan gabungan, implementasi tab kelas eksklusif, isolasi penuh konten XI & XII)  
- `src/views/layout_header.php` (Pembaruan tautan menu nilai agar langsung mengarah ke kelas default)  
- `index.php` (Pengalihan default `page=nilai` ke kelas pertama tanpa mode "Semua Kelas")  

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Evaluasi & Arahan Pengguna**:
   - Pengguna menginstruksikan: *"Revisi ulang: rekap nilai dipisah dan tidak di satukan halaman xi dan xii. Buatkan tab atau fitur untuk bisa berganti kelas"*.
   - Artinya, **DILARANG** menampilkan Kelas XI dan Kelas XII secara bersamaan di satu halaman (mode gabungan/scroll panjang dihapus total).
   - Halaman harus menerapkan sistem **Tab Kelas Eksklusif** (*Single-Class Active View*):
     - Hanya ada kelas yang sedang aktif dipilih.
     - Guru berpindah antar kelas menggunakan Tab Kelas di bagian atas.
     - Konten halaman (judul, statistik analitik, filter paket soal, tabel nilai siswa, tombol ekspor, dan lembar cetak) 100% terisolasi khusus untuk kelas yang sedang dibuka.

2. **Rancangan Antarmuka & UX**:
   - **Tab Pengganti Kelas**:
     - `📘 Kelas XI-PPLG` (Badge: 6 Siswa)
     - `📙 Kelas XII-PPLG` (Badge: 15 Siswa)
     - Hapus total tab *"Semua Kelas"*.
   - **Aturan Default**:
     - Jika guru mengakses `index.php?page=nilai` tanpa parameter kelas, sistem otomatis menetapkan `$activeKelas = 'XI-PPLG'` (atau kelas pertama).
   - **Filter Soal Dinamis Sesuai Kelas**:
     - Saat berada di tab `XI-PPLG`: dropdown soal **hanya** menampilkan soal Tingkat XI (PHP & C#).
     - Saat berada di tab `XII-PPLG`: dropdown soal **hanya** menampilkan soal Tingkat XII (Laravel & C# / Basis Data).
   - **Ekspor & Cetak Terisolasi**:
     - Tombol `📥 Ekspor CSV` langsung mengekspor data kelas yang sedang aktif.
     - Tombol `🖨️ Cetak Buku Nilai` mencetak lembar nilai resmi khusus kelas tersebut.

---

## 2. Kebutuhan Sistem & Dependensi

- MySQL `db_lab_praktikum` (tabel `users`, `pengumpulan`, `soal`).
- Native PHP 8.2 & PDO.
- Tidak ada dependensi pustaka luar.

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                 # Penyesuaian default route page=nilai
└── src/
    └── views/
        ├── layout_header.php # Tautan navbar guru langsung mengarah ke kelas aktif
        └── admin_nilai.php   # Tampilan Buku Nilai eksklusif 1 kelas aktif dengan Tab Berganti Kelas
```

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Logika Pemilihan Kelas Tunggal pada `admin_nilai.php`**
  - [x] Hapus opsi `ALL` (Semua Kelas).
  - [x] Tentukan `$activeKelas` (default ke `XI-PPLG` jika tidak ada parameter atau tidak valid).
  - [x] Isolasi query siswa sehingga hanya mengambil siswa dari `$activeKelas`.
- [x] **Fase 2: Desain Tab Berganti Kelas & Filter Soal Terisolasi**
  - [x] Buat tab navigasi eksklusif: `📘 Kelas XI-PPLG` dan `📙 Kelas XII-PPLG`.
  - [x] Filter opsi dropdown paket soal secara ketat berdasarkan tingkat kelas aktif (`tingkat = 'XI'` untuk XI-PPLG, `tingkat = 'XII'` untuk XII-PPLG).
- [x] **Fase 3: Penyajian Tabel Nilai, Statistik, & Tombol Aksi per Kelas**
  - [x] Tampilkan kartu statistik tunggal untuk kelas aktif.
  - [x] Tampilkan tabel buku nilai tunggal untuk kelas aktif.
  - [x] Sediakan tombol `📥 Ekspor CSV (Kelas X)` dan `🖨️ Cetak Lembar Nilai (Kelas X)`.
- [x] **Fase 4: Verifikasi & Pengujian Fungsional**
  - [x] Uji akses `page=nilai` -> otomatis menampilkan Kelas XI-PPLG tanpa data Kelas XII.
  - [x] Uji klik tab `Kelas XII-PPLG` -> menampilkan Kelas XII-PPLG tanpa data Kelas XI.
  - [x] Uji filter soal per tingkat pada masing-masing tab kelas.
  - [x] Uji ekspor CSV dan cetak print-friendly.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **URL Lama dengan `kelas=ALL`** | User membuka bookmark lama `kelas=ALL` | Diarahkan otomatis (*fallback*) ke kelas default `XI-PPLG`. |
| **Filter Soal Tidak Cocok dengan Tingkat Kelas** | Soal kelas XI muncul saat membuka kelas XII | Query opsi soal dibatasi dengan klausa `WHERE tingkat = :tingkat` sesuai kelas yang sedang aktif. |
