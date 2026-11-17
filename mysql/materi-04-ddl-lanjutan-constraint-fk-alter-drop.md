# Modul 04: DDL Lanjutan — Constraint, Relasi `FOREIGN KEY`, `ALTER TABLE`, & `DROP TABLE`

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### Prasyarat Awal
- Anda sudah mampu merancang **Diagram ERD** (`1:N`, pemecahan `M:N`) serta menyusun **Kamus Data** (**Modul 02**).
- Anda sudah mampu membuat tabel dasar beserta `PRIMARY KEY`, `AUTO_INCREMENT`, `NOT NULL`, dan `DEFAULT` menggunakan `CREATE TABLE` (**Modul 03**).

### Jembatan Konsep: Mengunci Aturan Data & Menghubungkan Antar-Tabel
Pada Modul 03, tabel `kategori` dan `produk` masih berdiri sendiri tanpa pagar penjaga. Tanpa aturan tambahan, siapa pun bisa mengisi `harga = -5000`, mendaftarkan dua produk dengan `kode_sku` kembar, atau menulis `id_kategori` yang sebenarnya tidak pernah ada di tabel `kategori`.

Di sinilah perintah **DDL Lanjutan** berperan sebagai penjaga integritas langsung di level mesin basis data:
1. **`UNIQUE`, `CHECK`, & `ENUM`** bertindak sebagai *satpam nilai* pada kolom agar data tidak masuk akal otomatis ditolak.
2. **`FOREIGN KEY`** bertindak sebagai *jembatan fisik* antara tabel **Induk (sisi `1`)** dan tabel **Anak (sisi `N`)** sesuai rancangan ERD Modul 02.
3. **`ALTER TABLE`, `RENAME TABLE`, & `DROP TABLE`** menjadi *perangkat pemeliharaan* ketika rancangan tabel perlu ditambah kolom, diubah ukurannya, atau dibersihkan.

```text
[Rancangan ERD & Kamus Data (Modul 02)]
        │
        ├──► Validasi Nilai Kolom   ──► UNIQUE, CHECK, ENUM
        ├──► Hubungkan Tabel (1:N)  ──► FOREIGN KEY (RESTRICT / CASCADE / SET NULL)
        └──► Ubah & Bersihkan Skema ──► ALTER TABLE, RENAME TABLE, DROP TABLE
```

### Tujuan Pembelajaran
Setelah menyelesaikan Modul 04, Anda diharapkan mampu:
1. Memahami **Format Umum SQL** untuk penambahan *constraint* (`UNIQUE`, `CHECK`, `ENUM`, `FOREIGN KEY`), `ALTER TABLE`, `RENAME TABLE`, dan `DROP TABLE`.
2. Menerjemahkan relasi **`1:N`** dan pemecahan **`M:N`** dari Diagram ERD menjadi kode SQL lengkap beserta aksi `ON DELETE` dan `ON UPDATE`.
3. Mengubah struktur tabel yang sudah terlanjur dibuat menggunakan `ALTER TABLE` serta menghapus tabel berelasi dengan urutan yang benar tanpa memicu error relasi.

---

## 2. Kamus Format Umum SQL, *Constraint*, & Perintah Pemeliharaan

### A. Format Umum *Constraint* Validasi Nilai (`UNIQUE`, `CHECK`, `ENUM`)
Berikut kerangka umum penulisan aturan pembatas nilai di dalam `CREATE TABLE`:

```sql
CREATE TABLE [IF NOT EXISTS] nama_tabel (
    id_kolom INT AUTO_INCREMENT PRIMARY KEY,
    kolom_unik tipe_data NOT NULL,
    kolom_pilihan ENUM('pilihan_1', 'pilihan_2', 'pilihan_3') NOT NULL DEFAULT 'pilihan_1',
    kolom_nilai tipe_data NOT NULL,
    CONSTRAINT nama_unique UNIQUE (kolom_unik),
    CONSTRAINT nama_check CHECK (kondisi_logika)
) ENGINE=InnoDB;
```

**Penjelasan Tiap Bagian:**
- **`CONSTRAINT nama_aturan`**: Memberi nama khusus pada aturan (misal `uq_pelanggan_hp` atau `chk_produk_harga`). Penamaan ini sangat membantu agar aturan mudah dikenali atau dihapus bila suatu saat perlu diubah.
- **`UNIQUE (kolom)`**: Melarang dua baris memiliki nilai yang sama pada kolom tersebut (cocok untuk `kode_sku`, `email`, `nim`, atau `no_hp`).
- **`CHECK (kondisi)`**: Menolak data apabila syarat logika bernilai salah (misal `CHECK (harga > 0)` atau `CHECK (stok >= 0)`). Didukung penuh sejak MySQL 8.0.16+.
- **`ENUM('A', 'B', ...)`**: Tipe data daftar pilihan tertutup. Kolom hanya boleh diisi salah satu dari teks yang tercantum di dalam tanda kurung.

