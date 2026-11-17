# LEMBAR SOAL UJIAN PRAKTIKUM 1 (PEMROGRAMAN WEB PHP)
**Mata Pelajaran:** Pemrograman Web dan Perangkat Bergerak  
**Kelas:** XI RPL / PPLG  
**Materi Uji:** Penanganan Autentikasi Pengguna & Proteksi Halaman  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Buat folder proyek kerja pada direktori web server lokal dengan format: `PRAKTIKUM1_WEB_NIS_NAMA`.
2. Salin template antarmuka yang telah disediakan pada bagian **IV. KODE RAW TEMPLATE TAMPILAN** di bawah ini (`login.html`, `register.html`, dan `dashboard.html`).
3. Analisis antarmuka yang disediakan. Desain tampilan tidak boleh rusak.
4. Hubungkan aplikasi web ke basis data `db_kopi_member`.
5. Pastikan seluruh alur sistem berjalan sesuai spesifikasi kebutuhan yang ditentukan.

---

## II. DATA DUMMY
Import skrip basis data `database_pelanggan.sql` ke dalam MySQL.

**Nama Database:** `db_kopi_member`  
**Nama Tabel:** `tb_pelanggan`  

### Struktur Tabel `tb_pelanggan`:
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_pelanggan` | INT (Auto Increment, Primary Key) | ID unik member |
| `nama_lengkap` | VARCHAR(100) | Nama lengkap pelanggan |
| `email` | VARCHAR(100) | Alamat email (Unique) |
| `no_hp` | VARCHAR(20) | Nomor telepon / WhatsApp |
| `password` | VARCHAR(255) | Kata sandi akun |
| `status_member` | ENUM('Aktif', 'Nonaktif') | Status akun member |
| `tanggal_daftar` | TIMESTAMP | Waktu pendaftaran akun |

### Data Pengujian Login:
| Email | Password | Status Member | Hasil yang Diharapkan Saat Login |
| :--- | :--- | :--- | :--- |
| `budi@gmail.com` | `kopi123` | Aktif | Berhasil masuk ke dashboard member |
| `siti@gmail.com` | `senja123` | Aktif | Berhasil masuk ke dashboard member |
| `andi@gmail.com` | `nusantara` | Nonaktif | Ditolak (muncul notifikasi akun nonaktif) |

---

## III. PETUNJUK SOAL

### 1. Pendaftaran Akun Baru (`register.php`)
Mengacu pada antarmuka "Formulir Pendaftaran Member".
* Pastikan semua field wajib diisi, apabila ada field yang kosong muncul warning *"Semua field wajib diisi !"*.
* Kata sandi dan ulangi sandi harus sesuai, apabila tidak sesuai muncul warning *"Konfirmasi kata sandi tidak cocok !"*.
* Kata sandi minimal berjumlah 6 karakter dan wajib mengandung kombinasi minimal satu angka. Apabila tidak memenuhi ketentuan, muncul warning *"Kata sandi minimal 6 karakter dan wajib mengandung angka !"*.
* Email yang didaftarkan tidak boleh sama dengan email yang sudah ada di database. Apabila email sudah terdaftar, muncul warning *"Email sudah digunakan, gunakan email lain !"*.
* Jika pendaftaran berhasil:
  * Akun baru otomatis tersimpan dengan status keanggotaan default **Aktif**.
  * Pengguna diarahkan ke halaman login dengan pesan konfirmasi *"Pendaftaran berhasil, silakan login !"*.

---

### 2. Masuk ke Sistem (`login.php`)
Mengacu pada antarmuka "Form Login Member".
* Pastikan semua field wajib diisi, apabila tidak sesuai muncul warning *"Email atau password yang anda masukkan tidak sesuai !"*.
* Jika akun yang dimasukkan memiliki status **Nonaktif**, proses login ditolak dan muncul warning *"Akun anda sedang dinonaktifkan !"*.
* Jika proses login berhasil:
  * Pengguna otomatis dialihkan ke halaman **Dashboard Member**.
  * Data pengguna yang sedang aktif tersimpan di dalam sesi sistem.

---

### 3. Dashboard Member (`dashboard.php`)
Mengacu pada antarmuka "Dashboard Member".
* **Proteksi Halaman:**
  * Halaman dashboard tidak dapat dibuka secara langsung jika pengguna belum login.
  * Apabila pengguna mencoba membuka halaman ini tanpa login, maka sistem otomatis mengarahkan pengguna kembali ke halaman login dengan warning *"Silakan login terlebih dahulu !"*.
* **Tampilan Data:**
  * Memunculkan secara otomatis nama lengkap, email, nomor kontak, dan status keanggotaan dari pengguna yang sedang aktif login.
  * Memunculkan secara otomatis daftar hak istimewa member beserta nomor urutnya.
* **Keluar Sistem (Logout):**
  * Ketika tombol "Keluar" diklik, sesi pengguna akan dihapus dan pengguna diarahkan kembali ke halaman login.

---

## IV. KODE RAW TEMPLATE TAMPILAN (SIAP SALIN)

Siswa dapat langsung menyalin kode HTML/CSS di bawah ini ke dalam berkas kerja masing-masing.

### 1. Template Halaman Masuk (`login.html` / `login.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Member Kopi Nusantara</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f4efe6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .auth-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(78, 52, 46, 0.12);
            border: 1px solid #e0d5c1;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .brand-header h2 {
            color: #4e342e;
            font-size: 24px;
            margin-bottom: 6px;
        }
        .brand-header p {
            color: #795548;
            font-size: 14px;
        }
        .alert-box {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
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
            color: #4e342e;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #d7ccc8;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-control:focus {
            border-color: #6d4c41;
            box-shadow: 0 0 0 3px rgba(109, 76, 65, 0.15);
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #4e342e;
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
            background-color: #3e2723;
        }
        .auth-footer {
            margin-top: 22px;
            text-align: center;
            font-size: 13px;
            color: #6d4c41;
        }
        .auth-footer a {
            color: #8d6e63;
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
            <h2>Kopi Nusantara</h2>
            <p>Portal Loyalitas & Pemesanan Member</p>
        </div>

        <div class="alert-box">
            <span>Pesan notifikasi sistem ditampilkan di sini</span>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Email Terdaftar</label>
                <input type="email" class="form-control" placeholder="nama@email.com" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" class="form-control" placeholder="Masukkan kata sandi" required>
            </div>

            <button type="submit" class="btn-submit">Masuk ke Akun</button>
        </form>

        <div class="auth-footer">
            Belum menjadi member? <a href="register.php">Daftar sekarang</a>
        </div>
    </div>

</body>
</html>
```

---

### 2. Template Halaman Pendaftaran (`register.html` / `register.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Member - Kopi Nusantara</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f4efe6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 25px 20px;
        }
        .auth-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 480px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(78, 52, 46, 0.12);
            border: 1px solid #e0d5c1;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .brand-header h2 {
            color: #4e342e;
            font-size: 24px;
            margin-bottom: 6px;
        }
        .brand-header p {
            color: #795548;
            font-size: 14px;
        }
        .alert-box {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
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
            color: #4e342e;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d7ccc8;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-control:focus {
            border-color: #6d4c41;
            box-shadow: 0 0 0 3px rgba(109, 76, 65, 0.15);
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
            background-color: #4e342e;
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
            background-color: #3e2723;
        }
        .auth-footer {
            margin-top: 22px;
            text-align: center;
            font-size: 13px;
            color: #6d4c41;
        }
        .auth-footer a {
            color: #8d6e63;
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
            <h2>Kopi Nusantara</h2>
            <p>Formulir Pendaftaran Member Baru</p>
        </div>

        <div class="alert-box">
            <span>Pesan status pendaftaran ditampilkan di sini</span>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" class="form-control" placeholder="nama@email.com" required>
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp / HP</label>
                <input type="tel" class="form-control" placeholder="081234567890" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group">
                    <label>Ulangi Sandi</label>
                    <input type="password" class="form-control" placeholder="Ketik ulang kata sandi" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Daftarkan Akun Member</button>
        </form>

        <div class="auth-footer">
            Sudah memiliki akun member? <a href="login.php">Masuk di sini</a>
        </div>
    </div>

</body>
</html>
```

---

### 3. Template Halaman Dashboard Member (`dashboard.html` / `dashboard.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - Kopi Nusantara</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f7f5f0;
            color: #333333;
        }
        .navbar {
            background-color: #4e342e;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .navbar-brand h2 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .navbar-user span {
            font-size: 14px;
            color: #d7ccc8;
        }
        .btn-logout {
            background-color: #c62828;
            color: #ffffff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-logout:hover {
            background-color: #b71c1c;
        }
        .container {
            max-width: 960px;
            margin: 35px auto;
            padding: 0 20px;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #6d4c41, #3e2723);
            color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 6px 18px rgba(62, 39, 35, 0.15);
        }
        .welcome-banner h1 {
            font-size: 26px;
            margin-bottom: 8px;
        }
        .welcome-banner p {
            color: #efebe9;
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
            border: 1px solid #e0d5c1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .info-card h4 {
            color: #795548;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        .info-card .card-value {
            font-size: 24px;
            font-weight: 700;
            color: #4e342e;
            margin-bottom: 6px;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .section-box {
            background: #ffffff;
            padding: 26px;
            border-radius: 10px;
            border: 1px solid #e0d5c1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .section-box h3 {
            color: #4e342e;
            font-size: 18px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #efebe9;
        }
        .benefit-list {
            list-style: none;
        }
        .benefit-item {
            padding: 12px 0;
            border-bottom: 1px solid #f4efe6;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #5d4037;
        }
        .benefit-item:last-child {
            border-bottom: none;
        }
        .benefit-number {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: #d7ccc8;
            color: #4e342e;
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
            <h2>Kopi Nusantara</h2>
        </div>
        <div class="navbar-user">
            <span>Masuk sebagai: <strong>Pelanggan Setia</strong> (pelanggan@email.com)</span>
            <a href="logout.php" class="btn-logout">Keluar</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-banner">
            <h1>Selamat Datang Kembali, Nama Pelanggan!</h1>
            <p>Terima kasih telah menjadi bagian dari keluarga penikmat Kopi Nusantara.</p>
        </div>

        <div class="grid-info">
            <div class="info-card">
                <h4>Status Keanggotaan</h4>
                <div class="card-value">Aktif</div>
                <span class="badge-status">Member Terverifikasi</span>
            </div>
            <div class="info-card">
                <h4>Nomor Kontak Terdaftar</h4>
                <div class="card-value">081234567890</div>
                <p style="font-size: 12px; color: #8d6e63;">Digunakan untuk verifikasi penukaran promo kafe.</p>
            </div>
        </div>

        <div class="section-box">
            <h3>Daftar Hak Istimewa Member Aktif</h3>
            <ul class="benefit-list">
                <li class="benefit-item">
                    <div class="benefit-number">1</div>
                    <div>Diskon 15% setiap pembelian di hari ulang tahun member.</div>
                </li>
                <li class="benefit-item">
                    <div class="benefit-number">2</div>
                    <div>Gratis upgrade ukuran gelas (Reguler ke Large) setiap hari Jumat.</div>
                </li>
                <li class="benefit-item">
                    <div class="benefit-number">3</div>
                    <div>Akses lebih awal untuk peluncuran menu musiman varian single origin.</div>
                </li>
                <li class="benefit-item">
                    <div class="benefit-number">4</div>
                    <div>Undangan eksklusif sesi cupping coffee bulanan bersama barista utama.</div>
                </li>
            </ul>
        </div>
    </div>

</body>
</html>
```
