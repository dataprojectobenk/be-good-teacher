# LEMBAR SOAL UJIAN PRAKTIKUM 2 (BASIS DATA / SQL)
**Mata Pelajaran:** Basis Data  
**Kelas:** XII RPL / PPLG  
**Materi Uji:** Manipulasi Data, Penyaringan Lanjutan, dan Rekapitulasi Statistik  
**Studi Kasus:** Sistem Ekspedisi Logistik "Nusantara Cargo Express"  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Siapkan lingkungan basis data dengan mengimpor berkas `skema_awal_praktikum_2_xii.sql` ke dalam database `db_nusantara_cargo`.
2. Tulis seluruh kueri SQL jawaban Anda secara berurutan ke dalam sebuah berkas teks dengan format nama: `PRAKTIKUM2_DB_XII_NIS_NAMA.sql`.
3. Cocokkan hasil eksekusi kueri yang Anda buat dengan **Hasil Akhir yang Diharapkan** yang tertera pada setiap nomor soal.
4. Kueri yang benar akan menghasilkan susunan kolom dan data keluaran yang persis sama dengan contoh hasil akhir.
5. Jalankan perintah secara berurutan dari nomor 1 sampai 10.

---

## II. DATA DUMMY
Gunakan data awal dari berkas `skema_awal_praktikum_2_xii.sql` yang memuat tabel:
- `tb_armada_kargo` (8 unit kendaraan angkut logistik).
- `tb_pelanggan_kargo` (5 data pelanggan perusahaan/perorangan).
- `tb_resi_pengiriman` (8 data faktur resi pengiriman).

---

## III. PETUNJUK SOAL & HASIL AKHIR YANG DIHARAPKAN

### Bagian A: Perubahan dan Pembersihan Data

#### 1. Penyesuaian Tarif Muatan Berat
Manajemen menetapkan kenaikan tarif dasar sebesar 10% untuk armada yang memiliki daya angkut muatan **di atas 5.000 Kg** (`kapasitas_kg > 5000`).  
*Terapkan perubahan data tersebut ke dalam sistem.*

**Hasil Akhir yang Diharapkan saat data diverifikasi:**
```
SELECT id_armada, jenis_kendaraan, kapasitas_kg, tarif_per_kg 
FROM tb_armada_kargo 
WHERE kapasitas_kg > 5000;
```
| id_armada | jenis_kendaraan | kapasitas_kg | tarif_per_kg |
| :--- | :--- | :---: | :---: |
| `ARM-04` | Truk Fuso Box | 10000 | 6600.00 |
| `ARM-05` | Truk Tronton Wingbox | 20000 | 5500.00 |
| `ARM-08` | Truk Trailer Kontainer | 30000 | 4950.00 |

---

#### 2. Pembaruan Status Kesiapan Armada
Armada dengan kode **ARM-02** yang sebelumnya berstatus *Operasional* telah menyelesaikan jadwal rute dan kembali ke pangkalan. Perbarui status armada tersebut menjadi **Tersedia**.

**Hasil Akhir yang Diharapkan saat data diverifikasi:**
```
SELECT id_armada, no_polisi, jenis_kendaraan, status_armada 
FROM tb_armada_kargo 
WHERE id_armada = 'ARM-02';
```
| id_armada | no_polisi | jenis_kendaraan | status_armada |
| :--- | :--- | :--- | :--- |
| `ARM-02` | B 9202 KRG | Truk Engkel CDE | Tersedia |

---

#### 3. Pembersihan Riwayat Pembatalan Resi
Bersihkan seluruh faktur resi pengiriman yang berstatus **Batal** agar tidak mempengaruhi rekapitulasi operasional logistik.

**Hasil Akhir yang Diharapkan saat data diverifikasi:**
```
SELECT COUNT(*) AS sisa_resi_batal 
FROM tb_resi_pengiriman 
WHERE status_kirim = 'Batal';
```
| sisa_resi_batal |
| :---: |
| 0 |

---

### Bagian B: Penyaringan Data Tertarget

#### 4. Daftar Armada Siap Angkut Muatan Tertentu
Tampilkan kode armada, nomor polisi, jenis kendaraan, kapasitas muatan, dan status armada untuk armada yang berstatus **Tersedia** dan jenis kendaraannya tergolong jenis **Blind Van GranMax** atau **Truk Double CDD**. Urutkan tampilan dari kapasitas angkut terbesar ke terkecil.

**Hasil Akhir yang Diharapkan:**
| id_armada | no_polisi | jenis_kendaraan | kapasitas_kg | status_armada |
| :--- | :--- | :--- | :---: | :--- |
| `ARM-03` | B 9303 KRG | Truk Double CDD | 5000 | Tersedia |
| `ARM-01` | B 9101 KRG | Blind Van GranMax | 1000 | Tersedia |

