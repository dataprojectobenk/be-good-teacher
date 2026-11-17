# Modul 01: Pengenalan Basis Data, Lingkungan MySQL, & DDL Database

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### Prasyarat Awal
- Anda dapat menyalakan aplikasi di komputer (Windows/macOS/Linux).
- Belum diperlukan pengetahuan SQL atau pemrograman apa pun sebelum membaca modul ini.

### Jembatan Konsep: Apa Itu Basis Data, RDBMS, dan MySQL?
Selama ini Anda mungkin terbiasa menyimpan catatan barang atau nilai mahasiswa di berkas **Excel (`.xlsx`)** atau dokumen teks biasa. Cara itu praktis untuk satu orang, tetapi cepat menimbulkan masalah saat data bertambah besar, diakses banyak pengguna secara bersamaan, atau harus saling terhubung rapi tanpa duplikasi.

Untuk membangun pemahaman yang utuh, mari kenali istilah dasar berikut:
- **Basis Data (*Database*):** Kumpulan data terorganisasi yang disimpan secara elektronik agar mudah dicari, diubah, dan dikelola.
- **DBMS vs RDBMS:** **DBMS** (*Database Management System*) adalah perangkat lunak pengelola basis data. Jika datanya disusun dalam bentuk **tabel-tabel yang saling berhubungan** (*relasional*), maka sistemnya disebut **RDBMS** (*Relational DBMS*). **MySQL 8.x** adalah salah satu RDBMS paling populer di dunia.
- **Anatomi Sederhana di Dalam Basis Data Relasional:**
  - **Database** = Lemari arsip untuk satu sistem (misalnya `toko_db`, `akademik_db`).
  - **Tabel (*Table*)** = Buku catatan terpisah per topik (misalnya tabel `produk`, tabel `pelanggan`).
  - **Kolom (*Field / Attribute*)** = Judul atribut ke samping (misal `nama_produk`, `harga`).
  - **Baris (*Record / Tuple*)** = Satu entri data utuh ke bawah (misal 1 barang: `Madu Hutan`, `75000`).
  - **Kunci (*Key*)** = Penanda unik agar setiap baris bisa dikenali dan dihubungkan ke tabel lain *(dibahas mendalam di Modul 02)*.

Agar lebih mudah membayangkan cara kerja **MySQL**, gunakan **Analogi Restoran**:
1. **Code Editor / Terminal (Klien)** = **Pelayan** tempat Anda menuliskan perintah dalam bahasa **SQL** (*Structured Query Language*).
2. **MySQL Server** = **Dapur** yang bekerja di latar belakang untuk menerima perintah, menjaga keamanan data, dan mengirimkan hasilnya kembali ke layar Anda.
3. **Database** = **Lemari Arsip** di dapur yang menyimpan seluruh tabel aplikasi Anda.

```text
+---------------------------+        Perintah SQL        +----------------------+      +---------------------+
| Code Editor / Terminal    | -------------------------> |    MySQL Server      | ---> |   Lemari Database   |
| (CLI / VS Code/phpMyAdmin)| <------------------------- | (Layanan Latar Blkg) |      | (toko_db, dll.)     |
+---------------------------+     Hasil / Pesan Sukses   +----------------------+      +---------------------+
```

### Tujuan Pembelajaran
Setelah menyelesaikan Modul 01, Anda diharapkan mampu:
1. Menjelaskan konsep dasar **basis data relasional (RDBMS)**, tabel, kolom, baris, serta arsitektur Client–Server MySQL.
2. Menjalankan layanan **MySQL Server** dan mengeksekusi perintah SQL lewat **CLI**, **VS Code**, maupun **phpMyAdmin**.
3. Membuat database baru, memilih database aktif, dan menghapus database uji coba dengan aman menggunakan perintah **DDL** (*Data Definition Language*).

---

## 2. Kamus Perintah & Argumen Dasar

> **Aturan Emas Penulisan SQL:** Setiap perintah SQL **wajib diakhiri dengan tanda titik koma (`;`)**. Tanda `;` memberi tahu MySQL bahwa satu kalimat perintah telah selesai dan siap dijalankan.

