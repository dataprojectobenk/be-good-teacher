# LEMBAR SOAL UJIAN PRAKTIKUM 2 (PEMROGRAMAN DESKTOP C# WINFORMS)
**Mata Pelajaran:** Pemrograman Desktop  
**Kelas:** XII RPL / PPLG  
**Materi Uji:** Pengelolaan Data Tunggal (CRUD 1 Tabel) & Rekapitulasi Data (C# Windows Forms)  
**Studi Kasus:** Pengelolaan Stok Suplemen & Alkes "Apotek FarmaMedika"  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Buat solusi proyek baru pada Visual Studio tipe **Windows Forms App** dengan nama: `PRAKTIKUM2_DESKTOP_XII_NIS_NAMA`.
2. Rancang antarmuka form yang memuat komponen input, tombol aksi (Simpan, Ubah, Hapus, Bersihkan), tabel data (DataGridView), serta label ringkasan data.
3. Tata letak komponen form dibuat rapi dan proporsional. Penambahan ikon/gambar bersifat "opsional".
4. Hubungkan aplikasi ke basis data `db_apotek_gudang`.
5. Pastikan seluruh fungsi pengelolaan data dan kalkulasi berjalan sesuai ketentuan yang ditentukan.

---

## II. DATA DUMMY
Import skrip basis data `database_suplemen.sql` ke dalam MySQL.

**Nama Database:** `db_apotek_gudang`  
**Nama Tabel:** `tb_suplemen`  

### Struktur Tabel `tb_suplemen`:
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_suplemen` | INT (Auto Increment, Primary Key) | ID unik barang |
| `kode_barang` | VARCHAR(20) | Kode stok suplemen/alkes (Unique) |
| `nama_barang` | VARCHAR(100) | Nama dagang produk |
| `kategori` | ENUM('Vitamin & Mineral', 'Herbal Sehat', 'Alat Kesehatan', 'Perawatan Luka') | Kelompok produk |
| `stok` | INT | Kuantitas stok fisik di gudang |
| `satuan` | VARCHAR(20) | Satuan unit (Botol, Strip, Pcs, Box, Unit) |
| `harga_satuan` | INT | Biaya modal per unit (Rupiah) |
| `batas_minimum` | INT | Batas ambang minimum restock |
| `terakhir_update` | TIMESTAMP | Waktu terakhir perubahan data |

### Data Awal untuk Pengujian CRUD & Status Restock:
| Kode | Nama Barang | Stok | Satuan | Harga Satuan | Batas Min. | Status yang Diharapkan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `SPL-001` | Vitamin C 500mg IPI Botol 45s | 50 | Botol | Rp 12.000 | 20 | **Stok Aman** |
| `SPL-002` | Minyak Kayu Putih 120ml Cap Lang | 8 | Botol | Rp 45.000 | 15 | **Kritis / Perlu Restock** |
| `SPL-003` | Tensimeter Digital Omron HEM-7120 | 4 | Unit | Rp 420.000 | 5 | **Kritis / Perlu Restock** |
| `SPL-004` | Kasa Steril Husada 16x16 Box | 35 | Box | Rp 15.000 | 10 | **Stok Aman** |
| `SPL-005` | Madu Murni Nusantara 250ml | 12 | Botol | Rp 55.000 | 10 | **Stok Aman** |

---

## III. PETUNJUK SOAL

### 1. Menampilkan Data & Rekapitulasi Otomatis
Membuat menu pengelolaan stok mengacu pada kebutuhan sistem "Kelola Persediaan Suplemen & Alkes".
* Menampilkan seluruh daftar produk suplemen/alkes secara otomatis ke dalam tabel saat form dibuka.
* Ketentuan penanda status restock:
  * Jika jumlah stok berada di bawah atau sama dengan batas minimum (`stok <= batas_minimum`), maka baris data tersebut otomatis diberi penanda visual (misal warna latar baris berubah) sebagai penanda status **"Kritis / Perlu Restock"**.
  * Jika stok lebih besar dari batas minimum, maka baris tampil dengan format normal sebagai status **"Stok Aman"**.
* Pada label ringkasan data, munculkan secara otomatis:
  * Akumulasi total nilai valuasi stok barang (total kuantitas stok dikalikan harga satuan untuk seluruh produk yang ada).
  * Jumlah total item barang yang saat ini berstatus kritis.

---

### 2. Tambah Data Produk Baru
* Pastikan semua field wajib diisi, apabila ada yang kosong muncul warning *"Semua field wajib diisi !"*.
* Kode barang tidak boleh sama dengan kode yang sudah terdaftar. Apabila duplikat, muncul warning *"Kode barang sudah digunakan !"*.
* Nilai stok tidak boleh bernilai negatif dan harga satuan harus bernilai lebih dari nol. Apabila tidak sesuai, muncul warning *"Input stok atau harga tidak valid !"*.
* Jika data berhasil disimpan:
  * Muncul pesan informasi *"Data produk apotek berhasil disimpan !"*.
  * Tabel data dan label ringkasan nilai otomatis diperbarui.
  * Seluruh field input dibersihkan secara otomatis.

---

### 3. Ubah Data Produk
* Ketika salah satu baris pada tabel diklik oleh pengguna:
  * Seluruh data pada baris terpilih otomatis berpindah ke field-field input form.
* Pengguna dapat memperbarui nama barang, kategori, stok, satuan, harga, maupun batas minimum.
* Apabila tombol Ubah diklik saat belum ada baris data yang dipilih, muncul warning *"Pilih data yang ingin diubah terlebih dahulu !"*.
* Jika proses pembaruan berhasil:
  * Muncul pesan informasi *"Data produk apotek berhasil diperbarui !"*.
  * Tabel data dan label ringkasan nilai otomatis diperbarui.
  * Form input dibersihkan kembali.

---

### 4. Hapus Data Produk
* Pengguna memilih baris data yang ingin dihapus.
* Apabila tombol Hapus diklik saat belum ada baris yang dipilih, muncul warning *"Pilih data yang ingin dihapus terlebih dahulu !"*.
* Ketika data dipilih dan tombol Hapus ditekan, sistem memunculkan kotak dialog konfirmasi *"Apakah anda yakin ingin menghapus produk ini ?"*.
* Jika user memilih "Yes", data terhapus dari sistem, muncul pesan informasi *"Data berhasil dihapus !"*, serta tabel dan label ringkasan otomatis diperbarui.