---

### B. Format Umum Relasi `FOREIGN KEY`
Gunakan kerangka umum ini pada **tabel sisi `N` (Tabel Anak)** untuk merujuk ke **`PRIMARY KEY` pada tabel sisi `1` (Tabel Induk)**:

```sql
CREATE TABLE [IF NOT EXISTS] tabel_anak (
    id_anak INT AUTO_INCREMENT PRIMARY KEY,
    id_induk INT NOT NULL,
    ...,
    CONSTRAINT nama_fk
        FOREIGN KEY (id_induk)
        REFERENCES tabel_induk(id_induk)
        ON DELETE [RESTRICT | CASCADE | SET NULL]
        ON UPDATE [RESTRICT | CASCADE | SET NULL]
) ENGINE=InnoDB;
```

**Syarat Wajib & Pilihan Aksi Referensial:**
1. **Tabel Induk harus dibuat lebih dulu** sebelum tabel Anak merujuknya.
2. **Tipe data harus sama persis** antara kolom `FK` di tabel Anak dan kolom `PK` di tabel Induk (misal sama-sama `INT`).
3. **Pilihan aksi saat baris Induk dihapus (`ON DELETE`) atau diubah ID-nya (`ON UPDATE`):**

| Opsi Aksi | Perilaku Saat Data Induk Dihapus / Diubah | Kapan Tepat Dipakai? |
| :--- | :--- | :--- |
| **`RESTRICT`** *(bawaan)* | **Menolak** penghapusan baris Induk selama masih ada baris Anak yang merujuknya. | Melindungi data penting, misalnya mencegah penghapusan `kategori` yang masih memiliki `produk`. |
| **`CASCADE`** | Penghapusan/perubahan pada Induk **otomatis ikut menghapus/mengubah** baris Anak terkait. | Data Anak tidak bermakna tanpa Induk, misalnya rincian `detail_pesanan` ikut terhapus jika nota `pesanan` dihapus. |
| **`SET NULL`** | Saat Induk dihapus, nilai kolom `FK` pada Anak otomatis diubah menjadi kosong (`NULL`). *(Syarat: kolom `FK` wajib boleh `NULL`, bukan `NOT NULL`)*. | Data Anak tetap dipertahankan sebagai riwayat meski referensi Induknya dihapus. |

---

### C. Format Umum Pemeliharaan Skema (`ALTER TABLE`, `RENAME TABLE`, `DROP TABLE`)

| Kebutuhan Perubahan | Format Umum SQL |
| :--- | :--- |
| Menambah kolom baru | `ALTER TABLE nama_tabel ADD COLUMN nama_kolom tipe [opsi] [AFTER kolom_acuan];` |
| Mengubah tipe data / ukuran / *nullability* kolom | `ALTER TABLE nama_tabel MODIFY COLUMN nama_kolom tipe_baru [opsi_baru];` |
| Menghapus kolom | `ALTER TABLE nama_tabel DROP COLUMN nama_kolom;` |
| Menambah aturan `UNIQUE` / `CHECK` | `ALTER TABLE nama_tabel ADD CONSTRAINT nama_aturan UNIQUE (kolom);` atau `CHECK (...);` |
| Menambah `FOREIGN KEY` ke tabel yang sudah ada | `ALTER TABLE tabel_anak ADD CONSTRAINT nama_fk FOREIGN KEY (kolom_fk) REFERENCES tabel_induk(kolom_pk) ON DELETE ... ON UPDATE ...;` |
| Melepas `FOREIGN KEY` / `CHECK` / `UNIQUE` | `ALTER TABLE nama_tabel DROP FOREIGN KEY nama_fk;` (atau `DROP CHECK nama_chk;` / `DROP INDEX nama_uq;`) |
| Mengganti nama tabel | `RENAME TABLE nama_lama TO nama_baru;` |
| Menghapus tabel beserta seluruh isinya | `DROP TABLE [IF EXISTS] nama_tabel;` |

---

## 3. Persiapan Lingkungan & Skema Praktikum

1. Nyalakan **MySQL Server** dan buka editor SQL Anda (**CLI**, **VS Code**, atau tab **SQL** phpMyAdmin).
2. Pastikan Anda sudah membuat tabel `kategori` dan `produk` di `toko_db` pada Modul 03. Jika Anda membuka sesi baru, skrip pada Bagian 4 tetap aman dijalankan karena sudah menyertakan `USE toko_db;` serta klausa `IF NOT EXISTS`.

### Peta Perkembangan Skema Modul 04
- **`toko_db`** *(Studi Kasus Utama)*: Menambah tabel `pelanggan`, `pesanan`, `detail_pesanan`, serta memperbarui `produk` agar seluruh 5 entitas ERD Modul 02 terhubung lengkap.
- **`akademik_db`** *(Latihan A)*: Menambah tabel `dosen`, `mata_kuliah`, `krs_detail`, serta menautkan `FOREIGN KEY` antartabel KRS.
- **`logistik_db`** *(Latihan B)*: Menambah tabel `riwayat_transit` untuk menghubungkan `paket_kiriman` dan `gudang` (`M:N`) beserta aturan `CHECK`.

