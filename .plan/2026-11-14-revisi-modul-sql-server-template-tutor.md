# Rencana Revisi Modul SQL Server Berdasarkan Pembaruan Template Modul Tutor

**Tanggal:** 2026-11-14  
**Topik:** Standardisasi Format Modul Pembelajaran Microsoft SQL Server (2019 / 2022)  
**Target Direktori:** `e:\My Drive\NITIP\be_good_teacher\sqlserver\`  
**Status:** Selesai Diimplementasikan  

---

## 1. Analisis Kebutuhan & Ruang Lingkup Pembaruan

Berdasarkan pembaruan standar pedagogi dan format modul pada `template-tutor.md` serta implementasi benchmark pada modul `mysql/`, seluruh modul pembelajaran SQL Server yang telah disusun (`Modul 01`, `Modul 02`, dan `Modul 06`) telah direvisi agar selaras dengan standar mutu terkini:

1. **Penyajian Tabel Acuan Praktik Visual Terlebih Dahulu (Bagian 6):**
   - Pada awal Bagian 6, telah disajikan tabel-tabel data acuan visual (berisi nama tabel, kolom, tipe/panjang, aturan, dan 4–5 baris data konkret per tabel, atau spesifikasi profil instance & arsitektur untuk modul fondasi) tepat di bawah judul latihan sebelum butir pertanyaan.
2. **Pola Kalimat Instruksi Terarah, Aplikatif, & Seragam:**
   - Menghilangkan label pemecah subjudul (`### Level 1`, `### Level 2`, dst.).
   - Menyusun daftar soal bernomor urut 1 s.d. 8–10 butir dengan instruksi aksi langsung (*action-oriented*), diawali kalimat baku: *"Tulis perintah T-SQL untuk [tindakan] pada [tabel] dengan [kondisi/nilai]..."* (atau kalimat instruksi langsung terarah untuk modul instalasi/tooling).
   - Kenaikan tingkat kesulitan berjalan alami dari butir-butir awal (dasar/tunggal) ke butir-butir akhir (kombinasi/analisis kasus).
3. **Pemisahan Kunci Jawaban & Target Visual Output Grid (Bagian 7):**
   - Kunci jawaban kueri T-SQL lengkap dan solusi analitis **DILARANG** ditulis langsung di modul siswa.
   - Bagian 7 difokuskan murni sebagai **Panduan Evaluasi & Checklist Mandiri**:
     - *Target Output Visual Kueri:* Untuk perintah `SELECT`, menyajikan contoh tampilan hasil akhir dalam format tabel grid ASCII SQL Server (`+---+---+`) lengkap dengan notasi baris terdampak `(N rows affected)`.
     - *Indikator Keberhasilan Tugas:* Catatan baris terdampak untuk DML/DDL atau indikator status layanan/koneksi untuk fondasi.
     - *Checklist Kesiapan Teknis.*
     - *Catatan Buku Pegangan Pengajar:* Merujuk ke berkas pegangan instruktur terpisah.
4. **Penyusunan Berkas Buku Kunci Jawaban Instruktur:**
   - Telah dibuat berkas terpusat `sqlserver/KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md` yang memuat seluruh kunci jawaban kueri T-SQL, rancangan arsitektur, dan pembahasan detail untuk Modul 01, Modul 02, dan Modul 06.
5. **Penyelarasan Prompt & Spesifikasi Skill:**
   - Telah diperbarui `prompt.md` dan `SKILL.md` pada plugin `sql-server` (di direktori config dan direktori exercise) agar proses penulisan modul-modul berikutnya (Modul 03–05, 07–24) otomatis mengikuti standar baku ini.

---

## 2. Berkas Target Pembaruan

1. [modul-01-arsitektur-edisi-instalasi.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/modul-01-arsitektur-edisi-instalasi.md)
2. [modul-02-autentikasi-jaringan-tooling.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/modul-02-autentikasi-jaringan-tooling.md)
3. [modul-06-operasi-crud-pembersihan-tabel.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/modul-06-operasi-crud-pembersihan-tabel.md)
4. [KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md](file:///e:/My%20Drive/NITIP/be_good_teacher/sqlserver/KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md) *(Berkas Baru)*
5. [prompt.md](file:///C:/Users/Dokter%20Komputer/.gemini/config/plugins/skills-agent/skills/sql-server/prompt.md)
6. [SKILL.md](file:///C:/Users/Dokter%20Komputer/.gemini/config/plugins/skills-agent/skills/sql-server/SKILL.md)

---

## 3. Tahapan Eksekusi Bertahap

- [x] **Fase 1: Perencanaan & Pembukaan Berkas Rencana**
  - Membuat berkas `.plan/2026-11-14-revisi-modul-sql-server-template-tutor.md` dan membuka otomatis via `Start-Process`.
- [x] **Fase 2: Pembaruan Berkas Modul Siswa (Modul 01, Modul 02, Modul 06)**
  - Menstandarisasi Bagian 6 (Tabel acuan visual + daftar tugas 1 s.d. 8–10 tanpa label Level 1/Level 2).
  - Merestrukturisasi Bagian 7 menjadi Panduan Evaluasi murni (Target visual ASCII grid output + checklist mandiri tanpa bocoran kunci jawaban).
  - Memastikan ketiadaan *meta-prompt leakage* dan konsistensi gaya buku ajar.
- [x] **Fase 3: Penyusunan Buku Kunci Jawaban Instruktur**
  - Membuat `sqlserver/KUNCI_JAWABAN_LATIHAN_SOAL_SQL_SERVER.md` berisi seluruh kunci kueri T-SQL lengkap, pembahasan mendalam, dan catatan pedagogis.
- [x] **Fase 4: Penyelarasan Skill Prompt & Template Spesifikasi**
  - Memperbarui `prompt.md` dan `SKILL.md` pada kedua lokasi repositori (`config/plugins/` dan `exercise/`).
- [x] **Fase 5: Verifikasi Integritas & Finalisasi**
  - Memastikan seluruh tautan berkas `file:///` valid, format rapi, tidak ada *null byte*, dan menandai status rencana selesai.

---

## 4. Analisis Risiko & Mitigasi

- **Risiko:** Pemindahan kunci jawaban dari modul siswa dapat menghilangkan panduan bagi pembaca jika berkas kunci jawaban tidak ditautkan dengan jelas.
  - *Mitigasi:* Menyajikan target visual output tabel yang sangat jelas di Bagian 7 sehingga siswa tetap bisa mencocokkan hasil secara visual, dan memberikan tautan eksplisit ke berkas pegangan instruktur.
- **Risiko:** Karakter non-ASCII atau saltik pada format tabel Markdown.
  - *Mitigasi:* Menggunakan format tabel standar GFM yang teruji dan memverifikasi tampilan teks secara saksama.
