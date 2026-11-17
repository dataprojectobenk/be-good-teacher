# Modul 06: Kueri Dasar (SELECT), Alias, DISTINCT, ORDER BY, & LIMIT

**Mata Pelajaran:** Basis Data Relasional (MySQL 8.x)  
**Alokasi Waktu:** 2 x 45 Menit  
**Sasaran:** Siswa SMK Rekayasa Perangkat Lunak / Mahasiswa Teknik Informatika  
**Target Kompetensi:** Menguasai pembacaan data tabel (*Data Query Language* / DQL) melalui proyeksi kolom terpilih, penamaan alias kolom, eliminasi nilai duplikat, pengurutan baris data, dan pembatasan data untuk paginasi.

---

## 1. Konsep Fundamental & Mental Model

### A. Prasyarat Pengetahuan
Sebelum mempelajari kueri pembacaan data pada modul ini, peserta didik diharapkan telah memahami:
1. Konsep pembuatan database dan tabel dengan tipe data yang tepat ([Modul 03](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-03-tipe-data-dan-ddl-pembuatan-tabel.md) & [Modul 04](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-04-ddl-lanjutan-constraint-fk-alter-drop.md)).
2. Pengisian data baris (*records*) ke dalam tabel menggunakan perintah `INSERT INTO` ([Modul 05](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-05-dml-manipulasi-data-insert-update-delete.md)).

### B. Jembatan Konsep (Mental Model)
Bayangkan sebuah basis data seperti gudang besar penyimpanan barang, dan tabel adalah rak-rak lemari yang tersusun rapi. Perintah DDL mendirikan rak lemari tersebut, sedangkan perintah DML `INSERT` menaruh barang ke dalamnya. 

Ketika ada pembeli datang meminta katalog barang:
- Kita tidak mengangkut seluruh lemari keluar gudang beserta seluruh informasi internal pabriknya.
- Kita menyusun **katalog etalase** yang hanya menampilkan kolom informasi yang relevan bagi pembeli (misalnya: nama barang, harga, dan ketersediaan stok). Tindakan memilih kolom tertentu ini disebut **Proyeksi (*Projection*)**.
- Kita dapat memberi judul kolom etalase yang rapi dan mudah dibaca oleh pembeli menggunakan nama samaran atau **Alias (`AS`)**.
- Jika terdapat barang dengan kategori yang sama berulang kali, kita bisa merangkum variasi kategori yang tersedia tanpa ada nama yang muncul ganda menggunakan **`DISTINCT`**.
- Kita menata susunan barang dari yang termurah ke yang termahal atau berdasarkan alfabet nama menggunakan **`ORDER BY`**.
- Terakhir, agar pembeli tidak kewalahan melihat ratusan barang sekaligus, kita mencetak katalog per halaman (misalnya 5 barang per halaman) menggunakan **`LIMIT`** dan **`OFFSET`**.

### C. Alur Logika Pemrosesan Kueri (*Logical Query Processing*)
Dalam penulisan kode SQL, klausa `SELECT` ditulis paling pertama. Namun, di dalam mesin MySQL (*Query Engine*), urutan eksekusinya bekerja secara terbalik:

```text
[Urutan Penulisan Sintaks SQL]
1. SELECT kolom / ekspresi
2. FROM nama_tabel
3. DISTINCT
4. ORDER BY kolom [ASC|DESC]
5. LIMIT jumlah OFFSET awal;

[Urutan Eksekusi Mesin MySQL (Runtime Engine)]
Langkah 1: FROM      -> Menentukan tabel sumber data mana yang akan diakses.
Langkah 2: SELECT    -> Mengekstrak kolom yang diminta atau menghitung ekspresi (Proyeksi).
Langkah 3: DISTINCT  -> Mengeliminasi baris keluaran yang memiliki nilai kembar.
Langkah 4: ORDER BY  -> Mengurutkan hasil baris data berdasarkan kriteria tertentu.
Langkah 5: LIMIT     -> Memotong dan mengambil sejumlah baris data yang ditentukan.
```

Pemahaman urutan eksekusi ini sangat penting untuk memahami mengapa alias kolom yang dibuat pada tahap `SELECT` dapat dikenali pada tahap `ORDER BY`.

---

## 2. Format Umum & Kamus Perintah (Syntax Reference)

### A. Format Sintaks Dasar `SELECT`