| Perintah Dasar | Bagian / Argumen | Fungsi Sederhana |
| :--- | :--- | :--- |
| `SELECT VERSION();` | `VERSION()` | Meminta server menampilkan nomor versi MySQL untuk memastikan koneksi berhasil. |
| `SHOW DATABASES;` | `DATABASES` | Menampilkan daftar semua lemari database yang saat ini tersimpan di MySQL Server. |
| `CREATE DATABASE [IF NOT EXISTS] nama_db;` | `IF NOT EXISTS` *(opsional)*<br>`nama_db` | Membuat database baru. Opsi `IF NOT EXISTS` mencegah error jika nama database tersebut sudah pernah dibuat sebelumnya. |
| `USE nama_db;` | `nama_db` | Berpindah dan memilih database tertentu sebagai tempat kerja aktif saat ini. |
| `DROP DATABASE [IF EXISTS] nama_db;` | `IF EXISTS` *(opsional)*<br>`nama_db` | Menghapus database beserta seluruh isinya secara **permanen**. Opsi `IF EXISTS` mencegah error jika database tidak ditemukan. |

**Keterangan Karakter Tambahan Saat Membuat Database:**
- `CHARACTER SET utf8mb4`: Mengatur agar database mendukung seluruh huruf Latin, aksara Arab/daerah, simbol mata uang, hingga emoji tanpa rusak.
- `COLLATE utf8mb4_0900_ai_ci`: Aturan standar MySQL 8.x untuk mengurutkan dan membandingkan teks tanpa membedakan huruf besar/kecil (*case-insensitive*).

---

## 3. Persiapan Lingkungan & Pengenalan Code Editor

Sebelum mengetik perintah SQL apa pun, pastikan dua hal berikut sudah siap di komputer Anda:

### Langkah A: Menyalakan MySQL Server
Pilih salah satu sesuai aplikasi yang terpasang di komputer Anda:
- **Pengguna XAMPP:** Buka *XAMPP Control Panel* → klik tombol **Start** pada baris **MySQL** hingga berubah menjadi hijau.
- **Pengguna Laragon:** Buka *Laragon* → klik tombol **Start All**.
- **Pengguna MySQL Installer Resmi (Windows/macOS/Linux):** Pastikan layanan `MySQL80` (*Services*) berstatus **Running**.

### Langkah B: Menjalankan Kueri di CLI, VS Code, atau phpMyAdmin
Anda cukup memilih **salah satu** dari tiga cara di bawah ini untuk menulis dan menjalankan perintah SQL pada modul ini *(pengguna MySQL Workbench atau DBeaver juga dapat langsung membuka tab Query dan menekan `Ctrl + Enter`)*.

#### 1. Melalui Terminal / CLI (`mysql`)
1. Buka **Command Prompt / PowerShell / Terminal** (atau klik tombol **Shell** pada XAMPP Control Panel).
2. Hubungkan terminal ke MySQL Server dengan mengetik:
   ```bash
   mysql -u root -p
   ```
   *(Jika diminta `Enter password:` dan Anda tidak memasang password—seperti bawaan XAMPP—langsung tekan `Enter`)*.
3. Jika berhasil, tampilan baris berubah menjadi `mysql>`. Ketik perintah SQL diakhiri tanda `;` (contoh: `SELECT VERSION();`) lalu tekan `Enter`.
4. Untuk keluar dari MySQL CLI setelah selesai, ketik `exit;` lalu tekan `Enter`.

#### 2. Melalui Visual Studio Code (VS Code)
Anda bisa memilih salah satu dari dua cara di VS Code:
- **Cara A (Terminal Terintegrasi):** Tekan `Ctrl + `` ` (atau menu *Terminal → New Terminal*) di VS Code, lalu ikuti langkah **CLI** (`mysql -u root -p`) langsung di dalam jendela VS Code.
- **Cara B (Ekstensi Database Visual):**
  1. Buka panel **Extensions** (`Ctrl + Shift + X`), lalu pasang ekstensi MySQL (misalnya **Database Client / MySQL** oleh *Weijan Chen*, atau **SQLTools** beserta driver **SQLTools MySQL**).
  2. Buka ikon database di panel kiri → klik **+** (*Add Connection*) → pilih **MySQL** → isi `Host: localhost`, `Port: 3306`, `User: root`, dan kosongkan password jika tidak ada → klik **Connect**.
  3. Klik **New Query** (atau buat file `latihan.sql`), ketik perintah SQL Anda, lalu tekan `Ctrl + Enter` (atau klik tombol **Run / ▶**).

