# LEMBAR SOAL UJIAN PRAKTIKUM 1 (BASIS DATA / SQL)
**Mata Pelajaran:** Basis Data  
**Kelas:** XI RPL / PPLG  
**Materi Uji:** Data Definition Language (DDL), Foreign Key (Integritas Referensial), dan Insert Data (MySQL / MariaDB)  
**Studi Kasus:** Sistem Manajemen Rental Mobil "TransJava Rent Car"  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Kerjakan tugas menggunakan perkakas baris perintah (**MySQL Command Line Client**) atau antarmuka GUI basis data (**phpMyAdmin / DBeaver / HeidiSQL**).
2. Tulis seluruh perintah SQL jawaban Anda ke dalam sebuah berkas teks berekstensi `.sql` dengan format penamaan: `PRAKTIKUM1_DB_NIS_NAMA.sql`.
3. Buat basis data baru dengan nama `db_transjava_rent`.
4. Rancang seluruh tabel beserta tipe data dan konstrain yang tertera pada Kamus Data.
5. Masukkan seluruh data dummy yang telah disediakan ke dalam tabel yang sesuai.
6. Seluruh skrip wajib dapat dieksekusi secara utuh tanpa memicu pesan galat (*error*).

---

## II. KAMUS DATA & DATA DUMMY

### 1. Entitas: Mobil (`tb_mobil`)
Kamus Data:
| Nama Kolom | Tipe Data | Size / Nilai | Konstrain & Aturan | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `id_mobil` | VARCHAR | 10 | Primary Key | Kode identitas mobil |
| `no_polisi` | VARCHAR | 15 | Not Null, Unique | Nomor plat kendaraan |
| `merk` | VARCHAR | 50 | Not Null | Merk dan tipe kendaraan |
| `tipe` | VARCHAR | 30 | Not Null | Jenis bodi mobil (MPV, SUV, dll) |
| `tahun_buat` | INT | - | Not Null | Tahun produksi unit |
| `tarif_harian` | DECIMAL | 12,2 | Not Null | Biaya sewa per 24 jam |
| `status_mobil` | ENUM | 'Tersedia', 'Disewa', 'Perawatan' | Default 'Tersedia' | Status fisik kendaraan |

Data Dummy `tb_mobil`:
| id_mobil | no_polisi | merk | tipe | tahun_buat | tarif_harian | status_mobil |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `M01` | `B 1234 ABC` | Toyota Avanza | MPV | 2022 | 350000 | Tersedia |
| `M02` | `B 5678 DEF` | Mitsubishi Xpander | MPV | 2023 | 400000 | Tersedia |
| `M03` | `B 9012 GHI` | Honda Brio | City Car | 2021 | 300000 | Disewa |
| `M04` | `B 3456 JKL` | Toyota Innova Reborn | Premium MPV | 2023 | 600000 | Tersedia |
| `M05` | `B 7890 MNO` | Toyota Fortuner | SUV | 2024 | 900000 | Disewa |

---

### 2. Entitas: Transaksi Sewa (`tb_transaksi_sewa`)
Kamus Data:
| Nama Kolom | Tipe Data | Size / Nilai | Konstrain & Aturan | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `no_transaksi` | VARCHAR | 15 | Primary Key | Nomor faktur transaksi |
| `id_penyewa` | VARCHAR | 10 | Foreign Key -> `tb_pelanggan_rental(id_penyewa)` | Relasi penyewa (ON UPDATE CASCADE ON DELETE RESTRICT) |
| `id_mobil` | VARCHAR | 10 | Foreign Key -> `tb_mobil(id_mobil)` | Relasi mobil (ON UPDATE CASCADE ON DELETE RESTRICT) |
| `tgl_sewa` | DATE | - | Not Null | Tanggal mulai peminjaman |
| `tgl_kembali` | DATE | - | Not Null | Tanggal selesai peminjaman |
| `lama_hari` | INT | - | Not Null | Durasi waktu sewa |
| `total_bayar` | DECIMAL | 12,2 | Not Null | Nominal biaya keseluruhan |
| `status_transaksi` | ENUM | 'Berjalan', 'Selesai', 'Batal' | Default 'Berjalan' | Status operasional sewa |

Data Dummy `tb_transaksi_sewa`:
| no_transaksi | id_penyewa | id_mobil | tgl_sewa | tgl_kembali | lama_hari | total_bayar | status_transaksi |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `TRX001` | `P01` | `M01` | `2026-11-01` | `2026-11-03` | 2 | 700000 | Selesai |
| `TRX002` | `P02` | `M03` | `2026-11-02` | `2026-11-05` | 3 | 900000 | Berjalan |
| `TRX003` | `P01` | `M04` | `2026-11-04` | `2026-11-06` | 2 | 1200000 | Selesai |
| `TRX004` | `P03` | `M02` | `2026-11-05` | `2026-11-06` | 1 | 400000 | Selesai |
| `TRX005` | `P04` | `M05` | `2026-11-07` | `2026-11-10` | 3 | 2700000 | Berjalan |

---

### 3. Entitas: Pelanggan Rental (`tb_pelanggan_rental`)
Kamus Data:
| Nama Kolom | Tipe Data | Size / Nilai | Konstrain & Aturan | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `id_penyewa` | VARCHAR | 10 | Primary Key | Kode identitas penyewa |
| `no_ktp` | VARCHAR | 20 | Not Null, Unique | Nomor Induk Kependudukan |
| `nama_penyewa` | VARCHAR | 100 | Not Null | Nama lengkap penyewa |
| `no_telepon` | VARCHAR | 20 | Not Null | Nomor telepon / WhatsApp |
| `alamat` | TEXT | - | Not Null | Alamat domisili |

Data Dummy `tb_pelanggan_rental`:
| id_penyewa | no_ktp | nama_penyewa | no_telepon | alamat |
| :--- | :--- | :--- | :--- | :--- |
| `P01` | `3201123456780001` | Bambang Prasetyo | 081211112222 | Jl. Sudirman No. 12, Jakarta |
| `P02` | `3201987654320002` | Ratna Sari | 081333334444 | Jl. Gatot Subroto No. 45, Bandung |
| `P03` | `3201555566660003` | Hendra Gunawan | 085677778888 | Jl. Pemuda No. 88, Semarang |
| `P04` | `3201444433330004` | Maya Indah | 087899990000 | Jl. Malioboro No. 05, Yogyakarta |

---

## III. PETUNJUK SOAL

1. **Pembuatan Basis Data:**  
   Buat basis data baru dengan nama `db_transjava_rent`.

2. **Perancangan Skema Tabel & Relasi:**  
   Berdasarkan Kamus Data yang telah disediakan:
   * Bangun seluruh tabel dengan tipe data, panjang ukuran kolom, dan batasan nilai (*constraint*) yang sesuai.
   * Tentukan kolom yang bertindak sebagai **Primary Key** pada masing-masing tabel.
   * Hubungkan tabel-tabel yang memiliki ketergantungan relasi menggunakan **Foreign Key** dengan opsi `ON UPDATE CASCADE` dan `ON DELETE RESTRICT`.

3. **Pengisian Data (Insert Data):**  
   * Masukkan seluruh data dummy yang tercantum pada tabel data di atas ke dalam masing-masing tabel pada basis data `db_transjava_rent`.
   * Seluruh baris data harus tersimpan secara utuh dan memenuhi aturan integritas relasional basis data.
