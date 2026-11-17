# MODUL 02: AUTENTIKASI, KONFIGURASI JARINGAN, & CLIENT TOOLING
- **Subjek / Mata Kuliah:** Basis Data / Administrasi Microsoft SQL Server (2019 / 2022)
- **Tahap Kurikulum:** Level 1 — Fondasi & Konektivitas
- **Target Peserta:** Terpadu Bertingkat (SMK & Mahasiswa)
- **Fokus Materi:** Memahami mode autentikasi (*Windows Auth* vs *Mixed Mode*), protokol jaringan (`Shared Memory` vs `TCP/IP` Port `1433` & *SQL Server Browser*), konfigurasi client tooling (`SSMS`, `Azure Data Studio`, `sqlcmd`), serta verifikasi identitas login pertama.
- **Studi Kasus Berkelanjutan:** `TokoRitelDB`, `AkademikDB`, `LogistikAuditDB`

---

## 1. Prasyarat, Jembatan Konsep, & Peta Alur

### A. Pengingat Prasyarat Cepat (2 Menit)
Sebelum membuka akses jaringan dan menyiapkan aplikasi klien, pastikan dua konsep dari **Modul 01** sudah melekat kuat:
- **Konsep yang Harus Sudah Dipahami:** Perbedaan **Database Engine** (*Windows Service* di latar belakang) dengan **Client Tool** (aplikasi antarmuka), serta perbedaan alamat koneksi *Default Instance* (`localhost`) dan *Named Instance* (`.\SQLEXPRESS`).
- **Cek Kilat Prasyarat (Jawab Singkat Sebelum Lanjut):**
  1. Jika layanan `SQL Server (MSSQLSERVER)` di Windows berstatus `Stopped`, apakah kita bisa login melalui SSMS? *(Jawab singkat: `Tidak bisa, karena mesin Database Engine belum berjalan.`)*
  2. Apa arti kode `@@SERVICENAME` yang menghasilkan nilai `'SQLEXPRESS'`? *(Jawab singkat: `Instance yang terpasang adalah Named Instance bernama SQLEXPRESS, bukan Default Instance.`)*

### B. Jembatan Konsep: Siapa yang Boleh Masuk dan Lewat Pintu Mana?
Melanjutkan **analogi restoran** pada Modul 01: sekarang "dapur" (*SQL Server Database Engine*) sudah berdiri dan menyala. Namun, dapur yang menyala saja belum cukup untuk melayani pelanggan dari luar. Ada dua masalah nyata yang harus diselesaikan:

1. **Masalah Pintu Masuk (*Network Protocols*):** Secara bawaan setelah instalasi, protokol **TCP/IP** sering kali masih berstatus **Disabled** demi keamanan awal. Akibatnya, meskipun komputer Anda sendiri bisa masuk melalui jalur internal (*Shared Memory*), aplikasi kasir atau komputer lain dalam jaringan Wi-Fi/LAN akan ditolak karena "pintu jaringan" (**Port TCP `1433`**) belum dibuka. Lebih rumit lagi jika memakai *Named Instance*, karena pintu yang dipakai bisa berubah-ubah sehingga butuh layanan resepsionis bernama **SQL Server Browser** untuk memberi tahu nomor pintunya.
2. **Masalah Kartu Identitas (*Authentication Mode*):** Jika SQL Server hanya memakai *Windows Authentication*, hanya akun Windows di komputer/domain yang sama yang boleh masuk. Bagaimana jika aplikasi web berbasis Linux, bahasa pemrograman lain, atau perangkat di luar domain Windows ingin terhubung? Di sinilah **Mixed Mode Authentication** dibutuhkan agar SQL Server juga menerima pasangan username & password khusus SQL (seperti akun `sa`).

### C. Tujuan Pembelajaran & Peta Alur Kerja
Setelah menyelesaikan modul ini, peserta didik mampu:
1. Membedakan cara kerja *Windows Authentication* dan *Mixed Mode Authentication* beserta risiko keamanan akun `sa`.
2. Mengaktifkan protokol `TCP/IP`, menetapkan port statis `1433` di *SQL Server Configuration Manager*, dan memahami fungsi *SQL Server Browser*.
3. Menghubungkan **SSMS**, **Azure Data Studio**, dan **`sqlcmd`** ke instance lokal serta memverifikasi identitas login aktif memakai `SUSER_SNAME()` dan `DB_NAME()`.

