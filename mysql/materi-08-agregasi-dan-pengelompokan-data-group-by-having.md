# Modul 08: Agregasi & Pengelompokan Data (COUNT, SUM, AVG, MIN, MAX, GROUP BY, & HAVING)

**Mata Pelajaran:** Basis Data Relasional (MySQL 8.x)  
**Alokasi Waktu:** 2 x 45 Menit  
**Sasaran:** Siswa SMK Rekayasa Perangkat Lunak / Mahasiswa Teknik Informatika  
**Target Kompetensi:** Menguasai kalkulasi ringkasan data kuantitatif menggunakan fungsi agregat standar, pengelompokan baris data menggunakan `GROUP BY`, serta penyaringan hasil kalkulasi kelompok menggunakan klausa `HAVING`.

---

## 1. Konsep Fundamental & Mental Model

### A. Prasyarat Pengetahuan
Sebelum mempelajari agregasi dan pengelompokan data pada modul ini, peserta didik diharapkan telah memahami:
1. Pembacaan data dan pengurutan menggunakan `SELECT`, `ORDER BY`, dan `LIMIT` ([Modul 06](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-06-kueri-dasar-select-alias-distinct-order-by-limit.md)).
2. Penyaringan baris data individual menggunakan klausa `WHERE` dan operator perbandingan ([Modul 07](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-07-penyaringan-data-where-clause-operator-logika-null.md)).

### B. Jembatan Konsep (Mental Model)
Bayangkan Anda adalah seorang manajer keuangan toko ritel pada akhir jam kerja. Anda memiliki 1.000 lembar nota penjualan fisik di atas meja:
- Anda tidak ingin membaca isi setiap lembar nota satu per satu (data atomik baris).
- Anda ingin mengetahui **fakta ringkasan eksekutif**: Berapa lembar total nota yang keluar hari ini? Berapa total rupiah omzet yang masuk? Berapa nilai transaksi terkecil dan terbesarnya? Berapa rata-rata belanja per pelanggan?
- Proses mengubah ribuan baris rincian data menjadi satu nilai ringkasan tunggal disebut **Agregasi (*Aggregation*)**.
- Selanjutnya, jika Anda ingin memilah nota-nota tersebut ke dalam beberapa tumpukan berdasarkan nama kasir atau jenis kategori barang, lalu menghitung total omzet untuk masing-masing tumpukan, tindakan ini disebut **Pengelompokan (*Grouping / GROUP BY*)**.
- Terakhir, jika Anda hanya ingin melihat tumpukan kasir yang total omzetnya melampaui target harian (misal $> \text{Rp } 5.000.000$), saringan untuk hasil ringkasan kelompok ini disebut **`HAVING`**.

### C. Alur Logika Pemrosesan Kueri 7 Klausa (*Logical Query Processing Flow*)
Dalam standar ANSI SQL dan arsitektur mesin MySQL 8.x, urutan penulisan kueri berbeda dari urutan eksekusi internal mesin:

```text
[Urutan Penulisan Sintaks SQL]
1. SELECT kolom_kelompok, FUNGSI_AGREGAT(kolom)
2. FROM nama_tabel
3. WHERE kondisi_baris_individual
4. GROUP BY kolom_kelompok
5. HAVING kondisi_filter_kelompok
6. ORDER BY hasil_agregat [ASC|DESC]
7. LIMIT jumlah;

[Urutan Eksekusi Mesin MySQL (Runtime Engine)]
Langkah 1: FROM      -> Mengakses tabel penyimpanan data.
Langkah 2: WHERE     -> Menyaring baris data mentah per baris (Row Filter).
Langkah 3: GROUP BY  -> Mengelompokkan baris yang lolos filter ke dalam ember-ember kelompok.
Langkah 4: HAVING    -> Menyaring ember kelompok berdasarkan hasil kalkulasi ringkasan.
Langkah 5: SELECT    -> Menghitung fungsi agregat dan memilih kolom kelompok (Proyeksi).
Langkah 6: ORDER BY  -> Mengurutkan hasil ringkasan kelompok (bisa memakai alias SELECT).
Langkah 7: LIMIT     -> Memotong jumlah kelompok teratas yang ditampilkan ke pengguna.
```

