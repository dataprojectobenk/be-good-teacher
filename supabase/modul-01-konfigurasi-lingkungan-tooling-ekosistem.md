# MODUL PRAKTIKUM SUPABASE & POSTGRESQL
## Modul 1: Konfigurasi Lingkungan, Tooling, & Ekosistem Supabase
- **Target Level:** Terpadu Bertingkat (SMK Rekayasa Perangkat Lunak / PPLG & Mahasiswa Informatika/Ilmu Komputer)
- **Tema Berkelanjutan Aktif:** 
  - Tema 1 (Inti): `lab-inventaris-db` (Sistem Inventaris Laboratorium Komputer)
  - Tema 2 (Transfer): `perpustakaan-db` (Sistem Keanggotaan & Perpustakaan Digital)
  - Tema 3 (Pendalaman): `log-transaksi-db` (Sistem Log Transaksi & Multi-Platform API)

---

### Bagian 1: Prasyarat, Jembatan Konsep, & Peta Alur `[INTI: SMK & Mahasiswa]`

#### A. Pengingat Prasyarat Cepat (2 Menit)
- **Konsep yang Harus Sudah Dipahami:**
  1. *Model Client-Server*: Pemahaman bahwa antarmuka pengguna (klien/frontend) berkomunikasi dengan server basis data melalui jaringan protokol IP dan nomor *port*.
  2. *Relational Database Management System (RDBMS)*: Konsep dasar penyimpanan data dalam bentuk tabel relasional yang diatur oleh engine SQL (Structured Query Language).
- **Cek Kilat Prasyarat (Jawab Singkat Sebelum Lanjut):**
  1. *Apa peran nomor Port dalam jaringan komputer?*  
     **Jawaban Singkat:** Nomor port berfungsi sebagai pintu gerbang virtual pada sistem operasi host untuk mengarahkan lalu lintas data jaringan ke proses atau layanan spesifik (misal: port 80/443 untuk Web HTTP/HTTPS, port 5432 untuk server PostgreSQL default).
  2. *Mengapa berkas kredensial seperti `.env` dilarang keras di-commit ke Git repositori publik?*  
     **Jawaban Singkat:** Karena berkas `.env` memuat rahasia sistem (*credentials*) seperti kata sandi database dan token API. Jika terunggah ke repositori publik, pihak luar dapat mengambil alih, memanipulasi, atau menghapus seluruh data pada server.

---

#### B. Jembatan Konsep: Mengapa Supabase & BaaS Ada? (*Why Before How*)
Pada arsitektur pengembangan aplikasi tradisional, menyalakan sistem backend yang lengkap memerlukan tahapan manual yang rumit dan rentan kesalahan:
1. Menyewa *Virtual Private Server* (VPS) Linux dan memasang engine PostgreSQL dari baris perintah.
2. Mengonfigurasi firewall, enkripsi SSL/TLS, otentikasi pengguna, dan strategi *connection pooling* agar database tidak tumbang saat ribuan pengguna mengakses secara serentak.
3. Menulis ratusan baris kode *boilerplate* API (REST/GraphQL) hanya untuk melakukan operasi dasar CRUD (*Create, Read, Update, Delete*).
4. Merancang sistem otentikasi login/register, token JWT (*JSON Web Token*), dan server terpisah untuk kebutuhan komunikasi data instan (*Realtime WebSockets*).

**Supabase hadir sebagai platform Backend-as-a-Service (BaaS) berbasis Open Source.**  
Supabase tidak menciptakan engine database baru buatan sendiri; Supabase menyediakan **PostgreSQL kelas industri murni (*Enterprise-Grade PostgreSQL*)** yang langsung dibundel dengan:
- **PostgREST**: Mengubah skema tabel database Anda menjadi REST API instan secara otomatis tanpa perlu menulis kode backend manual.
- **GoTrue**: Layanan otentikasi pengguna (email, OAuth Google/GitHub, magic link) yang terintegrasi langsung dengan tabel identitas PostgreSQL.
- **Realtime Engine**: Mengalirkan mutasi data (*insert, update, delete*) secara instan ke aplikasi frontend melalui protokol WebSockets.
- **Supavisor**: Mesin *Connection Pooler* modern berkinerja tinggi yang mengelola ribuan koneksi konkuren dan menjembatani kompatibilitas jaringan IPv4/IPv6.

Dengan Supabase, seorang pengembang perangkat lunak mendapatkan kecepatan pengembangan sekelas *BaaS modern* tanpa kehilangan kedalaman, integritas relasional, dan fleksibilitas bahasa SQL murni PostgreSQL.

---

