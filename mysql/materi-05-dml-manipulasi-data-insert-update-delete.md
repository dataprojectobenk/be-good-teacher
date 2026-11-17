# Modul 05: DML — Manipulasi Data (`INSERT`, `UPDATE`, `DELETE` vs `TRUNCATE`)

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### Prasyarat Awal
- Anda sudah mampu membuat tabel dan menghubungkannya menggunakan `PRIMARY KEY` serta `FOREIGN KEY` (**Modul 03 & Modul 04**).
- Anda sudah memahami peran aturan pembatas data seperti `NOT NULL`, `DEFAULT`, `UNIQUE`, dan `CHECK`.

### Jembatan Konsep: Dari Cetakan Formulir Kosong Menuju Pengisian Data
Pada modul sebelumnya, Anda telah berhasil merancang dan mendirikan struktur 5 tabel berelasi di `toko_db` menggunakan perintah **DDL** (*Data Definition Language*). Namun, tabel-tabel tersebut ibarat *buku kas dan formulir cetak yang masih kosong* (belum ada tinta catatan di dalamnya).

Kini kita melangkah ke kelompok perintah **DML** (*Data Manipulation Language*). DML bertugas mengelola baris catatan di dalam tabel:
1. **`INSERT INTO`**: Menuliskan baris catatan baru ke dalam tabel.
2. **`UPDATE`**: Memperbaiki atau memperbarui isi kolom pada baris yang sudah ada.
3. **`DELETE`**: Menghapus baris tertentu yang sudah tidak diperlukan berdasarkan syarat khusus.
4. **`TRUNCATE TABLE`**: Mengosongkan seluruh baris tabel secara instan dan mengembalikan nomor urut otomatis ke angka awal.

```text
[Tabel Kosong (Hasil DDL)]
        │
        ▼  (1. INSERT INTO — Mengisi data induk lalu data anak)
[Tabel Berisi Catatan Data]
        │
        ├──► (2. UPDATE ... SET ... WHERE — Mengubah nilai kolom tertentu)
        ├──► (3. DELETE FROM ... WHERE   — Menghapus baris tertentu secara aman)
        └──► (4. TRUNCATE TABLE          — Mengosongkan seluruh isi tabel seketika)
```

### Tujuan Pembelajaran
Setelah menyelesaikan Modul 05, Anda diharapkan mampu:
1. Memahami **Format Umum SQL** untuk perintah `INSERT INTO` (tunggal dan multi-baris), `UPDATE`, `DELETE`, dan `TRUNCATE TABLE`.
2. Menerapkan urutan pengisian data yang benar pada tabel berelasi: **mengisi tabel Induk terlebih dahulu sebelum tabel Anak** agar tidak memicu error `FOREIGN KEY`.
3. Memperbarui dan menghapus data secara terarah menggunakan klausa pembatas **`WHERE`** tanpa merusak baris data lainnya.
4. Menjelaskan perbedaan mendasar antara **`DELETE`** dan **`TRUNCATE TABLE`** dari sisi mekanisme kerja, kecepatan, efek pada nomor `AUTO_INCREMENT`, dan batasan relasi kunci asing.

---

## 2. Kamus Format Umum SQL & Perintah DML

### A. Format Umum `INSERT INTO` (Menambah Baris Data)

#### 1. Memasukkan Satu Baris Data (*Single-Row Insert*)
```sql
INSERT INTO nama_tabel (kolom_1, kolom_2, kolom_3)
VALUES (nilai_1, nilai_2, nilai_3);
```

#### 2. Memasukkan Banyak Baris Sekaligus (*Multi-Row Insert*)
```sql
INSERT INTO nama_tabel (kolom_1, kolom_2, kolom_3)
VALUES
    (baris1_nilai1, baris1_nilai2, baris1_nilai3),
    (baris2_nilai1, baris2_nilai2, baris2_nilai3),
    (baris3_nilai1, baris3_nilai2, baris3_nilai3);
```

