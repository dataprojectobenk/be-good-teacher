# Buku Kunci Jawaban & Pembahasan Latihan Soal Basis Data MySQL 8.x
*(Dokumen Pegangan Instruktur / Guru)*

Dokumen ini menghimpun seluruh kunci jawaban kueri SQL, rancangan normalisasi, kamus data, serta pembahasan teknis analitis untuk latihan soal di setiap modul praktikum **Basis Data Relasional MySQL 8.x**. Dokumen ini dipisahkan dari modul siswa agar modul ajar tetap menjadi instrumen belajar aktif mandiri yang efektif.

---

## Daftar Isi Modul

1. [Modul 01: Pengenalan Basis Data, Lingkungan MySQL, & DDL Database](#modul-01-pengenalan-basis-data-lingkungan-mysql--ddl-database)
2. [Modul 02: Perancangan Basis Data: ERD & Normalisasi Tabel](#modul-02-perancangan-basis-data-erd--normalisasi-tabel)
3. [Modul 03: Tipe Data Inti & DDL Pembuatan Tabel](#modul-03-tipe-data-inti--ddl-pembuatan-tabel)
4. [Modul 04: DDL Lanjutan: Constraint, Relasi FOREIGN KEY, ALTER TABLE, & DROP TABLE](#modul-04-ddl-lanjutan-constraint-relasi-foreign-key-alter-table--drop-table)
5. [Modul 05: DML: Manipulasi Data (INSERT, UPDATE, DELETE vs TRUNCATE)](#modul-05-dml-manipulasi-data-insert-update-delete-vs-truncate)
6. [Modul 06: Kueri Dasar (SELECT), Alias, DISTINCT, ORDER BY, & LIMIT](#modul-06-kueri-dasar-select-alias-distinct-order-by--limit)
7. [Modul 07: Penyaringan Data (WHERE Clause, Operator Logika, BETWEEN, IN, LIKE, & NULL)](#modul-07-penyaringan-data-where-clause-operator-logika-between-in-like--null)
8. [Modul 08: Agregasi & Pengelompokan Data (COUNT, SUM, AVG, MIN, MAX, GROUP BY, & HAVING)](#modul-08-agregasi--pengelompokan-data-count-sum-avg-min-max-group-by--having)
9. [Modul 09: Relasi Multi-Tabel (INNER JOIN, LEFT JOIN, RIGHT JOIN, Self Join, & CROSS JOIN)](#modul-09-relasi-multi-tabel-inner-join-left-join-right-join-self-join--cross-join)
*(Kunci jawaban Modul 10 s.d. 14 akan ditambahkan bertahap seiring rilis modul).*

---

## Modul 01: Pengenalan Basis Data, Lingkungan MySQL, & DDL Database
**Tautan Modul:** [materi-01-pengenalan-mysql-editor-dan-ddl-database.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-01-pengenalan-mysql-editor-dan-ddl-database.md)

### Tabel Acuan Database Praktik
| Nama Database | Karakter Encoding | Collation Standar | Tujuan Sistem |
| :--- | :--- | :--- | :--- |
| `toko_db` | `utf8mb4` | `utf8mb4_0900_ai_ci` | Toko Ritel & Penjualan Halal |
| `akademik_db` | `utf8mb4` | `utf8mb4_0900_ai_ci` | Sistem Administrasi Akademik Kampus |
| `logistik_db` | `utf8mb4` | `utf8mb4_0900_ai_ci` | Ekspedisi & Manajemen Gudang |
| `latihan_hapus_db` | `utf8mb4` | `utf8mb4_0900_ai_ci` | Database Sementara untuk Uji Coba Hapus |

### Kunci Jawaban & Pembahasan Soal 1–8

#### Soal 1: Menampilkan Versi MySQL Server Aktif
```sql
SELECT VERSION();
```
*Pembahasan:* Menjalankan fungsi bawaan sistem untuk mengembalikan string identitas rilis mesin MySQL (misal: `8.0.36`).

#### Soal 2: Menampilkan Seluruh Database di Server
```sql
SHOW DATABASES;
```
*Pembahasan:* Mengembalikan tabel berisi nama-nama skema yang terdaftar, mencakup database sistem (`information_schema`, `mysql`, `performance_schema`, `sys`) dan database pengguna.

#### Soal 3: Membuat Database `akademik_db` secara Aman
```sql
CREATE DATABASE IF NOT EXISTS akademik_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;
```
*Pembahasan:* Klausa `IF NOT EXISTS` mencegah timbulnya error fatal jika database telah dibuat sebelumnya. `utf8mb4` mendukung penuh representasi karakter internasional dan simbol modern.

#### Soal 4: Memilih dan Mengaktifkan Database `akademik_db`
```sql
USE akademik_db;
```
*Pembahasan:* Mengubah konteks sesi aktif ke `akademik_db` sehingga seluruh perintah DDL dan DML berikutnya diarahkan ke skema ini.

#### Soal 5: Membuat Database `logistik_db` secara Aman
```sql
CREATE DATABASE IF NOT EXISTS logistik_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;
```
*Pembahasan:* Mendefinisikan database untuk skenario jalur transfer Tema 3 (Logistik).

#### Soal 6: Membuat Database Sementara `latihan_hapus_db`
```sql
CREATE DATABASE IF NOT EXISTS latihan_hapus_db;
```
*Pembahasan:* Menyiapkan objek database uji coba untuk pengujian perintah penghapusan.

#### Soal 7: Menghapus Database Sementara `latihan_hapus_db` secara Aman
```sql
DROP DATABASE IF EXISTS latihan_hapus_db;
```
*Pembahasan:* Klausa `IF EXISTS` memastikan bahwa perintah tidak akan memicu error `Can't drop database ... database doesn't exist` apabila dieksekusi berulang kali.

#### Soal 8: Memperbaiki Kesalahan Sintaks Skrip Database
```sql
CREATE DATABASE IF NOT EXISTS kasir_masjid;
USE kasir_masjid;
```
*Pembahasan:* Kesalahan pada skrip awal meliputi saltik kata kunci (`DATABSE` menjadi `DATABASE`) dan ketiadaan tanda titik koma (`;`) di ujung baris perintah pertama yang memisahkan instruksi pembentukan dan aktivasi.

---

## Modul 02: Perancangan Basis Data: ERD & Normalisasi Tabel
**Tautan Modul:** [materi-02-erd-dan-normalisasi-tabel.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-02-erd-dan-normalisasi-tabel.md)

### Tabel Acuan Kasus Mentah
1. `kulakan_supplier (id_nota, tgl_masuk, nama_supplier, kota_supplier, daftar_barang)`
2. `krs_mentah (nim, nama_mhs, kode_prodi, nama_prodi, semester, daftar_matkul_diambil)`
3. `manifest_transit (id_manifest, no_resi, kota_asal, kota_tujuan, daftar_gudang_singgah)`

### Kunci Jawaban & Pembahasan Soal 1–8

#### Soal 1: Analisis *Insert Anomaly* pada `kulakan_supplier`
*Kunci Analisis:* Toko tidak dapat mencatat supplier baru ke dalam basis data sebelum supplier tersebut memiliki transaksi nota pasokan barang (`id_nota`). Karena `id_nota` bertindak sebagai Primary Key, nilainya tidak boleh `NULL`, sehingga data supplier baru tertolak sistem.

#### Soal 2: Analisis *Update Anomaly* pada `kulakan_supplier`
*Kunci Analisis:* Jika `CV Berkah Alam` pindah domisili dari Kota Solo ke Kota Semarang, operator harus memperbarui kolom `kota_supplier` di setiap baris transaksi nota lama yang pernah terjadi (`N-01` dan `N-03`). Apabila ada satu baris terlewat, database akan mengalami ketidakkonsistenan data (*inconsistent data state*).

#### Soal 3: Analisis *Delete Anomaly* pada `kulakan_supplier`
*Kunci Analisis:* Jika transaksi nota `N-02` dibatalkan dan dihapus, maka seluruh informasi mengenai keberadaan `PT Barokah Herbal` beserta lokasinya di Yogyakarta akan ikut terhapus permanen dari sistem karena belum ada tabel master tersendiri untuk supplier.

#### Soal 4: Rancangan Relasi `pesanan` dan `metode_pembayaran`
*Kunci Rancangan:* 
- Kardinalitas: **`1 : N`** (Satu metode pembayaran seperti 'Transfer Bank' dapat digunakan oleh banyak transaksi pesanan).
- Letak Foreign Key: Kolom `id_metode` ditempatkan pada tabel anak sisi banyak, yaitu tabel **`pesanan`**.

#### Soal 5: Evaluasi Ketergantungan Transitif & Dekomposisi 3NF
*Kunci Rancangan:*
- Evaluasi: Pada tabel `gudang_cabang`, kolom `kode_pos` menentukan `nama_kota` dan `nama_provinsi` (`kode_pos -> nama_kota, nama_provinsi`). Ini adalah ketergantungan transitif karena kolom non-kunci bergantung pada kolom non-kunci lainnya, melanggar syarat 3NF.
- Dekomposisi 3NF:
  1. Tabel `master_wilayah (kode_pos [PK], nama_kota, nama_provinsi)`
  2. Tabel `gudang_cabang (id_gudang [PK], nama_gudang, kode_pos [FK])`

#### Soal 6: Dekomposisi Bentuk Normal Pertama (1NF) untuk `krs_mentah`
*Kunci Rancangan:* Memecah daftar nilai jamak (*multivalued attribute*) `daftar_matkul_diambil` menjadi sel atomik:
| nim (PK1) | kode_mk (PK2) | nama_mhs | kode_prodi | nama_prodi | semester | sks | nama_dosen |
| :---: | :---: | :--- | :---: | :--- | :---: | :---: | :--- |
| 2401001 | IF101 | Ahmad Fauzi | IF | Informatika | 2026-1 | 3 | Dr. Hasan |
| 2401001 | IF102 | Ahmad Fauzi | IF | Informatika | 2026-1 | 3 | Bu Rina |
| 2401002 | IF101 | Zahra Aulia | IF | Informatika | 2026-1 | 3 | Dr. Hasan |

#### Soal 7: Dekomposisi Bentuk Normal 2NF & 3NF Skema Akademik
*Kunci Rancangan:*
1. `program_studi (id_prodi [PK], kode_prodi [UNIQUE], nama_prodi)`
2. `mahasiswa (id_mahasiswa [PK], nim [UNIQUE], nama_mhs, id_prodi [FK])`
3. `dosen (id_dosen [PK], nidn [UNIQUE], nama_dosen)`
4. `mata_kuliah (id_matkul [PK], kode_mk [UNIQUE], nama_mk, sks, id_dosen [FK])`
5. `krs_detail (id_krs [PK], id_mahasiswa [FK], id_matkul [FK], semester, status_ambil)`

#### Soal 8: Pemecahan Relasi Many-to-Many `paket_kiriman` dan `gudang`
*Kunci Rancangan:*
- Tabel Penghubung: `riwayat_transit`
- Struktur Kamus Data:
  - `id_transit INT AUTO_INCREMENT PRIMARY KEY`
  - `id_paket INT NOT NULL` (Foreign Key merujuk ke `paket_kiriman(id_paket)`)
  - `id_gudang INT NOT NULL` (Foreign Key merujuk ke `gudang(id_gudang)`)
  - `status_transit ENUM('Masuk Gudang', 'Dalam Pemeriksaan', 'Keluar Menuju Kota Berikutnya')`
  - `waktu_tiba DATETIME DEFAULT CURRENT_TIMESTAMP`

---

## Modul 03: Tipe Data Inti & DDL Pembuatan Tabel
**Tautan Modul:** [materi-03-tipe-data-dan-ddl-pembuatan-tabel.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-03-tipe-data-dan-ddl-pembuatan-tabel.md)

### Kunci Jawaban & Pembahasan Soal 1–8

#### Soal 1: Membuat Tabel `program_studi` pada `akademik_db`
```sql
USE akademik_db;

CREATE TABLE IF NOT EXISTS program_studi (
    id_prodi INT AUTO_INCREMENT PRIMARY KEY,
    kode_prodi CHAR(5) NOT NULL,
    nama_prodi VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
```

#### Soal 2: Membuat Tabel `mahasiswa` pada `akademik_db`
```sql
USE akademik_db;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id_mahasiswa INT AUTO_INCREMENT PRIMARY KEY,
    nim CHAR(10) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    terdaftar_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```

#### Soal 3: Menampilkan Seluruh Tabel di `akademik_db`
```sql
USE akademik_db;
SHOW TABLES;
```

#### Soal 4: Memeriksa Struktur Kolom Tabel `mahasiswa`
```sql
DESCRIBE mahasiswa;
```

#### Soal 5: Membuat Tabel `gudang` pada `logistik_db`
```sql
USE logistik_db;

CREATE TABLE IF NOT EXISTS gudang (
    id_gudang INT AUTO_INCREMENT PRIMARY KEY,
    kode_gudang CHAR(6) NOT NULL,
    kota VARCHAR(60) NOT NULL,
    alamat TEXT
) ENGINE=InnoDB;
```

#### Soal 6: Membuat Tabel `paket_kiriman` pada `logistik_db`
```sql
USE logistik_db;

CREATE TABLE IF NOT EXISTS paket_kiriman (
    id_paket INT AUTO_INCREMENT PRIMARY KEY,
    nomor_resi CHAR(12) NOT NULL,
    berat_kg DECIMAL(8, 2) NOT NULL,
    ongkos_kirim DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    tanggal_kirim DATE NOT NULL
) ENGINE=InnoDB;
```

#### Soal 7: Memeriksa Struktur Kolom Tabel `paket_kiriman`
```sql
DESCRIBE paket_kiriman;
```

#### Soal 8: Perbaikan Skrip Tabel `transaksi_uji`
```sql
-- Skrip yang telah diperbaiki
CREATE TABLE IF NOT EXISTS transaksi_uji (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi CHAR(8) NOT NULL,
    total_bayar DECIMAL(12, 2) NOT NULL,
    waktu_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```
*Pembahasan:* Menambahkan kata kunci `PRIMARY KEY` pada `id_transaksi`, menambahkan tanda koma `,` setelah deklarasi kolom `total_bayar`, dan menghapus tanda koma terlarang pada baris terakhir sebelum kurung tutup `)`.

---

## Modul 04: DDL Lanjutan: Constraint, Relasi FOREIGN KEY, ALTER TABLE, & DROP TABLE
**Tautan Modul:** [materi-04-ddl-lanjutan-constraint-fk-alter-drop.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-04-ddl-lanjutan-constraint-fk-alter-drop.md)

### Kunci Jawaban & Pembahasan Soal 1–10

#### Soal 1: Menambah Kolom `email` pada Tabel `pelanggan`
```sql
USE toko_db;

ALTER TABLE pelanggan
ADD COLUMN email VARCHAR(100) AFTER no_hp;
```

#### Soal 2: Menambahkan Constraint `UNIQUE` pada Kolom `email`
```sql
USE toko_db;

ALTER TABLE pelanggan
ADD CONSTRAINT uq_pelanggan_email UNIQUE (email);
```

#### Soal 3: Menghubungkan Tabel `mahasiswa` ke `program_studi` via FOREIGN KEY
```sql
USE akademik_db;

ALTER TABLE mahasiswa
ADD COLUMN id_prodi INT NOT NULL,
ADD CONSTRAINT fk_mhs_prodi
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;
```

#### Soal 4: Membuat Tabel `dosen` pada `akademik_db`
```sql
USE akademik_db;

CREATE TABLE IF NOT EXISTS dosen (
    id_dosen INT AUTO_INCREMENT PRIMARY KEY,
    nidn CHAR(10) NOT NULL UNIQUE,
    nama_dosen VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
```

#### Soal 5: Membuat Tabel `mata_kuliah` Berelasi ke `dosen`
```sql
USE akademik_db;

CREATE TABLE IF NOT EXISTS mata_kuliah (
    id_matkul INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk CHAR(6) NOT NULL UNIQUE,
    nama_mk VARCHAR(100) NOT NULL,
    sks INT NOT NULL,
    id_dosen INT NOT NULL,
    CONSTRAINT chk_matkul_sks CHECK (sks >= 1 AND sks <= 6),
    CONSTRAINT fk_matkul_dosen FOREIGN KEY (id_dosen) 
        REFERENCES dosen(id_dosen) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB;
```

#### Soal 6: Membuat Tabel Penghubung `krs_detail` (Many-to-Many)
```sql
USE akademik_db;

CREATE TABLE IF NOT EXISTS krs_detail (
    id_krs INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_matkul INT NOT NULL,
    semester VARCHAR(10) NOT NULL,
    status_ambil ENUM('Aktif', 'Batal') NOT NULL DEFAULT 'Aktif',
    CONSTRAINT fk_krs_mhs FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE,
    CONSTRAINT fk_krs_matkul FOREIGN KEY (id_matkul) REFERENCES mata_kuliah(id_matkul) ON DELETE RESTRICT,
    CONSTRAINT uq_krs_semester UNIQUE (id_mahasiswa, id_matkul, semester)
) ENGINE=InnoDB;
```

#### Soal 7: Menambahkan Validasi Nilai `CHECK` pada `paket_kiriman`
```sql
USE logistik_db;

ALTER TABLE paket_kiriman
ADD CONSTRAINT chk_paket_berat CHECK (berat_kg > 0),
ADD CONSTRAINT chk_paket_ongkir CHECK (ongkos_kirim >= 0);
```

#### Soal 8: Membuat Tabel Penghubung `riwayat_transit` pada `logistik_db`
```sql
USE logistik_db;

CREATE TABLE IF NOT EXISTS riwayat_transit (
    id_transit INT AUTO_INCREMENT PRIMARY KEY,
    id_paket INT NOT NULL,
    id_gudang INT NOT NULL,
    status_transit ENUM('Masuk Gudang', 'Dalam Pemeriksaan', 'Keluar Menuju Kota Berikutnya') NOT NULL,
    waktu_tiba DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transit_paket FOREIGN KEY (id_paket) REFERENCES paket_kiriman(id_paket) ON DELETE CASCADE,
    CONSTRAINT fk_transit_gudang FOREIGN KEY (id_gudang) REFERENCES gudang(id_gudang) ON DELETE RESTRICT
) ENGINE=InnoDB;
```

#### Soal 9: Memperbesar Ukuran Kolom `nama_lengkap` pada `mahasiswa`
```sql
USE akademik_db;

ALTER TABLE mahasiswa
MODIFY COLUMN nama_lengkap VARCHAR(150) NOT NULL;
```

#### Soal 10: Menghapus Tabel Berelasi secara Aman
```sql
-- Hapus tabel anak terlebih dahulu, lalu tabel induk
DROP TABLE IF EXISTS krs_detail;
DROP TABLE IF EXISTS mata_kuliah;
```

---

## Modul 05: DML: Manipulasi Data (INSERT, UPDATE, DELETE vs TRUNCATE)
**Tautan Modul:** [materi-05-dml-manipulasi-data-insert-update-delete.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-05-dml-manipulasi-data-insert-update-delete.md)

### Tabel Acuan Praktik
- `karyawan (id, nama, jabatan, gaji)`
- `produk (id_produk, nama_produk, harga, stok)`
- `order (id_order, tanggal, total, id_pelanggan)`
- `pelanggan (id_pelanggan, nama_pelanggan, alamat)`

### Kunci Jawaban & Pembahasan Soal 1–10

#### Soal 1: Menambahkan Karyawan Baru
```sql
INSERT INTO karyawan (id, nama, jabatan, gaji) 
VALUES (6, 'Nina', 'Staff', 55000);
```
*Pembahasan:* Menambahkan satu baris record baru dengan eksplisit menyebutkan 4 kolom target.

#### Soal 2: Memperbarui Gaji Karyawan ID 4
```sql
UPDATE karyawan 
SET gaji = 85000 
WHERE id = 4;
```
*Pembahasan:* Mengubah nilai kolom gaji spesifik pada baris yang memiliki `id = 4` (Rina).

#### Soal 3: Menghapus Data Produk ID 3
```sql
DELETE FROM produk 
WHERE id_produk = 3;
```
*Pembahasan:* Menghapus produk Tablet (`id_produk = 3`) secara permanen dari tabel produk.

#### Soal 4: Memperbarui Nama Pelanggan ID 4 Menjadi 'Nadia'
```sql
UPDATE pelanggan 
SET nama_pelanggan = 'Nadia' 
WHERE id_pelanggan = 4;
```
*Pembahasan:* Memperbarui record pelanggan nomor 4 dari 'Rina' menjadi 'Nadia'.

#### Soal 5: Memperbarui Stok dan Harga Produk ID 1 Sekaligus
```sql
UPDATE produk 
SET stok = 10, 
    harga = 1400000 
WHERE id_produk = 1;
```
*Pembahasan:* Menggunakan satu perintah `UPDATE` dengan tanda koma `,` untuk memodifikasi dua kolom pada record Laptop secara atomik.

#### Soal 6: Menambahkan Produk Baru ID 6
```sql
INSERT INTO produk (id_produk, nama_produk, harga, stok) 
VALUES (6, 'Mouse Wireless', 150000, 25);
```
*Pembahasan:* Memasukkan entri produk aksesoris baru ke dalam tabel produk.

#### Soal 7: Memperbarui Alamat Pelanggan Ali (ID 1)
```sql
UPDATE pelanggan 
SET alamat = 'Jl. Sudirman No. 10' 
WHERE id_pelanggan = 1;
```
*Pembahasan:* Mengubah string alamat pelanggan Ali pada baris `id_pelanggan = 1`.

#### Soal 8: Menghapus Data Order ID 5
```sql
DELETE FROM `order` 
WHERE id_order = 5;
```
*Pembahasan:* Menghapus satu baris transaksi pemesanan nomor 5. Nama tabel `order` diapit backtick karena merupakan reserved keyword di MySQL.

#### Soal 9: Menaikkan Stok Keyboard (ID 5) Menjadi 20
```sql
-- Cara 1 (Nilai Absolut):
UPDATE produk 
SET stok = 20 
WHERE id_produk = 5;

-- Cara 2 (Kalkulasi Relatif):
UPDATE produk 
SET stok = stok + 5 
WHERE id_produk = 5;
```
*Pembahasan:* Kedua pendekatan bernilai valid. Pendekatan relatif (`stok = stok + 5`) lebih direkomendasikan pada aplikasi sistem kasir concurrency untuk mencegah race condition.

#### Soal 10: Mengosongkan Tabel dan Mereset Auto Increment
```sql
TRUNCATE TABLE tabel_riwayat_sementara;
```
*Pembahasan:* `TRUNCATE TABLE` melakukan DDL de-alokasi halaman data secara instan dan mengembalikan pointer `AUTO_INCREMENT` kembali ke angka 1.

---

## Modul 06: Kueri Dasar (SELECT), Alias, DISTINCT, ORDER BY, & LIMIT
**Tautan Modul:** [materi-06-kueri-dasar-select-alias-distinct-order-by-limit.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-06-kueri-dasar-select-alias-distinct-order-by-limit.md)

### Tabel Acuan Praktik
- `karyawan (id, nama, jabatan, gaji)`
- `produk (id_produk, nama_produk, harga, stok)`
- `order (id_order, tanggal, total, id_pelanggan)`
- `pelanggan (id_pelanggan, nama_pelanggan, alamat)`

### Kunci Jawaban & Pembahasan Soal 1–10

#### Soal 1: Menampilkan Seluruh Kolom dan Baris Tabel `karyawan`
```sql
SELECT * 
FROM karyawan;
```
*Pembahasan:* Menggunakan wildcard `*` untuk mengambil seluruh kolom dari tabel `karyawan`. Menghasilkan 5 baris data.

#### Soal 2: Memilih Kolom `nama` dan `gaji` dengan Nama Alias
```sql
SELECT 
    nama AS 'Nama Pegawai', 
    gaji AS 'Gaji Pokok' 
FROM karyawan;
```
*Pembahasan:* Proyeksi dua kolom dengan penamaan alias yang rapi. Tanda petik wajib digunakan karena nama alias memiliki spasi pemisah.

#### Soal 3: Proyeksi Kolom Produk dan Kalkulasi Total Nilai
```sql
SELECT 
    nama_produk, 
    harga, 
    stok, 
    (harga * stok) AS 'Total Nilai Produk' 
FROM produk;
```
*Pembahasan:* Mengalikan nilai numerik harga dan stok di memori komputasi runtime MySQL tanpa mengubah struktur tabel fisik.

#### Soal 4: Menampilkan Daftar Variasi `jabatan` Unik tanpa Duplikasi
```sql
SELECT DISTINCT jabatan 
FROM karyawan;
```
*Pembahasan:* Klausa `DISTINCT` mengeliminasi baris ganda dari jabatan `Manager` dan `Staff`, menghasilkan 3 baris unik: `Manager`, `Staff`, dan `Supervisor`.

#### Soal 5: Menampilkan Daftar Unik `id_pelanggan` pada Tabel `order`
```sql
SELECT DISTINCT id_pelanggan 
FROM `order`;
```
*Pembahasan:* Mengidentifikasi ID pelanggan unik yang tercatat bertransaksi. Hasil yang dikembalikan adalah 3 baris: `1`, `2`, dan `3`.

#### Soal 6: Memilih `nama_produk` dan `harga`, Urutkan Termurah ke Termahal
```sql
SELECT nama_produk, harga 
FROM produk 
ORDER BY harga ASC;
```
*Pembahasan:* Mengurutkan data secara *ascending* (menaik). Produk `Keyboard` (300.000) tampil pertama, dan `Laptop` (15.000.000) tampil terakhir.

#### Soal 7: Mengurutkan Multi-Kolom (`jabatan` ASC, `gaji` DESC)
```sql
SELECT nama, jabatan, gaji 
FROM karyawan 
ORDER BY jabatan ASC, gaji DESC;
```
*Pembahasan:* Kriteria primer mengurutkan jabatan A–Z. Pada jabatan yang sama (`Manager`), Joko (120.000) tampil mendahului Budi (100.000). Pada `Staff`, Andi (60.000) tampil mendahului Siti (50.000).

#### Soal 8: Menampilkan 3 Produk dengan Harga Termahal
```sql
SELECT nama_produk, harga 
FROM produk 
ORDER BY harga DESC 
LIMIT 3;
```
*Pembahasan:* Mengurutkan harga dari yang terbesar (`DESC`), lalu memotong dan mengambil hanya 3 baris teratas: Laptop (15jt), Tablet (8jt), dan Smartphone (5jt).

#### Soal 9: Menampilkan 2 Transaksi Order Terbaru
```sql
SELECT * 
FROM `order` 
ORDER BY tanggal DESC 
LIMIT 2;
```
*Pembahasan:* Mengurutkan kronologi waktu dari tanggal paling mutakhir (`2024-10-05` dan `2024-10-04`) dan membatasi keluaran sebanyak 2 baris.

#### Soal 10: Simulasi Paginasi Halaman 2 Tabel `pelanggan` (Page Size = 2)
```sql
SELECT * 
FROM pelanggan 
ORDER BY nama_pelanggan ASC 
LIMIT 2 OFFSET 2;
```
*Alternatif Sintaks:*
```sql
SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC LIMIT 2, 2;
```
*Pembahasan:* Data diurutkan berdasarkan `nama_pelanggan` A–Z (Ali, Budi, Joko, Rina, Siti).
- Halaman 1 (`LIMIT 2 OFFSET 0`): Ali dan Budi.
- Halaman 2 (`LIMIT 2 OFFSET 2`): Melewati 2 data pertama, mengambil 2 data berikutnya yaitu Joko dan Rina.

---

## Modul 07: Penyaringan Data (WHERE Clause, Operator Logika, BETWEEN, IN, LIKE, & NULL)
**Tautan Modul:** [materi-07-penyaringan-data-where-clause-operator-logika-null.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-07-penyaringan-data-where-clause-operator-logika-null.md)

### Tabel Acuan Praktik
- `karyawan (id, nama, jabatan, gaji, bonus)`
- `produk (id_produk, nama_produk, harga, stok)`
- `order (id_order, tanggal, total, id_pelanggan)`
- `pelanggan (id_pelanggan, nama_pelanggan, alamat, no_hp)`

### Kunci Jawaban & Pembahasan Soal 1–10

#### Soal 1: Karyawan dengan Gaji Lebih Besar dari 60000
```sql
SELECT * 
FROM karyawan 
WHERE gaji > 60000;
```
*Pembahasan:* Menggunakan operator perbandingan lebih besar `>`. Menghasilkan 3 baris data: Budi (100000), Rina (80000), dan Joko (120000). Nilai 60000 (Andi) tidak ikut karena menggunakan tanda `>` murni, bukan `>=`.

#### Soal 2: Memilih Nama Produk dan Harga dengan Harga <= 5000000
```sql
SELECT nama_produk, harga 
FROM produk 
WHERE harga <= 5000000;
```
*Pembahasan:* Menggunakan operator kurang dari atau sama dengan `<=`. Produk yang bernilai pas 5.000.000 (Smartphone) ikut lolos bersama Monitor (1.2jt) dan Keyboard (300rb).

#### Soal 3: Karyawan Jabatan Staff DAN Gaji Minimal 55000
```sql
SELECT * 
FROM karyawan 
WHERE jabatan = 'Staff' 
  AND gaji >= 55000;
```
*Pembahasan:* Operator `AND` mensyaratkan kedua kriteria bernilai TRUE. Siti (Staff, gaji 50000) gugur pada syarat gaji, sehingga hanya Andi (Staff, gaji 60000) yang lolos.

#### Soal 4: Produk dengan Stok < 5 ATAU Harga > 10000000
```sql
SELECT * 
FROM produk 
WHERE stok < 5 
   OR harga > 10000000;
```
*Pembahasan:* Operator `OR` meloloskan baris jika salah satu atau kedua syarat terpenuhi. Laptop lolos karena harga > 10jt (meskipun stok 5), dan Tablet lolos karena stok 2 (meskipun harga 8jt).

#### Soal 5: Transaksi Order dengan Total BETWEEN 1000000 AND 2500000
```sql
SELECT * 
FROM `order` 
WHERE total BETWEEN 1000000 AND 2500000;
```
*Pembahasan:* Operator `BETWEEN ... AND ...` bersifat inklusif. Menghasilkan transaksi id_order 1 (total 2jt) dan id_order 4 (total 1.5jt).

#### Soal 6: Karyawan dengan Jabatan IN ('Manager', 'Supervisor')
```sql
SELECT * 
FROM karyawan 
WHERE jabatan IN ('Manager', 'Supervisor');
```
*Pembahasan:* Operator keanggotaan `IN` mencocokkan nilai kolom `jabatan` dengan salah satu anggota di dalam tanda kurung himpunan. Menghasilkan Budi, Rina, dan Joko.

#### Soal 7: Produk dengan Nama Diawali Huruf 'S'
```sql
SELECT * 
FROM produk 
WHERE nama_produk LIKE 'S%';
```
*Pembahasan:* Pola `'S%'` mencari teks yang diawali huruf 'S' diikuti karakter sembarang apa pun. Menghasilkan produk `Smartphone`.

#### Soal 8: Pelanggan yang Memiliki Nomor HP (IS NOT NULL)
```sql
SELECT * 
FROM pelanggan 
WHERE no_hp IS NOT NULL;
```
*Pembahasan:* Memeriksa nilai fisik yang terdefinisi pada kolom `no_hp`. Mengembalikan 3 baris: Ali, Budi, dan Joko.

#### Soal 9: Pelanggan yang Belum Memiliki Nomor HP (IS NULL)
```sql
SELECT * 
FROM pelanggan 
WHERE no_hp IS NULL;
```
*Pembahasan:* Memeriksa ketiadaan nilai menggunakan kata kunci baku `IS NULL` (bukan `= NULL`). Mengembalikan 2 baris: Siti dan Rina.

#### Soal 10: Sintesis Filter, Urutan DESC, dan Batas Teratas (LIMIT)
```sql
SELECT * 
FROM produk 
WHERE harga > 1000000 
ORDER BY harga DESC 
LIMIT 2;
```
*Pembahasan:* 
1. `WHERE harga > 1000000` meloloskan Laptop (15jt), Tablet (8jt), Smartphone (5jt), dan Monitor (1.2jt).
2. `ORDER BY harga DESC` menyusun baris: Laptop -> Tablet -> Smartphone -> Monitor.
3. `LIMIT 2` memotong dan mengambil hanya 2 produk termahal: Laptop dan Tablet.

---

## Modul 08: Agregasi & Pengelompokan Data (COUNT, SUM, AVG, MIN, MAX, GROUP BY, & HAVING)
**Tautan Modul:** [materi-08-agregasi-dan-pengelompokan-data-group-by-having.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-08-agregasi-dan-pengelompokan-data-group-by-having.md)

### Tabel Acuan Praktik
- `karyawan (id, nama, jabatan, gaji, bonus)`
- `produk (id_produk, nama_produk, harga, stok)`
- `order (id_order, tanggal, total, id_pelanggan)`
- `pelanggan (id_pelanggan, nama_pelanggan, alamat, no_hp)`

### Kunci Jawaban & Pembahasan Soal 1–10

#### Soal 1: Menghitung Total Jumlah Karyawan
```sql
SELECT COUNT(*) AS 'Total Karyawan' 
FROM karyawan;
```
*Pembahasan:* Fungsi `COUNT(*)` menghitung seluruh baris fisik yang terdaftar pada tabel `karyawan`, menghasilkan 1 baris berisi angka 5.

#### Soal 2: Menghitung Total Pengeluaran Gaji Karyawan
```sql
SELECT SUM(gaji) AS 'Total Anggaran Gaji' 
FROM karyawan;
```
*Pembahasan:* Menjumlahkan nilai numerik kolom gaji untuk seluruh karyawan: $100.000 + 50.000 + 60.000 + 80.000 + 120.000 = 410.000$.

#### Soal 3: Menghitung Rata-Rata Harga Produk
```sql
SELECT AVG(harga) AS 'Rata-Rata Harga' 
FROM produk;
```
*Pembahasan:* Menjumlahkan harga 5 produk ($15\text{jt} + 5\text{jt} + 8\text{jt} + 1.2\text{jt} + 300\text{rb} = 29.500.000$) lalu membaginya dengan 5, menghasilkan $5.900.000$.

#### Soal 4: Menampilkan Harga Produk Termurah dan Termahal
```sql
SELECT 
    MIN(harga) AS 'Harga Minimum', 
    MAX(harga) AS 'Harga Maksimum' 
FROM produk;
```
*Pembahasan:* `MIN(harga)` mencari harga terendah (Keyboard: 300.000) dan `MAX(harga)` mencari harga tertinggi (Laptop: 15.000.000) secara simultan.

#### Soal 5: Rekapitulasi Jumlah Karyawan dan Rerata Gaji per Jabatan
```sql
SELECT 
    jabatan, 
    COUNT(*) AS 'Jumlah Karyawan', 
    AVG(gaji) AS 'Rata-Rata Gaji' 
FROM karyawan 
GROUP BY jabatan;
```
*Pembahasan:* Mengelompokkan karyawan ke dalam 3 jabatan: Manager (2 orang, rerata 110rb), Staff (2 orang, rerata 55rb), dan Supervisor (1 orang, rerata 80rb). Kolom `jabatan` wajib masuk ke klausa `GROUP BY`.

#### Soal 6: Total Belanja dan Jumlah Transaksi per Pelanggan
```sql
SELECT 
    id_pelanggan, 
    SUM(total) AS 'Total Belanja', 
    COUNT(*) AS 'Jumlah Transaksi' 
FROM `order` 
GROUP BY id_pelanggan;
```
*Pembahasan:* Mengelompokkan baris transaksi order berdasarkan `id_pelanggan`: Pelanggan 1 (2 transaksi, total 3.5jt), Pelanggan 2 (2 transaksi, total 1.25jt), Pelanggan 3 (1 transaksi, total 3jt).

#### Soal 7: Filter Kelompok Jabatan dengan Rata-Rata Gaji > 70000 (HAVING)
```sql
SELECT 
    jabatan, 
    AVG(gaji) AS 'Rata-Rata Gaji' 
FROM karyawan 
GROUP BY jabatan 
HAVING AVG(gaji) > 70000;
```
*Pembahasan:* Klausa `HAVING` menyaring hasil kalkulasi rata-rata gaji kelompok. Kelompok `Staff` (rerata 55000) gugur, menyisakan `Manager` (110000) dan `Supervisor` (80000).

#### Soal 8: Pelanggan dengan Total Pembelian >= 2000000
```sql
SELECT 
    id_pelanggan, 
    SUM(total) AS 'Total Belanja' 
FROM `order` 
GROUP BY id_pelanggan 
HAVING SUM(total) >= 2000000;
```
*Pembahasan:* Mengelompokkan transaksi per pelanggan, lalu memfilter kelompok yang total akumulasi belanjanya $\ge 2.000.000$. Pelanggan 2 (1.25jt) gugur, menyisakan Pelanggan 1 (3.5jt) dan Pelanggan 3 (3jt).

#### Soal 9: Rekapitulasi Transaksi Bernilai > 600000 (WHERE + GROUP BY)
```sql
SELECT 
    id_pelanggan, 
    COUNT(*) AS 'Jumlah Transaksi', 
    SUM(total) AS 'Total Belanja' 
FROM `order` 
WHERE total > 600000 
GROUP BY id_pelanggan;
```
*Pembahasan:* Klausa `WHERE total > 600000` membuang transaksi id_order 5 (total 500rb) lebih dahulu sebelum pengelompokan. Setelah itu, baris yang tersisa dikelompokkan per `id_pelanggan`. Akibatnya, Pelanggan 2 hanya memiliki 1 transaksi (750rb).

#### Soal 10: Sintesis Penuh (WHERE + GROUP BY + HAVING + ORDER BY + LIMIT)
```sql
SELECT 
    jabatan, 
    SUM(gaji) AS 'Total Anggaran' 
FROM karyawan 
WHERE gaji >= 60000 
GROUP BY jabatan 
HAVING SUM(gaji) > 100000 
ORDER BY SUM(gaji) DESC 
LIMIT 1;
```
*Pembahasan:*
1. `WHERE gaji >= 60000`: Menyisakan Budi (100rb), Andi (60rb), Rina (80rb), dan Joko (120rb). Siti (50rb) gugur.
2. `GROUP BY jabatan`: Manager (Budi + Joko = 220rb), Supervisor (Rina = 80rb), Staff (Andi = 60rb).
3. `HAVING SUM(gaji) > 100000`: Hanya meloloskan Manager (220rb).
4. `ORDER BY ... DESC LIMIT 1`: Menampilkan tepat 1 baris teratas, yaitu `Manager` dengan Total Anggaran `220000`.

---

## Modul 09: Relasi Multi-Tabel (INNER JOIN, LEFT JOIN, RIGHT JOIN, Self Join, & CROSS JOIN)
**Tautan Modul:** [materi-09-relasi-multi-tabel-join.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-09-relasi-multi-tabel-join.md)

### Tabel Acuan Praktik
- `karyawan (id, nama, jabatan, gaji, id_atasan)`
- `pelanggan (id_pelanggan, nama_pelanggan, alamat)`
- `order (id_order, tanggal, total, id_pelanggan)`
- `produk (id_produk, nama_produk, harga, stok)`

### Kunci Jawaban & Pembahasan Soal 1–10

#### Soal 1: Menggabungkan Order dan Pelanggan dengan INNER JOIN
```sql
SELECT 
    o.id_order, 
    o.tanggal, 
    o.total, 
    p.nama_pelanggan 
FROM `order` AS o 
INNER JOIN pelanggan AS p 
    ON o.id_pelanggan = p.id_pelanggan;
```
*Pembahasan:* Menghubungkan kolom FK `o.id_pelanggan` ke PK `p.id_pelanggan`. Hanya 5 transaksi yang memiliki data pelanggan yang dimunculkan.

#### Soal 2: INNER JOIN dengan Filter Total > 1000000
```sql
SELECT 
    o.id_order, 
    p.nama_pelanggan, 
    o.total 
FROM `order` AS o 
INNER JOIN pelanggan AS p 
    ON o.id_pelanggan = p.id_pelanggan 
WHERE o.total > 1000000;
```
*Pembahasan:* Setelah tabel digabungkan, klausa `WHERE` memfilter transaksi bernilai di atas 1 juta, menyisakan 3 baris: Order 1 (Ali - 2jt), Order 3 (Budi - 3jt), dan Order 4 (Ali - 1.5jt).

#### Soal 3: Menampilkan Seluruh Pelanggan dengan LEFT JOIN
```sql
SELECT 
    p.nama_pelanggan, 
    o.id_order, 
    o.total 
FROM pelanggan AS p 
LEFT JOIN `order` AS o 
    ON p.id_pelanggan = o.id_pelanggan;
```
*Pembahasan:* Seluruh baris dari tabel `pelanggan` dipertahankan. Pelanggan yang belum pernah bertransaksi (Rina dan Joko) tetap tampil dengan nilai `id_order` dan `total` berisi `NULL`.

#### Soal 4: Mendeteksi Pelanggan yang Belum Pernah Belanja (Anti-Join)
```sql
SELECT 
    p.id_pelanggan, 
    p.nama_pelanggan, 
    p.alamat 
FROM pelanggan AS p 
LEFT JOIN `order` AS o 
    ON p.id_pelanggan = o.id_pelanggan 
WHERE o.id_order IS NULL;
```
*Pembahasan:* Pola *Anti-Join* menggunakan `LEFT JOIN` yang dipadukan dengan filter `WHERE o.id_order IS NULL` untuk mengisolasi baris tabel kiri yang tidak memiliki kecocokan di tabel kanan (menghasilkan Rina dan Joko).

#### Soal 5: Menggabungkan Transaksi Menggunakan RIGHT JOIN
```sql
SELECT 
    o.id_order, 
    o.tanggal, 
    o.total, 
    p.nama_pelanggan 
FROM pelanggan AS p 
RIGHT JOIN `order` AS o 
    ON p.id_pelanggan = o.id_pelanggan;
```
*Pembahasan:* Mempertahankan seluruh baris dari tabel kanan (`order`) beserta pasangannya dari tabel kiri (`pelanggan`). Menghasilkan 5 baris transaksi.

#### Soal 6: Self Join Staf dan Atasan (Hanya yang Memiliki Atasan)
```sql
SELECT 
    k.nama, 
    k.jabatan, 
    a.nama AS nama_atasan 
FROM karyawan AS k 
INNER JOIN karyawan AS a 
    ON k.id_atasan = a.id;
```
*Pembahasan:* Menghubungkan tabel `karyawan` dengan dirinya sendiri. `INNER JOIN` hanya meloloskan staf yang kolom `id_atasan`-nya terisi (Siti, Andi, dan Rina yang atasannya adalah Budi). Budi dan Joko tidak muncul karena `id_atasan` mereka bernilai `NULL`.

#### Soal 7: Self Join Seluruh Karyawan (Termasuk Manajer Puncak)
```sql
SELECT 
    k.nama, 
    k.jabatan, 
    a.nama AS nama_atasan 
FROM karyawan AS k 
LEFT JOIN karyawan AS a 
    ON k.id_atasan = a.id;
```
*Pembahasan:* Menggunakan `LEFT JOIN` pada diri sendiri agar manajer puncak yang tidak memiliki atasan (Budi dan Joko) tetap tampil pada hasil keluaran dengan kolom `nama_atasan` bernilai `NULL`.

#### Soal 8: Agregasi Transaksi Seluruh Pelanggan (LEFT JOIN + GROUP BY)
```sql
SELECT 
    p.nama_pelanggan, 
    COUNT(o.id_order) AS 'Jumlah Transaksi', 
    SUM(o.total) AS 'Total Belanja' 
FROM pelanggan AS p 
LEFT JOIN `order` AS o 
    ON p.id_pelanggan = o.id_pelanggan 
GROUP BY p.id_pelanggan, p.nama_pelanggan;
```
*Pembahasan:* Wajib menggunakan `COUNT(o.id_order)` agar pelanggan yang belum pernah belanja menghasilkan angka `0` (bukan 1 jika memakai `COUNT(*)`). Rina dan Joko menghasilkan 0 transaksi dan total belanja NULL.

#### Soal 9: Perkalian Silang (CROSS JOIN) Produk dan Pelanggan
```sql
SELECT 
    pr.nama_produk, 
    p.nama_pelanggan 
FROM produk AS pr 
CROSS JOIN pelanggan AS p 
LIMIT 5;
```
*Pembahasan:* `CROSS JOIN` memasangkan setiap baris produk dengan seluruh baris pelanggan ($5 \times 5 = 25$ baris). Klausa `LIMIT 5` memotong dan menampilkan hanya 5 baris pertama.

#### Soal 10: Sintesis Penuh (INNER JOIN + GROUP BY + HAVING + ORDER BY)
```sql
SELECT 
    p.nama_pelanggan, 
    SUM(o.total) AS 'Total Belanja' 
FROM pelanggan AS p 
INNER JOIN `order` AS o 
    ON p.id_pelanggan = o.id_pelanggan 
GROUP BY p.id_pelanggan, p.nama_pelanggan 
HAVING SUM(o.total) > 2000000 
ORDER BY 'Total Belanja' DESC;
```
*Pembahasan:*
1. `INNER JOIN` menyatukan data pesanan dan identitas pelanggan.
2. `GROUP BY` menghitung total belanja per pelanggan.
3. `HAVING SUM(o.total) > 2000000` meloloskan Ali (3.5jt) dan Budi (3jt). Siti (1.25jt) gugur.
4. `ORDER BY ... DESC` mengurutkan Ali terlebih dahulu, lalu Budi.