---

## 4. Contoh Praktik Bertingkat — Studi Kasus `toko_db`

Setiap contoh di bawah mengikuti alur tetap: **(1) Format Umum SQL → (2) Diagram Skema & Kamus Data → (3) Kode SQL → (4) Penjelasan Tiap Baris → (5) Hasil Keluaran**.

### Contoh 1 (Level Dasar): Membuat `pelanggan` & `pesanan` dengan `UNIQUE`, `ENUM`, dan Relasi `1:N`

#### 1. Format Umum yang Dipakai
```sql
CREATE TABLE [IF NOT EXISTS] tabel_induk (
    id_induk INT AUTO_INCREMENT PRIMARY KEY,
    kolom_unik VARCHAR(n) NOT NULL,
    CONSTRAINT nama_uq UNIQUE (kolom_unik)
) ENGINE=InnoDB;

CREATE TABLE [IF NOT EXISTS] tabel_anak (
    id_anak INT AUTO_INCREMENT PRIMARY KEY,
    id_induk INT NOT NULL,
    kolom_status ENUM('A', 'B') NOT NULL DEFAULT 'A',
    CONSTRAINT nama_fk FOREIGN KEY (id_induk) REFERENCES tabel_induk(id_induk)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
```

#### 2. Diagram Skema & Kamus Data (`pelanggan` 1 ──< N `pesanan`)
```text
+-----------------------+           +----------------------------+
|       pelanggan       |           |          pesanan           |
+-----------------------+ 1       N +----------------------------+
| PK id_pelanggan       |----------<| PK id_pesanan              |
|    nama_pelanggan     |           | FK id_pelanggan            |
| UQ no_hp              |           |    tgl_pesan               |
+-----------------------+           |    status_pesanan (ENUM)   |
                                    +----------------------------+
```

| Tabel | Nama Kolom | Tipe Data & Panjang | Aturan / Constraint | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `pelanggan` | `id_pelanggan` | `INT` | `AUTO_INCREMENT`, `PRIMARY KEY` | Identitas unik pelanggan. |
| `pelanggan` | `nama_pelanggan` | `VARCHAR(100)` | `NOT NULL` | Nama lengkap pelanggan. |
| `pelanggan` | `no_hp` | `VARCHAR(20)` | `NOT NULL`, `UNIQUE (uq_pelanggan_hp)` | Nomor telepon tidak boleh kembar antarpelanggan. |
| `pesanan` | `id_pesanan` | `INT` | `AUTO_INCREMENT`, `PRIMARY KEY` | Identitas unik transaksi pesanan. |
| `pesanan` | `id_pelanggan` | `INT` | `NOT NULL`, `FK` → `pelanggan(id_pelanggan)` | Pemilik pesanan (`ON DELETE RESTRICT ON UPDATE CASCADE`). |
| `pesanan` | `tgl_pesan` | `DATETIME` | `NOT NULL DEFAULT CURRENT_TIMESTAMP` | Waktu pencatatan pesanan. |
| `pesanan` | `status_pesanan` | `ENUM(...)` | `NOT NULL DEFAULT 'Menunggu'` | Pilihan status: `'Menunggu'`, `'Dibayar'`, `'Dikirim'`, `'Selesai'`, `'Dibatalkan'`. |

#### 3. Kode SQL Implementasi
```sql
USE toko_db;

CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    CONSTRAINT uq_pelanggan_hp UNIQUE (no_hp)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    tgl_pesan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status_pesanan ENUM('Menunggu', 'Dibayar', 'Dikirim', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Menunggu',
    CONSTRAINT fk_pesanan_pelanggan
        FOREIGN KEY (id_pelanggan)
        REFERENCES pelanggan(id_pelanggan)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;
```

#### 4. Penjelasan Tiap Baris
- **Baris 1 (`USE toko_db;`):** Mengaktifkan database `toko_db`.
- **Baris 3–8 (`CREATE TABLE IF NOT EXISTS pelanggan ...`):**
  - Baris 4–6 mendefinisikan `id_pelanggan`, `nama_pelanggan`, dan `no_hp`.
  - Baris 7 (`CONSTRAINT uq_pelanggan_hp UNIQUE (no_hp)`): Memasang aturan `UNIQUE` bernama `uq_pelanggan_hp` agar satu nomor HP hanya terdaftar satu kali.