**Aturan Penulisan `INSERT INTO`:**
- **Kolom `AUTO_INCREMENT`:** Tidak perlu dicantumkan di daftar kolom; MySQL akan memberikan nomor urut berikutnya secara otomatis (`1, 2, 3, ...`).
- **Kolom yang memiliki nilai `DEFAULT` atau boleh `NULL`:** Boleh dilewati dari daftar kolom.
- **Tipe Data Teks (`CHAR`, `VARCHAR`, `TEXT`) dan Tanggal (`DATE`, `DATETIME`):** Nilainya **wajib diapit tanda petik tunggal** (contoh: `'Madu Hutan'`, `'2026-11-01'`).
- **Tipe Data Angka (`INT`, `DECIMAL`):** Ditulis **langsung tanpa tanda petik** (contoh: `75000` atau `15.50`).

---

### B. Format Umum `UPDATE` (Memperbarui Data yang Sudah Ada)

```sql
UPDATE nama_tabel
SET kolom_1 = nilai_baru_1,
    kolom_2 = nilai_baru_2
WHERE kondisi_penyaring;
```

> **Peringatan Kritis:** **Selalu gunakan klausa `WHERE` pada perintah `UPDATE`!** Jika Anda menjalankan `UPDATE produk SET harga = 50000;` tanpa klausa `WHERE`, maka **seluruh produk di toko Anda akan berubah harganya menjadi 50.000**. Klausa `WHERE` bertindak sebagai penunjuk sasaran baris mana yang berhak diubah.

---

### C. Format Umum `DELETE` (Menghapus Baris Tertentu)

```sql
DELETE FROM nama_tabel
WHERE kondisi_penyaring;
```

Sama halnya dengan `UPDATE`, perintah `DELETE FROM nama_tabel;` tanpa `WHERE` akan **menghapus seluruh baris data** di dalam tabel tersebut satu per satu.

---

### D. Format Umum `TRUNCATE TABLE` & Tabel Perbandingan dengan `DELETE`

```sql
TRUNCATE TABLE nama_tabel;
```

| Aspek Perbandingan | `DELETE FROM nama_tabel;` | `TRUNCATE TABLE nama_tabel;` |
| :--- | :--- | :--- |
| **Kategori Perintah** | **DML** (*Data Manipulation Language*) | **DDL** (*Data Definition Language*) |
| **Cara Kerja di Mesin** | Menghapus data **baris demi baris** sambil mencatatnya ke buku log transaksi (*undo log*). | Menghapus seluruh halaman tabel dan membuat ulang wadah tabel kosong yang baru. |
| **Kecepatan pada Data Besar** | Lebih lambat karena mencatat tiap penghapusan baris. | Sangat cepat dan efisien. |
| **Kolom `AUTO_INCREMENT`** | **Melanjutkan** nomor urut terakhir (misal baris terakhir ID 10 dihapus, baris baru berikutnya tetap bernomor 11). | **Mereset kembali ke angka 1** seperti saat tabel pertama kali dibuat. |
| **Tabel yang Dirujuk `FOREIGN KEY`** | Boleh dijalankan (jika tidak melanggar baris anak aktif atau memakai `CASCADE`). | **Ditolak otomatis oleh MySQL** jika tabel tersebut sedang dirujuk oleh tabel lain yang ber-`FOREIGN KEY`. |

---

## 3. Persiapan Lingkungan & Skema Praktikum

1. Nyalakan layanan **MySQL Server** di komputer Anda, lalu buka editor pilihan Anda (**CLI**, **VS Code**, atau tab **SQL** di **phpMyAdmin**).
2. Pastikan database `toko_db` aktif dengan menjalankan `USE toko_db;`.
3. **Pahami Urutan Ketergantungan Pengisian Data:**
   Sesuai rancangan relasi Modul 04, tabel memiliki hierarki Induk dan Anak:
   ```text
   Tabel Induk (Isi Lebih Dulu)  ──►  kategori, pelanggan
                                           │
                                           ▼
   Tabel Anak (Isi Berikutnya)   ──►  produk, pesanan
                                           │
                                           ▼
   Tabel Penghubung M:N (Terakhir)►  detail_pesanan
   ```
   Jika Anda mencoba memasukkan data ke tabel `produk` dengan `id_kategori = 99` padahal di tabel `kategori` belum ada ID 99, MySQL akan **menolak data tersebut** dengan pesan `ERROR 1452 (Cannot add or update a child row: a foreign key constraint fails)`.

---

## 4. Contoh Praktik Bertingkat — Studi Kasus `toko_db`

