# LEMBAR SOAL UJIAN PRAKTIKUM 2 (PEMROGRAMAN DESKTOP C# WINFORMS)
**Mata Pelajaran:** Pemrograman Desktop  
**Kelas:** XI RPL / PPLG  
**Materi Uji:** Pengelolaan Data Tunggal (CRUD 1 Tabel) & Rekapitulasi Data  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Buat solusi proyek baru pada Visual Studio tipe **Windows Forms App** dengan nama: `PRAKTIKUM2_DESKTOP_NIS_NAMA`.
2. Rancang antarmuka utama pada form yang memuat komponen input, tombol aksi (Simpan, Ubah, Hapus, Bersihkan), tabel penyaji data (DataGridView), serta label ringkasan data.
3. Untuk layout dan penataan komponen dibuat rapi serta proporsional. Penambahan ikon/gambar bersifat "opsional".
4. Hubungkan aplikasi ke basis data `db_kopi_gudang`.
5. Pastikan seluruh fungsi pengelolaan data dan perhitungan berjalan sesuai spesifikasi kebutuhan yang ditentukan.

---

## II. DATA DUMMY
Import skrip basis data `database_bahan_baku.sql` ke dalam MySQL.

**Nama Database:** `db_kopi_gudang`  
**Nama Tabel:** `tb_bahan_baku`  

### Struktur Tabel `tb_bahan_baku`:
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_bahan` | INT (Auto Increment, Primary Key) | ID unik bahan baku |
| `kode_bahan` | VARCHAR(20) | Kode stok (Unique) |
| `nama_bahan` | VARCHAR(100) | Nama bahan baku / barang |
| `kategori` | ENUM('Biji Kopi', 'Susu & Dairy', 'Sirup & Pemanis', 'Kemasan') | Kelompok bahan |
| `stok` | INT | Kuantitas stok fisik |
| `satuan` | VARCHAR(20) | Satuan unit (Kg, Liter, Botol, Pcs) |
| `harga_satuan` | INT | Harga beli per satuan unit |
| `batas_minimum` | INT | Batas ambang stok minimum |
| `terakhir_update` | TIMESTAMP | Waktu terakhir data diubah |

### Data Awal Pengujian:
| Kode | Nama Bahan Baku | Stok | Satuan | Harga Satuan | Batas Min. | Status yang Diharapkan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `BB-001` | Biji Kopi Arabika Gayo Green Beans | 15 | Kg | Rp 120.000 | 10 | **Stok Aman** |
| `BB-002` | Susu Segar Pasteurisasi Fresh Milk | 8 | Liter | Rp 22.000 | 12 | **Kritis / Perlu Restock** |
| `BB-003` | Sirup Karamel Premium 750ml | 4 | Botol | Rp 85.000 | 5 | **Kritis / Perlu Restock** |
| `BB-004` | Paper Cup Hot 8oz Double Wall | 250 | Pcs | Rp 1.200 | 100 | **Stok Aman** |
| `BB-005` | Biji Kopi Robusta Temanggung | 25 | Kg | Rp 75.000 | 15 | **Stok Aman** |

---

## III. PETUNJUK SOAL

### 1. Menampilkan Data & Rekapitulasi Otomatis
Membuat menu pengelolaan persediaan mengacu pada kebutuhan sistem "Kelola Stok Bahan Baku".
* Menampilkan seluruh daftar bahan baku secara otomatis ke dalam tabel saat form dibuka.
* Ketentuan penanda status restock:
  * Jika jumlah stok berada di bawah atau sama dengan batas minimum (`stok <= batas_minimum`), maka baris data tersebut otomatis diberi penanda visual (misal warna latar baris berubah) sebagai penanda status **"Kritis / Perlu Restock"**.
  * Jika stok lebih besar dari batas minimum, maka baris tampil dengan format normal sebagai status **"Stok Aman"**.
* Pada label ringkasan data, munculkan secara otomatis:
  * Akumulasi total nilai valuasi stok bahan (total kuantitas stok dikalikan harga satuan untuk seluruh bahan yang ada).
  * Jumlah total item bahan yang saat ini berstatus kritis.

---

### 2. Tambah Data Bahan Baku
* Pastikan semua field wajib diisi, apabila ada yang kosong muncul warning *"Semua field wajib diisi !"*.
* Kode bahan tidak boleh sama dengan kode bahan yang sudah terdaftar. Apabila duplikat, muncul warning *"Kode bahan sudah digunakan !"*.
* Nilai stok tidak boleh bernilai negatif dan harga satuan harus bernilai lebih dari nol. Apabila tidak sesuai, muncul warning *"Input stok atau harga tidak valid !"*.
* Jika data berhasil disimpan:
  * Muncul pesan informasi *"Data bahan baku berhasil disimpan !"*.
  * Tabel data dan label ringkasan nilai otomatis diperbarui.
  * Seluruh field input dibersihkan secara otomatis.

---

### 3. Ubah Data Bahan Baku
* Ketika salah satu baris pada tabel diklik oleh pengguna:
  * Seluruh data pada baris terpilih otomatis berpindah ke field-field input form.
* Pengguna dapat memperbarui nama bahan, kategori, stok, satuan, harga, maupun batas minimum.
* Apabila tombol Ubah diklik saat belum ada baris data yang dipilih, muncul warning *"Pilih data yang ingin diubah terlebih dahulu !"*.
* Jika proses pembaruan berhasil:
  * Muncul pesan informasi *"Data bahan baku berhasil diperbarui !"*.
  * Tabel data dan label ringkasan nilai otomatis diperbarui.
  * Form input dibersihkan kembali.

---

### 4. Hapus Data Bahan Baku
* Pengguna memilih baris data yang ingin dihapus.
* Apabila tombol Hapus diklik saat belum ada baris yang dipilih, muncul warning *"Pilih data yang ingin dihapus terlebih dahulu !"*.
* Ketika data dipilih dan tombol Hapus ditekan, sistem memunculkan kotak konfirmasi *"Apakah anda yakin ingin menghapus data bahan baku ini ?"*.
* Jika user memilih "Yes", data terhapus dari sistem, muncul pesan informasi *"Data berhasil dihapus !"*, serta tabel dan label ringkasan otomatis diperbarui.
