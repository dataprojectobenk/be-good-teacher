# Modul 03: Tipe Data Inti & DDL Pembuatan Tabel

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### Prasyarat Awal
- Anda sudah mampu menyalakan MySQL Server dan menjalankan perintah SQL di CLI, VS Code, atau phpMyAdmin (**Modul 01**).
- Anda sudah memahami cara merancang struktur tabel yang bersih melalui **ERD dan Normalisasi** serta membaca **Kamus Data** (**Modul 02**).

### Jembatan Konsep: Menerjemahkan Rancangan ERD ke Tabel Nyata
Pada Modul 01, Anda sudah menyiapkan **Database** sebagai *Lemari Arsip* (`toko_db`, `akademik_db`, `logistik_db`), dan di Modul 02 Anda telah merancang struktur entitas beserta **Kamus Data** yang bebas duplikasi (*3NF*). Sekarang saatnya menerjemahkan Kamus Data tersebut menjadi **Tabel** nyata di dalam MySQL.

Berbeda dengan lembar kerja Excel yang membiarkan satu kolom terisi campuran angka dan huruf, tabel MySQL mewajibkan setiap **kolom** memiliki **Tipe Data** (angka bulat, uang desimal, teks, atau tanggal) serta **aturan pengisian** (*constraint*), misalnya ID unik atau kolom wajib isi.

```text
[Database: toko_db]
   ├── Tabel: kategori   ──► (id_kategori, nama_kategori, keterangan)
   └── Tabel: produk     ──► (id_produk, kode_sku, nama_produk, harga, stok, tanggal_kadaluwarsa, dibuat_pada)
```

### Tujuan Pembelajaran
Setelah menyelesaikan Modul 03, Anda diharapkan mampu:
1. Memahami **Format Umum SQL** perintah `CREATE TABLE`, `SHOW TABLES`, dan `DESCRIBE`.
2. Memilih **tipe data MySQL** yang sesuai untuk angka bulat, harga uang, teks, dan tanggal/waktu.
3. Membaca **Diagram Skema** dan **Kamus Data**, menerjemahkannya menjadi perintah `CREATE TABLE`, lalu menjelaskan fungsi tiap baris kodenya.

---

## 2. Kamus Format Umum SQL, Tipe Data, & Aturan Kolom

### A. Format Umum Perintah `CREATE TABLE`
Sebelum menulis kode nyata, pahami terlebih dahulu **kerangka umum** `CREATE TABLE` di bawah ini *(tanda kurung siku `[...]` berarti bagian tersebut opsional/boleh tidak ditulis)*:

```sql
CREATE TABLE [IF NOT EXISTS] nama_tabel (
    nama_kolom_1 tipe_data [aturan_kolom],
    nama_kolom_2 tipe_data [aturan_kolom],
    nama_kolom_terakhir tipe_data [aturan_kolom]
) [ENGINE=InnoDB];
```

**Penjelasan Tiap Bagian Format Umum:**
1. **`CREATE TABLE`**: Kata kunci wajib untuk memerintahkan MySQL membuat tabel baru.
2. **`[IF NOT EXISTS]`** *(opsional, sangat disarankan)*: Mencegah error apabila tabel dengan nama tersebut sudah pernah dibuat sebelumnya.
3. **`nama_tabel`**: Nama tabel buatan Anda (gunakan huruf kecil tanpa spasi, pisahkan kata dengan garis bawah `_`, misalnya `daftar_pelanggan`).
4. **Tanda `(` dan `);`**: Seluruh daftar kolom ditulis di dalam tanda kurung `(...)` dan ditutup dengan titik koma `;`.
5. **`nama_kolom tipe_data [aturan_kolom]`**: Pola penulisan satu kolom. Antarbaris kolom **wajib dipisahkan koma `,`**, sedangkan **baris kolom terakhir tidak boleh diberi koma** di belakangnya.
6. **`[ENGINE=InnoDB]`** *(opsional, standar MySQL 8.x)*: Mesin penyimpan yang mendukung relasi tabel dan keamanan transaksi.

