# LEMBAR SOAL UJIAN PRAKTIKUM 1 (PEMROGRAMAN WEB LARAVEL)

**Mata Pelajaran:** Pemrograman Web dan Perangkat Bergerak  
**Kelas:** XII RPL / PPLG  
**Materi Uji:** Otentikasi Pengguna, Validasi Request, dan Proteksi Route Dashboard (Framework Laravel)  
**Studi Kasus:** Portal Layanan Pasien "Apotek FarmaMedika"  
**Alokasi Waktu:** 90 Menit

---

## I. INSTRUKSI UMUM

1. **Unduh Starter Pack Laravel (base_app.zip):** Untuk mempermudah pengerjaan dan agar siswa tidak perlu mengunduh atau menginstal framework Laravel secara manual dari internet, silakan unduh berkas template dasar:
   👉 **[Unduh Starter Laravel (base_app.zip)](index.php?action=download_base_app)** *(atau via tautan langsung: `xii/base_app.zip`)*.  
   Ekstrak arsip zip tersebut ke folder server lokal Anda (`c:\xampp\htdocs\` atau direktori kerja) dan ubah nama folder proyek menjadi: `PRAKTIKUM1_LARAVEL_NIS_NAMA`.
2. Salin template tampilan Blade yang telah disediakan pada bagian **IV. KODE RAW BLADE TEMPLATE** di bawah ini (`login.blade.php`, `register.blade.php`, dan `dashboard.blade.php`).
3. Analisis formulir Blade yang disediakan. Desain tampilan tidak boleh rusak.
4. Hubungkan konfigurasi environment `.env` ke basis data MySQL `db_apotek_pasien`.
5. Pastikan seluruh alur sistem otentikasi, validasi, dan proteksi rute berjalan sesuai spesifikasi kebutuhan yang ditentukan.
6. Hubungkan direktori proyek lokal Anda ke repositori GitHub pribadi yang berstatus **Public** dengan format tautan yang valid.
7. **Kewajiban Git Commit & Push Berkala (Setiap 30 Menit):** Siswa **WAJIB** melakukan `git commit` dan `git push` ke repositori GitHub secara berkala **minimal setiap 30 menit sekali** sepanjang durasi ujian (90 menit). Siswa dibebaskan untuk mengatur urutan pengerjaan fitur secara alami; setiap interval 30 menit, lakukan commit dan push apa pun progres perubahan kode yang telah Anda selesaikan.
8. **Komentar/Pesan Commit yang Relevan (Meaningful Commit Messages):** Setiap commit **WAJIB** menyertakan pesan/komentar yang jelas, informatif, dan mendeskripsikan secara akurat perubahan kode yang sebenarnya telah Anda kerjakan pada rentang waktu tersebut. DILARANG menggunakan komentar pemalas atau tidak jelas (seperti: *"update"*, *"commit"*, *"test"*, *"a"*, dsb.).

---

## II. DATA DUMMY

Import skrip basis data `database_pasien.sql` ke dalam MySQL.

**Nama Database:** `db_apotek_pasien`  
**Nama Tabel:** `tb_pasien`

### Struktur Tabel `tb_pasien`:

| Nama Kolom      | Tipe Data                         | Keterangan               |
| :-------------- | :-------------------------------- | :----------------------- |
| `id_pasien`     | INT (Auto Increment, Primary Key) | ID unik pasien           |
| `nama_lengkap`  | VARCHAR(100)                      | Nama lengkap pasien      |
| `email`         | VARCHAR(100)                      | Alamat email (Unique)    |
| `no_telepon`    | VARCHAR(20)                       | Nomor telepon / WhatsApp |
| `alamat`        | TEXT                              | Alamat domisili          |
| `password`      | VARCHAR(255)                      | Kata sandi akun          |
| `status_pasien` | ENUM('Aktif', 'Nonaktif')         | Status akun pasien       |
| `created_at`    | TIMESTAMP                         | Waktu pendaftaran        |

### Data Pengujian Login:

| Email            | Password    | Status Pasien | Hasil yang Diharapkan Saat Login                    |
| :--------------- | :---------- | :------------ | :-------------------------------------------------- |
| `rina@gmail.com` | `sehat123`  | Aktif         | Berhasil masuk ke dashboard portal pasien           |
| `dani@gmail.com` | `medika123` | Aktif         | Berhasil masuk ke dashboard portal pasien           |
| `eko@gmail.com`  | `apotek`    | Nonaktif      | Ditolak (muncul notifikasi akun berstatus nonaktif) |

---

## III. PETUNJUK SOAL

### 1. Pendaftaran Pasien Baru (`register.blade.php`)

Membuat halaman registrasi pasien mengacu pada "Formulir Pendaftaran Pasien Baru".

- Pastikan semua field wajib diisi, apabila ada field yang kosong muncul pesan error validasi _"Semua field wajib diisi !"_.
- Kata sandi dan ulangi sandi harus sesuai, apabila tidak sesuai muncul error validasi _"Konfirmasi kata sandi tidak cocok !"_.
- Kata sandi minimal berjumlah 6 karakter dan wajib mengandung kombinasi angka. Apabila tidak sesuai ketentuan, muncul error validasi _"Kata sandi minimal 6 karakter dan wajib mengandung angka !"_.
- Email yang didaftarkan tidak boleh sama dengan email yang sudah ada di database. Apabila email sudah terdaftar, muncul error validasi _"Email sudah terdaftar, gunakan email lain !"_.
- Jika pendaftaran berhasil:
  - Data pasien baru tersimpan ke dalam database dengan status keanggotaan default **Aktif**.
  - Pengguna dialihkan ke halaman login disertai pesan notifikasi _"Pendaftaran berhasil, silakan masuk ke akun Anda !"_.

---

### 2. Masuk ke Portal Apotek (`login.blade.php`)

Membuat form masuk aplikasi mengacu pada "Form Login Member".

- Pastikan semua field wajib diisi, apabila email atau kata sandi tidak sesuai muncul peringatan _"Email atau password yang anda masukkan tidak sesuai !"_.
- Jika akun yang dimasukkan memiliki status **Nonaktif**, maka proses login ditolak dan muncul peringatan _"Akun pasien Anda sedang dinonaktifkan !"_.
- Jika proses login berhasil:
  - Pengguna diarahkan ke rute halaman **Dashboard Portal Pasien** (`/dashboard`).
  - Sesi pengguna aktif tercatat di dalam sistem otentikasi.

---

### 3. Dashboard Pasien Terproteksi (`dashboard.blade.php`)

Membuat menu utama pasien mengacu pada antarmuka "Portal Pasien".

- **Proteksi Rute Halaman (Middleware Guard):**
  - Halaman dashboard tidak dapat dibuka sama sekali jika pengguna belum melakukan login.
  - Apabila pengguna yang belum terotentikasi mencoba mengakses URL `/dashboard`, sistem secara otomatis menolak akses dan mengarahkan kembali ke halaman login dengan pesan _"Silakan masuk terlebih dahulu !"_.
- **Tampilan Data Pasien:**
  - Memunculkan secara otomatis nama lengkap pasien, email, nomor telepon, alamat, dan status kepesertaan yang sedang aktif login.
  - Memunculkan secara otomatis daftar layanan konsultasi dan fasilitas apotek beserta penomoran urutnya pada antarmuka.
- **Keluar Sistem (Logout):**
  - Ketika tombol "Keluar" diklik, sesi otentikasi pengguna dihapus dan pengguna diarahkan kembali ke halaman login.

---

## IV. KODE RAW BLADE TEMPLATE (SIAP SALIN)

Siswa dapat langsung menyalin kode Blade template di bawah ini ke dalam direktori `resources/views/` proyek Laravel masing-masing.

### 1. Template Masuk (`resources/views/login.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk - Apotek FarmaMedika</title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }
      body {
        background-color: #f0fdf4;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
      }
      .auth-card {
        background-color: #ffffff;
        width: 100%;
        max-width: 430px;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(22, 101, 52, 0.1);
        border: 1px solid #bbf7d0;
      }
      .brand-header {
        text-align: center;
        margin-bottom: 25px;
      }
      .brand-header h2 {
        color: #166534;
        font-size: 24px;
        margin-bottom: 6px;
      }
      .brand-header p {
        color: #15803d;
        font-size: 14px;
      }
      .alert-box {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
        display: block;
      }
      .form-group {
        margin-bottom: 18px;
      }
      .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #166534;
      }
      .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        transition:
          border-color 0.2s,
          box-shadow 0.2s;
      }
      .form-control:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
      }
      .btn-submit {
        width: 100%;
        padding: 12px;
        background-color: #166534;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 10px;
      }
      .btn-submit:hover {
        background-color: #14532d;
      }
      .auth-footer {
        margin-top: 22px;
        text-align: center;
        font-size: 13px;
        color: #475569;
      }
      .auth-footer a {
        color: #166534;
        text-decoration: none;
        font-weight: 600;
      }
      .auth-footer a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <div class="auth-card">
      <div class="brand-header">
        <h2>Apotek FarmaMedika</h2>
        <p>Portal Layanan Obat & Pasien</p>
      </div>

      <div class="alert-box">
        <span>Pesan status login sistem ditampilkan di sini</span>
      </div>

      <form action="" method="POST">
        <div class="form-group">
          <label>Alamat Email Pasien</label>
          <input
            type="email"
            class="form-control"
            placeholder="nama@email.com"
            required
          />
        </div>

        <div class="form-group">
          <label>Kata Sandi</label>
          <input
            type="password"
            class="form-control"
            placeholder="Masukkan kata sandi akun"
            required
          />
        </div>

        <button type="submit" class="btn-submit">Masuk ke Portal Apotek</button>
      </form>

      <div class="auth-footer">
        Belum terdaftar sebagai pasien? <a href="register">Daftar akun baru</a>
      </div>
    </div>
  </body>
