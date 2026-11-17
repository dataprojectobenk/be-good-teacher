# LEMBAR SOAL UJIAN PRAKTIKUM 2 (BASIS DATA / SQL)
**Mata Pelajaran:** Basis Data  
**Kelas:** XI RPL / PPLG  
**Materi Uji:** Manipulasi Data, Penyaringan Lanjutan, dan Rekapitulasi Statistik  
**Studi Kasus:** Sistem Manajemen Rental Mobil "TransJava Rent Car"  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Siapkan lingkungan basis data dengan mengimpor berkas `skema_awal_praktikum_2.sql` ke dalam database `db_transjava_rent`.
2. Tulis seluruh kueri SQL jawaban Anda secara berurutan ke dalam sebuah berkas teks dengan format nama: `PRAKTIKUM2_DB_NIS_NAMA.sql`.
3. Cocokkan hasil eksekusi kueri yang Anda buat dengan **Hasil Akhir yang Diharapkan** yang tertera pada setiap nomor soal.
4. Kueri yang benar akan menghasilkan susunan kolom dan data keluaran yang persis sama dengan contoh hasil akhir.
5. Jalankan perintah secara berurutan dari nomor 1 sampai 10.

---

## II. DATA DUMMY
Gunakan data awal dari berkas `skema_awal_praktikum_2.sql` yang memuat tabel:
- `tb_mobil` (8 unit kendaraan berbagai tipe dan tarif).
- `tb_pelanggan_rental` (5 data pelanggan).
- `tb_transaksi_sewa` (8 transaksi peminjaman).

---

## III. PETUNJUK SOAL & HASIL AKHIR YANG DIHARAPKAN

### Bagian A: Perubahan dan Pembersihan Data

#### 1. Penyesuaian Tarif Sewa Kendaraan
Manajemen menetapkan kenaikan tarif sewa sebesar 10% untuk semua unit mobil bertipe **SUV** yang tahun pembuatannya **di atas tahun 2022**.  
*Terapkan perubahan data tersebut ke dalam sistem.*

**Hasil Akhir yang Diharapkan saat data diverifikasi:**
```
SELECT id_mobil, merk, tipe, tahun_buat, tarif_harian 
FROM tb_mobil 
WHERE tipe = 'SUV' AND tahun_buat > 2022;
```
| id_mobil | merk | tipe | tahun_buat | tarif_harian |
| :--- | :--- | :--- | :---: | :---: |
| `M05` | Toyota Fortuner GR | SUV | 2024 | 1045000.00 |
| `M06` | Honda HR-V | SUV | 2023 | 605000.00 |

---

#### 2. Pembaruan Status Unit Kendaraan
Mobil dengan kode **M05** yang sebelumnya disewa telah kembali ke garasi rental. Perbarui status mobil tersebut menjadi **Tersedia**.

**Hasil Akhir yang Diharapkan saat data diverifikasi:**
```
SELECT id_mobil, no_polisi, merk, status_mobil 
FROM tb_mobil 
WHERE id_mobil = 'M05';
```
| id_mobil | no_polisi | merk | status_mobil |
| :--- | :--- | :--- | :--- |
| `M05` | B 7890 MNO | Toyota Fortuner GR | Tersedia |

---

#### 3. Pembersihan Riwayat Pembatalan Transaksi
Bersihkan seluruh rekaman transaksi yang berstatus **Batal** agar tidak tercatat pada pembukuan operasional aktif.

**Hasil Akhir yang Diharapkan saat data diverifikasi:**
```
SELECT COUNT(*) AS sisa_transaksi 
FROM tb_transaksi_sewa 
WHERE status_transaksi = 'Batal';
```
| sisa_transaksi |
| :---: |
| 0 |

---

### Bagian B: Penyaringan Data Tertarget

#### 4. Daftar Mobil Siap Pakai Kategori Tertentu
Tampilkan nomor polisi, merk, tipe, tarif harian, dan status mobil untuk seluruh armada yang berstatus **Tersedia** dan berjenis **MPV** atau **SUV**. Urutkan tampilan dari tarif sewa yang paling mahal ke yang paling terjangkau.

**Hasil Akhir yang Diharapkan:**
| no_polisi | merk | tipe | tarif_harian | status_mobil |
| :--- | :--- | :--- | :---: | :--- |
| `B 7890 MNO` | Toyota Fortuner GR | SUV | 1045000.00 | Tersedia |
| `D 1122 XYZ` | Honda HR-V | SUV | 605000.00 | Tersedia |
| `B 1234 ABC` | Toyota Avanza | MPV | 350000.00 | Tersedia |

