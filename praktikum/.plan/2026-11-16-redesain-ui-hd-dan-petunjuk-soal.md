# Rencana Implementasi: Redesain Seluruh Tampilan Antarmuka (UI/UX HD Monitor Ergonomics & Integrasi Petunjuk Soal Informatif)

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:**  
- `src/views/layout_header.php` & `src/views/layout_footer.php` (CSS Framework Design System, Container 1440px / Fluid HD, Compact Navigation Bar, Utility Classes)  
- `src/views/view_soal.php` (Redesain Lembar Kerja Soal: Two-Column Workspace Layout dengan Petunjuk Soal Interaktif di Kiri & Panel Aksi/Upload Sticky di Kanan)  
- `src/views/siswa_dashboard.php` (Redesain Dashboard Siswa: Compact Header, Kartu Praktikum Informatif dengan Cuplikan Petunjuk Inti, dan Form Upload Rapi)  
- `src/views/admin_pengumpulan.php` (Redesain Monitoring Pengumpulan: Tabel Lapang HD Monitor, Modal Pratinjau Naskah SQL dengan Split-View Side-by-Side Grading)  
- `src/views/admin_sesi.php` (Redesain Kontrol Sesi Ujian: Compact Metrics Toolbar, Saklar Status Ergonomis, Inline Action Buttons)  
- `src/views/admin_nilai.php` (Redesain Buku Nilai Siswa: Tab Kelas Elegan, Inline Filter Toolbar, Tabel Nilai HD Tanpa Tumpukan Baris)  

---

## 1. Analisis Kebutuhan & Akar Masalah (Root Causes)

Berdasarkan evaluasi penggunaan pada monitor resolusi HD (1366×768 hingga 1920×1080):
1. **Layout Sempit & Terbuang pada Monitor HD**:
   - Container utama sebelumnya dibatasi `max-width: 1140px` dengan margin besar di kiri-kanan, menyebabkan ruang horizontal monitor HD terbuang sia-sia, sementara elemen tabel dan form terdesak secara vertikal sehingga saling menumpuk.
   - Banner-banner peringatan statis di bagian atas layar terlalu tebal dan memakan hingga 40% area *above-the-fold*, memaksa pengguna melakukan scroll berulang.
2. **Posisi Petunjuk Soal Kurang Informatif**:
   - Pada halaman siswa, petunjuk teknis soal hanya dapat dilihat jika siswa membuka naskah di tab browser baru (`view_soal.php`).
   - Akibatnya, siswa harus terus berpindah-pindah tab (*context switching*) antara tab membaca soal dan tab mengunggah jawaban.
   - Pada halaman `view_soal.php` itu sendiri, petunjuk soal membentang satu kolom panjang tanpa adanya panel pengumpulan terpadu.
3. **Komponen Menumpuk (*Cluttered Stacking*) pada Tabel Guru**:
   - Kolom tabel pengumpulan memuat tombol Pratinjau, tombol Unduh, telemetri IP/Host, dan form penilaian yang ditumpuk secara vertikal dalam satu sel tabel kecil.
   - Modal pratinjau kode SQL sebelumnya meletakkan naskah kode di atas dan form penilaian di bawah, sehingga guru harus scroll berkali-kali untuk memberi nilai.

---

## 2. Kebutuhan Sistem & Solusi Desain Arsitektur Baru

### A. Global Layout & HD Monitor Optimization (`layout_header.php`)
- **Fluid Container System**: Memperlebar container ke `max-width: 1440px; width: 95%` sehingga memanfaatkan bentang layar monitor HD secara optimal.
- **Compact Sleek Navbar**: Memperkecil tinggi navbar dari 64px menjadi 52px dengan tipografi tajam, badge identitas ringkas, dan kontras warna modern (*Dark Slate & Indigo*).
- **Design System Tokens**: Palet warna diperbarui dengan skema Slate/Zinc yang bersih, bayangan halus (*subtle shadow*), border 1px lembut (`#e2e8f0`), dan padding yang ergonomis agar komponen tidak berdempetan.

### B. Workspace Split-View Petunjuk Soal Terpadu (`src/views/view_soal.php`)
- Mengubah halaman baca soal menjadi **Dual-Pane Integrated Workspace**:
  - **Pane Kiri (65–70% Lebar)**: Naskah Soal Markdown lengkap dengan syntax highlighter, tabel spesifikasi database, data dummy SQL, dan tombol salin kode cepat.
  - **Pane Kanan (Sticky Sidebar 30–35% Lebar)**:
    - *Kartu Status Ujian*: Waktu pengerjaan, format pengumpulan (SQL / GitHub), status kelulusan KKM (75).
    - *Formulir Pengumpulan Jawaban Langsung*: Siswa dapat mengunggah file SQL atau menempelkan tautan GitHub langsung di halaman soal tanpa membuka tab lain!
    - *Riwayat & Feedback Guru*: Menampilkan nilai dan umpan balik yang langsung terlihat begitu guru selesai menilai.

### C. Redesain Dashboard Siswa (`src/views/siswa_dashboard.php`)
- Mengganti banner kuning raksasa dengan **Compact Dismissible Alert Bar** yang ramping.
- Kartu tugas praktikum dibuat dengan gaya *modern card deck*:
  - Header kartu memuat *badge* sesi, kategori, dan status pengumpulan.
  - Terdapat cuplikan **Petunjuk Inti & Sasaran Praktikum** (*Quick Objectives*) sehingga siswa langsung tahu apa yang harus dibuat.
  - Tombol aksi jelas dan rapi: `🚀 Buka Lembar Kerja & Kumpul Tugas` dan `🗄️ Lihat Data Dummy`.

