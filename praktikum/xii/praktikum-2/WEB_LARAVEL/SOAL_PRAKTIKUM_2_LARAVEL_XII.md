# LEMBAR SOAL UJIAN PRAKTIKUM 2 (PEMROGRAMAN WEB LARAVEL)
**Mata Pelajaran:** Pemrograman Web dan Perangkat Bergerak  
**Kelas:** XII RPL / PPLG  
**Materi Uji:** Pengelolaan Data Tunggal (CRUD 1 Tabel) & Rekapitulasi Data (Framework Laravel)  
**Studi Kasus:** Pengelolaan Data Obat & Farmasi "Apotek FarmaMedika"  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. **Unduh Starter Pack Laravel (base_app.zip):** Untuk mempermudah pengerjaan dan agar siswa tidak perlu mengunduh atau menginstal framework Laravel secara manual dari internet, silakan unduh berkas template dasar:
   👉 **[Unduh Starter Laravel (base_app.zip)](index.php?action=download_base_app)** *(atau via tautan langsung: `xii/base_app.zip`)*.  
   Ekstrak arsip zip tersebut ke folder server lokal Anda (`c:\xampp\htdocs\` atau direktori kerja) dan ubah nama folder proyek menjadi: `PRAKTIKUM2_LARAVEL_NIS_NAMA`.
2. Salin template antarmuka Blade yang telah disediakan pada bagian **IV. KODE RAW BLADE TEMPLATE** di bawah ini (`index.blade.php`, `tambah.blade.php`, dan `edit.blade.php`).
3. Analisis formulir Blade yang disediakan. Desain antarmuka tidak boleh rusak.
4. Hubungkan konfigurasi database `.env` ke basis data `db_apotek_katalog`.
5. Modul CRUD ini murni bekerja pada satu tabel (`tb_obat`) tanpa melibatkan relasi antar-tabel.
6. Pastikan seluruh fungsi pengelolaan data (tambah, tampil, ubah, hapus) dan kalkulasi berjalan sesuai ketentuan yang ditentukan.
7. Hubungkan direktori proyek lokal Anda ke repositori GitHub pribadi yang berstatus **Public** dengan format tautan yang valid.
8. **Kewajiban Git Commit & Push Berkala (Setiap 30 Menit):** Siswa **WAJIB** melakukan `git commit` dan `git push` ke repositori GitHub secara berkala **minimal setiap 30 menit sekali** sepanjang durasi ujian (90 menit). Siswa dibebaskan untuk mengatur urutan pengerjaan fitur secara alami; setiap interval 30 menit, lakukan commit dan push apa pun progres perubahan kode yang telah Anda selesaikan.
9. **Komentar/Pesan Commit yang Relevan (Meaningful Commit Messages):** Setiap commit **WAJIB** menyertakan pesan/komentar yang jelas, informatif, dan mendeskripsikan secara akurat perubahan kode yang sebenarnya telah Anda kerjakan pada rentang waktu tersebut. DILARANG menggunakan komentar pemalas atau tidak jelas (seperti: *"update"*, *"commit"*, *"test"*, *"a"*, dsb.).

---

## II. DATA DUMMY
Import skrip basis data `database_obat.sql` ke dalam MySQL.

**Nama Database:** `db_apotek_katalog`  
**Nama Tabel:** `tb_obat`  

### Struktur Tabel `tb_obat`:
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_obat` | INT (Auto Increment, Primary Key) | ID unik obat |
| `kode_obat` | VARCHAR(20) | Kode item obat (Unique) |
| `nama_obat` | VARCHAR(100) | Nama dagang / sediaan obat |
| `kategori` | ENUM('Obat Bebas', 'Obat Bebas Terbatas', 'Obat Keras') | Golongan obat |
| `bentuk_sediaan` | ENUM('Tablet', 'Kapsul', 'Sirup', 'Salep') | Wujud sediaan farmasi |
| `stok` | INT | Jumlah kuantitas stok fisik |
| `harga` | INT | Harga normal per unit (Rupiah) |
| `status_promo` | ENUM('Ya', 'Tidak') | Keikutsertaan program diskon |
| `created_at` | TIMESTAMP | Waktu pembuatan data |

### Data Awal Pengujian:
| Kode | Nama Obat | Golongan | Sediaan | Stok | Harga Normal | Status Promo | Harga Akhir yang Diharapkan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `OBT-001` | Paracetamol 500mg Strip | Obat Bebas | Tablet | 150 | Rp 8.000 | Ya | Rp 7.200 (Diskon 10%) |
| `OBT-002` | Amoxicillin 500mg Kaplet | Obat Keras | Kapsul | 85 | Rp 18.000 | Tidak | Rp 18.000 (Normal) |
| `OBT-003` | Cetirizine 10mg Strip | Obat Bebas Terbatas | Tablet | 60 | Rp 12.000 | Ya | Rp 10.800 (Diskon 10%) |
| `OBT-004` | OBH Herbal Batuk Dewasa | Obat Bebas | Sirup | 40 | Rp 22.000 | Tidak | Rp 22.000 (Normal) |
| `OBT-005` | Hydrocortisone Salep 5g | Obat Bebas Terbatas | Salep | 30 | Rp 15.000 | Tidak | Rp 15.000 (Normal) |

---

## III. PETUNJUK SOAL

### 1. Halaman Katalog Obat & Rekapitulasi Data (`index.blade.php`)
Membuat tampilan katalog obat mengacu pada antarmuka "Katalog Obat & Farmasi".
* Menampilkan seluruh data obat secara otomatis ke dalam tabel terurut dari data yang paling baru ditambahkan.
* Memunculkan nomor urut baris tabel secara otomatis dan berurutan (1, 2, 3, dst.).
* Ketentuan perhitungan harga pada tabel:
  * Jika status promonya adalah **"Ya"**, maka sistem otomatis menghitung potongan harga sebesar 10% dari harga normal dan memunculkan badge bertuliskan **"Promo 10%"**.
  * Jika status promonya adalah **"Tidak"**, maka harga akhir sama dengan harga normal dan memunculkan badge bertuliskan **"Reguler"**.
* Pada panel kartu ringkasan di atas tabel, munculkan secara otomatis:
  * Total jumlah seluruh item obat yang terdaftar.
  * Total jumlah obat yang sedang dalam program promo apotek.
  * Akumulasi total nominal valuasi stok obat (kuantitas stok dikalikan harga normal).

---

### 2. Tambah Data Obat Baru (`tambah.blade.php`)
Membuat form entri obat mengacu pada antarmuka "Tambah Data Obat Baru".
* Pastikan semua field wajib diisi, apabila ada field yang kosong muncul pesan error validasi *"Semua field wajib diisi !"*.
* Kode obat tidak boleh sama dengan kode obat yang sudah tersimpan di sistem. Apabila duplikat, muncul error validasi *"Kode obat sudah digunakan !"*.
* Nilai kuantitas stok tidak boleh bernilai negatif dan harga harus lebih dari nol. Apabila tidak sesuai, muncul error validasi *"Harga harus lebih dari 0 !"*.
* Jika data berhasil disimpan:
  * Data baru tersimpan ke dalam database.
  * Pengguna dialihkan kembali ke halaman utama katalog obat (`/obat`) disertai pesan notifikasi *"Data obat berhasil ditambahkan !"*.

---

### 3. Ubah Data Obat (`edit.blade.php`)
Membuat form ubah obat mengacu pada antarmuka "Perbarui Data Obat".
* Ketika halaman dibuka berdasarkan obat yang dipilih, seluruh field formulir otomatis terisi dengan data lama obat tersebut.
* Pilihan golongan obat, bentuk sediaan, dan status promo pada dropdown otomatis terpilih sesuai data yang tersimpan sebelumnya.
* Pastikan semua field wajib diisi dan harga harus lebih dari 0.
* Jika proses pembaruan berhasil, pengguna dialihkan kembali ke halaman utama katalog obat disertai pesan notifikasi *"Data obat berhasil diperbarui !"*.

---

### 4. Hapus Data Obat
* Ketika tombol "Hapus" pada salah satu baris obat diklik, muncul konfirmasi peringatan dialog browser *"Apakah Anda yakin ingin menghapus data obat ini?"*.
* Jika pengguna mengonfirmasi, data obat tersebut dihapus dari sistem dan halaman katalog otomatis diperbarui dengan pesan notifikasi *"Data obat berhasil dihapus !"*.

---

## IV. KODE RAW BLADE TEMPLATE (SIAP SALIN)

Siswa dapat langsung menyalin kode Blade template di bawah ini ke dalam direktori `resources/views/` proyek Laravel masing-masing.

### 1. Template Katalog & Daftar Obat (`resources/views/index.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Obat - Apotek FarmaMedika</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 30px 20px;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: #ffffff;
            padding: 24px 30px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .header-title h2 {
            color: #166534;
            font-size: 24px;
            margin-bottom: 4px;
        }
        .header-title p {
            color: #15803d;
            font-size: 14px;
        }
        .btn-add {
            background-color: #166534;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
            display: inline-block;
        }
        .btn-add:hover {
            background-color: #14532d;
        }
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }
        .card-stat {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .card-stat h4 {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .card-stat .value {
            font-size: 22px;
            font-weight: 700;
            color: #166534;
        }
        .table-responsive {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        thead {
            background-color: #f1f5f9;
        }
        th {
            padding: 14px 18px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            border-bottom: 2px solid #cbd5e1;
        }
        td {
            padding: 14px 18px;
            font-size: 13.5px;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        tbody tr:hover {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-promo {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .badge-reguler {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .action-links {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .action-links a, .action-links button {
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }
        .link-edit {
            color: #2563eb;
        }
        .link-delete {
            color: #dc2626;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-section">
            <div class="header-title">
                <h2>Katalog Obat & Farmasi</h2>
                <p>Pengelolaan data stok, kategori sediaan, dan program diskon obat Apotek FarmaMedika</p>
            </div>
            <a href="obat/create" class="btn-add">+ Tambah Obat Baru</a>
        </div>

        <div class="summary-cards">
            <div class="card-stat">
                <h4>Total Jenis Obat Terdaftar</h4>
                <div class="value">0 Item</div>
            </div>
            <div class="card-stat">
                <h4>Obat Dalam Promo (Diskon 10%)</h4>
                <div class="value">0 Item</div>
            </div>
            <div class="card-stat">
                <h4>Total Estimasi Valuasi Stok</h4>
                <div class="value">Rp 0</div>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Sediaan</th>
                        <th>Stok</th>
                        <th>Harga Normal</th>
                        <th>Status Promo</th>
                        <th>Harga Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>OBT-001</strong></td>
                        <td>Paracetamol 500mg Strip</td>
                        <td>Obat Bebas</td>
                        <td>Tablet</td>
                        <td>150</td>
                        <td>Rp 8.000</td>
                        <td><span class="badge badge-promo">Promo 10%</span></td>
                        <td><strong>Rp 7.200</strong></td>
                        <td class="action-links">
                            <a href="obat/1/edit" class="link-edit">Ubah</a>
                            <form action="obat/1" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data obat ini?')">
                                <button type="submit" class="link-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>OBT-002</strong></td>
                        <td>Amoxicillin 500mg Kaplet</td>
                        <td>Obat Keras</td>
                        <td>Kapsul</td>
                        <td>85</td>
                        <td>Rp 18.000</td>
                        <td><span class="badge badge-reguler">Reguler</span></td>
                        <td><strong>Rp 18.000</strong></td>
                        <td class="action-links">
                            <a href="obat/2/edit" class="link-edit">Ubah</a>
                            <form action="obat/2" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data obat ini?')">
                                <button type="submit" class="link-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
```

---

### 2. Template Tambah Data Obat (`resources/views/tambah.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat Baru - Apotek FarmaMedika</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 35px 20px;
        }
        .form-card {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
        .form-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 15px;
        }
        .form-header h2 {
            color: #166534;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .form-header p {
            color: #64748b;
            font-size: 13px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #166534;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #16a34a;
        }
        .form-row {
            display: flex;
            gap: 12px;
        }
        .form-row .form-group {
            flex: 1;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        .btn-save {
            flex: 2;
            padding: 11px;
            background-color: #166534;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-save:hover {
            background-color: #14532d;
        }
        .btn-back {
            flex: 1;
            padding: 11px;
            background-color: #f1f5f9;
            color: #475569;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-back:hover {
            background-color: #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="form-card">
        <div class="form-header">
            <h2>Tambah Data Obat Baru</h2>
            <p>Masukkan rincian informasi obat, dosis sediaan, dan harga jual</p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Kode Obat</label>
                <input type="text" class="form-control" placeholder="Contoh: OBT-003" required>
            </div>

            <div class="form-group">
                <label>Nama Obat</label>
                <input type="text" class="form-control" placeholder="Contoh: Cetirizine 10mg Box" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Golongan Obat</label>
                    <select class="form-control" required>
                        <option value="">-- Pilih Golongan --</option>
                        <option value="Obat Bebas">Obat Bebas</option>
                        <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                        <option value="Obat Keras">Obat Keras</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bentuk Sediaan</label>
                    <select class="form-control" required>
                        <option value="">-- Pilih Sediaan --</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Kapsul">Kapsul</option>
                        <option value="Sirup">Sirup</option>
                        <option value="Salep">Salep</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kuantitas Stok</label>
                    <input type="number" class="form-control" placeholder="0" min="0" required>
                </div>
                <div class="form-group">
                    <label>Harga Normal (Rp)</label>
                    <input type="number" class="form-control" placeholder="Contoh: 15000" min="500" required>
                </div>
            </div>

            <div class="form-group">
                <label>Program Promo Apotek</label>
                <select class="form-control" required>
                    <option value="Tidak">Reguler (Tanpa Promo)</option>
                    <option value="Ya">Promo Aktif (Diskon 10%)</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-save">Simpan Data Obat</button>
                <a href="/obat" class="btn-back">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>
```

---

### 3. Template Ubah Data Obat (`resources/views/edit.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Obat - Apotek FarmaMedika</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 35px 20px;
        }
        .form-card {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
        .form-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 15px;
        }
        .form-header h2 {
            color: #166534;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .form-header p {
            color: #64748b;
            font-size: 13px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #166534;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #16a34a;
        }
        .form-row {
            display: flex;
            gap: 12px;
        }
        .form-row .form-group {
            flex: 1;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        .btn-update {
            flex: 2;
            padding: 11px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-update:hover {
            background-color: #1d4ed8;
        }
        .btn-back {
            flex: 1;
            padding: 11px;
            background-color: #f1f5f9;
            color: #475569;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-back:hover {
            background-color: #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="form-card">
        <div class="form-header">
            <h2>Perbarui Data Obat</h2>
            <p>Perbarui kuantitas stok, dosis sediaan, atau penyesuaian harga obat</p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Kode Obat</label>
                <input type="text" class="form-control" value="OBT-001" required>
            </div>

            <div class="form-group">
                <label>Nama Obat</label>
                <input type="text" class="form-control" value="Paracetamol 500mg Strip" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Golongan Obat</label>
                    <select class="form-control" required>
                        <option value="">-- Pilih Golongan --</option>
                        <option value="Obat Bebas" selected>Obat Bebas</option>
                        <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                        <option value="Obat Keras">Obat Keras</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bentuk Sediaan</label>
                    <select class="form-control" required>
                        <option value="">-- Pilih Sediaan --</option>
                        <option value="Tablet" selected>Tablet</option>
                        <option value="Kapsul">Kapsul</option>
                        <option value="Sirup">Sirup</option>
                        <option value="Salep">Salep</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kuantitas Stok</label>
                    <input type="number" class="form-control" value="150" min="0" required>
                </div>
                <div class="form-group">
                    <label>Harga Normal (Rp)</label>
                    <input type="number" class="form-control" value="8000" min="500" required>
                </div>
            </div>

            <div class="form-group">
                <label>Program Promo Apotek</label>
                <select class="form-control" required>
                    <option value="Tidak">Reguler (Tanpa Promo)</option>
                    <option value="Ya" selected>Promo Aktif (Diskon 10%)</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-update">Perbarui Data Obat</button>
                <a href="/obat" class="btn-back">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>
```

---

## V. KETENTUAN VERSION CONTROL (GIT & GITHUB)

Ujian praktikum ini menggunakan sistem kendali versi Git untuk memantau progres pengerjaan bertahap, orisinalitas kode, dan kedisiplinan alur kerja rekayasa perangkat lunak:

### 1. Aturan Commit & Push Periodik (Minimal Setiap 30 Menit)
* Sepanjang durasi ujian praktikum (90 menit), siswa **WAJIB** melakukan `git commit` dan `git push` ke repositori GitHub secara berkala **minimal setiap 30 menit sekali**.
* **Alur Pengerjaan Alami (Natural Progress):** Guru penguji **tidak membatasi atau menentukan** modul apa yang harus selesai pada menit tertentu. Kerjakan fitur-fitur praktikum secara alami sesuai urutan dan ritme pengerjaan Anda sendiri. Setiap kali interval 30 menit tercapai, segera lakukan commit dan push terhadap perubahan atau penambahan kode apa pun yang telah Anda capai saat itu.

### 2. Standar Komentar/Pesan Commit yang Relevan (Meaningful Commit Messages)
* Setiap pesan commit **WAJIB** menjelaskan secara jujur, jelas, dan akurat apa yang Anda ubah atau tambahkan pada rentang waktu tersebut.
* **DILARANG KERAS** menggunakan komentar pemalas atau asal-asalan (seperti: *"update"*, *"commit"*, *"test"*, *"tugas"*, *"a"*, *"fix"*, dsb.).
* Sesuaikan pesan commit dengan apa yang sebenarnya Anda kerjakan, contohnya:
  * Jika sedang membuat tabel/katalog: `git commit -m "feat: membuat tampilan katalog obat index.blade.php"`
  * Jika sedang menyelesaikan kalkulasi harga promo: `git commit -m "feat: implementasi perhitungan diskon 10% dan badge promo"`
  * Jika sedang membuat form tambah obat: `git commit -m "feat: pembuatan form tambah obat baru dan validasi request"`
  * Jika sedang mengerjakan fitur edit/hapus: `git commit -m "feat: implementasi update data obat dan dialog konfirmasi hapus"`
  * Jika sedang merapikan ringkasan akumulasi: `git commit -m "feat: kalkulasi ringkasan valuasi stok dan total obat"`

### 3. Kriteria Audit Version Control oleh Penguji
* Guru penguji akan memeriksa riwayat log commit (`git log --stat` / riwayat commit GitHub) pada tautan yang dikumpulkan di portal lab.
* **DILARANG** melakukan *single monolithic commit* (hanya melakukan 1 kali commit gelondongan di menit-menit akhir ujian).
* Siswa yang tidak mematuhi interval push 30 menit atau tidak menyertakan pesan commit yang sesuai dengan perubahan kode yang dilakukan akan dikenakan pemotongan nilai kedisiplinan teknis.