- **Baris 10–20 (`CREATE TABLE IF NOT EXISTS pesanan ...`):**
  - Baris 11–13 mendefinisikan PK `id_pesanan`, kolom jembatan `id_pelanggan` (bertipe `INT` sama persis dengan tabel induk), dan `tgl_pesan`.
  - Baris 14 (`status_pesanan ENUM(...) ... DEFAULT 'Menunggu'`): Membatasi isi status hanya pada lima kata baku dan mengisi `'Menunggu'` secara otomatis bila status belum disebut.
  - Baris 15–19 (`CONSTRAINT fk_pesanan_pelanggan ...`): Menghubungkan `id_pelanggan` di `pesanan` ke `pelanggan(id_pelanggan)`. `ON DELETE RESTRICT` mencegah penghapusan pelanggan yang sudah punya riwayat pesanan; `ON UPDATE CASCADE` ikut memperbarui referensi bila ID pelanggan berubah.

#### 5. Hasil Keluaran
```text
Query OK, 0 rows affected
Query OK, 0 rows affected
```

---

### Contoh 2 (Level Menengah): Memecah Relasi `M:N` Melalui Tabel `detail_pesanan` + `CHECK`

#### 1. Format Umum yang Dipakai
```sql
CREATE TABLE [IF NOT EXISTS] tabel_penghubung (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    fk_tabel_a INT NOT NULL,
    fk_tabel_b INT NOT NULL,
    kolom_angka INT NOT NULL,
    CONSTRAINT nama_uq_gabungan UNIQUE (fk_tabel_a, fk_tabel_b),
    CONSTRAINT nama_chk CHECK (kondisi),
    CONSTRAINT nama_fk_a FOREIGN KEY (fk_tabel_a) REFERENCES tabel_a(id_a) ON DELETE CASCADE,
    CONSTRAINT nama_fk_b FOREIGN KEY (fk_tabel_b) REFERENCES tabel_b(id_b) ON DELETE RESTRICT
) ENGINE=InnoDB;
```

#### 2. Diagram Skema & Kamus Data (`detail_pesanan`)
```text
+-------------------+ 1      N +---------------------+ N      1 +-------------------+
|      pesanan      |---------<|   detail_pesanan    |>---------|      produk       |
+-------------------+          +---------------------+          +-------------------+
| PK id_pesanan     |          | PK id_detail        |          | PK id_produk      |
+-------------------+          | FK id_pesanan       |          +-------------------+
                               | FK id_produk        |
                               |    jumlah (CHK > 0) |
                               |    harga_saat_beli  |
                               +---------------------+
```

| Nama Kolom | Tipe Data & Panjang | Aturan / Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id_detail` | `INT` | `AUTO_INCREMENT`, `PRIMARY KEY` | Identitas unik baris rincian pesanan. |
| `id_pesanan` | `INT` | `NOT NULL`, `FK` → `pesanan(id_pesanan)` | Merujuk nota induk (`ON DELETE CASCADE`). |
| `id_produk` | `INT` | `NOT NULL`, `FK` → `produk(id_produk)` | Merujuk barang yang dibeli (`ON DELETE RESTRICT`). |
| `jumlah` | `INT` | `NOT NULL`, `CHECK (jumlah > 0)` | Banyak unit dibeli, harus lebih dari 0. |
| `harga_saat_beli` | `DECIMAL(12, 2)` | `NOT NULL`, `CHECK (harga_saat_beli >= 0)` | Harga satuan saat transaksi terjadi. |
| *(Kombinasi)* | `(id_pesanan, id_produk)` | `UNIQUE (uq_detail_pesanan_produk)` | Mencegah produk yang sama dicatat ganda dalam satu nota yang sama. |

#### 3. Kode SQL Implementasi
```sql
USE toko_db;

CREATE TABLE IF NOT EXISTS detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL,
    harga_saat_beli DECIMAL(12, 2) NOT NULL,
    CONSTRAINT uq_detail_pesanan_produk UNIQUE (id_pesanan, id_produk),
    CONSTRAINT chk_detail_jumlah CHECK (jumlah > 0),
    CONSTRAINT chk_detail_harga CHECK (harga_saat_beli >= 0),
    CONSTRAINT fk_detail_pesanan
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detail_produk
        FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