#### C. Tujuan Pembelajaran & Peta Alur Kerja
*Setelah menyelesaikan Modul 1 ini, peserta didik mampu:*
1. Mengidentifikasi anatomi proyek Supabase Cloud dan membedakan secara tegas fungsi 4 kredensial kritis: `Project URL`, `anon key`, `service_role key`, dan `Database Connection URI`.
2. Mendiagnosis perbedaan jalur jaringan antara **Direct Connection Port `5432`** (IPv6) dan **Supavisor Connection Pooler Port `6543`** (IPv4 compatible) untuk mencegah kegagalan timeout koneksi di lingkungan lab/kantor.
3. Menghubungkan client database visual (*Visual Studio Code Database Client* dan *DBeaver*) ke database PostgreSQL Supabase dengan konfigurasi SSL yang tepat.
4. Menginisialisasi lingkungan Supabase Local CLI menggunakan Docker untuk pengujian mandiri di komputer lokal tanpa koneksi internet.

**Peta Alur Kerja Arsitektur Supabase (*Data & Infrastructure Flow*):**

```text
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                              LINGKUNGAN KLIEN / PENGEMBANG                             │
│                                                                                        │
│  [Aplikasi Client / Frontend]       [IDE: VS Code / DBeaver]     [Supabase Local CLI]  │
│  (React, Vue, Mobile App, cURL)     (Kueri SQL, Manajemen Skema) (Docker Local Stack)  │
└──────────────────────────┬───────────────────────┬─────────────────────────┬───────────┘
                           │                       │                         │
            Headers:       │                       │ Pooler URI              │ npx supabase
      apikey: anon_key     │                       │ Port 6543               │ init / start
                           ▼                       ▼                         ▼
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                                 INFRASTRUKTUR SUPABASE                                 │
│                                                                                        │
│   ┌────────────────────────────────┐     ┌─────────────────────────────────────────┐   │
│   │   PostgREST (Auto REST API)    │     │      Supavisor (Connection Pooler)      │   │
│   │   Port 443 / HTTPS             │     │      Port 6543 (Session/Transaction)    │   │
│   └──────────────┬─────────────────┘     └────────────────────┬────────────────────┘   │
│                  │                                            │                        │
│                  ▼                                            ▼                        │
│   ┌────────────────────────────────────────────────────────────────────────────────┐   │
│   │               PostgreSQL Core Engine (Port 5432 / Direct Connect)              │   │
│   │                                                                                │   │
│   │  • Tabel Relasional Data     • GoTrue Auth (`auth.users`)   • Ekstensi Database│   │
│   │  • Row Level Security (RLS)  • Stored Procedure (PL/pgSQL)  • Realtime WAL Log │   │
│   └────────────────────────────────────────────────────────────────────────────────┘   │
└────────────────────────────────────────────────────────────────────────────────────────┘
```

---

### Bagian 2: Kamus Kredensial, Klausa Jaringan, & Argumen Dasar `[INTI: SMK & Mahasiswa]`

Sebelum menyusun berkas konfigurasi atau menulis skrip koneksi apapun, pahami kamus parameter teknis berikut secara mendalam:

#### A. Anatomi Kredensial Proyek Supabase
| Nama Kredensial | Karakter Kunci | Hak Akses RLS | Batasan Keamanan Arsitektural |
| :--- | :--- | :--- | :--- |
| **`Project URL`** | `https://[ref].supabase.co` | N/A (Titik akhir) | **Publik.** Endpoint dasar untuk mengakses REST API PostgREST, Auth GoTrue, dan Storage via protokol HTTPS. |
| **`anon key`** | Format string JWT diawali `eyJ...` | **Wajib Patuh RLS** | **Aman untuk Sisi Klien.** Digunakan pada aplikasi web/mobile pengguna (React/Vue/Flutter). Hanya dapat membaca data yang diizinkan oleh kebijakan *Row Level Security* (RLS). |
| **`service_role key`** | Format string JWT diawali `eyJ...` | **BYPASS RLS MUTLAK** | **RAHASIA TINGKAT TINGGI.** Memiliki wewenang superuser untuk melewati (*bypass*) seluruh proteksi RLS. **DILARANG KERAS** diletakkan pada kode JavaScript frontend, repositori Git, atau aplikasi mobile! |
| **`DB Connection URI`** | `postgresql://postgres:[pwd]@[host]:[port]/postgres` | Superuser `postgres` | **Rahasia Server.** Tautan koneksi terpadu untuk menghubungkan aplikasi backend (Laravel, Node.js, Go) atau database IDE (VS Code, DBeaver). |

---

#### B. Perbandingan Port Koneksi: Direct (`5432`) vs Connection Pooler Supavisor (`6543`)
Koneksi ke database PostgreSQL di Supabase terbagi menjadi dua jalur dengan karakteristik jaringan yang berbeda nyata:

| Parameter Jaringan | Direct Connection (Port `5432`) | Supavisor Pooler (Port `6543`) |
| :--- | :--- | :--- |
| **Nomor Port** | `5432` | `6543` |
| **Protokol IP Jaringan** | **Wajib IPv6 murni** (atau add-on IPv4 berbayar) | **Kompatibel Penuh IPv4 & IPv6** |
| **Kasus Penggunaan Terbaik** | Eksekusi DDL skema besar via CLI lokal atau server internal cloud yang mendukung IPv6. | **Koneksi dari Indonesia (Indihome, Telkomsel, FirstMedia, jaringan Lab SMK/Kampus) via VS Code, DBeaver, Laravel, dan Serverless Functions.** |
| **Mode Pooler Tersedia** | Tidak ada (koneksi langsung 1-ke-1) | 1. **Session Mode** (Mendukung transaksi berkelanjutan, cocok untuk IDE seperti VS Code/DBeaver).<br>2. **Transaction Mode** (Koneksi instan dilepas setelah kueri selesai, ideal untuk Serverless/API). |
| **Batas Jumlah Koneksi** | Terbatas (sesuai spesifikasi RAM instance compute). | Sangat tinggi (mampu menampung ribuan koneksi konkuren). |

---

#### C. Perintah Perdana Pemeriksaan Engine SQL
Setelah client database terhubung ke PostgreSQL, jalankan perintah diagnostik berikut untuk memverifikasi kondisi server:

1. **Memeriksa Versi Engine PostgreSQL:**
   ```sql
   -- Sintaks Diagnostik 1
   SELECT version();
   ```
   - **Kegunaan:** Menampilkan rincian versi PostgreSQL, sistem operasi host, dan kompilasi arsitektur 64-bit yang digunakan Supabase.
   - **Contoh Hasil:** `PostgreSQL 15.6 on aarch64-unknown-linux-gnu, compiled by gcc...`

2. **Memeriksa Identitas Sesi & Port Jaringan yang Aktif:**
   ```sql
   -- Sintaks Diagnostik 2
   SELECT 
       current_database() AS nama_basisdata,
       current_user AS pengguna_aktif,
       inet_server_addr() AS ip_server,
       inet_server_port() AS port_server;
   ```
   - **Kegunaan:** Memastikan kueri dieksekusi di database `postgres` sebagai user superuser `postgres`.

---

#### D. Perintah Utama Supabase Local CLI
Bagi pengembang yang ingin bekerja secara *offline* di mesin lokal, Supabase menyediakan CLI berbasis Docker:

| Perintah CLI | Argumen Tambahan | Efek Eksekusi pada Lingkungan Lokal |
| :--- | :--- | :--- |
| `npx supabase init` | `--workdir [path]` | Membuat folder konfigurasi `./supabase` yang memuat berkas `config.toml`. |
| `npx supabase start` | `-x [komponen]` | Mengunduh dan menyalakan kontainer Docker (Postgres, Studio, Kong API Gateway, GoTrue, PostgREST). |
| `npx supabase status` | N/A | Menampilkan tabel seluruh kredensial lokal (URL Studio: `http://localhost:54323`, DB URL: `postgresql://postgres:postgres@localhost:54322/postgres`). |
| `npx supabase stop` | `--no-backup` | Menghentikan seluruh kontainer Docker Supabase di komputer lokal untuk menghemat memori RAM. |

---

### Bagian 3: Prasyarat Lingkungan & Checkpoint 3 Tema Berkelanjutan `[INTI: SMK & Mahasiswa]`

Sesuai standar kurikulum, Modul 1 menginisialisasi 3 tema praktikum berkelanjutan yang akan digunakan secara konsisten hingga Modul 7:

| Tema Berkelanjutan | Identitas Proyek / Database | Status Sebelum Modul 1 | Penambahan & Milestone pada Modul 1 |
| :--- | :--- | :--- | :--- |
| **Tema 1 (Inti)** | `lab-inventaris-db` | Proyek belum dibuat | 1. Registrasi proyek cloud di region Singapore.<br>2. Pencatatan berkas konfigurasi aman `.env.local`.<br>3. Verifikasi koneksi IDE VS Code / DBeaver ke port pooler 6543. |
| **Tema 2 (Transfer)** | `perpustakaan-db` | Proyek belum dibuat | 1. Pembuatan profil koneksi database kedua khusus untuk studi kasus perpustakaan.<br>2. Verifikasi status koneksi terisolasi dari Tema 1. |
| **Tema 3 *(Pendalaman)*** | `log-transaksi-db` | Lingkungan belum disiapkan | 1. Inisialisasi stack lokal Supabase CLI.<br>2. Pengecekan kesiapan dashboard Supabase Studio lokal di port `54323`. |

---

### Bagian 4: Contoh Implementasi Bertingkat — Tema 1 (`lab-inventaris-db`) *(4 Contoh Mikro)*

#### Contoh 1 — Registrasi Proyek Cloud & Ekstraksi Kredensial Aman `[INTI: SMK & Mahasiswa]`
Pada langkah pertama, pengembang membuat instance proyek baru di portal Supabase Cloud dan menyusun berkas variabel lingkungan (`.env.local`) yang terstandarisasi.