> [!IMPORTANT]
> **Mengapa Fungsi Agregat Dilarang di Dalam Klausa WHERE?**  
> Perhatikan urutan di atas: `WHERE` dieksekusi pada **Langkah 2**, sedangkan pengelompokan dan kalkulasi agregat baru terjadi pada **Langkah 3 dan Langkah 5**. Ketika mesin MySQL sedang menjalankan `WHERE`, nilai total (`SUM`) atau rata-rata (`AVG`) **belum pernah dihitung**. Oleh karena itu, kueri seperti `WHERE SUM(total) > 1000` akan memicu kesalahan fatal `ERROR 1111 (HY000): Invalid use of group function`. Filter hasil kalkulasi wajib diletakkan pada klausa **`HAVING`**.

---

## 2. Format Umum & Kamus Perintah (Syntax Reference)

### A. 5 Fungsi Agregat Standar SQL

| Fungsi | Arti Teknis | Contoh Sintaks | Perilaku terhadap Nilai `NULL` |
| :--- | :--- | :--- | :--- |
| **`COUNT(*)`** | Menghitung seluruh jumlah baris fisik | `SELECT COUNT(*) FROM produk;` | Menghitung semua baris, termasuk yang ada kolom NULL |
| **`COUNT(kolom)`** | Menghitung baris yang kolomnya terisi data | `SELECT COUNT(garansi_bulan) FROM produk;` | Baris yang memiliki nilai `NULL` **diabaikan** |
| **`COUNT(DISTINCT kolom)`** | Menghitung banyaknya variasi nilai unik | `SELECT COUNT(DISTINCT kategori) FROM produk;` | Menghitung nilai unik tanpa pengulangan |
| **`SUM(kolom)`** | Menjumlahkan total angka | `SELECT SUM(stok) FROM produk;` | Nilai `NULL` diabaikan, hanya menjumlahkan angka fisik |
| **`AVG(kolom)`** | Menghitung nilai rata-rata aritmetika | `SELECT AVG(harga) FROM produk;` | Pembagi adalah jumlah baris non-NULL |
| **`MIN(kolom)`** | Mengembalikan nilai terendah / terkecil | `SELECT MIN(harga) FROM produk;` | Bekerja pada angka, alfabet string, maupun tanggal |
| **`MAX(kolom)`** | Mengembalikan nilai tertinggi / terbesar | `SELECT MAX(harga) FROM produk;` | Bekerja pada angka, alfabet string, maupun tanggal |

### B. Format Dasar Pengelompokan Data (`GROUP BY`)

```sql
SELECT kolom_kelompok_1, 
       kolom_kelompok_2,
       FUNGSI_AGREGAT(kolom_nilai) AS nama_alias
FROM nama_tabel
GROUP BY kolom_kelompok_1, kolom_kelompok_2;
```

#### Aturan Baku `ONLY_FULL_GROUP_BY` pada MySQL 8.x
Pada MySQL 8.x, mode ketat (*strict mode*) `ONLY_FULL_GROUP_BY` aktif secara bawaan:
- **Setiap kolom non-agregat yang tercantum pada klausa `SELECT` WAJIB dicantumkan pada klausa `GROUP BY`.**
- *Contoh Keliru:* `SELECT kategori, nama_produk, COUNT(*) FROM produk GROUP BY kategori;`  
  Kueri ini akan ditolak mesin MySQL dengan `ERROR 1055` karena dalam satu kategori (misal *Aksesoris*) terdapat banyak *nama_produk*, sehingga MySQL tidak tahu nama produk mana yang harus ditampilkan mewakili kelompok tersebut.

### C. Format Penyaringan Hasil Kelompok (`HAVING`)

```sql
SELECT kolom_kelompok,
       FUNGSI_AGREGAT(kolom_nilai) AS total_ringkasan
FROM nama_tabel
GROUP BY kolom_kelompok
HAVING FUNGSI_AGREGAT(kolom_nilai) >= batas_nilai;
```
- Kondisi pada `HAVING` mengevaluasi hasil kalkulasi agregasi kelompok.
- Pada MySQL, Anda diperbolehkan menggunakan nama alias kolom di dalam klausa `HAVING`: `HAVING total_ringkasan >= 5000000`.

### D. Perbedaan Mendasar antara `WHERE` dan `HAVING`