Setiap contoh di bawah mengikuti alur tetap: **(1) Format Umum SQL → (2) Diagram Data Sebelum Eksekusi → (3) Kode SQL → (4) Penjelasan Tiap Baris → (5) Hasil Keluaran / Pelacakan Data (*State Tracing*)**.

### Contoh 1 (Tingkat Dasar): Memasukkan Data Induk (`kategori` & `pelanggan`)

Kita mulai dengan mengisi data tabel Induk yang belum bergantung pada tabel mana pun.

#### 1. Format Umum yang Dipakai
```sql
USE nama_database;

INSERT INTO tabel_induk (kolom_teks, kolom_keterangan)
VALUES ('Nilai 1', 'Nilai 2');

INSERT INTO tabel_induk (kolom_teks, kolom_keterangan)
VALUES
    ('Nilai A1', 'Nilai A2'),
    ('Nilai B1', 'Nilai B2');
```

#### 2. Rencana Data yang Akan Dimasukkan
- **Tabel `kategori`:**
  - Baris 1: `nama_kategori = 'Herbal'`, `keterangan = 'Produk kesehatan alami'`
  - Baris 2: `nama_kategori = 'Pangan Sunnah'`, `keterangan = 'Makanan dan buah sunnah pilihan'`
  - Baris 3: `nama_kategori = 'Perlengkapan Ibadah'`, `keterangan = 'Pakaian dan kelengkapan ibadah'`
- **Tabel `pelanggan`:**
  - Baris 1: Budi Santoso (`081211112222`, `budi@email.com`)
  - Baris 2: Siti Aminah (`081322223333`, `siti@email.com`)
  - Baris 3: Ahmad Fauzi (`081533334444`, `fauzi@email.com`)

#### 3. Kode SQL Implementasi
```sql
USE toko_db;

-- 1. Mengisi satu baris ke tabel kategori (Single-Row Insert)
INSERT INTO kategori (nama_kategori, keterangan)
VALUES ('Herbal', 'Produk kesehatan alami');

-- 2. Mengisi dua baris sekaligus ke tabel kategori (Multi-Row Insert)
INSERT INTO kategori (nama_kategori, keterangan)
VALUES
    ('Pangan Sunnah', 'Makanan dan buah sunnah pilihan'),
    ('Perlengkapan Ibadah', 'Pakaian dan kelengkapan ibadah');

-- 3. Mengisi data pelanggan sekaligus banyak baris
INSERT INTO pelanggan (nama_pelanggan, no_hp, email)
VALUES
    ('Budi Santoso', '081211112222', 'budi@email.com'),
    ('Siti Aminah', '081322223333', 'siti@email.com'),
    ('Ahmad Fauzi', '081533334444', 'fauzi@email.com');
```

#### 4. Penjelasan Tiap Baris
- **Baris 1 (`USE toko_db;`):** Mengarahkan eksekusi ke database `toko_db`.
- **Baris 4–5:** Memasukkan satu baris kategori `'Herbal'`. Kolom `id_kategori` tidak dituliskan karena bertipe `AUTO_INCREMENT` dan akan otomatis bernilai `1`.
- **Baris 8–11:** Memasukkan dua baris kategori berikutnya dalam satu perintah yang efisien. Nilai antarkelompok baris dipisahkan tanda koma `,`. Baris-baris ini otomatis mendapatkan `id_kategori` bernilai `2` dan `3`.
- **Baris 14–18:** Memasukkan tiga pelanggan pertama. Kolom `no_hp` dan `email` dipastikan unik sesuai aturan `UNIQUE` Modul 04.

#### 5. Hasil Keluaran & Pelacakan Data (*State Tracing*)
```text
Query OK, 1 row affected
Query OK, 2 rows affected
Query OK, 3 rows affected
```

**Isi Tabel `kategori` Saat Ini:**
| id_kategori (Auto) | nama_kategori | keterangan |
| :---: | :--- | :--- |
| **1** | Herbal | Produk kesehatan alami |
| **2** | Pangan Sunnah | Makanan dan buah sunnah pilihan |
| **3** | Perlengkapan Ibadah | Pakaian dan kelengkapan ibadah |

---

### Contoh 2 (Tingkat Menengah): Memasukkan Data Anak Berelasi (`produk`, `pesanan`, `detail_pesanan`)

