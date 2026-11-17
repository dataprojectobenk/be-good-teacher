<?php
// praktikum/src/views/admin_nilai.php
requireAdmin();

$pdo = getDatabaseConnection();
$pageTitle = "Buku Nilai Siswa per Kelas";
require_once __DIR__ . '/layout_header.php';

// Ambil daftar kelas unik siswa di basis data
$daftarKelas = $pdo->query("SELECT DISTINCT kelas FROM users WHERE role = 'siswa' ORDER BY kelas ASC")->fetchAll(PDO::FETCH_COLUMN);

// Hitung jumlah siswa per kelas untuk tab navigasi
$stmtCount = $pdo->query("SELECT kelas, COUNT(*) as jml FROM users WHERE role = 'siswa' GROUP BY kelas");
$countPerKelas = [];
while ($row = $stmtCount->fetch()) {
    $countPerKelas[$row['kelas']] = (int)$row['jml'];
}

// Logika Pemilihan Kelas Tunggal (Eksklusif - Tidak Ada Mode Gabungan)
$reqKelas = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';
if (empty($reqKelas) || !in_array($reqKelas, $daftarKelas, true)) {
    // Default otomatis ke kelas pertama (misal: XI-PPLG)
    $activeKelas = $daftarKelas[0] ?? 'XI-PPLG';
} else {
    $activeKelas = $reqKelas;
}

// Tingkat kelas aktif (XI atau XII)
$activeTingkat = getStudentTingkat($activeKelas);
$isXI = ($activeTingkat === 'XI');
$themeColor = $isXI ? '#1e40af' : '#9a3412';
$badgeClass = $isXI ? 'badge-primary' : 'badge-warning';
$iconKelas = $isXI ? '📘' : '📙';

// Filter soal khusus tingkat kelas aktif
$filterSoalId = isset($_GET['soal_id']) ? (int)$_GET['soal_id'] : 0;