| Aspek Pembeda | Klausa `WHERE` | Klausa `HAVING` |
| :--- | :--- | :--- |
| **Sasaran Filter** | Menyaring baris data mentah individual | Menyaring kelompok baris data hasil kalkulasi |
| **Waktu Eksekusi** | Sebelum baris dikelompokkan (*Pre-aggregation*) | Setelah kelompok dan agregasi terbentuk (*Post-aggregation*) |
| **Fungsi Agregat** | **DILARANG KERAS** (`WHERE SUM(x) > 0` $\rightarrow$ ERROR) | **DIPERBOLEHKAN** (`HAVING SUM(x) > 0` $\rightarrow$ VALID) |
| **Efisiensi Performa**| Sangat cepat (dapat memanfaatkan indeks tabel) | Relatif lambat (harus memproses kalkulasi memori) |

---

## 3. Persiapan Lingkungan & Skema Praktikum

Untuk menjalankan seluruh contoh praktik, kita menggunakan database `toko_elektronik_db` dengan tabel referensi `produk` yang telah memiliki variasi kategori, harga, dan stok yang kaya.

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
    garansi_bulan INT NULL
) ENGINE=InnoDB;

-- 4. Masukkan baris data uji coba (10 produk bervariasi)
INSERT INTO produk (kode_sku, nama_produk, kategori, harga, stok, garansi_bulan) VALUES
('PRD-001', 'Laptop ThinkPad X1', 'Komputer', 18500000.00, 8, 24),
('PRD-002', 'Mouse Wireless Silent', 'Aksesoris', 175000.00, 45, 12),
('PRD-003', 'Keyboard Mechanical RGB', 'Aksesoris', 650000.00, 20, 12),
('PRD-004', 'Monitor IPS 24 Inch', 'Komputer', 2100000.00, 12, 36),
('PRD-005', 'Webcam Full HD 1080p', 'Aksesoris', 420000.00, 15, 6),
('PRD-006', 'Printer Inkjet Warna', 'Peralatan Kantor', 1950000.00, 6, 12),
('PRD-007', 'Kabel HDMI 2.1 Braided', 'Aksesoris', 85000.00, 60, NULL),
('PRD-008', 'Headset Gaming 7.1', 'Audio', 550000.00, 18, 12),
('PRD-009', 'Speaker Bluetooth Portabel', 'Audio', 375000.00, 25, 12),
('PRD-010', 'Scanner Dokumen Portabel', 'Peralatan Kantor', 2300000.00, 4, 24);
```

### B. Kamus Data Tabel `produk`

| Nama Kolom | Tipe Data | Aturan | Keterangan |
| :--- | :--- | :--- | :--- |
| `id_produk` | `INT` | PK, Auto Increment | Identitas unik record barang |
| `kode_sku` | `VARCHAR(20)` | NOT NULL, UNIQUE | Kode fisik barang gudang |
| `nama_produk` | `VARCHAR(100)` | NOT NULL | Nama resmi barang |
| `kategori` | `VARCHAR(50)` | NOT NULL | Kelompok produk (Komputer, Aksesoris, Audio, Peralatan Kantor) |
| `harga` | `DECIMAL(12,2)`| NOT NULL | Harga satuan produk |
| `stok` | `INT` | NOT NULL, DEFAULT 0 | Jumlah stok unit fisik barang di gudang |
| `garansi_bulan`| `INT` | NULL | Masa garansi barang (ada barang aksesoris yang NULL) |

---

## 4. Tutorial Praktik Terbimbing

Berikut adalah 4 contoh praktik terurut yang mendemonstrasikan agregasi menyeluruh, pengelompokan baris data, penyaringan kelompok, hingga sintesis lengkap 6 klausa.

### Contoh 1: Kalkulasi Agregat Menyeluruh (Ringkasan Eksekutif Toko)

Skenario: Pemilik toko meminta satu baris laporan ringkasan yang memuat total variasi produk, total unit stok gudang, total nilai aset rupiah (`SUM(harga * stok)`), harga barang termurah, harga barang termahal, dan rata-rata harga barang.

#### A. Format Umum SQL
```sql
SELECT COUNT(*) AS total_item,
       SUM(kolom_stok) AS total_unit,
       SUM(kolom_harga * kolom_stok) AS total_aset,
       MIN(kolom_harga) AS harga_terendah,
       MAX(kolom_harga) AS harga_tertinggi,
       AVG(kolom_harga) AS rerata_harga
