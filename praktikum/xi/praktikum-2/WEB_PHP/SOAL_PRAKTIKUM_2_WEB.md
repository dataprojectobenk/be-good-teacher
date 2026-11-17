# LEMBAR SOAL UJIAN PRAKTIKUM 2 (PEMROGRAMAN WEB PHP)
**Mata Pelajaran:** Pemrograman Web dan Perangkat Bergerak  
**Kelas:** XI RPL / PPLG  
**Materi Uji:** Pengelolaan Data Tunggal (CRUD 1 Tabel) & Rekapitulasi Data  
**Alokasi Waktu:** 90 Menit  

---

## I. INSTRUKSI UMUM
1. Buat folder proyek kerja pada direktori web server lokal dengan format: `PRAKTIKUM2_WEB_NIS_NAMA`.
2. Salin template antarmuka yang telah disediakan pada bagian **IV. KODE RAW TEMPLATE TAMPILAN** di bawah ini (`index.html`, `tambah.html`, dan `edit.html`).
3. Analisis formulir dan tabel yang disediakan. Desain antarmuka tidak boleh rusak.
4. Hubungkan aplikasi web ke basis data `db_kopi_katalog`.
5. Pastikan seluruh alur pengelolaan data berjalan sesuai spesifikasi kebutuhan yang ditentukan.

---

## II. DATA DUMMY
Import skrip basis data `database_menu.sql` ke dalam MySQL.

**Nama Database:** `db_kopi_katalog`  
**Nama Tabel:** `tb_menu`  

