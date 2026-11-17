{{-- praktikum/xii/praktikum-2/WEB_LARAVEL/raw_html/index.blade.php --}}
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
