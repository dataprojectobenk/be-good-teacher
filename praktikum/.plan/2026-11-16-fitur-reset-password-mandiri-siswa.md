# Rencana Implementasi: Fitur Reset Password Mandiri Siswa

**Tanggal:** 2026-11-16  
**Status:** Selesai Diimplementasikan  
**Target File:** `src/views/reset_password.php`, `src/views/login.php`, `index.php`  
**Tujuan Utama:** Menyediakan fitur pemulihan / reset kata sandi secara mandiri bagi siswa yang lupa kata sandi tanpa bergantung pada server email eksternal (lingkungan jaringan lokal/lab offline), menggunakan verifikasi identitas resmi (NIS, Nama Lengkap, dan Kelas).

---

## 1. Analisis Kebutuhan & Ruang Lingkup

1. **Konteks Lingkungan Lab Mandiri**:
   - Komputer lab berada dalam jaringan lokal tanpa dependensi SMTP/email internet.
   - Pemulihan kata sandi harus dapat dilakukan secara mandiri oleh siswa melalui verifikasi multi-faktor identitas siswa yang tersimpan di sistem.
2. **Kriteria Verifikasi Identitas**:
   - Siswa memasukkan:
     1. **Nomor Induk Siswa (NIS)**
     2. **Nama Lengkap Siswa** (diverifikasi *case-insensitive* terhadap basis data)
     3. **Pilihan Kelas** (sesuai kelas terdaftar: `XI-PPLG`, `XII-PPLG`, atau kelas siswa sebelumnya)
     4. **Kata Sandi Baru** & **Konfirmasi Kata Sandi Baru** (minimal 6 karakter)
3. **Batasan Keamanan (Security Scope)**:
   - Fitur reset mandiri **HANYA BERLAKU** untuk akun dengan peran `role = 'siswa'`.
   - Akun dengan peran `role = 'admin'` **DITOLAK KERAS** untuk direset melalui formulir ini guna mencegah pengambilalihan akun guru/pengawas lab.
   - Menggunakan hashing `password_hash(..., PASSWORD_BCRYPT)`.

---

## 2. Kebutuhan Sistem & Dependensi

- Basis data MySQL `db_lab_praktikum` (tabel `users`).
- PHP 8.2+ dengan fungsi bawaan `password_hash()` dan `password_verify()`.

---

## 3. Rancangan Teknis & Struktur Berkas

```
c:\xampp\htdocs\
├── index.php                          # Penanganan aksi 'reset_password' & routing halaman
└── src/
    └── views/
        ├── login.php                  # Penambahan tautan "Lupa Kata Sandi?"
        └── reset_password.php         # Berkas view formulir verifikasi & input sandi baru
```

### Rincian Alur Logika:
1. **Formulir (`src/views/reset_password.php`)**:
   - Meminta input NIS, Nama Lengkap, Pilihan Kelas, Kata Sandi Baru, dan Ulangi Kata Sandi Baru.
   - Menampilkan kartu UI seragam dengan tema sistem lab praktikum.
2. **Pemrosesan Backend (`index.php?action=reset_password`)**:
   - Validasi kelengkapan form, kecocokan kedua sandi baru, dan panjang minimal (6 karakter).
   - Kueri data siswa: `SELECT id, nis, nama, kelas, role FROM users WHERE nis = :nis LIMIT 1`.
   - Jika akun tidak ditemukan atau `role !== 'siswa'`: berikan pesan error *"Data verifikasi siswa tidak ditemukan atau tidak valid."*
   - Jika nama (dengan `strcasecmp(trim($user['nama']), trim($inputNama)) !== 0`) atau kelas tidak cocok: tolak dengan pesan error.
   - Jika valid: eksekusi `UPDATE users SET password = :password WHERE id = :id`.
   - Berikan flash message sukses dan alihkan ke halaman login (`index.php?page=login`).

---

## 4. Tahapan Eksekusi Bertahap (Checklist)

- [x] **Fase 1: Pembuatan Tampilan Formulir Reset Password (`src/views/reset_password.php`)**
  - Buat formulir yang memuat input NIS, Nama Lengkap, Pilihan Kelas, Kata Sandi Baru, dan Konfirmasi Kata Sandi.
  - Tautkan kembali ke halaman login.
- [x] **Fase 2: Penambahan Tautan pada Halaman Login (`src/views/login.php`)**
  - Tambahkan link akses "Lupa Kata Sandi? Reset di sini" pada kartu login.
- [x] **Fase 3: Implementasi Routing & Handler Aksi pada `index.php`**
  - Tambahkan `case 'reset_password':` pada switch `$page`.
  - Tambahkan penanganan `if ($action === 'reset_password' && $_SERVER['REQUEST_METHOD'] === 'POST')` dengan validasi multi-faktor dan proteksi non-admin.
- [x] **Fase 4: Verifikasi & Pengujian Fungsional**
  - Uji penolakan jika nama atau kelas salah.
  - Uji penolakan jika mencoba mereset akun `admin`.
  - Uji sukses reset pada akun siswa, lalu lakukan pengujian login dengan kata sandi baru.

---

## 5. Analisis Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| **Siswa Mereset Akun Siswa Lain** | Akses akun siswa lain terganggu | Verifikasi wajib mencocokkan kombinasi 3 field identitas: NIS unik, Nama Lengkap persis (*case-insensitive*), dan Kelas. |
| **Eksploitasi Akun Admin** | Akun Guru/Admin di-reset oleh pihak tidak berhak | Pemeriksaan eksplisit `if ($user['role'] !== 'siswa')` yang langsung menolak eksekusi jika target bukan siswa. |
| **Siswa Migrasi dengan Kelas Lama** | Siswa yang sebelumnya terdaftar dengan nama kelas lama (misal `XII RPL 2`) kesulitan memilih kelas | Opsi kelas pada form reset menyertakan kelas standar saat ini (`XI-PPLG`, `XII-PPLG`) serta opsi kelas angkatan sebelumnya (`XII RPL 1`, `XII RPL 2`). |
