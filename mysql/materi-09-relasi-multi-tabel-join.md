# Modul 09: Relasi Multi-Tabel (INNER JOIN, LEFT JOIN, RIGHT JOIN, Self Join, & CROSS JOIN)

**Mata Pelajaran:** Basis Data Relasional (MySQL 8.x)  
**Alokasi Waktu:** 2 x 45 Menit  
**Sasaran:** Siswa SMK Rekayasa Perangkat Lunak / Mahasiswa Teknik Informatika  
**Target Kompetensi:** Menguasai teknik menggabungkan dan membaca data dari banyak tabel berelasi (*Relational Query*) menggunakan `INNER JOIN`, `LEFT JOIN`, `RIGHT JOIN`, `Self Join`, dan `CROSS JOIN`, serta mampu mengidentifikasi data tanpa pasangan (*Anti-Join*) dan mencegah anomali *Cartesian Product*.

---

## 1. Konsep Fundamental & Mental Model

### A. Prasyarat Pengetahuan
Sebelum mempelajari penggabungan tabel pada modul ini, peserta didik diharapkan telah memahami:
1. Konsep kunci utama (*Primary Key*) dan kunci asing (*Foreign Key*) pada desain relasi antartabel ([Modul 02](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-02-erd-dan-normalisasi-tabel.md) & [Modul 04](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-04-ddl-lanjutan-constraint-fk-alter-drop.md)).
2. Teknik penyaringan data `WHERE` ([Modul 07](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-07-penyaringan-data-where-clause-operator-logika-null.md)) dan fungsi agregasi kelompok `GROUP BY` ([Modul 08](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-08-agregasi-dan-pengelompokan-data-group-by-having.md)).

### B. Jembatan Konsep (Mental Model)
Pada [Modul 02: Normalisasi](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/materi-02-erd-dan-normalisasi-tabel.md), kita mempelajari bahwa data yang baik tidak boleh ditumpuk dalam satu tabel raksasa, melainkan harus dipecah ke dalam entitas-entitas spesifik (tabel `pelanggan`, tabel `order`, tabel `produk`) untuk mencegah anomali redundansi.

Namun, saat aplikasi web atau pengguna membutuhkan laporan utuh:
- Pengguna tidak ingin melihat kode angka asing seperti *"Pesanan ID 101 dibuat oleh Pelanggan ID 3"*.
- Pengguna ingin membaca informasi yang manusiawi: *"Pesanan ID 101 dibuat oleh Budi yang beralamat di Jl. Melati 3"*.
- Perintah **`JOIN`** berfungsi layaknya **lem perekat atau jembatan penghubung**: kita menyatukan kembali potongan-potongan puzzle data yang tersimpan di tabel-tabel berbeda secara dinamis di memori komputer berdasarkan kesamaan nilai kunci penghubungnya: **`tabel_a.primary_key = tabel_b.foreign_key`**.

### C. Peta Logika Ragam Operasi `JOIN`

```text
1. INNER JOIN (Irisan Murni)
   [ Tabel A ] ──∩── [ Tabel B ]
   Hanya baris yang memiliki pasangan data yang cocok di KEDUA tabel yang ditampilkan.

2. LEFT JOIN (Kiri Lengkap)
   [ Tabel A (Semua Baris) ] ────► [ Tabel B (Pasangan / NULL) ]
   Seluruh baris tabel kiri ditampilkan. Jika tabel kanan tidak memiliki transaksi/pasangan,
   kolom tabel kanan akan diisi dengan nilai NULL.

3. RIGHT JOIN (Kanan Lengkap)
   [ Tabel A (Pasangan / NULL) ] ◄──── [ Tabel B (Semua Baris) ]
   Seluruh baris tabel kanan ditampilkan beserta pasangannya di kiri (atau NULL jika kosong).

4. Self Join (Relasi Internal Diri Sendiri)
   [ Tabel Karyawan ] (sebagai staf) ────► [ Tabel Karyawan ] (sebagai atasan)
   Menggabungkan satu tabel fisik dengan dirinya sendiri menggunakan dua alias berbeda.

5. CROSS JOIN (Perkalian Kartesius)
   [ Tabel A (M baris) ] × [ Tabel B (N baris) ] = M × N baris
   Menghubungkan setiap baris di tabel A ke seluruh baris di tabel B tanpa syarat ON.
```