Sekarang kita mengisi tabel Anak dengan memanfaatkan nomor `id_kategori` dan `id_pelanggan` yang baru saja tercipta di atas.

#### 1. Format Umum yang Dipakai
```sql
INSERT INTO tabel_anak (id_induk_fk, kode, nama, harga, stok, ...)
VALUES (id_induk_yang_sudah_ada, 'KOD01', 'Nama Produk', 50000, 10, ...);
```

#### 2. Diagram Relasi Nilai Kunci Asing (*FK Tracing*)
```text
kategori (id_kategori: 1, 2) ──► dirujuk oleh ──► produk (id_kategori: 1, 2)
pelanggan (id_pelanggan: 1)  ──► dirujuk oleh ──► pesanan (id_pelanggan: 1)
pesanan (1) & produk (1, 2)  ──► dirujuk oleh ──► detail_pesanan (id_pesanan: 1, id_produk: 1, 2)
```

#### 3. Kode SQL Implementasi
```sql
USE toko_db;

-- 1. Mengisi katalog produk (id_kategori 1 = Herbal, id_kategori 2 = Pangan Sunnah)
INSERT INTO produk (id_kategori, kode_sku, nama_produk, harga, stok, tanggal_kadaluwarsa)
VALUES
    (1, 'SKU-0001', 'Madu Hutan Asli 500g', 75000.00, 20, '2027-12-31'),
    (2, 'SKU-0002', 'Kurma Ajwa Madinah 1kg', 90000.00, 15, '2027-06-30'),
    (1, 'SKU-0003', 'Minyak Zaitun Extra Virgin 250ml', 65000.00, 10, '2028-01-15');

-- 2. Membuat satu nota pesanan milik pelanggan Budi Santoso (id_pelanggan = 1)
INSERT INTO pesanan (id_pelanggan, status_pesanan)
VALUES (1, 'Menunggu');

-- 3. Mengisi rincian barang yang dibeli pada pesanan ID 1 (Madu 2 pcs, Kurma 1 pcs)
INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_saat_beli)
VALUES
    (1, 1, 2, 75000.00),
    (1, 2, 1, 90000.00);
```

#### 4. Penjelasan Tiap Baris
- **Baris 4–8:** Memasukkan 3 produk. Nilai kolom pertama (`1`, `2`, `1`) adalah `id_kategori` yang valid dari tabel `kategori`. Kolom `dibuat_pada` tidak ditulis karena otomatis terisi waktu sekarang via `DEFAULT CURRENT_TIMESTAMP`.
- **Baris 11–12:** Membuat nota pesanan untuk pelanggan nomor `1` (`id_pelanggan = 1`). Kolom `id_pesanan` otomatis terbit dengan nilai `1`.
- **Baris 15–18:** Mencatat dua jenis barang yang dibeli di dalam `pesanan` ID `1`. Baris pertama mencatat pembelian 2 botol Madu (`id_produk = 1`), baris kedua mencatat 1 kotak Kurma (`id_produk = 2`).

#### 5. Hasil Keluaran
```text
Query OK, 3 rows affected
Query OK, 1 row affected
Query OK, 2 rows affected
```

---

### Contoh 3 (Tingkat Lanjut): Memperbarui Data Tertarget dengan `UPDATE ... SET ... WHERE`

Dalam kegiatan toko sehari-hari, data sering kali berubah (stok berkurang setelah terjual, harga barang naik, atau status nota pesanan berubah setelah dibayar).

#### 1. Format Umum yang Dipakai
```sql
UPDATE nama_tabel
SET kolom_target_1 = nilai_baru,
    kolom_target_2 = nilai_baru
WHERE kolom_kunci = nilai_tertentu;
```

#### 2. Skenario Pembaruan Data
1. Pelanggan Budi Santoso telah mentransfer pembayaran untuk pesanan ID `1`. Status pesanan diubah dari `'Menunggu'` menjadi `'Dibayar'`.
2. Stok *Madu Hutan* (`id_produk = 1`) yang awalnya `20` berkurang `2` menjadi `18`, dan harganya disesuaikan menjadi `78000.00`.

#### 3. Kode SQL Implementasi
```sql
USE toko_db;

-- 1. Mengubah status pesanan ID 1 menjadi 'Dibayar'
UPDATE pesanan
SET status_pesanan = 'Dibayar'
WHERE id_pesanan = 1;

-- 2. Memperbarui stok dan harga produk ID 1 secara spesifik
UPDATE produk
SET stok = 18,
    harga = 78000.00
WHERE id_produk = 1;
```

