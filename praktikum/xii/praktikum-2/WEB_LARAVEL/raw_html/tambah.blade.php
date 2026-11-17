{{-- praktikum/xii/praktikum-2/WEB_LARAVEL/raw_html/tambah.blade.php --}}
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