FROM nama_tabel;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    COUNT(*) AS 'Total Item',
    SUM(stok) AS 'Total Fisik Stok',
    SUM(harga * stok) AS 'Total Nilai Aset (Rp)',
    MIN(harga) AS 'Harga Termurah',
    MAX(harga) AS 'Harga Termahal',
    AVG(harga) AS 'Rata-Rata Harga'
FROM produk;
```

#### C. Penjelasan Baris Demi Baris
- **Baris 2 (`COUNT(*)`):** Menghitung seluruh 10 baris fisik produk yang ada di tabel.
- **Baris 3 (`SUM(stok)`):** Menjumlahkan seluruh angka pada kolom stok ($8 + 45 + 20 + 12 + 15 + 6 + 60 + 18 + 25 + 4 = 213$).
- **Baris 4 (`SUM(harga * stok)`):** Mengalikan harga dan stok tiap baris terlebih dahulu di memori, lalu menjumlahkan seluruh hasil perkalian tersebut (total aset gudang).
- **Baris 5–6 (`MIN`, `MAX`):** Mengambil nilai harga paling kecil (Rp 85.000) dan paling besar (Rp 18.500.000).
- **Baris 7 (`AVG(harga)`):** Menjumlahkan harga seluruh produk lalu membaginya dengan 10.

#### D. Hasil Keluaran (Output MySQL)
```text
+------------+------------------+-----------------------+----------------+----------------+-----------------+
| Total Item | Total Fisik Stok | Total Nilai Aset (Rp) | Harga Termurah | Harga Termahal | Rata-Rata Harga |
+------------+------------------+-----------------------+----------------+----------------+-----------------+
|         10 |              213 |          242325000.00 |       85000.00 |    18500000.00 |   2710500.00000 |
+------------+------------------+-----------------------+----------------+----------------+-----------------+
1 row in set (0.00 sec)
```

---

### Contoh 2: Pengelompokan Data per Kategori (`GROUP BY`)

Skenario: Bagian gudang ingin mengetahui rincian per kategori produk: berapa variasi item produk pada kategori tersebut, berapa total stok fisiknya, dan berapa rata-rata harga produk di kategori bersangkutan.

#### A. Format Umum SQL
```sql
SELECT kolom_kategori,
       COUNT(*) AS jumlah_produk,
       SUM(kolom_stok) AS total_stok,
       AVG(kolom_harga) AS rerata_harga
FROM nama_tabel
GROUP BY kolom_kategori;
```

#### B. Pelacakan Kondisi Antara (*State Tracing*)
Mesin MySQL memilah 10 baris produk ke dalam 4 ember kategori sebelum melakukan kalkulasi:

```text
[Tabel Fisik produk: 10 Baris]
       │
       ├──► Ember 'Komputer'         (2 produk: Laptop, Monitor)
       │    └── COUNT = 2, SUM(stok) = 8 + 12 = 20, AVG(harga) = 10.300.000
       │
       ├──► Ember 'Aksesoris'        (4 produk: Mouse, Keyboard, Webcam, Kabel)
       │    └── COUNT = 4, SUM(stok) = 45 + 20 + 15 + 60 = 140, AVG(harga) = 332.500
       │
       ├──► Ember 'Audio'            (2 produk: Headset, Speaker)
       │    └── COUNT = 2, SUM(stok) = 18 + 25 = 43, AVG(harga) = 462.500
       │
       └──► Ember 'Peralatan Kantor' (2 produk: Printer, Scanner)
            └── COUNT = 2, SUM(stok) = 6 + 4 = 10, AVG(harga) = 2.125.000
```

#### C. Kode SQL Eksekusi
```sql
SELECT 
    kategori,
    COUNT(*) AS 'Jumlah Item',
    SUM(stok) AS 'Total Stok',
    AVG(harga) AS 'Rata-Rata Harga'