#### 3. Melalui phpMyAdmin (Browser)
1. Di **XAMPP Control Panel** atau **Laragon**, pastikan layanan **Apache** dan **MySQL** sama-sama berstatus **Start / Running** *(phpMyAdmin membutuhkan Apache untuk membuka halaman webnya)*.
2. Buka browser (Chrome/Edge/Firefox) dan kunjungi alamat: `http://localhost/phpmyadmin`.
3. Klik tab **SQL** di deretan menu bagian atas *(jika nantinya Anda ingin menjalankan perintah di dalam database tertentu, klik dulu nama database di panel kiri baru klik tab **SQL**)*.
4. Ketik perintah SQL di kotak teks besar, lalu klik tombol **Go** (atau **Kirim**) di kanan bawah untuk melihat hasilnya.

### Peta Database Studi Kasus
Sepanjang kursus MySQL ini, kita akan menggunakan tiga nama database utama secara bertahap:
- **`toko_db`**: Database toko ritel halal *(dipakai pada bagian Contoh Praktik di setiap modul)*.
- **`akademik_db`** & **`logistik_db`**: Database kampus dan pengiriman barang *(dipakai di bagian Latihan Mandiri)*.

---

## 4. Contoh Praktik Bertingkat — Studi Kasus `toko_db`

Buka editor SQL pilihan Anda, lalu ketik dan jalankan contoh berikut secara berurutan.

### Contoh 1 (Level Dasar): Menguji Koneksi & Melihat Database Bawaan
Langkah pertama setiap kali membuka editor adalah memastikan editor benar-benar terhubung ke MySQL Server.

```sql
-- 1. Menampilkan versi MySQL Server yang sedang berjalan
SELECT VERSION();

-- 2. Melihat daftar database yang sudah ada di server
SHOW DATABASES;
```

**Penjelasan Baris Demi Baris:**
- Tanda `-- ` di awal baris adalah **komentar** (catatan untuk manusia). Baris komentar tidak akan dijalankan oleh MySQL.
- `SELECT VERSION();` meminta MySQL menjawab dengan nomor versinya (misalnya `8.0.36` atau `8.4.0`). Jika muncul angka versi, artinya koneksi sukses!
- `SHOW DATABASES;` menampilkan database sistem bawaan MySQL (`information_schema`, `mysql`, `performance_schema`, `sys`). **Jangan pernah menghapus keempat database sistem ini.**

---

### Contoh 2 (Level Menengah): Membuat & Memilih Database `toko_db`
Sekarang kita akan membuat lemari arsip pertama bernama `toko_db`, lalu masuk ke dalamnya agar siap digunakan.

```sql
-- 1. Membuat database toko_db jika belum ada
CREATE DATABASE IF NOT EXISTS toko_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_0900_ai_ci;

-- 2. Memilih toko_db sebagai database aktif
USE toko_db;

-- 3. Memastikan toko_db sudah muncul dalam daftar
SHOW DATABASES;
```

**Penelusuran Perubahan Keadaan (*State Tracing*):**

| Tahap Eksekusi | Kondisi Daftar Database | Database yang Sedang Aktif |
| :--- | :--- | :--- |
| Sebelum `CREATE DATABASE` | Hanya 4 database sistem (`information_schema`, `mysql`, `performance_schema`, `sys`) | Belum ada (`NULL` / belum dipilih) |
| Setelah `CREATE DATABASE` | Bertambah menjadi 5 database (muncul `toko_db`) | Masih belum ada |
| Setelah `USE toko_db;` | Tetap 5 database | **`toko_db`** *(siap digunakan pada praktik modul berikutnya)* |

---

### Contoh 3 (Level Lanjut): Siklus Membuat & Menghapus Database Uji Coba
Saat bereksperimen, terkadang kita membuat database sementara lalu perlu membersihkannya kembali agar daftar database tetap rapi.

```sql
-- 1. Membuat database sementara untuk latihan
CREATE DATABASE IF NOT EXISTS uji_coba_db;

-- 2. Cek apakah uji_coba_db sudah terbuat
SHOW DATABASES;

-- 3. Menghapus database uji_coba_db karena sudah tidak dipakai
DROP DATABASE IF EXISTS uji_coba_db;

-- 4. Cek kembali daftar database untuk memastikan uji_coba_db sudah hilang
SHOW DATABASES;
```

> **Peringatan Penting:** Perintah `DROP DATABASE` tidak memiliki fitur *Undo* atau *Recycle Bin*. Pastikan Anda memeriksa ulang nama database sebelum menjalankannya.

