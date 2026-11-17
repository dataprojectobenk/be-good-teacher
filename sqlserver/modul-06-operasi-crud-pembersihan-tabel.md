# MODUL 06: OPERASI CRUD FUNDAMENTAL & PEMBERSIHAN TABEL (`INSERT`, `SELECT`, `UPDATE`, `DELETE` vs `TRUNCATE`)
- **Subjek / Mata Kuliah:** Basis Data / Administrasi Microsoft SQL Server (2019 / 2022)
- **Tahap Kurikulum:** Level 3 — DML Fundamental
- **Target Peserta:** Terpadu Bertingkat (SMK & Mahasiswa)
- **Fokus Materi:** Mengisi data tunggal, multi-baris, dan lintas tabel (`INSERT INTO ... VALUES` & `INSERT INTO ... SELECT`), membaca kolom secara eksplisit (`SELECT`), memperbarui baris secara presisi (`UPDATE ... WHERE`), serta membedakan mekanisme `DELETE` vs `TRUNCATE TABLE` terhadap `.ldf` dan `IDENTITY`.
- **Studi Kasus Berkelanjutan:** `TokoRitelDB`, `AkademikDB`, `LogistikAuditDB`

---

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### A. Pengingat Prasyarat Cepat (2 Menit)
Pada **Level 2 (Modul 03–05)**, Anda telah membangun struktur fisik database beserta tabel dan aturan integritasnya (*DDL & Constraints*). Sekarang saatnya mengisi dan mengelola data di dalamnya:
- **Konsep yang Harus Sudah Dipahami:** Fungsi `IDENTITY(1,1)`, `PRIMARY KEY`, `FOREIGN KEY`, `CHECK` constraint, dan `DEFAULT` constraint.
- **Cek Kilat Prasyarat (Jawab Singkat Sebelum Lanjut):**
  1. Jika kolom `ProductID` bertipe `INT IDENTITY(1,1)`, apakah kita boleh atau perlu menuliskan angkanya secara manual saat menambah baris normal? *(Jawab singkat: `Tidak perlu; SQL Server otomatis menghasilkan nomor urut 1, 2, 3, ... secara internal.`)*
  2. Apa yang terjadi jika kita mencoba memasukkan `StockQuantity = -5` ke kolom yang memiliki `CHECK (StockQuantity >= 0)`? *(Jawab singkat: `SQL Server menolak perintah tersebut dengan error pelanggaran CHECK constraint.`)*

### B. Jembatan Konsep: Mengapa Ada `DELETE` Sekaligus `TRUNCATE TABLE`?
Bayangkan Anda telah membangun rak-rak gudang yang kokoh beserta label aturan pada setiap kotaknya (*DDL Level 2*). Rak yang kosong belum berguna sebelum barang dimasukkan (**Create / `INSERT`**), diperiksa daftarnya (**Read / `SELECT`**), diubah label harga/stoknya (**Update / `UPDATE`**), atau dikeluarkan saat rusak/kedaluwarsa (**Delete / `DELETE`**). Keempat operasi ini dikenal sebagai **CRUD**.

Di balik layar **Storage Engine** SQL Server (ingat konsep *Data Pages 8 KB* dan *Extents 64 KB* dari Modul 03), menghapus seluruh isi tabel ternyata punya dua cara kerja yang sangat berbeda:
1. **`DELETE FROM Tabel` (Tanpa/Dengan `WHERE`):** SQL Server berjalan **baris demi baris**, menghapus satu per satu entri data, dan mencatat **setiap baris yang dihapus** ke dalam file **Transaction Log (`.ldf`)**. Jika ada 1 juta baris, 1 juta catatan log ditulis! Selain itu, penghitung `IDENTITY` **tetap mengingat angka terakhir** (tidak kembali ke `1`).
2. **`TRUNCATE TABLE Tabel`:** SQL Server **tidak** menghapus baris satu per satu, melainkan langsung melepas kepemilikan halaman data (*page/extent deallocation*) milik tabel tersebut. Catatan ke `.ldf` sangat minimal, eksekusinya sangat cepat bahkan untuk jutaan baris, dan penghitung `IDENTITY` otomatis **direset kembali ke nilai awal (*seed*)**. Karena cara kerjanya yang ekstrem, `TRUNCATE TABLE` tidak bisa memakai klausa `WHERE` dan ditolak jika tabel tersebut sedang dirujuk oleh `FOREIGN KEY` tabel lain.

### C. Tujuan Pembelajaran & Peta Alur Kerja
Setelah menyelesaikan modul ini, peserta didik mampu:
1. Menuliskan skrip `INSERT` (tunggal, multi-baris, dan `INSERT ... SELECT`), `SELECT` kolom eksplisit, `UPDATE ... WHERE`, `DELETE ... WHERE`, serta `TRUNCATE TABLE` dengan tepat.
2. Melacak perubahan nilai baris dan nomor `IDENTITY` (*State Tracing*) pada setiap operasi CRUD tanpa melupakan klausa `WHERE`.
3. Memilih penggunaan `DELETE` vs `TRUNCATE TABLE` secara tepat dengan mempertimbangkan beban file `.ldf`, reset `IDENTITY`, serta batasan `FOREIGN KEY`.

**Peta Alur Kerja (*CRUD & Cleanup Execution Flow*):**
```text
[Tabel Kosong / Terisi]
        │
        ├──► INSERT INTO ... VALUES / SELECT ──► [Cek Constraints & Tambah Baris + Naikkan IDENTITY]
        │
        ├──► UPDATE ... SET ... WHERE        ──► [Saring Baris Target] ──► [Validasi CHECK/FK] ──► [Ubah Nilai]
        │
        └──► Pembersihan Data:
               ├── Butuh hapus baris tertentu / terikat FK? ──► DELETE FROM ... WHERE (Log per baris, IDENTITY tetap)
               └── Kosongkan seluruh tabel staging bebas FK? ──► TRUNCATE TABLE (Deallocation cepat, IDENTITY reset)
```

---

## 2. Kamus Perintah & Klausa DML T-SQL

Pada bagian ini, Anda akan mempelajari rincian sintaks, parameter wajib maupun opsional, serta contoh singkat setiap perintah DML sebelum menerapkannya pada studi kasus.

### A. `INSERT INTO ... VALUES`
- **Kegunaan Utama:** Menyisipkan satu baris data baru atau beberapa baris sekaligus ke dalam sebuah tabel.
- **Sintaks Baku:**
  ```sql
  INSERT INTO dbo.NamaTabel (Kolom1, Kolom2, Kolom3)
  VALUES
      (Nilai1A, Nilai2A, Nilai3A),
      (Nilai1B, Nilai2B, Nilai3B);
  ```