FROM produk
GROUP BY kategori;
```

#### D. Hasil Keluaran (Output MySQL)
```text
+------------------+-------------+------------+-----------------+
| kategori         | Jumlah Item | Total Stok | Rata-Rata Harga |
+------------------+-------------+------------+-----------------+
| Komputer         |           2 |         20 |  10300000.00000 |
| Aksesoris        |           4 |        140 |    332500.00000 |
| Peralatan Kantor |           2 |         10 |   2125000.00000 |
| Audio            |           2 |         43 |    462500.00000 |
+------------------+-------------+------------+-----------------+
4 rows in set (0.00 sec)
```

---

### Contoh 3: Penyaringan Hasil Agregasi Menggunakan `HAVING`

Skenario: Manajemen ingin mendeteksi kategori produk mana saja yang memiliki **total stok gudang lebih dari 30 unit** DAN **rata-rata harga di bawah Rp 1.000.000** (kategori produk laris bervolume tinggi).

#### A. Format Umum SQL
```sql
SELECT kolom_kategori,
       SUM(kolom_stok) AS total_stok,
       AVG(kolom_harga) AS rerata_harga
FROM nama_tabel
GROUP BY kolom_kategori
HAVING SUM(kolom_stok) > batas_stok 
   AND AVG(kolom_harga) < batas_harga;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    kategori,
    SUM(stok) AS total_stok,
    AVG(harga) AS rerata_harga
FROM produk
GROUP BY kategori
HAVING total_stok > 30 
   AND rerata_harga < 1000000.00;
```

#### C. Penjelasan Baris Demi Baris
- **Baris 1–4:** Memproyeksikan kategori, menghitung total stok, dan rata-rata harga.
- **Baris 5 (`GROUP BY kategori`):** Mengelompokkan baris ke dalam 4 kelompok kategori.
- **Baris 6–7 (`HAVING ...`):** Menguji setiap kelompok:
  - *Komputer:* total stok 20 (gugur syarat stok > 30).
  - *Peralatan Kantor:* total stok 10 (gugur syarat stok > 30).
  - *Aksesoris:* stok 140 (> 30) dan rerata 332.500 (< 1jt) $\rightarrow$ **LOLOS**.
  - *Audio:* stok 43 (> 30) dan rerata 462.500 (< 1jt) $\rightarrow$ **LOLOS**.

#### D. Hasil Keluaran (Output MySQL)
```text
+-----------+------------+---------------+
| kategori  | total_stok | rerata_harga  |
+-----------+------------+---------------+
| Aksesoris |        140 |  332500.00000 |
| Audio     |         43 |  462500.00000 |
+-----------+------------+---------------+
2 rows in set (0.00 sec)
```

---

### Contoh 4: Sintesis Penuh 6 Klausa SQL (Pendalaman Arsitektur Kueri)

Skenario: Toko ingin membuat laporan kategori produk bernilai tinggi. Kriterianya:
1. Hanya memperhitungkan produk yang memiliki **garansi resmi** (`garansi_bulan IS NOT NULL` $\rightarrow$ filter baris `WHERE`).
2. Kelompokkan berdasarkan nama **kategori** (`GROUP BY`).
3. Hanya tampilkan kategori yang memiliki **total nilai aset gudang di atas Rp 10.000.000** (`HAVING`).
4. Urutkan hasil berdasarkan total aset dari yang **terbesar ke terkecil** (`ORDER BY ... DESC`).
5. Ambil **2 kategori teratas** saja (`LIMIT 2`).

#### A. Format Sintaks 6 Klausa Lengkap
```sql
SELECT kolom_kelompok,
       FUNGSI_AGREGAT(ekspresi) AS nama_alias
FROM nama_tabel
WHERE kondisi_baris_mentah
GROUP BY kolom_kelompok
HAVING kondisi_hasil_agregat
ORDER BY nama_alias DESC
LIMIT jumlah;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    kategori,
    COUNT(*) AS total_varian_bergaransi,
    SUM(harga * stok) AS total_aset_kategori
