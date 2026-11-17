# Modul 07: Penyaringan Data (WHERE Clause, Operator Logika, BETWEEN, IN, LIKE, & NULL)

**Mata Pelajaran:** Basis Data Relasional (MySQL 8.x)  
**Alokasi Waktu:** 2 x 45 Menit  
**Sasaran:** Siswa SMK Rekayasa Perangkat Lunak / Mahasiswa Teknik Informatika  
**Target Kompetensi:** Menguasai teknik seleksi dan penyaringan data (*Data Filtering*) secara presisi menggunakan klausa `WHERE`, operator perbandingan, operator logika kombinasi, rentang nilai, pencocokan pola teks, serta penanganan nilai tak terdefinisi (*Three-Valued Logic / NULL*).

---

## 1. Konsep Fundamental & Mental Model

### A. Prasyarat Pengetahuan
Sebelum mempelajari teknik penyaringan baris data pada modul ini, peserta didik diharapkan telah memahami:
1. Konsep proyeksi kolom spesifik dan penamaan alias kolom menggunakan `SELECT ... AS` ([Modul 06](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-06-kueri-dasar-select-alias-distinct-order-by-limit.md)).
2. Teknik pengurutan data (`ORDER BY`) dan pemotongan baris data (`LIMIT`) ([Modul 06](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-06-kueri-dasar-select-alias-distinct-order-by-limit.md)).

### B. Jembatan Konsep (Mental Model)
Bayangkan sebuah basis data perpustakaan digital nasional yang menampung 5 juta judul buku. Apabila seorang pengunjung ingin mencari buku tentang "Arsitektur Basis Data", kita tidak akan menyuruh komputer mengambil seluruh 5 juta buku tersebut lalu menampilkannya ke layar pengunjung. Tindakan tanpa saringan ini disebut **Pemindaian Tabel Penuh (*Full Table Scan*)**, yang akan membebani memori server, menguras *bandwidth* jaringan, dan membingungkan pengguna.

Klausa **`WHERE`** berfungsi layaknya **gerbang pos pemeriksaan atau saringan air bersih**:
- Setiap baris data (*record*) yang mengalir dari tabel fisik akan diuji oleh pos pemeriksaan `WHERE`.
- Hanya baris data yang memenuhi kriteria pengujian (menghasilkan nilai kebenaran **`TRUE`**) yang diizinkan lewat menuju tahap pemilihan kolom (`SELECT`).
- Baris data yang bernilai salah (**`FALSE`**) atau tidak diketahui (**`UNKNOWN / NULL`**) akan langsung disingkirkan dari hasil akhir kueri.

### C. Alur Logika Pemrosesan Kueri (*Logical Query Processing Flow*)
Memahami urutan eksekusi internal mesin MySQL sangat krusial agar Anda memahami interaksi antarklausa:

```text
[Urutan Penulisan Sintaks SQL]
1. SELECT kolom / ekspresi
2. FROM nama_tabel
3. WHERE kondisi_penyaring
4. ORDER BY kolom [ASC|DESC]
5. LIMIT jumlah;

[Urutan Eksekusi Mesin MySQL (Runtime Engine)]
Langkah 1: FROM      -> Mengakses tabel sumber data di penyimpanan.
Langkah 2: WHERE     -> Menyaring baris data (Filter Baris) sebelum kolom dipilih!
Langkah 3: SELECT    -> Mengambil kolom yang diminta atau menghitung ekspresi (Proyeksi).
Langkah 4: ORDER BY  -> Mengurutkan baris-baris data yang lolos dari saringan WHERE.
Langkah 5: LIMIT     -> Memotong jumlah baris data yang akan dikembalikan ke klien.
```

> [!IMPORTANT]
> **Mengapa Alias Kolom Tidak Bisa Dipakai di WHERE?**  
> Karena tahap `WHERE` dieksekusi pada **Langkah 2**, sedangkan alias kolom baru dibuat pada tahap `SELECT` (**Langkah 3**). Pada saat mengevaluasi `WHERE`, mesin MySQL belum mengetahui nama alias tersebut. Oleh karena itu, tulislah nama kolom asli atau ekspresi aslinya di dalam `WHERE`.

---

## 2. Format Umum & Kamus Perintah (Syntax Reference)

### A. Format Dasar Klausa `WHERE`

```sql
SELECT kolom_1, kolom_2 
FROM nama_tabel 
WHERE kondisi_evaluasi;
```
- `kondisi_evaluasi`: Suatu ekspresi logika yang menghasilkan salah satu dari tiga nilai logika (*Three-Valued Logic*): `TRUE`, `FALSE`, atau `UNKNOWN`.

