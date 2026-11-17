# LEMBAR SOAL UJIAN PRAKTIKUM 1 (BASIS DATA / SQL)
**Mata Pelajaran:** Basis Data  
**Kelas:** XII RPL / PPLG  
**Materi Uji:** Data Definition Language (DDL), Foreign Key (Integritas Referensial), dan Insert Data (MySQL / MariaDB)  
**Studi Kasus:** Sistem Ekspedisi Logistik "Nusantara Cargo Express"  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Kerjakan tugas menggunakan perkakas baris perintah (**MySQL Command Line Client**) atau antarmuka GUI basis data (**phpMyAdmin / DBeaver / HeidiSQL**).
2. Tulis seluruh perintah SQL jawaban Anda ke dalam sebuah berkas teks berekstensi `.sql` dengan format penamaan: `PRAKTIKUM1_DB_XII_NIS_NAMA.sql`.
3. Buat basis data baru dengan nama `db_nusantara_cargo`.
4. Rancang seluruh tabel beserta tipe data dan konstrain yang tertera pada Kamus Data.
5. Masukkan seluruh data dummy yang telah disediakan ke dalam tabel yang sesuai.
6. Seluruh skrip wajib dapat dieksekusi secara utuh tanpa memicu pesan galat (*error*).

---

## II. KAMUS DATA & DATA DUMMY

### 1. Entitas: Armada Angkutan Kargo (`tb_armada_kargo`)
Kamus Data:
| Nama Kolom | Tipe Data | Size / Nilai | Konstrain & Aturan | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `id_armada` | VARCHAR | 10 | Primary Key | Kode identitas armada kargo |
| `no_polisi` | VARCHAR | 15 | Not Null, Unique | Plat nomor kendaraan |
| `jenis_kendaraan` | VARCHAR | 30 | Not Null | Jenis unit (Blind Van, Truk CDD, Wingbox, dll) |
| `kapasitas_kg` | INT | - | Not Null | Daya angkut muatan maksimum dalam Kg |
| `status_armada` | ENUM | 'Tersedia', 'Operasional', 'Perawatan' | Default 'Tersedia' | Status fisik armada |

Data Dummy `tb_armada_kargo`:
| id_armada | no_polisi | jenis_kendaraan | kapasitas_kg | status_armada |
| :--- | :--- | :--- | :--- | :--- |
| `ARM-01` | `B 9101 KRG` | Blind Van GranMax | 1000 | Tersedia |
| `ARM-02` | `B 9202 KRG` | Truk Engkel CDE | 2500 | Operasional |
| `ARM-03` | `B 9303 KRG` | Truk Double CDD | 5000 | Tersedia |
| `ARM-04` | `D 8404 KRG` | Truk Fuso Box | 10000 | Operasional |
| `ARM-05` | `L 7505 KRG` | Truk Tronton Wingbox | 20000 | Tersedia |

---

### 2. Entitas: Resi Pengiriman Barang (`tb_resi_pengiriman`)
Kamus Data:
| Nama Kolom | Tipe Data | Size / Nilai | Konstrain & Aturan | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `no_resi` | VARCHAR | 15 | Primary Key | Nomor faktur resi pengiriman |
| `id_pengirim` | VARCHAR | 10 | Foreign Key -> `tb_pelanggan_kargo(id_pengirim)` | Relasi pengirim (ON UPDATE CASCADE ON DELETE RESTRICT) |
| `id_armada` | VARCHAR | 10 | Foreign Key -> `tb_armada_kargo(id_armada)` | Relasi armada (ON UPDATE CASCADE ON DELETE RESTRICT) |
| `tgl_kirim` | DATE | - | Not Null | Tanggal keberangkatan barang |
| `berat_kg` | DECIMAL | 8,2 | Not Null | Berat timbangan paket dalam Kg |
| `total_ongkir` | DECIMAL | 12,2 | Not Null | Total biaya kirim |
| `status_kirim` | ENUM | 'Diproses', 'Dalam Perjalanan', 'Terkirim', 'Batal' | Default 'Diproses' | Status ekspedisi |

Data Dummy `tb_resi_pengiriman`:
| no_resi | id_pengirim | id_armada | tgl_kirim | berat_kg | total_ongkir | status_kirim |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `EXP-001` | `CUST-01` | `ARM-01` | `2026-11-01` | 45.50 | 455000 | Terkirim |
| `EXP-002` | `CUST-02` | `ARM-02` | `2026-11-02` | 320.00 | 2560000 | Dalam Perjalanan |
| `EXP-003` | `CUST-01` | `ARM-03` | `2026-11-03` | 750.00 | 5250000 | Terkirim |
| `EXP-004` | `CUST-03` | `ARM-01` | `2026-11-04` | 80.00 | 800000 | Terkirim |
| `EXP-005` | `CUST-04` | `ARM-04` | `2026-11-05` | 1850.00 | 11100000 | Dalam Perjalanan |

---

### 3. Entitas: Pelanggan Pengirim Kargo (`tb_pelanggan_kargo`)
Kamus Data:
| Nama Kolom | Tipe Data | Size / Nilai | Konstrain & Aturan | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `id_pengirim` | VARCHAR | 10 | Primary Key | Kode identitas pengirim |
| `no_ktp` | VARCHAR | 20 | Not Null, Unique | Nomor identitas KTP pengirim |
| `nama_pengirim` | VARCHAR(100) | - | Not Null | Nama perorangan / perusahaan |
| `no_telepon` | VARCHAR | 20 | Not Null | Nomor telepon / HP |
| `kota_asal` | VARCHAR | 50 | Not Null | Kota kantor asal pengiriman |

Data Dummy `tb_pelanggan_kargo`:
| id_pengirim | no_ktp | nama_pengirim | no_telepon | kota_asal |
| :--- | :--- | :--- | :--- | :--- |
| `CUST-01` | `3171012345670001` | PT Sinar Logistik Abadi | 081112223334 | Jakarta |
| `CUST-02` | `3273023456780002` | CV Bandung Tekstil Mandiri | 081223344556 | Bandung |
| `CUST-03` | `3374034567890003` | Hendro Wicaksono | 081334455667 | Semarang |
| `CUST-04` | `3578045678900004` | PT Surabaya Makmur Sentosa | 081556677889 | Surabaya |

---

## III. PETUNJUK SOAL

1. **Pembuatan Basis Data:**  
   Buat basis data baru dengan nama `db_nusantara_cargo`.

2. **Perancangan Skema Tabel & Relasi:**  
   Berdasarkan Kamus Data yang telah disediakan:
   * Bangun seluruh tabel dengan tipe data, panjang ukuran kolom, dan batasan nilai (*constraint*) yang sesuai.
   * Tentukan kolom yang bertindak sebagai **Primary Key** pada masing-masing tabel.
   * Hubungkan tabel-tabel yang memiliki ketergantungan relasi menggunakan **Foreign Key** dengan opsi `ON UPDATE CASCADE` dan `ON DELETE RESTRICT`.

3. **Pengisian Data (Insert Data):**  
   * Masukkan seluruh data dummy yang tercantum pada tabel data di atas ke dalam masing-masing tabel pada basis data `db_nusantara_cargo`.
   * Seluruh baris data harus tersimpan secara utuh dan memenuhi aturan integritas relasional basis data.
