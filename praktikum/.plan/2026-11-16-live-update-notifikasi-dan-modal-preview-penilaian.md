# Rencana Implementasi: Auto-Update Real-time, Notifikasi, dan Modal Preview Jawaban Terintegrasi Penilaian

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:** `index.php`, `src/views/admin_dashboard.php`  
**Tujuan Utama:** Mengembangkan dua fitur produktivitas utama pada Panel Guru Lab:
1. **Pembaruan Otomatis & Notifikasi Real-time**: Memperbarui tabel pengumpulan dan kartu statistik secara *live* tanpa refresh halaman, disertai notifikasi visual (toast) dan audio chime saat ada siswa yang baru mengumpulkan.
2. **Modal Pratinjau Berkas & Penilaian Cepat**: Menampilkan isi berkas jawaban SQL siswa secara langsung di dalam jendela modal interaktif lengkap dengan form input nilai (0–100) dan umpan balik guru yang dapat disimpan seketika (*instant AJAX scoring*).

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Kendala Guru Saat Ini**:
   - Guru harus menekan `F5` berulang kali untuk melihat siswa mana saja yang sudah selesai mengumpulkan.
   - Untuk memeriksa kode SQL siswa, guru harus mengunduh berkas satu per satu ke komputer lalu membukanya di editor teks eksternal, yang sangat memakan waktu untuk puluhan siswa.
2. **Kebutuhan Fitur Terpadu**:
   - **Backend API (`index.php`)**:
     1. `action=api_submissions_poll`: Mengembalikan daftar jawaban baru sejak `last_id`, total pengumpulan, dan metrik duplikasi perangkat.
     2. `action=api_preview_submission`: Mengambil isi teks berkas jawaban (SQL/teks) dan detail pengumpulan berdasarkan `id` pengumpulan untuk ditampilkan di modal.
     3. `action=api_save_grade`: Menyimpan nilai dan feedback guru via AJAX sehingga tabel dan modal tersinkronisasi tanpa me-reload browser.
   - **Antarmuka Panel Guru (`admin_dashboard.php`)**:
     1. **Live Auto-Update**: Polling asinkron (interval 3.5 detik). Jika ada pengumpulan baru, baris tabel baru otomatis masuk di posisi teratas dengan animasi highlight hijau lembut (*glow*).
     2. **Notifikasi Audio-Visual**: Muncul notifikasi toast di sudut kanan atas dan bunyi *chime* sintetis (dilengkapi saklar Mute/Unmute audio).
     3. **Modal Pratinjau & Penilaian**:
        - Tombol `👁️ Pratinjau & Nilai` pada setiap baris pengumpulan.
        - Modal menampilkan: Identitas Siswa (Nama, NIS, Kelas, IP, PC Host), Naskah Kode SQL lengkap dalam blok kode dengan format monospace & scrolling, Catatan Siswa, serta Form Nilai (0–100) & Feedback Guru dengan tombol "Simpan Nilai".

---

## 2. Kebutuhan Sistem & Dependensi

- Basis data MySQL `db_lab_praktikum` (tabel `pengumpulan`, `users`, `soal`).
- JavaScript modern (ES6+ `fetch`, `async/await`, Web Audio API sintetis).
- PHP 8.2 file streaming & JSON response.
- Tanpa dependensi library eksternal (100% mandiri, ringan, dan bekerja offline di jaringan lab).

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                      # Endpoint API: api_submissions_poll, api_preview_submission, api_save_grade
└── src/
    └── views/
        └── admin_dashboard.php    # Komponen Modal Preview, Form Penilaian Cepat, Toast Container, & Realtime Script
```

### Rincian Endpoint API Backend:
1. **`index.php?action=api_submissions_poll`**:
   - Memvalidasi hak akses admin (`requireAdmin()`).
   - Menerima `last_id` dan `filter_soal`.
   - Mengembalikan data pengumpulan baru dalam format JSON.
2. **`index.php?action=api_preview_submission`**:
   - Menerima `id` pengumpulan.
   - Membaca isi berkas dari `src/uploads/<jawaban_konten>` (jika file SQL/teks).
   - Mengembalikan detail siswa, judul soal, isi berkas, nilai saat ini, dan feedback dalam format JSON.
3. **`index.php?action=api_save_grade`**:
   - Menerima `id`, `nilai`, dan `feedback` via POST.
   - Memperbarui tabel MySQL `pengumpulan`.
   - Mengembalikan respon sukses JSON `{ "success": true, "nilai": X, "feedback": Y }`.

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Implementasi Endpoint API Backend pada `index.php`**
  - [x] Buat handler `api_submissions_poll` (real-time polling data baru).
  - [x] Buat handler `api_preview_submission` (pembacaan isi berkas SQL aman).
  - [x] Buat handler `api_save_grade` (penyimpanan nilai via AJAX).
- [x] **Fase 2: Desain Komponen Modal Preview & Penilaian pada `admin_dashboard.php`**
  - [x] Bangun struktur HTML/CSS modal dialog dengan wadah kode `<pre><code>` yang dapat di-scroll dan form input nilai terintegrasi.
  - [x] Tambahkan tombol aksi `👁️ Pratinjau & Nilai` pada setiap baris tabel.
- [x] **Fase 3: Integrasi Auto-Update, Notifikasi Toast, & Audio Chime**
  - [x] Implementasikan script polling 3.5 detik dengan *glow animation* pada baris baru.
  - [x] Pasang generator bunyi *chime* Web Audio API dan tombol toggle Mute/Unmute.
  - [x] Hubungkan fungsi AJAX simpan nilai agar modal langsung memperbarui tampilan tabel secara instan.
- [x] **Fase 4: Verifikasi & Pengujian Fungsional Menyeluruh**
  - [x] Uji pembukaan modal preview pada berkas SQL siswa nyata (contoh: jawaban Fahrizal, Rifqi, dsb).
  - [x] Uji pengisian nilai dan feedback melalui modal serta verifikasi perubahannya di database.
  - [x] Uji pengiriman jawaban baru oleh siswa dan amati pembaruan otomatis tabel, bunyi audio, dan munculnya notifikasi toast.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Keamanan Pembacaan Berkas (Directory Traversal)** | Akses berkas di luar folder `src/uploads/` | Nama berkas disanitasi menggunakan `basename()` dan diverifikasi berada di dalam direktori `src/uploads/`. |
| **Ukuran Berkas Sangat Besar** | Browser hang jika membaca file ratusan megabyte | Dibatasi pembacaan maksimal 1 MB teks kode SQL, dengan fallback tombol unduh jika file melampaui batas wajar. |
| **Konflik Polling dengan Input Nilai** | Baris tabel ter-overwrite saat guru sedang mengetik nilai | Polling hanya menyisipkan (*prepend*) baris baru yang belum ada (`WHERE id > :last_id`) tanpa me-reset baris yang sedang diedit. |