```

#### 4. Penjelasan Tiap Baris
- **Baris 1 (`USE toko_db;`):** Memastikan tabel dibuat di `toko_db`.
- **Baris 3–8:** Mendefinisikan kolom inti `id_detail`, dua kolom penghubung (`id_pesanan` dan `id_produk`), `jumlah`, serta `harga_saat_beli`.
- **Baris 9 (`CONSTRAINT uq_detail_pesanan_produk UNIQUE (id_pesanan, id_produk),`):** *Composite Unique* — boleh membeli produk A di nota berbeda, atau produk A & B di nota yang sama, tetapi melarang produk A ditulis dua kali terpisah dalam nota yang sama.
- **Baris 10–11:** Memasang dua aturan `CHECK` agar jumlah barang minimal `1` (`> 0`) dan harga beli tidak pernah bernilai negatif.
- **Baris 12–14 (`fk_detail_pesanan`):** Menghubungkan ke `pesanan` dengan `ON DELETE CASCADE` (jika nota pesanan dihapus, seluruh rincian itemnya otomatis ikut terbersihkan).
- **Baris 15–17 (`fk_detail_produk`):** Menghubungkan ke `produk` dengan `ON DELETE RESTRICT` (produk yang sudah pernah muncul di nota transaksi tidak boleh dihapus sembarangan).

#### 5. Hasil Keluaran
```text
Query OK, 0 rows affected
```

---

### Contoh 3 (Level Lanjut): Memperbarui Struktur Tabel `produk` dengan `ALTER TABLE`

Pada Modul 03, tabel `produk` sudah dibuat tanpa kolom `id_kategori`, tanpa aturan `UNIQUE (kode_sku)`, dan tanpa validasi `CHECK` harga/stok. Kini kita melengkapinya memakai `ALTER TABLE` tanpa perlu menghapus tabel yang sudah ada.

#### 1. Format Umum yang Dipakai
```sql
ALTER TABLE nama_tabel
    ADD COLUMN kolom_baru tipe [opsi] AFTER kolom_acuan,
    MODIFY COLUMN kolom_lama tipe_baru [opsi_baru],
    ADD CONSTRAINT nama_uq UNIQUE (kolom),
    ADD CONSTRAINT nama_chk CHECK (kondisi),
    ADD CONSTRAINT nama_fk FOREIGN KEY (kolom_fk) REFERENCES tabel_induk(kolom_pk)
        ON DELETE RESTRICT ON UPDATE CASCADE;
```

#### 2. Diagram Perubahan Struktur `produk`
```text
Tabel produk (Modul 03)                  Tabel produk (Setelah ALTER TABLE Modul 04)
───────────────────────                  ─────────────────────────────────────────
id_produk                                id_produk (PK)
kode_sku                                 id_kategori (FK → kategori.id_kategori)  [BARU]
nama_produk (120)          ──────►       kode_sku (UNIQUE)                          [ATURAN BARU]
harga                                    nama_produk (150)                          [DIPERBESAR]
stok                                     harga (CHECK > 0)                          [ATURAN BARU]
tanggal_kadaluwarsa                      stok (CHECK >= 0)                          [ATURAN BARU]
dibuat_pada                              tanggal_kadaluwarsa, dibuat_pada
```

#### 3. Kode SQL Implementasi
```sql
USE toko_db;

ALTER TABLE produk
    ADD COLUMN id_kategori INT NOT NULL AFTER id_produk,
    MODIFY COLUMN nama_produk VARCHAR(150) NOT NULL,
    ADD CONSTRAINT uq_produk_sku UNIQUE (kode_sku),
    ADD CONSTRAINT chk_produk_harga CHECK (harga > 0),
    ADD CONSTRAINT chk_produk_stok CHECK (stok >= 0),
    ADD CONSTRAINT fk_produk_kategori
        FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
        ON DELETE RESTRICT ON UPDATE CASCADE;

DESCRIBE produk;
```

#### 4. Penjelasan Tiap Baris
- **Baris 1 (`USE toko_db;`):** Mengaktifkan `toko_db`.
- **Baris 3 (`ALTER TABLE produk`):** Membuka perintah modifikasi struktur tabel `produk`.
- **Baris 4 (`ADD COLUMN id_kategori INT NOT NULL AFTER id_produk,`):** Menambahkan kolom penghubung `id_kategori` (bertipe `INT` sama dengan `kategori.id_kategori`) tepat setelah kolom `id_produk`.
- **Baris 5 (`MODIFY COLUMN nama_produk VARCHAR(150) NOT NULL,`):** Memperbesar kapasitas teks `nama_produk` dari `120` menjadi `150` karakter tanpa mengubah tipe dasarnya.
- **Baris 6–8:** Menambahkan aturan `UNIQUE` pada `kode_sku` dan dua aturan `CHECK` untuk menjaga `harga` tetap positif dan `stok` tidak minus.
- **Baris 9–11 (`ADD CONSTRAINT fk_produk_kategori ...`):** Mengaktifkan relasi `1:N` antara `kategori` (`1`) dan `produk` (`N`).
- **Baris 13 (`DESCRIBE produk;`):** Memverifikasi bahwa kolom `id_kategori` sudah muncul dan `nama_produk` berubah menjadi `varchar(150)`.

#### 5. Hasil Keluaran & Pelacakan Keadaan (`DESCRIBE produk;`)
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id_produk` | `int` | `NO` | `PRI` | `NULL` | `auto_increment` |
| `id_kategori` | `int` | `NO` | `MUL` | `NULL` | |
| `kode_sku` | `char(8)` | `NO` | `UNI` | `NULL` | |
| `nama_produk` | `varchar(150)` | `NO` | | `NULL` | |
| `harga` | `decimal(12,2)` | `NO` | | `NULL` | |
| `stok` | `int` | `NO` | | `0` | |
| ... | ... | ... | ... | ... | ... |