```bash
# Direktori Kerja Proyek: lab-inventaris-db
# File: .env.example (Dapat dikomit ke Git sebagai panduan tim)

# 1. Endpoint API Publik Supabase
NEXT_PUBLIC_SUPABASE_URL=https://xyzcompanyprojectref.supabase.co

# 2. Kunci Publik Klien (Aman dipakai di browser dengan proteksi RLS)
NEXT_PUBLIC_SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.contoh_anon_key_publik_anda

# 3. Kunci Rahasia Backend (HANYA untuk skrip server rahasia, JANGAN taruh di frontend!)
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.contoh_service_role_rahasia_anda

# 4. Connection String Database (Jalur Pooler Supavisor Port 6543 - Session Mode)
DATABASE_URL=postgresql://postgres.xyzcompanyprojectref:[PASSWORD_ANDA]@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require
```

- **Penjelasan Langkah Kritis:**
  1. Buat akun di `https://supabase.com`, lalu klik tombol **New Project**.
  2. Beri nama proyek: `lab-inventaris-db`.
  3. Masukkan kata sandi database yang kuat (misal: kombinasi huruf besar, kecil, angka, dan simbol). **Catat kata sandi ini di tempat aman karena tidak dapat dilihat kembali.**
  4. Pilih Region terdekat: **Southeast Asia (Singapore) - `ap-southeast-1`** untuk menjamin latensi jaringan terendah dari Indonesia (< 40 ms).
  5. Navigasi ke menu **Project Settings > API** untuk menyalin `Project URL`, `anon key`, dan `service_role key`.
  6. Navigasi ke menu **Project Settings > Database > Connection string** untuk menyalin format koneksi pooler.

- **Hasil Keluaran Verifikasi:**
  Berkas `.env.local` tersimpan di komputer pengembang dan telah terdaftar di dalam berkas `.gitignore` agar tidak bocor ke publik.

---

#### Contoh 2 — Menghubungkan Visual Studio Code via Supavisor Pooler Port 6543 `[INTI: SMK & Mahasiswa]`
Sebagian besar ISP di Indonesia (Indihome, Telkomsel, jaringan sekolah) belum mendukung perutean IPv6 murni. Oleh karena itu, menghubungkan VS Code langsung ke port default 5432 seringkali menghasilkan pesan error `Connection timed out`. Kita wajib menghubungkan VS Code melalui Connection Pooler Supavisor pada port `6543`.

```json
// Konfigurasi Ekstensi "PostgreSQL" di VS Code (Chris Kolkman / Database Client)
// Disimpan secara internal oleh ekstensi:
{
  "name": "Supabase - Lab Inventaris (Pooler)",
  "host": "aws-0-ap-southeast-1.pooler.supabase.com",
  "port": 6543,
  "database": "postgres",
  "username": "postgres.xyzcompanyprojectref",
  "password": "PasswordDatabaseKuatAnda123!",
  "ssl": {
    "rejectUnauthorized": false
  }
}
```

- **Perbedaan Krusial dengan Port 5432:**
  - Username pada port pooler `6543` **wajib** menggunakan format gabungan: `postgres.[PROJECT_REF]` (contoh: `postgres.xyzcompanyprojectref`), bukan sekadar `postgres`.
  - Host server pooler beralamat pada domain pooler AWS regional: `aws-0-ap-southeast-1.pooler.supabase.com`.
  - Parameter SSL wajib diaktifkan (`require` / `rejectUnauthorized: false`) karena Supabase Cloud menolak seluruh koneksi jaringan tanpa enkripsi TLS/SSL.

- **Pelacakan Status Koneksi (*State Tracing*):**
  ```text
  [VS Code Client] 
         │ (Mengirim request koneksi TCP ke Port 6543 via jaringan IPv4 lokal)
         ▼
  [aws-0-ap-southeast-1.pooler.supabase.com:6543]
         │ (Handshake SSL/TLS Enkripsi Berhasil)
         ▼
  [Autentikasi User: postgres.xyzcompanyprojectref]
         │ (Validasi Password Berhasil -> Dialokasikan ke sesi database)
         ▼
  [Status IDE: CONNECTED (Siap menjalankan kueri SQL)]
  ```

- **Kueri Verifikasi Pertama:**
  ```sql
  -- Eksekusi di VS Code Query Editor:
  SELECT 
      current_database() AS database_aktif,
      inet_server_port() AS port_terhubung;
  ```

- **Hasil Keluaran Nyata:**
  | database_aktif | port_terhubung |
  | :--- | :--- |
  | `postgres` | `6543` |

---

#### Contoh 3 — Inisialisasi Supabase Local CLI Menggunakan Docker `[INTI: SMK & Mahasiswa]`
Bagi peserta didik di laboratorium komputer dengan keterbatasan akses internet, Supabase dapat dijalankan 100% secara lokal menggunakan Supabase CLI dan Docker Desktop.