### Struktur Tabel `tb_menu`:
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_menu` | INT (Auto Increment, Primary Key) | ID unik menu |
| `kode_menu` | VARCHAR(20) | Kode item menu (Unique) |
| `nama_menu` | VARCHAR(100) | Nama menu sajian |
| `kategori` | ENUM('Kopi', 'Non-Kopi', 'Makanan Ringan') | Kelompok sajian |
| `harga` | INT | Harga normal |
| `status_promo` | ENUM('Ya', 'Tidak') | Keikutsertaan program promo |
| `terakhir_diperbarui` | TIMESTAMP | Waktu terakhir pembaruan data |

### Data Awal Pengujian:
| Kode Menu | Nama Menu | Kategori | Harga Normal | Status Promo | Harga Akhir yang Diharapkan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `CF-001` | Espresso Single Origin Gayo | Kopi | Rp 25.000 | Ya | Rp 22.500 (Diskon 10%) |
| `NC-002` | Matcha Latte Signature | Non-Kopi | Rp 28.000 | Tidak | Rp 28.000 (Normal) |
| `CF-003` | Caramel Macchiato Ice | Kopi | Rp 32.000 | Ya | Rp 28.800 (Diskon 10%) |
| `SN-004` | Croissant Butter Almond | Makanan Ringan | Rp 24.000 | Tidak | Rp 24.000 (Normal) |
| `NC-005` | Earl Grey Milk Tea | Non-Kopi | Rp 26.000 | Tidak | Rp 26.000 (Normal) |

---

## III. PETUNJUK SOAL

### 1. Halaman Katalog & Rekapitulasi Data (`index.php`)
Membuat tampilan katalog menu mengacu pada antarmuka "Katalog Menu Kopi Nusantara".
* Menampilkan seluruh data menu secara otomatis ke dalam tabel dari data yang paling baru ditambahkan.
* Memunculkan nomor urut baris tabel secara otomatis dan berurutan (1, 2, 3, dst.).
* Ketentuan perhitungan harga pada tabel:
  * Jika status promonya adalah **"Ya"**, maka sistem otomatis menghitung potongan harga sebesar 10% dari harga normal dan memunculkan badge bertuliskan **"Promo 10%"**.
  * Jika status promonya adalah **"Tidak"**, maka harga akhir tetap sama dengan harga normal dan memunculkan badge bertuliskan **"Reguler"**.
* Pada panel kartu ringkasan di atas tabel, munculkan secara otomatis:
  * Total jumlah seluruh menu yang terdaftar.
  * Total jumlah menu yang sedang dalam program promo.
  * Akumulasi total nominal harga seluruh menu.

---

### 2. Halaman Tambah Menu Baru (`tambah.php`)
Membuat form entri menu mengacu pada antarmuka "Tambah Menu Baru".
* Pastikan semua field wajib diisi, apabila ada yang kosong muncul warning *"Semua field wajib diisi !"*.
* Kode menu tidak boleh sama dengan kode menu yang sudah ada di sistem. Apabila duplikat, muncul warning *"Kode menu sudah digunakan !"*.
* Nilai harga normal harus berupa angka lebih dari nol. Apabila tidak sesuai, muncul warning *"Harga harus lebih dari 0 !"*.
* Jika proses penyimpanan berhasil, maka user otomatis dialihkan kembali ke halaman utama katalog menu (`index.php`).

---

### 3. Halaman Ubah Menu (`edit.php`)
Membuat form ubah menu mengacu pada antarmuka "Perbarui Data Menu".
* Ketika halaman dibuka berdasarkan menu yang dipilih, seluruh field formulir otomatis terisi dengan data lama menu tersebut.
* Pilihan kategori dan status promo pada dropdown otomatis terpilih sesuai data yang tersimpan sebelumnya.
* Pastikan semua field wajib diisi dan harga harus lebih dari 0.
* Jika proses pembaruan berhasil, maka user otomatis dialihkan kembali ke halaman utama katalog menu (`index.php`).

---

### 4. Hapus Menu (`hapus.php`)
* Ketika tombol "Hapus" pada salah satu baris menu diklik, muncul konfirmasi peringatan *"Apakah anda yakin ingin menghapus menu ini ?"*.
* Jika user mengonfirmasi, maka data menu tersebut dihapus dari sistem dan halaman katalog menu otomatis disegarkan.

---

## IV. KODE RAW TEMPLATE TAMPILAN (SIAP SALIN)

Siswa dapat langsung menyalin kode HTML/CSS di bawah ini ke dalam berkas kerja masing-masing.

### 1. Template Halaman Utama & Katalog (`index.html` / `index.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu - Kopi Nusantara</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f7f5f0;
            color: #333333;
            padding: 30px 20px;
        }
        .container {
            max-width: 1080px;
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
            border: 1px solid #e0d5c1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .header-title h2 {
            color: #4e342e;
            font-size: 24px;
            margin-bottom: 4px;
        }
        .header-title p {
            color: #795548;
            font-size: 14px;
        }
        .btn-add {
            background-color: #4e342e;
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
            background-color: #3e2723;
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
            border: 1px solid #e0d5c1;
        }
        .card-stat h4 {
            color: #8d6e63;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .card-stat .value {
            font-size: 22px;
            font-weight: 700;
            color: #4e342e;
        }
        .table-responsive {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e0d5c1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        thead {
            background-color: #efebe9;
        }
        th {
            padding: 14px 18px;
            font-size: 13px;
            font-weight: 700;
            color: #4e342e;
            border-bottom: 2px solid #d7ccc8;
        }
        td {
            padding: 14px 18px;
            font-size: 14px;
            color: #4e342e;
            border-bottom: 1px solid #f4efe6;
            vertical-align: middle;
        }
        tbody tr:hover {
            background-color: #faf8f5;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-promo {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .badge-reguler {
            background-color: #f5f5f5;
            color: #616161;
            border: 1px solid #e0e0e0;
        }
        .action-links a {
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            margin-right: 12px;
        }
        .link-edit {
            color: #1976d2;
        }
        .link-delete {
            color: #d32f2f;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-section">
            <div class="header-title">
                <h2>Katalog Menu Kopi Nusantara</h2>
                <p>Pengelolaan data harga, kategori sajian, dan program promo kafe</p>
            </div>
            <a href="tambah.php" class="btn-add">+ Tambah Menu Baru</a>
        </div>

        <div class="summary-cards">
            <div class="card-stat">
                <h4>Total Menu Terdaftar</h4>
                <div class="value">0 Item</div>
            </div>
            <div class="card-stat">
                <h4>Menu Dalam Promo (Diskon 10%)</h4>
                <div class="value">0 Item</div>
            </div>
            <div class="card-stat">
                <h4>Total Akumulasi Harga Menu</h4>
                <div class="value">Rp 0</div>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga Normal</th>
                        <th>Status Promo</th>
                        <th>Harga Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>CF-001</strong></td>
                        <td>Espresso Single Origin Gayo</td>
                        <td>Kopi</td>
                        <td>Rp 25.000</td>
                        <td><span class="badge badge-promo">Promo 10%</span></td>
                        <td><strong>Rp 22.500</strong></td>
                        <td class="action-links">
                            <a href="edit.php?id=1" class="link-edit">Ubah</a>
                            <a href="hapus.php?id=1" class="link-delete" onclick="return confirm('Hapus menu ini?')">Hapus</a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>NC-002</strong></td>
                        <td>Matcha Latte Signature</td>
                        <td>Non-Kopi</td>
                        <td>Rp 28.000</td>
                        <td><span class="badge badge-reguler">Reguler</span></td>
                        <td><strong>Rp 28.000</strong></td>
                        <td class="action-links">
                            <a href="edit.php?id=2" class="link-edit">Ubah</a>
                            <a href="hapus.php?id=2" class="link-delete" onclick="return confirm('Hapus menu ini?')">Hapus</a>
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

### 2. Template Halaman Tambah Menu (`tambah.html` / `tambah.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - Kopi Nusantara</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f7f5f0;
            color: #333333;
            padding: 35px 20px;
        }
        .form-card {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            border: 1px solid #e0d5c1;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .form-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #efebe9;
            padding-bottom: 15px;
        }
        .form-header h2 {
            color: #4e342e;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .form-header p {
            color: #795548;
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
            color: #4e342e;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d7ccc8;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #6d4c41;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        .btn-save {
            flex: 2;
            padding: 11px;
            background-color: #4e342e;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-save:hover {
            background-color: #3e2723;
        }
        .btn-back {
            flex: 1;
            padding: 11px;
            background-color: #efebe9;
            color: #5d4037;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-back:hover {
            background-color: #d7ccc8;
        }
    </style>
</head>
<body>

    <div class="form-card">
        <div class="form-header">
            <h2>Tambah Menu Baru</h2>
            <p>Masukkan rincian informasi menu sajian baru ke dalam katalog</p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Kode Menu</label>
                <input type="text" class="form-control" placeholder="Contoh: CF-003" required>
            </div>

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" class="form-control" placeholder="Contoh: Caramel Macchiato" required>
            </div>

            <div class="form-group">
                <label>Kategori Sajian</label>
                <select class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Kopi">Kopi</option>
                    <option value="Non-Kopi">Non-Kopi</option>
                    <option value="Makanan Ringan">Makanan Ringan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Harga Normal (Rupiah)</label>
                <input type="number" class="form-control" placeholder="Contoh: 25000" min="1000" required>
            </div>

            <div class="form-group">
                <label>Status Program Promo</label>
                <select class="form-control" required>
                    <option value="Tidak">Tidak Ada Promo</option>
                    <option value="Ya">Promo Aktif (Diskon 10%)</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-save">Simpan Menu</button>
                <a href="index.php" class="btn-back">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>
```

---

### 3. Template Halaman Ubah Menu (`edit.html` / `edit.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Menu - Kopi Nusantara</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f7f5f0;
            color: #333333;
            padding: 35px 20px;
        }
        .form-card {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            border: 1px solid #e0d5c1;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .form-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #efebe9;
            padding-bottom: 15px;
        }
        .form-header h2 {
            color: #4e342e;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .form-header p {
            color: #795548;
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
            color: #4e342e;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d7ccc8;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #6d4c41;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        .btn-update {
            flex: 2;
            padding: 11px;
            background-color: #1976d2;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-update:hover {
            background-color: #1565c0;
        }
        .btn-back {
            flex: 1;
            padding: 11px;
            background-color: #efebe9;
            color: #5d4037;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-back:hover {
            background-color: #d7ccc8;
        }
    </style>
</head>
<body>

    <div class="form-card">
        <div class="form-header">
            <h2>Perbarui Data Menu</h2>
            <p>Perbarui rincian informasi dan status promo menu terpilih</p>
        </div>

        <form action="" method="POST">
            <input type="hidden" value="1">

            <div class="form-group">
                <label>Kode Menu</label>
                <input type="text" class="form-control" value="CF-001" required>
            </div>

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" class="form-control" value="Espresso Single Origin Gayo" required>
            </div>

            <div class="form-group">
                <label>Kategori Sajian</label>
                <select class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Kopi" selected>Kopi</option>
                    <option value="Non-Kopi">Non-Kopi</option>
                    <option value="Makanan Ringan">Makanan Ringan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Harga Normal (Rupiah)</label>
                <input type="number" class="form-control" value="25000" min="1000" required>
            </div>

            <div class="form-group">
                <label>Status Program Promo</label>
                <select class="form-control" required>
                    <option value="Tidak">Tidak Ada Promo</option>
                    <option value="Ya" selected>Promo Aktif (Diskon 10%)</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-update">Perbarui Menu</button>
                <a href="index.php" class="btn-back">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>
```