**Contoh Mikro (Penerapan Format Umum Paling Sederhana):**
```sql
CREATE TABLE IF NOT EXISTS catatan_harian (
    id_catatan INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100) NOT NULL
);
```
- *Penjelasan:* Baris 1 membuka pembuatan tabel `catatan_harian`; baris 2 mendefinisikan kolom ID otomatis unik (diakhiri koma `,`); baris 3 mendefinisikan teks wajib isi maks. 100 karakter (tanpa koma karena kolom terakhir); baris 4 menutup perintah dengan `);`.

---

### B. Format Umum Perintah Inspeksi Tabel (`SHOW TABLES` & `DESCRIBE`)

| Perintah & Format Umum | Kegunaan | Contoh & Penjelasan |
| :--- | :--- | :--- |
| `SHOW TABLES;` | Menampilkan semua nama tabel di database yang sedang aktif (`USE`). | `SHOW TABLES;` → menampilkan daftar tabel di layar hasil. |
| `DESCRIBE nama_tabel;` *(atau `DESC nama_tabel;`)* | Memeriksa rancangan kolom (nama kolom, tipe data, status boleh kosong, `Key`, dan `Default`). | `DESCRIBE catatan_harian;` → menampilkan struktur kolom tabel `catatan_harian`. |

---

### C. Tipe Data Inti yang Umum Digunakan

| Kategori | Tipe Data | Contoh Penulisan | Kegunaan Praktis |
| :--- | :--- | :--- | :--- |
| **Angka Bulat** | `INT` | `stok INT` | Menyimpan bilangan bulat tanpa koma (stok barang, jumlah SKS, nomor ID hingga ~2 miliar). |
| **Angka Desimal / Uang** | `DECIMAL(p, s)` | `harga DECIMAL(12, 2)` | Menyimpan angka desimal presisi tinggi. `12` = total seluruh digit, `2` = angka di belakang koma (aman untuk mata uang). |
| **Teks Panjang Tetap** | `CHAR(n)` | `kode_sku CHAR(8)` | Menyimpan teks yang jumlah karakternya selalu sama persis (kode SKU 8 digit atau NIM 10 digit). |
| **Teks Panjang Fleksibel** | `VARCHAR(n)` | `nama_produk VARCHAR(120)` | Menyimpan teks pendek–menengah dengan batas maksimal `n` karakter sesuai panjang aslinya. |
| **Teks Panjang** | `TEXT` | `keterangan TEXT` | Menyimpan paragraf panjang (deskripsi barang, catatan, alamat lengkap). |
| **Tanggal** | `DATE` | `tgl_kadaluwarsa DATE` | Menyimpan tanggal saja dalam format `YYYY-MM-DD` (contoh: `2027-08-17`). |
| **Tanggal & Jam** | `DATETIME` | `dibuat_pada DATETIME` | Menyimpan tanggal sekaligus jam dalam format `YYYY-MM-DD HH:MM:SS`. |

---

### D. Aturan Kolom (*Constraint*) Dasar

| Kata Kunci | Penjelasan Singkat |
| :--- | :--- |
| `PRIMARY KEY` | Penanda identitas unik utama setiap baris data. Nilainya **wajib ada** dan **tidak boleh kembar**. |
| `AUTO_INCREMENT` | Memerintahkan MySQL mengisi angka urut otomatis (`1, 2, 3, ...`) pada kolom `PRIMARY KEY` bertipe bilangan bulat. |
| `NOT NULL` | Mewajibkan kolom selalu diisi nilai saat data dimasukkan nanti (tidak boleh dibiarkan kosong). |
| `DEFAULT nilai` | Memberikan nilai bawaan otomatis jika pengguna tidak mengisi kolom tersebut (misal `DEFAULT 0` atau `DEFAULT CURRENT_TIMESTAMP`). |

---

## 3. Persiapan Lingkungan & Skema Praktikum

1. Nyalakan **MySQL Server** (XAMPP/Laragon/Services), lalu buka editor pilihan Anda (**CLI**, **VS Code**, atau tab **SQL** di **phpMyAdmin**).
2. Pastikan database `toko_db` aktif dengan `USE toko_db;` sebelum membuat tabel *(di phpMyAdmin, Anda juga bisa klik nama database pada panel kiri)*.