```bash
# 1. Buat folder proyek lokal
mkdir lab-inventaris-local && cd lab-inventaris-local

# 2. Inisialisasi konfigurasi Supabase
npx supabase init

# 3. Nyalakan seluruh ekosistem Supabase di Docker
npx supabase start
```

- **Penjelasan Langkah demi Langkah:**
  1. `npx supabase init` akan membuat folder `supabase/` dengan berkas `config.toml` yang mengatur port lokal, template seed data, dan konfigurasi otentikasi.
  2. `npx supabase start` akan mengunduh (*pull*) citra kontainer Docker resmi Supabase: `postgres`, `supabase-studio`, `gotrue`, `postgrest`, dan `realtime`.
  3. Setelah kontainer aktif, terminal akan menampilkan ringkasan alamat antarmuka web dan titik akhir API.

- **Pelacakan Alur Sistem Lokal (*Step Trace*):**
  ```text
  [Terminal: npx supabase start]
         │
         ├──> Mengaktifkan Docker Container: supabase_db_lab-inventaris-local (Port 54322)
         ├──> Mengaktifkan Docker Container: postgrest (Port 54321)
         ├──> Mengaktifkan Docker Container: gotrue auth (Port 54321/auth/v1)
         └──> Mengaktifkan Docker Container: supabase-studio GUI (Port 54323)
  ```

- **Hasil Keluaran Terminal Supabase Status:**
  ```text
  Started supabase local development setup.

           API URL: http://localhost:54321
        GraphQL URL: http://localhost:54321/graphql/v1
             DB URL: postgresql://postgres:postgres@localhost:54322/postgres
         Studio URL: http://localhost:54323
       Inbucket URL: http://localhost:54324
         anon key: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
  service_role key: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
  ```
  *Buka browser di alamat `http://localhost:54323` untuk mengakses Supabase Studio (dashboard web) secara offline!*

---

#### Contoh 4 — Skrip Otomasi Validasi Kredensial & Audit Keamanan `[PENDALAMAN: Mahasiswa / SMK Lanjutan]`
Untuk mencegah terjadinya bencana kebocoran data di lingkungan produksi, kita menyusun skrip pengujian berbasis Node.js yang memverifikasi bahwa:
1. `service_role key` **tidak pernah** bocor atau didefinisikan ke variabel publik yang berawalan `NEXT_PUBLIC_` atau `VITE_`.
2. Koneksi ke PostgreSQL berhasil membuka koneksi dan membaca versi engine.

```javascript
// scripts/04_audit_koneksi.js
// Jalankan dengan perintah: node scripts/04_audit_koneksi.js

const { Client } = require('pg');
require('dotenv').config({ path: '.env.local' });

function auditKeamananKredensial() {
    console.log("=== [1. AUDIT KEAMANAN KREDENSIAL SUPABASE] ===");
    
    // Periksa apakah service_role tidak sengaja terekspos ke variabel publik
    const publicKeys = Object.keys(process.env).filter(key => 
        key.startsWith('NEXT_PUBLIC_') || key.startsWith('VITE_')
    );
    
    let isLeaked = false;
    for (const key of publicKeys) {
        if (process.env[key] && process.env[key] === process.env.SUPABASE_SERVICE_ROLE_KEY) {
            console.error(`🚨 BAHAYA KRITIS: ${key} memuat nilai SUPABASE_SERVICE_ROLE_KEY!`);
            isLeaked = true;
        }
    }

    if (!isLeaked) {
        console.log("✅ Kredensial service_role terlindungi dengan aman di sisi server.");
    } else {
        process.exit(1);
    }
}

async function ujiKoneksiDatabase() {
    console.log("\n=== [2. UJI KONEKSI POSTGRESQL POOLER] ===");
    
    // Konfigurasi koneksi ke port pooler 6543
    const client = new Client({
        connectionString: process.env.DATABASE_URL,
        ssl: {
            rejectUnauthorized: false
        }
    });

    try {
        await client.connect();
        console.log("✅ Handshake SSL & Autentikasi Database Berhasil Terhubung!");
        
        const res = await client.query(`
            SELECT 
                current_database() AS db,
                current_user AS user_aktif,
                version() AS engine_version;
        `);
        
        console.log("Informasi Database Server:");
        console.log(`- Database Aktif : ${res.rows[0].db}`);
        console.log(`- User Terkoneksi: ${res.rows[0].user_aktif}`);
        console.log(`- Versi Engine   : ${res.rows[0].engine_version.split(',')[0]}`);
    } catch (err) {
        console.error("❌ Gagal terhubung ke Supabase:", err.message);
    } finally {
        await client.end();
    }
}

// Jalankan seluruh tahapan audit
auditKeamananKredensial();
ujiKoneksiDatabase();
```