### B. Operator Perbandingan (*Comparison Operators*)

| Operator | Arti Teknis | Contoh Sintaks | Keterangan |
| :---: | :--- | :--- | :--- |
| `=` | Sama dengan | `WHERE kategori = 'Komputer'` | Membandingkan kesamaan nilai teks atau angka |
| `<>` atau `!=` | Tidak sama dengan | `WHERE status != 'Batal'` | Mengabaikan nilai yang cocok |
| `<` | Lebih kecil dari | `WHERE stok < 10` | Khusus tipe angka, tanggal, atau waktu |
| `>` | Lebih besar dari | `WHERE harga > 1000000` | Memeriksa nilai di atas batas tertentu |
| `<=` | Lebih kecil atau sama dengan | `WHERE umur <= 17` | Nilai batas ikut dianggap memenuhi syarat |
| `>=` | Lebih besar atau sama dengan | `WHERE gaji >= 5000000` | Nilai batas ikut dianggap memenuhi syarat |

### C. Operator Logika Kombinasi (*Logical Operators*)

1. **`AND`**: Seluruh kondisi yang dihubungkan wajib bernilai `TRUE` agar baris data lolos.
   ```sql
   WHERE kondisi_1 AND kondisi_2;
   ```
2. **`OR`**: Cukup salah satu kondisi bernilai `TRUE`, baris data akan lolos.
   ```sql
   WHERE kondisi_1 OR kondisi_2;
   ```
3. **`NOT`**: Membalikkan hasil evaluasi logika (`TRUE` menjadi `FALSE`, dan sebaliknya).
   ```sql
   WHERE NOT (kondisi_1);
   ```
4. **Presedensi Tanda Kurung `()`**:
   - Di dalam mesin MySQL, operator `AND` memiliki prioritas evaluasi yang lebih tinggi daripada `OR`.
   - Gunakan selalu tanda kurung `( ... )` untuk mengelompokkan logika secara eksplisit dan mencegah salah tafsir filter.

### D. Operator Rentang Nilai (`BETWEEN ... AND ...`)

```sql
SELECT kolom 
FROM nama_tabel 
WHERE kolom_angka_atau_tanggal BETWEEN nilai_awal AND nilai_akhir;
```
- Bersifat inklusif (*inclusive*): `nilai_awal` dan `nilai_akhir` ikut dihitung sebagai data yang lolos saringan.
- Bentuk negasi: `WHERE kolom NOT BETWEEN nilai_awal AND nilai_akhir;`.

### E. Operator Keanggotaan Himpunan (`IN (...)`)

```sql
SELECT kolom 
FROM nama_tabel 
WHERE kolom_acuan IN (nilai_1, nilai_2, nilai_n);
```
- Menggantikan penulisan klausa `OR` yang panjang dan berulang (`kolom = 'A' OR kolom = 'B' OR kolom = 'C'`).
- Bentuk negasi: `WHERE kolom NOT IN (nilai_1, nilai_2);` untuk menyaring data di luar daftar.

### F. Operator Pencocokan Pola Teks (`LIKE`)

```sql
SELECT kolom 
FROM nama_tabel 
WHERE kolom_string LIKE 'pola_pencarian';
```
MySQL menyediakan dua karakter *wildcard* utama:
1. **Persen (`%`)**: Mewakili nol, satu, atau banyak karakter sembarang.
   - `'Laptop%'` : Diawali kata "Laptop" (contoh: *Laptop ThinkPad*, *Laptop ASUS*).
   - `'%Gaming'` : Diakhiri kata "Gaming" (contoh: *Headset Gaming*, *Mouse Gaming*).
   - `'%Pro%'`    : Mengandung kata "Pro" di posisi mana pun (contoh: *MacBook Pro*, *iPad Pro M2*).
2. **Garis Bawah (`_`)**: Mewakili tepat satu karakter tunggal sembarang.
   - `'PRD-___'` : Diawali "PRD-" diikuti tepat 3 karakter sembarang (contoh: *PRD-001*, *PRD-ABC*).
3. Bentuk negasi: `WHERE kolom NOT LIKE 'pola%';`.

### G. Penanganan Nilai Kosong (*NULL Handling*)

```sql
-- Memeriksa baris yang bernilai NULL (tidak terisi):
SELECT kolom FROM nama_tabel WHERE kolom_target IS NULL;

-- Memeriksa baris yang memiliki nilai fisik (bukan NULL):
SELECT kolom FROM nama_tabel WHERE kolom_target IS NOT NULL;
```