---

#### 5. Pencarian Resi Kargo Berdasarkan Rentang Waktu dan Muatan
Tampilkan nomor resi, kode pengirim, tanggal kirim, berat muatan, dan total ongkir untuk pengiriman yang diberangkatkan antara tanggal **2026-11-01 sampai 2026-11-04** dengan berat paket **di bawah 500 Kg**.

**Hasil Akhir yang Diharapkan:**
| no_resi | id_pengirim | tgl_kirim | berat_kg | total_ongkir |
| :--- | :--- | :--- | :---: | :---: |
| `EXP-001` | CUST-01 | 2026-11-01 | 45.00 | 450000.00 |
| `EXP-002` | CUST-02 | 2026-11-02 | 320.00 | 2560000.00 |
| `EXP-004` | CUST-03 | 2026-11-04 | 80.00 | 800000.00 |

---

#### 6. Pencarian Data Pelanggan Berdasarkan Nama atau Wilayah Asal
Tampilkan seluruh data pelanggan kargo yang nama pengirimnya mengandung unsur kata **Sentosa** atau beralamat di kota asal **Bandung**.

**Hasil Akhir yang Diharapkan:**
| id_pengirim | no_ktp | nama_pengirim | no_telepon | kota_asal |
| :--- | :--- | :--- | :--- | :--- |
| `CUST-02` | 3273023456780002 | CV Bandung Tekstil Mandiri | 081223344556 | Bandung |
| `CUST-04` | 3578045678900004 | PT Surabaya Makmur Sentosa | 081556677889 | Surabaya |

---

#### 7. Penyaringan Kelompok Asal Pengiriman
Tampilkan kode pengirim, nama pengirim, nomor telepon, dan kota asal untuk pengirim yang berasal dari wilayah **Jakarta** atau **Semarang**.

**Hasil Akhir yang Diharapkan:**
| id_pengirim | nama_pengirim | no_telepon | kota_asal |
| :--- | :--- | :--- | :--- |
| `CUST-01` | PT Sinar Logistik Abadi | 081112223334 | Jakarta |
| `CUST-03` | Hendro Wicaksono Santoso | 081334455667 | Semarang |
| `CUST-05` | Dewi Lestari Nusantara | 081778899001 | Jakarta |

---

### Bagian C: Rangkuman Statistik & Pengelompokan Data

#### 8. Ringkasan Finansial Pengiriman Sukses Terkirim
Tampilkan ringkasan statistik khusus untuk pengiriman yang berstatus **Terkirim**, dengan rincian kolom:
- `total_resi_terkirim` : Banyaknya paket yang telah berhasil terkirim.
- `total_penerimaan_ongkir` : Akumulasi pendapatan ongkos kirim.
- `rata_rata_ongkir` : Rata-rata nominal ongkir per pengiriman.
- `ongkir_tertinggi` : Nilai ongkir transaksi terbesar.
- `ongkir_terendah` : Nilai ongkir transaksi terkecil.

**Hasil Akhir yang Diharapkan:**
| total_resi_terkirim | total_penerimaan_ongkir | rata_rata_ongkir | ongkir_tertinggi | ongkir_terendah |
| :---: | :---: | :---: | :---: | :---: |
| 5 | 26800000.00 | 5360000.000000 | 17500000.00 | 450000.00 |

---

#### 9. Rekapitulasi Muatan Berdasarkan Status Pengiriman
Tampilkan rekapitulasi data dari tabel resi pengiriman yang dikelompokkan menurut **status pengiriman** aktif, menampilkan:
- `status_kirim` : Status ekspedisi.
- `jumlah_resi` : Total resi pada status tersebut.
- `total_berat_muatan` : Akumulasi berat paket (Kg) pada masing-masing status.

**Hasil Akhir yang Diharapkan:**
| status_kirim | jumlah_resi | total_berat_muatan |
| :--- | :---: | :---: |
| Dalam Perjalanan | 2 | 2170.00 |
| Terkirim | 5 | 4775.00 |

---

#### 10. Daftar Pelanggan dengan Akumulasi Ongkir Terbesar
Hitung akumulasi total biaya ongkir yang telah dibayarkan oleh masing-masing pelanggan kargo (`id_pengirim`). Tampilkan hanya pelanggan yang memiliki total akumulasi ongkir **lebih dari Rp 5.000.000**.

**Hasil Akhir yang Diharapkan:**
| id_pengirim | total_ongkos_kirim |
| :--- | :---: |
| `CUST-01` | 5700000.00 |
| `CUST-02` | 20060000.00 |
| `CUST-04` | 11100000.00 |