---

### Contoh 4 *(Pendalaman — Opsional)*: Menjalankan Skrip Ulang Tanpa Error
Dalam praktik kerja nyata, sebuah berkas `.sql` sering dijalankan lebih dari satu kali. Perhatikan perbedaan berikut saat Anda menekan tombol *Execute* untuk kedua kalinya:

```sql
-- Aman dijalankan berkali-kali (tidak akan memicu pesan error)
CREATE DATABASE IF NOT EXISTS toko_db;
DROP DATABASE IF EXISTS uji_coba_db;
```

Dengan menambahkan `IF NOT EXISTS` saat membuat dan `IF EXISTS` saat menghapus, skrip Anda menjadi **aman dijalankan ulang** meskipun `toko_db` sudah ada atau `uji_coba_db` sudah terhapus.

---

## 5. Verifikasi Output & Panduan Troubleshooting

### Tabel Ekspektasi Output Normal

| Perintah yang Dijalankan | Tanda Berhasil di Terminal / CLI | Tanda Berhasil di VS Code / phpMyAdmin |
| :--- | :--- | :--- |
| `SELECT VERSION();` | Muncul tabel kecil berisi 1 baris nomor versi (misal `8.0.36`). | Tab hasil menampilkan kolom `VERSION()` dan angkanya. |
| `SHOW DATABASES;` | Daftar nama database dalam kotak teks. | Tabel 1 kolom (`Database`) berisi nama-nama database. |
| `CREATE DATABASE ...;` | `Query OK, 1 row affected` | Pesan sukses / ikon centang hijau; nama database muncul di panel kiri setelah *refresh*. |
| `USE toko_db;` | `Database changed` | Pesan sukses hijau *(di phpMyAdmin tab SQL, Anda juga bisa langsung klik nama `toko_db` di panel kiri)*. |
| `DROP DATABASE ...;` | `Query OK, 0 rows affected` | Pesan sukses hijau dan nama database hilang saat daftar di-*refresh*. |

### Kendala Umum & Cara Mengatasinya

| Pesan Error yang Muncul | Penyebab Umum | Cara Mengatasinya |
| :--- | :--- | :--- |
| `'mysql' is not recognized` (di CLI / VS Code Terminal) | Folder `bin` MySQL belum terdaftar di sistem PATH, atau belum memakai Shell XAMPP. | Gunakan tombol **Shell** di XAMPP Control Panel, atau masuk ke folder instalasi `mysql\bin` terlebih dahulu. |
| Halaman `localhost/phpmyadmin` tidak bisa dibuka | Layanan **Apache** di XAMPP/Laragon belum dinyalakan. | Klik tombol **Start** pada baris **Apache** di samping MySQL. |
| `ERROR 2002 (HY000): Can't connect to MySQL server` | Layanan MySQL Server di komputer belum dinyalakan. | Buka XAMPP/Laragon/Services, lalu klik **Start** pada MySQL. |
| `ERROR 1064 (42000): You have an error in your SQL syntax` | Lupa menuliskan titik koma `;` di akhir baris, atau salah eja kata kunci (misalnya `DATABSE`). | Periksa ejaan kata kunci SQL dan pastikan setiap perintah ditutup dengan `;`. |
| `ERROR 1007 (HY000): Can't create database ...; database exists` | Menjalankan `CREATE DATABASE` tanpa `IF NOT EXISTS` padahal databasenya sudah ada. | Tambahkan `IF NOT EXISTS` setelah `CREATE DATABASE`. |
| `ERROR 1049 (42000): Unknown database '...'` | Salah ketik nama database pada perintah `USE`, atau databasenya memang belum dibuat. | Jalankan `SHOW DATABASES;` untuk melihat ejaan yang benar, atau buat dulu dengan `CREATE DATABASE`. |

---

## 6. Latihan Soal

*Perhatikan tabel acuan database dan daftar perintah dasar berikut sebelum mengerjakan latihan soal:*

#### Tabel Acuan Database Praktik