> [!CAUTION]
> **Jebakan Logika: Jangan Pernah Menggunakan `= NULL`**  
> Nilai `NULL` bukanlah angka nol `0` dan bukan string kosong `''`. `NULL` adalah ketiadaan nilai (*unknown value*). Dalam standar SQL, perbandingan `WHERE kolom = NULL` atau `WHERE kolom != NULL` selalu menghasilkan logika `UNKNOWN`, yang oleh mesin MySQL dianggap sama dengan `FALSE`. Akibatnya, kueri tidak akan pernah mengembalikan baris apa pun! Selalu gunakan kata kunci baku **`IS NULL`** atau **`IS NOT NULL`**.

---

## 3. Persiapan Lingkungan & Skema Praktikum

Untuk menguji seluruh materi pada modul ini, siapkan database `toko_elektronik_db` dengan tabel referensi `produk` yang telah diperkaya kolom `garansi_bulan` dan `keterangan` (memuat nilai `NULL`) untuk pengujian logika *Three-Valued Logic*.

### A. Skrip Pembuatan Database & Data Seeder
Jalankan skrip SQL berikut pada editor database Anda:

```sql
-- 1. Aktifkan database toko
CREATE DATABASE IF NOT EXISTS toko_elektronik_db;
USE toko_elektronik_db;

-- 2. Bersihkan tabel uji coba jika sebelumnya sudah ada
DROP TABLE IF EXISTS produk;

-- 3. Buat struktur tabel produk
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    kode_sku VARCHAR(20) NOT NULL UNIQUE,
    nama_produk VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga DECIMAL(12, 2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    garansi_bulan INT NULL,
    keterangan VARCHAR(100) NULL
) ENGINE=InnoDB;

-- 4. Masukkan baris data uji coba (10 produk bervariasi)
INSERT INTO produk (kode_sku, nama_produk, kategori, harga, stok, garansi_bulan, keterangan) VALUES
('PRD-001', 'Laptop ThinkPad X1', 'Komputer', 18500000.00, 8, 24, 'Garansi Resmi Lenovo'),
('PRD-002', 'Mouse Wireless Silent', 'Aksesoris', 175000.00, 45, 12, 'Promo Bundling'),
('PRD-003', 'Keyboard Mechanical RGB', 'Aksesoris', 650000.00, 20, 12, NULL),
('PRD-004', 'Monitor IPS 24 Inch', 'Komputer', 2100000.00, 12, 36, 'Panel Anti Glare'),
('PRD-005', 'Webcam Full HD 1080p', 'Aksesoris', 420000.00, 15, 6, NULL),
('PRD-006', 'Printer Inkjet Warna', 'Peralatan Kantor', 1950000.00, 6, 12, 'Bonus Tinta Hitam'),
('PRD-007', 'Kabel HDMI 2.1 Braided', 'Aksesoris', 85000.00, 60, NULL, 'Aksesoris Non Garansi'),
('PRD-008', 'Headset Gaming 7.1', 'Audio', 550000.00, 18, 12, 'Surround Sound'),
('PRD-009', 'Speaker Bluetooth Portabel', 'Audio', 375000.00, 25, 12, NULL),
('PRD-010', 'Scanner Dokumen Portabel', 'Peralatan Kantor', 2300000.00, 4, 24, 'Garansi Resmi Epson');
```

### B. Kamus Data Tabel `produk`

| Nama Kolom | Tipe Data | Aturan Nilai | Keterangan |
| :--- | :--- | :--- | :--- |
| `id_produk` | `INT` | Primary Key, Auto Increment | Nomor identitas unik baris |
| `kode_sku` | `VARCHAR(20)` | NOT NULL, UNIQUE | Kode fisik barang gudang |
| `nama_produk` | `VARCHAR(100)` | NOT NULL | Nama resmi barang |
| `kategori` | `VARCHAR(50)` | NOT NULL | Kelompok barang |
| `harga` | `DECIMAL(12,2)`| NOT NULL | Harga per unit barang |
| `stok` | `INT` | NOT NULL, DEFAULT 0 | Jumlah stok barang saat ini |
| `garansi_bulan`| `INT` | NULL | Masa garansi dalam satuan bulan (bisa kosong) |
| `keterangan` | `VARCHAR(100)` | NULL | Catatan promo/keunggulan (bisa kosong) |

---

## 4. Tutorial Praktik Terbimbing

Berikut adalah 4 contoh praktik bertingkat dari penyaringan sederhana hingga kombinasi operator logika dan penanganan `NULL`.

### Contoh 1: Penyaringan Dasar dengan Operator Perbandingan