</html>
```

---

### 2. Template Pendaftaran Pasien (`resources/views/register.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftaran Pasien - Apotek FarmaMedika</title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }
      body {
        background-color: #f0fdf4;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 25px 20px;
      }
      .auth-card {
        background-color: #ffffff;
        width: 100%;
        max-width: 500px;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(22, 101, 52, 0.1);
        border: 1px solid #bbf7d0;
      }
      .brand-header {
        text-align: center;
        margin-bottom: 22px;
      }
      .brand-header h2 {
        color: #166534;
        font-size: 24px;
        margin-bottom: 6px;
      }
      .brand-header p {
        color: #15803d;
        font-size: 14px;
      }
      .alert-box {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        display: block;
      }
      .form-group {
        margin-bottom: 16px;
      }
      .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #166534;
      }
      .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        transition:
          border-color 0.2s,
          box-shadow 0.2s;
      }
      .form-control:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
      }
      .form-row {
        display: flex;
        gap: 12px;
      }
      .form-row .form-group {
        flex: 1;
      }
      .btn-submit {
        width: 100%;
        padding: 12px;
        background-color: #166534;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 10px;
      }
      .btn-submit:hover {
        background-color: #14532d;
      }
      .auth-footer {
        margin-top: 22px;
        text-align: center;
        font-size: 13px;
        color: #475569;
      }
      .auth-footer a {
        color: #166534;
        text-decoration: none;
        font-weight: 600;
      }
      .auth-footer a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <div class="auth-card">
      <div class="brand-header">
        <h2>Apotek FarmaMedika</h2>
        <p>Formulir Pendaftaran Pasien Baru</p>
      </div>

      <div class="alert-box">
        <span>Pesan status pendaftaran ditampilkan di sini</span>
      </div>

      <form action="" method="POST">
        <div class="form-group">
          <label>Nama Lengkap Pasien</label>
          <input
            type="text"
            class="form-control"
            placeholder="Contoh: Rina Safitri"
            required
          />
        </div>

        <div class="form-group">
          <label>Alamat Email</label>
          <input
            type="email"
            class="form-control"
            placeholder="nama@email.com"
            required
          />
        </div>

        <div class="form-group">
          <label>Nomor Telepon / WhatsApp</label>
          <input
            type="tel"
            class="form-control"
            placeholder="081234567890"
            required
          />
        </div>

        <div class="form-group">
          <label>Alamat Domisili</label>
          <textarea
            class="form-control"
            rows="2"
            placeholder="Alamat lengkap tempat tinggal"
            required
          ></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Kata Sandi</label>
            <input
              type="password"
              class="form-control"
              placeholder="Minimal 6 karakter"
              required
            />
          </div>
          <div class="form-group">
            <label>Ulangi Sandi</label>
            <input
              type="password"
              class="form-control"
              placeholder="Ketik ulang sandi"
              required
            />
          </div>
        </div>

        <button type="submit" class="btn-submit">Daftarkan Akun Pasien</button>
      </form>

      <div class="auth-footer">
        Sudah terdaftar sebelumnya? <a href="login">Masuk di sini</a>
      </div>
    </div>
  </body>