**Peta Alur Kerja (*Connection, Network, & Authentication Flow*):**
```text
[Client Tool: SSMS / Azure Data Studio / sqlcmd]
        │
        ▼  (1. Mengetuk Pintu Jaringan)
[Protokol Aktif?] ──(TCP/IP Disabled / Salah Port)──► [Gagal Terhubung: Error 26 / 40]
        │ (TCP/IP Port 1433 Terbuka)
        ▼  (2. Pemeriksaan Identitas di Pintu Engine)
[Mode Autentikasi?] ──(Sandi Salah / Mode Ditolak)──► [Akses Ditolak: Error 18456]
        │ (Lolos Windows Auth / SQL Auth)
        ▼
[Sesi Terhubung! Cek Identitas: SELECT SUSER_SNAME(), DB_NAME();]
```

---

## 2. Kamus Konsep Jaringan, Autentikasi, & Perintah Dasar

### A. Dua Mode Autentikasi SQL Server

| Mode Autentikasi | Sumber Pemeriksaan Identitas | Kapan Digunakan? | Kelebihan & Catatan Penting |
| :--- | :--- | :--- | :--- |
| **Windows Authentication** | Sistem operasi Windows / Active Directory | Koneksi internal di komputer yang sama atau jaringan kantor berdomain Windows. | **Paling aman** (tidak mengirim sandi SQL terpisah, mendukung kebijakan sandi Windows). |
| **Mixed Mode** *(Windows + SQL Server Auth)* | Windows **atau** daftar Login internal SQL Server (misal `sa`) | Aplikasi web, aplikasi lintas OS, atau perangkat di luar domain Windows. | Wajib diaktifkan jika ingin login memakai username & password SQL. Wajib memasang sandi kuat untuk `sa`. |

### B. Protokol Jaringan pada *SQL Server Configuration Manager*

| Komponen Jaringan | Status Default (Umum) | Fungsi Utama | Keterangan Port |
| :--- | :---: | :--- | :--- |
| **Shared Memory** | *Enabled* | Jalur super cepat khusus koneksi **dari komputer yang sama** (*local*). | Tidak memakai port jaringan TCP. |
| **TCP/IP** | Sering *Disabled* *(pada Express/Developer tertentu)* | Jalur utama komunikasi antar komputer lewat jaringan LAN/Wi-Fi maupun driver aplikasi modern. | Standar Default Instance: **TCP Port `1433`**. |
| **SQL Server Browser** | *Stopped / Disabled* | Layanan "resepsionis" yang menerjemahkan nama *Named Instance* (misal `.\SQLEXPRESS`) ke port TCP aktifnya. | Menggunakan **UDP Port `1434`**. |

### C. Tiga *Client Tooling* Utama
1. **SQL Server Management Studio (SSMS v19/v20):** Aplikasi desktop Windows paling lengkap untuk administrasi server, melihat objek secara visual (*Object Explorer*), dan menjalankan kueri T-SQL.
2. **Azure Data Studio (ADS):** Aplikasi modern ringan lintas OS (Windows, macOS, Linux) dengan antarmuka mirip VS Code, cepat untuk menulis kueri dan mencatat hasil dalam format Notebook.
3. **`sqlcmd`:** Alat baris perintah (*Command-Line Interface*) bawaan untuk mengetes koneksi atau menjalankan perintah T-SQL langsung dari PowerShell/CMD.

### D. Perintah Verifikasi Koneksi & Identitas Pengguna
Pada bagian ini, Anda akan mempelajari tiga perintah dasar untuk mengecek keterbukaan port jaringan, menguji koneksi terminal, dan melihat identitas login aktif.

1. **PowerShell — Mengecek Apakah Pintu Port `1433` Terbuka:**
   - **Sintaks Baku:**
     ```powershell
     Test-NetConnection localhost -Port 1433
     ```
   - **Rincian Parameter:**
     - `localhost` *(Wajib)*: Alamat komputer tujuan (komputer sendiri).
     - `-Port 1433` *(Wajib)*: Nomor pintu jaringan SQL Server yang sedang diuji.
   - **Nilai Kembalian:** Menampilkan ringkasan jaringan; baris terpenting adalah `TcpTestSucceeded : True` (pintu terbuka) atau `False` (tertutup/service belum restart).