#### 1. Memilih Kolom Tertentu (Proyeksi Terarah)
```sql
SELECT kolom_1, kolom_2, kolom_n 
FROM nama_tabel;
```
- Digunakan untuk mengambil hanya atribut yang benar-benar dibutuhkan oleh sistem atau aplikasi.
- Menghemat penggunaan memori RAM server dan mempercepat transfer data jaringan (*bandwidth*).

#### 2. Memilih Seluruh Kolom
```sql
SELECT * 
FROM nama_tabel;
```
- Tanda bintang (`*`) menginstruksikan MySQL untuk mengembalikan semua kolom yang terdaftar pada tabel sesuai urutan saat tabel didefinisikan.
- **Catatan Praktik Industri:** Penggunaan `SELECT *` sangat berguna untuk inspeksi data saat proses belajar atau *debugging*. Namun, pada aplikasi produksi berskala besar, selalu sebutkan nama kolom secara eksplisit demi efisiensi dan keamanan aplikasi.

### B. Format Kolom Alias (`AS`) & Operasi Aritmetika

```sql
SELECT kolom_asli AS nama_alias_kolom,
       (kolom_angka_1 * kolom_angka_2) AS nama_hasil_hitung
FROM nama_tabel;
```
- Kata kunci `AS` bersifat opsional di MySQL, namun **sangat disarankan ditulis secara eksplisit** demi kejelasan (*readability*) kode.
- Jika nama alias terdiri dari dua kata atau lebih yang dipisahkan spasi, nama alias wajib diapit tanda petik: `'Total Nilai Aset'`.
- MySQL mendukung operasi aritmetika standar pada kolom bertipe angka: penambahan (`+`), pengurangan (`-`), perkalian (`*`), pembagian (`/`), dan sisa bagi (`%`).

### C. Format Eliminasi Nilai Ganda (`DISTINCT`)

```sql
SELECT DISTINCT kolom_acuan
FROM nama_tabel;
```
- `DISTINCT` diletakkan tepat setelah kata `SELECT`.
- Jika `DISTINCT` diterapkan pada beberapa kolom: `SELECT DISTINCT kolom_1, kolom_2 FROM nama_tabel;`, maka keunikan dinilai dari kombinasi kedua nilai kolom tersebut secara bersamaan (*composite uniqueness*).

### D. Format Pengurutan Data (`ORDER BY`)

```sql
SELECT kolom_1, kolom_2
FROM nama_tabel
ORDER BY kolom_pengurut_1 [ASC | DESC], 
         kolom_pengurut_2 [ASC | DESC];
```
- `ASC` (*Ascending*): Mengurutkan data dari nilai terkecil ke terbesar (A ke Z, 0 ke 9, tanggal terlama ke terbaru). Ini merupakan nilai bawaan (*default*) jika arah tidak ditulis.
- `DESC` (*Descending*): Mengurutkan data dari nilai terbesar ke terkecil (Z ke A, 9 ke 0, tanggal terbaru ke terlama).
- Pengurutan bertingkat (*multi-column sort*): Kolom kedua baru akan dievaluasi jika terdapat nilai yang sama (*tie*) pada kolom pertama.

### E. Format Pembatasan Baris & Paginasi (`LIMIT` & `OFFSET`)

```sql
SELECT kolom_1, kolom_2
FROM nama_tabel
LIMIT jumlah_baris OFFSET titik_mulai;
```
- `jumlah_baris`: Banyaknya baris maksimum yang ingin ditampilkan.
- `titik_mulai` (*OFFSET*): Posisi indeks awal baris data yang akan dilewati sebelum mulai mengambil data. Indeks awal baris pada MySQL dimulai dari angka **`0`** (baris pertama = indeks 0).
- Bentuk alternatif ringkas:
  ```sql
  SELECT kolom_1, kolom_2 FROM nama_tabel LIMIT titik_mulai, jumlah_baris;
  ```
  *(Catatan: Dalam penulisan alternatif dua angka yang dipisahkan koma, angka pertama adalah OFFSET dan angka kedua adalah JUMLAH BARIS).*

---

## 3. Persiapan Lingkungan & Skema Praktikum

Untuk menjalankan seluruh contoh praktik terbimbing, siapkan database `toko_elektronik_db` dengan satu tabel referensi produk yang memuat variasi harga, kategori, dan stok barang.

### A. Skrip Pembuatan Database & Tabel Praktikum
Jalankan skrip SQL berikut pada editor database Anda:

```sql
-- 1. Buat database dan aktifkan
CREATE DATABASE IF NOT EXISTS toko_elektronik_db;
USE toko_elektronik_db;

-- 2. Bersihkan tabel jika sebelumnya sudah ada
DROP TABLE IF EXISTS produk;

-- 3. Buat struktur tabel produk
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    kode_sku VARCHAR(20) NOT NULL UNIQUE,
    nama_produk VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga DECIMAL(12, 2) NOT NULL,
    stok INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- 4. Masukkan baris data uji coba (10 produk)
INSERT INTO produk (kode_sku, nama_produk, kategori, harga, stok) VALUES
('PRD-001', 'Laptop ThinkPad X1', 'Komputer', 18500000.00, 8),
('PRD-002', 'Mouse Wireless Silent', 'Aksesoris', 175000.00, 45),
('PRD-003', 'Keyboard Mechanical RGB', 'Aksesoris', 650000.00, 20),
('PRD-004', 'Monitor IPS 24 Inch', 'Komputer', 2100000.00, 12),
('PRD-005', 'Webcam Full HD 1080p', 'Aksesoris', 420000.00, 15),
('PRD-006', 'Printer Inkjet Warna', 'Peralatan Kantor', 1950000.00, 6),
('PRD-007', 'Kabel HDMI 2.1 Braided', 'Aksesoris', 85000.00, 60),
('PRD-008', 'Headset Gaming 7.1', 'Audio', 550000.00, 18),
('PRD-009', 'Speaker Bluetooth Portabel', 'Audio', 375000.00, 25),
('PRD-010', 'Scanner Dokumen Portabel', 'Peralatan Kantor', 2300000.00, 4);
```

### B. Kamus Data Tabel `produk`

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_produk` | `INT` | Kunci utama (*Primary Key*), bertambah otomatis |
| `kode_sku` | `VARCHAR(20)` | Kode identitas fisik barang di gudang (wajib unik) |
| `nama_produk`| `VARCHAR(100)`| Nama lengkap barang |
| `kategori` | `VARCHAR(50)` | Jenis kelompok produk (Komputer, Aksesoris, Audio, dsb.) |
| `harga` | `DECIMAL(12,2)`| Harga satuan produk dalam rupiah |
| `stok` | `INT` | Jumlah fisik unit produk yang tersedia di gudang |

---

## 4. Tutorial Praktik Terbimbing

Berikut adalah 4 contoh praktik terurut yang mendemonstrasikan pembacaan data secara bertahap dari skenario sederhana hingga implementasi paginasi.

### Contoh 1: Proyeksi Kolom Tertentu, Alias (`AS`), dan Ekspresi Aritmetika

Skenario: Bagian keuangan meminta daftar nama produk, harga saat ini, jumlah stok, serta perhitungan total nilai aset gudang (`harga * stok`) dengan judul kolom yang rapi.

#### A. Format Umum SQL
```sql
SELECT kolom_1, 
       kolom_2 AS nama_alias_1, 
       (kolom_angka_1 * kolom_angka_2) AS nama_alias_2 
FROM nama_tabel;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    nama_produk AS 'Nama Barang',
    harga AS 'Harga Satuan (Rp)',
    stok AS 'Stok Gudang',
    (harga * stok) AS 'Total Nilai Aset'
FROM produk;
```

#### C. Penjelasan Baris Demi Baris
- **Baris 1 (`SELECT`):** Memulai instruksi penarikan data dan spesifikasi kolom.
- **Baris 2 (`nama_produk AS 'Nama Barang',`):** Mengambil nilai kolom `nama_produk` dan menampilkannya pada tabel hasil dengan header `'Nama Barang'`.
- **Baris 3 (`harga AS 'Harga Satuan (Rp)',`):** Mengambil kolom harga dan melabelinya dengan header `'Harga Satuan (Rp)'`.
- **Baris 4 (`stok AS 'Stok Gudang',`):** Mengambil kolom stok dan melabelinya dengan `'Stok Gudang'`.
- **Baris 5 (`(harga * stok) AS 'Total Nilai Aset'`):** Melakukan komputasi matematika baris per baris antara kolom harga dan stok secara dinamis di memori tanpa mengubah isi tabel fisik, lalu melabelinya sebagai `'Total Nilai Aset'`.
- **Baris 6 (`FROM produk;`):** Menunjuk tabel `produk` sebagai sumber data yang dibaca.

#### D. Hasil Keluaran (Output MySQL)
```text
+----------------------------+-------------------+-------------+------------------+
| Nama Barang                | Harga Satuan (Rp) | Stok Gudang | Total Nilai Aset |
+----------------------------+-------------------+-------------+------------------+
| Laptop ThinkPad X1         |       18500000.00 |           8 |     148000000.00 |
| Mouse Wireless Silent      |         175000.00 |          45 |       7875000.00 |
| Keyboard Mechanical RGB    |         650000.00 |          20 |      13000000.00 |
| Monitor IPS 24 Inch        |        2100000.00 |          12 |      25200000.00 |
| Webcam Full HD 1080p       |         420000.00 |          15 |       6300000.00 |
| Printer Inkjet Warna       |        1950000.00 |           6 |      11700000.00 |
| Kabel HDMI 2.1 Braided     |          85000.00 |          60 |       5100000.00 |
| Headset Gaming 7.1         |         550000.00 |          18 |       9900000.00 |
| Speaker Bluetooth Portabel |         375000.00 |          25 |       9375000.00 |
| Scanner Dokumen Portabel   |        2300000.00 |           4 |       9200000.00 |
+----------------------------+-------------------+-------------+------------------+
10 rows in set (0.00 sec)
```

---

### Contoh 2: Eliminasi Nilai Ganda dengan `DISTINCT`

Skenario: Bagian pergudangan ingin mengetahui kelompok kategori apa saja yang saat ini dimiliki toko tanpa menampilkan pengulangan nama kategori yang sama.

#### A. Format Umum SQL
```sql
SELECT DISTINCT nama_kolom 
FROM nama_tabel;
```

#### B. Kode SQL Eksekusi
```sql
-- Perbandingan 1: Tanpa DISTINCT (menghasilkan baris ganda)
SELECT kategori FROM produk;