### Peta Tabel Praktikum Modul 03
- **`toko_db`** *(Contoh Praktik Utama)*: Tabel `kategori` dan `produk`.
- **`akademik_db`** *(Latihan A)*: Tabel `program_studi` dan `mahasiswa`.
- **`logistik_db`** *(Latihan B)*: Tabel `gudang` dan `paket_kiriman`.

---

## 4. Contoh Praktik Bertingkat — Studi Kasus `toko_db`

Setiap contoh di bawah mengikuti alur tetap: **(1) Format Umum SQL → (2) Diagram Skema & Kamus Data → (3) Kode SQL → (4) Penjelasan Tiap Baris → (5) Hasil Keluaran**.

### Contoh 1 (Level Dasar): Membuat Tabel `kategori`

#### 1. Format Umum yang Dipakai
```sql
USE nama_database;

CREATE TABLE [IF NOT EXISTS] nama_tabel (
    nama_kolom_1 tipe_data AUTO_INCREMENT PRIMARY KEY,
    nama_kolom_2 tipe_data NOT NULL,
    nama_kolom_3 tipe_data
) ENGINE=InnoDB;
```

#### 2. Diagram Skema & Kamus Data Tabel `kategori`
```text
+-----------------------------------+
|             kategori              |
+-----------------------------------+
| * id_kategori   : INT (PK, AI)    |
|   nama_kategori : VARCHAR(50)     |
|   keterangan    : TEXT            |
+-----------------------------------+
```

| No | Nama Kolom | Tipe Data & Panjang | Aturan Kolom | Keterangan |
| :---: | :--- | :--- | :--- | :--- |
| 1 | `id_kategori` | `INT` | `AUTO_INCREMENT`, `PRIMARY KEY` | Nomor identitas unik kategori, terisi otomatis (`1, 2, 3...`). |
| 2 | `nama_kategori` | `VARCHAR(50)` | `NOT NULL` | Nama kategori (misal: *Minuman Herbal*), wajib diisi, maks. 50 karakter. |
| 3 | `keterangan` | `TEXT` | *(Boleh NULL)* | Penjelasan tambahan kategori, bersifat opsional. |

#### 3. Kode SQL Implementasi
```sql
CREATE DATABASE IF NOT EXISTS toko_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_0900_ai_ci;
USE toko_db;

CREATE TABLE IF NOT EXISTS kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL,
    keterangan TEXT
) ENGINE=InnoDB;
```

#### 4. Penjelasan Tiap Baris
- **Baris 1–3 (`CREATE DATABASE ...`):** Memastikan wadah database `toko_db` sudah tersedia dan memakai standar karakter `utf8mb4`.
- **Baris 4 (`USE toko_db;`):** Memilih `toko_db` sebagai database aktif tempat tabel baru akan disimpan.
- **Baris 6 (`CREATE TABLE IF NOT EXISTS kategori (`):** Memulai pembuatan tabel `kategori`; hanya membuat jika belum ada agar tidak error bila skrip dijalankan ulang.
- **Baris 7 (`id_kategori INT AUTO_INCREMENT PRIMARY KEY,`):** Membuat kolom pertama `id_kategori` bertipe bilangan bulat yang terisi otomatis sekaligus menjadi identitas unik tabel, lalu ditutup koma `,` karena masih ada kolom berikutnya.
- **Baris 8 (`nama_kategori VARCHAR(50) NOT NULL,`):** Membuat kolom teks fleksibel maksimal 50 huruf yang wajib diisi (`NOT NULL`), lalu ditutup koma `,`.
- **Baris 9 (`keterangan TEXT`):** Membuat kolom teks panjang opsional untuk deskripsi. **Tidak ada koma** di ujung baris ini karena merupakan kolom terakhir.
- **Baris 10 (`) ENGINE=InnoDB;`):** Menutup daftar kolom, memilih mesin penyimpan standar `InnoDB`, dan mengakhiri seluruh perintah dengan titik koma `;`.

#### 5. Hasil Keluaran
```text
Query OK, 0 rows affected
```

---

### Contoh 2 (Level Menengah): Membuat Tabel `produk` dengan Beragam Tipe Data