- **Analisis Keandalan & Keamanan:**
  - Skrip ini bertindak sebagai pintu gerbang *Continuous Integration* (CI) sederhana.
  - Jika seorang siswa atau pengembang secara tidak sengaja menaruh `service_role` pada variabel frontend (`NEXT_PUBLIC_`), skrip otomatis menggagalkan proses (*exit 1*) sebelum kode terpublikasi ke server produksi.

- **Hasil Keluaran Terminal:**
  ```text
  === [1. AUDIT KEAMANAN KREDENSIAL SUPABASE] ===
  ✅ Kredensial service_role terlindungi dengan aman di sisi server.

  === [2. UJI KONEKSI POSTGRESQL POOLER] ===
  ✅ Handshake SSL & Autentikasi Database Berhasil Terhubung!
  Informasi Database Server:
  - Database Aktif : postgres
  - User Terkoneksi: postgres.xyzcompanyprojectref
  - Versi Engine   : PostgreSQL 15.6 on aarch64-unknown-linux-gnu
  ```

---

### Bagian 5: Verifikasi Output & Panduan Debugging (*Common Pitfalls*) `[INTI: SMK & Mahasiswa]`

#### A. Ringkasan Ekspektasi Output Contoh 1–4
| Contoh | Fokus Pengujian | Indikator Berhasil / Output Kunci |
| :--- | :--- | :--- |
| **Contoh 1** `[INTI]` | Registrasi Cloud & Variabel `.env` | Proyek berstatus *Active* di region Singapore; berkas `.env.local` terisi 4 kredensial tanpa terekspos ke Git. |
| **Contoh 2** `[INTI]` | Koneksi IDE VS Code via Pooler | Ekstensi VS Code menampilkan status hijau *Connected* ke host pooler port `6543` dengan user `postgres.[REF]`. |
| **Contoh 3** `[INTI]` | Supabase Local CLI di Docker | Seluruh kontainer aktif; browser membuka Supabase Studio di `http://localhost:54323` tanpa koneksi internet. |
| **Contoh 4** `[PENDALAMAN]` | Skrip Audit Kredensial & Jaringan | Lolos audit pemisahan `anon` vs `service_role` dan mengembalikan metadata database `PostgreSQL 15.x`. |

---

#### B. Kesalahan Umum Pemula & Solusi Tepat (*Troubleshooting Guide*)
| Gejala Error / Perilaku Masalah | Akar Masalah Arsitektur | Tindakan Solusi Tepat |
| :--- | :--- | :--- |
| ⚠️ **`Connection timed out: connect to [host]:5432`** | Penyedia layanan internet (ISP) atau router lokal tidak merutekan paket IPv6 langsung ke cloud. | **Ganti port dan host ke Connection Pooler:** Buka menu *Project Settings > Database*, pilih tab **Connection String > Mode: Session**, gunakan host pooler port **`6543`** yang mendukung IPv4. |
| ⚠️ **`FATAL: password authentication failed for user "postgres"`** | Pada port pooler `6543`, Anda hanya memasukkan username `postgres` saja tanpa menyertakan ID proyek (*Project Reference*). | **Ubah username menjadi format gabungan:** Gunakan `postgres.[PROJECT_REF]` (misal: `postgres.abcdefghijklmno`). ID proyek dapat dilihat pada URL dashboard Supabase Anda. |
| ⚠️ **`self-signed certificate in certificate chain` / `SSL off`** | PostgreSQL di Supabase Cloud mewajibkan enkripsi TLS/SSL secara penuh, sedangkan database client mencoba terhubung via teks terbuka (*plain text*). | Pada konfigurasi IDE (VS Code / DBeaver), centang opsi **SSL: Require** atau atur parameter JSON `"ssl": { "rejectUnauthorized": false }`. |
| ⚠️ **Supabase CLI Error: `docker is not running`** | Perintah `npx supabase start` dijalankan saat aplikasi Docker Desktop di komputer Anda dalam keadaan tertutup/mati. | Buka aplikasi **Docker Desktop**, tunggu hingga ikon indikator di taskbar berubah menjadi hijau (*Engine running*), lalu ulangi eksekusi `npx supabase start`. |
| ⚠️ **Tabel di Dashboard Supabase kosong padahal kueri client berhasil** | Anda terhubung ke database lokal Docker (`localhost:54322`) tetapi memeriksa tampilan dashboard di portal cloud (`supabase.com`), atau sebaliknya. | Sadari perbedaan lingkungan: Dashboard Cloud berada di `supabase.com/dashboard`, sedangkan Dashboard Lokal berada di `http://localhost:54323`. |

---

### Bagian 6: Latihan Soal & Praktikum Berkelanjutan Multi-Tema *(4 Level, 9 Soal)*