---

## 2. Format Umum & Kamus Perintah (Syntax Reference)

### A. Format Baku `INNER JOIN`

```sql
SELECT a.kolom_1, 
       b.kolom_2 
FROM nama_tabel_a AS a
INNER JOIN nama_tabel_b AS b 
    ON a.kolom_primary_key = b.kolom_foreign_key;
```
- Kata kunci `AS` pada alias tabel bersifat opsional, namun sangat disarankan demi keterbacaan kode yang ringkas.
- **Pemberian Prefiks Alias Kolom:** Jika kedua tabel memiliki nama kolom yang identik (misalnya sama-sama bernama `id` atau `nama`), Anda **wajib** menyertakan alias tabel di depannya (`a.nama`, `b.nama`). Jika tidak, MySQL akan menolak kueri dengan pesan kesalahan `Column '...' in field list is ambiguous`.

### B. Format Baku `LEFT JOIN` & Deteksi Data Kosong (*Anti-Join*)

```sql
-- Format Dasar LEFT JOIN:
SELECT p.nama_pelanggan, 
       o.id_order, 
       o.total
FROM pelanggan AS p
LEFT JOIN `order` AS o 
    ON p.id_pelanggan = o.id_pelanggan;

-- Format Anti-Join (Mencari pelanggan yang belum pernah belanja sama sekali):
SELECT p.nama_pelanggan, 
       p.alamat
FROM pelanggan AS p
LEFT JOIN `order` AS o 
    ON p.id_pelanggan = o.id_pelanggan
WHERE o.id_order IS NULL;
```
- Sisi kiri adalah tabel yang diletakkan setelah kata `FROM`.
- Sisi kanan adalah tabel yang diletakkan setelah kata `JOIN`.
- Pada pola *Anti-Join*, kondisi `WHERE o.id_order IS NULL` menyaring hanya baris-baris dari tabel kiri yang gagal menemukan pasangan transaksi di tabel kanan.

### C. Format Baku `Self Join` (Hirarki Organisasi / Pohon)

```sql
SELECT staff.nama AS nama_karyawan,
       atasan.nama AS nama_manajer
FROM karyawan AS staff
INNER JOIN karyawan AS atasan 
    ON staff.id_atasan = atasan.id;
```
- Pada `Self Join`, satu tabel fisik diakses dua kali dengan memberikan dua alias peran yang berbeda (`staff` dan `atasan`).
- Jika ada manajer puncak yang tidak memiliki atasan (`id_atasan IS NULL`), gunakan `LEFT JOIN` agar manajer puncak tersebut tidak hilang dari hasil kueri.

### D. Format Penggabungan Banyak Tabel (Multi-Table JOIN $\ge 3$)

```sql
SELECT o.id_order,
       p.nama_pelanggan,
       pr.nama_produk,
       d.jumlah,
       d.subtotal
FROM `order` AS o
INNER JOIN pelanggan AS p 
    ON o.id_pelanggan = p.id_pelanggan
INNER JOIN detail_pesanan AS d 
    ON o.id_order = d.id_order
INNER JOIN produk AS pr 
    ON d.id_produk = pr.id_produk;
```
- Penyambungan tabel dieksekusi secara berantai dari tabel induk ke tabel-tabel perantara dan referensi.

---

## 3. Persiapan Lingkungan & Skema Praktikum

Untuk menjalankan seluruh simulasi praktik pada modul ini, kita menggunakan database `toko_elektronik_db` dengan skema relasi yang memuat tabel master kategori, tabel produk, serta tabel struktur staf karyawan berhirarki atasan.

### A. Skrip Pembuatan Database & Data Seeder
Jalankan skrip SQL berikut pada editor database Anda:

```sql
-- 1. Aktifkan database toko
CREATE DATABASE IF NOT EXISTS toko_elektronik_db;
USE toko_elektronik_db;

-- 2. Bersihkan tabel uji coba sebelumnya jika ada (urut anak ke induk)
DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS staf_karyawan;

-- 3. Tabel Master Kategori
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- 4. Tabel Produk (berelasi ke kategori, ada produk yang id_kategori bernilai NULL)
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    kode_sku VARCHAR(20) NOT NULL UNIQUE,
    nama_produk VARCHAR(100) NOT NULL,
    id_kategori INT NULL,
    harga DECIMAL(12, 2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori) 
        REFERENCES kategori(id_kategori) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Tabel Karyawan Berhirarki (Self-Referencing untuk Self Join)
CREATE TABLE staf_karyawan (
    id_staf INT AUTO_INCREMENT PRIMARY KEY,
    nama_staf VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) NOT NULL,
    id_manajer INT NULL,
    CONSTRAINT fk_staf_manajer FOREIGN KEY (id_manajer) 
        REFERENCES staf_karyawan(id_staf) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 6. Masukkan Data Uji Coba Kategori (Ada kategori yang belum punya barang: 'Fotografi')
INSERT INTO kategori (id_kategori, nama_kategori) VALUES
(1, 'Komputer'),
(2, 'Aksesoris'),
(3, 'Peralatan Kantor'),
(4, 'Audio'),
(5, 'Fotografi');

-- 7. Masukkan Data Uji Coba Produk (Ada barang yang belum masuk kategori: 'Meja Kerja Minimalis')
INSERT INTO produk (kode_sku, nama_produk, id_kategori, harga, stok) VALUES
('PRD-001', 'Laptop ThinkPad X1', 1, 18500000.00, 8),
('PRD-002', 'Mouse Wireless Silent', 2, 175000.00, 45),
('PRD-003', 'Keyboard Mechanical RGB', 2, 650000.00, 20),
('PRD-004', 'Monitor IPS 24 Inch', 1, 2100000.00, 12),
('PRD-005', 'Webcam Full HD 1080p', 2, 420000.00, 15),
('PRD-006', 'Printer Inkjet Warna', 3, 1950000.00, 6),
('PRD-007', 'Headset Gaming 7.1', 4, 550000.00, 18),
('PRD-008', 'Meja Kerja Minimalis', NULL, 850000.00, 5);

-- 8. Masukkan Data Uji Coba Staf Berhirarki
INSERT INTO staf_karyawan (id_staf, nama_staf, jabatan, id_manajer) VALUES
(1, 'Budi Santoso', 'General Manager', NULL),
(2, 'Siti Nurhaliza', 'Supervisor Gudang', 1),
(3, 'Andi Wijaya', 'Staff Administrasi', 2),
(4, 'Rina Permata', 'Supervisor Kasir', 1),
(5, 'Joko Susilo', 'Staff Kasir', 4);
```

---

## 4. Tutorial Praktik Terbimbing

Berikut adalah 4 contoh praktik bertingkat dari penggabungan dua tabel dasar hingga sintesis multi-tabel dengan agregasi.

### Contoh 1: Penggabungan Dua Tabel dengan `INNER JOIN`

Skenario: Toko ingin menampilkan katalog barang lengkap yang memuat nama produk, nama kategori resminya, dan harga, dengan format label yang rapi.

#### A. Format Umum SQL
```sql
SELECT alias_a.kolom_1, 
       alias_b.kolom_2 
FROM tabel_a AS alias_a
INNER JOIN tabel_b AS alias_b 
    ON alias_a.foreign_key = alias_b.primary_key;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    p.nama_produk AS 'Nama Produk',
    k.nama_kategori AS 'Kategori',
    p.harga AS 'Harga Satuan'
FROM produk AS p
INNER JOIN kategori AS k 
    ON p.id_kategori = k.id_kategori;
```

#### C. Penjelasan Baris Demi Baris
- **Baris 2–4 (`SELECT ...`):** Memproyeksikan nama produk dari tabel produk (`p`), nama kategori dari tabel kategori (`k`), dan harga dari produk.
- **Baris 5 (`FROM produk AS p`):** Menjadikan tabel produk sebagai tabel sisi primer kiri dengan alias `p`.
- **Baris 6 (`INNER JOIN kategori AS k`):** Menggabungkan tabel kategori sebagai tabel kanan dengan alias `k`.
- **Baris 7 (`ON p.id_kategori = k.id_kategori`):** Titik temu pencocokan baris data. Baris produk `Meja Kerja Minimalis` (yang `id_kategori`-nya NULL) dan kategori `Fotografi` (yang tidak ada produknya) **tidak dimunculkan**, karena `INNER JOIN` hanya meloloskan pasangan data yang cocok di kedua sisi.

