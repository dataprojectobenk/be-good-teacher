# PANDUAN ASESMEN & RUBRIK PENILAIAN PENGUJI (GURU)
**Materi Uji:** Praktikum Pemrograman C# WinForms, PHP Native, dan Basis Data SQL  
**Sasaran:** Siswa SMK / SMA Kelas XI (Rekayasa Perangkat Lunak / PPLG)  

---

## 1. STRUKTUR DISTRIBUSI BOBOT NILAI

Setiap sesi praktikum dinilai dalam skala 0–100 dengan pembobotan komponen sebagai berikut:

| Komponen Praktikum | Praktikum 1 (Otentikasi & DDL) | Praktikum 2 (CRUD 1 Tabel & DML/Agregat) |
| :--- | :---: | :---: |
| **Pemrograman Web (PHP Native)** | 35% | 35% |
| **Pemrograman Desktop (C# WinForms)** | 35% | 35% |
| **Basis Data (MySQL / SQL)** | 30% | 30% |
| **Total** | **100%** | **100%** |

---

## 2. RUBRIK PENILAIAN PRAKTIKUM 1

### A. Pemrograman Web (PHP Native) — Maksimal 35 Poin
1. **Analisis Template & Formulir (5 Poin):** Siswa mampu menambahkan atribut `name` yang tepat pada input formulir tanpa mengubah styling CSS.
2. **Koneksi Database (5 Poin):** Konfigurasi `mysqli_connect` benar dan penanganan error berjalan baik.
3. **Validasi Percabangan Register (8 Poin):** Validasi field kosong, kecocokan kata sandi dengan konfirmasi sandi, dan batas minimal panjang 6 karakter.
4. **Algoritma Looping Validasi Sandi (5 Poin):** Perulangan karakter string berhasil mendeteksi keberadaan minimal 1 karakter digit angka.
5. **Autentikasi Login & Status Akun (7 Poin):** Pencocokan hash MD5 berhasil dan percabangan status akun `'Aktif'` vs `'Nonaktif'` berfungsi dengan pesan tepat.
6. **Proteksi Sesi Dashboard & Logout (5 Poin):** Proteksi `isset($_SESSION['login'])` memblokir akses langsung tanpa login, data sesi tampil, dan fungsi logout bekerja.

### B. Pemrograman Desktop (C# WinForms) — Maksimal 35 Poin
1. **Desain Form & Event Handler (5 Poin):** Desain form rapi, komponen masukan sesuai, dan logika ditulis langsung pada event-handler tanpa custom class.
2. **Koneksi ADO.NET (5 Poin):** Koneksi MySQL berhasil dengan pembungkus `try-catch`.
3. **Validasi Percabangan Register (8 Poin):** Validasi seluruh input terisi, kecocokan konfirmasi sandi, dan pencegahan duplikasi username.
4. **Algoritma Looping Validasi Sandi (5 Poin):** Penelusuran per karakter pada string password mendeteksi angka (`char.IsDigit`).
5. **Autentikasi Login & Penanganan Status (7 Poin):** Validasi login berhasil dan penolakan akun `status_aktif = 0` berfungsi memunculkan pesan.
6. **Proteksi Form Dashboard & Looping SOP (5 Poin):** Dashboard terproteksi validasi login state, serta perulangan array string berhasil mencetak nomor urut SOP ke `ListBox`.

### C. Basis Data (MySQL / MariaDB) — Maksimal 30 Poin
1. **Pembuatan Database & Tabel Master (10 Poin):** Sintaks `CREATE DATABASE` dan `CREATE TABLE` `tb_mobil` serta `tb_pelanggan_rental` tepat tipe data dan primary key-nya.
2. **Konfigurasi Foreign Key & Integritas Referensial (10 Poin):** Penulisan `CONSTRAINT ... FOREIGN KEY` pada `tb_transaksi_sewa` merujuk ke kedua tabel master dengan `ON UPDATE CASCADE ON DELETE RESTRICT`.
3. **Penyisipan Data Dummy (10 Poin):** Perintah `INSERT INTO` dieksekusi secara berurutan dan seluruh data berhasil masuk tanpa galat relasi.

---

## 3. RUBRIK PENILAIAN PRAKTIKUM 2

### A. Pemrograman Web (PHP Native) — Maksimal 35 Poin
1. **Analisis Formulir & Tambah Data (8 Poin):** Formulir dilengkapi atribut `name`, validasi harga $> 0$, pencegahan kode duplikat, dan eksekusi `INSERT INTO`.
2. **Read Data & Looping Tabel (8 Poin):** Perulangan `while` mencetak seluruh baris tabel dan nomor urut counter otomatis.
3. **Algoritma Percabangan Promo (7 Poin):** Perhitungan diskon 10% tepat untuk `status_promo == 'Ya'` dan penandaan badge CSS dinamis.
4. **Algoritma Looping Akumulasi Nilai (5 Poin):** Variabel akumulasi di dalam/setelah loop berhasil menghitung total item, total promo, dan total harga ke kartu ringkasan.
5. **Operasi Ubah & Hapus (7 Poin):** Mengisi kembali data lama ke form edit, eksekusi `UPDATE`, dan perintah `DELETE` berjalan sempurna.

### B. Pemrograman Desktop (C# WinForms) — Maksimal 35 Poin
1. **Integrasi DataGridView (7 Poin):** Pengambilan data dari database dan pengisian ke `DataGridView` berjalan lancar.
2. **Validasi Percabangan Tambah Data (7 Poin):** Validasi field kosong, angka numerik tidak boleh negatif, harga $> 0$, dan eksekusi `INSERT INTO`.
3. **Algoritma Percabangan Status Restock (7 Poin):** Deteksi kondisi `stok <= batas_minimum` berhasil mengubah warna baris / status kritis.
4. **Algoritma Looping Valuasi Stok (7 Poin):** Perulangan baris `DataGridView` berhasil menghitung `totalValuasi += (stok * hargaSatuan)` dan menghitung total item kritis.
5. **Operasi Ubah & Hapus (7 Poin):** Pemindahan data saat baris diklik, eksekusi `UPDATE`, dan konfirmasi dialog `DELETE`.

### C. Basis Data (MySQL / MariaDB) — Maksimal 30 Poin
1. **Manipulasi Data DML (9 Poin):**
   - Update tarif mobil SUV $> 2022$ naik 10% (3 Poin).
   - Update status mobil M05 menjadi 'Tersedia' (3 Poin).
   - Delete data transaksi berstatus 'Batal' (3 Poin).
2. **Klausa WHERE Lanjutan (11 Poin):**
   - Kombinasi logika `AND` dan `OR` + sorting tarif (3 Poin).
   - Penggunaan operator `BETWEEN` dan filter harga (3 Poin).
   - Pencarian pola string dengan `LIKE '%...%'` (2.5 Poin).
   - Filter himpunan data menggunakan operator `IN (...)` (2.5 Poin).
3. **Fungsi Agregasi & Grouping (10 Poin):**
   - Agregasi lengkap `COUNT`, `SUM`, `AVG`, `MAX`, `MIN` (4 Poin).
   - Pengelompokan `GROUP BY` tipe mobil dengan agregat (3 Poin).
   - Penyaringan kelompok agregat menggunakan `HAVING` $> 1500000$ (3 Poin).

---

## 4. KUNCI JAWABAN ACUAN PENGUJI (SQL REFERENCE)

### Kunci Jawaban Soal Praktikum 2 Basis Data:
```sql
-- 1. UPDATE Tarif SUV > 2022 naik 10%
UPDATE tb_mobil 
SET tarif_harian = tarif_harian * 1.10 
WHERE tipe = 'SUV' AND tahun_buat > 2022;

-- 2. UPDATE Status Mobil M05
UPDATE tb_mobil 
SET status_mobil = 'Tersedia' 
WHERE id_mobil = 'M05';

-- 3. DELETE Transaksi Batal
DELETE FROM tb_transaksi_sewa 
WHERE status_transaksi = 'Batal';

-- 4. WHERE AND & OR
SELECT no_polisi, merk, tipe, tarif_harian, status_mobil 
FROM tb_mobil 
WHERE status_mobil = 'Tersedia' AND (tipe = 'MPV' OR tipe = 'SUV')
ORDER BY tarif_harian DESC;

-- 5. WHERE BETWEEN
SELECT * FROM tb_mobil 
WHERE (tahun_buat BETWEEN 2021 AND 2023) AND tarif_harian < 600000;

-- 6. WHERE LIKE
SELECT * FROM tb_pelanggan_rental 
WHERE nama_penyewa LIKE '%Dewi%' OR alamat LIKE '%Bandung%';

-- 7. WHERE IN
SELECT * FROM tb_mobil 
WHERE tipe IN ('City Car', 'Minibus');

-- 8. Agregasi Dasar
SELECT 
    COUNT(*) AS total_transaksi_selesai,
    SUM(total_bayar) AS total_pendapatan,
    AVG(total_bayar) AS rata_rata_sewa,
    MAX(total_bayar) AS sewa_tertinggi,
    MIN(total_bayar) AS sewa_terendah
FROM tb_transaksi_sewa 
WHERE status_transaksi = 'Selesai';

-- 9. GROUP BY Tipe Mobil
SELECT 
    tipe,
    COUNT(*) AS jumlah_unit,
    AVG(tarif_harian) AS rata_rata_tarif
FROM tb_mobil 
GROUP BY tipe;

-- 10. GROUP BY dengan HAVING
SELECT 
    id_mobil,
    SUM(total_bayar) AS total_pembayaran
FROM tb_transaksi_sewa 
GROUP BY id_mobil 
HAVING total_pembayaran > 1500000;
```
