# RENCANA PELAKSANAAN: PENYUSUNAN MODUL 1 KURIKULUM SUPABASE & POSTGRESQL

**Tanggal:** 2026-11-18  
**Topik Modul:** Modul 1 — Konfigurasi Lingkungan, Tooling, & Ekosistem Supabase  
**Target Pembaca:** Siswa SMK Rekayasa Perangkat Lunak (RPL/PPLG), Mahasiswa Informatika/Ilmu Komputer, dan Calon Fullstack Developer  
**Lokasi Direktori Berkas:** `supabase/modul-01-konfigurasi-lingkungan-tooling-ekosistem.md`  
**Status Dokumen:** `Selesai Diimplementasikan`

---

## 1. TUJUAN & RUANG LINGKUP MATERI

Menyusun materi praktikum komprehensif untuk **Modul 1** sesuai kurikulum resmi `supabase-curriculum-architect` dengan format pedagogis 8 bagian terpadu (*Strict Sequential Scaffolding*):
1. **Konseptual BaaS & Arsitektur Supabase**: Membedah perbedaan mendasar antara database on-premise manual vs Backend-as-a-Service (BaaS) berbasis engine PostgreSQL modern.
2. **Pembedahan Kredensial & Keamanan Mutlak**: Menanamkan batasan keamanan antara `Project URL`, `anon key` (kunci publik client terproteksi RLS), `service_role key` (kunci rahasia bypass RLS khusus backend server), dan `Database Connection URI`.
3. **Arsitektur Jaringan & Koneksi**: Membedah perbedaan teknis krusial antara **Direct Connection Port `5432`** (memerlukan dukungan IPv6) vs **Connection Pooler Supavisor Port `6543`** (Session Mode & Transaction Mode, kompatibel dengan IPv4 di Indonesia).
4. **Tooling Terpadu**:
   - Pembuatan proyek online di `supabase.com` (pemilihan region terdekat: Singapore `ap-southeast-1`).
   - Integrasi koneksi client eksternal menggunakan **Visual Studio Code** (ekstensi PostgreSQL/Database Client) dan **DBeaver** dengan SSL Require.
   - Setup lokal menggunakan **Supabase Local CLI** (`supabase init`, `supabase start`, akses Supabase Studio lokal di port `54323`).
5. **Persistent Lab Tracks (3 Tema Berkelanjutan)**:
   - **Tema 1 (Inti):** `lab-inventaris-db` (Sistem Inventaris Laboratorium Komputer).
   - **Tema 2 (Transfer):** `perpustakaan-db` (Sistem Keanggotaan & Perpustakaan Digital).
   - **Tema 3 (Pendalaman):** `log-transaksi-db` (Sistem Audit Log & Multi-Platform Client).

---

## 2. KEBUTUHAN SISTEM & DEPENDENSI

- **Supabase Cloud Account**: Akun gratis di `https://supabase.com`.
- **Node.js & npx**: Versi LTS (>= v18) untuk menjalankan Supabase CLI lokal jika diperlukan.
- **Visual Studio Code & Ekstensi**: Ekstensi *PostgreSQL* (oleh Chris Kolkman) atau *Database Client*.
- **Database Client Mandiri (Opsional)**: DBeaver Community Edition.
- **Docker Desktop (Opsional untuk Local CLI)**: Untuk menjalankan instance PostgreSQL dan Supabase Studio secara lokal.

---

## 3. RANCANGAN STRUKTUR MODUL (FORMAT 8 BAGIAN)

Berkas `supabase/modul-01-konfigurasi-lingkungan-tooling-ekosistem.md` akan disusun dengan 8 bagian:
- **Bagian 1:** Prasyarat, Jembatan Konsep (Why Before How), & Peta Alur Arsitektur Supabase (ASCII diagram).
- **Bagian 2:** Kamus Kredensial, Klausa SQL/CLI, Argumen Port, & Parameter Koneksi (tabel parameter lengkap).
- **Bagian 3:** Prasyarat Lingkungan & Checkpoint 3 Tema Berkelanjutan (Status tema dan kueri uji server).
- **Bagian 4:** Contoh Implementasi Bertingkat — Tema 1 (`lab-inventaris-db`):
  - *Contoh 1 [INTI]:* Registrasi Proyek Cloud & Ekstraksi Kredensial Aman (`.env.example`).
  - *Contoh 2 [INTI]:* Menghubungkan VS Code ke Database Supabase via Supavisor Pooler Port 6543 (SSL Require) + State Tracing.
  - *Contoh 3 [INTI]:* Inisialisasi Supabase CLI Lokal (`supabase init` & `start`) + Step Trace.
  - *Contoh 4 [PENDALAMAN]:* Skrip Otomasi Pengujian Integritas Kredensial (Node.js script deteksi kebocoran `service_role key` dan uji koneksi).
- **Bagian 5:** Verifikasi Output & Panduan Debugging (Tabel ekspektasi output dan tabel mitigasi error umum: IPv6 direct connection timeout, SSL handshake failure, dsb.).
- **Bagian 6:** Latihan Soal & Praktikum Berkelanjutan Multi-Tema (Level 1 Tracing, Level 2 Modifikasi, Level 3 Transfer ke Tema 2 `perpustakaan-db`, Level 4 Analisis Jaringan & Edge Case).
- **Bagian 7:** Cek Mandiri, Milestone Tema, & Panduan Verifikasi.
- **Bagian 8:** Ringkasan Fungsi/Istilah & Refleksi Pemahaman Konseptual.

---

## 4. TAHAPAN EKSEKUSI BERTAHAP (CHECKLIST FASE 1–4)

- [x] **Fase 1: Analisis Kebutuhan & Dokumen Rencana (Plan Approval)**
  - [x] Menyusun berkas rencana `.plan/2026-11-18-modul-01-supabase-konfigurasi-lingkungan.md`.
  - [x] Membuka berkas rencana di editor pengguna melalui `Start-Process`.
- [x] **Fase 2: Pembuatan Struktur Direktori & Modul 1**
  - [x] Membuat direktori `supabase/` pada root repositori aktif.
  - [x] Menyusun berkas `supabase/modul-01-konfigurasi-lingkungan-tooling-ekosistem.md` lengkap dengan 8 bagian tanpa komentar pemalas.
- [x] **Fase 3: Verifikasi Teknis & Integritas Dokumen**
  - [x] Memastikan tidak ada lonjakan konsep (*Zero Knowledge Leap*).
  - [x] Memastikan pembedaan tegas kredensial (`anon` vs `service_role`) dan port (`5432` vs `6543`).
  - [x] Memverifikasi 3 tema berkelanjutan termuat secara proporsional.
- [x] **Fase 4: Finalisasi & Penyajian**
  - [x] Memperbarui status rencana menjadi `Selesai Diimplementasikan`.
  - [x] Menyajikan ringkasan hasil teknis dengan format direct & zero fluff serta tautan berkas `file:///`.