#### D. Hasil Keluaran (Output MySQL)
```text
+-------------------------+------------------+--------------+
| Nama Produk             | Kategori         | Harga Satuan |
+-------------------------+------------------+--------------+
| Laptop ThinkPad X1      | Komputer         |  18500000.00 |
| Monitor IPS 24 Inch     | Komputer         |   2100000.00 |
| Mouse Wireless Silent   | Aksesoris        |    175000.00 |
| Keyboard Mechanical RGB | Aksesoris        |    650000.00 |
| Webcam Full HD 1080p    | Aksesoris        |    420000.00 |
| Printer Inkjet Warna    | Peralatan Kantor |   1950000.00 |
| Headset Gaming 7.1      | Audio            |    550000.00 |
+-------------------------+------------------+--------------+
7 rows in set (0.00 sec)
```

---

### Contoh 2: Menemukan Data Kosong Tanpa Transaksi dengan `LEFT JOIN` (*Anti-Join*)

Skenario: Manajemen ingin mendeteksi kategori barang mana saja yang **belum memiliki produk terdaftar sama sekali** untuk segera dicarikan pasokan barangnya oleh bagian *purchasing*.

#### A. Pelacakan Kondisi Antara (*State Tracing*)
Pada `LEFT JOIN` dari `kategori` ke `produk`:

```text
[Tabel Kategori (Kiri)]         [Tabel Produk (Kanan)]
1. Komputer             ──────► Cocok dengan 2 produk
2. Aksesoris            ──────► Cocok dengan 3 produk
3. Peralatan Kantor     ──────► Cocok dengan 1 produk
4. Audio                ──────► Cocok dengan 1 produk
5. Fotografi            ──────► TIDAK ADA PASANGAN! -> Kolom produk terisi NULL!
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    k.id_kategori,
    k.nama_kategori,
    p.nama_produk
FROM kategori AS k
LEFT JOIN produk AS p 
    ON k.id_kategori = p.id_kategori
WHERE p.id_produk IS NULL;
```

#### C. Hasil Keluaran (Output MySQL)
```text
+-------------+---------------+-------------+
| id_kategori | nama_kategori | nama_produk |
+-------------+---------------+-------------+
|           5 | Fotografi     | NULL        |
+-------------+---------------+-------------+
1 row in set (0.00 sec)
```
*Analisis:* Kategori `Fotografi` berhasil dideteksi sebagai kategori kosong karena kolom `p.id_produk` bernilai `NULL`.

---

### Contoh 3: Relasi Hirarki Internal dengan `Self Join`

Skenario: Bagian SDM ingin mencetak bagan organisasi staf yang menampilkan nama setiap karyawan, jabatannya, serta **nama atasan langsungnya**. Karyawan yang tidak memiliki atasan (General Manager) tetap harus tampil dengan keterangan `'Pimpinan Tertinggi'`.

#### A. Format Umum SQL
```sql
SELECT staf.nama_kolom, 
       COALESCE(atasan.nama_kolom, 'Teks Pengganti') AS nama_atasan
FROM nama_tabel AS staf
LEFT JOIN nama_tabel AS atasan 
    ON staf.kolom_fk_atasan = atasan.kolom_pk_staf;
```

#### B. Kode SQL Eksekusi
```sql
SELECT 
    staf.nama_staf AS 'Nama Karyawan',
    staf.jabatan AS 'Jabatan',
    COALESCE(manajer.nama_staf, 'Pimpinan Tertinggi') AS 'Atasan Langsung'
FROM staf_karyawan AS staf
LEFT JOIN staf_karyawan AS manajer 
    ON staf.id_manajer = manajer.id_staf;
```

#### C. Penjelasan Baris Demi Baris
- **`FROM staf_karyawan AS staf`**: Memposisikan tabel fisik sebagai peran bawahan/staf.
- **`LEFT JOIN staf_karyawan AS manajer`**: Memanggil kembali tabel fisik yang sama dengan peran sebagai atasan/manajer.
- **`ON staf.id_manajer = manajer.id_staf`**: Menyambungkan kunci `id_manajer` milik staf ke kunci `id_staf` milik manajer.
- **`COALESCE(..., 'Pimpinan Tertinggi')`**: Menggantikan nilai `NULL` pada baris Budi Santoso (karena pimpinan tertinggi tidak memiliki atasan).