### D. Redesain Panel Guru (`admin_pengumpulan.php`, `admin_sesi.php`, `admin_nilai.php`)
- **Monitoring Pengumpulan (`admin_pengumpulan.php`)**:
  - Tabel memanfaatkan bentang layar HD: telemetri IP/Host diletakkan berdampingan (*inline badges*), tombol aksi dikelompokkan dalam *button group* rapi.
  - **Modal Pratinjau Side-by-Side**: Naskah kode SQL di sisi kiri (60%) dan Form Penilaian Cepat di sisi kanan (40%). Guru dapat memeriksa kode sambil langsung mengetik nilai tanpa perlu scroll ke bawah!
- **Kontrol Sesi (`admin_sesi.php`)**:
  - Ringkasan statistik menjadi horizontal KPI baris tunggal.
  - Saklar aktivasi dan tombol naskah soal tersusun sejajar (*horizontal inline layout*).
- **Buku Nilai (`admin_nilai.php`)**:
  - Tab kelas eksklusif dengan pill navigasi yang mencolok.
  - Tabel nilai bersih dengan indikator status KKM yang kontras dan jelas.

---

## 3. Struktur Berkas yang Direnovasi

```
c:\xampp\htdocs\
└── src/
    └── views/
        ├── layout_header.php      # Base CSS Design System (HD Fluid 1440px, Typography, Workspace Grid)
        ├── view_soal.php          # Dual-Pane Workspace (Naskah Soal Kiri + Upload Langsung Kanan)
        ├── siswa_dashboard.php    # Modern Card Deck dengan Cuplikan Petunjuk Inti Soal
        ├── admin_pengumpulan.php  # HD Monitor Submissions Table + Side-by-Side Grading Modal
        ├── admin_sesi.php         # Compact Exam Session Control & Inline Action Buttons
        └── admin_nilai.php        # Clean Class-Exclusive Gradebook
```

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Pembaruan Design System & Layout Global HD (`src/views/layout_header.php`)**
  - Terapkan container lebar HD (`max-width: 1440px; width: 95%`).
  - Bangun komponen CSS utilitas: Grid split-view (`.workspace-layout`), `.compact-kpi-bar`, `.btn-group-inline`, dan modal side-by-side (`.modal-split-grid`).
  - Rampingkan Navbar dan header identitas pengguna.
- [x] **Fase 2: Redesain Halaman Lembar Kerja Soal Terpadu (`src/views/view_soal.php`)**
  - Implementasikan tata letak Dual-Pane Workspace (Pane Naskah Soal di Kiri, Sticky Sidebar Form Upload di Kanan).
  - Hubungkan form pengumpulan jawaban langsung di sidebar soal sehingga siswa tidak perlu berpindah tab browser.
  - Sajikan ringkasan kriteria praktikum, status kirim, dan tombol cepat data dummy SQL secara informatif.
- [x] **Fase 3: Redesain Dashboard Siswa (`src/views/siswa_dashboard.php`)**
  - Rampingkan banner pengingat akun lab menjadi notifikasi ringkas.
  - Tata ulang kartu soal dengan cuplikan petunjuk praktikum (*problem brief / objectives*), tombol navigasi lembar kerja, dan badge status pengumpulan yang menonjol.
- [x] **Fase 4: Redesain Panel Guru (Monitoring, Sesi, dan Rekap Nilai)**
  - Di `admin_pengumpulan.php`: Tata ulang tabel agar memanfaatkan layar HD tanpa tumpukan vertikal, dan perbarui modal pratinjau dengan tata letak split horizontal (Kode SQL di kiri, Form Penilaian di kanan).
  - Di `admin_sesi.php`: Rampingkan tabel aktivasi dan toolbar statistik.
  - Di `admin_nilai.php`: Rampingkan tab kelas dan tabel nilai eksklusif.
- [x] **Fase 5: Verifikasi Visual & Fungsionalitas Menyeluruh**
  - Uji render pada resolusi HD (1366px, 1440px, 1920px) untuk memastikan tidak ada komponen yang bertumpuk atau terpotong.
  - Uji alur pengerjaan siswa pada lembar kerja soal baru (baca soal di kiri, upload langsung di kanan).
  - Uji alur koreksi guru pada modal side-by-side (baca kode di kiri, simpan nilai di kanan).

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Layar Siswa dengan Resolusi Kecil (Handphone / Tablet)** | Tampilan split-view 2 kolom terhimpit jika dibuka di layar sempit | Menggunakan CSS Media Query `@media (max-width: 992px)` yang otomatis mengubah tata letak split 2 kolom menjadi tumpukan 1 kolom vertikal yang responsif. |
| **Pengiriman Jawaban dari Sidebar Soal** | Konflik route aksi `submit_jawaban` | Form action tetap mengarah ke `index.php?action=submit_jawaban` dengan input tersembunyi `soal_id` dan kembali ke halaman soal aktif dengan flash message sukses. |
| **Modal Pratinjau Terlalu Lebar** | Tampilan terpotong di monitor dengan resolusi 1366×768 | Mengatur modal dengan `max-width: 95vw; max-height: 90vh;` dan scrolling internal mandiri pada masing-masing pane (kode dan form nilai). |