2. **CLI `sqlcmd` — Menguji Koneksi T-SQL dari Terminal:**
   - **Sintaks Baku:**
     ```powershell
     sqlcmd -S localhost -E -C -Q "SELECT SUSER_SNAME();"
     ```
   - **Rincian Parameter / Flag:**

     | Flag | Status | Contoh Nilai | Arti & Fungsi |
     | :--- | :---: | :--- | :--- |
     | `-S` | **Wajib** | `localhost` atau `.\SQLEXPRESS` | *Server*: alamat server dan nama instance tujuan. |
     | `-E` | *Bersyarat* | *(tanpa nilai)* | Login memakai **Windows Authentication** (*Trusted Connection*). Jangan digabung dengan `-U`. |
     | `-U` / `-P` | *Bersyarat* | `-U sa -P "SandiKuat123!"` | Login memakai **SQL Server Authentication** (username & password). |
     | `-C` | *Opsional* | *(tanpa nilai)* | *Trust Server Certificate*: mengizinkan sertifikat lokal di lingkungan belajar (*berguna pada `sqlcmd` / ODBC versi baru*). |
     | `-Q` | *Opsional* | `"SELECT SUSER_SNAME();"` | *Query*: menjalankan satu perintah T-SQL lalu langsung keluar kembali ke terminal. |

3. **Fungsi T-SQL Verifikasi Identitas (`SUSER_SNAME()` & `DB_NAME()`):**
   - **Sintaks Baku:**
     ```sql
     SELECT SUSER_SNAME() AS PenggunaAktif, DB_NAME() AS DatabaseAktif;
     ```
   - **Rincian Kata Kunci & Fungsi:**

     | Bagian Perintah | Tipe | Penjelasan Sederhana |
     | :--- | :--- | :--- |
     | `SELECT` | Kata kunci | Meminta SQL Server menampilkan nilai hasil ke layar. |
     | `SUSER_SNAME()` | Fungsi sistem *(tanpa argumen)* | Mengembalikan **nama akun login** yang sedang terhubung di sesi ini (misal `PC-RITEL\Kasir` atau `sa`). |
     | `DB_NAME()` | Fungsi sistem *(tanpa argumen)* | Mengembalikan **nama database** yang sedang dimasuki sesi saat ini (bawaannya `master` saat baru login). |
     | `AS` | Kata kunci alias | Memberi judul kolom pada tabel hasil. |

   - **Contoh Mikro (1 Baris Mandiri):**
     ```sql
     SELECT SUSER_SNAME() AS SiapaSaya;
     ```
     *Hasil eksekusi:* Menampilkan 1 baris nama login aktif, misalnya `DESKTOP-LAB\Admin` (jika login dengan Windows Auth) atau `sa` (jika login dengan SQL Auth).

---

## 3. Persiapan Lingkungan & Profil Studi Kasus

### A. Kebutuhan Lingkungan
- **Database Engine:** SQL Server 2019/2022 dari Milestone Modul 01 sudah berstatus `Running`.
- **Hak Akses Windows:** Akun Administrator lokal (dibutuhkan untuk mengubah konfigurasi jaringan dan me-restart service di *SQL Server Configuration Manager*).
- **Client Tooling Terpasang:** SSMS v19/v20, Azure Data Studio *(opsional/pendamping)*, dan `sqlcmd` (biasanya sudah terpasang bersama SSMS/ODBC Driver).

### B. Peta Skenario Kebutuhan Tiga Sistem (Akses & Konektivitas)
Sebelum masuk ke pembuatan tabel fisik di modul berikutnya, ketiga studi kasus berikut memetakan kebutuhan jaringan dan keamanan akses masing-masing sistem:

| Tema | Domain Sistem | Status dari Modul 01 | Target Kebutuhan Konektivitas pada Modul 02 |
| :--- | :--- | :--- | :--- |
| **Tema 1 (Inti)** | `TokoRitelDB` | Instance lokal terpasang & `Running` | Membuka `TCP/IP` Port `1433`, mengaktifkan *Mixed Mode* agar aplikasi kasir dapat login memakai SQL Auth, serta menguji koneksi SSMS, `sqlcmd`, dan ADS. |
| **Tema 2 (Transfer)** | `AkademikDB` | Spesifikasi instalasi tercatat | Menyiapkan akses multi-PC di jaringan laboratorium lokal (1 PC Server Dosen $\rightarrow$ 30 PC Mahasiswa) memakai *Named Instance* atau port statis `1433` + firewall. |
| **Tema 3 (Pendalaman)** | `LogistikAuditDB` | Rancangan kapasitas & pemisahan disk selesai | Menganalisis bahaya memakai login `sa` untuk aplikasi produksi dan membandingkan keamanan *Static Port* vs *Dynamic Port + SQL Browser*. |

---

## 4. Praktik Bertingkat Langkah demi Langkah — Studi Kasus `TokoRitelDB`

### Tahap 1 — Mengaktifkan Protokol `TCP/IP` & Port Statis `1433`
Untuk skenario `TokoRitelDB`, mesin kasir dan alat klien lain membutuhkan jalur jaringan `TCP/IP` yang konsisten di Port `1433`.

1. Tekan tombol Windows, ketik dan buka **SQL Server Configuration Manager** *(bukan SSMS)*.
2. Di panel kiri, buka **SQL Server Network Configuration** $\rightarrow$ klik **Protocols for MSSQLSERVER** *(atau nama instance Anda)*.
3. Pada panel kanan, klik kanan **TCP/IP** $\rightarrow$ pilih **Enable**.
4. Klik kanan lagi **TCP/IP** $\rightarrow$ pilih **Properties** $\rightarrow$ buka tab **IP Addresses**.
5. Gulir ke bagian paling bawah (**IPAll**):
   - Kosongkan kolom **TCP Dynamic Ports** *(hapus angka `0` atau angka acak jika ada, biarkan kosong)*.
   - Isi kolom **TCP Port** dengan `1433`.
   - Klik **Apply** $\rightarrow$ **OK**.
6. **Langkah Wajib:** Buka menu **SQL Server Services** di panel kiri $\rightarrow$ klik kanan **SQL Server (MSSQLSERVER)** $\rightarrow$ pilih **Restart** agar perubahan port berlaku.

### Tahap 2 — Mengaktifkan *Mixed Mode Authentication* & Akun `sa` + *State Tracing*
Sekarang kita siapkan agar `TokoRitelDB` mendukung login via *Windows Auth* maupun *SQL Auth* (`sa`).

1. Buka **SSMS**, login terlebih dahulu memakai **Windows Authentication** ke `localhost` *(centang **Trust server certificate** jika muncul di SSMS 20)*.
2. Di panel **Object Explorer** (kiri), klik kanan nama server paling atas $\rightarrow$ pilih **Properties**.
3. Buka halaman **Security** $\rightarrow$ pada bagian *Server authentication*, pilih **SQL Server and Windows Authentication mode** $\rightarrow$ klik **OK**.
4. **Restart Service SQL Server** *(klik kanan nama server di Object Explorer $\rightarrow$ **Restart**, atau lewat Configuration Manager)*.
5. Buka folder **Security** $\rightarrow$ **Logins** $\rightarrow$ klik ganda akun **`sa`**:
   - Pada halaman **General**: masukkan *Password* dan *Confirm password* yang kuat (misal `RitelLab#2026!`).
   - Pada halaman **Status**: pastikan bagian *Login* dipilih **Enabled** $\rightarrow$ klik **OK**.
6. Uji coba login baru di SSMS: klik **Connect $\rightarrow$ Database Engine**, ubah *Authentication* menjadi **SQL Server Authentication**, isi Login `sa` dan sandi yang baru dibuat, lalu jalankan 1 baris verifikasi di **New Query**:

```sql
-- Memverifikasi identitas sesi dan database posisi awal setelah login
SELECT SUSER_SNAME() AS PenggunaAktif, DB_NAME() AS DatabasePosisiAwal;
```