FROM produk
WHERE garansi_bulan IS NOT NULL
GROUP BY kategori
HAVING total_aset_kategori > 10000000.00
ORDER BY total_aset_kategori DESC
LIMIT 2;
```

#### C. Pelacakan Alur Eksekusi Data (*Step-by-Step Runtime Trace*)
1. **`FROM produk`**: 10 baris data produk dimuat.
2. **`WHERE garansi_bulan IS NOT NULL`**: Kabel HDMI (garansi NULL) disingkirkan. Tersisa 9 baris data.
3. **`GROUP BY kategori`**: 9 baris dikelompokkan ke dalam 4 kategori (Komputer: 2, Aksesoris: 3, Audio: 2, Peralatan Kantor: 2).
4. **`HAVING total_aset_kategori > 10000000`**:
   - Komputer: $148\text{ jt} + 25.2\text{ jt} = 173.2\text{ jt}$ (**Lolos**)
   - Peralatan Kantor: $11.7\text{ jt} + 9.2\text{ jt} = 20.9\text{ jt}$ (**Lolos**)
   - Aksesoris: $7.875\text{ jt} + 13\text{ jt} + 6.3\text{ jt} = 27.175\text{ jt}$ (**Lolos**)
   - Audio: $9.9\text{ jt} + 9.375\text{ jt} = 19.275\text{ jt}$ (**Lolos**)
5. **`ORDER BY total_aset_kategori DESC`**: Komputer (173.2jt) $\rightarrow$ Aksesoris (27.175jt) $\rightarrow$ Peralatan Kantor (20.9jt) $\rightarrow$ Audio (19.275jt).
6. **`LIMIT 2`**: Memotong dan mengambil 2 kelompok teratas: Komputer dan Aksesoris.

#### D. Hasil Keluaran (Output MySQL)
```text
+-----------+--------------------------+---------------------+
| kategori  | total_varian_bergaransi  | total_aset_kategori |
+-----------+--------------------------+---------------------+
| Komputer  |                        2 |        173200000.00 |
| Aksesoris |                        3 |         27175000.00 |
+-----------+--------------------------+---------------------+
2 rows in set (0.00 sec)
```

---

## 5. Verifikasi Output & Kendala Umum (Troubleshooting)

### A. Gejala Kesalahan Umum (*Common Pitfalls*)

| Pesan Kesalahan (*Error Message*) | Akar Masalah (*Root Cause*) | Solusi Perbaikan |
| :--- | :--- | :--- |
| `ERROR 1111 (HY000): Invalid use of group function` | Menggunakan fungsi agregat seperti `COUNT()`, `SUM()`, atau `AVG()` di dalam klausa `WHERE`. | Pindahkan kondisi tersebut dari klausa `WHERE` ke klausa `HAVING`. |
| `ERROR 1055 (42000): Expression #1 of SELECT list is not in GROUP BY clause...` | Mencantumkan kolom non-agregat di `SELECT` yang tidak terdaftar di klausa `GROUP BY` (pelanggaran mode `ONLY_FULL_GROUP_BY`). | Daftarkan kolom tersebut ke dalam klausa `GROUP BY`, atau bungkus kolom tersebut dengan fungsi agregat (`MIN`, `MAX`, `ANY_VALUE`). |
| Nilai `AVG(kolom)` terlihat lebih besar dari hitungan manual | Mengharapkan baris `NULL` ikut dihitung sebagai pembagi nol. Standar SQL mengabaikan baris `NULL` saat menghitung rata-rata. | Jika baris `NULL` ingin dianggap bernilai 0, gunakan fungsi koalesensi: `AVG(COALESCE(kolom, 0))`. |
| Klausa `GROUP BY` menghasilkan hasil agregat yang keliru setelah digabung `WHERE` | Menyaring data kelompok menggunakan `WHERE` alih-alih `HAVING`, atau sebaliknya. | Pahami perbedaannya: `WHERE` menyaring baris individual sebelum dikelompokkan; `HAVING` menyaring hasil kelompok setelah dikelompokkan. |

### B. Praktik Terbaik Efisiensi & Kinerja (*Performance Tips*)
1. **Saring Data Sedini Mungkin Menggunakan `WHERE`:**  
   Jika sebuah kondisi dapat difilter sebelum pengelompokan (misal `WHERE status = 'Selesai'`), jangan taruh di `HAVING`. Meletakkan filter di `WHERE` membuang baris yang tidak relevan lebih awal, sehingga memori yang dipakai untuk proses `GROUP BY` menjadi jauh lebih kecil dan cepat.
2. **Indeks untuk Kolom `GROUP BY`:**  
   Menambahkan indeks pada kolom yang sering digunakan di `GROUP BY` memungkinkan MySQL melakukan agregasi langsung melalui pembacaan pohon indeks (*Loose/Tight Index Scan*) tanpa harus membuat tabel sementara di memori (*Using temporary; Using filesort*).

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