#### Level 1 — Pemahaman Konsep & Tracing Kredensial *(Tema 1: `lab-inventaris-db`)* `[INTI: SMK & Mahasiswa]`
1. Perhatikan potongan kode frontend React berikut:
   ```javascript
   // src/lib/supabaseClient.js
   import { createClient } from '@supabase/supabase-js'

   const supabaseUrl = 'https://labinv.supabase.co'
   const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJvbGUiOiJzZXJ2aWNlX3JvbGUiLCJleHAiOjE5OD...';

   export const supabase = createClient(supabaseUrl, supabaseKey);
   ```
   *Pertanyaan:* Lakukan analisis arsitektural terhadap baris kode di atas. Mengapa kode tersebut merupakan pelanggaran keamanan fatal?
2. Jika seorang teknisi laboratorium komputer di sekolah Anda mencoba menghubungkan DBeaver ke Supabase menggunakan port `5432` dan mengalami kendala *Connection Timeout*, sebutkan 2 parameter koneksi yang wajib ia ubah agar dapat terhubung kembali melalui Supavisor Pooler!

---

#### Level 2 — Modifikasi Konfigurasi Port & Tooling *(Tema 1: `lab-inventaris-db`)* `[INTI: SMK & Mahasiswa]`
3. Diberikan connection string database mentah berikut:
   ```text
   postgresql://postgres:RahasiaLab2026@db.abckucinggarong.supabase.co:5432/postgres
   ```
   Ubahlah connection string di atas agar berpindah dari **Direct Connection Port 5432** ke **Supavisor Pooler Port 6543 (Session Mode)** pada region Singapore (`aws-0-ap-southeast-1.pooler.supabase.com`) dengan menyertakan parameter SSL `require`!
4. Lengkapi potongan berkas konfigurasi `.env` berikut agar aman digunakan oleh aplikasi web frontend:
   ```bash
   # Lengkapi variabel berikut dengan kunci yang tepat:
   NEXT_PUBLIC_SUPABASE_URL=https://abckucinggarong.supabase.co
   NEXT_PUBLIC_SUPABASE_ANON_KEY=[Kunci JWT yang mana yang wajib ditaruh di sini?]
   DATABASE_URL=postgresql://[Lengkapi format username pooler]:RahasiaLab2026@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require
   ```

---

#### Level 3 — Praktikum Berkelanjutan: Transfer Konsep *(Tema 2: `perpustakaan-db`)* `[INTI LANJUT: SMK & Mahasiswa]`
5. **Milestone Tema 2 Langkah A (Pembuatan Proyek Cloud Mandiri):**  
   Buat proyek Supabase Cloud kedua secara mandiri khusus untuk sistem perpustakaan dengan ketentuan:
   - Nama Proyek: `perpustakaan-db`
   - Region: `ap-southeast-1` (Singapore)
   - Buat berkas baru bernama `perpustakaan.env` di komputer Anda yang memuat 4 variabel lengkap: `PROJECT_URL`, `ANON_KEY`, `SERVICE_ROLE_KEY`, dan `DATABASE_POOLER_URL`.
6. **Milestone Tema 2 Langkah B (Uji Diagnostik Koneksi Eksternal):**  
   Hubungkan VS Code atau DBeaver ke proyek `perpustakaan-db` Anda via port 6543. Setelah terhubung, eksekusi kueri SQL diagnostik untuk memverifikasi bahwa sesi yang terhubung benar-benar berada di proyek perpustakaan:
   ```sql
   SELECT 
       current_database() AS nama_db,
       current_user AS user_koneksi,
       inet_server_port() AS port_aktif,
       now() AS waktu_server_utc;
   ```
   Salin hasil keluaran tabel kueri tersebut ke dalam log catatan praktikum Anda!

---

#### Level 4 — Praktikum Berkelanjutan: Analisis Jaringan & Edge Case *(Tema 3: `log-transaksi-db`)* `[PENDALAMAN: Mahasiswa / Pengayaan SMK]`
7. **Kasus Analisis Latensi & Connection Pooler Mode:**  
   Pada Supabase Connection Pooler (Supavisor), tersedia dua mode operasi: **Session Mode** dan **Transaction Mode**.
   - Analisislah mengapa aplikasi backend berbasis **Serverless Functions** (seperti Vercel / AWS Lambda) sangat disarankan menggunakan **Transaction Mode** pada port 6543.
   - Mengapa aplikasi *Database Migration Tool* (seperti Prisma Migrate atau migrasi skema tabel DDL) justru wajib menggunakan **Session Mode** atau Direct Connection Port 5432?
8. **Simulasi Offline Tema 3 via Supabase Local CLI:**  
   Jalankan Supabase CLI pada komputer Anda untuk proyek `log-transaksi-db`. Tuliskan langkah-langkah terminal dari `supabase init` hingga Anda berhasil membuka Supabase Studio lokal di peramban web pada port `54323`, dan catat nilai `anon key` lokal yang dihasilkan oleh kontainer Docker!

