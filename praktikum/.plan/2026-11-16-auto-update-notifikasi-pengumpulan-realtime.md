# Rencana Implementasi: Auto-Update Real-time & Notifikasi Pengumpulan Tugas Siswa

**Tanggal:** 2026-11-16  
**Status:** Menunggu Persetujuan Pengguna  
**Target File:** `index.php`, `src/views/admin_dashboard.php`  
**Tujuan Utama:** Mengembangkan fitur pembaruan otomatis (*live auto-refresh*) pada tabel rekapitulasi jawaban siswa di Panel Guru tanpa perlu me-refresh halaman browser manual, serta memunculkan notifikasi interaktif (visual toast, audio chime, dan highlight baris) setiap kali ada siswa yang baru mengirimkan jawaban praktikum.

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Kondisi Eksisting**:
   - Saat ini, Panel Guru (`admin_dashboard.php`) bersifat statis. Guru pengawas harus melakukan *refresh* manual (`F5`) untuk mengetahui apakah ada siswa yang baru saja mengumpulkan tugas praktikum.
   - Tidak ada indikator audio maupun visual ketika ada pengumpulan baru yang masuk.
2. **Kebutuhan Fitur Baru**:
   - **Endpoint API Real-time Polling (`index.php?action=api_submissions_poll`)**:
     - Mengembalikan data pengumpulan terbaru dalam format JSON (jumlah total, daftar pengumpulan baru sejak `last_id`, dan status duplikasi perangkat).
   - **Auto-Update Tanpa Reload Halaman (Live Update)**:
     - Panel Guru melakukan polling ringan (setiap 3–4 detik) secara *asynchronous* (`fetch`).
     - Jika terdeteksi ada pengumpulan baru, baris tabel baru otomatis dimasukkan ke bagian atas tabel dengan efek animasi *glow highlight*.
     - Kartu statistik ("Jawaban Terkumpul" dan "Deteksi PC Duplikat") otomatis diperbarui angkanya secara instan.
   - **Notifikasi Multi-Sensori Pengawas**:
     1. **Toast Notification Pop-up**: Notifikasi elegan di pojok kanan atas layar menampilkan nama siswa, NIS, kelas, dan judul soal yang dikumpulkan.
     2. **Audio Chime Lembut (Web Audio API)**: Bunyi notifikasi sintetis menggunakan `AudioContext` browser (mandiri tanpa butuh berkas MP3 eksternal, 100% andal di jaringan lab offline).
     3. **Saklar Kontrol Suara**: Guru dapat mengaktifkan/menonaktifkan (*Mute / Unmute*) bunyi notifikasi sesuai preferensi lab.

---

## 2. Kebutuhan Sistem & Dependensi

- Basis data MySQL `db_lab_praktikum` (tabel `pengumpulan`, `users`, `soal`).
- JavaScript modern (ES6+ `fetch`, `DOM manipulation`, `Web Audio API`).
- Tanpa dependensi pihak ketiga atau library eksternal (100% mandiri dan ringan).

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                      # Endpoint API: action=api_submissions_poll (JSON response)
└── src/
    └── views/
        └── admin_dashboard.php    # Polling script, kontainer Toast Notification, & Web Audio
```

### Rincian Endpoint API Backend (`index.php?action=api_submissions_poll`):
- Memeriksa hak akses admin (`requireAdmin()`).
- Menerima parameter `last_id` (ID pengumpulan tertinggi yang sedang ditampilkan) dan `filter_soal` (jika guru sedang memfilter soal tertentu).
- Menghitung statistik total pengumpulan dan alert duplikasi telemetri.
- Mengambil entri baru (`WHERE p.id > :last_id`).
- Mengembalikan JSON:
  ```json
  {
    "success": true,
    "total_pengumpulan": 16,
    "total_alert_duplikat": 0,
    "new_count": 1,
    "latest_id": 36,
    "items": [
      {
        "id": 36,
        "nis": "242507008",
        "nama": "Fahrizal Rakha Gunawan",
        "kelas": "XII RPL 2",
        "judul_soal": "Praktikum 1 - Basis Data",
        "tipe_jawaban": "file_sql",
        "jawaban_konten": "JWB_9_242507008.sql",
        "catatan_siswa": "",
        "client_ip": "192.168.1.105",
        "client_hostname": "PC-LAN-105",
        "waktu_kirim": "2026-11-16 11:15:30",
        "is_duplicate": false
      }
    ]
  }
  ```

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [ ] **Fase 1: Pembuatan Endpoint API Polling pada `index.php`**
  - Implementasikan aksi `api_submissions_poll` yang merespons data JSON pengumpulan baru dan metrik statistik.
- [ ] **Fase 2: Integrasi Komponen Notifikasi Visual & Audio pada `admin_dashboard.php`**
  - Buat elemen kontainer Toast Notifikasi melayang dengan gaya UI modern.
  - Tambahkan generator audio sintetis *chime* berbasis `AudioContext` serta saklar mute/unmute.
- [ ] **Fase 3: Implementasi Script Real-Time Auto-Update pada `admin_dashboard.php`**
  - Tulis skrip AJAX polling (interval 3.5 detik).
  - Bangun logika injeksi baris tabel baru ke DOM dengan animasi *fade-in highlight*.
  - Sinkronkan angka statistik ringkas secara dinamis.
- [ ] **Fase 4: Verifikasi & Pengujian Fungsional**
  - Uji simulasi siswa mengirimkan jawaban praktikum saat halaman Panel Guru sedang dibuka.
  - Pastikan notifikasi toast muncul seketika, audio berbunyi, baris tabel ter-update otomatis, dan angka statistik bertambah tanpa perlu me-refresh browser.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Beban Server dari Polling Berulang** | CPU server meningkat jika polling terlalu agresif | Interval polling diatur pada 3.5–4 detik dengan kueri ringan (`WHERE id > :last_id` memanfaatkan primary key index). Kueri ini memakan waktu < 1 ms di MySQL InnoDB. |
| **Kebijakan Browser Autoplay Audio** | Suara notifikasi terblokir sebelum ada interaksi pengguna | Audio chime dirancang aktif setelah interaksi pertama pengguna (klik pertama di halaman atau saat menekan toggle audio). |