-- Perbandingan 2: Menggunakan DISTINCT (hanya nilai unik yang ditampilkan)
SELECT DISTINCT kategori AS 'Daftar Kategori Unik'
FROM produk;
```

#### C. Penjelasan Baris Demi Baris
- **Kueri 1:** Membaca kolom `kategori` secara mentah. Karena tabel memiliki 10 baris, maka keluaran tetap berjumlah 10 baris di mana kata `'Aksesoris'` tercetak 4 kali, `'Komputer'` 2 kali, `'Audio'` 2 kali, dan `'Peralatan Kantor'` 2 kali.
- **Kueri 2 (`SELECT DISTINCT kategori AS ...`):** MySQL membaca seluruh baris kolom kategori, membuat tabel penampung sementara di memori, membuang seluruh kemunculan duplikat, dan mengembalikan setiap nilai unik tepat satu kali.

#### D. Hasil Keluaran (Output MySQL)
```text
-- Hasil Kueri 2 (DISTINCT):
+----------------------+
| Daftar Kategori Unik |
+----------------------+
| Komputer             |
| Aksesoris            |
| Peralatan Kantor     |
| Audio                |
+----------------------+
4 rows in set (0.00 sec)
```

---

### Contoh 3: Pengurutan Data Tunggal dan Multi-Kolom (`ORDER BY`)

Skenario: Toko ingin menampilkan katalog produk yang dikelompokkan berdasarkan nama kategori secara berurutan (A–Z), dan apabila dalam kategori yang sama terdapat beberapa produk, urutkan produk tersebut dari harga tertinggi ke terendah.

#### A. Format Umum SQL
```sql
SELECT kolom_1, kolom_2, kolom_3
FROM nama_tabel
ORDER BY kolom_primer ASC, kolom_sekunder DESC;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    kategori,
    nama_produk,
    harga
FROM produk
ORDER BY 
    kategori ASC, 
    harga DESC;
