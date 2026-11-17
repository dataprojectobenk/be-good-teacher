# DAFTAR NILAI & HASIL EVALUASI UJIAN PRAKTIKUM 1 BASIS DATA
**Mata Pelajaran:** Basis Data (SQL / DDL & DML)  
**Kelas:** XII Rekayasa Perangkat Lunak (RPL / PPLG)  
**Sesi Ujian:** Praktikum 1 (Studi Kasus: Sistem Ekspedisi "Nusantara Cargo Express")  
**Tanggal Evaluasi:** 2026-11-16  
**Total Peserta Mengumpulkan:** 15 Siswa  

---

## I. RUBRIK PENILAIAN RESMI (MAKSIMAL 100 POIN)

| No | Komponen Penilaian | Bobot | Kriteria Evaluasi |
| :---: | :--- | :---: | :--- |
| 1 | **Database Creation & Selection** | 10 | Perintah `CREATE DATABASE db_nusantara_cargo;` dan `USE db_nusantara_cargo;` |
| 2 | **DDL Tabel `tb_armada_kargo`** | 20 | Ketepatan tipe data, PRIMARY KEY (`id_armada`), NOT NULL UNIQUE (`no_polisi`), dan ENUM status |
| 3 | **DDL Tabel `tb_pelanggan_kargo`** | 20 | Ketepatan tipe data, PRIMARY KEY (`id_pengirim`), NOT NULL UNIQUE (`no_ktp`), dan panjang karakter |
| 4 | **DDL Tabel `tb_resi_pengiriman` + FK** | 25 | Relasi Foreign Key `id_pengirim` dan `id_armada` dengan referensi yang benar ke tabel master |
| 5 | **DML Insert Data Dummy** | 25 | Pengisian data lengkap 5 unit armada, 4 pelanggan, dan 5 resi pengiriman tanpa error |

---

## II. DAFTAR NILAI HASIL EVALUASI PRAKTIKUM SISWA

| Peringkat | NIS | Nama Siswa | Kelas | Nilai | Status | Keterangan Evaluasi Utama |
| :---: | :---: | :--- | :---: | :---: | :---: | :--- |
| 🥇 1 | `242507008` | **Fahrizal Rakha Gunawan** | XII RPL 2 | **87** | **Lulus (Sangat Baik)** | Skema DDL & DML 3 tabel sangat lengkap. Catatan: sintaks FK perlu dirapikan. |
| 🥈 2 | `0092726620` | **Muhamad Rifqi s** | XII RPL 1 | **84** | **Lulus (Sangat Baik)** | Pengerjaan sangat terstruktur. DDL & data 3 tabel lengkap. Perbaiki tanda koma baris 5 armada. |
| 🥉 3 | `242507105` | **Wika Bima Santoso** | XII RPL 2 | **83** | **Lulus (Sangat Baik)** | Lengkap 3 tabel dan seluruh data dummy. Terdapat typo penamaan tabel `tb_armada_cargp`. |
| 4 | `242507007` | **Dimas Ghifari Azhar Maulana** | XII RPL 2 | **82** | **Lulus (Sangat Baik)** | Lengkap 3 tabel. Perbaiki format deklarasi FK dan tanda petik pada tipe tanggal. |
| 5 | `242507005` | **Azril Rizkiansyah** | XII RPL 2 | **71** | **Lulus (Baik)** | 3 tabel & data dummy terisi. Typo kolom `jenis_kelamin` (seharusnya `jenis_kendaraan`). |
| 6 | `3093595948` | **ARYA PANCA NAGARA** | XII RPL 2 | **71** | **Lulus (Baik)** | 3 tabel ada. Typo tipe data `tgl_kirim data`. Format insert pelanggan lupa kurung values. |
| 7 | `242507009` | **Fida Fatah kabalhu** | XII RPL 2 | **55** | **Remedial** | Tabel armada selesai. Tabel pelanggan belum selesai dan relasi FK belum terhubung. |
| 8 | `242507012` | **Rahmadika zikri** | XII-PPLG | **52** | **Remedial** | Tabel armada & resi ada. Tabel pelanggan kargo belum dibuat, sintaks FK perlu diperbaiki. |
| 9 | `3080277728` | **Ryanza Aulia Pratama** | XII RPL 2 | **50** | **Remedial** | Struktur 3 tabel ada tapi perintah INSERT kurang `VALUES`, tipe data VARCHAR nama kurang panjang. |
| 10 | `0081980338` | **Muhamad Rafael** | XII RPL 2 | **49** | **Remedial** | Tabel armada bagus. Tabel resi salah format FK. Tabel pelanggan kargo belum dibuat. |
| 11 | `0094448607` | **M. Arfal Kautsar** | XII RPL 2 | **48** | **Remedial** | Tabel armada selesai. Tabel pelanggan kargo belum dibuat, sintaks FK belum sesuai standar. |
| 12 | `2425070123` | **Rais Prayata Wgn** | XII RPL 2 | **44** | **Remedial** | DDL armada selesai. DDL resi salah sintaks FK. Tabel pelanggan kargo belum dikerjakan. |
| 13 | `00094478877` | **Tita** | XII-PPLG | **25** | **Remedial** | Baru DDL & DML sebagian tabel armada. Perintah INSERT kurang keyword VALUES. |
| 14 | `0096879959` | **aldiansyah** | XII-PPLG | **10** | **Belum Mengerjakan** | Berkas yang diunggah kosong (0 bytes). Perlu mengunggah ulang tugasnya. |
| 15 | `0085361615` | **Siti Aminah** | XII-PPLG | **10** | **Belum Mengerjakan** | Berkas yang diunggah kosong (0 bytes). Perlu mengunggah ulang tugasnya. |

---

## III. TEMUAN UMUM & CATATAN EVALUASI GURU

1. **Kesalahan Fatal Sintaks FOREIGN KEY pada Mayoritas Siswa**:
   - Hampir 90% siswa menuliskan sintaks FK yang keliru karena menyalin struktur kamus data soal secara harfiah:
     ```sql
     -- ❌ KESALAHAN YANG DILAKUKAN SISWA:
     id_pengirim VARCHAR(10) FOREIGN KEY (tb_pelanggan_kargo) REFERENCES (id_pengirim)
     
     -- ✅ SINTAKS SQL YANG BENAR:
     id_pengirim VARCHAR(10),
     CONSTRAINT fk_resi_pengirim FOREIGN KEY (id_pengirim) 
         REFERENCES tb_pelanggan_kargo(id_pengirim) 
         ON UPDATE CASCADE ON DELETE RESTRICT
     ```
   - Rekomendasi: Berikan penekanan materi deklarasi *table-level constraint* vs *column-level constraint* pada pertemuan praktikum berikutnya.

2. **Dua Siswa Mengunggah Berkas Kosong (0 Bytes)**:
   - Siswa atas nama **aldiansyah** dan **Siti Aminah** mengunggah berkas berekstensi `.sql` dengan ukuran 0 bytes. Kemungkinan besar siswa belum menekan tombol `Ctrl + S` di VS Code saat mengunggah. Disarankan untuk diberi kesempatan unggah ulang.

3. **Status Penyimpanan di Sistem**:
   - Seluruh nilai di atas beserta umpan balik (*teacher feedback*) telah tersimpan ke dalam tabel MySQL `pengumpulan` dan dapat langsung dipantau melalui [Panel Guru Lab](http://127.0.0.1/index.php?page=dashboard&filter_soal=9).