#### D. Hasil Keluaran (Output MySQL)
```text
+----------------+--------------------+--------------------+
| Nama Karyawan  | Jabatan            | Atasan Langsung    |
+----------------+--------------------+--------------------+
| Budi Santoso   | General Manager    | Pimpinan Tertinggi |
| Siti Nurhaliza | Supervisor Gudang  | Budi Santoso       |
| Andi Wijaya    | Staff Administrasi | Siti Nurhaliza     |
| Rina Permata   | Supervisor Kasir   | Budi Santoso       |
| Joko Susilo    | Staff Kasir        | Rina Permata       |
+----------------+--------------------+--------------------+
5 rows in set (0.00 sec)
```

---

### Contoh 4: Penggabungan Banyak Tabel dan Agregasi (Pendalaman)

Skenario: Manajemen toko ingin membuat rekapitulasi data per kategori: Nama kategori, jumlah produk aktif di kategori tersebut, dan total nilai aset barang pada kategori tersebut, terurut dari nilai aset terbesar. Kategori tanpa produk harus tetap muncul dengan nilai aset Rp 0.

#### A. Kode SQL Eksekusi
```sql
SELECT 
    k.nama_kategori AS 'Kategori',
    COUNT(p.id_produk) AS 'Jumlah Produk',
    COALESCE(SUM(p.harga * p.stok), 0.00) AS 'Total Nilai Aset'
FROM kategori AS k
LEFT JOIN produk AS p 
    ON k.id_kategori = p.id_kategori
GROUP BY k.id_kategori, k.nama_kategori
ORDER BY 'Total Nilai Aset' DESC;
```

#### B. Mengapa Menggunakan `COUNT(p.id_produk)` bukan `COUNT(*)`?
- Jika menggunakan `COUNT(*)`, kategori `Fotografi` yang memiliki 1 baris NULL pada tabel kanan akan dihitung bernilai **`1`** (karena baris fisiknya ada).
- Dengan menulis `COUNT(p.id_produk)`, MySQL hanya menghitung nilai non-NULL pada kolom kunci produk, sehingga kategori `Fotografi` tepat menghasilkan nilai **`0`**.

#### C. Hasil Keluaran (Output MySQL)
```text
+------------------+---------------+------------------+
| Kategori         | Jumlah Produk | Total Nilai Aset |
+------------------+---------------+------------------+
| Komputer         |             2 |     173200000.00 |
| Aksesoris        |             3 |      27175000.00 |
| Peralatan Kantor |             1 |      11700000.00 |
| Audio            |             1 |       9900000.00 |
| Fotografi        |             0 |             0.00 |
+------------------+---------------+------------------+
5 rows in set (0.00 sec)
```

---

## 5. Verifikasi Output & Kendala Umum (Troubleshooting)

### A. Gejala Kesalahan Umum (*Common Pitfalls*)

| Pesan Kesalahan (*Error Message*) | Akar Masalah (*Root Cause*) | Solusi Perbaikan |
| :--- | :--- | :--- |
| `ERROR 1052 (23000): Column 'id' in field list is ambiguous` | Memanggil kolom yang ada di kedua tabel tanpa menyertakan alias/nama tabel pemiliknya. | Beri awalan alias tabel pada kolom: `p.id` atau `k.id`. |
| Baris hasil kueri meledak menjadi ribuan baris di luar dugaan | Lupa menuliskan klausa `ON` pada perintah `JOIN`, sehingga memicu *Cartesian Product* (*CROSS JOIN* tak sengaja). | Pastikan setiap kata kunci `JOIN` selalu diikuti syarat relasi `ON kunci_pk = kunci_fk`. |
| Data pada tabel kiri hilang saat menggunakan `JOIN` biasa | Menggunakan `INNER JOIN` pada tabel yang datanya belum tentu memiliki transaksi di tabel kanan. | Ganti menjadi `LEFT JOIN` agar seluruh baris tabel kiri tetap dipertahankan. |
| Nilai agregat `COUNT` pada kelompok kosong menghasilkan angka 1 | Menggunakan `COUNT(*)` pada hasil `LEFT JOIN`. | Ubah menjadi `COUNT(tabel_kanan.id_pk)` agar baris NULL tidak dihitung. |

### B. Praktik Terbaik Efisiensi & Kinerja (*Performance Tips*)
1. **Selalu Berikan Indeks pada Kolom *Foreign Key*:**  
   Ketika MySQL mengeksekusi `ON a.id_kategori = b.id_kategori`, mesin pencari akan menggunakan algoritma *Index Lookup* jika kolom FK terindeks, mengubah waktu pemrosesan dari $O(N \times M)$ menjadi $O(N \log M)$.