```

#### C. Penjelasan Baris Demi Baris
- **Baris 1–4 (`SELECT ... FROM produk`):** Memilih atribut kategori, nama produk, dan harga dari tabel produk.
- **Baris 5 (`ORDER BY`):** Mengaktifkan instruksi pengurutan baris keluaran.
- **Baris 6 (`kategori ASC,`):** Kriteria pengurutan utama (primer). Nilai kolom kategori diurutkan dari huruf abjad terkecil ke terbesar (`Aksesoris` -> `Audio` -> `Komputer` -> `Peralatan Kantor`).
- **Baris 7 (`harga DESC;`):** Kriteria pengurutan pembantu (sekunder). Jika terdapat produk dengan kategori yang identik (misalnya sesama produk `Aksesoris`), baris diurutkan berdasarkan harga paling mahal terlebih dahulu.

#### D. Hasil Keluaran (Output MySQL)
```text
+------------------+----------------------------+-------------+
| kategori         | nama_produk                | harga       |
+------------------+----------------------------+-------------+
| Aksesoris        | Keyboard Mechanical RGB    |   650000.00 |
| Aksesoris        | Webcam Full HD 1080p       |   420000.00 |
| Aksesoris        | Mouse Wireless Silent      |   175000.00 |
| Aksesoris        | Kabel HDMI 2.1 Braided     |    85000.00 |
| Audio            | Headset Gaming 7.1         |   550000.00 |
| Audio            | Speaker Bluetooth Portabel |   375000.00 |
| Komputer         | Laptop ThinkPad X1         | 18500000.00 |
| Komputer         | Monitor IPS 24 Inch        |  2100000.00 |
| Peralatan Kantor | Scanner Dokumen Portabel   |  2300000.00 |
| Peralatan Kantor | Printer Inkjet Warna       |  1950000.00 |
+------------------+----------------------------+-------------+
10 rows in set (0.00 sec)
```
*Perhatikan:* Pada kelompok `Aksesoris`, `Keyboard Mechanical RGB` (650.000) tampil paling atas dan `Kabel HDMI 2.1 Braided` (85.000) tampil paling bawah sesuai instruksi `harga DESC`.

---

### Contoh 4: Pembatasan Baris dan Konsep Paginasi Halaman (`LIMIT` & `OFFSET`)

Skenario: Pada antarmuka aplikasi web toko, satu halaman hanya boleh menampilkan 3 produk. Produk diurutkan berdasarkan stok terbanyak. Kita akan mensimulasikan kueri untuk Halaman 1, Halaman 2, dan Halaman 3.

#### A. Rumus Konsep Paginasi
Jika ditentukan:
- $N$ = Ukuran halaman (*Page Size* / `LIMIT`)
- $P$ = Nomor halaman aktif (*Page Number*, dimulai dari halaman 1)
- Maka nilai $\text{OFFSET} = (P - 1) \times N$

| Halaman ($P$) | Ukuran ($N$) | Perhitungan OFFSET | Kueri SQL |
| :---: | :---: | :---: | :--- |
| Halaman 1 | 3 baris | $(1 - 1) \times 3 = 0$ | `LIMIT 3 OFFSET 0` (Ambil 3 baris pertama) |
| Halaman 2 | 3 baris | $(2 - 1) \times 3 = 3$ | `LIMIT 3 OFFSET 3` (Lewati 3 baris, ambil 3 baris berikutnya) |
| Halaman 3 | 3 baris | $(3 - 1) \times 3 = 6$ | `LIMIT 3 OFFSET 6` (Lewati 6 baris, ambil 3 baris berikutnya) |

#### B. Kode SQL Eksekusi
```sql
-- Simulasi Halaman 1 (Baris ke-1 sampai ke-3 berdasarkan stok terbanyak)
SELECT nama_produk, kategori, stok
FROM produk
ORDER BY stok DESC
LIMIT 3 OFFSET 0;

-- Simulasi Halaman 2 (Baris ke-4 sampai ke-6 berdasarkan stok terbanyak)
SELECT nama_produk, kategori, stok
FROM produk
ORDER BY stok DESC
LIMIT 3 OFFSET 3;

-- Simulasi Halaman 3 (Baris ke-7 sampai ke-9 berdasarkan stok terbanyak)
SELECT nama_produk, kategori, stok
FROM produk
ORDER BY stok DESC
LIMIT 3 OFFSET 6;
```

#### C. Hasil Keluaran (Output MySQL)
```text
-- Hasil Halaman 1 (LIMIT 3 OFFSET 0):
+----------------------------+-----------+------+
| nama_produk                | kategori  | stok |
+----------------------------+-----------+------+
| Kabel HDMI 2.1 Braided     | Aksesoris |   60 |
| Mouse Wireless Silent      | Aksesoris |   45 |
| Speaker Bluetooth Portabel | Audio     |   25 |
+----------------------------+-----------+------+
3 rows in set (0.00 sec)

-- Hasil Halaman 2 (LIMIT 3 OFFSET 3):
+-------------------------+-----------+------+
| nama_produk             | kategori  | stok |
+-------------------------+-----------+------+
| Keyboard Mechanical RGB | Aksesoris |   20 |
| Headset Gaming 7.1      | Audio     |   18 |
| Webcam Full HD 1080p    | Aksesoris |   15 |
+-------------------------+-----------+------+
3 rows in set (0.00 sec)