Skenario: Manajemen toko ingin menyaring dan menampilkan daftar produk yang harganya di bawah Rp 500.000 untuk dimasukkan ke dalam etalase produk terjangkau.

#### A. Format Umum SQL
```sql
SELECT kolom_1, kolom_2, kolom_n 
FROM nama_tabel 
WHERE kolom_angka < batas_nilai;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    nama_produk, 
    kategori, 
    harga, 
    stok
FROM produk
WHERE harga < 500000.00;
```

#### C. Penjelasan Baris Demi Baris
- **Baris 1–4 (`SELECT ...`):** Memilih atribut nama produk, kategori, harga, dan stok.
- **Baris 5 (`FROM produk`):** Menentukan tabel sumber data.
- **Baris 6 (`WHERE harga < 500000.00;`):** Mesin MySQL memeriksa nilai kolom harga pada setiap baris. Baris yang memiliki harga $\ge 500.000$ diabaikan, dan hanya baris bernilai $< 500.000$ yang dikembalikan.

#### D. Hasil Keluaran (Output MySQL)
```text
+----------------------------+-----------+-----------+------+
| nama_produk                | kategori  | harga     | stok |
+----------------------------+-----------+-----------+------+
| Mouse Wireless Silent      | Aksesoris | 175000.00 |   45 |
| Webcam Full HD 1080p       | Aksesoris | 420000.00 |   15 |
| Kabel HDMI 2.1 Braided     | Aksesoris |  85000.00 |   60 |
| Speaker Bluetooth Portabel | Audio     | 375000.00 |   25 |
+----------------------------+-----------+-----------+------+
4 rows in set (0.00 sec)
```

---

### Contoh 2: Logika Kombinasi & Pengaturan Presedensi Tanda Kurung

Skenario: Toko ingin menampilkan produk yang termasuk dalam kategori `'Komputer'` ATAU `'Aksesoris'`, TETAPI hanya yang memiliki stok di bawah 20 unit (perlu restok segera).

#### A. Format Umum SQL
```sql
SELECT kolom_1, kolom_2 
FROM nama_tabel 
WHERE (kondisi_A OR kondisi_B) AND kondisi_C;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    nama_produk, 
    kategori, 
    stok
FROM produk
WHERE (kategori = 'Komputer' OR kategori = 'Aksesoris') 
  AND stok < 20;
```

#### C. Pelacakan Kondisi Antara (*State Tracing*)
Tanda kurung memastikan evaluasi kategori dilakukan terlebih dahulu sebelum dievaluasi dengan syarat stok:

| Nama Produk | Kategori | Syarat Kategori (`OR`) | Syarat Stok (`stok < 20`) | Hasil Evaluasi Akhir (`AND`) | Status Keluaran |
| :--- | :--- | :---: | :---: | :---: | :---: |
| Laptop ThinkPad X1 | Komputer | **TRUE** | **TRUE** (8 < 20) | **TRUE** | **Lolos** |
| Mouse Wireless Silent | Aksesoris | **TRUE** | **FALSE** (45 < 20) | **FALSE** | Ditolak |
| Monitor IPS 24 Inch | Komputer | **TRUE** | **TRUE** (12 < 20) | **TRUE** | **Lolos** |
| Webcam Full HD 1080p | Aksesoris | **TRUE** | **TRUE** (15 < 20) | **TRUE** | **Lolos** |
| Kabel HDMI 2.1 Braided| Aksesoris | **TRUE** | **FALSE** (60 < 20) | **FALSE** | Ditolak |
| Printer Inkjet Warna | Peralatan Kantor | **FALSE** | **TRUE** (6 < 20) | **FALSE** | Ditolak |

#### D. Hasil Keluaran (Output MySQL)
```text
+---------------------+-----------+------+
| nama_produk         | kategori  | stok |
+---------------------+-----------+------+
| Laptop ThinkPad X1  | Komputer  |    8 |
| Monitor IPS 24 Inch | Komputer  |   12 |
| Webcam Full HD 1080p| Aksesoris |   15 |
+---------------------+-----------+------+
3 rows in set (0.00 sec)
```

---

### Contoh 3: Penyaringan Rentang Nilai (`BETWEEN`) dan Pola Teks (`LIKE`)

Skenario: Bagian penjualan ingin mencari produk apa saja yang memiliki harga menengah antara Rp 500.000 sampai Rp 2.500.000, serta memiliki nama produk yang memuat kata `'Portabel'` atau diawali jenis perangkat tertentu.