2. **Utamakan `INNER JOIN` Jika Data Kosong Tidak Dibutuhkan:**  
   `INNER JOIN` memberikan keleluasaan bagi *Query Optimizer* MySQL untuk menentukan urutan tabel mana yang lebih efisien dibaca terlebih dahulu (*Join Reordering*), sedangkan `LEFT JOIN` memaksa MySQL membaca tabel kiri terlebih dahulu.

---

## 6. Latihan Soal Praktik

Kerjakan seluruh butir latihan di bawah ini berdasarkan 4 tabel acuan berikut:

### Tabel Acuan Praktik

#### Tabel: karyawan
| id | nama | jabatan | gaji | id_atasan |
| :---: | :--- | :--- | :---: | :---: |
| 1 | Budi | Manager | 100000 | NULL |
| 2 | Siti | Staff | 50000 | 1 |
| 3 | Andi | Staff | 60000 | 1 |
| 4 | Rina | Supervisor | 80000 | 1 |
| 5 | Joko | Manager | 120000 | NULL |

#### Tabel: pelanggan
| id_pelanggan | nama_pelanggan | alamat |
| :---: | :--- | :--- |
| 1 | Ali | Jl. Merdeka 1 |
| 2 | Siti | Jl. Kenanga 2 |
| 3 | Budi | Jl. Melati 3 |
| 4 | Rina | Jl. Mawar 4 |
| 5 | Joko | Jl. Anggrek 5 |

#### Tabel: order
| id_order | tanggal | total | id_pelanggan |
| :---: | :---: | :---: | :---: |
| 1 | 2024-10-01 | 2000000 | 1 |
| 2 | 2024-10-02 | 750000 | 2 |
| 3 | 2024-10-03 | 3000000 | 3 |
| 4 | 2024-10-04 | 1500000 | 1 |
| 5 | 2024-10-05 | 500000 | 2 |

#### Tabel: produk
| id_produk | nama_produk | harga | stok |
| :---: | :--- | :---: | :---: |
| 1 | Laptop | 15000000 | 5 |
| 2 | Smartphone | 5000000 | 10 |
| 3 | Tablet | 8000000 | 2 |
| 4 | Monitor | 1200000 | 8 |
| 5 | Keyboard | 300000 | 15 |

---

### Daftar Tugas Praktik

1. Tulis perintah SQL untuk menampilkan `id_order`, `tanggal`, `total`, dan `nama_pelanggan` dengan menggabungkan tabel `order` dan tabel `pelanggan` menggunakan `INNER JOIN`.
2. Tulis perintah SQL untuk menampilkan `id_order`, `nama_pelanggan`, dan `total`, hanya untuk transaksi pesanan yang memiliki nilai `total` lebih besar dari `1000000`, menggunakan `INNER JOIN` dan klausa `WHERE`.
3. Tulis perintah SQL untuk menampilkan seluruh pelanggan (`nama_pelanggan`) beserta `id_order` dan `total` pesanannya menggunakan `LEFT JOIN`, sehingga pelanggan yang belum pernah memesan tetap muncul di hasil kueri dengan nilai NULL.
4. Tulis perintah SQL untuk menampilkan daftar pelanggan yang **belum pernah melakukan transaksi pemesanan sama sekali** menggunakan teknik `LEFT JOIN` dan filter `IS NULL` (*Anti-Join*).
5. Tulis perintah SQL untuk menampilkan seluruh transaksi pesanan beserta nama pelanggan yang memesan menggunakan `RIGHT JOIN` dari tabel `pelanggan` ke tabel `order`.
6. Tulis perintah SQL untuk menampilkan nama staf (`nama`) beserta nama atasannya langsung (`nama_atasan`) menggunakan teknik `Self Join` (`INNER JOIN`) pada tabel `karyawan`.
7. Tulis perintah SQL untuk menampilkan seluruh karyawan beserta nama atasannya menggunakan `LEFT JOIN` pada tabel `karyawan` (Self Join), sehingga manajer yang tidak memiliki atasan tetap tampil dengan nilai NULL pada kolom atasan.
8. Tulis perintah SQL untuk menghitung jumlah transaksi (`COUNT(o.id_order)`) dan total pengeluaran belanja (`SUM(o.total)`) untuk setiap pelanggan, termasuk pelanggan yang belum pernah bertransaksi (gunakan `LEFT JOIN` dan `GROUP BY`).
9. Tulis perintah SQL untuk menampilkan kombinasi perkalian silang (*Cartesian Product*) antara `nama_produk` dari tabel `produk` dan `nama_pelanggan` dari tabel `pelanggan` menggunakan `CROSS JOIN`, serta batasi tampilan hanya untuk 5 baris pertama.
10. Tulis perintah SQL untuk menampilkan `nama_pelanggan` dan total belanja bagi pelanggan yang memiliki akumulasi belanja di atas `2000000`, urutkan dari total belanja terbesar, menggunakan `INNER JOIN`, `GROUP BY`, `HAVING`, dan `ORDER BY`.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Cocokkan hasil eksekusi kueri latihan Anda di terminal atau editor database dengan **target tampilan tabel output** berikut. Kueri Anda dinyatakan benar apabila format kolom, nama alias, susunan baris, dan isi data yang dihasilkan cocok persis dengan tabel target di bawah ini:*

