# Rencana Komprehensif Revisi Materi Ajar SMA (Kelas 10, 11, dan 12)

**Tanggal:** 2026-11-14  
**Status:** Selesai Diimplementasikan  
**Standar Acuan:** `rules/templates/template-materi-ajar.md` & `skills/matematika-sma/SKILL.md`

---

## 1. Tujuan & Ruang Lingkup

Melakukan standardisasi dan revisi menyeluruh terhadap 9 berkas materi ajar Matematika SMA/MA (Jurusan IPA & Pesantren) yang berada di direktori `math/kelas-10/`, `math/kelas-11/`, dan `math/kelas-12/`.

Tujuan utama revisi:
1. **Penerapan Format Baku 9 Bagian Siswa (Terkalibrasi 80 Menit/Minggu)**:
   - **Bagian 1:** Wajib memuat `#### A. Pemanasan Cepat (3 Menit)` (2 soal kilat pengingat prasyarat + cek jawaban singkat) dan `#### B. Pengertian & Analogi Nyata` (hikmah keteraturan sunnatullah & analogi konkret).
   - **Bagian 2:** Bentuk Umum & Rumus Baku (Display KaTeX `$$...$$` rata kiri dengan keterangan simbol lengkap).
   - **Bagian 3:** Sifat-Sifat & Aturan Penting (2–3 aturan esensial + tabel komparasi).
   - **Bagian 4:** Contoh Soal & Pembahasan Bertingkat Mikro (Prinsip *Contoh Kembar* 1-to-1 dengan Latihan, berformat Diketahui, Ditanyakan, Langkah, Kesimpulan $\checkmark$).
   - **Bagian 5:** Penerapan Kontekstual (1 studi kasus santri berangka ramah hitung; bebas istilah non-syariah seperti koperasi).
   - **Bagian 6 & 7 (Urutan Baku):** `Bagian 6: Latihan Soal Kelas` ditempatkan SEBELUM `Bagian 7: Pola Kunci, Jebakan Umum, & Cara Cepat`.
     - *Bagian 6* dikalibrasi 80 menit: `6A` Terbimbing (2 soal), `6B` Prosedur Inti (4–5 soal), `6C` Soal Cerita (1–2 soal), `6D` Tantangan/HOTS Opsional (1 soal).
     - *Bagian 7* memuat Pola Kunci yang Terbentuk, Cara Cek Cepat Hasil, dan Waspada Jebakan Umum (`⚠️`).
   - **Bagian 8:** Pekerjaan Rumah (PR Pondok / Mandiri) (2 soal rutin, 1 soal cerita, 1 refleksi pemahaman konsep) DILENGKAPI **Kunci Jawaban Akhir Ringkas (Cek Mandiri)** untuk 6A, 6B, 6C, dan PR 1–3.
   - **Bagian 9:** Ringkasan Materi (2–4 butir intisari formula & langkah kunci).
2. **Pembersihan Pelanggaran Aturan (*Zero Teacher Rubric* & Kosakata Halal)**:
   - Merevisi total `math/kelas-11/pertemuan-08-evaluasi-matriks.md` yang sebelumnya berformat lembar ujian instruktur dengan rubrik penilaian skor/bobot menjadi modul evaluasi mandiri siswa 9 bagian.
   - Menghapus penggunaan istilah `koperasi` pada `math/kelas-10/pertemuan-09...` dan menggantinya dengan `Toko Kitab Pesantren` atau `Kantin Asrama`.

---

## 2. Berkas Target Revisi

### A. Direktori `math/kelas-10/` (1 Berkas)
- `math/kelas-10/pertemuan-09-sistem-persamaan-linear-tiga-variabel.md`

### B. Direktori `math/kelas-11/` (3 Berkas)
- `math/kelas-11/pertemuan-07-determinan-dan-invers-matriks.md`
- `math/kelas-11/pertemuan-08-evaluasi-matriks.md`
- `math/kelas-11/pertemuan-09-transformasi-geometri-translasi-dan-refleksi.md`

### C. Direktori `math/kelas-12/` (5 Berkas)
- `math/kelas-12/pertemuan-08-ukuran-penyebaran-data.md`
- `math/kelas-12/pertemuan-09-latihan-dan-aplikasi-statistika.md`
- `math/kelas-12/pertemuan-10-evaluasi-statistika-deskriptif.md`
- `math/kelas-12/pertemuan-11-pemantapan-materi-kelas-10-dan-11.md`
- `math/kelas-12/pertemuan-12-review-dan-persiapan-pas-ganjil.md`