*(Perhatikan munculnya penanda baru di kolom **`Key`**: `MUL` menandakan kolom `FOREIGN KEY` dan `UNI` menandakan kolom `UNIQUE`.)*

---

### Contoh 4 *(Pendalaman)*: Mengubah Nama & Urutan Aman Menghapus Tabel (`RENAME` & `DROP TABLE`)

#### 1. Format Umum yang Dipakai
```sql
RENAME TABLE nama_lama TO nama_baru;
ALTER TABLE tabel_anak DROP FOREIGN KEY nama_fk;
ALTER TABLE nama_tabel DROP COLUMN nama_kolom;
DROP TABLE [IF EXISTS] nama_tabel;
```

#### 2. Kode SQL Implementasi (Menggunakan Tabel Uji Coba)
```sql
USE toko_db;

-- 1. Buat tabel sementara berelasi untuk latihan RENAME, DROP COLUMN, & DROP TABLE
CREATE TABLE IF NOT EXISTS log_sementara (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    catatan_lama VARCHAR(100),
    CONSTRAINT fk_log_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
) ENGINE=InnoDB;

-- 2. Hapus kolom catatan_lama dan ganti nama tabel
ALTER TABLE log_sementara DROP COLUMN catatan_lama;
RENAME TABLE log_sementara TO arsip_uji;

-- 3. Lepas relasi FK terlebih dahulu (opsional bila ingin menguji pelepasannya), lalu hapus tabel uji
ALTER TABLE arsip_uji DROP FOREIGN KEY fk_log_kategori;
DROP TABLE IF EXISTS arsip_uji;

SHOW TABLES;
```

#### 3. Penjelasan Tiap Baris
- **Baris 4–9:** Membuat tabel sementara `log_sementara` yang terhubung ke `kategori`.
- **Baris 12 (`ALTER TABLE ... DROP COLUMN`):** Menghapus kolom `catatan_lama` yang tidak lagi dibutuhkan.
- **Baris 13 (`RENAME TABLE ... TO ...`):** Mengubah nama tabel `log_sementara` menjadi `arsip_uji`.
- **Baris 16 (`DROP FOREIGN KEY`):** Memutus tautan relasi menggunakan nama constraint `fk_log_kategori` (jika lupa nama FK-nya, cek dulu lewat `SHOW CREATE TABLE nama_tabel;`).
- **Baris 17 (`DROP TABLE IF EXISTS arsip_uji;`):** Menghapus tabel uji coba secara permanen dengan aman, sambil membiarkan 5 tabel utama `toko_db` tetap utuh.

> **Aturan Urutan `DROP TABLE` pada Tabel Berelasi:** Jika Anda perlu menghapus tabel Induk dan tabel Anak sekaligus, Anda **wajib menghapus tabel Anak terlebih dahulu** (misal `detail_pesanan` → `pesanan` → `pelanggan`), baru kemudian tabel Induk. Menghapus tabel Induk saat masih dirujuk oleh `FOREIGN KEY` akan ditolak MySQL.

---

## 5. Verifikasi Output & Kendala Umum (*Troubleshooting*)

### Tabel Ekspektasi Output Normal

| Perintah yang Dijalankan | Tanda Berhasil |
| :--- | :--- |
| `SHOW TABLES;` pada `toko_db` | Tepat 5 tabel utama tampil: `detail_pesanan`, `kategori`, `pelanggan`, `pesanan`, `produk`. |
| `DESCRIBE produk;` | Kolom `id_kategori` berstatus `Key = MUL` dan `kode_sku` berstatus `Key = UNI`. |
| `SHOW CREATE TABLE detail_pesanan;` | Menampilkan definisi lengkap kedua `FOREIGN KEY`, kedua `CHECK`, dan `UNIQUE`. |

### Kendala Umum & Cara Mengatasinya

| Pesan Error yang Muncul | Penyebab Umum | Cara Mengatasinya |
| :--- | :--- | :--- |
| `ERROR 1824 (HY000): Failed to open the referenced table '...'` | Tabel Induk yang dirujuk pada `REFERENCES` belum dibuat atau salah ketik namanya. | Buat tabel Induk lebih dulu dan periksa kesamaan ejaan nama tabel. |
| `ERROR 3780 (HY000): Referencing column ... and referenced column ... are incompatible` | Tipe data atau atribut `UNSIGNED` antara kolom `FK` dan kolom `PK` induk tidak sama persis. | Samakan tipe data keduanya secara persis (misal sama-sama `INT` atau `CHAR(8)`). |
| `ERROR 1830 (HY000): Column '...' cannot be NOT NULL: needed in a foreign key constraint SET NULL` | Memasang `ON DELETE SET NULL` pada kolom `FK` yang diberi aturan `NOT NULL`. | Ubah kolom `FK` menjadi boleh `NULL`, atau ganti aksi menjadi `RESTRICT` / `CASCADE`. |
| `ERROR 3730 (HY000): Cannot drop table '...' referenced by a foreign key constraint` | Mencoba menjalankan `DROP TABLE` pada tabel Induk sebelum tabel Anak yang merujuknya dihapus. | Hapus tabel Anak terlebih dahulu (atau lepas FK-nya dengan `ALTER TABLE ... DROP FOREIGN KEY`), baru hapus tabel Induk. |