### A. Target Tampilan Output Tabel Latihan (Soal 1–10)

#### Target Output Soal 1 (INNER JOIN: Order & Pelanggan)
```text
+----------+------------+---------+----------------+
| id_order | tanggal    | total   | nama_pelanggan |
+----------+------------+---------+----------------+
|        1 | 2024-10-01 | 2000000 | Ali            |
|        2 | 2024-10-02 |  750000 | Siti           |
|        3 | 2024-10-03 | 3000000 | Budi           |
|        4 | 2024-10-04 | 1500000 | Ali            |
|        5 | 2024-10-05 |  500000 | Siti           |
+----------+------------+---------+----------------+
5 rows in set
```

#### Target Output Soal 2 (INNER JOIN + Filter WHERE Total > 1000000)
```text
+----------+----------------+---------+
| id_order | nama_pelanggan | total   |
+----------+----------------+---------+
|        1 | Ali            | 2000000 |
|        3 | Budi           | 3000000 |
|        4 | Ali            | 1500000 |
+----------+----------------+---------+
3 rows in set
```

#### Target Output Soal 3 (LEFT JOIN: Seluruh Pelanggan & Pesanan)
```text
+----------------+----------+---------+
| nama_pelanggan | id_order | total   |
+----------------+----------+---------+
| Ali            |        1 | 2000000 |
| Ali            |        4 | 1500000 |
| Siti           |        2 |  750000 |
| Siti           |        5 |  500000 |
| Budi           |        3 | 3000000 |
| Rina           |     NULL |    NULL |
| Joko           |     NULL |    NULL |
+----------------+----------+---------+
7 rows in set
```

#### Target Output Soal 4 (Anti-Join: Pelanggan yang Belum Pernah Belanja)
```text
+--------------+----------------+-------------+
| id_pelanggan | nama_pelanggan | alamat      |
+--------------+----------------+-------------+
|            4 | Rina           | Jl. Mawar 4 |
|            5 | Joko           | Jl. Anggrek 5|
+--------------+----------------+-------------+
2 rows in set
```

#### Target Output Soal 5 (RIGHT JOIN: Order ke Pelanggan)
```text
+----------+------------+---------+----------------+
| id_order | tanggal    | total   | nama_pelanggan |
+----------+------------+---------+----------------+
|        1 | 2024-10-01 | 2000000 | Ali            |
|        2 | 2024-10-02 |  750000 | Siti           |
|        3 | 2024-10-03 | 3000000 | Budi           |
|        4 | 2024-10-04 | 1500000 | Ali            |
|        5 | 2024-10-05 |  500000 | Siti           |
+----------+------------+---------+----------------+
5 rows in set
```

#### Target Output Soal 6 (Self Join INNER: Karyawan & Atasan)
```text
+------+------------+-------------+
| nama | jabatan    | nama_atasan |
+------+------------+-------------+
| Siti | Staff      | Budi        |
| Andi | Staff      | Budi        |
| Rina | Supervisor | Budi        |
+------+------------+-------------+
3 rows in set
```