---

## 3. Tahapan Eksekusi Bertahap

- [x] **Fase 1:** Pembuatan dokumen rencana `.plan/2026-11-14-revisi-materi-sma-kelas-10-11-12.md` dan pembukaan otomatis via PowerShell.
- [x] **Fase 2:** Revisi berkas `math/kelas-10/pertemuan-09-sistem-persamaan-linear-tiga-variabel.md` (Pemanasan cepat, perbaikan istilah koperasi, kalibrasi latihan 6A-6D, pola kunci, dan kunci jawaban mandiri).
- [x] **Fase 3:** Revisi berkas `math/kelas-11/`:
  - [x] `pertemuan-07-determinan-dan-invers-matriks.md` (Kalibrasi dari 60 soal ke format 80 menit 6A–6D, pemanasan cepat, kunci jawaban mandiri).
  - [x] `pertemuan-08-evaluasi-matriks.md` (Transformasi dari lembar rubrik guru ke format modul belajar mandiri siswa 9 bagian).
  - [x] `pertemuan-09-transformasi-geometri-translasi-dan-refleksi.md` (Kalibrasi dari 40 soal ke format 80 menit 6A–6D, pemanasan cepat, kunci jawaban mandiri).
- [x] **Fase 4:** Revisi berkas `math/kelas-12/`:
  - [x] `pertemuan-08-ukuran-penyebaran-data.md` (Kalibrasi ke format 80 menit 6A–6D, pemanasan cepat, kunci jawaban mandiri).
  - [x] `pertemuan-09-latihan-dan-aplikasi-statistika.md` (Kalibrasi ke format 80 menit 6A–6D, pemanasan cepat, kunci jawaban mandiri).
  - [x] `pertemuan-10-evaluasi-statistika-deskriptif.md` (Kalibrasi ke format 80 menit 6A–6D, pemanasan cepat, kunci jawaban mandiri).
  - [x] `pertemuan-11-pemantapan-materi-kelas-10-dan-11.md` (Kalibrasi ke format 80 menit 6A–6D, pemanasan cepat, kunci jawaban mandiri).
  - [x] `pertemuan-12-review-dan-persiapan-pas-ganjil.md` (Kalibrasi ke format 80 menit 6A–6D, pemanasan cepat, kunci jawaban mandiri).
- [x] **Fase 5:** Verifikasi integritas notasi KaTeX LaTeX, ketiadaan tag rubrik guru, ketiadaan istilah terlarang, dan pembaruan dokumen rencana menjadi selesai diimplementasikan.

---

## 4. Hasil Verifikasi Akhir
- Seluruh 9 berkas telah mematuhi struktur baku 9 Bagian Siswa (Terkalibrasi 80 Menit/Minggu).
- `Bagian 1` telah dilengkapi `#### A. Pemanasan Cepat (3 Menit)` dengan 2 soal kilat dan cek singkat, serta `#### B. Pengertian Konsep Dasar` yang lugas tanpa analogi teologis/filosofis yang dipaksakan.
- `Bagian 6: Latihan Soal Kelas` disajikan secara bersih dan mandiri:
  - Label internal authoring seperti `*(Pola Kembar Contoh 1)*` telah dihapus sepenuhnya di seluruh modul.
  - Label deskriptif nomor soal pada Bagian 6B (misalnya *1. Koefisien bulat positif sederhana:*) telah dihilangkan dan diganti dengan penomoran standar yang bersih.
- `Bagian 6: Latihan Soal Kelas` diletakkan konsisten SEBELUM `Bagian 7: Pola Kunci, Jebakan Umum, & Cara Cepat`.
- `Bagian 8: Pekerjaan Rumah` telah memuat **Kunci Jawaban Akhir Ringkas (Cek Mandiri)**.
- Seluruh rubrik instruktur/skor nilai guru telah dibersihkan secara tuntas (*Zero Teacher Rubric*).
- Seluruh istilah non-syariah seperti `koperasi` telah diganti dengan kosakata halal (`Toko Kitab Pesantren`, `Kantin Asrama`).
