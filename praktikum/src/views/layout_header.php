<?php
// praktikum/src/views/layout_header.php
$flash = getFlashMessage();
$user = currentUser();
$clientIp = getClientIP();
$clientHost = getClientHostname($clientIp);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Lab Classroom Praktikum RPL</title>
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #172554;
            --primary-light: #3b82f6;
            --accent: #d97706;
            --success: #15803d;
            --danger: #b91c1c;
            --warning: #b45309;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
        }

        .navbar {
            background-color: var(--primary);
            color: #ffffff;
            padding: 9px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #ffffff;
        }

        .nav-brand-title {
            font-size: 16.5px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .nav-brand-subtitle {
            font-size: 10.5px;
            color: #bfdbfe;
            display: block;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            color: #ffffff;
            text-decoration: none;
            font-size: 12.5px;
            font-weight: 600;
            padding: 5px 11px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
        }

        .nav-link.active {
            background-color: rgba(255,255,255,0.25);
            box-shadow: inset 0 -2px 0 #93c5fd;
        }

        .nav-link-warning {
            background-color: var(--accent);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link-warning:hover {
            background-color: #b45309;
        }

        /* Tab Filter Menu */
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            border-bottom: 2px solid var(--border);
            padding-bottom: 12px;
        }

        .filter-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: var(--text-muted);
            background: #ffffff;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }

        .filter-tab:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        .filter-tab.active {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 2px 6px rgba(30, 58, 138, 0.25);
        }

        .filter-tab.active .tab-count {
            background: rgba(255,255,255,0.25);
            color: #ffffff;
        }

        .tab-count {
            background: #e2e8f0;
            color: #475569;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 12px;
            font-weight: 700;
        }

        .user-pill {
            background-color: rgba(255,255,255,0.12);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .user-pill strong {
            color: #93c5fd;
        }

        /* HD Fluid Container System (1366px - 1920px) */
        .main-container {
            max-width: 1440px;
            width: 95%;
            margin: 18px auto;
            padding: 0 16px;
            flex: 1;
        }

        @media (min-width: 1600px) {
            .main-container {
                max-width: 1560px;
                width: 96%;
            }
        }

        /* Workspace Grid Split Layout (Soal di Kiri, Sidebar Aksi/Upload di Kanan) */
        .workspace-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 22px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .workspace-layout {
                grid-template-columns: 1fr;
            }
        }

        .sticky-panel {
            position: sticky;
            top: 68px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* Modal Side-by-Side Split View */
        .modal-split-grid {
            display: grid;
            grid-template-columns: 1.45fr 1fr;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 920px) {
            .modal-split-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Horizontal KPI Toolbar */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .kpi-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 14px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .kpi-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-main);
            margin-top: 3px;
            line-height: 1.2;
        }

        .kpi-sub {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 4px;
        }

        .alert {
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .alert-info {
            background-color: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
        }

        .card {
            background: var(--card-bg);
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fafbfc;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        .card-body {
            padding: 22px;
        }

        .btn {
            display: inline-block;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-success {
            background-color: var(--success);
            color: #ffffff;
        }

        .btn-success:hover {
            background-color: #166534;
        }

        .btn-danger {
            background-color: var(--danger);
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #991b1b;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 12px;
            text-transform: uppercase;
        }

        .badge-primary { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-secondary { background: #f1f5f9; color: #475569; }
        .badge-purple { background: #f3e8ff; color: #6b21a8; }
        
        .badge-kkm-pass {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-kkm-fail {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-group-inline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        th {
            background-color: #f1f5f9;
            padding: 12px 14px;
            font-weight: 700;
            color: #334155;
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .device-pill {
            font-size: 11px;
            background: #f1f5f9;
            color: #475569;
            padding: 3px 6px;
            border-radius: 4px;
            font-family: monospace;
            display: inline-block;
        }

        .device-alert {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            margin-top: 4px;
        }

        /* Styling Lembar Cetak Dokumen Nilai (Print-friendly) */
        @media print {
            .navbar, footer, .no-print, .btn, .filter-tabs, .alert, #toastContainer, #previewModal {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .card {
                border: 1px solid #94a3b8 !important;
                box-shadow: none !important;
                break-inside: avoid;
            }
            .main-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            table {
                font-size: 11px !important;
            }
            th, td {
                padding: 6px 8px !important;
            }
            .print-only {
                display: block !important;
            }
        }

        .print-only {
            display: none;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="nav-brand">
            <span style="font-size: 22px;">💻</span>
            <div>
                <span class="nav-brand-title">Lab Classroom RPL</span>
                <span class="nav-brand-subtitle">Portal Ujian Praktikum Jaringan Lokal</span>
            </div>
        </a>

        <div class="nav-menu">
            <a href="index.php?page=petunjuk" class="nav-link nav-link-warning">
                ⚠️ Petunjuk Bersihkan Akun Lab
            </a>

            <?php if ($user): ?>
                <?php 
                    $currPage = $_GET['page'] ?? '';
                    $currTingkat = $_GET['tingkat'] ?? '';
                ?>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="index.php?page=kontrol_sesi" class="nav-link <?= (in_array($currPage, ['kontrol_sesi', 'dashboard', '']) && $currPage !== 'pengumpulan' && $currPage !== 'nilai') ? 'active' : '' ?>">
                        🎛️ Sesi Ujian
                    </a>
                    <a href="index.php?page=pengumpulan" class="nav-link <?= ($currPage === 'pengumpulan') ? 'active' : '' ?>" style="color: #93c5fd; font-weight: 700;">
                        📥 Pengumpulan Jawaban
                    </a>
                    <a href="index.php?page=nilai" class="nav-link <?= ($currPage === 'nilai') ? 'active' : '' ?>" style="color: #86efac; font-weight: 700;">
                        📊 Rekap Nilai
                    </a>
                <?php else: ?>
                    <a href="index.php?page=dashboard" class="nav-link <?= ($currPage === 'dashboard') ? 'active' : '' ?>">
                        📝 Lembar Praktikum
                    </a>
                    <a href="index.php?page=nilai" class="nav-link <?= ($currPage === 'nilai') ? 'active' : '' ?>" style="color: #86efac; font-weight: 700;">
                        📊 Nilai Saya
                    </a>
                <?php endif; ?>

                <div class="user-pill">
                    <span><?= htmlspecialchars($user['nama']) ?> (<strong><?= htmlspecialchars($user['role'] === 'admin' ? 'Guru' : $user['kelas']) ?></strong>)</span>
                </div>
                <a href="index.php?page=logout" class="nav-link" style="color: #fca5a5;">Keluar</a>
            <?php else: ?>
                <a href="index.php?page=login" class="nav-link">Masuk</a>
                <a href="index.php?page=register" class="nav-link" style="background-color: rgba(255,255,255,0.2);">Daftar Siswa</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="main-container">
        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                <span><?= htmlspecialchars($flash['message']) ?></span>
            </div>
        <?php endif; ?>