#### Target Output Soal 7 (Self Join LEFT: Seluruh Karyawan & Atasan)
```text
+------+------------+-------------+
| nama | jabatan    | nama_atasan |
+------+------------+-------------+
| Budi | Manager    | NULL        |
| Siti | Staff      | Budi        |
| Andi | Staff      | Budi        |
| Rina | Supervisor | Budi        |
| Joko | Manager    | NULL        |
+------+------------+-------------+
5 rows in set
```

#### Target Output Soal 8 (LEFT JOIN + GROUP BY: Rekapitulasi Semua Pelanggan)
```text
+----------------+------------------+---------------+
| nama_pelanggan | Jumlah Transaksi | Total Belanja |
+----------------+------------------+---------------+
| Ali            |                2 |       3500000 |
| Siti           |                2 |       1250000 |
| Budi           |                1 |       3000000 |
| Rina           |                0 |          NULL |
| Joko           |                0 |          NULL |
+----------------+------------------+---------------+
5 rows in set
```
*(Catatan: Kolom Total Belanja bernilai NULL atau 0 tergantung penggunaan fungsi COALESCE).*

#### Target Output Soal 9 (CROSS JOIN: Produk & Pelanggan - LIMIT 5)
```text
+-------------+----------------+
| nama_produk | nama_pelanggan |
+-------------+----------------+
| Laptop      | Ali            |
| Smartphone  | Ali            |
| Tablet      | Ali            |
| Monitor     | Ali            |
| Keyboard    | Ali            |
+-------------+----------------+
5 rows in set
```

#### Target Output Soal 10 (Sintesis: INNER JOIN, GROUP BY, HAVING, ORDER BY)
```text
+----------------+---------------+
| nama_pelanggan | Total Belanja |
+----------------+---------------+
| Ali            |       3500000 |
| Budi           |       3000000 |
+----------------+---------------+
2 rows in set
```

---

### B. Checklist Kesiapan Teknis
- [ ] Selalu menyertakan nama alias tabel pada setiap nama kolom kueri JOIN untuk mencegah *ambiguous error*.
- [ ] Memahami perbedaan perilaku baris yang dihasilkan oleh `INNER JOIN` dan `LEFT JOIN`.
- [ ] Menggunakan fungsi `COUNT(tabel_kanan.id_pk)` saat melakukan agregasi pada hasil `LEFT JOIN`.
- [ ] Memahami pola *Anti-Join* menggunakan `LEFT JOIN ... WHERE kolom_kanan IS NULL`.
- [ ] Seluruh tabel keluaran kueri di editor Anda cocok persis dengan target visual di atas.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci kueri SQL lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-09-relasi-multi-tabel-inner-join-left-join-right-join-self-join--cross-join).

---

## 8. Rangkuman & Refleksi

### Rangkuman Sintaks
1. **`INNER JOIN`** menyatukan dua tabel hanya pada baris yang memenuhi syarat kesamaan kunci pada klausa `ON`.
2. **`LEFT JOIN`** mempertahankan seluruh baris tabel kiri dan mengisi nilai `NULL` untuk tabel kanan jika tidak ditemukan kecocokan.
3. **`Self Join`** menghubungkan satu tabel fisik dengan dirinya sendiri untuk memetakan hubungan hirarki atau subordinasi.
4. **`CROSS JOIN`** menghasilkan perkalian baris tanpa syarat penggabungan.
5. Menghubungkan 3 tabel atau lebih dilakukan secara berantai menggunakan beberapa klausa `JOIN ... ON`.

### Pertanyaan Refleksi Mandiri
1. Apa yang akan terjadi jika sebuah kueri menggabungkan tabel `pelanggan` (100 baris) dan tabel `order` (1.000 baris) menggunakan perintah `JOIN` namun lupa menuliskan klausa `ON`? Berapa baris data yang akan dihasilkan?
2. Mengapa kueri *Anti-Join* menggunakan kolom Primary Key tabel kanan yang diuji dengan `IS NULL` (`WHERE o.id_order IS NULL`) untuk mendeteksi data yang belum memiliki relasi?
3. Dalam sistem kasir toko online, mengapa relasi dari tabel `pelanggan` ke tabel `order` sebaiknya menggunakan `LEFT JOIN` saat menghitung statistik loyalitas seluruh pelanggan?

---
*Materi Berikutnya:* **Modul 10: Kueri Bertingkat & Analisis Lanjut (Subquery, CTE / WITH Clause, Window Functions, & JSON)**.