#### A. Format Umum SQL
```sql
SELECT kolom_1, kolom_2 
FROM nama_tabel 
WHERE kolom_harga BETWEEN batas_bawah AND batas_atas 
  AND kolom_nama LIKE '%pola%';
```

#### B. Kode SQL Eksekusi
```sql
-- Skenario A: Filter Rentang Harga Inklusif
SELECT nama_produk, harga, garansi_bulan
FROM produk
WHERE harga BETWEEN 500000.00 AND 2500000.00;

-- Skenario B: Filter Pola Teks Mengandung Kata 'Portabel'
SELECT kode_sku, nama_produk, kategori
FROM produk
WHERE nama_produk LIKE '%Portabel%';
```

#### C. Hasil Keluaran (Output MySQL)
```text
-- Hasil Skenario A (BETWEEN 500rb - 2.5jt):
+-------------------------+------------+---------------+
| nama_produk             | harga      | garansi_bulan |
+-------------------------+------------+---------------+
| Keyboard Mechanical RGB |  650000.00 |            12 |
| Monitor IPS 24 Inch     | 2100000.00 |            36 |
| Printer Inkjet Warna    | 1950000.00 |            12 |
| Headset Gaming 7.1      |  550000.00 |            12 |
| Scanner Dokumen Portabel| 2300000.00 |            24 |
+-------------------------+------------+---------------+
5 rows in set (0.00 sec)

-- Hasil Skenario B (LIKE '%Portabel%'):
+----------+----------------------------+------------------+
| kode_sku | nama_produk                | kategori         |
+----------+----------------------------+------------------+
| PRD-009  | Speaker Bluetooth Portabel | Audio            |
| PRD-010  | Scanner Dokumen Portabel   | Peralatan Kantor |
+----------+----------------------------+------------------+
2 rows in set (0.00 sec)
```

---

### Contoh 4: Penanganan Nilai NULL & Sintesis Kueri Bertingkat (Pendalaman)

Skenario: Manajemen audit ingin mendeteksi produk mana saja yang **belum memiliki data garansi resmi** (`garansi_bulan IS NULL`), ATAU produk bergaransi yang memiliki keterangan khusus (`keterangan IS NOT NULL`), kemudian diurutkan dari harga termahal dan dibatasi 3 produk teratas.

#### A. Format Umum SQL
```sql
SELECT kolom_1, kolom_2 
FROM nama_tabel 
WHERE kolom_target IS NULL [OR kolom_lain IS NOT NULL]
ORDER BY kolom_pengurut DESC 
LIMIT jumlah;
```

#### B. Kode SQL Eksekusi
```sql
-- Skenario 1: Mendeteksi data yang tidak memiliki garansi (NULL)
SELECT nama_produk, kategori, harga, garansi_bulan
FROM produk
WHERE garansi_bulan IS NULL;

-- Skenario 2 (Sintesis): Produk bergaransi resmi yang memiliki catatan keterangan
SELECT 
    nama_produk, 
    harga, 
    garansi_bulan, 
    keterangan
FROM produk
WHERE garansi_bulan IS NOT NULL 
  AND keterangan IS NOT NULL
ORDER BY harga DESC
LIMIT 3;
```

#### C. Penjelasan Baris Demi Baris
- **Skenario 1 (`WHERE garansi_bulan IS NULL`):** Menyaring produk yang kolom garansinya benar-benar tidak terisi di penyimpanan fisik (menghasilkan kabel HDMI).
- **Skenario 2 (`WHERE garansi_bulan IS NOT NULL AND keterangan IS NOT NULL`):** Hanya meloloskan baris yang kedua kolomnya terisi data fisik konkret.
- **`ORDER BY harga DESC`:** Menyusun baris yang lolos dari harga paling tinggi ke terendah.
- **`LIMIT 3`:** Membatasi keluaran hanya untuk 3 barang termahal.

#### D. Hasil Keluaran (Output MySQL)
```text
-- Hasil Skenario 1 (IS NULL):
+------------------------+-----------+----------+---------------+
| nama_produk            | kategori  | harga    | garansi_bulan |
+------------------------+-----------+----------+---------------+
| Kabel HDMI 2.1 Braided | Aksesoris | 85000.00 |          NULL |
+------------------------+-----------+----------+---------------+
1 row in set (0.00 sec)

-- Hasil Skenario 2 (Sintesis WHERE + ORDER BY + LIMIT):
+-------------------------+-------------+---------------+----------------------+
| nama_produk             | harga       | garansi_bulan | keterangan           |
+-------------------------+-------------+---------------+----------------------+
| Laptop ThinkPad X1      | 18500000.00 |            24 | Garansi Resmi Lenovo |
| Scanner Dokumen Portabel|  2300000.00 |            24 | Garansi Resmi Epson  |
| Monitor IPS 24 Inch     |  2100000.00 |            36 | Panel Anti Glare     |
+-------------------------+-------------+---------------+----------------------+
3 rows in set (0.00 sec)
```

