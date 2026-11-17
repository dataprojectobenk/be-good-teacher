# Rencana Standardisasi Format Latihan Soal & Pembaruan Modul 01–04

**Status:** Selesai Diimplementasikan
**Tanggal:** 2026-11-13

---

## 1. Tujuan & Ruang Lingkup
Menyelaraskan format **Bagian 6: Latihan Soal** pada seluruh template modul ajar dan berkas modul MySQL yang telah disusun (`Modul 01`, `Modul 02`, `Modul 03`, `Modul 04`) sesuai contoh standar visual yang diberikan pengguna:
1. **Penyajian Tabel Acuan Praktik yang Jelas & Terstruktur Terlebih Dahulu:**
   Menyajikan tabel-tabel data acuan (nama tabel, nama kolom, dan baris data konkret 4–5 baris per tabel, atau spesifikasi rancangan untuk modul DDL) tepat di bawah judul latihan sebelum butir pertanyaan.
2. **Pola Kalimat Instruksi yang Lugas & Seragam ("Tulis perintah SQL untuk..."):**
   Mengubah daftar soal menjadi daftar bernomor 1 s.d. 8–10 tanpa label pemecah ('Level 1', 'Level 2'), dengan kalimat instruksi langsung pada tindakan teknis (*action-oriented*).
3. **Kenaikan Tingkat Kesulitan Alami:**
   Soal bergerak bertahap dari operasi tunggal/dasar di nomor-nomor awal hingga kombinasi/analisis di nomor-nomor akhir berbasis tabel acuan yang dipajang.

## 2. Berkas Target Pembaruan
- `C:\Users\Dokter Komputer\.gemini\config\plugins\skills-agent\rules\templates\template-tutor.md`
- `D:\Project\exercise\skills-agent\rules\templates\template-tutor.md`
- `C:\Users\Dokter Komputer\.gemini\config\plugins\skills-agent\skills\mysql\prompt.md`
- `D:\Project\exercise\skills-agent\skills\mysql\prompt.md`
- `mysql/materi-01-pengenalan-mysql-editor-dan-ddl-database.md`
- `mysql/materi-02-erd-dan-normalisasi-tabel.md`
- `mysql/materi-03-tipe-data-dan-ddl-pembuatan-tabel.md`
- `mysql/materi-04-ddl-lanjutan-constraint-fk-alter-drop.md`

## 3. Tahapan Eksekusi
- [x] **Fase 1:** Analisis format contoh dari pengguna & penyusunan rencana di `.plan/`.
- [x] **Fase 2:** Pembaruan standar Bagian 6 pada `template-tutor.md` dan `prompt.md` (di kedua direktori config & project).
- [x] **Fase 3:** Standardisasi Bagian 6 pada `materi-01`, `materi-02`, `materi-03`, dan `materi-04`.
- [x] **Fase 4:** Verifikasi integritas seluruh berkas (ketiadaan *null byte*, kejelasan tabel acuan, dan keseragaman format).