---

## 6. Latihan Soal

*Perhatikan tabel-tabel data acuan dan spesifikasi relasi berikut sebelum mengerjakan latihan soal:*

#### Tabel: dosen (Data Acuan `akademik_db`)

| id_dosen | nidn | nama_dosen | email |
| :---: | :---: | :--- | :--- |
| 1 | 0012057801 | Dr. Hasan Basri, M.Kom. | hasan@kampus.ac.id |
| 2 | 0023088202 | Rina Wulandari, M.T. | rina@kampus.ac.id |
| 3 | 0015118503 | Ahmad Zaki, M.Cs. | zaki@kampus.ac.id |

#### Tabel: mata_kuliah (Data Acuan `akademik_db`)

| id_matkul | kode_mk | nama_mk | sks | id_dosen (FK) |
| :---: | :---: | :--- | :---: | :---: |
| 1 | IF101 | Basis Data Relasional | 3 | 1 |
| 2 | IF102 | Algoritma & Pemrograman | 3 | 2 |
| 3 | IF201 | Desain Arsitektur Database | 3 | 1 |
| 4 | SI101 | Pengantar Sistem Informasi | 2 | 3 |

#### Tabel: krs_detail (Data Acuan Penghubung M:N `akademik_db`)

| id_krs | id_mahasiswa (FK) | id_matkul (FK) | semester | status_ambil |
| :---: | :---: | :---: | :---: | :---: |
| 1 | 1 | 1 | 2026-1 | Aktif |
| 2 | 1 | 2 | 2026-1 | Aktif |
| 3 | 2 | 1 | 2026-1 | Aktif |
| 4 | 3 | 4 | 2026-1 | Batal |

#### Tabel: riwayat_transit (Data Acuan Penghubung M:N `logistik_db`)

| id_transit | id_paket (FK) | id_gudang (FK) | status_transit | waktu_tiba |
| :---: | :---: | :---: | :--- | :--- |
| 1 | 1 | 1 | Masuk Gudang | 2026-11-01 09:15:00 |
| 2 | 1 | 2 | Keluar Menuju Kota Berikutnya | 2026-11-01 16:40:00 |
| 3 | 2 | 2 | Dalam Pemeriksaan | 2026-11-02 11:20:00 |

---

#### Daftar Tugas Praktik
1. Tulis perintah SQL untuk menambahkan kolom baru `email VARCHAR(100)` pada tabel `pelanggan` di `toko_db` tepat setelah kolom `no_hp`.
2. Tulis perintah SQL untuk menambahkan aturan `UNIQUE` bernama `uq_pelanggan_email` pada kolom `email` di tabel `pelanggan`.
3. Tulis perintah SQL untuk menambahkan kolom `id_prodi INT NOT NULL` ke dalam tabel `mahasiswa` di `akademik_db`, lalu hubungkan sebagai `FOREIGN KEY` ke tabel `program_studi(id_prodi)` dengan aturan `ON DELETE RESTRICT ON UPDATE CASCADE`.
4. Tulis perintah SQL untuk membuat tabel `dosen` pada `akademik_db` dengan kolom `id_dosen` (PK, AI), `nidn` (CHAR 10, NOT NULL, UNIQUE), dan `nama_dosen` (VARCHAR 100, NOT NULL).
5. Tulis perintah SQL untuk membuat tabel `mata_kuliah` pada `akademik_db` dengan kolom `id_matkul` (PK, AI), `kode_mk` (CHAR 6, NOT NULL, UNIQUE), `nama_mk` (VARCHAR 100, NOT NULL), `sks` (INT NOT NULL, dengan validasi `CHECK (sks >= 1 AND sks <= 6)`), dan `id_dosen` (INT NOT NULL sebagai `FOREIGN KEY` merujuk `dosen(id_dosen)`).
6. Tulis perintah SQL untuk membuat tabel penghubung `krs_detail` pada `akademik_db` yang memecah relasi Many-to-Many antara `mahasiswa` dan `mata_kuliah` dengan aturan kombinasi unik `UNIQUE (id_mahasiswa, id_matkul, semester)`.
7. Tulis perintah SQL untuk menambahkan validasi nilai `CHECK (berat_kg > 0)` dan `CHECK (ongkos_kirim >= 0)` pada tabel `paket_kiriman` di `logistik_db`.
8. Tulis perintah SQL untuk membuat tabel penghubung `riwayat_transit` pada `logistik_db` yang menghubungkan `paket_kiriman` (`ON DELETE CASCADE`) dan `gudang` (`ON DELETE RESTRICT`) beserta pilihan status pengiriman menggunakan `ENUM`.
9. Tulis perintah SQL untuk mengubah dan memperbesar ukuran kolom `nama_lengkap` pada tabel `mahasiswa` menjadi `VARCHAR(150) NOT NULL` menggunakan perintah pemeliharaan kolom.
10. Tulis perintah SQL untuk menghapus tabel uji coba berelasi secara aman tanpa memicu kesalahan referensi `FOREIGN KEY`.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil eksekusi kueri latihan soal Anda secara mandiri:*