---

#### 5. Pencarian Mobil Tahun Tertentu Berdasarkan Batas Anggaran
Tampilkan kode mobil, merk, tipe, tahun pembuatan, dan tarif harian untuk mobil yang dibuat pada rentang tahun **2021 sampai 2023** dengan tarif sewa harian **di bawah Rp 600.000**.

**Hasil Akhir yang Diharapkan:**
| id_mobil | merk | tipe | tahun_buat | tarif_harian |
| :--- | :--- | :--- | :---: | :---: |
| `M01` | Toyota Avanza | MPV | 2021 | 350000.00 |
| `M02` | Mitsubishi Xpander | MPV | 2023 | 400000.00 |

---

#### 6. Pencarian Pelanggan Berdasarkan Pola Nama atau Kota
Tampilkan seluruh informasi pelanggan rental yang nama penyewanya mengandung unsur nama **Dewi** atau beralamat di kota **Bandung**.

**Hasil Akhir yang Diharapkan:**
| id_penyewa | no_ktp | nama_penyewa | no_telepon | alamat |
| :--- | :--- | :--- | :--- | :--- |
| `P02` | 3201987654320002 | Ratna Sari Dewi | 081333334444 | Jl. Gatot Subroto No. 45, Bandung |

---

#### 7. Penyaringan Kelompok Jenis Kendaraan Khusus
Tampilkan kode, nomor polisi, merk, dan tipe kendaraan khusus untuk unit yang tergolong dalam jenis **City Car** atau **Minibus**.

**Hasil Akhir yang Diharapkan:**
| id_mobil | no_polisi | merk | tipe |
| :--- | :--- | :--- | :--- |
| `M03` | B 9012 GHI | Honda Brio Satya | City Car |
| `M07` | D 3344 KLM | Daihatsu Sigra | City Car |
| `M08` | AB 5566 JQ | Toyota HiAce Commuter | Minibus |

---

### Bagian C: Rangkuman Statistik & Pengelompokan Data

#### 8. Ringkasan Finansial Transaksi Selesai
Tampilkan ringkasan statistik khusus untuk transaksi yang berstatus **Selesai**, dengan rincian kolom:
- `total_transaksi_selesai` : Banyaknya transaksi yang telah selesai.
- `total_pendapatan` : Akumulasi nominal uang sewa yang diterima.
- `rata_rata_sewa` : Rata-rata nominal per transaksi.
- `sewa_tertinggi` : Nilai pembayaran transaksi terbesar.
- `sewa_terendah` : Nilai pembayaran transaksi terkecil.

**Hasil Akhir yang Diharapkan:**
| total_transaksi_selesai | total_pendapatan | rata_rata_sewa | sewa_tertinggi | sewa_terendah |
| :---: | :---: | :---: | :---: | :---: |
| 4 | 5000000.00 | 1250000.000000 | 2200000.00 | 300000.00 |

---

#### 9. Rekapitulasi Armada per Tipe Kendaraan
Tampilkan rekapitulasi data armada mobil yang dikelompokkan berdasarkan **tipe** kendaraannya, dengan rincian kolom:
- `tipe` : Jenis bodi mobil.
- `jumlah_unit` : Total mobil pada tipe tersebut.
- `rata_rata_tarif` : Rata-rata tarif sewa harian untuk tipe yang bersangkutan.

**Hasil Akhir yang Diharapkan:**
| tipe | jumlah_unit | rata_rata_tarif |
| :--- | :---: | :---: |
| City Car | 2 | 275000.000000 |
| Minibus | 1 | 1100000.000000 |
| MPV | 2 | 375000.000000 |
| Premium MPV | 1 | 600000.000000 |
| SUV | 2 | 825000.000000 |

---

#### 10. Daftar Kendaraan Penghasil Pendapatan Terbesar
Hitung akumulasi total penerimaan uang sewa dari setiap unit kendaraan pada transaksi yang tercatat. Tampilkan hanya unit kendaraan yang menghasilkan total penerimaan **lebih dari Rp 1.500.000**.

**Hasil Akhir yang Diharapkan:**
| id_mobil | total_pembayaran |
| :--- | :---: |
| `M04` | 3000000.00 |
| `M05` | 2850000.00 |
| `M08` | 2200000.00 |
