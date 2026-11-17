# Buku Kunci Jawaban & Pembahasan Latihan Soal Microsoft SQL Server (2019 / 2022)
*(Dokumen Pegangan Instruktur / Guru)*

Dokumen ini menghimpun seluruh kunci jawaban kueri Transact-SQL (T-SQL), skrip administrasi terminal PowerShell/`sqlcmd`, arsitektur konfigurasi, serta pembahasan teknis mendalam untuk setiap latihan soal di modul praktikum **Microsoft SQL Server (2019 / 2022)**. 

Pemisahan dokumen ini dari modul siswa bertujuan untuk menjaga integritas proses pembelajaran aktif mandiri (*hands-on practice*), sehingga peserta didik jenjang SMK dan Mahasiswa dapat melatih kemampuan analisis sintaks dan pencocokan visual output tabel secara mandiri.

---

## Daftar Isi Modul

1. [Modul 01: Arsitektur Edisi, Instance, & Instalasi Bersih Microsoft SQL Server](#modul-01-arsitektur-edisi-instance--instalasi-bersih-microsoft-sql-server)
2. [Modul 02: Autentikasi Mixed Mode, Jaringan TCP/IP, & Client Tooling](#modul-02-autentikasi-mixed-mode-jaringan-tcpip--client-tooling)
3. [Modul 06: Operasi CRUD, Pemindahan Data Antar-Tabel, & Pembersihan Objek](#modul-06-operasi-crud-pemindahan-data-antar-tabel--pembersihan-objek)
*(Kunci jawaban modul-modul lainnya akan ditambahkan bertahap seiring rilis modul pada kurikulum 24 pertemuan).*

---

## Modul 01: Arsitektur Edisi, Instance, & Instalasi Bersih Microsoft SQL Server
**Tautan Modul Siswa:** [modul-01-arsitektur-edisi-instalasi.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/modul-01-arsitektur-edisi-instalasi.md)

### Tabel Acuan Konfigurasi Praktik & Studi Kasus Arsitektur
| Nama Sistem / Lab | Edisi Target | Tipe Instance | Collation Standar | Service Account | Skenario Kebutuhan & Beban Data |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `TokoRitelDB` | Developer / Express | Default (`MSSQLSERVER`) | `SQL_Latin1_General_CP1_CI_AS` | `NT Service\MSSQLSERVER` | Lab kasir ritel mandiri, pencarian teks tidak peka huruf besar/kecil. |
| `AkademikDB` | Developer (Lab) | Named (`AKADEMIK2022`) | `SQL_Latin1_General_CP1_CI_AS` | `NT Service\MSSQL$AKADEMIK2022` | Lab akademik 15.000 mahasiswa, jadwal job backup otomatis tanpa lisensi komersial. |
| `LogistikAuditDB` | Standard / Enterprise | Default (`MSSQLSERVER`) | `SQL_Latin1_General_CP1_CI_AS` | Akun Domain Dedicated | Server produksi 50 GB/tahun, Buffer Pool > 10 GB, pemeliharaan otomatis harian. |
| `LabPC-Kasir01` | Express | Named (`TOKO`) | `SQL_Latin1_General_CP1_CI_AS` | `NT Service\MSSQL$TOKO` | Komputer klien kasir cabang lokal dengan kapasitas data kecil. |

---

### Kunci Jawaban & Pembahasan Mendalam Soal 1–8

#### Soal 1: Menampilkan Versi Lengkap Mesin SQL Server Aktif
```sql
SELECT @@VERSION AS InformasiVersiSQL;
```
*Pembahasan Pedagogis:*
Variabel sistem global `@@VERSION` mengembalikan satu baris teks bertipe `NVARCHAR` yang merinci: nomor build rilis produk (misalnya `16.0.1000.6` untuk SQL Server 2022 RTM), arsitektur prosesor (`X64`), edisi yang terpasang (`Developer Edition` / `Express Edition`), tingkat pembaruan kumulatif (CU), dan versi sistem operasi Windows host.

#### Soal 2: Menampilkan Nama Instance dan Nama Layanan Windows
```sql
SELECT 
    @@SERVERNAME AS AlamatKoneksi,
    @@SERVICENAME AS NamaLayanan;
```
*Pembahasan Pedagogis:*
- Pada komputer dengan **Default Instance**, `@@SERVERNAME` bernilai nama komputer (misalnya `DESKTOP-LAB`) dan `@@SERVICENAME` bernilai bawaan `MSSQLSERVER`.
- Pada komputer dengan **Named Instance** bernama `TOKO` di komputer `PC-KASIR01`, `@@SERVERNAME` menghasilkan format majemuk `PC-KASIR01\TOKO` dan `@@SERVICENAME` menghasilkan string nama instance saja (`TOKO`). Nilai ini identik dengan nama service yang terdaftar di Windows Service Control Manager (`services.msc`), yaitu `MSSQL$TOKO`.

#### Soal 3: Koreksi Penulisan Kueri Versi Mesin SQL
```sql
SELECT @@VERSION AS VersiMesinSQL;
```
*Pembahasan Pedagogis:*
Kesalahan pada kode awal adalah: (1) kata kunci sistem `VERSION` tidak diawali dengan tanda `@@` ganda sehingga parser SQL Server mengira `VERSION` adalah nama kolom tabel, dan (2) ketiadaan titik koma (`;`) penutup statement T-SQL standar. Perbaikan dilakukan dengan menyematkan prefix `@@` dan penutup statement.

#### Soal 4: Memeriksa Status Layanan Windows Terkait SQL Server di PowerShell
```powershell
Get-Service *SQL* | Select-Object Status, Name, DisplayName
```
*Pembahasan Pedagogis:*
Perintah cmdlet PowerShell ini melakukan penyaringan terhadap seluruh Windows Services yang mengandung kata kunci `SQL`. Layanan inti Database Engine default memiliki `Name = MSSQLSERVER` (atau `MSSQL$<NamaInstance>`). Sebelum client tool (SSMS) dapat berkomunikasi dengan mesin database, kolom `Status` wajib bernilai `Running`. Jika bernilai `Stopped`, administrator dapat mengaktifkannya via `Start-Service MSSQLSERVER`.

#### Soal 5: Menggabungkan Tiga Informasi Sistem dalam Satu Baris Hasil
```sql
SELECT 
    @@SERVERNAME AS AlamatServer,
    @@SERVICENAME AS NamaLayanan,
    @@VERSION AS VersiSQL;
```
*Pembahasan Pedagogis:*
Kueri ini menunjukkan karakteristik klausa `SELECT` sebagai evaluator ekspresi skalar murni tanpa tabel sumber (`FROM`). Ketiga variabel sistem dievaluasi secara simultan dalam satu siklus eksekusi dan diproyeksikan menjadi tabel baris tunggal dengan 3 kolom terpisah.

#### Soal 6: Menentukan Alamat Server Name di SSMS untuk 3 Skenario
- **Skenario A (Komputer `LAB-05`, Default Instance):**
  `LAB-05` atau `localhost` (atau cukup tanda titik `.`)
- **Skenario B (Komputer `LAB-05`, Named Instance `RITEL2022`):**
  `LAB-05\RITEL2022` (atau `.\RITEL2022` jika diakses dari komputer lokal)
- **Skenario C (Default Instance di komputer sendiri tanpa nama PC):**
  `.` atau `localhost` atau `(local)`
*Pembahasan Pedagogis:*
Default Instance mendengarkan port statis `1433` tanpa memerlukan kualifikasi nama instance tambahan. Sebaliknya, Named Instance wajib menyertakan backslash (`\`) diikuti nama instance agar Client Net-Library (SNI) dapat mengarahkan paket TDS ke port yang tepat melalui SQL Server Browser Service atau konfigurasi port statis.

#### Soal 7: Keputusan Konfigurasi Sistem `AkademikDB` di Laboratorium Kampus
- **Rekomendasi Edisi:** **Developer Edition**
  *Alasan:* Menyediakan kapabilitas teknis penuh setara Enterprise Edition tanpa batasan RAM dan ukuran database 10 GB, serta menyertakan layanan **SQL Server Agent** aktif yang dibutuhkan untuk simulasi backup otomatis berkala, dengan lisensi gratis khusus riset dan pembelajaran.
- **Tipe Instance:** **Named Instance** (misal `AKADEMIK2022` atau `AKADEMIK`)
  *Alasan:* Menjaga isolasi mesin database lab kampus agar tidak bertabrakan dengan instance default sistem lain di komputer lab bersama.
- **Collation:** `SQL_Latin1_General_CP1_CI_AS`
  *Alasan:* Aturan `CI` (*Case-Insensitive*) memastikan kueri pencarian nama mahasiswa (misal `'Ahmad'` dan `'ahmad'`) menghasilkan kesesuaian data yang diharapkan, sedangkan `AS` (*Accent-Sensitive*) menjaga integritas tanda aksen internasional.
- **Service Account:** Akun Virtual Service bawaan Windows (`NT Service\MSSQL$AKADEMIK2022`)
  *Alasan:* Menerapkan prinsip isolasi hak akses minimal (*Least Privilege*) tanpa memerlukan pemeliharaan rotasi sandi manual.

#### Soal 8: Analisis Batasan Lisensi, Skalabilitas Produksi, & Rancangan Multi-Volume Disk
- **Analisis Ketidaklayakan Edisi:**
  1. *Express Edition Tidak Layak Secara Teknis:* Memiliki hard limit ukuran maksimal database sebesar **10 GB per database**, batas memori buffer pool sebesar **1.410 MB (~1,4 GB)**, dan **tidak memiliki layanan SQL Server Agent**. Proyeksi data 50 GB pada tahun pertama akan melumpuhkan database seketika saat menyentuh limit 10 GB.
  2. *Developer Edition Tidak Layak Secara Hukum (Legalitas):* Secara teknis fiturnya identik dengan Enterprise, namun lisensi Microsoft EULA menegaskan bahwa Developer Edition **hanya boleh digunakan untuk pengembangan, pengujian, dan pembelajaran**, dilarang keras digunakan untuk melayani operasional produksi komersial (*production environment*).
  3. *Rekomendasi:* Menggunakan **SQL Server Standard Edition** (atau Enterprise Edition bila membutuhkan fitur Disaster Recovery tingkat lanjut).
- **Rancangan Peta 4 Volume Disk Fisik Produksi:**
  1. `C:\` (Volume OS & Binary): Menampung Windows Server dan file program biner SQL Server (`C:\Program Files\Microsoft SQL Server`). Pola I/O: *Read-mostly* dan OS paging. Menjamin OS tidak macet bila disk database penuh.
  2. `D:\SQLData\` (Volume Data `.mdf` / `.ndf`): Khusus menyimpan file data utama. Pola I/O: *Random Read & Write*. Dioptimalkan dengan konfigurasi RAID 10 atau SSD NVMe untuk performa throughput IOPS tinggi.
  3. `E:\SQLLogs\` (Volume Transaction Log `.ldf`): Khusus mencatat log transaksi secara kronologis. Pola I/O: *Sequential Write* murni. Memisahkan log dari data mencegah benturan head baca-tulis (*head thrashing*) dan menjamin commit transaksi cepat.
  4. `T:\SQLTempDB\` (Volume `TempDB`): Meja kerja sementara internal engine (penyortiran, hash tables, version store). Pola I/O: *Extremely High-Churn Temporary I/O*. Menggunakan disk NVMe tercepat dengan format sektor disk 64 KB.

---

## Modul 02: Autentikasi Mixed Mode, Jaringan TCP/IP, & Client Tooling
**Tautan Modul Siswa:** [modul-02-autentikasi-jaringan-tooling.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/modul-02-autentikasi-jaringan-tooling.md)

### Tabel Acuan Parameter Autentikasi & Jaringan Praktik
| Identitas Target / PC | Mode Autentikasi | Port TCP / Protokol | Akun Login Aktif | Target Posisi Database | Keterangan Endpoint & Keamanan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `TOKO-PC\Kasir01` | Windows Authentication | Lokal / Shared Memory | `TOKO-PC\Kasir01` | `master` | Login kasir lokal terintegrasi akun OS Windows. |
| `localhost` (sa) | SQL Server Authentication | TCP `1433` (Statis) | `sa` | `master` | Akun System Administrator untuk pemeliharaan server lokal. |
| `SRV-KAMPUS\AKADEMIK` | Mixed Mode | TCP Port Statis / Dinamis | `sa` / `AkunSiswa` | `AkademikDB` | Server lab kampus diakses 30 komputer klien via LAN (`192.168.10.10`). |
| `SRV-LOGISTIK` | Windows Auth / Mixed | TCP `1433` (Firewall Whitelist) | `app_logistik_user` | `LogistikAuditDB` | Server produksi logistik dengan isolasi port dan hak akses terendah. |

---

### Kunci Jawaban & Pembahasan Mendalam Soal 1–10

#### Soal 1: Menampilkan Identitas Akun Sesi Aktif dan Nama Database
```sql
SELECT 
    SUSER_SNAME() AS IdentitasMasuk,
    DB_NAME() AS PosisiDatabase;
```
*Pembahasan Pedagogis:*
- `SUSER_SNAME()` mengembalikan login name keamanan pada level instance SQL Server. Ketika masuk via Windows Authentication, hasilnya berupa format domain atau nama PC lokal (misal `'TOKO-PC\Kasir01'`). Ketika login ulang memakai SQL Authentication `sa`, nilai kolom tersebut seketika berubah menjadi `'sa'`.
- `DB_NAME()` mengembalikan nama database yang menjadi konteks eksekusi sesi saat kueri dijalankan. Secara default setelah login pertama kali, pengguna mendarat di database sistem `master`.

#### Soal 2: Diagnosis Kegagalan Login `sa` (Error 18456)
*Langkah Krusial yang Terlewat:*
1. **Belum me-restart layanan Windows SQL Server Database Engine.** Perubahan mode autentikasi dari *Windows Authentication mode* ke *SQL Server and Windows Authentication mode (Mixed Mode)* disimpan ke Registry Windows dan **hanya dimuat ke memori engine saat layanan dinyalakan ulang** (*service restart*).
2. **Status login `sa` belum diaktifkan (*Enabled*).** Secara default setelah instalasi, login `sa` berstatus *Disabled*. Administrator wajib membuka *Security* $\rightarrow$ *Logins* $\rightarrow$ klik kanan `sa` $\rightarrow$ *Properties* $\rightarrow$ menu *Status* $\rightarrow$ tandai `Login: Enabled` dan `Grant permission to connect to database engine`.

#### Soal 3: Koreksi Perintah Terminal `sqlcmd`
```powershell
sqlcmd -S localhost -U sa -P "SandiKuat_123!" -C -Q "SELECT SUSER_SNAME();"
```
*Pembahasan Pedagogis:*
Kesalahan pada sintaks awal adalah mencantumkan flag `-E` (*Trusted Connection / Windows Authentication*) bersamaan dengan flag `-U sa` (*SQL Authentication*). Kedua parameter ini saling bertentangan secara logika protokol autentikasi. Untuk login menggunakan kredensial SQL `sa`, flag `-E` harus dihapus, dan flag `-P` (kata sandi) wajib disertakan. Flag `-C` (*Trust Server Certificate*) tetap dipertahankan agar tidak terjadi galat sertifikat enkripsi.

#### Soal 4: Menetapkan Port Statis TCP 1433 pada Configuration Manager
*Langkah Konfigurasi:*
1. Buka **SQL Server Configuration Manager**.
2. Pilih **SQL Server Network Configuration** $\rightarrow$ **Protocols for MSSQLSERVER** (atau nama instance yang sesuai).
3. Klik kanan pada protokol **TCP/IP** $\rightarrow$ pilih **Properties** $\rightarrow$ buka tab **IP Addresses**.
4. Gulir hingga ke bagian paling bawah pada grup **IPAll**:
   - Hapus seluruh angka pada kotak **TCP Dynamic Ports** (pastikan benar-benar kosong).
   - Ketikkan angka `1433` pada kotak **TCP Port**.
5. Klik **Apply** lalu klik **OK**.
6. Buka menu **SQL Server Services** di panel kiri $\rightarrow$ klik kanan **SQL Server (MSSQLSERVER)** $\rightarrow$ pilih **Restart**.

#### Soal 5: Memproyeksikan Empat Informasi Lingkungan Sekaligus
```sql
SELECT 
    SUSER_SNAME() AS PenggunaAktif,
    DB_NAME() AS DatabasePosisi,
    @@SERVERNAME AS AlamatServer,
    @@SERVICENAME AS LayananWindows;
```
*Pembahasan Pedagogis:*
Kueri ini memverifikasi seluruh komponen yang dibahas pada Modul 01 dan Modul 02: konteks autentikasi aktif (`SUSER_SNAME()`), lokasi skema aktif (`DB_NAME()`), nama server host (`@@SERVERNAME`), dan nama service engine Windows (`@@SERVICENAME`).

#### Soal 6: Penanganan Pesan Error Sertifikat TLS pada Client Tooling
- **SQL Server Management Studio (SSMS v20+):**
  Pada jendela *Connect to Server* $\rightarrow$ buka tab *Options* / tab *Connection Properties* $\rightarrow$ centang kotak **Trust server certificate**.
- **Azure Data Studio (ADS):**
  Pada form pembuatan koneksi baru $\rightarrow$ bagian *Advanced* $\rightarrow$ atur opsi **Trust server certificate** menjadi bernilai `True`.
- **Utilitas Terminal `sqlcmd`:**
  Tambahkan argumen flag kapital `-C` pada baris perintah eksekusi (contoh: `sqlcmd -S localhost -C ...`).

#### Soal 7: Tiga Komponen Pendukung Akses Multi-PC pada `SRV-KAMPUS`
1. **Layanan SQL Server Browser (`UDP Port 1434`):**
   *Peran:* Menerima permintaan resolusi port dari komputer klien yang mengetikkan nama Named Instance `192.168.10.10\AKADEMIK`, lalu memberitahukan nomor port TCP aktual yang sedang didengarkan oleh instance tersebut.
2. **Protokol Jaringan TCP/IP Aktif:**
   *Peran:* Mengizinkan komunikasi antarmuka jaringan berbasis soket TCP antara komputer klien dan engine SQL Server melalui antarmuka kartu jaringan (NIC).
3. **Inbound Firewall Rules pada Windows Defender Firewall:**
   *Peran:* Membuka izin lalu lintas data masuk (*inbound traffic*) untuk port TCP SQL Server (default port `1433` atau port instance statis) dan port UDP `1434` (SQL Server Browser) agar paket koneksi dari subnet lab mahasiswa tidak diblokir oleh sistem keamanan OS server.

#### Soal 8: Verifikasi Keterbukaan Port Jaringan dengan PowerShell
```powershell
Test-NetConnection -ComputerName localhost -Port 1433
```
*Pembahasan Pedagogis:*
Cmdlet `Test-NetConnection` mengirimkan paket SYN TCP ke alamat dan port target. Port dinyatakan terbuka sempurna dan siap menerima koneksi aplikasi klien apabila baris `TcpTestSucceeded` mengembalikan nilai boolean `True`. Jika bernilai `False`, kemungkinan protokol TCP/IP masih disabled, port belum di-assign, atau service SQL Server sedang tidak berjalan.

#### Soal 9: Analisis Risiko Penyematan Akun `sa` dan Prinsip Least Privilege
*Tiga Dampak Keamanan Fatal:*
1. **Eskalasi Hak Istimewa Maksimal (*Full Sysadmin Rights*):** Akun `sa` memiliki izin penuh tak terbatas di server. Jika aplikasi web terkena celah *SQL Injection*, penyerang dapat membaca, memodifikasi, atau menghapus seluruh database di server (bukan hanya database aplikasi tersebut).
2. **Eksekusi Perintah Sistem Operasi Windows:** Pengguna berstatus sysadmin dapat mengaktifkan dan mengeksekusi extended stored procedure `xp_cmdshell` untuk menjalankan perintah command prompt Windows, mengunduh malware, atau mengambil alih server host secara fisik.
3. **Ketiadaan Jejak Audit Forensik (*Non-Repudiation Failure*):** Jika semua sistem memakai akun `sa`, tim keamanan tidak dapat melacak identitas programmer, modul, atau layanan mana yang melakukan kesalahan fatal atau transaksi ilegal karena seluruh log mencatat pengguna yang sama: `'sa'`.

*Prinsip Pengganti:* Menerapkan **Principle of Least Privilege (PoLP)** dengan membuat Login dan User khusus (misalnya `app_logistik_user`) yang hanya diberi izin data manipulation (`db_datareader`, `db_datawriter`, atau `EXECUTE` pada stored procedure tertentu) murni pada database `LogistikAuditDB`, tanpa akses ke database sistem atau hak administratif server.

#### Soal 10: Komparasi Port Statis vs Dynamic Port + SQL Server Browser
- **Pendekatan Opsi A (Dynamic Ports + Browser UDP 1434):**
  Port TCP dapat berubah-ubah setiap kali service SQL Server restart. Administrator firewall harus membuka seluruh rentang port dinamis atau memperbarui port secara berkala, serta wajib membuka port UDP 1434. Port UDP 1434 kerap menjadi target eksploitasi serangan DoS (*Denial of Service*) dan *UDP amplification attack*.
- **Pendekatan Opsi B (Static Port Tetap, matikan SQL Server Browser):**
  Port TCP dikunci permanen (misal `1433` atau port non-standar privat). Firewall hanya perlu mengizinkan satu port TCP tunggal yang sangat spesifik dari alamat IP sumber yang terdaftar (*IP Whitelisting*). Layanan SQL Server Browser dapat dimatikan sepenuhnya, sehingga menyembunyikan keberadaan instance dari pemindaian liar jaringan.
- **Kesimpulan:** **Opsi B** jauh lebih disarankan untuk server produksi karena meminimalisasi bidang serangan (*attack surface*) dan mempermudah audit keamanan jaringan.

---

## Modul 06: Operasi CRUD, Pemindahan Data Antar-Tabel, & Pembersihan Objek
**Tautan Modul Siswa:** [modul-06-operasi-crud-pembersihan-tabel.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/modul-06-operasi-crud-pembersihan-tabel.md)

### Tabel Acuan Data Praktik

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

### Kunci Jawaban & Pembahasan Mendalam Soal 1–10

#### Soal 1: Pelacakan Output Perilaku `IDENTITY` setelah `DELETE`
```sql
USE TokoRitelDB;
GO

DELETE FROM dbo.StagingProducts WHERE StageID = 3;

INSERT INTO dbo.StagingProducts (StageSKU, StageName, StagePrice, StageStock)
VALUES ('BRG-X01', N'Gula Aren 250 g', 18000.00, 10);

SELECT StageID, StageSKU 
FROM dbo.StagingProducts;
GO
```
*Hasil Keluaran:*
```text
StageID  StageSKU
-------  --------
1        BRG-006
2        BRG-007
4        BRG-X01
(3 rows affected)
```
*Pembahasan Pedagogis:*
Nilai `StageID` yang tersisa adalah `1`, `2`, dan **`4`**. Ketika baris `StageID = 3` dihapus via `DELETE`, nilai counter internal tabel `IDENTITY` **tidak dimundurkan atau direset**. SQL Server tetap mencatat bahwa angka `3` sudah pernah diterbitkan. Ketika baris `'BRG-X01'` dimasukkan, sistem menerbitkan nomor berikutnya yaitu `4`. Hal ini membuktikan bahwa `DELETE` mempertahankan integritas riwayat penomoran urut otomatis tanpa mengisi celah nomor yang hilang (*gap identity*).

#### Soal 2: Identifikasi Kesalahan Constraint pada Nilai Negatif
*Penyebab Kesalahan:*
Kueri `INSERT` tersebut gagal dan memicu pesan kesalahan:
```text
Msg 547, Level 16, State 0, Line 1
The INSERT statement conflicted with the CHECK constraint "CHK_Products_Stock". The conflict occurred in database "TokoRitelDB", table "dbo.Products", column 'StockQuantity'.
```
*Pembahasan Pedagogis:*
Pada definisi tabel `dbo.Products` (Modul 05), kolom `StockQuantity` dipasangi batasan integritas `CONSTRAINT CHK_Products_Stock CHECK (StockQuantity >= 0)`. Memasukkan nilai `-2` secara tegas ditolak oleh Database Engine untuk mencegah anomali kuantitas barang fisik yang tidak logis di dunia nyata.

#### Soal 3: Koreksi Penulisan Skrip `UPDATE` Presisi
```sql
USE TokoRitelDB;
GO

UPDATE dbo.Products
SET StockQuantity = StockQuantity + 10
WHERE ProductSKU = 'BRG-003';
GO
```
*Pembahasan Pedagogis:*
Kesalahan fatal pada skrip awal adalah **ketiadaan klausa `WHERE`**. Tanpa klausa `WHERE`, perintah `UPDATE` akan diterapkan ke **seluruh baris data** yang ada di tabel `dbo.Products`, menyebabkan semua produk mengalami penambahan stok 10 unit secara salah. Penambahan `WHERE ProductSKU = 'BRG-003';` mengunci pembaruan murni pada target satu produk Teh Celup Melati Box.

#### Soal 4: Menambahkan Dua Pelanggan Baru dan Memverifikasi Kolom Eksplisit
```sql
USE TokoRitelDB;
GO

INSERT INTO dbo.Customers (CustomerCode, FullName, Email)
VALUES 
    ('CUST-004', N'Dewi Lestari', 'dewi.lestari@example.com'),
    ('CUST-005', N'Hendra Wijaya', 'hendra.w@example.com');
GO

SELECT CustomerID, CustomerCode, FullName, IsActive
FROM dbo.Customers
WHERE CustomerCode IN ('CUST-004', 'CUST-005');
GO
```
*Hasil Keluaran:*
```text
CustomerID  CustomerCode  FullName       IsActive
----------  ------------  -------------  --------
4           CUST-004      Dewi Lestari   1
5           CUST-005      Hendra Wijaya  1
(2 rows affected)
```
*Pembahasan Pedagogis:*
Penyisipan multi-baris (*multi-row insert*) dieksekusi dalam satu transaksi atomik. Kolom `CustomerID` (`IDENTITY`) otomatis menghasilkan nilai urut `4` dan `5`. Kolom `IsActive` yang tidak disebutkan di daftar kolom secara otomatis terisi nilai default `1` sesuai batasan `DEFAULT (1)`.

#### Soal 5: Memperbarui Harga dan Stok Produk `BRG-002` Secara Bersamaan
```sql
USE TokoRitelDB;
GO

UPDATE dbo.Products
SET 
    UnitPrice = UnitPrice + 2500.00,
    StockQuantity = StockQuantity + 15
WHERE ProductSKU = 'BRG-002';
GO

SELECT ProductID, ProductSKU, ProductName, UnitPrice, StockQuantity
FROM dbo.Products
WHERE ProductSKU = 'BRG-002';
GO
```
*Hasil Keluaran:*
```text
ProductID  ProductSKU  ProductName               UnitPrice  StockQuantity
---------  ----------  ------------------------  ---------  -------------
2          BRG-002     Minyak Goreng Kelapa 2 L  38500.00   55
(1 row affected)
```
*Pembahasan Pedagogis:*
Pembaruan beberapa kolom dilakukan dalam satu klausa `SET` yang dipisahkan tanda koma. `UnitPrice` semula `36000.00` naik menjadi `38500.00`, dan `StockQuantity` semula `40` bertambah menjadi `55`.

#### Soal 6: Menyalin Data Produk Secara Massal Menggunakan `INSERT ... SELECT`
```sql
USE TokoRitelDB;
GO

INSERT INTO dbo.Products (ProductSKU, ProductName, UnitPrice, StockQuantity)
SELECT StageSKU, StageName, StagePrice, StageStock
FROM dbo.StagingProducts
WHERE StageStock >= 30;
GO

SELECT ProductID, ProductSKU, ProductName, UnitPrice, StockQuantity
FROM dbo.Products
WHERE ProductID >= 6;
GO
```
*Hasil Keluaran:*
```text
ProductID  ProductSKU  ProductName                  UnitPrice  StockQuantity
---------  ----------  ---------------------------  ---------  -------------
6          BRG-006     Garam Beryodium 500 g        4500.00    100
7          BRG-007     Susu Bubuk Full Cream 400 g  42000.00   30
(2 rows affected)
```
*Pembahasan Pedagogis:*
Klausa `WHERE StageStock >= 30` memfilter tabel staging: produk `'BRG-006'` (stok 100) dan `'BRG-007'` (stok 30) lolos kriteria, sementara `'BRG-008'` (stok 20) diabaikan. Kedua baris tersebut disalin dan memperoleh nomor urut `ProductID` baru (`6` dan `7`).

#### Soal 7: Menambahkan Mata Pelajaran dan Memperbarui Nilai di `AkademikDB`
```sql
USE AkademikDB;
GO

-- 1. Tambah 1 mata pelajaran baru
INSERT INTO dbo.MataPelajaran (KodeMapel, NamaMapel, KKM)
VALUES ('MP-WEB', N'Pemrograman Web', 75.00);

-- 2. Perbarui nilai angka santri ID 3 pada mapel ID 1
UPDATE dbo.NilaiSemester
SET NilaiAngka = 80.00
WHERE SantriID = 3 AND MapelID = 1;

-- 3. Tampilkan seluruh isi tabel NilaiSemester
SELECT NilaiID, SantriID, MapelID, NilaiAngka, TahunAjaran
FROM dbo.NilaiSemester;
GO
```
*Hasil Keluaran:*
```text
NilaiID  SantriID  MapelID  NilaiAngka  TahunAjaran
-------  --------  -------  ----------  -----------
1        1         1        85.00       2026/2027
2        2         1        90.00       2026/2027
3        3         1        80.00       2026/2027
4        4         2        88.00       2026/2027
(4 rows affected)
```
*Pembahasan Pedagogis:*
Mata pelajaran `MP-WEB` masuk ke tabel referensi. Pembaruan pada `dbo.NilaiSemester` menggunakan operator logika `AND` pada klausa `WHERE` untuk memastikan penargetan baris spesifik tanpa mengubah nilai milik santri lain.

#### Soal 8: Memindahkan Calon Santri dan Mengosongkan Tabel dengan `TRUNCATE`
```sql
USE AkademikDB;
GO

-- 1. Salin seluruh calon santri ke tabel santri utama
INSERT INTO dbo.Santri (NIS, NamaLengkap)
SELECT NIS, NamaLengkap
FROM dbo.SantriCalonBaru;

-- 2. Kosongkan tabel calon santri dan reset IDENTITY
TRUNCATE TABLE dbo.SantriCalonBaru;

-- 3. Verifikasi 8 santri aktif di tabel utama
SELECT SantriID, NIS, NamaLengkap, StatusAktif
FROM dbo.Santri;
GO
```
*Hasil Keluaran:*
```text
SantriID  NIS      NamaLengkap        StatusAktif
--------  -------  -----------------  -----------
1         2026001  Muhammad Ridwan    1
2         2026002  Aisyah Nurjanah    1
3         2026003  Fajar Pratama      1
4         2026004  Zahra Salsabila    1
5         2026005  Hasan Al-Banna     1
6         2026006  Nadia Kusuma       1
7         2026007  Rizky Ramadhan     1
8         2026008  Salma Khairunnisa  1
(8 rows affected)
```
*Pembahasan Pedagogis:*
Calon santri sebanyak 3 orang disalin ke tabel `dbo.Santri` dan memperoleh nomor `SantriID` urut `6`, `7`, dan `8`. Kemudian `TRUNCATE TABLE dbo.SantriCalonBaru;` menghapus seluruh baris data pendaftaran secara instan dan mereset nilai counter `CalonID` kembali ke seed awal (`1`).

#### Soal 9: Analisis Batasan Metadata `TRUNCATE` pada Tabel Ber-Foreign Key
*Penyebab Penolakan `Msg 4712`:*
SQL Server memvalidasi keberadaan batasan *Foreign Key* pada level metadata sistem (`sys.foreign_keys`). Jika sebuah tabel dirujuk oleh tabel lain sebagai kunci induk (*referenced table*), engine secara tegas menolak perintah `TRUNCATE TABLE`, terlepas dari apakah tabel anak saat itu sedang kosong (`0 baris`) atau berisi data. Hal ini dirancang untuk mencegah pelanggaran integritas referensial secara tak sengaja pada operasi deallokasi halaman yang tidak mencatat baris per baris.

*Urutan Perintah DML yang Benar dan Aman:*
```sql
USE LogistikAuditDB;
GO

-- Langkah 1: Hapus data tabel anak terlebih dahulu
DELETE FROM dbo.LogMutasiHarian;

-- Langkah 2: Hapus data tabel induk menggunakan DELETE (bukan TRUNCATE)
DELETE FROM dbo.GudangBarang;
GO
```

#### Soal 10: Komparasi Efisiensi Log Transaksi: `DELETE` vs `TRUNCATE TABLE` (500.000 Baris)
| Parameter Pembanding | Operasi `DELETE FROM dbo.LogMutasiHarian;` | Operasi `TRUNCATE TABLE dbo.LogMutasiHarian;` |
| :--- | :--- | :--- |
| **(a) Pertumbuhan File Log (`.ldf`)** | **Sangat Besar (*Full Logging*):** Mencatat setiap baris yang dihapus satu per satu ke dalam log transaksi (500.000 entri log), menyebabkan file `.ldf` membengkak ratusan megabyte/gigabyte. | **Sangat Minimal (*Minimal Logging*):** Hanya mencatat peristiwa deallokasi halaman data (*extent deallocation*), sehingga konsumsi ruang file `.ldf` sangat kecil (hanya beberapa kilobyte). |
| **(b) Kecepatan & Penguncian (*Locking*)** | **Lambat:** Membutuhkan waktu signifikan karena scanning dan logging per baris; menahan penguncian eksklusif (*exclusive row/page lock*) dalam durasi lama sehingga dapat memicu kemacetan transaksi lain (*blocking*). | **Instan (< 1 detik):** Hanya melepaskan pointer halaman data di metadata tabel; menggunakan kunci modifikasi skema (*Schema Modification lock*) yang sangat cepat dilepas kembali. |
| **(c) Perilaku Nilai `LogID` (`IDENTITY`)** | **Nilai Lanjut:** Counter `IDENTITY` tetap berada di angka terakhir (transaksi berikutnya akan mendapat `LogID = 500001`). | **Reset ke Awal:** Counter `IDENTITY` otomatis di-reset kembali ke nilai seed awal tabel (`LogID = 1`). |