1. Tulis perintah SQL untuk menghitung total jumlah karyawan yang terdaftar pada tabel `karyawan` dengan alias `'Total Karyawan'`.
2. Tulis perintah SQL untuk menghitung total pengeluaran gaji (`SUM`) untuk seluruh karyawan pada tabel `karyawan` dengan alias `'Total Anggaran Gaji'`.
3. Tulis perintah SQL untuk menghitung rata-rata harga produk (`AVG`) dari tabel `produk` dengan alias `'Rata-Rata Harga'`.
4. Tulis perintah SQL untuk menampilkan harga produk termurah (`MIN`) dengan alias `'Harga Minimum'` dan harga produk termahal (`MAX`) dengan alias `'Harga Maksimum'` dari tabel `produk`.
5. Tulis perintah SQL untuk menampilkan `jabatan`, jumlah karyawan pada tiap jabatan (`COUNT(*)`), dan rata-rata gaji untuk masing-masing jabatan dari tabel `karyawan`, dikelompokkan berdasarkan `jabatan`.
6. Tulis perintah SQL untuk menghitung total belanja (`SUM(total)`) dan jumlah transaksi (`COUNT(*)`) untuk setiap pelanggan dari tabel `order`, dikelompokkan berdasarkan `id_pelanggan`.
7. Tulis perintah SQL untuk menampilkan daftar `jabatan` dan rata-rata gaji dari tabel `karyawan`, tetapi hanya tampilkan jabatan yang memiliki rata-rata gaji lebih besar dari `70000` menggunakan klausa `HAVING`.
8. Tulis perintah SQL untuk menampilkan `id_pelanggan` dan total akumulasi pembelian dari tabel `order`, hanya untuk pelanggan yang total akumulasi transaksinya lebih besar dari atau sama dengan `2000000` menggunakan klausa `HAVING`.
9. Tulis perintah SQL untuk menghitung jumlah transaksi dan total belanja per `id_pelanggan` pada tabel `order`, hanya untuk transaksi individual yang nilainya lebih besar dari `600000` (gunakan `WHERE`), lalu kelompokkan per `id_pelanggan`.
10. Tulis perintah SQL untuk menampilkan `jabatan` dan total anggaran gaji per jabatan dari tabel `karyawan`, hanya untuk karyawan yang memiliki gaji pokok minimal `60000`, kelompokkan berdasarkan `jabatan`, saring kelompok yang total anggarannya di atas `100000` (gunakan `HAVING`), urutkan total anggaran dari yang terbesar, dan batasi tampilan hanya untuk 1 baris teratas.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Cocokkan hasil eksekusi kueri latihan Anda di terminal atau editor database dengan **target tampilan tabel output** berikut. Kueri Anda dinyatakan benar apabila format kolom, nama alias, susunan baris, dan hasil kalkulasi yang dihasilkan cocok persis dengan tabel target di bawah ini:*

### A. Target Tampilan Output Tabel Latihan (Soal 1–10)

#### Target Output Soal 1 (Total Jumlah Karyawan)
```text
+----------------+
| Total Karyawan |
+----------------+
|              5 |
+----------------+
1 row in set
```

#### Target Output Soal 2 (Total Anggaran Gaji Karyawan)
```text
+---------------------+
| Total Anggaran Gaji |
+---------------------+
|              410000 |
+---------------------+
1 row in set
```

#### Target Output Soal 3 (Rata-Rata Harga Produk)
```text
+-----------------+
| Rata-Rata Harga |
+-----------------+
|   5900000.00000 |
+-----------------+
1 row in set
```

#### Target Output Soal 4 (Harga Minimum dan Maksimum Produk)
```text
+---------------+----------------+
| Harga Minimum | Harga Maksimum |
+---------------+----------------+
|        300000 |       15000000 |
+---------------+----------------+
1 row in set
```

#### Target Output Soal 5 (Jumlah Karyawan dan Rata-Rata Gaji per Jabatan)
```text
+------------+-----------------+-----------------+
| jabatan    | Jumlah Karyawan | Rata-Rata Gaji  |
+------------+-----------------+-----------------+
| Manager    |               2 |   110000.000000 |
| Staff      |               2 |    55000.000000 |
| Supervisor |               1 |    80000.000000 |
+------------+-----------------+-----------------+
3 rows in set
```