</html>
```

---

## V. KETENTUAN VERSION CONTROL (GIT & GITHUB) DAN STANDAR COMMIT

Ujian praktikum ini menggunakan sistem kendali versi Git untuk memantau progres pengerjaan bertahap, orisinalitas kode, dan kedisiplinan alur kerja rekayasa perangkat lunak:

### 1. Jadwal & Ritme Commit-Push Wajib (Per 30 Menit)

Durasi ujian praktikum adalah 90 menit. Siswa wajib membagi pekerjaan ke dalam 3 tahapan commit berkala:

- **Commit 1 (Menit ke-0 s/d ke-30):**
  - **Fokus Pengerjaan:** Inisialisasi proyek, konfigurasi `.env`, import database `db_apotek_pasien`, serta perancangan route dan Controller pendaftaran akun pasien (`register.blade.php`).
  - **Contoh Pesan Commit:** `feat(register): implementasi formulir pendaftaran pasien baru dan validasi request database`
- **Commit 2 (Menit ke-30 s/d ke-60):**
  - **Fokus Pengerjaan:** Implementasi halaman login pasien (`login.blade.php`), otentikasi akun, validasi status kepesertaan aktif/nonaktif, dan manajemen sesi pengguna.
  - **Contoh Pesan Commit:** `feat(login): implementasi otentikasi login pasien, pengecekan status aktif, dan pengelolaan session`
- **Commit 3 (Menit ke-60 s/d ke-90):**
  - **Fokus Pengerjaan:** Implementasi proteksi rute middleware pada dashboard (`dashboard.blade.php`), pemunculan data profil & daftar layanan apotek, serta fungsi logout.
  - **Contoh Pesan Commit:** `feat(dashboard): proteksi route middleware dashboard, render data pasien, dan fitur logout`

### 2. Standar Format Pesan Commit (Meaningful Commit Messages)

Setiap pesan commit wajib ditulis dengan format terstruktur yang mencerminkan komponen yang diubah secara jelas dan relevan:

- Format: `feat(<modul>): <penjelasan singkat fitur/perubahan yang dibuat>`
- DILARANG melakukan commit sekaligus di menit-menit akhir ujian (_single monolithic commit_).
- DILARANG menggunakan komentar pemalas seperti _"update"_, _"commit"_, _"test"_, _"a"_, dsb.

### 3. Kriteria Audit Version Control oleh Penguji

- Guru penguji akan memeriksa riwayat log commit (`git log --stat`) pada tautan GitHub yang dikumpulkan di portal lab.
- Siswa yang tidak mematuhi interval push 30 menit atau menggunakan komentar commit yang tidak deskriptif akan dikenakan pemotongan nilai kedisiplinan teknis.

---

### 3. Template Dashboard Portal Pasien (`resources/views/dashboard.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portal Pasien - Apotek FarmaMedika</title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }
      body {
        background-color: #f8fafc;
        color: #1e293b;
      }
      .navbar {
        background-color: #166534;
        padding: 16px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      }
      .navbar-brand h2 {
        font-size: 20px;
        font-weight: 700;
      }
      .navbar-user {
        display: flex;
        align-items: center;
        gap: 20px;
      }
      .navbar-user span {
        font-size: 14px;
        color: #bbf7d0;
      }
      .btn-logout {
        background-color: #dc2626;
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
      }
      .btn-logout:hover {
        background-color: #b91c1c;
      }
      .container {
        max-width: 960px;
        margin: 35px auto;
        padding: 0 20px;
      }
      .welcome-banner {
        background: linear-gradient(135deg, #15803d, #166534);
        color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 6px 18px rgba(22, 101, 52, 0.15);
      }
      .welcome-banner h1 {
        font-size: 26px;
        margin-bottom: 8px;
      }
      .welcome-banner p {
        color: #dcfce7;
        font-size: 14px;
      }
      .grid-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
      }
      .info-card {
        background: #ffffff;
        padding: 24px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
      }
      .info-card h4 {
        color: #15803d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 12px;
      }
      .info-card .card-value {
        font-size: 22px;
        font-weight: 700;
        color: #166534;
        margin-bottom: 6px;
      }
      .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background-color: #dcfce7;
        color: #166534;
      }
      .section-box {
        background: #ffffff;
        padding: 26px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
      }
      .section-box h3 {
        color: #166534;
        font-size: 18px;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
      }
      .service-list {
        list-style: none;
      }
      .service-item {
        padding: 12px 0;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        color: #334155;
      }
      .service-item:last-child {
        border-bottom: none;
      }
      .service-number {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: #dcfce7;
        color: #166534;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
      }
    </style>
  </head>
  <body>
    <nav class="navbar">
      <div class="navbar-brand">
        <h2>Apotek FarmaMedika</h2>
      </div>
      <div class="navbar-user">
        <span>Pasien: <strong>Rina Safitri</strong> (rina@gmail.com)</span>
        <form action="logout" method="POST" style="display: inline;">
          <button type="submit" class="btn-logout">Keluar</button>
        </form>
      </div>
    </nav>

    <div class="container">
      <div class="welcome-banner">
        <h1>Selamat Datang di Portal Pasien, Rina Safitri!</h1>
        <p>
          Layanan tebus resep digital dan informasi obat terintegrasi Apotek
          FarmaMedika.
        </p>
      </div>

      <div class="grid-info">
        <div class="info-card">
          <h4>Status Kepesertaan</h4>
          <div class="card-value">Aktif</div>
          <span class="badge-status">Pasien Terverifikasi</span>
        </div>
        <div class="info-card">
          <h4>Nomor Kontak Terdaftar</h4>
          <div class="card-value">081234567890</div>
          <p style="font-size: 12px; color: #64748b;">
            Alamat: Jl. Merdeka Sehat No. 10, Jakarta
          </p>
        </div>
      </div>

      <div class="section-box">
        <h3>Daftar Layanan Konsultasi & Layanan Pasien Aktif</h3>
        <ul class="service-list">
          <li class="service-item">
            <div class="service-number">1</div>
            <div>
              Konsultasi gratis penggunaan obat antibiotik bersama apoteker
              jaga.
            </div>
          </li>
          <li class="service-item">
            <div class="service-number">2</div>
            <div>
              Layanan pengantaran obat resep langsung ke alamat domisili dalam
              radius 5 km.
            </div>
          </li>
          <li class="service-item">
            <div class="service-number">3</div>
            <div>
              Pengingat otomatis jadwal minum obat dan isi ulang resep kronis.
            </div>
          </li>
          <li class="service-item">
            <div class="service-number">4</div>
            <div>
              Pemeriksaan tensi darah dan kadar gula darah gratis setiap akhir
              pekan.
            </div>
          </li>
        </ul>
      </div>
    </div>
  </body>
