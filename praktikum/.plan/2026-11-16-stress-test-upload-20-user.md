# Rencana Pengujian: Stress Test Unggah Berkas Jawaban (20 Pengguna Bersamaan)

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target Pengujian:** `index.php?action=submit_jawaban`, `src/uploads/`, tabel MySQL `pengumpulan`  
**Tujuan Utama:** Menguji ketahanan, konkurensi, dan integritas sistem saat 20 siswa mengunggah berkas jawaban praktikum (.sql) secara bersamaan (simultan) tanpa menimbulkan error *lock*, *timeout*, atau kehilangan data.

---

## 1. Analisis Kebutuhan & Ruang Lingkup Pengujian

1. **Skenario Pengujian (Concurrency Load Test)**:
   - 20 akun siswa aktif (`USER_BENCH_01` s.d. `USER_BENCH_20`) disiapkan pada kelas `XII-PPLG`.
   - Mengambil target soal praktikum berbasis unggah berkas SQL: Soal ID 9 (*Praktikum 1 - Basis Data SQL: Nusantara Cargo*, `tipe_pengumpulan = 'file_sql'`).
   - Setiap pengguna melakukan autentikasi sesi dan secara paralel mengirimkan *payload* `multipart/form-data` berisi berkas SQL jawaban, catatan siswa, dan telemetri perangkat secara simultan.
2. **Metrik Keberhasilan (Success Criteria)**:
   - **Tingkat Keberhasilan (Success Rate)**: 20 dari 20 permintaan (100%) berstatus HTTP `302 Found` (redirect ke dashboard dengan flash message sukses).
   - **Integritas Berkas Fisik**: 20 berkas fisik tersimpan utuh di direktori `src/uploads/` dengan format nama unik `JWB_9_<NIS>_<timestamp>.sql`.
   - **Integritas Basis Data**: 20 baris data tersimpan di tabel MySQL `pengumpulan` tanpa ada duplikasi konflik atau kegagalan relasi foreign key.
   - **Zero Lock Exception**: Bebas dari segala bentuk error `database is locked` atau *general error 5*.

---

## 2. Kebutuhan Sistem & Dependensi

- Ekstensi PHP `curl` (multi-curl: `curl_multi_init()`) untuk menjalankan 20 HTTP request non-blocking secara paralel.
- Web server Apache XAMPP (`http://127.0.0.1`).
- Basis data MySQL `db_lab_praktikum` (engine InnoDB).
- Izin tulis pada direktori `src/uploads/`.

---

## 3. Rancangan Teknis & Struktur Skrip Pengujian

```
c:\xampp\htdocs\
├── stress_test_upload.php         # Skrip benchmark multi-curl 20 upload paralel (sementara)
├── src/
│   ├── uploads/                   # Target penyimpanan berkas jawaban fisik
│   └── config/database.php        # Koneksi basis data MySQL
```

### Mekanisme Eksekusi:
1. **Inisialisasi**:
   - Skrip membuat 20 akun siswa dummy di MySQL dengan password ter-hash.
   - Skrip menyiapkan 20 berkas SQL unik temporer untuk diunggah.
2. **Simulasi Login & Cookie Jar**:
   - Mengambil cookie sesi (`PHPSESSID`) untuk masing-masing dari 20 akun melalui POST ke `index.php?action=login`.
3. **Simultaneous Parallel Dispatch (`curl_multi`)**:
   - Membuka 20 *handle* cURL `POST` `index.php?action=submit_jawaban` dengan berkas SQL terlampir.
   - Menjalankan ke-20 transfer secara bersamaan dalam satu loop `curl_multi_exec()`.
4. **Analisis & Pelaporan**:
   - Mencatat waktu eksekusi (total response time, throughput).
   - Memeriksa database `pengumpulan` dan isi folder `src/uploads/`.
5. **Pembersihan Data (Cleanup)**:
   - Opsi membersihkan 20 akun uji coba dan 20 berkas hasil tes agar tidak mengotori repositori lab.

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Pembuatan Skrip Pengujian Konkurensi (`stress_test_upload.php`)**
  - Buat skrip yang mengautomasi pembuatan 20 akun, autentikasi sesi paralel, dan pengiriman 20 unggahan serentak via `curl_multi`.
- [x] **Fase 2: Eksekusi Stress Test 20 Pengguna Simultan**
  - Jalankan skrip melalui terminal dan catat metrik respons HTTP serta waktu eksekusi.
- [x] **Fase 3: Verifikasi Konsistensi Data & Berkas Fisik**
  - Hitung jumlah baris di tabel `pengumpulan` (`COUNT(*) == 20`).
  - Verifikasi eksistensi dan ukuran 20 berkas di `src/uploads/`.
  - Pastikan tidak ada log error fatal di Apache / PHP.
- [x] **Fase 4: Pembersihan Lingkungan Pengujian**
  - Hapus 20 data pengumpulan uji coba dan berkas fisiknya, hapus 20 akun uji coba, serta hapus skrip benchmark.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Apache Worker Exhaustion** | Request antre jika worker Apache terlalu sedikit | Konfigurasi default XAMPP mendukung hingga 150-250 concurrent threads, sangat aman untuk 20 koneksi. |
| **Batas Upload PHP (`upload_max_filesize`)** | Berkas ditolak server | Ukuran berkas uji coba dibuat realistis (~5 KB SQL per berkas), jauh di bawah batas default 2 MB / 40 MB. |
| **Pencemaran Data Nyata Siswa** | Data pengujian tercampur dengan siswa asli | Menggunakan prefix NIS unik `BENCH_` sehingga mudah diidentifikasi dan dibersihkan kembali setelah pengujian selesai. |
