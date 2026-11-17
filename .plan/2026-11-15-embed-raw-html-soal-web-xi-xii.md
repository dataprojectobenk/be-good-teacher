# RENCANA PELAKSANAAN: PENYEMATAN RAW HTML/BLADE LANGSUNG PADA NASKAH SOAL WEB (.MD) KELAS XI & XII

**Tanggal:** 2026-11-15  
**Target:** Naskah Soal Praktikum Web Kelas XI & Kelas XII  
**Tujuan:** Memasukkan seluruh kode template tampilan (Raw HTML / Blade) langsung ke dalam berkas soal Markdown (`.md`) agar siswa dapat langsung menyalin (*copy-paste*) kode antarmuka secara terpusat dari naskah soal tanpa harus membuka direktori berkas terpisah.  
**Status Dokumen:** `Selesai Diimplementasikan`

---

## 1. RUANG LINGKUP PERUBAHAN BERKAS

1. **Kelas XI - Praktikum 1 Web PHP:**
   - Berkas: `praktikum/xi/praktikum-1/WEB_PHP/SOAL_PRAKTIKUM_1_WEB.md`
   - Menambahkan bagian `## IV. KODE RAW TEMPLATE TAMPILAN (SIAP SALIN)`:
     - Template `login.html`
     - Template `register.html`
     - Template `dashboard.html`
   - Memperbarui instruksi umum agar merujuk ke lampiran template pada soal.

2. **Kelas XI - Praktikum 2 Web PHP:**
   - Berkas: `praktikum/xi/praktikum-2/WEB_PHP/SOAL_PRAKTIKUM_2_WEB.md`
   - Menambahkan bagian `## IV. KODE RAW TEMPLATE TAMPILAN (SIAP SALIN)`:
     - Template `index.html`
     - Template `tambah.html`
     - Template `edit.html`
   - Memperbarui instruksi umum agar merujuk ke lampiran template pada soal.

3. **Kelas XII - Praktikum 1 Web Laravel:**
   - Berkas: `praktikum/xii/praktikum-1/WEB_LARAVEL/SOAL_PRAKTIKUM_1_LARAVEL_XII.md`
   - Menambahkan bagian `## IV. KODE RAW BLADE TEMPLATE (SIAP SALIN)`:
     - Template `resources/views/login.blade.php`
     - Template `resources/views/register.blade.php`
     - Template `resources/views/dashboard.blade.php`
   - Memperbarui instruksi umum agar merujuk ke lampiran template pada soal.

4. **Kelas XII - Praktikum 2 Web Laravel:**
   - Berkas: `praktikum/xii/praktikum-2/WEB_LARAVEL/SOAL_PRAKTIKUM_2_LARAVEL_XII.md`
   - Menambahkan bagian `## IV. KODE RAW BLADE TEMPLATE (SIAP SALIN)`:
     - Template `resources/views/index.blade.php`
     - Template `resources/views/tambah.blade.php`
     - Template `resources/views/edit.blade.php`
   - Memperbarui instruksi umum agar merujuk ke lampiran template pada soal.

---

## 2. CHECKLIST TAHAPAN EKSEKUSI

- [x] Membuat dokumen rencana kerja `.plan/2026-11-15-embed-raw-html-soal-web-xi-xii.md`.
- [x] Menyematkan raw HTML pada `praktikum/xi/praktikum-1/WEB_PHP/SOAL_PRAKTIKUM_1_WEB.md` (`login.html`, `register.html`, `dashboard.html`).
- [x] Menyematkan raw HTML pada `praktikum/xi/praktikum-2/WEB_PHP/SOAL_PRAKTIKUM_2_WEB.md` (`index.html`, `tambah.html`, `edit.html`).
- [x] Menyematkan raw Blade pada `praktikum/xii/praktikum-1/WEB_LARAVEL/SOAL_PRAKTIKUM_1_LARAVEL_XII.md` (`login.blade.php`, `register.blade.php`, `dashboard.blade.php`).
- [x] Menyematkan raw Blade pada `praktikum/xii/praktikum-2/WEB_LARAVEL/SOAL_PRAKTIKUM_2_LARAVEL_XII.md` (`index.blade.php`, `tambah.blade.php`, `edit.blade.php`).
- [x] Menambahkan tombol *"📋 Salin Kode"* 1-klik pada setiap blok kode di pembaca soal web (`src/views/view_soal.php`).
- [x] Verifikasi keterbacaan dan integritas parsing Markdown pada web classroom.
- [x] Memperbarui status rencana menjadi `Selesai Diimplementasikan`.