| Nama Database | Karakter Encoding | Collation Standar | Tujuan Sistem |
| :--- | :--- | :--- | :--- |
| `toko_db` | `utf8mb4` | `utf8mb4_0900_ai_ci` | Toko Ritel & Penjualan Halal |
| `akademik_db` | `utf8mb4` | `utf8mb4_0900_ai_ci` | Sistem Administrasi Akademik Kampus |
| `logistik_db` | `utf8mb4` | `utf8mb4_0900_ai_ci` | Ekspedisi & Manajemen Gudang |
| `latihan_hapus_db`| `utf8mb4` | `utf8mb4_0900_ai_ci` | Database Sementara untuk Uji Coba Hapus |

---

#### Daftar Tugas Praktik
1. Tulis perintah SQL untuk menampilkan nomor versi MySQL Server yang sedang aktif di komputer Anda.
2. Tulis perintah SQL untuk menampilkan seluruh daftar database yang ada di server saat ini.
3. Tulis perintah SQL untuk membuat database baru bernama `akademik_db` dengan aturan karakter `utf8mb4` dan `COLLATE utf8mb4_0900_ai_ci` secara aman (tidak memicu error jika sudah ada).
4. Tulis perintah SQL untuk memilih dan mengaktifkan database `akademik_db` sebagai area kerja aktif.
5. Tulis perintah SQL untuk membuat database baru bernama `logistik_db` menggunakan pengaturan karakter `utf8mb4` secara aman.
6. Tulis perintah SQL untuk membuat database sementara bernama `latihan_hapus_db`.
7. Tulis perintah SQL untuk menghapus database sementara `latihan_hapus_db` secara aman tanpa memicu error meskipun dijalankan berulang kali.
8. Tulis perintah SQL untuk memperbaiki kesalahan penulisan pada cuplikan skrip berikut agar dapat dieksekusi tanpa error:
   ```sql
   CREATE DATABSE kasir_masjid
   USE kasir_masjid
   ```

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil eksekusi kueri latihan soal Anda secara mandiri:*

### Indikator Keberhasilan Tugas (Checklist Hasil Soal 1–8)
- **No. 1:** Muncul tabel hasil satu kolom `VERSION()` berisi nomor versi aktif (misal `8.0.36`).
- **No. 2:** Muncul daftar database bawaan sistem (`information_schema`, `mysql`, `performance_schema`, `sys`).
- **No. 3–5:** Muncul pesan `Query OK, 1 row affected` dan nama `akademik_db` serta `logistik_db` muncul saat `SHOW DATABASES;` dijalankan.
- **No. 6–7:** Database `latihan_hapus_db` berhasil terbuat lalu terhapus bersih dari daftar `SHOW DATABASES;`.
- **No. 8:** Kesalahan saltik kata kunci diperbaiki dan tanda titik koma pemisah perintah ditambahkan sehingga kedua perintah berhasil dieksekusi.

### Checklist Kesiapan Teknis
- [ ] Mampu menyalakan MySQL Server dan menjalankan perintah SQL lewat salah satu alat (**CLI**, **VS Code**, atau **phpMyAdmin**).
- [ ] Memahami fungsi tanda titik koma (`;`) di akhir setiap perintah SQL.
- [ ] Berhasil membuat tiga database latihan (`toko_db`, `akademik_db`, dan `logistik_db`) dan memverifikasinya lewat `SHOW DATABASES;`.
- [ ] Memahami bahaya perintah `DROP DATABASE` dan tidak menghapus database sistem bawaan.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci jawaban kueri SQL lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-01-pengenalan-basis-data-lingkungan-mysql--ddl-database).

---

## 8. Ringkasan Perintah & Refleksi Pemahaman

### Ringkasan Cepat Modul 01

| Tujuan | Perintah Singkat |
| :--- | :--- |
| Cek koneksi & versi MySQL | `SELECT VERSION();` |
| Lihat daftar database | `SHOW DATABASES;` |
| Buat database baru yang aman | `CREATE DATABASE IF NOT EXISTS nama_db CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;` |
| Pilih database aktif | `USE nama_db;` |
| Hapus database permanen | `DROP DATABASE IF EXISTS nama_db;` |

### Refleksi Pemahaman
1. Mengapa dalam kerja tim kita disarankan selalu menulis `IF NOT EXISTS` saat membuat database?
2. Sebelum langsung menulis kode pembuatan tabel di dalam `toko_db`, mengapa kita perlu merancang dulu struktur tabel dan hubungannya agar data tidak menumpuk atau ganda? *(Kita akan mempelajarinya secara utuh pada **Modul 02: Perancangan Basis Data — ERD & Normalisasi Tabel**).*
