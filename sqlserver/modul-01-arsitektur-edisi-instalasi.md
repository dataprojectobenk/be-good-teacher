# MODUL 01: ARSITEKTUR, PEMILIHAN EDISI, & INSTALASI MICROSOFT SQL SERVER
- **Subjek / Mata Kuliah:** Basis Data / Administrasi Microsoft SQL Server (2019 / 2022)
- **Tahap Kurikulum:** Level 1 — Fondasi & Instalasi
- **Target Peserta:** Terpadu Bertingkat (SMK & Mahasiswa)
- **Fokus Materi:** Memahami cara kerja Database Server vs Aplikasi Klien, memilih Edisi & Tipe Instance, memahami Collation & pemisahan jalur file (`.mdf`, `.ldf`, `TempDB`), melakukan instalasi bersih, serta menjalankan verifikasi pertama.
- **Studi Kasus Berkelanjutan:** `TokoRitelDB`, `AkademikDB`, `LogistikAuditDB`

---

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### A. Pengingat Prasyarat Cepat (2 Menit)
Sebelum menyentuh installer atau menulis perintah apa pun, hubungkan konsep basis data dengan hal yang sudah akrab di komputer sehari-hari:
- **Konsep yang Harus Sudah Dipahami:** Perbedaan antara **aplikasi dokumen biasa** (seperti file Excel yang dibuka langsung oleh satu orang) dengan **sistem basis data terpusat** (yang melayani banyak pengguna secara bersamaan), serta pemahaman dasar tentang **partisi/drive disk** di Windows (`C:\`, `D:\`).
- **Cek Kilat Prasyarat (Jawab Singkat Sebelum Lanjut):**
  1. Jika dua kasir membuka satu file Excel yang sama lewat jaringan bersamaan, apa risiko yang terjadi? *(Jawab singkat: `File terkunci (read-only) atau perubahan salah satu kasir saling menimpa.`)*
  2. Apakah aplikasi **SQL Server Management Studio (SSMS)** adalah tempat data disimpan? *(Jawab singkat: `Bukan. SSMS hanyalah aplikasi klien (antarmuka) untuk mengirim perintah; mesin pengelola datanya adalah layanan SQL Server Database Engine.`)*

### B. Jembatan Konsep: Mengapa Tidak Boleh Asal Klik *Next-Next* saat Instalasi?
Bayangkan sebuah **restoran**. Pelanggan atau pelayan yang mencatat pesanan di meja adalah **Aplikasi Klien** (seperti kasir toko atau SSMS). Sementara itu, **Dapur**—tempat bahan disimpan rapi di lemari pendingin, koki memasak pesanan, dan antrean diatur agar tidak saling bertabrakan—adalah **SQL Server Database Engine**. Di sistem operasi Windows, "dapur" ini berjalan diam-diam di latar belakang sebagai **Windows Service**. Anda tidak akan pernah bisa memesan makanan kalau dapurnya belum dibangun dengan benar dan belum dinyalakan.

Banyak pemula langsung menekan tombol *Next-Next* (*Basic Install*) saat memasang SQL Server. Akibatnya, masalah besar baru muncul berbulan-bulan kemudian ketika sistem sudah dipakai nyata:
1. Memasang **Express Edition** untuk sistem perusahaan besar sehingga database mendadak berhenti menerima data saat menyentuh batas **10 GB**.
2. Membiarkan file data (`.mdf`), catatan transaksi (`.ldf`), dan meja kerja sementara (`TempDB`) menumpuk di drive sistem `C:\` hingga Windows melambat atau kehabisan ruang disk.
3. Salah memilih **Collation** (aturan kepekaan huruf besar/kecil), sehingga pencarian nama `'kopi'` gagal menemukan `'Kopi'`.

Modul ini membangun model mental arsitektur tersebut terlebih dahulu sebelum Anda menyentuh perintah pembuatan tabel di level berikutnya.

### C. Tujuan Pembelajaran & Peta Alur Kerja
Setelah menyelesaikan modul ini, peserta didik mampu:
1. Membedakan peran **Client Tool** (SSMS) dan **Database Engine Service** (`sqlservr.exe`), serta menjelaskan fungsi Edisi, Instance, *Collation*, dan *Service Account*.
2. Memilih konfigurasi instalasi yang tepat sesuai kebutuhan skala data dan pengguna.
3. Melakukan instalasi SQL Server 2019/2022 secara terpandu, memverifikasi layanan Windows, dan menjalankan perintah verifikasi 1 baris pertama tanpa kebingungan sintaks.

**Peta Alur Kerja (*Installation & First Verification Flow*):**
```text
[1. Pahami Kebutuhan Sistem: Ukuran Data, RAM, & Jumlah Pengguna]
        │
        ▼
[2. Tentukan Edisi (Developer / Express) & Tipe Instance (Default / Named)]
        │
        ▼
[3. Atur Direktori Penyimpanan (Data / Log / TempDB), Collation, & Service Account]
        │
        ▼
[4. Cek Windows Service] ──(Stopped)──► [Klik Start Service di services.msc]
        │ (Running)
        ▼
[5. Hubungkan SSMS & Jalankan Verifikasi 1 Baris: SELECT @@VERSION;]
```

---

## 2. Kamus Konsep Arsitektur, Istilah Instalasi, & Perintah Pertama

Pada bagian ini, Anda akan mempelajari seluruh istilah arsitektur yang muncul di layar instalasi beserta dua perintah verifikasi paling dasar sebelum memulai praktik.

### A. Perbandingan Edisi Microsoft SQL Server (2019 / 2022)

| Edisi | Biaya Lisensi | Batas Ukuran per Database | Batas RAM (*Buffer Pool*) | Batas CPU | Ketersediaan *SQL Server Agent* | Kapan Harus Dipilih? |
| :--- | :--- | :--- | :--- | :--- | :---: | :--- |
| **Developer** | **Gratis** *(khusus belajar/dev)* | Tanpa batas teknis edisi | Mengikuti kapasitas OS | Mengikuti OS | **Ada** | **Pilihan utama belajar, lab SMK/Kampus, & pengujian** *(dilarang untuk server komersial/produksi)*. |
| **Express** | **Gratis** *(boleh untuk produksi)* | **Maksimal 10 GB** | **Maksimal ~1,4 GB (1410 MB)** | **1 Socket / 4 Cores** | **Tidak Ada** | Aplikasi mikro/UMKM kecil di produksi dengan data di bawah 10 GB. |
| **Standard** | Berbayar (per core / CAL) | 524 PB | Maksimal 128 GB | 4 Sockets / 24 Cores | **Ada** | Server produksi bisnis skala menengah. |
| **Enterprise** | Berbayar (tinggi) | 524 PB | Mengikuti batas OS | Mengikuti batas OS | **Ada** | Aplikasi korporasi misi kritis & volume data masif. |

### B. Arsitektur *Instance*: `Default Instance` vs `Named Instance`
Satu komputer Windows boleh memasang lebih dari satu mesin SQL Server secara terpisah (misalnya satu untuk versi 2019 dan satu lagi untuk versi 2022). Setiap pemasangan mesin disebut **Instance**.

| Jenis Instance | Nama Layanan di Windows (`services.msc`) | Alamat Koneksi yang Diketik di SSMS | Karakteristik Utama |
| :--- | :--- | :--- | :--- |
| **Default Instance** | `MSSQLSERVER` | `localhost` atau `.` atau `NamaPC` | Hanya boleh ada **1** per komputer. Paling praktis diakses karena tidak perlu menyebut nama tambahan. |
| **Named Instance** | `MSSQL$<NamaInstance>` *(contoh: `MSSQL$SQLEXPRESS`)* | `localhost\<NamaInstance>` atau `.\SQLEXPRESS` | Boleh dipasang lebih dari satu di komputer yang sama. Wajib menyertakan tanda `\` diikuti nama instance saat login. |

### C. Mengenal 3 Jenis File Penyimpanan Fisik (Konsep Tanpa Skrip)
Saat Anda mengatur lokasi folder di tahap instalasi, SQL Server memisahkan tugas penyimpanan menjadi tiga komponen utama:
- **File Data Utama (`.mdf` — *Master Data File*):** Lemari arsip utama tempat seluruh tabel dan isi data disimpan. Pola kerjanya **baca-tulis acak** (*random I/O*).
- **File Log Transaksi (`.ldf` — *Transaction Log File*):** Buku catatan kronologis setiap perubahan data sebelum dicatat permanen ke `.mdf`. Pola kerjanya **tulis berurutan** (*sequential I/O*) dan menjadi penyelamat pemulihan data jika listrik padam.
- **Database `TempDB`:** "Meja kerja sementara" tempat SQL Server mengurutkan atau menghitung data besar sebelum hasilnya dikirim ke layar. Isinya selalu direset bersih setiap kali service SQL Server dinyalakan ulang.

### D. *Collation* (`SQL_Latin1_General_CP1_CI_AS`)
*Collation* adalah aturan kamus bahasa dan perbandingan karakter di dalam mesin SQL Server. Memilih *Collation* saat instalasi sangat krusial karena menjadi aturan bawaan seluruh sistem.
- **`SQL_Latin1_General_CP1`**: Mendukung alfabet standar Latin/Indonesia/Inggris.
- **`CI` (*Case-Insensitive*)**: Huruf besar dan kecil **dianggap sama** (`'Budi'` sama dengan `'budi'`). Ini standar yang paling ramah untuk mayoritas aplikasi. *(Kebalikannya adalah `CS` / Case-Sensitive: `'Budi'` dianggap berbeda dengan `'budi'`).*
- **`AS` (*Accent-Sensitive*)**: Karakter beraksen dibedakan (`'e'` berbeda dengan `'é'`).

### E. *Service Account* (Akun Penggerak Layanan)
Karena SQL Server berjalan sebagai layanan latar belakang Windows, ia membutuhkan identitas akun Windows untuk membaca dan menulis folder di disk:
- **Standar Aman (Bawaan):** `NT Service\MSSQLSERVER` (untuk Default Instance) atau `NT Service\MSSQL$<Nama>`. Ini adalah *Virtual Account* yang hanya diberi izin seperlunya (*least privilege*) ke folder SQL Server saja, bukan ke seluruh sistem Windows.

### F. Dua Perintah Verifikasi Pertama
Setelah instalasi selesai, gunakan dua perintah verifikasi paling dasar berikut untuk memastikan mesin SQL Server aktif:

1. **Perintah PowerShell Cek Status Layanan:**
   - **Sintaks:**
     ```powershell
     Get-Service *SQL*
     ```
   - **Penjelasan Perintah:** `Get-Service` adalah perintah Windows PowerShell untuk melihat daftar layanan latar belakang; tanda `*SQL*` menyaring hanya layanan yang namanya mengandung kata `SQL`.
   - **Hasil:** Menampilkan kolom `Status` (`Running` atau `Stopped`), `Name`, dan `DisplayName`.

2. **Perintah T-SQL Pertama di SSMS (`SELECT` + Variabel Sistem `@@`):**
   - **Sintaks Baku:**
     ```sql
     SELECT @@VERSION;
     ```
     atau memberi judul kolom dengan `AS`:
     ```sql
     SELECT @@SERVERNAME AS NamaAlamatServer;
     ```
   - **Rincian Kata Kunci & Fungsi:**

     | Bagian Perintah | Arti & Fungsi | Penjelasan Sederhana |
     | :--- | :--- | :--- |
     | `SELECT` | *"Tampilkan hasil ke layar"* | Perintah dasar untuk meminta SQL Server menampilkan nilai. Pada bentuk 1 baris ini, kita **belum** membaca tabel mana pun. |
     | `@@VERSION` | Variabel sistem versi | Nilai bawaan SQL Server (ditandai `@@` di depan) yang otomatis berisi informasi edisi, nomor versi, dan sistem operasi. |
     | `@@SERVERNAME` | Variabel sistem nama server | Mengembalikan alamat server/instance yang sedang kita akses (misal `DESKTOP-LAB` atau `DESKTOP-LAB\SQLEXPRESS`). |
     | `@@SERVICENAME` | Variabel sistem nama layanan | Mengembalikan nama service Windows instance (`MSSQLSERVER` jika Default Instance, atau nama instance jika Named Instance). |
     | `AS <JudulKolom>` | *Alias* (Opsional) | Memberi nama judul kolom pada tabel hasil agar rapi dan tidak tertulis `(No column name)`. |
     | `;` | Penutup perintah | Menandai akhir satu kalimat perintah T-SQL. |

   - **Contoh Mikro (1 Baris Mandiri):**
     ```sql
     SELECT @@SERVICENAME AS NamaLayananAktif;
     ```
     *Hasil eksekusi:* Menampilkan 1 kotak hasil berisi `MSSQLSERVER` (jika Default Instance) dengan judul kolom `NamaLayananAktif`.

---

## 3. Persiapan Lingkungan & Profil Studi Kasus

### A. Kebutuhan Perangkat Keras & Lunak
- **Sistem Operasi:** Windows 10 / Windows 11 (64-bit) atau Windows Server 2019 / 2022.
- **Spesifikasi Minimal Lab:** CPU 2 Cores, RAM 4 GB *(disarankan 8 GB)*, ruang disk kosong minimal 6 GB.
- **Installer:**
  - **Microsoft SQL Server 2022 Developer Edition** *(atau 2019 Developer / Express)*.
  - **SQL Server Management Studio (SSMS) v19+** *(pengaturan koneksi lanjutan akan dipelajari pada Modul 02, namun sudah dapat dipasang agar siap melakukan tes `SELECT @@VERSION;` di akhir modul ini)*.

### B. Peta Skenario Kebutuhan Tiga Sistem
Pada tahap instalasi awal ini, ketiga skema sistem berikut digunakan sebagai studi kasus perencanaan arsitektur sebelum struktur fisiknya dibuat pada Level 2:

| Tema | Domain Sistem | Profil Skala & Kebutuhan Sistem | Fokus Keputusan pada Modul 01 |
| :--- | :--- | :--- | :--- |
| **Tema 1 (Inti)** | `TokoRitelDB` | Aplikasi kasir dan inventaris toko ritel di lingkungan laboratorium belajar; butuh fitur lengkap agar seluruh materi Level 1–7 bisa diuji tanpa batas. | Menentukan edisi lab (*Developer*), *Default Instance*, dan `Collation` *Case-Insensitive* (`CI_AS`). |
| **Tema 2 (Transfer)** | `AkademikDB` | Sistem akademik sekolah/pesantren/kampus dengan 15.000 data peserta didik; pencarian nama siswa/mahasiswa tidak boleh sensitif huruf kapital. | Mentransfer keputusan edisi & memverifikasi alasan pemilihan `CI_AS` vs `CS_AS` serta verifikasi service aktif. |
| **Tema 3 (Pendalaman)** | `LogistikAuditDB` | Sistem logistik gudang perusahaan skala produksi: tumbuh **50 GB/tahun**, butuh **RAM 12 GB**, dan otomasi *SQL Server Agent* tiap malam. | Menganalisis mengapa Express Edition gagal memenuhi beban ini serta merancang pemisahan drive Data, Log, dan `TempDB`. |

---

## 4. Praktik Bertingkat Langkah demi Langkah — Studi Kasus `TokoRitelDB`

Seluruh tahap di bagian ini menggunakan skenario **`TokoRitelDB`** dengan peningkatan satu langkah pemahaman pada setiap tahapnya.

### Tahap 1 — Merancang Keputusan Instalasi untuk `TokoRitelDB`
Sebelum menjalankan file `setup.exe`, tentukan 4 keputusan konfigurasi untuk mesin laboratorium `TokoRitelDB`:
1. **Edisi:** Pilih **Developer** *(gratis, tetapi memiliki seluruh fitur Enterprise seperti SQL Server Agent dan tanpa batas 10 GB)*.
2. **Tipe Instance:** Pilih **Default Instance (`MSSQLSERVER`)** agar koneksi nanti cukup mengetik `localhost` atau `.`.
3. **Collation:** Biarkan pada bawaan **`SQL_Latin1_General_CP1_CI_AS`** agar pencarian nama produk seperti `'Minyak Goreng'` tetap cocok saat diketik `'minyak goreng'`.
4. **Mode Autentikasi Awal:** Pilih **Mixed Mode** (atau *Windows Authentication* sementara jika hanya dipakai sendiri — pengamanan autentikasi & jaringan akan dibahas lengkap di Modul 02) dan klik **Add Current User** pada daftar SQL Server administrators.

### Tahap 2 — Panduan Instalasi Terpandu SQL Server 2022 (`Custom` / `Installation Center`)
Mengapa memilih mode **Custom** (bukan *Basic*) saat menginstal? Karena mode *Custom* membuka jendela konfigurasi agar Anda melihat langsung tempat pengaturan Instance, Service Account, Collation, dan Direktori Data.

Langkah terpandu di **SQL Server Installation Center**:
1. Jalankan installer $\rightarrow$ pilih **Custom** $\rightarrow$ tunggu unduhan paket selesai hingga muncul *SQL Server Installation Center*.
2. Pilih menu **Installation** (kiri) $\rightarrow$ klik **New SQL Server stand-alone installation**.
3. Pada layar **Specify a free edition**, pilih **Developer** $\rightarrow$ *Next* $\rightarrow$ centang *I accept the license terms*.
4. Pada layar **Feature Selection**, centang cukup **Database Engine Services** *(hindari mencentang fitur Machine Learning/Analysis Services yang tidak dipakai agar RAM tidak terbebani)*.
5. Pada layar **Instance Configuration**, pilih **Default instance** (Instance ID otomatis terisi `MSSQLSERVER`).
6. Pada layar **Server Configuration**:
   - Tab *Service Accounts*: Pastikan *SQL Server Database Engine* menggunakan `NT Service\MSSQLSERVER` dengan *Startup Type* **Automatic**.
   - Tab *Collation*: Pastikan tertulis `SQL_Latin1_General_CP1_CI_AS`.
7. Pada layar **Database Engine Configuration**:
   - Tab *Server Configuration*: Klik **Add Current User** agar akun Windows Anda otomatis menjadi administrator SQL Server.
   - Tab *Data Directories* & *TempDB*: Amati lokasi folder penyimpanan file `.mdf`, `.ldf`, dan `TempDB`. *(Untuk komputer lab 1 drive, boleh dibiarkan di folder bawaan; untuk server produksi multi-drive, lihat Tahap 4).*
8. Klik **Install** dan tunggu hingga seluruh komponen berstatus **Succeeded**.

- **Perbedaan dengan Tahap 1:** Tahap 1 adalah perencanaan di atas kertas, sedangkan Tahap 2 menerapkan keputusan tersebut langsung pada wizard instalasi.
- **Pelacakan Kondisi Sistem (*State Tracing*) Selama Instalasi:**
  ```text
  [Sebelum Instalasi]
    └─ Windows Services : Tidak ada layanan SQL Server terpasang.
    └─ Koneksi SSMS     : Gagal (belum ada "dapur" yang mendengarkan permintaan).

  [Setelah Feature Selection & Instance Configuration]
    └─ File program biner (sqlservr.exe) disalin ke Program Files.
    └─ Folder penyimpanan master, model, msdb, dan tempdb dibuat.

  [Selesai Install (Succeeded)]
    └─ Windows Services : Layanan 'SQL Server (MSSQLSERVER)' terdaftar & berstatus Running.
    └─ Status Sistem    : Mesin Database Engine siap menerima perintah pertama!
  ```

### Tahap 3 — Verifikasi Layanan & Kueri 1 Baris Pertama
Setelah instalasi sukses, verifikasi bahwa "dapur" SQL Server benar-benar menyala dan merespons perintah:

**Langkah 3A — Cek lewat Windows PowerShell:**
```powershell
Get-Service *SQL*
```
Pastikan baris `MSSQLSERVER` memiliki `Status : Running`.

**Langkah 3B — Jalankan 2 Perintah T-SQL Pertama di SSMS:**
1. Buka **SQL Server Management Studio (SSMS)** *(atau Azure Data Studio)*.
2. Pada jendela *Connect to Server*, isi **Server name** dengan `localhost` (atau `.\SQLEXPRESS` jika Anda menginstal Named Instance Express), pilih **Authentication: Windows Authentication**, lalu klik **Connect**.
3. Klik tombol **New Query** di toolbar atas, ketik tepat 2 baris berikut, lalu tekan **F5** (*Execute*):

```sql
-- Verifikasi pertama koneksi dan identitas mesin SQL Server
SELECT @@VERSION AS InformasiVersiSQL;
SELECT @@SERVERNAME AS AlamatInstance, @@SERVICENAME AS NamaServiceWindows;
```

- **Pelacakan Alur Data (*Step-by-Step Trace*):**
  ```text
  1. Anda mengetik SELECT @@VERSION AS InformasiVersiSQL; di jendela New Query SSMS.
        │
        ▼  (Menekan F5 / Execute)
  2. SSMS mengirim teks perintah tersebut ke Windows Service 'MSSQLSERVER'.
        │
        ▼  (Engine membaca variabel sistem @@VERSION di memorinya)
  3. SQL Server tidak perlu membuka disk/tabel; ia langsung mengembalikan teks versi.
        │
        ▼
  4. Tab "Results" di bagian bawah SSMS menampilkan 2 tabel hasil terpisah!
  ```
- **Contoh Hasil Keluaran di Tab *Results* SSMS:**
  ```text
  Result 1:
  InformasiVersiSQL
  --------------------------------------------------------------------------------
  Microsoft SQL Server 2022 (RTM) - 16.0.1000.6 (X64) ... Developer Edition (64-bit)

  Result 2:
  AlamatInstance    NamaServiceWindows
  ----------------  ------------------
  DESKTOP-RITEL     MSSQLSERVER
  ```

### Tahap 4 — Perencanaan Pemisahan Direktori & Kapasitas Produksi (Pendalaman)

> **Jalur Pendalaman (Mahasiswa / Pengayaan SMK):** Tahap ini membahas strategi pemisahan disk fisik untuk mencegah kemacetan I/O pada server produksi.

Pada komputer laptop belajar yang hanya memiliki satu SSD (`C:\`), seluruh file diletakkan pada satu partisi masih dapat ditoleransi. Namun, pada server produksi nyata, membiarkan Data (`.mdf`), Log (`.ldf`), `TempDB`, dan OS berada di partisi yang sama akan memicu kemacetan I/O (*disk contention*) dan risiko sistem operasi macet bila disk penuh.

**Rancangan Tata Letak Direktori Produksi Ideal:**

| Komponen | Lokasi Volume Disarankan | Pola Baca/Tulis (*I/O Pattern*) | Alasan Arsitektural Pemisahan |
| :--- | :--- | :--- | :--- |
| **Sistem Operasi & Biner** | `C:\Program Files\Microsoft SQL Server\` | Baca program & OS paging | Menjaga OS tetap responsif meskipun disk database penuh atau sibuk. |
| **Data Files (`.mdf`/`.ndf`)** | `D:\SQLData\` | *Random Read/Write* (banyak baca halaman data) | Memaksimalkan IOPS pembacaan tabel tanpa terganggu penulisan log. |
| **Transaction Log (`.ldf`)** | `E:\SQLLogs\` | *Sequential Write* (tulis berurutan cepat) | Penulisan log harus sangat cepat dan berurutan agar transaksi tidak antre. |
| **`TempDB` Files** | `T:\SQLTempDB\` | *High-Churn Temporary I/O* | Operasi penyortiran dan data sementara sangat padat sehingga layak memakai NVMe/disk tersendiri. |
| **Backup Files (`.bak`)** | `F:\SQLBackup\` *(atau NAS terpisah)* | *Large Sequential Write* | Jika disk `D:\` rusak, salinan cadangan di disk berbeda tetap selamat. |

---

## 5. Verifikasi Output & Panduan Troubleshooting

### A. Ringkasan Ekspektasi Hasil Tahap 1–4

| Tahap | Jalur | Objek / Hal yang Diperiksa | Indikator Berhasil |
| :---: | :---: | :--- | :--- |
| **Tahap 1** | Inti | Keputusan Edisi, Instance, & Collation | Tertulis jelas: `Developer`, `Default (MSSQLSERVER)`, dan `SQL_Latin1_General_CP1_CI_AS`. |
| **Tahap 2** | Inti | Wizard Instalasi SQL Server | Seluruh fitur di layar akhir instalasi berstatus **Succeeded** tanpa tanda silang merah. |
| **Tahap 3** | Inti | PowerShell & Query Pertama di SSMS | `Get-Service *SQL*` menunjukkan `Running`; SSMS menampilkan 2 tab hasil berisi nama edisi dan `MSSQLSERVER`. |
| **Tahap 4** | Pendalaman | Rancangan Direktori & Kapasitas | OS, `.mdf`, `.ldf`, `TempDB`, dan `.bak` dipetakan ke jalur/volume terpisah sesuai pola I/O-nya. |

### B. Kesalahan Umum Pemula saat Instalasi & Verifikasi Awal

| Gejala / Pesan Kesalahan | Penyebab Umum | Solusi Tepat |
| :--- | :--- | :--- |
| Tidak bisa login dengan `localhost` di SSMS (*Error 26 / Network-related error*) | Sebenarnya menginstal **Express Edition** (yang otomatis membuat Named Instance `SQLEXPRESS`), tetapi di SSMS menulis `localhost` saja. | Ubah *Server name* di SSMS menjadi `localhost\SQLEXPRESS` atau `.\SQLEXPRESS`. |
| `Get-Service *SQL*` menampilkan `Status: Stopped` | Service SQL Server dihentikan manual atau gagal menyala saat booting Windows. | Jalankan `Start-Service MSSQLSERVER` (PowerShell Run as Administrator) atau buka `services.msc` $\rightarrow$ klik kanan **SQL Server (MSSQLSERVER)** $\rightarrow$ **Start**. |
| `Incorrect syntax near 'VERSION'` saat mengetik `SELECT VERSION;` | Lupa menyertakan dua tanda `@` (`@@`) di depan variabel sistem. | Perbaiki menjadi `SELECT @@VERSION;`. |
| Mengira *Developer Edition* boleh dipakai gratis untuk server toko/kantor asli | Salah memahami lisensi: gratisnya *Developer Edition* hanya untuk belajar, pengembangan, dan uji coba. | Untuk server produksi komersial berfitur lengkap gunakan **Standard/Enterprise**, atau **Express** jika data pasti `< 10 GB`. |

---

## 6. Latihan Soal

*Perhatikan tabel acuan konfigurasi instance dan spesifikasi lingkungan praktik berikut sebelum mengerjakan latihan soal:*

#### Tabel Acuan Konfigurasi Praktik & Studi Kasus Arsitektur

| Nama Sistem / Lab | Edisi Target | Tipe Instance | Collation Standar | Service Account | Skenario Kebutuhan & Beban Data |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `TokoRitelDB` | Developer / Express | Default (`MSSQLSERVER`) | `SQL_Latin1_General_CP1_CI_AS` | `NT Service\MSSQLSERVER` | Lab kasir ritel mandiri, pencarian teks tidak peka huruf besar/kecil. |
| `AkademikDB` | Developer (Lab) | Named (`AKADEMIK2022`) | `SQL_Latin1_General_CP1_CI_AS` | `NT Service\MSSQL$AKADEMIK2022` | Lab akademik 15.000 mahasiswa, jadwal job backup otomatis tanpa lisensi komersial. |
| `LogistikAuditDB` | Standard / Enterprise | Default (`MSSQLSERVER`) | `SQL_Latin1_General_CP1_CI_AS` | Akun Domain Dedicated | Server produksi 50 GB/tahun, Buffer Pool > 10 GB, pemeliharaan otomatis harian. |
| `LabPC-Kasir01` | Express | Named (`TOKO`) | `SQL_Latin1_General_CP1_CI_AS` | `NT Service\MSSQL$TOKO` | Komputer klien kasir cabang lokal dengan kapasitas data kecil. |

---

#### Daftar Tugas Praktik
1. Tulis perintah T-SQL untuk menampilkan informasi versi lengkap dari Microsoft SQL Server yang sedang berjalan di komputer Anda.
2. Tulis perintah T-SQL untuk menampilkan nama instance server aktif dan nama layanan Windows dalam dua kolom terpisah berlabel `AlamatKoneksi` dan `NamaLayanan`.
3. Tulis perintah T-SQL untuk memperbaiki kesalahan penulisan kueri berikut agar dapat dieksekusi tanpa error dan menghasilkan kolom berjudul `VersiMesinSQL`:
   ```sql
   SELECT VERSION AS VersiMesinSQL
   ```
4. Tulis perintah PowerShell untuk memeriksa status seluruh layanan Windows terkait SQL Server yang terpasang di komputer Anda.
5. Tulis perintah T-SQL tunggal untuk menggabungkan tiga variabel sistem sekaligus sehingga menghasilkan satu baris data dengan kolom `AlamatServer`, `NamaLayanan`, dan `VersiSQL`.
6. Tentukan alamat *Server name* yang harus diketikkan pada jendela login SSMS untuk tiga skenario koneksi: (a) Komputer lab `LAB-05` dengan Default Instance, (b) Komputer lab `LAB-05` dengan Named Instance `RITEL2022`, dan (c) Komputer lokal sendiri menggunakan Default Instance tanpa mengetikkan nama PC.
7. Tentukan keputusan konfigurasi yang tepat untuk sistem `AkademikDB` pada lingkungan laboratorium kampus (mencakup rekomendasi Edisi, Tipe Instance, Collation, dan Service Account) serta jelaskan alasan teknis dari setiap pilihan tersebut.
8. Analisis batasan teknis dan hukum: jelaskan mengapa Express Edition dan Developer Edition sama-sama tidak dapat digunakan untuk server produksi `LogistikAuditDB` (50 GB/tahun), serta rancang peta pemisahan minimal 4 volume/drive disk berbeda (`OS`, Data `.mdf`, Log `.ldf`, `TempDB`) beserta alasan pola I/O masing-masing.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil eksekusi kueri dan verifikasi konfigurasi Anda secara mandiri:*

### A. Indikator Keberhasilan Tugas & Target Output Visual Kueri

- **Target Output Visual Soal 1 (Kueri Versi Lengkap):**
  ```text
  +--------------------------------------------------------------------------------------------------------------------------------------------------------+
  | (No column name)                                                                                                                                       |
  +--------------------------------------------------------------------------------------------------------------------------------------------------------+
  | Microsoft SQL Server 2022 (RTM) - 16.0.1000.6 (X64) ... Developer Edition (64-bit) on Windows 10 Pro ...                                              |
  +--------------------------------------------------------------------------------------------------------------------------------------------------------+
  (1 row affected)
  ```

- **Target Output Visual Soal 2 (Alamat Koneksi & Layanan Instance):**
  ```text
  +--------------------+--------------------+
  | AlamatKoneksi      | NamaLayanan        |
  +--------------------+--------------------+
  | PC-KASIR01\TOKO    | TOKO               |
  +--------------------+--------------------+
  (1 row affected)
  ```
  *(Catatan: Pada komputer yang memakai Default Instance, kolom AlamatKoneksi menampilkan nama PC dan NamaLayanan bernilai `MSSQLSERVER`).*

- **Target Output Visual Soal 3 (Koreksi Sintaks Variabel Sistem):**
  ```text
  +--------------------------------------------------------------------------------------------------------------------------------------------------------+
  | VersiMesinSQL                                                                                                                                          |
  +--------------------------------------------------------------------------------------------------------------------------------------------------------+
  | Microsoft SQL Server 2019/2022 ...                                                                                                                     |
  +--------------------------------------------------------------------------------------------------------------------------------------------------------+
  (1 row affected)
  ```

- **Target Output Visual Soal 4 (Verifikasi Layanan di PowerShell):**
  ```text
  Status   Name               DisplayName
  ------   ----               -----------
  Running  MSSQLSERVER        SQL Server (MSSQLSERVER)
  Running  SQLSERVERAGENT     SQL Server Agent (MSSQLSERVER)
  ```

- **Target Output Visual Soal 5 (3 Kolom Sekaligus):**
  ```text
  +-----------------+-----------------+--------------------------------------------------------------------------------------------------------------------+
  | AlamatServer    | NamaLayanan     | VersiSQL                                                                                                           |
  +-----------------+-----------------+--------------------------------------------------------------------------------------------------------------------+
  | DESKTOP-LAB     | MSSQLSERVER     | Microsoft SQL Server 2022 ...                                                                                      |
  +-----------------+-----------------+--------------------------------------------------------------------------------------------------------------------+
  (1 row affected)
  ```

- **Indikator Keberhasilan Soal 6–8 (Konfigurasi & Perancangan):**
  - **Soal 6:** Alamat SSMS pada ketiga skenario valid dan berhasil membuka sesi koneksi ke Database Engine.
  - **Soal 7:** Rekomendasi konfigurasi `AkademikDB` memenuhi kebutuhan penanganan huruf besar/kecil (`CI_AS`) tanpa error dan memiliki fitur SQL Server Agent aktif untuk backup otomatis tanpa beban lisensi komersial.
  - **Soal 8:** Analisis membedah batasan teknis 10 GB Express Edition, batasan legalitas lisensi Developer Edition, dan pemisahan 4 volume fisik sesuai beban I/O.

### B. Checklist Kesiapan Teknis
- [ ] Layanan `SQL Server (MSSQLSERVER)` atau Named Instance berstatus **Running** di `services.msc` maupun PowerShell.
- [ ] Kueri verifikasi satu baris (`SELECT @@VERSION;`, `SELECT @@SERVERNAME;`) berhasil dieksekusi di SSMS tanpa pesan error.
- [ ] Hasil tampilan kolom cocok persis dengan target format grid tabel SQL Server di atas.

> **Catatan Buku Pegangan Pengajar:**
> Kunci jawaban kueri T-SQL lengkap, skrip administrasi, dan pembahasan konseptual mendalam untuk seluruh butir latihan soal dihimpun secara terpisah pada dokumen pegangan instruktur ([KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md)) agar modul praktikum tetap dapat digunakan sebagai sarana latihan mandiri yang menantang bagi peserta didik.

---

## 8. Ringkasan Konsep & Refleksi Pemahaman

| Konsep / Perintah | Fungsi Inti | Hal Kritis yang Wajib Diingat |
| :--- | :--- | :--- |
| **Database Engine vs SSMS** | Mesin pengelola data vs aplikasi klien | Data disimpan dan diproses oleh *Windows Service* Engine, bukan oleh SSMS. |
| **Developer vs Express** | Pemilihan edisi instalasi | Developer lengkap & gratis khusus belajar; Express gratis untuk produksi tetapi mentok **10 GB/DB** & tanpa SQL Agent. |
| **Default vs Named Instance** | Isolasi mesin SQL Server di 1 OS | Default diakses lewat `localhost` (service `MSSQLSERVER`); Named diakses lewat `.\NamaInstance`. |
| **Collation (`CI_AS`)** | Aturan perbandingan huruf & bahasa | `CI` = huruf besar/kecil sama; sulit diubah setelah instalasi selesai. |
| **Data (`.mdf`), Log (`.ldf`), `TempDB`** | Jenis file fisik utama SQL Server | Pola I/O berbeda (*Random* vs *Sequential* vs *High-Churn*), idealnya dipisah volumenya di produksi. |
| `Get-Service *SQL*` | Cek status service SQL di PowerShell | Pastikan kolom `Status` bernilai `Running` sebelum membuka SSMS. |
| `SELECT @@VERSION;` | Kueri T-SQL paling dasar | `SELECT` menampilkan hasil ke layar; `@@VERSION`, `@@SERVERNAME`, `@@SERVICENAME` adalah variabel sistem. |

### Pertanyaan Refleksi
1. *Jelaskan dengan bahasamu sendiri memakai analogi sehari-hari: mengapa menyalahkan SSMS saat layanan `MSSQLSERVER` berstatus `Stopped` ibarat memarahi pelayan restoran ketika kompor di dapur belum dinyalakan?*
2. *Mengapa keputusan memilih **Collation** dan **Edisi** pada Modul 01 sangat memengaruhi ketiga sistem (`TokoRitelDB`, `AkademikDB`, dan `LogistikAuditDB`) meskipun kita belum menulis satu baris pun perintah pembuatan tabel?*
