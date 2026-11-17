# Silabus Kurikulum Microsoft SQL Server (2019 / 2022) — Zero to Advanced

Kurikulum komprehensif berbasis praktik (*Hands-On First*) dari tahap instalasi hingga administrasi produksi, optimasi kueri (*Query Tuning*), dan pemulihan bencana (*Disaster Recovery*) menggunakan **Microsoft SQL Server 2019/2022** dan **Transact-SQL (T-SQL)**.

---

## 1. Profil Kurikulum & Kalibrasi Jalur Belajar

| Komponen | Spesifikasi |
| :--- | :--- |
| **Platform & Bahasa** | Microsoft SQL Server 2019 / 2022, Transact-SQL (T-SQL) |
| **Perangkat Lunak (Tooling)** | SQL Server Management Studio (SSMS v19/v20), Azure Data Studio, SQL Server Configuration Manager |
| **Total Cakupan** | **7 Tingkat Penguasaan** — **24 Modul Pertemuan** |
| **Target Peserta** | Siswa SMK (RPL/TKJ/SIJA) & Mahasiswa Informatika / Sistem Informasi |
| **Penanda Jalur** | **`[INTI]`** (Wajib SMK & Mahasiswa) \| **`[INTI LANJUT]`** (SMK Lanjutan & Mahasiswa) \| **`[PENDALAMAN]`** (Mahasiswa & Pengayaan SMK) |

### Peta 3 Tema Praktikum Berkelanjutan (*Persistent Lab Tracks*)

Seluruh modul mengunci 3 skema basis data yang bertumbuh secara kumulatif lintas level agar peserta fokus pada mekanika T-SQL dan arsitektur mesin basis data:

| Jalur Tema | Nama Database | Peran dalam Modul | Domain & Cakupan Berkelanjutan |
| :--- | :--- | :--- | :--- |
| **Tema 1 (Inti)** | `TokoRitelDB` | Contoh Terpandu (Bagian 4) & Latihan Level 1–2 | Manajemen produk, kategori, pelanggan, pesanan, stok, dan transaksi kasir ritel |
| **Tema 2 (Transfer)** | `AkademikDB` | Latihan Level 3 (Transfer Konsep & Milestone) | Manajemen santri/mahasiswa, pengajar, mata pelajaran/kuliah, jadwal, dan penilaian |
| **Tema 3 (Pendalaman)** | `LogistikAuditDB` | Latihan Level 4 (Edge Case, Concurrency, Tuning, DR) | Gudang logistik, audit trail transaksi bervolume tinggi, pengujian indeks, dan pemulihan bencana |

---

## 2. Peta Jalan 7 Tingkat Penguasaan

