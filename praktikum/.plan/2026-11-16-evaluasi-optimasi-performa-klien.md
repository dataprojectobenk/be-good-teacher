# Rencana Evaluasi & Optimasi Performa: Akses Lambat pada Browser Klien

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:** `src/helpers/telemetry.php`, `src/views/layout_footer.php`, `index.php`  
**Akar Masalah Utama:** Fungsi `getClientHostname($ip)` mengeksekusi fungsi `@gethostbyaddr($ip)` (Reverse DNS PTR Lookup) pada setiap IP jaringan lokal (LAN). Karena router/DNS lokal lab tidak menyediakan PTR record, sistem mengalami *network timeout* sebesar **~4.93 detik per pemanggilan**. Pada setiap halaman web, fungsi ini dipanggil 2 kali (`layout_header.php` dan `layout_footer.php`), menyebabkan waktu tunggu beku (*blocking freeze*) selama **~10 detik** bagi setiap browser klien di jaringan lab.

---

## 1. Analisis Akar Masalah (Root Cause Analysis)

1. **DNS PTR Lookup Timeout pada Lingkungan LAN**:
   - Di `src/helpers/telemetry.php` baris 34:
     ```php
     $hostname = @gethostbyaddr($ip);
     ```
   - Pengujian terukur menunjukkan bahwa kueri `gethostbyaddr('192.168.1.100')` memakan waktu **4.9309 detik** sebelum gagal dan mengembalikan IP kembali.
   - Pada akses server lokal (`127.0.0.1`), terdapat *bypass* cepat sehingga di komputer server terasa instan. Namun, ketika dibuka dari komputer siswa/klien lab melalui IP LAN, terjadi jeda 5 detik.
2. **Pemanggilan Redundan Ganda pada Setiap Page Load**:
   - `src/views/layout_header.php` baris 6 memanggil `getClientHostname($clientIp)` (menunggu 4.93s).
   - `src/views/layout_footer.php` baris 4 memanggil `getClientHostname($ip)` kembali (menunggu 4.93s).
   - Total waktu pembukaan halaman di browser klien: **9.86 detik s.d. 10 detik** hanya untuk menunggu DNS reverse lookup yang sia-sia!
3. **Pemanggilan Tambahan pada Saat Aksi Form**:
   - Pada aksi login (`index.php:107`), registrasi (`index.php:62`), dan submit jawaban (`index.php:175, 229`), fungsi ini dipanggil kembali sehingga setiap submit tombol terasa lambat/berputar lama.

---

## 2. Tujuan & Ruang Lingkup Perbaikan

- **Mengeliminasi DNS PTR Lookup Blocking**:
  - Mengganti logika `getClientHostname($ip)` agar tidak menjalankan `gethostbyaddr()` yang memblokir proses.
  - Menggunakan identifikasi cepat berbasis subnet/segmen IP (`PC-LAN-<segmen_terakhir>`), nama host yang dikirim klien jika ada, atau memanfaatkan memori statis / cache session (eksekusi **0.00001 detik**).
- **Menghapus Pemanggilan Redundan di Footer**:
  - `src/views/layout_footer.php` langsung menggunakan variabel `$clientIp` dan `$clientHost` yang sudah didefinisikan di `layout_header.php`.
- **Target Hasil**:
  - Waktu muat halaman di browser klien terpangkas dari **~10 detik** menjadi **< 0.05 detik (Instan / 200x lebih cepat)**.

---

## 3. Kebutuhan Sistem & Dependensi

- Tidak memerlukan pustaka eksternal.
- Kompatibel penuh dengan PHP 8.2 dan Apache di jaringan lokal XAMPP.

---

## 4. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── src/
│   ├── helpers/
│   │   └── telemetry.php          # Optimasi getClientHostname (non-blocking 0ms)
│   └── views/
│       └── layout_footer.php      # Reuse variabel $clientIp & $clientHost (tanpa panggil ulang)
└── index.php                      # Memastikan telemetri instan tanpa delay
```

### Rincian Perubahan Logika:
1. **`src/helpers/telemetry.php`**:
   ```php
   function getClientHostname(string $ip): string {
       static $cache = [];
       if (isset($cache[$ip])) {
           return $cache[$ip];
       }

       if ($ip === '127.0.0.1' || $ip === '::1') {
           return $cache[$ip] = 'localhost (' . (gethostname() ?: 'HOST-SERVER') . ')';
       }

       // Hindari gethostbyaddr() karena timeout 5 detik pada jaringan lab tanpa reverse DNS
       $cleanIp = str_replace(['.', ':'], '-', $ip);
       $lastOctet = substr(strrchr($ip, '.') ?: $ip, 1);
       return $cache[$ip] = 'PC-LAN-' . ($lastOctet ?: substr($cleanIp, -4));
   }
   ```
2. **`src/views/layout_footer.php`**:
   ```php
   // Gunakan variabel yang telah disiapkan layout_header.php
   $ip = $clientIp ?? getClientIP();
   $host = $clientHost ?? 'PC-LAN';
   ```

---

## 5. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Optimasi Non-Blocking pada `getClientHostname()` (`src/helpers/telemetry.php`)**
  - Hapus pemanggilan lambat `@gethostbyaddr()` dan terapkan *in-memory static caching*.
- [x] **Fase 2: Pembersihan Pemanggilan Redundan di `src/views/layout_footer.php`**
  - Manfaatkan variabel `$clientIp` dan `$clientHost` dari header.
- [x] **Fase 3: Verifikasi Pengujian Latensi Klien LAN**
  - Ukur waktu eksekusi skrip untuk simulasi IP LAN klien (`192.168.1.xxx`).
  - Uji kecepatan render halaman melalui simulasi HTTP header `X-Forwarded-For`.
  - Pastikan waktu respons turun drastis dari 10 detik menjadi di bawah 50 milidetik.

---

## 6. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Identitas Nama Komputer Siswa** | Nama komputer fisik tidak terbaca jika DNS PTR mati | Nama PC digantikan oleh format konsisten `PC-LAN-<oktet_terakhir>` (misal `PC-LAN-105` untuk IP `192.168.1.105`), yang justru jauh lebih rapi, konsisten, dan mudah dipetakan oleh pengawas lab. |