- **Rincian Parameter / Klausa:**

  | Bagian Sintaks | Status | Format / Aturan | Penjelasan & Contoh Nilai Valid |
  | :--- | :---: | :--- | :--- |
  | `dbo.NamaTabel` | **Wajib** | Schema + Nama Tabel | Target tabel penerima data, misal `dbo.Products`. |
  | `(Kolom1, Kolom2, ...)` | *Sangat Dianjurkan* | Daftar kolom eksplisit | Menyebutkan nama kolom secara jelas agar urutan input tidak bergantung pada posisi fisik kolom. Kolom `IDENTITY` tidak ditulis; kolom ber-`DEFAULT` boleh dilewati. |
  | `VALUES (...)` | **Wajib** | Daftar nilai literal | Jumlah dan urutan tipe data nilai **harus sama persis** dengan daftar kolom di atasnya. Teks Unicode (`NVARCHAR`) diawali `N'...'`. |

- **Nilai Kembalian / Efek Eksekusi:** Mengembalikan pesan SSMS `(N rows affected)`. Gagal seluruhnya jika salah satu baris melanggar `PRIMARY KEY`, `UNIQUE`, `CHECK`, atau `NOT NULL`.
- **Contoh Mikro (1–3 Baris Mandiri):**
  ```sql
  INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
  VALUES ('BRG-901', N'Gula Pasir 1 Kg', 17500.00, 40);
  ```
  *Hasil eksekusi:* `(1 row affected)` dan kolom `ProductID` otomatis terisi nomor urut berikutnya.