#### Target Output Soal 6 (Total Belanja dan Jumlah Transaksi per Pelanggan)
```text
+--------------+---------------+------------------+
| id_pelanggan | Total Belanja | Jumlah Transaksi |
+--------------+---------------+------------------+
|            1 |       3500000 |                2 |
|            2 |       1250000 |                2 |
|            3 |       3000000 |                1 |
+--------------+---------------+------------------+
3 rows in set
```

#### Target Output Soal 7 (Jabatan dengan Rata-Rata Gaji > 70000)
```text
+------------+----------------+
| jabatan    | Rata-Rata Gaji |
+------------+----------------+
| Manager    |  110000.000000 |
| Supervisor |   80000.000000 |
+------------+----------------+
2 rows in set
```

#### Target Output Soal 8 (Pelanggan dengan Akumulasi Pembelian >= 2000000)
```text
+--------------+---------------+
| id_pelanggan | Total Belanja |
+--------------+---------------+
|            1 |       3500000 |
|            3 |       3000000 |
+--------------+---------------+
2 rows in set
```

#### Target Output Soal 9 (Rekapitulasi Order untuk Transaksi Nilai > 600000)
```text
+--------------+------------------+---------------+
| id_pelanggan | Jumlah Transaksi | Total Belanja |
+--------------+------------------+---------------+
|            1 |                2 |       3500000 |
|            2 |                1 |        750000 |
|            3 |                1 |       3000000 |
+--------------+------------------+---------------+
3 rows in set
```

#### Target Output Soal 10 (Sintesis Penuh: Filter Gaji, GROUP BY, HAVING, ORDER BY, LIMIT)
```text
+---------+---------------+
| jabatan | Total Anggaran|
+---------+---------------+
| Manager |        220000 |
+---------+---------------+
1 row in set
```

---

### B. Checklist Kesiapan Teknis
- [ ] Mampu membedakan kapan harus menggunakan `COUNT(*)` dan `COUNT(kolom)`.
- [ ] Memahami alasan mengapa fungsi agregat dilarang di dalam klausa `WHERE`.
- [ ] Mampu menerapkan klausa `GROUP BY` sesuai aturan standar `ONLY_FULL_GROUP_BY`.
- [ ] Mampu menggunakan klausa `HAVING` untuk menyaring hasil kalkulasi kelompok secara tepat.
- [ ] Seluruh tabel keluaran kueri di editor Anda cocok persis dengan target visual di atas.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci kueri SQL lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-08-agregasi--pengelompokan-data-count-sum-avg-min-max-group-by--having).

---

## 8. Rangkuman & Refleksi

### Rangkuman Sintaks
1. **Fungsi Agregat** (`COUNT`, `SUM`, `AVG`, `MIN`, `MAX`) meringkas banyak baris data menjadi satu nilai kuantitatif tunggal.
2. **`GROUP BY`** memecah baris data ke dalam beberapa kelompok berdasarkan kesamaan nilai atribut tertentu.
3. **`HAVING`** menyaring kelompok data hasil agregasi, sedangkan **`WHERE`** menyaring baris data mentah sebelum dikelompokkan.
4. Urutan eksekusi logis: `FROM` $\rightarrow$ `WHERE` $\rightarrow$ `GROUP BY` $\rightarrow$ `HAVING` $\rightarrow$ `SELECT` $\rightarrow$ `ORDER BY` $\rightarrow$ `LIMIT`.

### Pertanyaan Refleksi Mandiri
1. Apa perbedaan hasil antara `SELECT COUNT(*) FROM karyawan;` dengan `SELECT COUNT(bonus) FROM karyawan;` pada tabel latihan kita? Mengapa hasilnya berbeda?
2. Mengapa kueri `SELECT jabatan, AVG(gaji) FROM karyawan WHERE AVG(gaji) > 70000 GROUP BY jabatan;` memicu pesan error? Bagaimana cara memperbaikinya?
3. Jika pada sebuah tabel transaksi penjualan kita ingin menghitung total omzet per hari khusus untuk pembayaran non-tunai, klausa mana yang digunakan untuk menyaring jenis pembayaran dan klausa mana yang digunakan untuk mengelompokkan hari?

---
*Materi Berikutnya:* **Modul 09: Relasi Multi-Tabel (INNER JOIN, LEFT JOIN, RIGHT JOIN, Self Join, & CROSS JOIN)**.
