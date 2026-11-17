# Rencana Implementasi: Pemisahan Halaman Kontrol Sesi Ujian dan Rekapitulasi Pengumpulan Jawaban

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:**  
- `index.php` (Routing `page=kontrol_sesi` dan `page=pengumpulan`, redirect aksi yang sesuai)  
- `src/views/layout_header.php` (Pembaruan menu navigasi guru: Kontrol Sesi Ujian vs Pengumpulan Jawaban)  
- `src/views/admin_sesi.php` (Halaman khusus Kontrol Sesi Ujian & Saklar Aktivasi Soal)  
- `src/views/admin_pengumpulan.php` (Halaman khusus Rekapitulasi Pengumpulan Jawaban, Real-time Auto-Update, Audit Telemetri, & Modal Pratinjau Penilaian)  
- `src/views/admin_dashboard.php` (Pengalihan / Router terpadu agar tautan lama tetap kompatibel)  

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Masalah Saat Ini**:
   - Halaman `admin_dashboard.php` menggabungkan dua fungsi yang sangat berbeda dalam satu tampilan panjang:
     - Bagian atas: Kontrol Saklar Aktivasi Soal Praktikum (konfigurasi sesi ujian).
     - Bagian bawah: Rekapitulasi pengumpulan puluhan siswa, audit telemetri duplikasi perangkat, live polling 3.5 detik, notifikasi audio toast, dan modal pratinjau penilaian.
   - Penggabungan ini membuat halaman terlalu panjang, membebani pandangan guru saat hanya ingin mengaktifkan/menonaktifkan satu sesi ujian, atau saat sedang fokus memantau pengumpulan jawaban siswa.

2. **Solusi Pemisahan Bersih (Separation of Concerns)**:
   - **Halaman 1: Kontrol Sesi Ujian (`page=kontrol_sesi` atau default admin dashboard)**:
     - Khusus mengelola status aktifasi soal ujian tingkat XI dan XII.
     - Kartu statistik: Total Paket Soal, Soal Aktif, Soal Nonaktif, Siswa Terdaftar.
     - Tab filter kelas (Semua, Kelas XI, Kelas XII).
     - Tabel paket soal dengan tombol aksi: Buka Naskah Soal, Lihat Data Dummy, dan Saklar Aktifkan/Nonaktifkan.
     - Tanpa beban skrip polling real-time 3.5 detik sehingga sangat ringan dan cepat dibuka.
   - **Halaman 2: Rekapitulasi Pengumpulan Jawaban (`page=pengumpulan`)**:
     - Khusus monitoring langsung pengumpulan jawaban siswa di laboratorium.
     - Auto-update real-time (polling 3.5 detik), Audio chime notifikasi, dan Floating Toast.
     - Kartu statistik: Jawaban Terkumpul, Siswa Terdaftar, Deteksi PC Duplikat (Alerts), dan Soal Aktif.
     - Bilah filter spesifik per soal praktikum.
     - Tabel jawaban siswa lengkap dengan telemetri IP/Host lab dan peringatan titip kirim.
     - Fitur interaktif: Modal Pratinjau Berkas SQL dan formulir input nilai instan via AJAX.

---

## 2. Kebutuhan Sistem & Dependensi

- MySQL `db_lab_praktikum` (tabel `soal`, `pengumpulan`, `users`).
- PHP 8.2 & PDO.
- Web Audio API & JavaScript Polling Engine pada halaman pengumpulan.
- Tidak ada dependensi pustaka luar (100% mandiri dan bekerja di jaringan LAN lab).

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                      # Registrasi route 'kontrol_sesi' & 'pengumpulan', penyesuaian redirect
└── src/
    └── views/
        ├── layout_header.php      # Menu navigasi Guru: [🎛️ Kontrol Sesi] [📥 Pengumpulan Jawaban] [📊 Rekap Nilai]
        ├── admin_sesi.php         # Tampilan Khusus Kontrol Sesi Ujian (Manajemen Aktivasi Soal)
        ├── admin_pengumpulan.php  # Tampilan Khusus Rekapitulasi Jawaban (Live Monitor, Audit, Modal Preview)
        └── admin_dashboard.php    # Router pengarah kompatibilitas (mengarahkan default ke kontrol sesi / pengumpulan)
```

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Pembuatan Halaman Khusus Kontrol Sesi Ujian (`src/views/admin_sesi.php`)**
  - [x] Ekstrak Bagian 1 dari dashboard lama menjadi halaman mandiri yang bersih dan fokus.
  - [x] Tambahkan kartu statistik khusus status soal (Total Paket, Aktif, Nonaktif, Total Siswa).
  - [x] Berikan tombol pintas cepat menuju halaman pengumpulan jawaban.
- [x] **Fase 2: Pembuatan Halaman Khusus Rekapitulasi Pengumpulan Jawaban (`src/views/admin_pengumpulan.php`)**
  - [x] Pindahkan Bagian 2 (Tabel pengumpulan, audit IP/Host, skrip polling 3.5s, chime audio, toast notification, modal preview & AJAX grading).
  - [x] Tampilkan header fokus monitoring pengumpulan dan filter soal.
  - [x] Perbarui aksi `nilai_jawaban` agar redirect kembali ke `index.php?page=pengumpulan`.
- [x] **Fase 3: Pembaruan Routing & Navigasi Global (`index.php` & `layout_header.php`)**
  - [x] Daftarkan `page=kontrol_sesi` dan `page=pengumpulan` pada perutean `index.php`.
  - [x] Pada `action=toggle_soal`, arahkan redirect ke `index.php?page=kontrol_sesi`.
  - [x] Pada `layout_header.php`, perbarui menu Guru menjadi:
    1. `🎛️ Sesi Ujian` (`index.php?page=kontrol_sesi`)
    2. `📥 Pengumpulan Jawaban` (`index.php?page=pengumpulan`)
    3. `📊 Rekap Nilai` (`index.php?page=nilai`)
- [x] **Fase 4: Verifikasi & Pengujian Fungsional Menyeluruh**
  - [x] Uji saklar aktifasi soal pada halaman `kontrol_sesi` (pastikan update status lancar tanpa gangguan).
  - [x] Uji halaman `pengumpulan` (pastikan auto-polling, audio chime, modal preview berkas SQL, dan simpan nilai AJAX tetap berjalan 100% sempurna).
  - [x] Uji navigasi navbar antar halaman.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Tautan / Redirect Lama Rusak** | Guru mengetik `page=dashboard` dan mendapati halaman error | `page=dashboard` pada router `index.php` secara mulus mengarahkan admin ke `admin_sesi.php` (atau mempertahankan alias). |
| **Skrip Polling Tidak Aktif** | Guru tidak mendapat auto-update pada halaman pengumpulan | Seluruh skrip real-time, audio chime, dan toast dipindahkan utuh ke `admin_pengumpulan.php` dan diinisialisasi saat halaman dibuka. |
| **Filter Dropdown Kehilangan Konteks** | Filter soal hilang saat submit form penilaian inline | Form AJAX mengirim parameter via POST asinkron tanpa reload, dan tombol redirect konvensional mengarah ke `page=pengumpulan`. |