### B. `INSERT INTO ... SELECT`
- **Kegunaan Utama:** Menyalin atau memindahkan banyak baris sekaligus dari tabel sumber (atau tabel sementara/*staging*) ke tabel tujuan dalam satu perintah.
- **Sintaks Baku:**
  ```sql
  INSERT INTO dbo.TabelTujuan (KolomA, KolomB)
  SELECT SumberA, SumberB
  FROM dbo.TabelSumber
  WHERE Kondisi;
  ```
- **Rincian Parameter / Klausa:**
  - Daftar kolom `TabelTujuan` dan daftar kolom di `SELECT` **wajib berjumlah sama** dan bertipe data kompatibel.
  - Tidak memakai kata kunci `VALUES`.
- **Nilai Kembalian / Efek Eksekusi:** Menyisipkan seluruh baris hasil `SELECT` ke `TabelTujuan` sekaligus. Jika hasil `SELECT` adalah 0 baris, perintah tetap sukses dengan pesan `(0 rows affected)`.
- **Contoh Mikro (1–3 Baris Mandiri):**
  ```sql
  INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
  SELECT StageSKU, StageName, StagePrice, StageStock
  FROM dbo.StagingProducts;
  ```
  *Hasil eksekusi:* Seluruh baris dari `dbo.StagingProducts` disalin masuk ke `dbo.Products`.

### C. `SELECT` (Kolom Eksplisit) & `WHERE` Sederhana
- **Kegunaan Utama:** Menampilkan baris dan kolom spesifik dari sebuah tabel. Selalu sebutkan nama kolom secara eksplisit (hindari `SELECT *`) agar hemat memori, bandwidth jaringan, dan siap untuk optimasi indeks di level lanjut.
- **Sintaks Baku:**
  ```sql
  SELECT Kolom1, Kolom2
  FROM dbo.NamaTabel
  WHERE KolomFilter = Nilai;
  ```
- **Rincian Operator Perbandingan Dasar pada `WHERE`:** `=` (sama dengan), `<>` atau `!=` (tidak sama dengan), `>` (lebih dari), `<` (kurang dari), `>=` (lebih dari atau sama dengan), `<=` (kurang dari atau sama dengan), serta penggabung `AND` / `OR`.
- **Contoh Mikro (1–3 Baris Mandiri):**
  ```sql
  SELECT ProductID, ProductSKU, ProductName, UnitPrice, StockQuantity
  FROM dbo.Products
  WHERE StockQuantity < 20;
  ```
  *Hasil eksekusi:* Menampilkan 5 kolom produk yang stoknya di bawah `20`.

### D. `UPDATE ... SET ... WHERE`
- **Kegunaan Utama:** Mengubah nilai satu atau beberapa kolom pada baris yang sudah ada.
- **Sintaks Baku:**
  ```sql
  UPDATE dbo.NamaTabel
  SET Kolom1 = NilaiBaru1,
      Kolom2 = NilaiBaru2
  WHERE KolomKunci = NilaiTarget;
  ```
- **Rincian Parameter / Klausa:**

  | Bagian Sintaks | Status | Penjelasan & Peringatan Kritis |
  | :--- | :---: | :--- |
  | `SET Kolom = Ekspresi` | **Wajib** | Bisa diisi nilai tetap (`UnitPrice = 15000.00`) atau perhitungan dari nilai lama (`StockQuantity = StockQuantity - 2`). |
  | `WHERE Kondisi` | *Opsional (Wajib secara Praktik)* | Menentukan baris mana yang diubah. **Peringatan:** Jika `WHERE` lupa ditulis, **SELURUH BARIS** di tabel akan tertimpa nilai baru! |

- **Contoh Mikro (1–3 Baris Mandiri):**
  ```sql
  UPDATE dbo.Products
  SET UnitPrice = 18000.00
  WHERE ProductSKU = 'BRG-001';
  ```
  *Hasil eksekusi:* Hanya 1 baris produk `'BRG-001'` yang harganya berubah menjadi `18000.00`.

### E. `DELETE FROM ... WHERE` vs `TRUNCATE TABLE`
- **Kegunaan Utama:** Menghapus sebagian baris (`DELETE ... WHERE`) atau mengosongkan seluruh isi tabel (`DELETE` tanpa `WHERE` vs `TRUNCATE TABLE`).
- **Sintaks Baku:**
  ```sql
  -- Menghapus baris terpilih (atau seluruh baris jika tanpa WHERE)
  DELETE FROM dbo.NamaTabel
  WHERE KolomKunci = NilaiTarget;

  -- Mengosongkan seluruh isi tabel secara total
  TRUNCATE TABLE dbo.NamaTabel;
  ```
- **Tabel Perbandingan Teknis:**

  | Aspek Perbandingan | `DELETE FROM dbo.Tabel [WHERE ...]` | `TRUNCATE TABLE dbo.Tabel` |
  | :--- | :--- | :--- |
  | **Bisa pakai `WHERE`?** | **Ya** (bisa pilih baris tertentu) | **Tidak** (selalu menghapus semua baris) |
  | **Pencatatan ke Log (`.ldf`)** | Penuh (mencatat setiap baris yang dihapus) | Minimal (hanya mencatat pelepasan *data pages/extents*) |
  | **Kecepatan pada jutaan baris** | Lambat (menghapus baris satu per satu) | Sangat cepat (instan melepaskan alokasi halaman) |
  | **Efek pada `IDENTITY`** | **Tidak reset** (lanjut dari angka terakhir) | **Reset ke nilai awal (*seed*)**, misal kembali ke `1` |
  | **Bila tabel dirujuk `FOREIGN KEY`** | Bisa (selama baris yang dihapus tidak punya data anak) | **Selalu Ditolak** (bahkan meski tabel anak berisi 0 baris!) |

- **Contoh Mikro (1–3 Baris Mandiri):**
  ```sql
  DELETE FROM dbo.StagingProducts WHERE StageSKU = 'STG-001';
  TRUNCATE TABLE dbo.StagingProducts;
  ```

---

## 3. Persiapan Lingkungan & Skema Praktikum

- **Kebutuhan Lingkungan:** Microsoft SQL Server 2019 / 2022 + SSMS v19+ / Azure Data Studio (dari Level 1), serta pemahaman `CREATE DATABASE`, tipe data presisi, `PRIMARY KEY`, `FOREIGN KEY`, dan `CHECK` constraint (dari Level 2).
- **Peta Status Tema Berkelanjutan (Progresi Lintas Modul):**

  | Tema | Domain Sistem | Status dari Level 2 (Modul 03–05) | Target Penambahan di Modul 06 |
  | :--- | :--- | :--- | :--- |
  | **Tema 1 (Inti)** | `TokoRitelDB` | Struktur `Customers`, `Products`, `Orders`, dan `StagingProducts` | Pengisian awal (*seeder*) + praktik `INSERT`, `UPDATE`, `DELETE`, dan `TRUNCATE` di Bagian 4 & Level 1–2. |
  | **Tema 2 (Transfer)** | `AkademikDB` | Struktur `Santri`, `MataPelajaran`, `NilaiSemester`, `SantriCalonBaru` | Transfer CRUD & milestone pemindahan calon santri ke tabel utama (Level 3). |
  | **Tema 3 (Pendalaman)** | `LogistikAuditDB` | Struktur `GudangBarang`, `LogMutasiHarian`, `ArsipMutasi` | Analisis *edge case* `CHECK`/`FOREIGN KEY` dan dampak log `.ldf` saat pengarsipan (Level 4). |

### Skrip Persiapan — Tema 1 (`TokoRitelDB`)
*Salin dan jalankan skrip berikut untuk membangun ulang struktur dasar dan data awal `TokoRitelDB` secara bersih:*

```sql
-- =========================================================================
-- CHECKPOINT TEMA 1: TokoRitelDB (Modul 06 - Fondasi Skema & Data Awal)
-- =========================================================================
SET NOCOUNT ON;

IF DB_ID('TokoRitelDB') IS NULL
BEGIN
    CREATE DATABASE TokoRitelDB;
END;
GO

USE TokoRitelDB;
GO

IF OBJECT_ID('dbo.Orders', 'U') IS NOT NULL DROP TABLE dbo.Orders;
IF OBJECT_ID('dbo.Products', 'U') IS NOT NULL DROP TABLE dbo.Products;
IF OBJECT_ID('dbo.Customers', 'U') IS NOT NULL DROP TABLE dbo.Customers;
IF OBJECT_ID('dbo.StagingProducts', 'U') IS NOT NULL DROP TABLE dbo.StagingProducts;
GO

CREATE TABLE dbo.Customers (
    CustomerID INT IDENTITY(1,1) NOT NULL,
    CustomerCode VARCHAR(20) NOT NULL,
    FullName NVARCHAR(150) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    IsActive BIT NOT NULL CONSTRAINT DF_Customers_IsActive DEFAULT (1),
    CreatedAt DATETIME2(3) NOT NULL CONSTRAINT DF_Customers_CreatedAt DEFAULT (SYSUTCDATETIME()),
    CONSTRAINT PK_Customers PRIMARY KEY CLUSTERED (CustomerID),
    CONSTRAINT UQ_Customers_Code UNIQUE (CustomerCode),
    CONSTRAINT UQ_Customers_Email UNIQUE (Email)
);

CREATE TABLE dbo.Products (
    ProductID INT IDENTITY(1,1) NOT NULL,
    ProductSKU VARCHAR(50) NOT NULL,
    ProductName NVARCHAR(200) NOT NULL,
    UnitPrice DECIMAL(18,2) NOT NULL,
    StockQuantity INT NOT NULL,
    CONSTRAINT PK_Products PRIMARY KEY CLUSTERED (ProductID),
    CONSTRAINT UQ_Products_SKU UNIQUE (ProductSKU),
    CONSTRAINT CHK_Products_Price CHECK (UnitPrice >= 0),
    CONSTRAINT CHK_Products_Stock CHECK (StockQuantity >= 0)
);

-- Mencatat pesanan pelanggan atas produk
CREATE TABLE dbo.Orders (
    OrderID BIGINT IDENTITY(1,1) NOT NULL,
    OrderNumber VARCHAR(30) NOT NULL,
    CustomerID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    OrderDate DATETIME2(3) NOT NULL CONSTRAINT DF_Orders_OrderDate DEFAULT (SYSUTCDATETIME()),
    CONSTRAINT PK_Orders PRIMARY KEY CLUSTERED (OrderID),
    CONSTRAINT UQ_Orders_Number UNIQUE (OrderNumber),
    CONSTRAINT FK_Orders_Customers FOREIGN KEY (CustomerID) REFERENCES dbo.Customers(CustomerID),
    CONSTRAINT FK_Orders_Products FOREIGN KEY (ProductID) REFERENCES dbo.Products(ProductID),
    CONSTRAINT CHK_Orders_Qty CHECK (Quantity > 0)
);

-- Tabel penampung sementara untuk produk baru sebelum masuk ke katalog utama
CREATE TABLE dbo.StagingProducts (
    StageID INT IDENTITY(1,1) NOT NULL,
    StageSKU VARCHAR(50) NOT NULL,
    StageName NVARCHAR(200) NOT NULL,
    StagePrice DECIMAL(18,2) NOT NULL,
    StageStock INT NOT NULL,
    CONSTRAINT PK_StagingProducts PRIMARY KEY CLUSTERED (StageID)
);
GO

-- Data awal (Seeder) Tema 1
INSERT INTO dbo.Customers (CustomerCode, FullName, Email)
VALUES
    ('CUST-001', N'Ahmad Fauzan', 'ahmad.fauzan@example.com'),
    ('CUST-002', N'Siti Rahmawati', 'siti.rahma@example.com'),
    ('CUST-003', N'Budi Santoso', 'budi.santoso@example.com');

INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
VALUES
    ('BRG-001', N'Beras Pandan Wangi 5 Kg', 74000.00, 25),
    ('BRG-002', N'Minyak Goreng Kelapa 2 L', 36000.00, 40),
    ('BRG-003', N'Teh Celup Melati Box', 8500.00, 60),
    ('BRG-004', N'Kurma Ajwa 500 g', 95000.00, 15),
    ('BRG-005', N'Madu Hutan Asli 250 ml', 65000.00, 12);

INSERT INTO dbo.Orders (OrderNumber, CustomerID, ProductID, Quantity)
VALUES ('ORD-2026-0001', 1, 1, 2);

INSERT INTO dbo.StagingProducts (StageSKU, StageName, StagePrice, StageStock)
VALUES
    ('BRG-006', N'Garam Beryodium 500 g', 4500.00, 100),
    ('BRG-007', N'Susu Bubuk Full Cream 400 g', 42000.00, 30),
    ('BRG-008', N'Kopi Arabika Gayo 250 g', 58000.00, 20);
GO
```

### Skrip Persiapan — Tema 2 (`AkademikDB`) & Tema 3 (`LogistikAuditDB`)
*Jalankan skrip ini sebelum mengerjakan Latihan Level 3 dan Level 4.*

```sql
-- =========================================================================
-- CHECKPOINT TEMA 2 & 3: AkademikDB & LogistikAuditDB (Modul 06)
-- =========================================================================
SET NOCOUNT ON;

IF DB_ID('AkademikDB') IS NULL CREATE DATABASE AkademikDB;
GO

USE AkademikDB;
GO

IF OBJECT_ID('dbo.NilaiSemester', 'U') IS NOT NULL DROP TABLE dbo.NilaiSemester;
IF OBJECT_ID('dbo.MataPelajaran', 'U') IS NOT NULL DROP TABLE dbo.MataPelajaran;
IF OBJECT_ID('dbo.Santri', 'U') IS NOT NULL DROP TABLE dbo.Santri;
IF OBJECT_ID('dbo.SantriCalonBaru', 'U') IS NOT NULL DROP TABLE dbo.SantriCalonBaru;
GO

CREATE TABLE dbo.Santri (
    SantriID INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    NIS VARCHAR(20) NOT NULL UNIQUE,
    NamaLengkap NVARCHAR(150) NOT NULL,
    StatusAktif BIT NOT NULL DEFAULT (1)
);

CREATE TABLE dbo.MataPelajaran (
    MapelID INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    KodeMapel VARCHAR(15) NOT NULL UNIQUE,
    NamaMapel NVARCHAR(100) NOT NULL,
    KKM DECIMAL(5,2) NOT NULL CHECK (KKM >= 0 AND KKM <= 100)
);

CREATE TABLE dbo.NilaiSemester (
    NilaiID INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    SantriID INT NOT NULL REFERENCES dbo.Santri(SantriID),
    MapelID INT NOT NULL REFERENCES dbo.MataPelajaran(MapelID),
    NilaiAngka DECIMAL(5,2) NOT NULL CHECK (NilaiAngka >= 0 AND NilaiAngka <= 100),
    TahunAjaran VARCHAR(10) NOT NULL
);

CREATE TABLE dbo.SantriCalonBaru (
    CalonID INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    NIS VARCHAR(20) NOT NULL,
    NamaLengkap NVARCHAR(150) NOT NULL
);

INSERT INTO dbo.Santri (NIS, NamaLengkap)
VALUES
    ('2026001', N'Muhammad Ridwan'),
    ('2026002', N'Aisyah Nurjanah'),
    ('2026003', N'Fajar Pratama'),
    ('2026004', N'Zahra Salsabila'),
    ('2026005', N'Hasan Al-Banna');

INSERT INTO dbo.MataPelajaran (KodeMapel, NamaMapel, KKM)
VALUES
    ('MP-BD1', N'Basis Data Dasar', 75.00),
    ('MP-ALG', N'Algoritma & Pemrograman', 75.00),
    ('MP-JAR', N'Jaringan Komputer', 70.00);

INSERT INTO dbo.NilaiSemester (SantriID, MapelID, NilaiAngka, TahunAjaran)
VALUES
    (1, 1, 85.00, '2026/2027'),
    (2, 1, 90.00, '2026/2027'),
    (3, 1, 72.00, '2026/2027'),
    (4, 2, 88.00, '2026/2027');

INSERT INTO dbo.SantriCalonBaru (NIS, NamaLengkap)
VALUES
    ('2026006', N'Nadia Kusuma'),
    ('2026007', N'Rizky Ramadhan'),
    ('2026008', N'Salma Khairunnisa');
GO

-- =========================================================================
-- TEMA 3: LogistikAuditDB
-- =========================================================================
IF DB_ID('LogistikAuditDB') IS NULL CREATE DATABASE LogistikAuditDB;
GO

USE LogistikAuditDB;
GO

IF OBJECT_ID('dbo.LogMutasiHarian', 'U') IS NOT NULL DROP TABLE dbo.LogMutasiHarian;
IF OBJECT_ID('dbo.GudangBarang', 'U') IS NOT NULL DROP TABLE dbo.GudangBarang;
IF OBJECT_ID('dbo.ArsipMutasi', 'U') IS NOT NULL DROP TABLE dbo.ArsipMutasi;
GO

CREATE TABLE dbo.GudangBarang (
    BarangID INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    KodeBarang VARCHAR(20) NOT NULL UNIQUE,
    NamaBarang NVARCHAR(150) NOT NULL,
    StokGudang INT NOT NULL CHECK (StokGudang >= 0)
);

CREATE TABLE dbo.LogMutasiHarian (
    LogID BIGINT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    BarangID INT NOT NULL REFERENCES dbo.GudangBarang(BarangID),
    JenisMutasi VARCHAR(10) NOT NULL CHECK (JenisMutasi IN ('MASUK', 'KELUAR')),
    Jumlah INT NOT NULL CHECK (Jumlah > 0),
    WaktuCatat DATETIME2(3) NOT NULL DEFAULT (SYSUTCDATETIME())
);

CREATE TABLE dbo.ArsipMutasi (
    ArsipID BIGINT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    BarangID INT NOT NULL,
    JenisMutasi VARCHAR(10) NOT NULL,
    Jumlah INT NOT NULL,
    WaktuCatat DATETIME2(3) NOT NULL
);

INSERT INTO dbo.GudangBarang (KodeBarang, NamaBarang, StokGudang)
VALUES
    ('LOG-A01', N'Karton Kemasan Besar', 200),
    ('LOG-A02', N'Lakban Bening Industri', 150),
    ('LOG-B01', N'Palet Kayu Standar', 35),
    ('LOG-B02', N'Plastik Wrapping Roll', 80),
    ('LOG-C01', N'Label Barcode Thermal', 500);

INSERT INTO dbo.LogMutasiHarian (BarangID, JenisMutasi, Jumlah)
VALUES
    (1, 'MASUK', 50),
    (2, 'KELUAR', 20),
    (3, 'KELUAR', 5),
    (4, 'MASUK', 30),
    (5, 'KELUAR', 100);
GO
```

---

## 4. Contoh Praktik Bertingkat — Studi Kasus `TokoRitelDB`

Seluruh contoh di bagian ini berfokus pada studi kasus **`TokoRitelDB`** dengan peningkatan satu konsep baru pada setiap tahapnya.

### Contoh 1: Kasus Paling Dasar (`INSERT INTO ... VALUES` & `SELECT` Kolom Eksplisit)
Menambahkan satu produk baru lalu langsung dua produk sekaligus ke `dbo.Products`, kemudian memverifikasi daftar tabel menggunakan `SELECT` kolom eksplisit.

```sql
-- Contoh 1: Menambah 1 baris dan multi-baris dengan INSERT INTO ... VALUES
USE TokoRitelDB;
GO

-- 1. Menyisipkan 1 baris data produk baru
INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
VALUES ('BRG-009', N'Kecap Manis Botol 500 ml', 22000.00, 35);

-- 2. Menyisipkan 2 baris sekaligus dalam satu perintah INSERT
INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
VALUES
    ('BRG-010', N'Saus Sambal 340 ml', 14500.00, 50),
    ('BRG-011', N'Tepung Terigu Serbaguna 1 Kg', 13000.00, 45);

-- 3. Membaca hasil dengan menyebutkan nama kolom eksplisit
SELECT ProductID, ProductSKU, ProductName, UnitPrice, StockQuantity
FROM dbo.Products;
GO
```
- **Penjelasan Baris Kunci:**
  - Kolom `ProductID` sengaja **tidak ditulis** pada daftar kolom `INSERT` karena bernilai `IDENTITY(1,1)` yang otomatis diisi SQL Server (`6`, `7`, `8`).
  - Prefix `N'...'` dipakai pada `ProductName` karena bertipe `NVARCHAR(200)` (Unicode).
- **Hasil Keluaran:**
  ```text
  ProductID  ProductSKU  ProductName                   UnitPrice  StockQuantity
  ---------  ----------  ----------------------------  ---------  -------------
  1          BRG-001     Beras Pandan Wangi 5 Kg       74000.00   25
  ...
  6          BRG-009     Kecap Manis Botol 500 ml      22000.00   35
  7          BRG-010     Saus Sambal 340 ml            14500.00   50
  8          BRG-011     Tepung Terigu Serbaguna 1 Kg  13000.00   45
  ```
  *(Total `8` baris pada `dbo.Products`)*.

### Contoh 2: Variasi Kondisi (`UPDATE ... SET ... WHERE`) + *State Tracing*
Memperbarui harga dan mengurangi stok karena ada pembelian tunai untuk produk `'BRG-001'`, menggunakan klausa `WHERE` yang presisi.

```sql
-- Contoh 2: Memperbarui baris spesifik dengan UPDATE ... WHERE
USE TokoRitelDB;
GO

-- Kondisi sebelum perubahan (opsional untuk verifikasi manual)
SELECT ProductID, ProductSKU, ProductName, UnitPrice, StockQuantity
FROM dbo.Products
WHERE ProductSKU = 'BRG-001';

-- Mengubah harga menjadi 76000.00 dan mengurangi stok sebanyak 3 unit
UPDATE dbo.Products
SET UnitPrice = 76000.00,
    StockQuantity = StockQuantity - 3
WHERE ProductSKU = 'BRG-001';

-- Kondisi sesudah perubahan
SELECT ProductID, ProductSKU, ProductName, UnitPrice, StockQuantity
FROM dbo.Products
WHERE ProductSKU = 'BRG-001';
GO
```
- **Perbedaan dengan Contoh 1:** Contoh 1 menambah baris baru, sedangkan Contoh 2 memodifikasi baris yang sudah ada berdasarkan kunci unik `ProductSKU = 'BRG-001'` tanpa mengubah `ProductID`.
- **Pelacakan Kondisi Antara (*State Tracing*):**
  ```text
  [1. Kondisi Awal Tabel dbo.Products]:
    - ProductID = 1 ('BRG-001'): UnitPrice = 74000.00 | StockQuantity = 25
    - ProductID = 2..8         : (7 baris lainnya)

  [2. Evaluasi Klausa WHERE ProductSKU = 'BRG-001']:
    - Hanya baris ProductID = 1 yang memenuhi kondisi TRUE; 7 baris lainnya diabaikan.

  [3. Evaluasi Klausa SET]:
    - UnitPrice     = 76000.00
    - StockQuantity = 25 - 3 = 22  (valid karena 22 >= 0 pada CHK_Products_Stock)

  [4. Kondisi Akhir]:
    - ProductID = 1 ('BRG-001'): UnitPrice = 76000.00 | StockQuantity = 22
  ```
- **Hasil Keluaran:**
  ```text
  ProductID  ProductSKU  ProductName              UnitPrice  StockQuantity
  ---------  ----------  -----------------------  ---------  -------------
  1          BRG-001     Beras Pandan Wangi 5 Kg  76000.00   22
  ```

### Contoh 3: Kombinasi Menengah (`INSERT INTO ... SELECT` + `DELETE ... WHERE`) + *Step Trace*
Memindahkan produk yang harganya `>= 20000.00` dari tabel penampung `dbo.StagingProducts` ke tabel utama `dbo.Products`, lalu menghapus data yang sudah dipindahkan tersebut dari `dbo.StagingProducts` dan menguji perilaku `IDENTITY` pada `dbo.StagingProducts`.

```sql
-- Contoh 3: Memindahkan data antar-tabel dan menghapus baris terproses
USE TokoRitelDB;
GO

-- 1. Salin produk dengan harga >= 20000 dari StagingProducts ke Products
INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
SELECT StageSKU, StageName, StagePrice, StageStock
FROM dbo.StagingProducts
WHERE StagePrice >= 20000.00;

-- 2. Hapus baris yang sudah dipindahkan dari StagingProducts memakai DELETE + WHERE
DELETE FROM dbo.StagingProducts
WHERE StagePrice >= 20000.00;

-- 3. Sisipkan 1 baris baru ke StagingProducts untuk mengamati nomor StageID (IDENTITY)
INSERT INTO dbo.StagingProducts (StageSKU, StageName, StagePrice, StageStock)
VALUES ('BRG-012', N'Gula Merah 500 g', 16000.00, 30);

-- 4. Verifikasi isi kedua tabel
SELECT ProductID, ProductSKU, ProductName, UnitPrice
FROM dbo.Products
WHERE ProductID >= 6;

SELECT StageID, StageSKU, StageName, StagePrice
FROM dbo.StagingProducts;
GO
```
- **Pelacakan Alur Data (*Step-by-Step Trace*):**
  ```text
  Awal dbo.StagingProducts (3 baris):
    StageID 1 ('BRG-006', 4500.00), StageID 2 ('BRG-007', 42000.00), StageID 3 ('BRG-008', 58000.00)
        │
        ▼  (Tahap 1: INSERT INTO dbo.Products ... SELECT ... WHERE StagePrice >= 20000.00)
  2 baris ('BRG-007' & 'BRG-008') masuk ke dbo.Products menjadi ProductID 9 & 10.
        │
        ▼  (Tahap 2: DELETE FROM dbo.StagingProducts WHERE StagePrice >= 20000.00)
  StageID 2 & 3 terhapus; StagingProducts tersisa StageID 1 ('BRG-006').
  Counter IDENTITY terakhir StagingProducts tetap berada di angka 3!
        │
        ▼  (Tahap 3: INSERT 'BRG-012' ke StagingProducts)
  Baris baru mendapat StageID = 4 (BUKAN 2, karena DELETE tidak mereset IDENTITY).
  ```
- **Hasil Keluaran:**
  ```text
  -- dbo.Products (ProductID >= 6):
  ProductID  ProductSKU  ProductName                   UnitPrice
  ---------  ----------  ----------------------------  ---------
  6          BRG-009     Kecap Manis Botol 500 ml      22000.00
  7          BRG-010     Saus Sambal 340 ml            14500.00
  8          BRG-011     Tepung Terigu Serbaguna 1 Kg  13000.00
  9          BRG-007     Susu Bubuk Full Cream 400 g   42000.00
  10         BRG-008     Kopi Arabika Gayo 250 g       58000.00

  -- dbo.StagingProducts:
  StageID  StageSKU  StageName              StagePrice
  -------  --------  ---------------------  ----------
  1        BRG-006   Garam Beryodium 500 g  4500.00
  4        BRG-012   Gula Merah 500 g       16000.00
  ```

### Contoh 4: Pembersihan Total & Kasus Batas (`TRUNCATE TABLE` vs `FOREIGN KEY` & Reset `IDENTITY`)

> **Jalur Pendalaman (Mahasiswa / Pengayaan SMK):** Contoh ini menguji perilaku *edge case* pengosongan tabel dan kaitannya dengan aturan *Foreign Key* pada tingkat metadata.

Mengosongkan `dbo.StagingProducts` menggunakan `TRUNCATE TABLE` untuk mereset `IDENTITY` kembali ke `1`, serta memahami mengapa `TRUNCATE TABLE` ditolak pada tabel induk `dbo.Products` meskipun `dbo.Orders` dikosongkan.

```sql
-- Contoh 4: Mengosongkan tabel staging dan menguji batasan Foreign Key pada TRUNCATE
USE TokoRitelDB;
GO

-- 1. Kosongkan tabel staging dengan TRUNCATE TABLE (cepat + reset IDENTITY)
TRUNCATE TABLE dbo.StagingProducts;

-- 2. Sisipkan 1 baris baru: perhatikan StageID kembali mulai dari 1!
INSERT INTO dbo.StagingProducts (StageSKU, StageName, StagePrice, StageStock)
VALUES ('BRG-013', N'Kacang Hijau 500 g', 15000.00, 40);

SELECT StageID, StageSKU, StageName
FROM dbo.StagingProducts;

-- 3. EKSPERIMEN EDGE CASE (Amati pesan error):
-- Hapus semua baris dbo.Orders sehingga dbo.Orders berisi 0 baris
DELETE FROM dbo.Orders;

-- Meski dbo.Orders sudah 0 baris, perintah di bawah ini TETAP GAGAL (Msg 4712):
-- TRUNCATE TABLE dbo.Products;
-- Pesan Error: Cannot truncate table 'dbo.Products' because it is being referenced by a FOREIGN KEY constraint.
GO
```
- **Mengapa Langkah Ini Penting:** SQL Server memeriksa keberadaan skema *Foreign Key* pada tingkat metadata sebelum menjalankan `TRUNCATE TABLE`, bukan memeriksa apakah tabel anak berisi baris atau kosong. Oleh karena itu, tabel referensi utama seperti `dbo.Products` atau `dbo.Customers` hanya bisa dikosongkan menggunakan `DELETE FROM dbo.Products;` (setelah tabel anaknya bersih), bukan `TRUNCATE TABLE`.
- **Hasil Keluaran:**
  ```text
  StageID  StageSKU  StageName
  -------  --------  ------------------
  1        BRG-013   Kacang Hijau 500 g
  ```
  *(Dan jika baris komentar `TRUNCATE TABLE dbo.Products;` dijalankan, SSMS langsung menolak dengan `Msg 4712`)*.

---

## 5. Verifikasi Output & Panduan Troubleshooting

### A. Ringkasan Ekspektasi Output Contoh 1–4

| Contoh | Jalur | Fokus Fitur yang Diuji | Indikator Berhasil / Output Kunci |
| :--- | :---: | :--- | :--- |
| **Contoh 1** | Inti | `INSERT` tunggal & multi-baris | `dbo.Products` bertambah dari `5` baris menjadi `8` baris (`ProductID` `1` s.d. `8`). |
| **Contoh 2** | Inti | `UPDATE ... SET ... WHERE` | Hanya `ProductID = 1` yang berubah (`UnitPrice = 76000.00`, `StockQuantity = 22`). |
| **Contoh 3** | Inti | `INSERT...SELECT` + `DELETE...WHERE` | `ProductID 9` dan `10` masuk ke `Products`; `StagingProducts` menyisakan `StageID 1` dan `4` (`IDENTITY` lanjut). |
| **Contoh 4** | Pendalaman | `TRUNCATE TABLE` & FK *Edge Case* | `StageID` pada `StagingProducts` reset menjadi `1`; `TRUNCATE` pada `Products` ditolak oleh `Msg 4712`. |

### B. Kesalahan Umum & Anti-Pattern Pemula (*Common Pitfalls*)

| Kesalahan / Gejala Error | Penyebab Umum pada Argumen / Klausa | Solusi Tepat |
| :--- | :--- | :--- |
| Seluruh harga barang tiba-tiba berubah menjadi sama semua! | Menjalankan `UPDATE dbo.Products SET UnitPrice = 5000;` dan **lupa menulis klausa `WHERE`**. | Biasakan selalu mengetik `SELECT ... WHERE ...` dulu untuk mengecek baris target, baru ubah menjadi `UPDATE ... WHERE ...`. |
| `Msg 213: Column name or number of supplied values does not match table definition.` | Jumlah nilai pada `VALUES (...)` atau `SELECT ...` tidak sama dengan daftar kolom tabel (sering karena menyertakan nilai untuk kolom `IDENTITY` tanpa sengaja). | Selalu tulis daftar kolom eksplisit `INSERT INTO Tabel (Kol1, Kol2)` dan lewati kolom `IDENTITY`. |
| `Msg 547: The UPDATE/INSERT statement conflicted with the CHECK constraint "CHK_Products_Stock".` | Mencoba mengisi atau mengurangi `StockQuantity` sehingga nilainya menjadi negatif (`< 0`). | Pastikan jumlah pengurangan stok tidak melebihi `StockQuantity` saat ini sebelum melakukan `UPDATE`. |
| `Msg 4712: Cannot truncate table ... because it is being referenced by a FOREIGN KEY constraint.` | Mencoba menjalankan `TRUNCATE TABLE` pada tabel induk (`Products`/`Customers`/`Santri`) yang terikat `FOREIGN KEY`. | Gunakan `DELETE FROM dbo.NamaTabel;` (setelah data anak dihapus) atau hanya gunakan `TRUNCATE TABLE` pada tabel staging/log tanpa FK. |

---

## 6. Latihan Soal

*Perhatikan tabel-tabel data acuan berikut sebelum mengerjakan latihan soal:*

#### Tabel Acuan: dbo.Products (TokoRitelDB)

| ProductID | ProductSKU | ProductName | UnitPrice | StockQuantity |
| :---: | :--- | :--- | :---: | :---: |
| 1 | `BRG-001` | Beras Pandan Wangi 5 Kg | 74000.00 | 25 |
| 2 | `BRG-002` | Minyak Goreng Kelapa 2 L | 36000.00 | 40 |
| 3 | `BRG-003` | Teh Celup Melati Box | 8500.00 | 60 |
| 4 | `BRG-004` | Kurma Ajwa 500 g | 95000.00 | 15 |
| 5 | `BRG-005` | Madu Hutan Asli 250 ml | 65000.00 | 12 |

#### Tabel Acuan: dbo.Customers (TokoRitelDB)

| CustomerID | CustomerCode | FullName | Email | IsActive |
| :---: | :--- | :--- | :--- | :---: |
| 1 | `CUST-001` | Ahmad Fauzan | ahmad.fauzan@example.com | 1 |
| 2 | `CUST-002` | Siti Rahmawati | siti.rahma@example.com | 1 |
| 3 | `CUST-003` | Budi Santoso | budi.santoso@example.com | 1 |

#### Tabel Acuan: dbo.StagingProducts (TokoRitelDB)

| StageID | StageSKU | StageName | StagePrice | StageStock |
| :---: | :--- | :--- | :---: | :---: |
| 1 | `BRG-006` | Garam Beryodium 500 g | 4500.00 | 100 |
| 2 | `BRG-007` | Susu Bubuk Full Cream 400 g | 42000.00 | 30 |
| 3 | `BRG-008` | Kopi Arabika Gayo 250 g | 58000.00 | 20 |

#### Tabel Acuan: dbo.SantriCalonBaru (AkademikDB)

| CalonID | NIS | NamaLengkap |
| :---: | :--- | :--- |
| 1 | `2026006` | Nadia Kusuma |
| 2 | `2026007` | Rizky Ramadhan |
| 3 | `2026008` | Salma Khairunnisa |

#### Tabel Acuan: dbo.NilaiSemester (AkademikDB)

| NilaiID | SantriID | MapelID | NilaiAngka | TahunAjaran |
| :---: | :---: | :---: | :---: | :--- |
| 1 | 1 | 1 | 85.00 | 2026/2027 |
| 2 | 2 | 1 | 90.00 | 2026/2027 |
| 3 | 3 | 1 | 72.00 | 2026/2027 |
| 4 | 4 | 2 | 88.00 | 2026/2027 |

---

#### Daftar Tugas Praktik
1. Tulis perintah T-SQL untuk memprediksi dan melacak nilai kolom `StageID` yang tersisa pada tabel `dbo.StagingProducts` setelah menghapus baris `StageID = 3`, menambahkan baris produk baru `'BRG-X01'` (`N'Gula Aren 250 g'`, `18000.00`, `10`), dan membaca seluruh datanya.
2. Identifikasi penyebab kegagalan eksekusi dan sebutkan nama *constraint* yang dilanggar ketika mencoba menyisipkan baris produk baru dengan stok `-2` ke tabel `dbo.Products`:
   ```sql
   INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
   VALUES ('BRG-999', N'Produk Uji', 10000.00, -2);
   ```
3. Tulis perintah T-SQL untuk memperbaiki kesalahan fatal pada skrip berikut agar kenaikan stok 10 unit hanya diterapkan pada produk dengan SKU `'BRG-003'`:
   ```sql
   USE TokoRitelDB;
   UPDATE dbo.Products
   SET StockQuantity = StockQuantity + 10;
   ```
4. Tulis perintah T-SQL untuk menambahkan dua pelanggan baru sekaligus ke dalam tabel `dbo.Customers` (`CustomerCode`, `FullName`, `Email`): `'CUST-004'` (`N'Dewi Lestari'`, `'dewi.lestari@example.com'`) dan `'CUST-005'` (`N'Hendra Wijaya'`, `'hendra.w@example.com'`), lalu verifikasi hasilnya dengan `SELECT` kolom eksplisit (`CustomerID`, `CustomerCode`, `FullName`, `IsActive`).
5. Tulis perintah T-SQL untuk memperbarui produk `'BRG-002'` (`Minyak Goreng Kelapa 2 L`) pada tabel `dbo.Products` sehingga harganya naik `2500.00` dari harga saat ini dan stoknya bertambah `15`, lalu verifikasi hasilnya dengan `SELECT` kolom eksplisit.
6. Tulis perintah T-SQL untuk menyalin seluruh produk dari `dbo.StagingProducts` yang memiliki nilai `StageStock >= 30` ke dalam tabel `dbo.Products` menggunakan klausa `INSERT INTO ... SELECT`.
7. Tulis perintah T-SQL untuk menambahkan satu mata pelajaran baru ke `dbo.MataPelajaran` (`'MP-WEB'`, `N'Pemrograman Web'`, KKM `75.00`) pada `AkademikDB`, perbarui `NilaiAngka` santri dengan `SantriID = 3` pada `MapelID = 1` menjadi `80.00`, dan tampilkan seluruh data `dbo.NilaiSemester`.
8. Tulis perintah T-SQL untuk memindahkan seluruh data calon santri dari `dbo.SantriCalonBaru` ke tabel utama `dbo.Santri` menggunakan `INSERT INTO ... SELECT`, lalu kosongkan tabel `dbo.SantriCalonBaru` seketika serta reset counter `IDENTITY`-nya menggunakan `TRUNCATE TABLE`.
9. Analisis batasan integritas relasional: jelaskan penyebab penolakan `Msg 4712` saat mengeksekusi `TRUNCATE TABLE dbo.GudangBarang;` pada `LogistikAuditDB` meskipun seluruh baris pada tabel anak `dbo.LogMutasiHarian` telah dikosongkan, serta tuliskan urutan perintah DML yang aman untuk mengosongkan `dbo.GudangBarang` tanpa menghapus struktur *Foreign Key*.
10. Analisis efisiensi log transaksi: bandingkan pembersihan 500.000 baris log mutasi bulanan pada `dbo.LogMutasiHarian` menggunakan `DELETE` versus `TRUNCATE TABLE` ditinjau dari (a) pertumbuhan volume file log `.ldf`, (b) kecepatan dan level penguncian sumber daya (*locking*), dan (c) nomor urut `LogID` (`IDENTITY`) pada transaksi bulan berikutnya.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil eksekusi kueri latihan soal Anda secara mandiri:*

### A. Indikator Keberhasilan Tugas & Target Output Visual Kueri

- **Target Output Visual Soal 1 (Pelacakan IDENTITY Staging):**
  ```text
  +---------+----------+
  | StageID | StageSKU |
  +---------+----------+
  | 1       | BRG-006  |
  | 2       | BRG-007  |
  | 4       | BRG-X01  |
  +---------+----------+
  (3 rows affected)
  ```
  *(Catatan: StageID bernilai 1, 2, dan 4 karena DELETE tidak mereset counter IDENTITY).*

- **Target Output Visual Soal 4 (Verifikasi 2 Pelanggan Baru):**
  ```text
  +------------+--------------+-----------------+----------+
  | CustomerID | CustomerCode | FullName        | IsActive |
  +------------+--------------+-----------------+----------+
  | 4          | CUST-004     | Dewi Lestari    | 1        |
  | 5          | CUST-005     | Hendra Wijaya   | 1        |
  +------------+--------------+-----------------+----------+
  (2 rows affected)
  ```

- **Target Output Visual Soal 5 (Pembaruan Harga & Stok Produk BRG-002):**
  ```text
  +-----------+------------+--------------------------+-----------+---------------+
  | ProductID | ProductSKU | ProductName              | UnitPrice | StockQuantity |
  +-----------+------------+--------------------------+-----------+---------------+
  | 2         | BRG-002    | Minyak Goreng Kelapa 2 L | 38500.00  | 55            |
  +-----------+------------+--------------------------+-----------+---------------+
  (1 row affected)
  ```

- **Target Output Visual Soal 6 (Hasil Kueri Produk Setelah Salin Massal):**
  ```text
  +-----------+------------+-----------------------------+-----------+---------------+
  | ProductID | ProductSKU | ProductName                 | UnitPrice | StockQuantity |
  +-----------+------------+-----------------------------+-----------+---------------+
  | 6         | BRG-006    | Garam Beryodium 500 g       | 4500.00   | 100           |
  | 7         | BRG-007    | Susu Bubuk Full Cream 400 g | 42000.00  | 30            |
  +-----------+------------+-----------------------------+-----------+---------------+
  (2 rows affected)
  ```

- **Target Output Visual Soal 7 (Verifikasi NilaiSemester AkademikDB):**
  ```text
  +---------+----------+---------+------------+-------------+
  | NilaiID | SantriID | MapelID | NilaiAngka | TahunAjaran |
  +---------+----------+---------+------------+-------------+
  | 1       | 1        | 1       | 85.00      | 2026/2027   |
  | 2       | 2        | 1       | 90.00      | 2026/2027   |
  | 3       | 3        | 1       | 80.00      | 2026/2027   |
  | 4       | 4        | 2       | 88.00      | 2026/2027   |
  +---------+----------+---------+------------+-------------+
  (4 rows affected)
  ```

- **Target Output Visual Soal 8 (Verifikasi 8 Santri Aktif):**
  ```text
  +----------+---------+-------------------+-------------+
  | SantriID | NIS     | NamaLengkap       | StatusAktif |
  +----------+---------+-------------------+-------------+
  | 1        | 2026001 | Muhammad Ridwan   | 1           |
  | ...      | ...     | ...               | 1           |
  | 6        | 2026006 | Nadia Kusuma      | 1           |
  | 7        | 2026007 | Rizky Ramadhan    | 1           |
  | 8        | 2026008 | Salma Khairunnisa | 1           |
  +----------+---------+-------------------+-------------+
  (8 rows affected)
  ```

- **Indikator Keberhasilan Soal 2, 3, 9, 10 (Analisis & Troubleshooting):**
  - **Soal 2:** Mengetahui pelanggaran batasan integritas `CHK_Products_Stock` (`StockQuantity >= 0`).
  - **Soal 3:** Menambahkan klausa filter presisi `WHERE ProductSKU = 'BRG-003';` sehingga mencegah penimpaan stok massal.
  - **Soal 9:** Memahami bahwa `TRUNCATE TABLE` dicegah oleh keberadaan relasi *Foreign Key* pada tingkat metadata, dan menggunakan `DELETE` berurutan dari tabel anak ke tabel induk sebagai solusi aman.
  - **Soal 10:** Analisis membedah efisiensi deallokasi halaman pada `TRUNCATE` (minimal logging di `.ldf`, pelepasan kunci tabel instan, reset seed `IDENTITY = 1`) dibanding biaya pencatatan baris demi baris pada `DELETE`.

### B. Checklist Kesiapan Teknis
- [ ] Seluruh skrip DML (`INSERT`, `UPDATE`, `DELETE`, `TRUNCATE`) berhasil dieksekusi tanpa melanggar *primary key*, *unique*, atau *check constraint*.
- [ ] Hasil tampilan tabel kueri `SELECT` verifikasi cocok persis dengan target grid ASCII di atas.
- [ ] `AkademikDB.dbo.Santri` siap dengan 8 santri aktif dan `dbo.SantriCalonBaru` dalam kondisi bersih untuk digunakan pada **Modul 07**.

> **Catatan Buku Pegangan Pengajar:**
> Kunci jawaban kueri T-SQL lengkap, analisis eksekusi baris, dan pembahasan konseptual mendalam untuk seluruh butir latihan soal dihimpun secara terpisah pada dokumen pegangan instruktur ([KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md)) agar modul praktikum tetap dapat digunakan sebagai sarana latihan mandiri yang menantang bagi peserta didik.

---

## 8. Ringkasan Perintah & Refleksi Pemahaman

| Perintah DML | Kegunaan Inti | Bagian Kritis | Efek pada `IDENTITY` & `.ldf` |
| :--- | :--- | :--- | :--- |
| `INSERT INTO ... VALUES` | Tambah 1 atau beberapa baris manual | Daftar kolom eksplisit (lewati `IDENTITY`) | `IDENTITY` naik `+1` per baris |
| `INSERT INTO ... SELECT` | Salin data antar-tabel massal | Jumlah & tipe kolom tujuan = kolom `SELECT` | `IDENTITY` naik sesuai jumlah baris masuk |
| `SELECT col1, col2` | Membaca data kolom secara spesifik | Hindari `SELECT *`; gunakan `WHERE` seperlunya | Hanya membaca (*Read-Only*) |
| `UPDATE ... SET ... WHERE` | Mengubah nilai baris yang ada | **Wajib `WHERE`** agar tidak menimpa semua baris | `IDENTITY` tidak berubah; dicatat penuh ke `.ldf` |
| `DELETE FROM ... WHERE` | Hapus baris tertentu (atau semua baris) | **Wajib `WHERE`** bila hapus sebagian | `IDENTITY` **tidak reset**; dicatat per baris ke `.ldf` |
| `TRUNCATE TABLE` | Kosongkan seluruh tabel secara instan | Tidak boleh ada `WHERE` & bebas `FOREIGN KEY` | `IDENTITY` **reset ke seed (`1`)**; log `.ldf` minimal |

### Pertanyaan Refleksi
1. *Mengapa seorang DBA sangat melarang menjalankan `UPDATE` atau `DELETE` di database produksi tanpa terlebih dahulu menguji kondisinya memakai `SELECT ... WHERE ...`?*
2. *Dari penerapan di `TokoRitelDB` (`StagingProducts` vs `Products`) dan `AkademikDB` (`SantriCalonBaru` vs `Santri`), pola tabel seperti apa yang tepat dibersihkan dengan `TRUNCATE TABLE`, dan pola tabel seperti apa yang wajib memakai `DELETE`?*