- **Perbedaan dengan Tahap 1:** Tahap 1 membuka jalur jaringan (pintu masuk), sedangkan Tahap 2 mengatur pemeriksa kartu identitas (keamanan login) lalu membuktikannya lewat T-SQL 1 baris.
- **Pelacakan Kondisi Sistem (*State Tracing*):**
  ```text
  Kondisi A (Login via Windows Authentication):
    Client mengirim token Windows user 'PC-RITEL\KasirAdmin'
    ──► SUSER_SNAME() mengembalikan : 'PC-RITEL\KasirAdmin'
    ──► DB_NAME()     mengembalikan : 'master'

  Kondisi B (Login via SQL Server Authentication 'sa'):
    Client mengirim pasangan Login 'sa' + Password
    ──► SUSER_SNAME() mengembalikan : 'sa'
    ──► DB_NAME()     mengembalikan : 'master'
  ```

### Tahap 3 — Verifikasi Koneksi Multi-Tooling (`Test-NetConnection`, `sqlcmd`, & Azure Data Studio)
Membuktikan bahwa layanan yang baru dikonfigurasi dapat dijangkau oleh berbagai klien, bukan hanya SSMS.

**Langkah 3A — Uji Port Jaringan lewat PowerShell:**
```powershell
Test-NetConnection localhost -Port 1433
```
Pastikan muncul `TcpTestSucceeded : True`.

**Langkah 3B — Uji Koneksi `sqlcmd` (Windows Auth vs SQL Auth):**
```powershell
# 1. Uji dengan Windows Authentication (-E)
sqlcmd -S localhost -E -C -Q "SELECT SUSER_SNAME() AS LoginWindows, DB_NAME() AS PosisiDB;"

# 2. Uji dengan SQL Server Authentication (-U dan -P)
sqlcmd -S localhost -U sa -P "RitelLab#2026!" -C -Q "SELECT SUSER_SNAME() AS LoginSQL, DB_NAME() AS PosisiDB;"
```

**Langkah 3C — Uji Koneksi di Azure Data Studio (ADS):**
1. Buka **Azure Data Studio** $\rightarrow$ klik **New Connection**.
2. Isi *Server*: `localhost`, *Authentication type*: `SQL Login` (atau `Windows Authentication`), centang *Trust server certificate: True* jika diminta, lalu klik **Connect**.

- **Pelacakan Alur Data (*Step-by-Step Trace*):**
  ```text
  Terminal (sqlcmd -S localhost -U sa ...)
        │
        ▼  (1. Mencari alamat localhost pada port TCP 1433)
  Pintu TCP/IP 1433 Menerima Permintaan
        │
        ▼  (2. Engine memeriksa apakah Mixed Mode aktif & sandi 'sa' cocok)
  Lolos Autentikasi
        │
        ▼  (3. Menjalankan query -Q "SELECT SUSER_SNAME()...")
  Terminal mencetak hasil 'sa' dan 'master', lalu koneksi otomatis ditutup.
  ```

### Tahap 4 — Hardening Keamanan Jaringan & Prinsip Akun Produksi (Pendalaman)

> **Jalur Pendalaman (Mahasiswa / Pengayaan SMK):** Tahap ini membahas praktik pengamanan jaringan dan kredensial agar konfigurasi lab tidak terbawa mentah-mentah ke server produksi.

Mengamankan konfigurasi default laboratorium sebelum berpindah ke server produksi nyata:
1. **Jangan Pernah Memakai `sa` di Aplikasi Produksi:** Akun `sa` adalah *superuser* yang bisa menghapus seluruh database atau membaca file sistem jika disalahgunakan. Di produksi, setelah selesai instalasi, buatlah akun admin pengganti lalu **Disable** atau ganti nama akun `sa`, dan berikan akun khusus berhak terbatas (*least privilege*) untuk aplikasi kasir/gudang (teknik pembuatannya dipelajari pada Level 7).
2. **Jangan Membuka Port `1433` ke Internet Publik:** Port `1433` hanya boleh diakses dari jaringan internal aplikasi (*application server*) melalui aturan **Windows Firewall / Network Firewall** yang membatasi alamat IP sumber (*IP Allowlist*).
3. **Waspadai *Encryption & Certificate*:** Pada driver modern (SSMS 20+, ODBC 18+, ADS), opsi `Encrypt = Mandatory` aktif secara default. Untuk lab lokal kita memakai *Trust Server Certificate*, sedangkan di produksi wajib memasang sertifikat SSL/TLS resmi agar sandi SQL tidak disadap (*Man-in-the-Middle*).

---

## 5. Verifikasi Output & Panduan Troubleshooting