-- Hasil Halaman 3 (LIMIT 3 OFFSET 6):
+----------------------+------------------+------+
| nama_produk          | kategori         | stok |
+----------------------+------------------+------+
| Monitor IPS 24 Inch  | Komputer         |   12 |
| Laptop ThinkPad X1   | Komputer         |    8 |
| Printer Inkjet Warna | Peralatan Kantor |    6 |
+----------------------+------------------+------+
3 rows in set (0.00 sec)
```

---

## 5. Verifikasi Output & Kendala Umum (Troubleshooting)

### A. Gejala Kesalahan Umum (*Common Pitfalls*)

| Pesan Kesalahan (*Error Message*) | Akar Penyebab (*Root Cause*) | Solusi Perbaikan |
| :--- | :--- | :--- |
| `ERROR 1054 (42S22): Unknown column 'namaproduk' in 'field list'` | Kesalahan ejaan (*typo*) pada nama kolom atau kolom tidak ada pada tabel yang diakses. | Periksa struktur tabel dengan `DESCRIBE nama_tabel;` dan pastikan ejaan kolom sama persis dengan definisi tabel (`nama_produk`). |
| `ERROR 1064 (42000): You have an error in your SQL syntax... near 'LIMIT 5 ORDER BY...'` | Pelanggaran urutan klausa SQL. Klausa `LIMIT` ditulis sebelum klausa `ORDER BY`. | Posisikan klausa sesuai urutan sintaks baku: `SELECT` -> `FROM` -> `ORDER BY` -> `LIMIT`. |
| `ERROR 1064 (42000): You have an error in your SQL syntax... near 'Nilai Aset FROM...'` | Memberikan nama alias yang memiliki spasi tanpa menggunakan tanda petik: `SELECT harga * stok AS Total Nilai Aset`. | Bungkus nama alias yang mengandung spasi dengan tanda petik tunggal atau petik ganda: `AS 'Total Nilai Aset'`. |
| Hasil pengurutan karakter angka tidak sesuai (misal: '10' muncul sebelum '2') | Kolom angka disimpan menggunakan tipe string (`VARCHAR` / `CHAR`), sehingga MySQL mengurutkannya berdasarkan aturan leksikografis (abjad), bukan nilai numerik. | Pastikan kolom nominal atau kuantitas didefinisikan dengan tipe numerik (`INT`, `DECIMAL`) pada DDL. |

### B. Praktik Terbaik Efisiensi & Kinerja (*Performance Tips*)
1. **Hindari `SELECT *` dalam Aplikasi Terhubung:** Saat aplikasi web atau *mobile* terhubung ke database, menarik semua kolom yang tidak digunakan akan membebani memori server dan memperlambat latensi jaringan.
2. **Kombinasi `ORDER BY` dan `LIMIT`:** Kueri `SELECT ... ORDER BY kolom DESC LIMIT n` bekerja sangat cepat jika kolom yang diurutkan telah memiliki indeks (*Index*), karena MySQL dapat langsung membaca ujung indeks tanpa harus melakukan penyortiran seluruh tabel di memori (*filesort*).

---

## 6. Latihan Soal Praktik

Kerjakan seluruh butir latihan di bawah ini berdasarkan 4 tabel acuan berikut:

### Tabel Acuan Praktik

#### Tabel: karyawan
| id | nama | jabatan | gaji |
| :--- | :--- | :--- | :--- |
| 1 | Budi | Manager | 100000 |
| 2 | Siti | Staff | 50000 |
| 3 | Andi | Staff | 60000 |
| 4 | Rina | Supervisor | 80000 |
| 5 | Joko | Manager | 120000 |

#### Tabel: produk
| id_produk | nama_produk | harga | stok |
| :--- | :--- | :--- | :--- |
| 1 | Laptop | 15000000 | 5 |
| 2 | Smartphone | 5000000 | 10 |
| 3 | Tablet | 8000000 | 2 |
| 4 | Monitor | 1200000 | 8 |
| 5 | Keyboard | 300000 | 15 |

#### Tabel: order
| id_order | tanggal | total | id_pelanggan |
| :--- | :--- | :--- | :--- |
| 1 | 2024-10-01 | 2000000 | 1 |
| 2 | 2024-10-02 | 750000 | 2 |
| 3 | 2024-10-03 | 3000000 | 3 |
| 4 | 2024-10-04 | 1500000 | 1 |
| 5 | 2024-10-05 | 500000 | 2 |

#### Tabel: pelanggan
| id_pelanggan | nama_pelanggan | alamat |
| :--- | :--- | :--- |
| 1 | Ali | Jl. Merdeka 1 |
| 2 | Siti | Jl. Kenanga 2 |
| 3 | Budi | Jl. Melati 3 |
| 4 | Rina | Jl. Mawar 4 |
| 5 | Joko | Jl. Anggrek 5 |

---

### Tugas Instruksi Praktik:

1. Tulis perintah SQL untuk menampilkan seluruh kolom dan baris dari tabel `karyawan`.
2. Tulis perintah SQL untuk memilih kolom `nama` dan `gaji` dari tabel `karyawan`, serta berikan alias kolom `nama` menjadi `'Nama Pegawai'` dan kolom `gaji` menjadi `'Gaji Pokok'`.
3. Tulis perintah SQL untuk memilih `nama_produk`, `harga`, dan `stok` dari tabel `produk`, serta tambahkan satu kolom kalkulasi perkalian antara `harga` dan `stok` dengan alias `'Total Nilai Produk'`.
4. Tulis perintah SQL untuk menampilkan daftar variasi `jabatan` yang ada di tabel `karyawan` tanpa menampilkan jabatan yang duplikat.
5. Tulis perintah SQL untuk menampilkan daftar unik `id_pelanggan` yang pernah melakukan pemesanan pada tabel `order`.
6. Tulis perintah SQL untuk memilih kolom `nama_produk` dan `harga` dari tabel `produk`, lalu urutkan baris data berdasarkan `harga` dari yang termurah ke termahal (*ascending*).
7. Tulis perintah SQL untuk memilih kolom `nama`, `jabatan`, dan `gaji` dari tabel `karyawan`, lalu urutkan data berdasarkan `jabatan` secara alfabetis (A–Z), kemudian untuk jabatan yang sama urutkan berdasarkan `gaji` dari yang tertinggi ke terendah (*descending*).
8. Tulis perintah SQL untuk menampilkan 3 produk dengan harga termahal dari tabel `produk`.
9. Tulis perintah SQL untuk menampilkan data pemesanan pada tabel `order`, diurutkan berdasarkan `tanggal` terbaru ke terlama, dan batasi tampilan hanya untuk 2 transaksi pertama.
10. Tulis perintah SQL untuk menyimulasikan halaman kedua (*page 2*) dari daftar seluruh pelanggan di tabel `pelanggan`, di mana setiap halaman menampilkan 2 data pelanggan yang diurutkan berdasarkan `nama_pelanggan` dari A ke Z.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Cocokkan hasil eksekusi kueri latihan Anda di terminal atau editor database dengan **target tampilan tabel output** berikut. Kueri Anda dinyatakan benar apabila format kolom, nama alias, susunan baris, dan jumlah data yang dihasilkan cocok persis dengan tabel target di bawah ini:*

### A. Target Tampilan Output Tabel Latihan (Soal 1–10)

#### Target Output Soal 1 (Seluruh Kolom dan Baris `karyawan`)
```text
+----+------+------------+--------+
| id | nama | jabatan    | gaji   |
+----+------+------------+--------+
|  1 | Budi | Manager    | 100000 |
|  2 | Siti | Staff      |  50000 |
|  3 | Andi | Staff      |  60000 |
|  4 | Rina | Supervisor |  80000 |
|  5 | Joko | Manager    | 120000 |
+----+------+------------+--------+
5 rows in set
```

#### Target Output Soal 2 (Proyeksi Kolom dengan Alias Kustom)
```text
+--------------+------------+
| Nama Pegawai | Gaji Pokok |
+--------------+------------+
| Budi         |     100000 |
| Siti         |      50000 |
| Andi         |      60000 |
| Rina         |      80000 |
| Joko         |     120000 |
+--------------+------------+
5 rows in set
```

#### Target Output Soal 3 (Kalkulasi Nilai Aset Produk `harga * stok`)
```text
+-------------+----------+------+--------------------+
| nama_produk | harga    | stok | Total Nilai Produk |
+-------------+----------+------+--------------------+
| Laptop      | 15000000 |    5 |           75000000 |
| Smartphone  |  5000000 |   10 |           50000000 |
| Tablet      |  8000000 |    2 |           16000000 |
| Monitor     |  1200000 |    8 |            9600000 |
| Keyboard    |   300000 |   15 |            4500000 |
+-------------+----------+------+--------------------+
5 rows in set
```

#### Target Output Soal 4 (Daftar `jabatan` Unik tanpa Duplikasi)
```text
+------------+
| jabatan    |
+------------+
| Manager    |
| Staff      |
| Supervisor |
+------------+
3 rows in set
```

#### Target Output Soal 5 (Daftar Unik `id_pelanggan` pada `order`)
```text
+--------------+
| id_pelanggan |
+--------------+
|            1 |
|            2 |
|            3 |
+--------------+
3 rows in set
```

#### Target Output Soal 6 (Produk Urut Harga Termurah ke Termahal)
```text
+-------------+----------+
| nama_produk | harga    |
+-------------+----------+
| Keyboard    |   300000 |
| Monitor     |  1200000 |
| Smartphone  |  5000000 |
| Tablet      |  8000000 |
| Laptop      | 15000000 |
+-------------+----------+
5 rows in set
```

#### Target Output Soal 7 (Urut Multi-Kolom: `jabatan` ASC, `gaji` DESC)
```text
+------+------------+--------+
| nama | jabatan    | gaji   |
+------+------------+--------+
| Joko | Manager    | 120000 |
| Budi | Manager    | 100000 |
| Andi | Staff      |  60000 |
| Siti | Staff      |  50000 |
| Rina | Supervisor |  80000 |
+------+------------+--------+
5 rows in set
```

#### Target Output Soal 8 (3 Produk dengan Harga Termahal)
```text
+-------------+----------+
| nama_produk | harga    |
+-------------+----------+
| Laptop      | 15000000 |
| Tablet      |  8000000 |
| Smartphone  |  5000000 |
+-------------+----------+
3 rows in set
```
*(Catatan: Jika Anda memilih seluruh kolom produk, 3 baris teratas yang muncul tetap merupakan ketiga barang di atas).*

#### Target Output Soal 9 (2 Transaksi Order dengan Tanggal Terbaru)
```text
+----------+------------+---------+--------------+
| id_order | tanggal    | total   | id_pelanggan |
+----------+------------+---------+--------------+
|        5 | 2024-10-05 |  500000 |            2 |
|        4 | 2024-10-04 | 1500000 |            1 |
+----------+------------+---------+--------------+
2 rows in set
```

#### Target Output Soal 10 (Simulasi Paginasi Halaman 2 Tabel `pelanggan`)
```text
+--------------+----------------+----------------+
| id_pelanggan | nama_pelanggan | alamat         |
+--------------+----------------+----------------+
|            5 | Joko           | Jl. Anggrek 5  |
|            4 | Rina           | Jl. Mawar 4    |
+--------------+----------------+----------------+
2 rows in set
```

---

### B. Checklist Kesiapan Teknis
- [ ] Seluruh kueri `SELECT` berhasil dijalankan tanpa pesan kesalahan sintaks.
- [ ] Penggunaan nama alias dengan spasi telah diapit tanda petik secara benar.
- [ ] Memahami perbedaan hasil antara `SELECT DISTINCT` satu kolom dan multi-kolom.
- [ ] Mampu menghitung nilai `OFFSET` secara tepat berdasarkan nomor halaman dan ukuran data per halaman.
- [ ] Seluruh tabel keluaran kueri di editor cocok persis dengan target visual di atas.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci kueri SQL lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-06-kueri-dasar-select-alias-distinct-order-by--limit).

---

## 8. Rangkuman & Refleksi

### Rangkuman Sintaks
1. **`SELECT`** menentukan kolom atau hasil ekspresi matematika yang ingin ditampilkan ke layar (*Proyeksi*).
2. **`AS`** memberikan nama label sementara pada header kolom agar lebih informatif dan mudah dipahami pengguna aplikasi.
3. **`DISTINCT`** menyaring dan membuang baris data yang bernilai kembar sehingga hanya menampilkan nilai unik.
4. **`ORDER BY`** menyusun urutan tampilan baris data berdasarkan kriteria alfabet, numerik, atau kronologi waktu secara menaik (`ASC`) maupun menurun (`DESC`).
5. **`LIMIT` & `OFFSET`** memotong sebagian data dari kumpulan hasil untuk mengimplementasikan sistem paginasi halaman pada aplikasi.

### Pertanyaan Refleksi Mandiri
1. Mengapa penulisan `SELECT *` sebaiknya dihindari saat membuat modul kueri pada aplikasi web backend yang memiliki jutaan pengunjung?
2. Pada kueri `SELECT DISTINCT kategori, nama_produk FROM produk;`, mengapa kata `'Aksesoris'` tetap muncul berulang kali padahal kata kunci `DISTINCT` sudah disertakan?
3. Jika sebuah aplikasi memiliki 100 data produk dan menampilkan 10 produk per halaman, berapakah nilai `LIMIT` dan `OFFSET` yang harus dikirimkan pada kueri saat pengguna membuka Halaman 7?

---
*Materi Berikutnya:* **Modul 07: Penyaringan Data dengan Klausa WHERE, Operator Perbandingan, Logika (AND, OR, NOT), BETWEEN, IN, LIKE, & Penanganan NULL**.