```text
┌────────────────────────────────────────────────────────────────────────┐
│ LEVEL 1: Instalasi, Konfigurasi Instance, & Client Tooling   (Mod 1–2) │
├────────────────────────────────────────────────────────────────────────┤
│ LEVEL 2: Arsitektur Storage Engine, DDL, & Integritas        (Mod 3–5) │
├────────────────────────────────────────────────────────────────────────┤
│ LEVEL 3: DML Fundamental, Filtering, Aggregasi, & Joins      (Mod 6–9) │
├────────────────────────────────────────────────────────────────────────┤
│ LEVEL 4: Logika Pemrograman T-SQL (SP, UDF, Views, Triggers)(Mod 10–14)│
├────────────────────────────────────────────────────────────────────────┤
│ LEVEL 5: Transaksi, ACID, Concurrency, & Deadlock           (Mod 15–17)│
├────────────────────────────────────────────────────────────────────────┤
│ LEVEL 6: Indexing B-Tree, Execution Plan, & Query Tuning    (Mod 18–21)│
├────────────────────────────────────────────────────────────────────────┤
│ LEVEL 7: Keamanan, Backup Strategies, & Disaster Recovery   (Mod 22–24)│
└────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Rincian Silabus Terstruktur (24 Pertemuan)

### LEVEL 1: Instalasi, Konfigurasi Instance, & Client Tooling

| Modul | Judul Pertemuan | Jalur | Materi Pokok & Subtopik Teknis | Target Praktikum / Verifikasi |
| :---: | :--- | :---: | :--- | :--- |
| **01** | **Arsitektur Edisi & Instalasi SQL Server** *(Fondasi)* | `[INTI]` | Konsep Client-Server & Database Engine; perbandingan edisi (Express, Developer, Standard, Enterprise); arsitektur *Default Instance* vs *Named Instance*; tata letak direktori Data (`.mdf`), Log (`.ldf`), & `tempdb`; *Collation* (`CI_AS` vs `CS_AS`); *Service Accounts*. | Instalasi bersih SQL Server Developer/Express, cek layanan di `services.msc`, serta kueri verifikasi 1 baris pertama (`SELECT @@VERSION;`, `SELECT @@SERVERNAME;`). |
| **02** | **Autentikasi, Jaringan, & Client Tooling** *(Fondasi)* | `[INTI]` | Mixed Mode Authentication (Windows Auth vs SQL Auth `sa`); SQL Server Configuration Manager (TCP/IP, static port `1433`, SQL Server Browser); setup SSMS v19/v20, Azure Data Studio, & `sqlcmd`; verifikasi koneksi lokal dan pengguna aktif. | Aktivasi TCP/IP & Mixed Mode lewat GUI, koneksi perdana dari SSMS & `sqlcmd`, serta kueri 1 baris (`SELECT SUSER_SNAME();`). |

---

### LEVEL 2: Arsitektur Storage Engine, DDL, & Integritas Relasional

| Modul | Judul Pertemuan | Jalur | Materi Pokok & Subtopik Teknis | Milestone 3 Tema |
| :---: | :--- | :---: | :--- | :--- |
| **03** | **Storage Engine & Penciptaan Database** | `[INTI]` | Struktur penyimpanan fisik: Data Pages (`8 KB`), Extents (`64 KB`: Uniform vs Mixed); peran file `.mdf` (primary), `.ndf` (secondary), dan `.ldf` (transaction log); Filegroups; sintaks `CREATE DATABASE` serta konfigurasi *initial size* dan *autogrowth* yang sehat. | Inisialisasi fisik `TokoRitelDB`, `AkademikDB`, dan `LogistikAuditDB`. |
| **04** | **Tipe Data Presisi Transact-SQL** | `[INTI]` | Integer (`TINYINT`, `SMALLINT`, `INT`, `BIGINT`); String tetap vs dinamis dan non-Unicode vs Unicode (`CHAR`, `VARCHAR`, `VARCHAR(MAX)` vs `NCHAR`, `NVARCHAR`); angka moneter/presisi (`DECIMAL(p,s)` vs `MONEY` vs `FLOAT`); tanggal modern (`DATE`, `TIME`, `DATETIME2(3)` vs warisan `DATETIME`); identifier unik (`UNIQUEIDENTIFIER`, `NEWID()`, `NEWSEQUENTIALID()`). | Audit efisiensi ukuran baris (*row size*) untuk skema tabel utama di Tema 1–3. |
| **05** | **DDL Idempotent & Data Integrity Constraints** | `[INTI]` | Penulisan skrip idempotent (`IF OBJECT_ID(..., 'U') IS NOT NULL`); `CREATE TABLE`, `ALTER TABLE`; `PRIMARY KEY`, `FOREIGN KEY` beserta referential actions (`ON DELETE CASCADE`, `SET NULL`, `NO ACTION`), `UNIQUE`, `CHECK`, `DEFAULT`, dan `IDENTITY`. | Membangun skema relasional lengkap `TokoRitelDB` (`Customers`, `Products`, `Orders`, `OrderItems`), `AkademikDB`, dan `LogistikAuditDB`. |

---

### LEVEL 3: DML Fundamental, Filtering, Aggregasi, & Multi-Table Joins

| Modul | Judul Pertemuan | Jalur | Materi Pokok & Subtopik Teknis | Milestone 3 Tema |
| :---: | :--- | :---: | :--- | :--- |
| **06** | **Operasi CRUD Dasar & Pembersihan Tabel** | `[INTI]` | `INSERT INTO ... VALUES`, `INSERT INTO ... SELECT`, `UPDATE` dengan `WHERE` wajib, `DELETE` vs `TRUNCATE TABLE` (dampak pada transaction log, lock, dan reset nilai `IDENTITY`). | Mengisi data awal (*seeder*) idempotent dan simulasi pembaruan stok/nilai pada Tema 1–2. |
| **07** | **Filtering, Pencarian Pola, `NULL`, & Pagination** | `[INTI]` | Klausa `WHERE`, operator logika (`AND`, `OR`, `NOT`), `BETWEEN`, `IN`, `LIKE` dengan wildcard (`%`, `_`, `[ ]`); penanganan `NULL` (`IS NULL`, `IS NOT NULL`, `ISNULL()`, `COALESCE()`); pengurutan `ORDER BY` dan pagination modern `OFFSET ... FETCH NEXT`. | Fitur pencarian katalog & halaman daftar pesanan pada `TokoRitelDB` dan daftar peserta didik pada `AkademikDB`. |
| **08** | **Agregasi & Pengelompokan Data** | `[INTI]` | Urutan pemrosesan logis kueri SQL; fungsi agregat (`COUNT(*)`, `COUNT(col)`, `SUM`, `AVG`, `MIN`, `MAX`); pengelompokan `GROUP BY` dan penyaringan hasil agregasi dengan `HAVING` (vs `WHERE`). | Laporan omzet harian/kategori pada `TokoRitelDB` & rekap SKS/nilai rata-rata pada `AkademikDB`. |
| **09** | **Multi-Table Joins, `APPLY`, CTE, & Window Functions** | `[INTI]` / `[INTI LANJUT]` | `INNER JOIN`, `LEFT/RIGHT OUTER JOIN`, `FULL OUTER JOIN`, `CROSS JOIN` (matriks/dummy data); `CROSS APPLY` & `OUTER APPLY` untuk ekspresi table-valued berkorelasi; pengenalan `WITH` (CTE) dan Window Functions (`ROW_NUMBER()`, `RANK()`) sebagai solusi *set-based* pengganti loop. | Laporan detail faktur multi-tabel (`TokoRitelDB`), transkrip akademik (`AkademikDB`), dan riwayat mutasi gudang terbaru per barang (`LogistikAuditDB`). |

---

### LEVEL 4: Logika Pemrograman T-SQL (SP, Functions, Views, & Triggers)

| Modul | Judul Pertemuan | Jalur | Materi Pokok & Subtopik Teknis | Milestone 3 Tema |
| :---: | :--- | :---: | :--- | :--- |
| **10** | **Variabel Lokal & Kontrol Alur T-SQL** | `[INTI]` | Deklarasi variabel (`DECLARE @var`), penugasan `SET` vs `SELECT`, percabangan `IF ... ELSE`, perulangan `WHILE` (`BREAK`, `CONTINUE`), serta ekspresi kondisional `CASE WHEN ... THEN ... ELSE ... END`. | Skrip klasifikasi tier pelanggan (`TokoRitelDB`) & predikat kelulusan (`AkademikDB`). |
| **11** | **Stored Procedures Produksi** | `[INTI]` | `CREATE OR ALTER PROCEDURE`, `SET NOCOUNT ON` (reduksi overhead network round-trip), parameter input wajib & bernilai default, parameter `OUTPUT`, `RETURN` codes, serta pencegahan SQL Injection dengan eksekusi terparameter. | `usp_ProsesPesanan` (`TokoRitelDB`) dan `usp_InputNilai` (`AkademikDB`). |
| **12** | **User-Defined Functions (UDF): Scalar vs iTVF vs mTVF** | `[INTI LANJUT]` / `[PENDALAMAN]` | Scalar UDF (karakteristik & bahaya performa *row-by-row overhead*), Inline Table-Valued Functions (`iTVF` — bekerja seperti *parameterized view* yang di-inline Query Optimizer), dan Multi-Statement TVF (`mTVF` — masalah estimasi kardinalitas). | Perbandingan performa fungsi hitung diskon/pajak dan pencarian riwayat transaksi pada `TokoRitelDB` & `LogistikAuditDB`. |
| **13** | **Views: Standard, Schemabound, & Indexed Views** | `[INTI]` | Standard Views untuk abstraksi dan pembatasan akses kolom; `WITH SCHEMABINDING` untuk mengunci dependensi skema; Indexed Views (Materialized Views) dan syarat ketatnya (`SET` options, `COUNT_BIG(*)`, tanpa `SELECT *`). | View ringkasan penjualan (`TokoRitelDB`), rekap KRS (`AkademikDB`), dan agregasi stok gudang terindeks (`LogistikAuditDB`). |
| **14** | **Triggers & Mekanisme Audit Otomatis** | `[INTI LANJUT]` / `[PENDALAMAN]` | `AFTER` Triggers (`INSERT`, `UPDATE`, `DELETE`); pemanfaatan tabel virtual memori `inserted` dan `deleted`; `INSTEAD OF` Triggers untuk view kompleks; pengendalian *nested* dan *recursive triggers*. | Audit perubahan harga pada `TokoRitelDB` dan pencatatan jejak audit otomatis pada `LogistikAuditDB`. |

---

### LEVEL 5: Transaksi, ACID, Concurrency Control, & Deadlock Management

| Modul | Judul Pertemuan | Jalur | Materi Pokok & Subtopik Teknis | Milestone 3 Tema |
| :---: | :--- | :---: | :--- | :--- |
| **15** | **Prinsip ACID, Transaksi Eksplisit, & Error Handling Modern** | `[INTI]` | Mekanisme Write-Ahead Logging (WAL) pada file `.ldf` untuk menjamin ACID; `BEGIN TRANSACTION`, `COMMIT`, `ROLLBACK`; `SET XACT_ABORT ON`; blok `BEGIN TRY ... BEGIN CATCH` dengan `ERROR_NUMBER()`, `ERROR_MESSAGE()`, `ERROR_LINE()`, `@@TRANCOUNT`, dan `THROW 50000`. | Stored Procedure checkout atomik pengurangan stok + pencatatan order (`TokoRitelDB`) & transfer mutasi antar-gudang (`LogistikAuditDB`). |
| **16** | **Transaction Isolation Levels & Row Versioning** | `[PENDALAMAN]` | `READ UNCOMMITTED` (bahaya `NOLOCK` & Dirty Reads); `READ COMMITTED` (default, Non-Repeatable Reads); `REPEATABLE READ`; `SERIALIZABLE` (range locks); serta `SNAPSHOT` dan `READ COMMITTED SNAPSHOT ISOLATION (RCSI)` berbasis `tempdb`. | Eksperimen 2 sesi di SSMS mengamati blocking vs row versioning pada `TokoRitelDB` dan `LogistikAuditDB`. |
| **17** | **Locking Mechanics & Deadlock Management** | `[PENDALAMAN]` | Tipe kunci (Shared `S`, Exclusive `X`, Update `U`, Intent `IS`/`IX`); penggunaan hint aman `WITH (UPDLOCK, ROWLOCK)`; melacak blocking via `sys.dm_exec_requests` & `sys.dm_os_waiting_tasks`; simulasi deadlock 2 sesi; `SET DEADLOCK_PRIORITY LOW`; pembacaan deadlock graph via Extended Events. | Simulasi dan resolusi deadlock pada pembaruan stok bersamaan di `LogistikAuditDB`. |

---

### LEVEL 6: Indexing B-Tree, Analisis Execution Plan, & Query Tuning

| Modul | Judul Pertemuan | Jalur | Materi Pokok & Subtopik Teknis | Milestone 3 Tema |
| :---: | :--- | :---: | :--- | :--- |
| **18** | **Arsitektur Index B-Tree: Clustered vs Non-Clustered** | `[INTI LANJUT]` | Struktur Root, Intermediate, dan Leaf pages; karakteristik kunci Clustered ideal (Unique, Narrow, Static, Ever-Increasing); Heap Table (RID Lookup) vs Clustered Table (Key Lookup). | Desain kunci Clustered dan indeks FK pada tabel bervolume 100.000+ baris di `LogistikAuditDB`. |
| **19** | **Optimasi Index Lanjutan (`INCLUDE` & Filtered)** | `[PENDALAMAN]` | Composite Index (urutan kolom selektif); Covering Index dengan `INCLUDE` (menghindari batas 1700 byte key & menghilangkan Key Lookup); Filtered Index (`WHERE IsDeleted = 0` atau `WHERE Status = 'Pending'`). | Pembuatan Covering Index dan Filtered Index pada tabel transaksi `TokoRitelDB` & `LogistikAuditDB`. |
| **20** | **Analisis Execution Plan & Metrik I/O** | `[PENDALAMAN]` | Membaca Actual Execution Plan (`Ctrl + M`); membedakan operator `Index Seek`, `Index Scan`, `Table Scan`, `Key Lookup`, `Nested Loops`, `Hash Match`, dan `Sort`; membandingkan Estimated vs Actual Rows; membaca `SET STATISTICS IO ON` (Logical/Physical Reads) & `SET STATISTICS TIME ON`. | Benchmark pengurangan *Logical Reads* sebelum dan sesudah optimasi indeks pada `LogistikAuditDB`. |
| **21** | **Kueri SARGable & Tuning Berbasis DMV** | `[PENDALAMAN]` | Prinsip *Search Argument Able* (menghindari fungsi pembungkus kolom di `WHERE`, mis. `YEAR(OrderDate)` $\rightarrow$ rentang tanggal); bahaya *Implicit Conversion* (`VARCHAR` vs `NVARCHAR`); analisis kueri terberat & rekomendasi indeks via DMV (`sys.dm_exec_query_stats`, `sys.dm_db_missing_index_details`, `sys.dm_db_index_physical_stats`). | Refactor kueri non-SARGable dan audit DMV pada `LogistikAuditDB`. |

---

### LEVEL 7: Administrasi Keamanan, Backup Strategies, & Disaster Recovery

| Modul | Judul Pertemuan | Jalur | Materi Pokok & Subtopik Teknis | Milestone 3 Tema |
| :---: | :--- | :---: | :--- | :--- |
| **22** | **Arsitektur Keamanan Bertingkat & Prinsip Least Privilege** | `[INTI LANJUT]` / `[PENDALAMAN]` | Server Logins (Windows Auth vs SQL Auth) & Fixed Server Roles (`sysadmin`, `serveradmin`); Database Users & Fixed/Custom Database Roles (`db_datareader`, `db_datawriter`, `db_owner`); kontrol akses granular tingkat objek/skema (`GRANT`, `DENY`, `REVOKE`). | Pembuatan role kasir (`TokoRitelDB`), staf akademik (`AkademikDB`), dan auditor read-only (`LogistikAuditDB`). |
| **23** | **Recovery Models & Strategi Backup Berantai** | `[PENDALAMAN]` | Perbandingan model `SIMPLE`, `FULL`, dan `BULK_LOGGED`; manajemen pertumbuhan file `.ldf`; skrip Full Backup (`WITH INIT, COMPRESSION`), Differential Backup (`WITH DIFFERENTIAL`), dan Transaction Log Backup (`BACKUP LOG`). | Eksekusi jadwal rantai backup Full + Differential + Log pada `LogistikAuditDB`. |
| **24** | **Disaster Recovery, Point-in-Time Restore, & Otomasi Maintenance** | `[PENDALAMAN]` | Simulasi insiden kehilangan data; Tail-Log Backup (`WITH NORECOVERY`); urutan restore berantai dengan `WITH NORECOVERY`, `STOPAT`, dan `RECOVERY`; SQL Server Agent (Jobs, Schedules, Operators, Alerts); pemeliharaan indeks (Reorganize 10%–30% vs Rebuild >30%) serta uji integritas `DBCC CHECKDB`. | Simulasi pemulihan *Point-in-Time Recovery (PITR)* hingga 10 detik sebelum kesalahan penghapusan data pada `LogistikAuditDB`. |

---

## 4. Standar Anatomi Setiap Modul Praktikum (Format Tutor 8 Bagian)

Saat silabus ini diturunkan menjadi dokumen modul ajar per pertemuan, setiap modul disusun secara bertahap agar tidak melompati sintaks yang belum dipelajari peserta:

- **Level 1 (Modul 01–02 — Pendekatan Fondasi):** Fokus membangun *mental model*, panduan GUI/installer langkah demi langkah, studi kasus kebutuhan 3 tema, dan kueri verifikasi 1 baris yang dijelaskan kata demi kata.
- **Level 2–7 (Modul 03–24 — Praktikum Sintaks & Arsitektur):** Menggunakan skrip idempotent 3 tema dan 4 contoh kode bertingkat sesuai sintaks yang sudah dikuasai.

Struktur 8 bagian setiap modul:
1. **Prasyarat, Jembatan Konsep, & Peta Alur** — 2 soal kilat prasyarat, penjelasan *Why Before How*, dan diagram alur ASCII.
2. **Kamus Konsep, Istilah, & Perintah Dasar** — Penjelasan istilah/fungsi/klausa, parameter wajib/opsional, dan contoh mikro 1–3 baris sebelum masuk praktik.
3. **Persiapan Lingkungan & Skema Praktikum** — Checklist skenario kebutuhan (Level 1) atau skrip idempotent & data uji (Level 2–7) untuk `TokoRitelDB`, `AkademikDB`, dan `LogistikAuditDB`.
4. **Contoh Praktik Bertingkat (Studi Kasus `TokoRitelDB`)** — Kenaikan 1 konsep per tahap/contoh: Kasus Dasar $\rightarrow$ Variasi Parameter + *State Tracing* $\rightarrow$ Kombinasi + *Step Trace* $\rightarrow$ Penerapan Produksi / Kasus Batas *(Pendalaman)*.
5. **Verifikasi Output & Panduan Troubleshooting** — Tabel ekspektasi output dan tabel kesalahan umum.
6. **Latihan Soal & Praktikum Berkelanjutan (4 Level, 10 Soal)** — Level 1 & Level 2 pada `TokoRitelDB`; Level 3 pada `AkademikDB`; Level 4 pada `LogistikAuditDB` *(Pendalaman)*.
7. **Cek Mandiri & Panduan Verifikasi** — Indikator hasil Level 1–2 dan checklist kesiapan milestone Tema 2–3.
8. **Ringkasan & Refleksi Pemahaman** — Tabel ringkasan konsep/perintah kritis dan pertanyaan refleksi transfer lintas tema.

---

## 5. Penyesuaian Alokasi Target Peserta

| Jalur Peserta | Cakupan Modul Wajib | Penekanan Evaluasi |
| :--- | :--- | :--- |
| **SMK (Dasar – Menengah)** | Modul 01–11, Modul 13 (Standard View), Modul 15 (Transaksi Dasar), Modul 22 (Dasar) | Ketepatan sintaks T-SQL, integritas relasional (`PK`/`FK`), operasi CRUD, JOIN, Stored Procedure dasar, dan penyelesaian Level 1–3 (`TokoRitelDB` & `AkademikDB`). |
| **Mahasiswa / Profesional Junior** | Seluruh Modul 01–24 (`[INTI]` + `[INTI LANJUT]` + `[PENDALAMAN]`) | Seluruh capaian SMK ditambah desain UDF efisien (`iTVF`), *Isolation Levels*, resolusi *Deadlock*, optimasi *Covering/Filtered Index*, analisis *Execution Plan* & SARGable, serta simulasi *Point-in-Time Recovery*. |