#### 1. Format Umum yang Dipakai
```sql
USE nama_database;

CREATE TABLE [IF NOT EXISTS] nama_tabel (
    kolom_pk INT AUTO_INCREMENT PRIMARY KEY,
    kolom_kode CHAR(n) NOT NULL,
    kolom_uang DECIMAL(p, s) NOT NULL,
    kolom_default tipe_data NOT NULL DEFAULT nilai_awal,
    kolom_tanggal DATE,
    kolom_waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```

#### 2. Diagram Skema & Kamus Data Tabel `produk`
```text
+-----------------------------------------------+
|                    produk                     |
+-----------------------------------------------+
| * id_produk           : INT (PK, AI)          |
|   kode_sku            : CHAR(8)               |
|   nama_produk         : VARCHAR(120)          |
|   harga               : DECIMAL(12,2)         |
|   stok                : INT (DEFAULT 0)       |
|   tanggal_kadaluwarsa : DATE                  |
|   dibuat_pada         : DATETIME (DEFAULT NOW)|
+-----------------------------------------------+
```

| No | Nama Kolom | Tipe Data & Panjang | Aturan Kolom | Keterangan |
| :---: | :--- | :--- | :--- | :--- |
| 1 | `id_produk` | `INT` | `AUTO_INCREMENT`, `PRIMARY KEY` | ID unik setiap produk, diisi otomatis oleh MySQL. |
| 2 | `kode_sku` | `CHAR(8)` | `NOT NULL` | Kode stok tepat 8 karakter (contoh: `SKU00105`), wajib diisi. |
| 3 | `nama_produk` | `VARCHAR(120)` | `NOT NULL` | Nama produk maks. 120 karakter, wajib diisi. |
| 4 | `harga` | `DECIMAL(12, 2)` | `NOT NULL` | Harga jual dalam Rupiah (presisi hingga ratusan juta + 2 desimal). |
| 5 | `stok` | `INT` | `NOT NULL DEFAULT 0` | Jumlah stok bulat; otomatis diisi `0` bila tidak dicantumkan. |
| 6 | `tanggal_kadaluwarsa` | `DATE` | *(Boleh NULL)* | Tanggal kedaluwarsa (`YYYY-MM-DD`), boleh kosong untuk produk non-pangan. |
| 7 | `dibuat_pada` | `DATETIME` | `NOT NULL DEFAULT CURRENT_TIMESTAMP` | Tanggal & jam pencatatan otomatis sesuai waktu server saat itu. |

#### 3. Kode SQL Implementasi
```sql
USE toko_db;

CREATE TABLE IF NOT EXISTS produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    kode_sku CHAR(8) NOT NULL,
    nama_produk VARCHAR(120) NOT NULL,
    harga DECIMAL(12, 2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    tanggal_kadaluwarsa DATE,
    dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```

#### 4. Penjelasan Tiap Baris
- **Baris 1 (`USE toko_db;`):** Memastikan tabel `produk` diletakkan di dalam database `toko_db`.
- **Baris 3 (`CREATE TABLE IF NOT EXISTS produk (`):** Membuka definisi pembuatan tabel bernama `produk`.
- **Baris 4 (`id_produk INT AUTO_INCREMENT PRIMARY KEY,`):** Kolom nomor urut otomatis sebagai kunci utama (*primary key*).
- **Baris 5 (`kode_sku CHAR(8) NOT NULL,`):** Memakai `CHAR(8)` karena panjang kode SKU selalu tetap 8 karakter dan wajib ada.
- **Baris 6 (`nama_produk VARCHAR(120) NOT NULL,`):** Memakai `VARCHAR(120)` karena panjang nama produk berbeda-beda (hemat ruang penyimpanan).
- **Baris 7 (`harga DECIMAL(12, 2) NOT NULL,`):** Memakai `DECIMAL` agar perhitungan harga uang tetap presisi tanpa pembulatan keliru.
- **Baris 8 (`stok INT NOT NULL DEFAULT 0,`):** Menetapkan nilai awal `0` secara otomatis jika jumlah stok belum diisi.
- **Baris 9 (`tanggal_kadaluwarsa DATE,`):** Menyimpan tanggal saja; dibiarkan opsional (tanpa `NOT NULL`).
- **Baris 10 (`dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP`):** Menangkap tanggal dan jam pembuatan baris secara otomatis; tanpa koma karena ini kolom terakhir.
- **Baris 11 (`) ENGINE=InnoDB;`):** Menutup blok tabel `produk` dengan mesin penyimpanan `InnoDB`.

