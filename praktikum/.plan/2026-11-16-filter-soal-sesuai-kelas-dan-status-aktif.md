# Rencana Implementasi: Restriksi Akses Soal Sesuai Kelas Siswa & Status Aktif

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:** `src/helpers/auth.php`, `src/views/siswa_dashboard.php`, `src/views/view_soal.php`, `src/views/view_sql.php`, `index.php`  
**Tujuan Utama:** Menjamin bahwa siswa hanya dapat melihat, mengakses, mengunduh, dan mengumpulkan tugas praktikum yang sesuai dengan tingkat kelasnya (`XI` untuk kelas XI-PPLG dan `XII` untuk kelas XII-PPLG) dan berstatus aktif (`is_active = 1`), serta mengeliminasi celah akses silang antar tingkat.

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Kondisi Eksisting**:
   - Di `src/views/siswa_dashboard.php`, terdapat tab navigasi "Semua", "Tingkat XI", dan "Tingkat XII". Siswa kelas XI saat ini dapat melihat daftar soal kelas XII dan sebaliknya.
   - Di `src/views/view_soal.php` dan `src/views/view_sql.php`, pemeriksaan hanya memvalidasi `is_active = 1` tanpa memvalidasi apakah tingkat soal (`soal.tingkat`) sesuai dengan kelas siswa (`user.kelas`).
   - Pada aksi pengumpulan jawaban (`index.php?action=submit_jawaban`) dan pengunduhan berkas (`download_soal`, `download_sql`), belum ada validasi tingkat kelas siswa terhadap soal yang dituju.
2. **Kebutuhan Bisnis & Keamanan**:
   - **Pemetaan Tingkat Otomatis**: Kelas siswa (contoh: `XI-PPLG` -> `XI`, `XII-PPLG` -> `XII`) menjadi acuan utama tingkat soal.
   - **Isolasi Dashboard Siswa**: Dashboard siswa hanya menampilkan statistik dan daftar soal yang aktif (`is_active = 1`) dan sesuai tingkat siswa (`tingkat = $studentTingkat`). Opsi/tab tingkat lain dihilangkan dari dashboard siswa.
   - **Proteksi Halaman Baca Soal & SQL**: Jika siswa mencoba mengakses URL langsung (misal: `index.php?page=soal&id=X`) yang bukan tingkatnya atau tidak aktif, sistem langsung menolak dengan pesan error dan mengalihkan ke dashboard.
   - **Proteksi Pengumpulan & Unduhan**: Aksi submit jawaban dan download berkas menolak segala upaya pengiriman untuk soal yang tidak aktif atau bukan untuk kelas siswa.

---

## 2. Kebutuhan Sistem & Dependensi

- Basis data MySQL `db_lab_praktikum` (tabel `users` kolom `kelas`, tabel `soal` kolom `tingkat` dan `is_active`).
- PHP 8.2+ dengan session login aktif.

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                      # Validasi tingkat & status aktif pada submit_jawaban, download_soal, download_sql
└── src/
    ├── helpers/
    │   └── auth.php               # Fungsi helper getStudentTingkat($kelas)
    └── views/
        ├── siswa_dashboard.php    # Isolasi query soal: WHERE is_active = 1 AND tingkat = :tingkat
        ├── view_soal.php          # Validasi hak akses tingkat soal & switcher terbatas
        └── view_sql.php           # Validasi hak akses tingkat soal pada data dummy SQL
```

### Rincian Perubahan Logika:
1. **Helper `getStudentTingkat(string $kelas): string` (`src/helpers/auth.php`)**:
   ```php
   function getStudentTingkat(string $kelas): string {
       $upper = strtoupper(trim($kelas));
       if (strpos($upper, 'XII') === 0) {
           return 'XII';
       }
       if (strpos($upper, 'XI') === 0) {
           return 'XI';
       }
       return 'XI';
   }
   ```
2. **Dashboard Siswa (`src/views/siswa_dashboard.php`)**:
   - Ambil tingkat siswa: `$studentTingkat = getStudentTingkat($user['kelas'] ?? '');`.
   - Kueri hanya memuat: `SELECT * FROM soal WHERE is_active = 1 AND tingkat = :tingkat ORDER BY sesi ASC, kategori ASC`.
   - Hilangkan tab pilihan tingkat lain; ganti dengan penanda badge aktif kelas siswa (misal: *"Paket Praktikum Aktif - Tingkat XII"*).
3. **Halaman Baca Soal & SQL (`src/views/view_soal.php` & `src/views/view_sql.php`)**:
   ```php
   if (!isAdmin()) {
       $studentTingkat = getStudentTingkat($user['kelas'] ?? '');
       if (!$soal['is_active'] || $soal['tingkat'] !== $studentTingkat) {
           setFlashMessage('error', 'Akses ditolak! Soal praktikum tidak tersedia untuk kelas Anda atau sedang dinonaktifkan.');
           header('Location: index.php?page=dashboard');
           exit;
       }
   }
   ```
4. **Backend Router & Actions (`index.php`)**:
   - Pada aksi `submit_jawaban`, `download_soal`, dan `download_sql`, pastikan `soal['is_active'] == 1` dan `soal['tingkat'] === getStudentTingkat($user['kelas'])`.

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Implementasi Helper `getStudentTingkat()` pada `src/helpers/auth.php`**
  - Buat fungsi helper untuk mendeteksi tingkat kelas siswa secara akurat.
- [x] **Fase 2: Restriksi dan Pembersihan Tampilan `src/views/siswa_dashboard.php`**
  - Batasi kueri soal hanya untuk `tingkat = :tingkat AND is_active = 1`.
  - Sesuaikan kartu statistik dan header filter agar murni menampilkan soal kelas siswa.
- [x] **Fase 3: Proteksi Akses pada `src/views/view_soal.php`, `src/views/view_sql.php`, dan `index.php`**
  - Pasang validasi tingkat dan status aktif di `view_soal.php` dan `view_sql.php`.
  - Pasang validasi pada aksi `submit_jawaban`, `download_soal`, dan `download_sql` di `index.php`.
- [x] **Fase 4: Verifikasi & Pengujian Akses Silang Antar Kelas**
  - Uji siswa XI hanya melihat soal XI yang aktif.
  - Uji siswa XII hanya melihat soal XII yang aktif.
  - Uji upaya akses paksa (ID traversal) siswa XI membuka soal XII (harus ditolak).
  - Uji upaya pengumpulan tugas siswa ke soal non-aktif atau beda tingkat (harus ditolak).

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Akses Akun Guru / Admin Terpengaruh** | Admin tidak bisa memantau semua soal | Pengecekan tingkat hanya diterapkan jika pengguna BUKAN admin (`!isAdmin()`). Guru/Admin tetap memiliki akses 100% ke semua soal aktif maupun non-aktif. |
| **Siswa dengan Format Kelas Lama** | Siswa lama (`XII RPL 2`) tidak terbaca | Logika `strpos($upper, 'XII') === 0` mendukung baik format baru `XII-PPLG` maupun format lama `XII RPL 2`. |