</html>
```

---

## V. KETENTUAN VERSION CONTROL (GIT & GITHUB)

Ujian praktikum ini menggunakan sistem kendali versi Git untuk memantau progres pengerjaan bertahap, orisinalitas kode, dan kedisiplinan alur kerja rekayasa perangkat lunak:

### 1. Aturan Commit & Push Periodik (Minimal Setiap 30 Menit)
* Sepanjang durasi ujian praktikum (90 menit), siswa **WAJIB** melakukan `git commit` dan `git push` ke repositori GitHub secara berkala **minimal setiap 30 menit sekali**.
* **Alur Pengerjaan Alami (Natural Progress):** Guru penguji **tidak membatasi atau menentukan** modul apa yang harus selesai pada menit tertentu. Kerjakan fitur-fitur praktikum secara alami sesuai urutan dan ritme pengerjaan Anda sendiri. Setiap kali interval 30 menit tercapai, segera lakukan commit dan push terhadap perubahan atau penambahan kode apa pun yang telah Anda capai saat itu.

### 2. Standar Komentar/Pesan Commit yang Relevan (Meaningful Commit Messages)
* Setiap pesan commit **WAJIB** menjelaskan secara jujur, jelas, dan akurat apa yang Anda ubah atau tambahkan pada rentang waktu tersebut.
* **DILARANG KERAS** menggunakan komentar pemalas atau asal-asalan (seperti: *"update"*, *"commit"*, *"test"*, *"tugas"*, *"a"*, *"fix"*, dsb.).
* Sesuaikan pesan commit dengan apa yang sebenarnya Anda kerjakan, contohnya:
  * Jika sedang membuat form/tampilan: `git commit -m "feat: membuat tampilan form registrasi pasien dan styling blade"`
  * Jika sedang menyelesaikan logika login/validasi: `git commit -m "feat: implementasi validasi input dan pengecekan akun nonaktif"`
  * Jika sedang menghubungkan database/controller: `git commit -m "feat: konfigurasi database env dan pembuatan controller"`
  * Jika sedang menyelesaikan perbaikan bug: `git commit -m "fix: memperbaiki rute middleware dashboard dan pesan error"`

### 3. Kriteria Audit Version Control oleh Penguji
* Guru penguji akan memeriksa riwayat log commit (`git log --stat` / riwayat commit GitHub) pada tautan yang dikumpulkan di portal lab.
* **DILARANG** melakukan *single monolithic commit* (hanya melakukan 1 kali commit gelondongan di menit-menit akhir ujian).
* Siswa yang tidak mematuhi interval push 30 menit atau tidak menyertakan pesan commit yang sesuai dengan perubahan kode yang dilakukan akan dikenakan pemotongan nilai kedisiplinan teknis.