#### 5. Hasil Keluaran
```text
Query OK, 0 rows affected
```

---

### Contoh 3 (Level Lanjut): Mengecek Daftar & Struktur Tabel (`SHOW TABLES` & `DESCRIBE`)

#### 1. Format Umum yang Dipakai
```sql
USE nama_database;
SHOW TABLES;
DESCRIBE nama_tabel;
```

#### 2. Kode SQL Implementasi
```sql
USE toko_db;
SHOW TABLES;
DESCRIBE produk;
```

#### 3. Penjelasan Tiap Baris
- **Baris 1 (`USE toko_db;`):** Mengarahkan editor ke database `toko_db`.
- **Baris 2 (`SHOW TABLES;`):** Menampilkan daftar seluruh tabel di `toko_db` (`kategori` dan `produk`).
- **Baris 3 (`DESCRIBE produk;`):** Menampilkan cetakan struktur kolom tabel `produk` untuk memastikan tipe data dan aturan kolom sudah persis seperti Kamus Data.

#### 4. Hasil Keluaran `DESCRIBE produk;`
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id_produk` | `int` | `NO` | `PRI` | `NULL` | `auto_increment` |
| `kode_sku` | `char(8)` | `NO` | | `NULL` | |
| `nama_produk` | `varchar(120)` | `NO` | | `NULL` | |
| `harga` | `decimal(12,2)` | `NO` | | `NULL` | |
| `stok` | `int` | `NO` | | `0` | |
| `tanggal_kadaluwarsa` | `date` | `YES` | | `NULL` | |
| `dibuat_pada` | `datetime` | `NO` | | `CURRENT_TIMESTAMP` | `DEFAULT_GENERATED` |

**Cara Membaca Output:**
- **`Null = NO`**: Kolom wajib diisi (`NOT NULL`); **`Null = YES`**: boleh kosong.
- **`Key = PRI`**: Kolom bertindak sebagai `PRIMARY KEY`.
- **`Default` & `Extra`**: Menampilkan nilai bawaan (`0`, `CURRENT_TIMESTAMP`) serta fitur otomatis (`auto_increment`).

---

### Contoh 4 *(Pendalaman — Opsional)*: Mengintip Resep SQL Utuh (`SHOW CREATE TABLE`)
- **Format Umum:** `SHOW CREATE TABLE nama_tabel;`
- **Kode SQL & Penjelasan:**
  ```sql
  USE toko_db;
  SHOW CREATE TABLE produk;
  ```
  - **Baris 1:** Mengaktifkan `toko_db`.
  - **Baris 2:** Meminta MySQL menampilkan ulang kode `CREATE TABLE` lengkap yang tersimpan di server beserta karakter *encoding* dan `ENGINE=InnoDB` bawaannya.

---

## 5. Verifikasi Output & Panduan Troubleshooting

### Tabel Ekspektasi Output Normal

| Perintah yang Dijalankan | Tanda Berhasil di Terminal / CLI | Tanda Berhasil di VS Code / phpMyAdmin |
| :--- | :--- | :--- |
| `CREATE TABLE ...;` | `Query OK, 0 rows affected` | Pesan sukses hijau; nama tabel muncul di panel kiri setelah *refresh*. |
| `SHOW TABLES;` | Daftar tabel (`kategori`, `produk`). | Daftar tabel tampil di kotak hasil maupun di sidebar kiri. |
| `DESCRIBE produk;` | Tabel 6 kolom (`Field`, `Type`, `Null`, `Key`, `Default`, `Extra`). | Grid hasil terstruktur menampilkan seluruh definisi kolom. |

### Kendala Umum & Cara Mengatasinya

| Pesan Error yang Muncul | Penyebab Umum | Cara Mengatasinya |
| :--- | :--- | :--- |
| `ERROR 1046 (3D000): No database selected` | Menjalankan `CREATE TABLE` sebelum memilih database. | Tambahkan `USE toko_db;` sebelum `CREATE TABLE`, atau klik databasenya di phpMyAdmin. |
| `ERROR 1064 (42000): You have an error in your SQL syntax` | Lupa koma `,` di antara kolom, atau masih menulis koma `,` pada kolom paling terakhir sebelum `)`. | Beri koma `,` setelah tiap kolom **kecuali** baris kolom terakhir sebelum `)`. |
| `ERROR 1050 (42S01): Table '...' already exists` | Menjalankan `CREATE TABLE` tanpa `IF NOT EXISTS` saat tabel sudah ada. | Selalu sertakan `IF NOT EXISTS` setelah `CREATE TABLE`. |
| `ERROR 1075 (42000): Incorrect table definition` | Menulis `AUTO_INCREMENT` pada kolom yang belum diberi `PRIMARY KEY`. | Pastikan kolom `AUTO_INCREMENT` juga ditandai `PRIMARY KEY`. |

---

## 6. Latihan Soal

*Perhatikan tabel acuan spesifikasi skema dan contoh data berikut sebelum mengerjakan latihan soal:*

#### Tabel: program_studi (Spesifikasi & Data Acuan `akademik_db`)

| id_prodi (PK, AI) | kode_prodi (CHAR 5) | nama_prodi (VARCHAR 100) |
| :---: | :---: | :--- |
| 1 | IF001 | Teknik Informatika |
| 2 | SI001 | Sistem Informasi |
| 3 | TI001 | Teknologi Informasi |

#### Tabel: mahasiswa (Spesifikasi & Data Acuan `akademik_db`)

| id_mahasiswa (PK, AI) | nim (CHAR 10) | nama_lengkap (VARCHAR 100) | tanggal_lahir (DATE) | terdaftar_pada (DATETIME) |
| :---: | :---: | :--- | :---: | :--- |
| 1 | 2401001001 | Ahmad Fauzi | 2005-04-12 | 2024-09-01 08:30:00 |
| 2 | 2401001002 | Zahra Aulia | 2005-08-25 | 2024-09-01 08:35:00 |
| 3 | 2401001003 | Budi Santoso | 2004-12-10 | 2024-09-01 08:40:00 |

#### Tabel: gudang (Spesifikasi & Data Acuan `logistik_db`)

| id_gudang (PK, AI) | kode_gudang (CHAR 6) | kota (VARCHAR 60) | alamat (TEXT) |
| :---: | :---: | :--- | :--- |
| 1 | GDK-01 | Jakarta | Jl. Daan Mogot Km 12, Cengkareng |
| 2 | GDK-02 | Semarang | Jl. Pandanaran No. 45 |
| 3 | GDK-03 | Surabaya | Jl. Rungkut Industri Raya No. 8 |

#### Tabel: paket_kiriman (Spesifikasi & Data Acuan `logistik_db`)

| id_paket (PK, AI) | nomor_resi (CHAR 12) | berat_kg (DECIMAL 8,2) | ongkos_kirim (DECIMAL 12,2) | tanggal_kirim (DATE) |
| :---: | :---: | :---: | :---: | :---: |
| 1 | RESI20260001 | 1.50 | 25000.00 | 2026-11-01 |
| 2 | RESI20260002 | 3.25 | 45000.00 | 2026-11-02 |
| 3 | RESI20260003 | 0.75 | 15000.00 | 2026-11-02 |

---

#### Daftar Tugas Praktik
1. Tulis perintah SQL untuk membuat tabel `program_studi` pada database `akademik_db` dengan kolom `id_prodi` (PK, Auto Increment), `kode_prodi` (CHAR 5, NOT NULL), dan `nama_prodi` (VARCHAR 100, NOT NULL).
2. Tulis perintah SQL untuk membuat tabel `mahasiswa` pada database `akademik_db` dengan kolom `id_mahasiswa` (PK, Auto Increment), `nim` (CHAR 10, NOT NULL), `nama_lengkap` (VARCHAR 100, NOT NULL), `tanggal_lahir` (DATE, NOT NULL), dan `terdaftar_pada` (DATETIME, NOT NULL, DEFAULT CURRENT_TIMESTAMP).
3. Tulis perintah SQL untuk menampilkan seluruh daftar tabel yang ada di dalam database `akademik_db`.
4. Tulis perintah SQL untuk memeriksa struktur kolom dan aturan tabel `mahasiswa` menggunakan perintah deskripsi tabel.
5. Tulis perintah SQL untuk membuat tabel `gudang` pada database `logistik_db` dengan kolom `id_gudang` (PK, Auto Increment), `kode_gudang` (CHAR 6, NOT NULL), `kota` (VARCHAR 60, NOT NULL), dan `alamat` (TEXT).
6. Tulis perintah SQL untuk membuat tabel `paket_kiriman` pada database `logistik_db` dengan kolom `id_paket` (PK, Auto Increment), `nomor_resi` (CHAR 12, NOT NULL), `berat_kg` (DECIMAL 8,2, NOT NULL), `ongkos_kirim` (DECIMAL 12,2, NOT NULL, DEFAULT 0), dan `tanggal_kirim` (DATE, NOT NULL).
7. Tulis perintah SQL untuk memeriksa cetakan struktur kolom tabel `paket_kiriman` pada database `logistik_db`.
8. Tulis perintah SQL untuk memperbaiki 3 kesalahan sintaks pada cuplikan pembuatan tabel berikut agar dapat dijalankan tanpa error:
   ```sql
   CREATE TABLE transaksi_kantin (
       id_transaksi INT AUTO_INCREMENT,
       total_bayar DECIMAL(12, 2) NOT NULL
       catatan VARCHAR(150),
   );
   ```

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil eksekusi kueri latihan soal Anda secara mandiri:*

### Indikator Keberhasilan Tugas (Checklist Hasil Soal 1–8)
- **No. 1–2:** Tabel `program_studi` dan `mahasiswa` berhasil terbuat (`Query OK, 0 rows affected`).
- **No. 3:** Perintah `SHOW TABLES;` pada `akademik_db` menampilkan 2 tabel: `mahasiswa` dan `program_studi`.
- **No. 4:** Hasil `DESCRIBE mahasiswa;` menunjukkan 5 kolom lengkap dengan `Key = PRI` pada `id_mahasiswa`.
- **No. 5–6:** Tabel `gudang` dan `paket_kiriman` berhasil terbuat di `logistik_db`.
- **No. 7:** Hasil `DESCRIBE paket_kiriman;` menunjukkan 5 kolom dengan `Default = 0.00` pada kolom `ongkos_kirim`.
- **No. 8:** Kesalahan diperbaiki menjadi penambahan `PRIMARY KEY` pada `id_transaksi`, penambahan tanda koma `,` setelah `total_bayar`, dan penghapusan koma berlebih sebelum kurung tutup `)`.

### Checklist Kesiapan Teknis
- [ ] Seluruh tabel baru di atas berhasil dibuat tanpa pesan error.
- [ ] Struktur kolom tabel cocok persis dengan tabel spesifikasi acuan saat diperiksa dengan `DESCRIBE`.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci jawaban kueri DDL lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-03-tipe-data-inti--ddl-pembuatan-tabel).

---

## 8. Ringkasan Perintah & Refleksi Pemahaman

### Ringkasan Cepat Modul 03

| Tujuan | Format Umum / Sintaks Inti |
| :--- | :--- |
| Memilih database aktif | `USE nama_db;` |
| Membuat tabel baru | `CREATE TABLE [IF NOT EXISTS] nama_tabel (kolom tipe [aturan], ...) ENGINE=InnoDB;` |
| Melihat seluruh daftar tabel | `SHOW TABLES;` |
| Memeriksa struktur kolom tabel | `DESCRIBE nama_tabel;` |

### Refleksi Pemahaman
1. Mengapa baris kolom terakhir di dalam `CREATE TABLE ( ... )` tidak boleh diakhiri tanda koma `,`?
2. Jika nanti kita ingin mencegah dua barang memiliki `kode_sku` yang kembar, menghubungkan kolom `id_kategori` pada tabel `produk` ke tabel `kategori`, atau mengubah struktur tabel yang sudah terlanjur dibuat, perintah apa yang kita pakai? *(Kita akan mempelajarinya di **Modul 04: DDL Lanjutan — Constraint, Relasi FOREIGN KEY, ALTER TABLE, & DROP TABLE**).*