#### 4. Penjelasan Tiap Baris
- **Baris 4–6:** Memperbarui hanya baris yang memiliki `id_pesanan = 1`. Kolom `status_pesanan` diisi nilai `'Dibayar'` (salah satu pilihan valid dari `ENUM`).
- **Baris 9–12:** Memperbarui dua kolom sekaligus pada tabel `produk` (`stok` dan `harga`) hanya untuk baris dengan `id_produk = 1`. Produk nomor 2 dan 3 tidak terpengaruh sama sekali.

#### 5. Pelacakan Kondisi Data (*State Tracing*)
```text
[Kondisi Awal Produk ID 1]  ──► stok: 20 | harga: 75000.00 | status pesanan 1: 'Menunggu'
                                            │
                                  (Eksekusi UPDATE)
                                            ▼
[Kondisi Akhir Produk ID 1] ──► stok: 18 | harga: 78000.00 | status pesanan 1: 'Dibayar'
```

---

### Contoh 4 *(Pendalaman)*: Menghapus Data Tertarget (`DELETE`) vs Mengosongkan Tabel (`TRUNCATE`)

#### 1. Format Umum yang Dipakai
```sql
DELETE FROM nama_tabel WHERE kolom_kunci = nilai;
TRUNCATE TABLE nama_tabel_tanpa_relasi_fk;
```

#### 2. Kode SQL Implementasi (Eksperimen Aman)
```sql
USE toko_db;

-- 1. Menghapus satu baris detail pesanan (misal item produk 2 dibatalkan oleh pembeli)
DELETE FROM detail_pesanan
WHERE id_pesanan = 1 AND id_produk = 2;

-- 2. Membuat tabel sementara untuk menguji reset nomor AUTO_INCREMENT pada TRUNCATE
CREATE TABLE IF NOT EXISTS tabel_tes_reset (
    id_tes INT AUTO_INCREMENT PRIMARY KEY,
    nama_tes VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- 3. Mengisi 2 baris data uji coba (menghasilkan ID 1 dan ID 2)
INSERT INTO tabel_tes_reset (nama_tes) VALUES ('Percobaan A'), ('Percobaan B');

-- 4. Kosongkan tabel uji coba seketika dengan TRUNCATE
TRUNCATE TABLE tabel_tes_reset;

-- 5. Masukkan 1 baris baru setelah di-TRUNCATE
INSERT INTO tabel_tes_reset (nama_tes) VALUES ('Percobaan C');

-- Cek apakah Percobaan C mendapatkan ID 1 kembali (terbukti reset nomor)
SELECT id_tes, nama_tes FROM tabel_tes_reset;

-- Bersihkan tabel tes setelah praktikum selesai
DROP TABLE IF EXISTS tabel_tes_reset;
```

#### 3. Penjelasan Tiap Baris & Hasil Bukti Reset
- **Baris 4–5 (`DELETE FROM detail_pesanan WHERE ...`):** Menghapus tepat 1 baris rincian barang pada pesanan 1 tanpa menghapus nota induk `pesanan`.
- **Baris 18 (`TRUNCATE TABLE tabel_tes_reset;`):** Menghapus seluruh baris data dan mengatur ulang penghitung `AUTO_INCREMENT` kembali ke titik nol.
- **Baris 21–24:** Ketika `'Percobaan C'` dimasukkan setelah `TRUNCATE`, kolom `id_tes` terbukti kembali mendapatkan nomor **`1`** (jika menggunakan `DELETE`, ID baris baru akan berlanjut ke angka `3`).

---

## 5. Verifikasi Output & Kendala Umum (*Troubleshooting*)

### Tabel Ekspektasi Output Normal

| Perintah yang Dijalankan | Tanda Berhasil di Terminal / Editor SQL |
| :--- | :--- |
| `INSERT INTO ...` (1 baris) | `Query OK, 1 row affected` |
| `INSERT INTO ...` (3 baris) | `Query OK, 3 rows affected` |
| `UPDATE ... WHERE ...` | `Query OK, 1 row affected (Rows matched: 1  Changed: 1)` |
| `DELETE FROM ... WHERE ...` | `Query OK, 1 row affected` |
| `TRUNCATE TABLE ...` | `Query OK, 0 rows affected` |

