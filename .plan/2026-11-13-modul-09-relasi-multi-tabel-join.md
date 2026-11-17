# Rencana Penyusunan Modul 09: Relasi Multi-Tabel (INNER JOIN, LEFT JOIN, RIGHT JOIN, Self Join, & CROSS JOIN)

**Status:** Selesai Diimplementasikan
**Tanggal:** 2026-11-13

---

## 1. Tujuan & Ruang Lingkup
Menyusun **Modul 09** (mysql/materi-09-relasi-multi-tabel-join.md) untuk membimbing peserta didik memahami teknik menggabungkan dan membaca data dari banyak tabel berelasi secara simultan:
- Konsep dasar relasi kunci (Primary Key <-> Foreign Key) saat pembacaan data antar-tabel.
- **INNER JOIN**: Menggabungkan baris yang memiliki pasangan data yang cocok di kedua tabel.
- **LEFT JOIN (LEFT OUTER JOIN)**: Menampilkan seluruh baris dari tabel kiri beserta data pasangan di tabel kanan (menghasilkan NULL jika tidak ada transaksi/relasi).
- **RIGHT JOIN (RIGHT OUTER JOIN)**: Menampilkan seluruh baris dari tabel kanan beserta padanannya di tabel kiri.
- **Self Join**: Menggabungkan tabel dengan dirinya sendiri (misal relasi hirarki karyawan ke manajer atau atasan).
- **CROSS JOIN / Cartesian Product**: Konsep perkalian himpunan baris dan bahaya terjadinya ledakan baris data tanpa klausa ON.
- Menggabungkan 3 tabel atau lebih secara bertingkat (misal: order -> pelanggan & order_detail -> produk).
- Format Latihan Soal Standar: Tabel acuan visual konkret (karyawan, produk, order, pelanggan) + 10 butir tugas instruksi langsung.
- Bagian 7 menyajikan **Target Visual Output Grid Tabel** (+---+---+) untuk setiap butir soal tanpa membocorkan kueri SQL-nya.
- Kunci jawaban kueri SQL lengkap dan pembahasan analitis disatukan langsung ke dalam KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md.

## 2. Kebutuhan Sistem & Dependensi
- Mengikuti format 8 bagian template-tutor.md yang telah diperbarui dengan standar target visual output tabel.
- Bahasa pengantar profesional, membimbing, dan setara (bebas label stigma kemampuan).
- Zero Untaught Syntax: Menggabungkan JOIN dengan klausa WHERE, GROUP BY, HAVING, ORDER BY, dan LIMIT. Belum menggunakan Subquery / CTE (dijadwalkan di Modul 10).

## 3. Rancangan Berkas Target
- mysql/materi-09-relasi-multi-tabel-join.md (Selesai dibuat)
- mysql/KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md (Penambahan bab kunci jawaban Modul 09 selesai)
- mysql/SILABUS_LENGKAP_MYSQL_8X.md (Status Modul 09 diperbarui menjadi Selesai)

## 4. Tahapan Eksekusi
- [x] Fase 1: Menyusun rencana dan membuka dokumen .plan/.
- [x] Fase 2: Menyusun materi-09-relasi-multi-tabel-join.md lengkap 8 bagian.
- [x] Fase 3: Memperbarui KUNCI_JAWABAN_LATIHAN_SOAL_MYSQL.md, SILABUS_LENGKAP_MYSQL_8X.md, dan memverifikasi integritas seluruh berkas.