---

## 5. Verifikasi Output & Kendala Umum (Troubleshooting)

### A. Gejala Kesalahan Umum (*Common Pitfalls*)

| Pesan Kesalahan (*Error Message*) | Akar Masalah (*Root Cause*) | Solusi Perbaikan |
| :--- | :--- | :--- |
| `ERROR 1054 (42S22): Unknown column 'total_aset' in 'where clause'` | Menggunakan nama alias kolom dari `SELECT` ke dalam klausa `WHERE`. | Ganti nama alias dengan ekspresi aslinya: `WHERE (harga * stok) > 10000000`. |
| Kueri dijalankan tapi menghasilkan `Empty set` padahal data `NULL` ada | Menggunakan pembanding kesamaan `= NULL` atau `!= NULL`. | Ganti menjadi operator baku: `WHERE garansi_bulan IS NULL` atau `IS NOT NULL`. |
| Hasil kueri menampilkan baris di luar dugaan saat menggabungkan `AND` dan `OR` | Operator `AND` dievaluasi lebih dulu daripada `OR` karena perbedaan tingkat presedensi. | Bungkus kelompok kondisi `OR` dengan tanda kurung: `WHERE (kat = 'A' OR kat = 'B') AND harga < 100`. |
| `ERROR 1064 (42000): You have an error in your SQL syntax... near 'BETWEEN 10000'` | Kurang menyertakan kata kunci `AND` pada pasangan klausa `BETWEEN`. | Format wajib `BETWEEN` selalu berpasangan: `WHERE harga BETWEEN 10000 AND 50000`. |

### B. Praktik Terbaik Efisiensi & Kinerja (*Performance Tips*)
1. **Hindari Membungkus Kolom Indeks dengan Fungsi di `WHERE`:**  
   Contoh: `WHERE YEAR(tanggal) = 2024` membuat MySQL tidak dapat menggunakan indeks tanggal. Lebih baik tulis rentang: `WHERE tanggal BETWEEN '2024-01-01' AND '2024-12-31'`.
2. **Perhatikan Pola Wildcard `%`:**  
   Pencarian dengan awalan wildcard seperti `LIKE '%Laptop'` memaksa database membaca seluruh indeks (*Full Scan*). Jika memungkinkan, gunakan awalan tetap seperti `LIKE 'Laptop%'` agar indeks dapat dimanfaatkan secara optimal (*Index Range Scan*).

---

## 6. Latihan Soal Praktik

Kerjakan seluruh butir latihan di bawah ini berdasarkan 4 tabel acuan berikut:

### Tabel Acuan Praktik

#### Tabel: karyawan
| id | nama | jabatan | gaji | bonus |
| :---: | :--- | :--- | :---: | :---: |
| 1 | Budi | Manager | 100000 | 10000 |
| 2 | Siti | Staff | 50000 | NULL |
| 3 | Andi | Staff | 60000 | 5000 |
| 4 | Rina | Supervisor | 80000 | NULL |
| 5 | Joko | Manager | 120000 | 15000 |

#### Tabel: produk
| id_produk | nama_produk | harga | stok |
| :---: | :--- | :---: | :---: |
| 1 | Laptop | 15000000 | 5 |
| 2 | Smartphone | 5000000 | 10 |
| 3 | Tablet | 8000000 | 2 |
| 4 | Monitor | 1200000 | 8 |
| 5 | Keyboard | 300000 | 15 |

#### Tabel: order
| id_order | tanggal | total | id_pelanggan |
| :---: | :---: | :---: | :---: |
| 1 | 2024-10-01 | 2000000 | 1 |
| 2 | 2024-10-02 | 750000 | 2 |
| 3 | 2024-10-03 | 3000000 | 3 |
| 4 | 2024-10-04 | 1500000 | 1 |
| 5 | 2024-10-05 | 500000 | 2 |

#### Tabel: pelanggan
| id_pelanggan | nama_pelanggan | alamat | no_hp |
| :---: | :--- | :--- | :---: |
| 1 | Ali | Jl. Merdeka 1 | 0812345678 |
| 2 | Siti | Jl. Kenanga 2 | NULL |
| 3 | Budi | Jl. Melati 3 | 0812987654 |
| 4 | Rina | Jl. Mawar 4 | NULL |
| 5 | Joko | Jl. Anggrek 5 | 0813555777 |