### Kendala Umum & Cara Mengatasinya

| Pesan Error yang Muncul | Penyebab Umum | Solusi Tepat |
| :--- | :--- | :--- |
| `ERROR 1062 (23000): Duplicate entry '...' for key '...'` | Memasukkan nilai yang sudah pernah ada pada kolom beraturan `PRIMARY KEY` atau `UNIQUE`. | Periksa kembali data; gunakan nilai unik yang belum terdaftar. |
| `ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails` | Mengisi nilai pada kolom `FK` anak yang tidak ada di tabel Induk (misal `id_kategori = 99`). | Pastikan data induk sudah diinput terlebih dahulu sebelum dirujuk oleh tabel anak. |
| `ERROR 1175 (HY000): You are using safe update mode...` | Menjalankan `UPDATE` atau `DELETE` tanpa menyertakan kolom berindeks/`PRIMARY KEY` pada klausa `WHERE`. | Sertakan kolom kunci utama pada `WHERE` (contoh: `WHERE id_produk = 1`), atau nonaktifkan sementara fitur pengaman dengan `SET SQL_SAFE_UPDATES = 0;`. |
| `ERROR 1451 (23000): Cannot delete or update a parent row: a foreign key constraint fails` | Menghapus baris Induk yang masih memiliki baris Anak aktif dan aksi relasinya adalah `RESTRICT`. | Hapus baris di tabel Anak terlebih dahulu, baru hapus baris di tabel Induk. |
| `ERROR 1701 (42000): Cannot truncate a table referenced in a foreign key constraint` | Menjalankan `TRUNCATE TABLE` pada tabel Induk yang sedang dirujuk oleh tabel lain. | Gunakan `DELETE FROM nama_tabel;` jika tabel berelasi, atau hapus tabel anak terlebih dahulu. |

---

## 6. Latihan Soal

*Perhatikan tabel-tabel data acuan berikut sebelum mengerjakan latihan soal:*

#### Tabel: karyawan

| id | nama | jabatan | gaji |
| :---: | :--- | :--- | :---: |
| 1 | Budi | Manager | 100000 |
| 2 | Siti | Staff | 50000 |
| 3 | Andi | Staff | 60000 |
| 4 | Rina | Supervisor | 80000 |
| 5 | Joko | Manager | 120000 |

#### Tabel: produk

| id_produk | nama_produk | harga | stok |
| :---: | :--- | :---: | :---: |
| 1 | Laptop | 1500000 | 5 |
| 2 | Smartphone | 500000 | 10 |
| 3 | Tablet | 800000 | 2 |
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

| id_pelanggan | nama_pelanggan | alamat |
| :---: | :--- | :--- |
| 1 | Ali | Jl. Merdeka 1 |
| 2 | Siti | Jl. Kenanga 2 |
| 3 | Budi | Jl. Melati 3 |
| 4 | Rina | Jl. Mawar 4 |
| 5 | Joko | Jl. Anggrek 5 |

---

#### Daftar Tugas Praktik
1. Tulis perintah SQL untuk menambahkan data karyawan baru dengan `id = 6`, `nama = 'Nina'`, `jabatan = 'Staff'`, dan `gaji = 55000`.
2. Tulis perintah SQL untuk memperbarui gaji karyawan dengan `id = 4` menjadi `85000`.
3. Tulis perintah SQL untuk menghapus data produk dengan `id_produk = 3`.
4. Tulis perintah SQL untuk memperbarui nama pelanggan dengan `id_pelanggan = 4` menjadi `'Nadia'`.
5. Tulis perintah SQL untuk mengupdate stok produk dengan `id_produk = 1` menjadi `10` dan harga menjadi `1400000`.
6. Tulis perintah SQL untuk menambahkan satu produk baru dengan `id_produk = 6`, `nama_produk = 'Mouse Wireless'`, `harga = 150000`, dan `stok = 25`.
7. Tulis perintah SQL untuk memperbarui alamat pelanggan bernama `'Ali'` (`id_pelanggan = 1`) menjadi `'Jl. Sudirman No. 10'`.
8. Tulis perintah SQL untuk menghapus data order yang memiliki `id_order = 5`.
9. Tulis perintah SQL untuk menaikkan stok produk `Keyboard` (`id_produk = 5`) sebanyak 5 unit sehingga menjadi `20`.
10. Tulis perintah SQL untuk mengosongkan seluruh baris data pada tabel uji coba `tabel_riwayat_sementara` seketika serta mereset nomor urut otomatisnya kembali ke angka 1.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil eksekusi kueri latihan soal Anda secara mandiri:*