### A. Ringkasan Ekspektasi Hasil Tahap 1–4

| Tahap | Jalur | Objek yang Diuji | Indikator Berhasil |
| :--- | :---: | :--- | :--- |
| **Tahap 1** | Inti | Configuration Manager (`TCP/IP`) | `TCP/IP` berstatus `Enabled`, `TCP Port` = `1433` pada `IPAll`, service sudah di-restart. |
| **Tahap 2** | Inti | *Mixed Mode* & `sa` di SSMS | Berhasil login memakai `sa`; `SELECT SUSER_SNAME();` mengembalikan `'sa'`. |
| **Tahap 3** | Inti | PowerShell & `sqlcmd` | `TcpTestSucceeded : True`; kedua perintah `sqlcmd` (`-E` dan `-U sa`) mencetak hasil tanpa error. |
| **Tahap 4** | Pendalaman | Evaluasi Keamanan Produksi | Memahami pemisahan akun aplikasi vs `sa`, firewall port `1433`, dan kebutuhan sertifikat TLS. |

### B. Kesalahan Umum saat Konfigurasi Autentikasi & Jaringan

| Gejala / Pesan Kesalahan | Penyebab Umum | Solusi Tepat |
| :--- | :--- | :--- |
| `Login failed for user 'sa'. (Error 18456)` | (1) Masih memakai *Windows Authentication Only*, (2) lupa **Restart Service** setelah memilih *Mixed Mode*, atau (3) status akun `sa` di menu *Properties $\rightarrow$ Status* masih **Disabled**. | Ubah ke *Mixed Mode*, aktifkan `Enabled` pada login `sa`, dan **wajib Restart SQL Server Service**. |
| `A connection was successfully established with the server, but then an error occurred during the login process. (The certificate chain was issued by an authority that is not trusted)` | SSMS v20 / ADS / `sqlcmd` baru mengaktifkan enkripsi wajib secara default, sementara server lokal belum memiliki sertifikat SSL terpercaya. | Centang **Trust server certificate** pada jendela login SSMS/ADS, atau tambahkan flag `-C` pada `sqlcmd`. |
| `TcpTestSucceeded : False` saat cek port `1433` | Protokol `TCP/IP` masih *Disabled*, lupa *Restart Service*, atau Anda memakai *Named Instance* yang portnya masih dinamis (bukan `1433`). | Aktifkan `TCP/IP`, isi `TCP Port = 1433` di `IPAll`, kosongkan *Dynamic Ports*, dan **Restart Service**. |
| Komputer klien lain gagal koneksi padahal komputer server bisa login `localhost` | **Windows Defender Firewall** pada komputer server memblokir koneksi masuk pada TCP Port `1433` (atau UDP `1434` untuk *Named Instance*). | Tambahkan *Inbound Rule* di Windows Firewall untuk mengizinkan TCP Port `1433` dari subnet jaringan lokal yang tepercaya. |

---

## 6. Latihan Soal

*Perhatikan tabel acuan profil autentikasi dan konfigurasi endpoint jaringan berikut sebelum mengerjakan latihan soal:*

#### Tabel Acuan Parameter Autentikasi & Jaringan Praktik

| Identitas Target / PC | Mode Autentikasi | Port TCP / Protokol | Akun Login Aktif | Target Posisi Database | Keterangan Endpoint & Keamanan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `TOKO-PC\Kasir01` | Windows Authentication | Lokal / Shared Memory | `TOKO-PC\Kasir01` | `master` | Login kasir lokal terintegrasi akun OS Windows. |
| `localhost` (sa) | SQL Server Authentication | TCP `1433` (Statis) | `sa` | `master` | Akun System Administrator untuk pemeliharaan server lokal. |
| `SRV-KAMPUS\AKADEMIK`| Mixed Mode | TCP Port Statis / Dinamis | `sa` / `AkunSiswa` | `AkademikDB` | Server lab kampus diakses 30 komputer klien via LAN (`192.168.10.10`). |
| `SRV-LOGISTIK` | Windows Auth / Mixed | TCP `1433` (Firewall Whitelist) | `app_logistik_user` | `LogistikAuditDB` | Server produksi logistik dengan isolasi port dan hak akses terendah. |

---