$stmtSoalOptions = $pdo->prepare("
    SELECT * FROM soal 
    WHERE tingkat = :tingkat 
    ORDER BY sesi ASC, kategori ASC
");
$stmtSoalOptions->execute([':tingkat' => $activeTingkat]);
$soalOptions = $stmtSoalOptions->fetchAll();
$stmtSoalOptions->closeCursor();

// Validasi apakah filterSoalId cocok dengan tingkat kelas aktif
$selectedSoal = null;
if ($filterSoalId > 0) {
    foreach ($soalOptions as $s) {
        if ((int)$s['id'] === $filterSoalId) {
            $selectedSoal = $s;
            break;
        }
    }
    if (!$selectedSoal) {
        $filterSoalId = 0;
    }
}

// Ambil daftar siswa KHUSUS untuk kelas aktif
$stmtStudents = $pdo->prepare("
    SELECT id, nis, nama, kelas 
    FROM users 
    WHERE role = 'siswa' AND kelas = :kelas 
    ORDER BY nama ASC
");
$stmtStudents->execute([':kelas' => $activeKelas]);
$students = $stmtStudents->fetchAll();
$stmtStudents->closeCursor();

// Ambil pengumpulan jawaban siswa KHUSUS untuk kelas aktif
$queryPengumpulan = "
    SELECT 
        p.*,
        u.nis, u.nama, u.kelas,
        s.judul AS judul_soal, s.tingkat, s.sesi, s.kategori
    FROM pengumpulan p
    JOIN users u ON p.user_id = u.id
    JOIN soal s ON p.soal_id = s.id
    WHERE u.role = 'siswa' AND u.kelas = :kelas
";
$paramsPengumpulan = [':kelas' => $activeKelas];

if ($filterSoalId > 0) {
    $queryPengumpulan .= " AND p.soal_id = :soal_id ";
    $paramsPengumpulan[':soal_id'] = $filterSoalId;
}

$stmtPengumpulan = $pdo->prepare($queryPengumpulan);
$stmtPengumpulan->execute($paramsPengumpulan);
$rawPengumpulan = $stmtPengumpulan->fetchAll();
$stmtPengumpulan->closeCursor();

// Pemetaan pengumpulan: [user_id][soal_id] => data pengumpulan
$submissionsMap = [];
$scores = [];
foreach ($rawPengumpulan as $p) {
    $submissionsMap[$p['user_id']][$p['soal_id']] = $p;
    if ($p['nilai'] !== null) {
        $scores[] = (int)$p['nilai'];
    }
}

// Kalkulasi statistik 100% khusus kelas aktif
$totalSiswa = count($students);
$totalTerkumpul = count($rawPengumpulan);
$totalDinilai = count($scores);
$rataRataKelas = !empty($scores) ? round(array_sum($scores) / count($scores), 1) : null;
$nilaiTertinggi = !empty($scores) ? max($scores) : null;
$nilaiTerendah = !empty($scores) ? min($scores) : null;

$totalTuntasKKM = 0;
foreach ($scores as $sc) {
    if ($sc >= 75) {
        $totalTuntasKKM++;
    }
}
$persentaseTuntas = $totalDinilai > 0 ? round(($totalTuntasKKM / $totalDinilai) * 100, 1) : 0;
?>

<!-- Header Khusus Cetak Dokumen Resmi (Hanya Muncul Saat Print / PDF) -->
<div class="print-only" style="margin-bottom: 25px; border-bottom: 3px double #0f172a; padding-bottom: 12px; text-align: center;">
    <h2 style="font-size: 18px; text-transform: uppercase; margin-bottom: 3px; color: #0f172a;">
        BUKU NILAI EVALUASI PRAKTIKUM RPL — KELAS <?= htmlspecialchars($activeKelas) ?>
    </h2>
    <div style="font-size: 13px; font-weight: 600; color: #334155;">
        LABORATORIUM KOMPUTER REKAYASA PERANGKAT LUNAK — TAHUN AJARAN 2026/2027
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 15px; font-size: 12px; text-align: left;">
        <div>
            Kelas: <strong><?= htmlspecialchars($activeKelas) ?></strong> (Tingkat <?= htmlspecialchars($activeTingkat) ?>)<br>
            Jumlah Siswa: <strong><?= $totalSiswa ?> Siswa</strong>
        </div>
        <div style="text-align: right;">
            Standar KKM: <strong>75.0</strong><br>
            Paket Soal: <strong><?= htmlspecialchars($selectedSoal ? '[' . $selectedSoal['tingkat'] . '] ' . $selectedSoal['sesi'] . ' - ' . $selectedSoal['judul'] : 'Rekapitulasi Seluruh Praktikum') ?></strong>
        </div>
    </div>
</div>

<!-- Header Antarmuka Web -->
<div class="no-print" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 24px; color: var(--primary-dark);">
            <?= $iconKelas ?> Buku Nilai Siswa: Kelas <?= htmlspecialchars($activeKelas) ?>
        </h1>
        <p style="color: var(--text-muted); font-size: 14px;">
            Rekapitulasi nilai praktikum, evaluasi KKM, cetak rapor, dan ekspor CSV khusus <strong>Kelas <?= htmlspecialchars($activeKelas) ?></strong>.
        </p>
    </div>
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        <button type="button" class="btn btn-secondary" onclick="window.print()" style="font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
            🖨️ Cetak Buku Nilai
        </button>
        <a href="index.php?action=export_nilai_csv&kelas=<?= urlencode($activeKelas) ?>&soal_id=<?= $filterSoalId ?>" class="btn btn-success" style="font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
            📥 Ekspor CSV (Kelas <?= htmlspecialchars($activeKelas) ?>)
        </a>
        <a href="index.php?page=pengumpulan" class="btn btn-primary" style="font-size: 12.5px;">
            📥 Monitoring Pengumpulan Live &rarr;
        </a>
    </div>
</div>

<!-- Tab Navigasi Berganti Kelas (Eksklusif - Tanpa Mode Gabungan) -->
<div class="filter-tabs no-print" style="margin-bottom: 20px;">
    <span style="font-size: 13px; font-weight: 700; color: #475569; display: flex; align-items: center; margin-right: 6px;">
        Pilih Kelas:
    </span>
    <?php foreach ($daftarKelas as $k): 
        $isTabXI = strpos(strtoupper($k), 'XI') !== false && strpos(strtoupper($k), 'XII') === false;
        $tabIcon = $isTabXI ? '📘' : '📙';
        $isActiveTab = ($k === $activeKelas);
    ?>
        <a href="index.php?page=nilai&kelas=<?= urlencode($k) ?>" class="filter-tab <?= $isActiveTab ? 'active' : '' ?>">
            <?= $tabIcon ?> Kelas <?= htmlspecialchars($k) ?> 
            <span class="tab-count"><?= $countPerKelas[$k] ?? 0 ?> Siswa</span>
        </a>
    <?php endforeach; ?>
</div>

<!-- Bilah Filter Paket Soal Praktikum (Hanya Soal Tingkat Kelas Aktif) -->
<div class="card no-print" style="margin-bottom: 25px; padding: 14px 20px;">
    <form method="GET" action="index.php" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <input type="hidden" name="page" value="nilai">
        <input type="hidden" name="kelas" value="<?= htmlspecialchars($activeKelas) ?>">

        <div style="flex: 2; min-width: 280px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 5px;">
                Filter Paket Soal (Khusus Tingkat <?= htmlspecialchars($activeTingkat) ?>):
            </label>
            <select name="soal_id" class="form-control" style="font-size: 13px; padding: 7px 10px;" onchange="this.form.submit()">
                <option value="0" <?= $filterSoalId === 0 ? 'selected' : '' ?>>
                    -- Rekap Seluruh Soal Praktikum (Rata-rata Kelas <?= htmlspecialchars($activeKelas) ?>) --
                </option>
                <?php foreach ($soalOptions as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $filterSoalId === (int)$s['id'] ? 'selected' : '' ?>>
                        [<?= htmlspecialchars($s['tingkat']) ?>] <?= htmlspecialchars($s['sesi']) ?> - <?= htmlspecialchars($s['judul']) ?> (<?= htmlspecialchars($s['kategori']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-secondary" style="font-size: 13px; padding: 7px 14px;">
                🔍 Terapkan Filter
            </button>
            <?php if ($filterSoalId > 0): ?>
                <a href="index.php?page=nilai&kelas=<?= urlencode($activeKelas) ?>" class="btn btn-secondary" style="font-size: 13px; padding: 7px 14px; color: #b91c1c;">
                    ✕ Reset Soal
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Statistik Analitik Khusus Kelas Aktif (HD Fluid KPI Bar) -->
<div class="kpi-grid no-print">
    <div class="kpi-card" style="border-left: 4px solid <?= $themeColor ?>;">
        <div class="kpi-label">Total Siswa</div>
        <div class="kpi-value" style="color: <?= $themeColor ?>;"><?= $totalSiswa ?> Siswa</div>
        <div class="kpi-sub">Terdaftar di Kelas <?= htmlspecialchars($activeKelas) ?></div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid <?= $rataRataKelas !== null ? ($rataRataKelas >= 75 ? '#15803d' : '#b91c1c') : '#64748b' ?>;">
        <div class="kpi-label">Rata-rata Kelas</div>
        <div class="kpi-value" style="color: <?= $rataRataKelas !== null ? ($rataRataKelas >= 75 ? '#15803d' : '#b91c1c') : '#64748b' ?>;">
            <?= $rataRataKelas !== null ? $rataRataKelas : '-' ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;">/ 100</span>
        </div>
        <div class="kpi-sub">Dari <?= $totalDinilai ?> nilai masuk</div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #0284c7;">
        <div class="kpi-label">Ketuntasan KKM (>= 75)</div>
        <div class="kpi-value" style="color: #0284c7;"><?= $persentaseTuntas ?>%</div>
        <div class="kpi-sub" style="color: #166534; font-weight: 600;"><?= $totalTuntasKKM ?> Tuntas | <?= $totalDinilai - $totalTuntasKKM ?> Remedial</div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #0f172a;">
        <div class="kpi-label">Rentang Nilai (Min / Max)</div>
        <div class="kpi-value" style="color: #0f172a;">
            <span style="color: #b91c1c;"><?= $nilaiTerendah !== null ? $nilaiTerendah : '-' ?></span>
            <span style="font-size: 15px; color: #94a3b8; font-weight: 400;"> s/d </span>
            <span style="color: #15803d;"><?= $nilaiTertinggi !== null ? $nilaiTertinggi : '-' ?></span>
        </div>
        <div class="kpi-sub">Skor terendah &amp; skor tertinggi</div>
    </div>
</div>
    </div>
</div>

<!-- Tabel Utama Buku Nilai (Khusus Kelas Aktif) -->
<div class="card" style="border-top: 4px solid <?= $themeColor ?>;">
    <div class="card-header" style="background: #fafbfc;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="badge" style="background: <?= $isXI ? '#dbeafe' : '#ffedd5' ?>; color: <?= $themeColor ?>; font-size: 12.5px; padding: 6px 12px; font-weight: 800;">
                <?= $iconKelas ?> KELAS <?= htmlspecialchars($activeKelas) ?>
            </span>
            <div>
                <span class="card-title" style="font-size: 15.5px;">
                    Daftar Nilai Siswa
                    <?php if ($selectedSoal): ?>
                        - <?= htmlspecialchars($selectedSoal['sesi'] . ' (' . $selectedSoal['judul'] . ')') ?>
                    <?php endif; ?>
                </span>
                <div style="font-size: 12px; color: #64748b;">
                    Standar KKM: <strong>75.0</strong> | Total <?= $totalSiswa ?> Siswa
                </div>
            </div>
        </div>
        <div class="no-print" style="display: flex; gap: 8px;">
            <a href="index.php?action=export_nilai_csv&kelas=<?= urlencode($activeKelas) ?>&soal_id=<?= $filterSoalId ?>" class="btn btn-secondary" style="font-size: 11.5px; padding: 5px 12px;">
                📥 Unduh CSV
            </a>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th style="width: 110px;">NIS</th>
                        <th>Nama Lengkap Siswa</th>
                        <th style="width: 100px;">Kelas</th>

                        <?php if ($filterSoalId > 0): ?>
                            <th>Waktu Pengumpulan</th>
                            <th style="text-align: center; width: 110px;">Nilai (0–100)</th>
                            <th style="text-align: center; width: 120px;">Status KKM</th>
                            <th style="width: 280px;">Catatan Penguji</th>
                        <?php else: ?>
                            <th style="text-align: center; width: 130px;">Tugas Dikumpulkan</th>
                            <th style="text-align: center; width: 120px;">Rata-rata Nilai</th>
                            <th style="text-align: center; width: 130px;">Predikat Kelulusan</th>
                            <th style="width: 250px;">Ringkasan Status</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 35px 20px; color: var(--text-muted);">
                                Belum ada siswa terdaftar pada kelas <?= htmlspecialchars($activeKelas) ?>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $no = 1; 
                        foreach ($students as $siswa): 
                            $userId = $siswa['id'];

                            if ($filterSoalId > 0):
                                $sub = $submissionsMap[$userId][$filterSoalId] ?? null;
                                $hasSub = !empty($sub);
                                $isGraded = $hasSub && $sub['nilai'] !== null;
                                $score = $isGraded ? (int)$sub['nilai'] : null;
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><code><?= htmlspecialchars($siswa['nis']) ?></code></td>
                                    <td><strong><?= htmlspecialchars($siswa['nama']) ?></strong></td>
                                    <td><span class="badge badge-secondary"><?= htmlspecialchars($siswa['kelas']) ?></span></td>
                                    <td>
                                        <?php if ($hasSub): ?>
                                            <span style="font-size: 12px; color: #334155;">
                                                <?= htmlspecialchars($sub['waktu_kirim']) ?>
                                            </span>
                                            <br>
                                            <a href="src/uploads/<?= htmlspecialchars($sub['jawaban_konten']) ?>" target="_blank" download style="font-size: 11px; color: var(--primary);">
                                                💾 <?= htmlspecialchars(basename($sub['jawaban_konten'])) ?>
                                            </a>
                                        <?php else: ?>
                                            <span style="font-size: 12px; color: #94a3b8; font-style: italic;">
                                                Belum mengumpulkan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($isGraded): ?>
                                            <span style="font-size: 18px; font-weight: 800; color: <?= $score >= 75 ? '#15803d' : '#b91c1c' ?>;">
                                                <?= $score ?>
                                            </span>
                                        <?php elseif ($hasSub): ?>
                                            <span class="badge badge-warning" style="font-size: 10.5px;">⏳ Menunggu</span>
                                        <?php else: ?>
                                            <span style="color: #cbd5e1; font-weight: 700;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($isGraded): ?>
                                            <?php if ($score >= 75): ?>
                                                <span class="badge badge-success" style="padding: 4px 8px; font-size: 10.5px;">✅ TUNTAS</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger" style="padding: 4px 8px; font-size: 10.5px;">⚠️ REMEDIAL</span>
                                            <?php endif; ?>
                                        <?php elseif ($hasSub): ?>
                                            <span class="badge badge-secondary" style="font-size: 10.5px;">Belum Dinilai</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger" style="font-size: 10px;">Belum Kirim</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($sub['feedback_guru'])): ?>
                                            <div style="background: #f8fafc; border-left: 3px solid #3b82f6; padding: 5px 8px; border-radius: 4px; font-size: 11.5px; color: #1e293b; line-height: 1.4;">
                                                <?= htmlspecialchars($sub['feedback_guru']) ?>
                                            </div>
                                        <?php elseif ($isGraded): ?>
                                            <span style="color: #94a3b8; font-size: 11px; font-style: italic;">-</span>
                                        <?php elseif ($hasSub): ?>
                                            <span style="color: #94a3b8; font-size: 11px; font-style: italic;">Menunggu penilaian</span>
                                        <?php else: ?>
                                            <span style="color: #cbd5e1; font-size: 11px;">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php 
                            else: 
                                // Tampilan Rekap Seluruh Soal Praktikum (Rata-rata Siswa)
                                $userSubs = $submissionsMap[$userId] ?? [];
                                $userScores = [];
                                foreach ($userSubs as $us) {
                                    if ($us['nilai'] !== null) {
                                        $userScores[] = (int)$us['nilai'];
                                    }
                                }
                                $userAvg = !empty($userScores) ? round(array_sum($userScores) / count($userScores), 1) : null;
                                $userTuntas = 0;
                                foreach ($userScores as $sc) {
                                    if ($sc >= 75) $userTuntas++;
                                }

                                $pred = '-';
                                $predBadge = '#64748b';
                                if ($userAvg !== null) {
                                    if ($userAvg >= 90) { $pred = 'A (Sangat Baik)'; $predBadge = '#15803d'; }
                                    elseif ($userAvg >= 80) { $pred = 'B (Baik)'; $predBadge = '#0369a1'; }
                                    elseif ($userAvg >= 75) { $pred = 'C (Tuntas)'; $predBadge = '#d97706'; }
                                    else { $pred = 'D (Remedial)'; $predBadge = '#b91c1c'; }
                                }
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><code><?= htmlspecialchars($siswa['nis']) ?></code></td>
                                    <td><strong><?= htmlspecialchars($siswa['nama']) ?></strong></td>
                                    <td><span class="badge badge-secondary"><?= htmlspecialchars($siswa['kelas']) ?></span></td>
                                    <td style="text-align: center;">
                                        <span class="badge badge-info" style="font-size: 11.5px; padding: 4px 8px;">
                                            <?= count($userSubs) ?> Tugas
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($userAvg !== null): ?>
                                            <span style="font-size: 17px; font-weight: 800; color: <?= $userAvg >= 75 ? '#15803d' : '#b91c1c' ?>;">
                                                <?= $userAvg ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #94a3b8; font-style: italic;">Belum Dinilai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($userAvg !== null): ?>
                                            <span class="badge" style="background: <?= $predBadge ?>; color: #ffffff; padding: 4px 8px; font-size: 11px;">
                                                <?= htmlspecialchars($pred) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary" style="font-size: 10.5px;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-size: 11.5px; color: #475569;">
                                            <?php if (!empty($userScores)): ?>
                                                <span style="color: #166534; font-weight: 600;">✅ <?= $userTuntas ?> Tuntas</span>
                                                <?php if (count($userScores) - $userTuntas > 0): ?>
                                                    | <span style="color: #b91c1c; font-weight: 600;">⚠️ <?= count($userScores) - $userTuntas ?> Remedial</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span style="color: #94a3b8; font-style: italic;">Belum ada nilai yang dimasukkan</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tanda Tangan Cetak Lembar Buku Nilai Khusus Kelas Aktif -->
<div class="print-only" style="margin-top: 40px; display: flex; justify-content: space-between; page-break-inside: avoid;">
    <div style="text-align: center; width: 250px;">
        <p style="font-size: 11.5px; margin-bottom: 55px;">Mengetahui,<br>Ketua Program Keahlian RPL</p>
        <p style="font-size: 11.5px; font-weight: 700; text-decoration: underline;">Kepala Program RPL</p>
        <p style="font-size: 10.5px; color: #475569;">NIP. 19850101 201001 1 001</p>
    </div>
    <div style="text-align: center; width: 250px;">
        <p style="font-size: 11.5px; margin-bottom: 55px;">Disahkan oleh,<br>Wali Kelas / Guru Penguji <?= htmlspecialchars($activeKelas) ?></p>
        <p style="font-size: 11.5px; font-weight: 700; text-decoration: underline;"><?= htmlspecialchars($user['nama']) ?></p>
        <p style="font-size: 10.5px; color: #475569;">Guru Pembimbing Ujian</p>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