---

### Daftar Tugas Praktik

1. Tulis perintah SQL untuk menampilkan data seluruh karyawan yang memiliki `gaji` lebih besar dari `60000`.
2. Tulis perintah SQL untuk memilih kolom `nama_produk` dan `harga` dari tabel `produk` yang memiliki `harga` kurang dari atau sama dengan `5000000`.
3. Tulis perintah SQL untuk menampilkan data karyawan yang memiliki jabatan `'Staff'` DAN memiliki `gaji` minimal `55000`.
4. Tulis perintah SQL untuk menampilkan data produk yang memiliki `stok` kurang dari `5` ATAU memiliki `harga` di atas `10000000`.
5. Tulis perintah SQL untuk menampilkan data transaksi pada tabel `order` yang memiliki `total` dalam rentang `1000000` sampai dengan `2500000` menggunakan operator `BETWEEN`.
6. Tulis perintah SQL untuk menampilkan data karyawan yang jabatannya termasuk dalam kelompok `'Manager'` atau `'Supervisor'` menggunakan operator keanggotaan `IN`.
7. Tulis perintah SQL untuk menampilkan data produk yang kolom `nama_produk`-nya diawali dengan huruf `'S'` menggunakan pencocokan pola `LIKE`.
8. Tulis perintah SQL untuk menampilkan seluruh data pelanggan yang nomor teleponnya sudah terdata (kolom `no_hp` bernilai tidak kosong / bukan NULL).
9. Tulis perintah SQL untuk menampilkan seluruh data pelanggan yang belum memiliki nomor telepon (kolom `no_hp` bernilai NULL).
10. Tulis perintah SQL untuk menampilkan data produk yang memiliki harga di atas `1000000`, urutkan dari harga termahal ke termurah (`DESC`), dan batasi tampilan hanya untuk 2 produk teratas.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Cocokkan hasil eksekusi kueri latihan Anda di terminal atau editor database dengan **target tampilan tabel output** berikut. Kueri Anda dinyatakan benar apabila format kolom, nama alias, susunan baris, dan jumlah data yang dihasilkan cocok persis dengan tabel target di bawah ini:*

### A. Target Tampilan Output Tabel Latihan (Soal 1–10)

#### Target Output Soal 1 (Karyawan dengan Gaji > 60000)
```text
+----+------+------------+--------+-------+
| id | nama | jabatan    | gaji   | bonus |
+----+------+------------+--------+-------+
|  1 | Budi | Manager    | 100000 | 10000 |
|  4 | Rina | Supervisor |  80000 |  NULL |
|  5 | Joko | Manager    | 120000 | 15000 |
+----+------+------------+--------+-------+
3 rows in set
```

#### Target Output Soal 2 (Produk dengan Harga <= 5000000)
```text
+-------------+---------+
| nama_produk | harga   |
+-------------+---------+
| Smartphone  | 5000000 |
| Monitor     | 1200000 |
| Keyboard    |  300000 |
+-------------+---------+
3 rows in set
```

#### Target Output Soal 3 (Staff dengan Gaji >= 55000)
```text
+----+------+---------+-------+-------+
| id | nama | jabatan | gaji  | bonus |
+----+------+---------+-------+-------+
|  3 | Andi | Staff   | 60000 |  5000 |
+----+------+---------+-------+-------+
1 row in set
```

#### Target Output Soal 4 (Produk dengan Stok < 5 ATAU Harga > 10000000)
```text
+-----------+-------------+----------+------+
| id_produk | nama_produk | harga    | stok |
+-----------+-------------+----------+------+
|         1 | Laptop      | 15000000 |    5 |
|         3 | Tablet      |  8000000 |    2 |
+-----------+-------------+----------+------+
2 rows in set
```

#### Target Output Soal 5 (Order dengan Total BETWEEN 1jt dan 2.5jt)
```text
+----------+------------+---------+--------------+
| id_order | tanggal    | total   | id_pelanggan |
+----------+------------+---------+--------------+
|        1 | 2024-10-01 | 2000000 |            1 |
|        4 | 2024-10-04 | 1500000 |            1 |
+----------+------------+---------+--------------+
2 rows in set
```

#### Target Output Soal 6 (Karyawan dengan Jabatan IN ('Manager', 'Supervisor'))
```text
+----+------+------------+--------+-------+
| id | nama | jabatan    | gaji   | bonus |
+----+------+------------+--------+-------+
|  1 | Budi | Manager    | 100000 | 10000 |
|  4 | Rina | Supervisor |  80000 |  NULL |
|  5 | Joko | Manager    | 120000 | 15000 |
+----+------+------------+--------+-------+
3 rows in set
```

