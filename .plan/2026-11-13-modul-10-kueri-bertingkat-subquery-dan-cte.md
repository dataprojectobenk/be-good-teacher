# Rencana Penyusunan Modul 10: Kueri Bertingkat & Analisis Lanjut (Subquery, CTE / WITH Clause, Window Functions, & JSON)

**Status:** Menunggu Persetujuan / Siap Eksekusi (Auto-Approved untuk Berkas .md)
**Tanggal:** 2026-11-13

---

## 1. Tujuan & Ruang Lingkup
Menyusun **Modul 10** (mysql/materi-10-kueri-bertingkat-subquery-dan-cte.md) untuk membimbing peserta didik memahami kueri lanjutan berbasis kueri bersarang dan ekspresi tabel umum:
- **Subquery (Kueri di dalam Kueri)**:
  - *Scalar Subquery*: Mengembalikan 1 baris 1 kolom (misal mencari produk dengan harga di atas rata-rata: WHERE harga > (SELECT AVG(harga) FROM produk)).
  - *Subquery Berbasis Baris/Tabel*: Menggunakan operator IN, NOT IN, ANY, ALL.
  - *Correlated Subquery & EXISTS / NOT EXISTS*: Mengevaluasi keberadaan relasi data dinamis per baris.
- **Common Table Expression (CTE / Klausa WITH ... AS)**:
  - Menyusun kueri modular bertingkat yang rapi, mudah dibaca, dan memisahkan logika analisis antara sebelum hasil akhir diproyeksikan.
- **Pengenalan Analisis Lanjut MySQL 8.x (Pendalaman Mahasiswa)**:
  - Pengenalan dasar *Window Functions* (ROW_NUMBER(), DENSE_RANK() OVER (PARTITION BY ... ORDER BY ...)).
  - Pengenalan manipulasi data semi-terstruktur JSON (JSON_EXTRACT(), operator -> dan ->>).
- Format Latihan Soal Standar: Tabel acuan visual konkret (karyawan, produk, order, pelanggan) + 10 butir tugas instruksi langsung.
- Bagian 7 menyajikan **Target Visual Output Grid Tabel** (+---+---+) untuk setiap butir soal tanpa membocorkan kueri SQL-nya.
- Kunci jawaban kueri SQL lengkap dan pembahasan analitis disatukan langsung ke dalam KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md.

## 2. Kebutuhan Sistem & Dependensi
- Mengikuti format 8 bagian template-tutor.md yang telah diperbarui dengan standar target visual output tabel.
- Bahasa pengantar profesional, membimbing, dan setara (bebas label stigma kemampuan).
- Menutup Tahap 3 (Pengolahan & Analisis Kueri) sebelum memasuki Tahap 4 (Otomasi, Keandalan, & Produksi di Modul 11–14).

## 3. Rancangan Berkas Target
- mysql/materi-10-kueri-bertingkat-subquery-dan-cte.md (baru)
- mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md (penambahan bab kunci jawaban Modul 10)
- mysql/SILABUS_LENGKAP_MYSQL_8X.md (status Modul 10 diperbarui)

## 4. Tahapan Eksekusi
- [x] Fase 1: Menyusun rencana dan membuka dokumen .plan/.
- [ ] Fase 2: Menyusun materi-10-kueri-bertingkat-subquery-dan-cte.md lengkap 8 bagian.
- [ ] Fase 3: Memperbarui KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md, SILABUS_LENGKAP_MYSQL_8X.md, dan memverifikasi integritas seluruh berkas.