### Indikator Keberhasilan Tugas (Checklist Hasil Soal 1–10)
- **Soal 1:** Eksekusi berhasil (`Query OK, 1 row affected`), periksa dengan `SELECT * FROM karyawan WHERE id = 6;` untuk memastikan Nina tercatat dengan gaji 55000.
- **Soal 2:** Eksekusi pembaruan berhasil (`Rows matched: 1  Changed: 1`), gaji Rina (`id = 4`) berubah dari 80000 menjadi 85000.
- **Soal 3:** Eksekusi penghapusan berhasil (`Query OK, 1 row affected`), baris produk Tablet (`id_produk = 3`) hilang dari tabel `produk`.
- **Soal 4:** Eksekusi pembaruan berhasil, nama pelanggan pada `id_pelanggan = 4` berubah dari 'Rina' menjadi 'Nadia'.
- **Soal 5:** Dua kolom terbarui sekaligus pada produk Laptop (`id_produk = 1`), stok menjadi 10 dan harga menjadi 1400000.
- **Soal 6:** Produk aksesoris Mouse Wireless berhasil tercatat pada baris ke-6 di tabel `produk`.
- **Soal 7:** Alamat pelanggan Ali (`id_pelanggan = 1`) terbarui menjadi `'Jl. Sudirman No. 10'`.
- **Soal 8:** Baris transaksi dengan `id_order = 5` berhasil terhapus dari tabel `order`.
- **Soal 9:** Stok produk Keyboard (`id_produk = 5`) bertambah atau terbarui menjadi 20 unit.
- **Soal 10:** Seluruh isi tabel `tabel_riwayat_sementara` bersih seketika dan nomor urut `AUTO_INCREMENT` kembali ter-reset ke angka 1.

### Checklist Kesiapan Teknis
- [ ] Setiap perintah `UPDATE` dan `DELETE` selalu menyertakan klausa `WHERE`.
- [ ] Pengisian data anak selalu memastikan data kuncinya sudah tersedia di tabel induk.
- [ ] Memahami perbedaan risiko dan kecepatan antara perintah `DELETE` dan `TRUNCATE TABLE`.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci jawaban kueri SQL lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-05-dml-manipulasi-data-insert-update-delete-vs-truncate).

---

## 8. Ringkasan Perintah & Refleksi Pemahaman

### Ringkasan Cepat Modul 05

| Operasi | Sintaks Baku | Catatan Keamanan |
| :--- | :--- | :--- |
| **Tambah Data** | `INSERT INTO tabel (kolom1, ...) VALUES (nilai1, ...);` | Teks & tanggal wajib pakai petik `'...'`, angka tanpa petik. |
| **Ubah Data** | `UPDATE tabel SET kolom = nilai WHERE kondisi;` | Wajib pakai `WHERE` agar tidak mengubah seluruh tabel. |
| **Hapus Data Baris** | `DELETE FROM tabel WHERE kondisi;` | Menghapus baris tertentu, tidak mereset nilai `AUTO_INCREMENT`. |
| **Kosongkan Tabel** | `TRUNCATE TABLE tabel;` | Reset tabel seketika, mengembalikan `AUTO_INCREMENT` ke angka 1. |

### Refleksi Pemahaman
1. Mengapa saat mengisi basis data toko, kita wajib menjalankan `INSERT INTO kategori` terlebih dahulu sebelum menjalankan `INSERT INTO produk`?
2. Data di dalam tabel kini sudah lengkap terisi dan terawat. Perintah SQL apa yang akan kita pelajari pada modul berikutnya untuk membaca, memilih kolom tertentu, memberi nama alias (*AS*), dan membatasi jumlah data yang tampil di layar? *(Kita akan mempelajarinya pada **Modul 06: Kueri Dasar (`SELECT`), Alias, `DISTINCT`, `ORDER BY`, & `LIMIT`**).*