#### Daftar Tugas Praktik
1. Tulis perintah T-SQL untuk menampilkan identitas akun yang sedang login dan nama database yang sedang aktif dalam dua kolom berlabel `IdentitasMasuk` dan `PosisiDatabase`.
2. Jelaskan langkah konfigurasi yang terlewat apabila pengubahan opsi *Server authentication* menjadi *SQL Server and Windows Authentication mode* sudah diklik OK di SSMS, namun login `sa` tetap gagal dengan pesan `Error 18456`.
3. Tulis perintah PowerShell / terminal untuk memperbaiki kesalahan argumen pada sintaks `sqlcmd` berikut agar dapat terhubung dengan akun `sa` tanpa memicu konflik mode autentikasi:
   ```powershell
   sqlcmd -S localhost -E -U sa -C -Q "SELECT SUSER_SNAME();"
   ```
4. Tuliskan langkah perbaikan konfigurasi pada *SQL Server Configuration Manager* $\rightarrow$ *TCP/IP Properties* bagian *IPAll* jika kolom `TCP Dynamic Ports` bernilai `52411` dan `TCP Port` kosong, agar server mendengarkan port statis `1433` secara permanen.
5. Tulis perintah T-SQL tunggal untuk menampilkan empat kolom sekaligus dalam satu baris hasil: `PenggunaAktif` (`SUSER_SNAME()`), `DatabasePosisi` (`DB_NAME()`), `AlamatServer` (`@@SERVERNAME`), dan `LayananWindows` (`@@SERVICENAME`).
6. Tentukan pengaturan atau flag verifikasi sertifikat yang harus diaktifkan pada masing-masing client tool (**SSMS v20+**, **Azure Data Studio**, dan **`sqlcmd`**) ketika muncul kendala *"The certificate chain was issued by an authority that is not trusted"*.
7. Tentukan 3 komponen jaringan dan sistem (layanan, protokol, dan aturan firewall) yang wajib diaktifkan pada server lab `SRV-KAMPUS` (`192.168.10.10\AKADEMIK`) agar dapat diakses secara serentak oleh 30 PC siswa di jaringan LAN, serta jelaskan peran teknis masing-masing komponen.
8. Tulis perintah PowerShell menggunakan `Test-NetConnection` untuk memverifikasi keterbukaan port TCP `1433` pada `localhost`, lalu jelaskan indikator nilai keluaran yang menandakan port telah siap melayani koneksi.
9. Analisis risiko keamanan pada `LogistikAuditDB`: jelaskan 3 bahaya fatal jika kredensial `sa` disematkan langsung di dalam kode aplikasi web gudang, serta jelaskan prinsip keamanan apa yang harus diterapkan untuk menggantikan penggunaan akun `sa`.
10. Analisis perbandingan arsitektur keamanan: bandingkan penggunaan *TCP Dynamic Ports* + layanan *SQL Server Browser* (UDP `1434`) dengan port TCP statis tetap (`1433`) tanpa *SQL Server Browser* dari sisi efektivitas aturan firewall dan rekomendasi server produksi.

---

## 7. Panduan Evaluasi & Checklist Mandiri

*Gunakan petunjuk ringkas berikut untuk mencocokkan hasil eksekusi kueri dan verifikasi konfigurasi Anda secara mandiri:*

### A. Indikator Keberhasilan Tugas & Target Output Visual Kueri

- **Target Output Visual Soal 1 (Identitas & Database Sesi Aktif):**
  ```text
  +-----------------------------------+--------------------+
  | IdentitasMasuk                    | PosisiDatabase     |
  +-----------------------------------+--------------------+
  | TOKO-PC\Kasir01                   | master             |
  +-----------------------------------+--------------------+
  (1 row affected)
  ```
  *(Catatan: Jika login menggunakan akun `sa`, kolom `IdentitasMasuk` akan menampilkan `sa`).*

- **Target Output Visual Soal 3 (Koreksi Perintah `sqlcmd`):**
  ```text
  sa
  ```

- **Target Output Visual Soal 5 (4 Kolom Verifikasi Lengkap):**
  ```text
  +---------------+----------------+-----------------+-----------------+
  | PenggunaAktif | DatabasePosisi | AlamatServer    | LayananWindows  |
  +---------------+----------------+-----------------+-----------------+
  | sa            | master         | DESKTOP-LAB     | MSSQLSERVER     |
  +---------------+----------------+-----------------+-----------------+
  (1 row affected)
  ```