---

### Bagian 7: Cek Mandiri, Milestone Tema, & Panduan Verifikasi `[INTI: SMK & Mahasiswa]`

Gunakan rubrik mandiri berikut untuk memastikan seluruh capaian Modul 1 telah terpenuhi sebelum melangkah ke Modul 2:

#### A. Kunci Jawaban Indikator Level 1 & Level 2
- **Soal 1 (Level 1):** Kode tersebut mengekspos `service_role key` ke sisi frontend web. Karena kode frontend dapat diinspeksi oleh siapa saja melalui menu *Inspect Element / Developer Tools* di peramban, pengguna jahat dapat menyalin kunci tersebut dan membypass seluruh keamanan Row Level Security (RLS), sehingga mereka dapat membaca, mengubah, atau menghapus seluruh database secara bebas. Kunci frontend **wajib** menggunakan `anon key`.
- **Soal 2 (Level 1):** Dua parameter yang wajib diubah:
  1. Ubah Host dari direct host (`db.[ref].supabase.co`) menjadi host pooler (`aws-0-[region].pooler.supabase.com`).
  2. Ubah Port dari `5432` menjadi `6543`.
  3. Ubah Username dari `postgres` menjadi `postgres.[PROJECT_REF]`.
- **Soal 3 (Level 2):** Connection string yang tepat:
  ```text
  postgresql://postgres.abckucinggarong:RahasiaLab2026@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require
  ```
- **Soal 4 (Level 2):** 
  - Kunci yang ditaruh di `NEXT_PUBLIC_SUPABASE_ANON_KEY` adalah **`anon key`** (bukan service role).
  - Format username pooler adalah `postgres.abckucinggarong`.

---

#### B. Checklist Kesiapan Milestone Tema 2 & 3 (Syarat Lanjut Modul 2)
Beri tanda centang `[x]` jika Anda telah berhasil melakukan verifikasi mandiri:
- [ ] Proyek `lab-inventaris-db` (Tema 1) telah aktif di region Singapore dan berhasil dihubungkan dari VS Code / DBeaver dengan status *Connected*.
- [ ] Berkas kredensial `.env.local` telah dibuat dan tidak memuat `service_role key` pada variabel yang berawalan publik.
- [ ] Kueri diagnostik `SELECT version();` berhasil mengembalikan engine PostgreSQL 15.x melalui port pooler `6543`.
- [ ] Proyek `perpustakaan-db` (Tema 2) telah terdaftar di dashboard Supabase Cloud dan siap menerima DDL pembuatan tabel di Modul 2.

---

### Bagian 8: Ringkasan Parameter & Refleksi Pemahaman `[INTI: SMK & Mahasiswa]`

#### A. Tabel Ringkasan Komponen Ekosistem Supabase
| Komponen Ekosistem | Peran Fungsional | Akses / Port Kunci |
| :--- | :--- | :--- |
| **PostgreSQL 15 Core** | Mesin basis data relasional penyimpan seluruh data, skema, dan logika bisnis. | Port `5432` (Direct / IPv6) |
| **Supavisor** | Connection pooler pengatur ribuan koneksi konkuren; jembatan jaringan IPv4. | Port `6543` (Session / Transaction) |
| **PostgREST** | Mengubah tabel database menjadi REST API instan secara otomatis. | Jalur HTTPS Port `443` (`/rest/v1`) |
| **GoTrue** | Layanan otentikasi identitas pengguna, registrasi, login, dan penerbit token JWT. | Jalur HTTPS Port `443` (`/auth/v1`) |
| **Supabase Studio** | Antarmuka grafis web (GUI) untuk mengelola tabel, SQL Editor, dan memantau log. | Cloud Portal / Port Lokal `54323` |

---

#### B. Pertanyaan Refleksi Pemahaman Konseptual
Setelah menyelesaikan seluruh materi Modul 1, renungkan dan diskusikan 2 pertanyaan berikut:
1. *Mengapa Supabase memilih menggunakan PostgreSQL murni sebagai fondasinya daripada membuat mesin basis data baru dari nol? Apa keuntungan jangka panjang bagi karir Anda sebagai pengembang perangkat lunak?*
2. *Jika aplikasi sistem inventaris lab (`lab-inventaris-db`) Anda nantinya diakses oleh ribuan siswa dari perangkat HP mereka secara bersamaan, komponen arsitektur Supabase manakah yang bertugas mencegah database agar tidak kehabisan memori koneksi (*out of memory*)? Jelaskan mekanismenya secara singkat!*

---
*(Selamat! Fondasi lingkungan, tooling IDE, dan pemahaman kredensial ekosistem Supabase Anda telah kokoh. Lanjutkan ke **Modul 2: Fondasi Database & Pembuatan Tabel Dasar** untuk mulai merancang skema relasional tabel pertama Anda!)*