#### Target Output Soal 7 (Produk dengan Nama Diawali Huruf 'S')
```text
+-----------+-------------+---------+------+
| id_produk | nama_produk | harga   | stok |
+-----------+-------------+---------+------+
|         2 | Smartphone  | 5000000 |   10 |
+-----------+-------------+---------+------+
1 row in set
```

#### Target Output Soal 8 (Pelanggan yang Memiliki Nomor HP / IS NOT NULL)
```text
+--------------+----------------+---------------+------------+
| id_pelanggan | nama_pelanggan | alamat        | no_hp      |
+--------------+----------------+---------------+------------+
|            1 | Ali            | Jl. Merdeka 1 | 0812345678 |
|            3 | Budi           | Jl. Melati 3  | 0812987654 |
|            5 | Joko           | Jl. Anggrek 5 | 0813555777 |
+--------------+----------------+---------------+------------+
3 rows in set
```

#### Target Output Soal 9 (Pelanggan yang Belum Memiliki Nomor HP / IS NULL)
```text
+--------------+----------------+---------------+-------+
| id_pelanggan | nama_pelanggan | alamat        | no_hp |
+--------------+----------------+---------------+-------+
|            2 | Siti           | Jl. Kenanga 2 | NULL  |
|            4 | Rina           | Jl. Mawar 4   | NULL  |
+--------------+----------------+---------------+-------+
2 rows in set
```

#### Target Output Soal 10 (Sintesis: Harga > 1jt, ORDER BY Harga DESC, LIMIT 2)
```text
+-----------+-------------+----------+------+
| id_produk | nama_produk | harga    | stok |
+-----------+-------------+----------+------+
|         1 | Laptop      | 15000000 |    5 |
|         3 | Tablet      |  8000000 |    2 |
+-----------+-------------+----------+------+
2 rows in set
```

---

### B. Checklist Kesiapan Teknis
- [ ] Seluruh kueri `WHERE` berhasil dijalankan tanpa memicu pesan kesalahan sintaks.
- [ ] Memahami alasan mengapa alias kolom dari `SELECT` tidak dapat digunakan pada `WHERE`.
- [ ] Menggunakan operator `IS NULL` dan `IS NOT NULL` saat menyaring data kosong, bukan `= NULL`.
- [ ] Berhasil menerapkan tanda kurung `()` pada kueri yang menggabungkan operator `AND` dan `OR`.
- [ ] Seluruh tabel keluaran kueri di editor Anda cocok persis dengan target visual di atas.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci kueri SQL lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-07-penyaringan-data-where-clause-operator-logika-between-in-like--null).

---

## 8. Rangkuman & Refleksi

### Rangkuman Sintaks
1. **`WHERE`** menyaring baris data mentah dari tabel sebelum diproyeksikan oleh `SELECT`.
2. **Operator Perbandingan** (`=`, `!=`, `<`, `>`, `<=`, `>=`) mengevaluasi kesesuaian nilai baris terhadap kriteria acuan.
3. **`AND` & `OR`** menghubungkan banyak kriteria logika, dengan tanda kurung `()` sebagai pengendali urutan evaluasi.
4. **`BETWEEN`** menyaring nilai dalam rentang inklusif, sedangkan **`IN`** mencocokkan data terhadap suatu daftar himpunan nilai.
5. **`LIKE`** melakukan pencarian pola teks menggunakan karakter *wildcard* `%` (jamak) dan `_` (tunggal).
6. **`IS NULL` & `IS NOT NULL`** merupakan satu-satunya cara sah dalam SQL untuk menguji keberadaan nilai kosong (*missing value*).

### Pertanyaan Refleksi Mandiri
1. Apa perbedaan hasil antara kueri `WHERE harga BETWEEN 1000000 AND 5000000` dengan kueri `WHERE harga > 1000000 AND harga < 5000000`?
2. Mengapa kueri `SELECT * FROM produk WHERE keterangan = NULL;` tidak mengembalikan baris apa pun meskipun ada produk yang keterangannya kosong?
3. Jika pada sebuah tabel pengguna kita ingin mencari seluruh email yang berasal dari domain `@kampus.ac.id`, bagaimana pola penulisan wildcard `LIKE` yang tepat?

---
*Materi Berikutnya:* **Modul 08: Agregasi & Pengelompokan Data (COUNT, SUM, AVG, MIN, MAX, GROUP BY, & HAVING)**.