- **Target Output Visual Soal 8 (Pengujian Port Jaringan PowerShell):**
  ```text
  ComputerName     : localhost
  RemoteAddress    : ::1
  RemotePort       : 1433
  InterfaceAlias   : Loopback Pseudo-Interface 1
  SourceAddress    : ::1
  TcpTestSucceeded : True
  ```

- **Indikator Keberhasilan Soal 2, 4, 6, 7, 9, 10 (Analisis & Troubleshooting):**
  - **Soal 2 & 4:** Mengetahui kewajiban me-restart service SQL Server setelah mengubah mode autentikasi atau port TCP/IP di Configuration Manager.
  - **Soal 6:** Opsi enkripsi / *Trust server certificate* (flag `-C` pada `sqlcmd`) berhasil mengatasi galat sertifikat pada ketiga tool.
  - **Soal 7 & 8:** Server kampus terverifikasi membuka port statis dan melayani koneksi jaringan lokal tanpa hambatan firewall.
  - **Soal 9 & 10:** Analisis membedah prinsip hak akses terendah (*Principle of Least Privilege*) serta keunggulan port statis terhadap proteksi firewall.

### B. Checklist Kesiapan Teknis
- [ ] Protokol `TCP/IP` berstatus **Enabled** dengan port statis `1433` (atau port instance terdokumentasi) dan `Test-NetConnection` menghasilkan `TcpTestSucceeded : True`.
- [ ] Pengujian login SSMS berhasil menggunakan Windows Authentication dan SQL Server Authentication (`sa`).
- [ ] Perintah `sqlcmd` berjalan lancar dari terminal tanpa pesan error sertifikat atau penolakan login.

> **Catatan Buku Pegangan Pengajar:**
> Kunci jawaban kueri T-SQL lengkap, skrip administrasi terminal, dan pembahasan konseptual mendalam untuk seluruh butir latihan soal dihimpun secara terpisah pada dokumen pegangan instruktur ([KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md)) agar modul praktikum tetap dapat digunakan sebagai sarana latihan mandiri yang menantang bagi peserta didik.

---

## 8. Ringkasan Konsep & Refleksi Pemahaman

| Komponen / Perintah | Kegunaan Inti | Detail Kritis |
| :--- | :--- | :--- |
| **Windows Auth vs Mixed Mode** | Mengatur siapa yang dipercaya masuk ke Engine | *Mixed Mode* mendukung Windows Auth + SQL Login (`sa`), wajib *Restart Service* setelah diubah. |
| **TCP/IP (`1433`)** | Jalur koneksi jaringan antar komputer/aplikasi | Kosongkan *Dynamic Ports* dan isi `1433` di `IPAll` jika ingin port statis. |
| **SQL Server Browser (`UDP 1434`)** | Penerjemah nama *Named Instance* ke nomor port | Dibutuhkan saat memakai *Named Instance* berport dinamis di jaringan. |
| **SSMS / ADS / `sqlcmd`** | Tiga *client tool* utama SQL Server | Gunakan *Trust server certificate* / `-C` saat belajar di lab lokal dengan enkripsi default. |
| `Test-NetConnection` | Cek terbukanya port jaringan di PowerShell | `TcpTestSucceeded : True` menandakan port siap menerima koneksi. |
| `SUSER_SNAME()` & `DB_NAME()` | Cek siapa yang login & posisi database aktif | Membantu memastikan konteks sesi benar sebelum mengeksekusi perintah apa pun. |

### Pertanyaan Refleksi
1. *Mengapa mengaktifkan protokol `TCP/IP` dan mengubah mode autentikasi di SQL Server selalu mewajibkan proses **Restart Service**, dan apa dampaknya jika itu dilakukan di jam sibuk produksi tanpa pemberitahuan?*
2. *Setelah menyelesaikan Modul 01 dan Modul 02, bagaimana hubungan berurutan antara **Service SQL Server (Modul 01)** $\rightarrow$ **Protokol Jaringan & Autentikasi (Modul 02)** $\rightarrow$ **Client Tooling** sebelum kita mulai membuat database fisik `TokoRitelDB`, `AkademikDB`, dan `LogistikAuditDB` di Modul 03?*