### Indikator Keberhasilan Tugas (Checklist Hasil Soal 1–10)
- **No. 1–2:** Kolom `email` tampil di `DESCRIBE pelanggan;` dengan atribut `Key = UNI`.
- **No. 3:** Kolom `id_prodi` muncul di `mahasiswa` dengan `Key = MUL` menandakan `FOREIGN KEY` aktif.
- **No. 4–5:** Tabel `dosen` dan `mata_kuliah` berhasil dibuat; `mata_kuliah` memiliki referensi FK ke `dosen`.
- **No. 6:** Tabel `krs_detail` memiliki 2 kolom `FOREIGN KEY` (`id_mahasiswa`, `id_matkul`) dan *composite unique* pada tiga kolom.
- **No. 7–8:** Tabel `riwayat_transit` berhasil dibuat dan kedua aturan `CHECK` pada `paket_kiriman` tercatat di `SHOW CREATE TABLE`.
- **No. 9:** Kolom `nama_lengkap` pada `mahasiswa` berubah menjadi `varchar(150)` saat dicek dengan `DESCRIBE`.
- **No. 10:** Tabel uji coba anak terhapus terlebih dahulu sebelum tabel induk, atau relasi FK diputus sebelum eksekusi `DROP TABLE`.

### Checklist Kesiapan Teknis
- [ ] `SHOW TABLES;` di `toko_db` menampilkan 5 tabel: `kategori`, `produk`, `pelanggan`, `pesanan`, dan `detail_pesanan`.
- [ ] `SHOW TABLES;` di `akademik_db` menampilkan 5 tabel: `program_studi`, `mahasiswa`, `dosen`, `mata_kuliah`, dan `krs_detail`.
- [ ] `SHOW TABLES;` di `logistik_db` menampilkan 3 tabel: `gudang`, `paket_kiriman`, dan `riwayat_transit`.
- [ ] Seluruh aturan `CHECK`, `UNIQUE`, `ENUM`, dan `FOREIGN KEY` berhasil melindungi integritas data.

> [!NOTE]
> **Akses Kunci Jawaban Lengkap:**  
> Kunci jawaban kueri DDL lanjutan lengkap dan pembahasan teknis analitis untuk setiap butir soal dihimpun secara terpusat pada dokumen pegangan instruktur: [KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md](file:///e:/My%20Drive/NITIP/be_good_teacher/mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md#modul-04-ddl-lanjutan-constraint-relasi-foreign-key-alter-table--drop-table).

---

## 8. Ringkasan Perintah & Refleksi Pemahaman

### Ringkasan Cepat Modul 04

| Tujuan | Format Umum / Sintaks Inti |
| :--- | :--- |
| Mencegah nilai kembar & membatasi nilai | `CONSTRAINT nama UNIQUE (kolom)` / `CHECK (kondisi)` / `ENUM(...)` |
| Menghubungkan tabel Anak (`N`) ke Induk (`1`) | `CONSTRAINT nama FOREIGN KEY (fk) REFERENCES induk(pk) ON DELETE ... ON UPDATE ...` |
| Menambah / mengubah / menghapus kolom | `ALTER TABLE tabel ADD / MODIFY / DROP COLUMN ...;` |
| Menambah aturan ke tabel yang sudah ada | `ALTER TABLE tabel ADD CONSTRAINT nama UNIQUE / CHECK / FOREIGN KEY ...;` |
| Mengganti nama & menghapus tabel aman | `RENAME TABLE lama TO baru;` dan `DROP TABLE IF EXISTS tabel;` |

### Refleksi Pemahaman
1. Mengapa dalam relasi `pesanan` → `detail_pesanan` kita memilih `ON DELETE CASCADE`, sedangkan pada `kategori` → `produk` kita memilih `ON DELETE RESTRICT`?
2. Seluruh struktur tabel pada `toko_db`, `akademik_db`, dan `logistik_db` kini sudah siap dan saling terhubung, namun isinya masih kosong (0 baris). Perintah SQL apa yang akan kita gunakan untuk mengisi, memperbarui, dan menghapus baris data di dalam tabel-tabel tersebut? *(Kita akan mempelajarinya pada **Modul 05: DML — Manipulasi Data (`INSERT`, `UPDATE`, `DELETE`)**).*
