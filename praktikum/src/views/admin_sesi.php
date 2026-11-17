<?php
// praktikum/src/views/admin_sesi.php
requireAdmin();

$pdo = getDatabaseConnection();
$pageTitle = "Kontrol Sesi Ujian & Aktivasi Soal";
require_once __DIR__ . '/layout_header.php';

// Filter tingkat soal
$tingkatFilter = isset($_GET['tingkat']) && in_array(strtoupper($_GET['tingkat']), ['XI', 'XII']) ? strtoupper($_GET['tingkat']) : 'ALL';

// Hitung total paket soal per tingkat
$countAll = (int)$pdo->query("SELECT COUNT(*) FROM soal")->fetchColumn();
$countXI = (int)$pdo->query("SELECT COUNT(*) FROM soal WHERE tingkat = 'XI'")->fetchColumn();
$countXII = (int)$pdo->query("SELECT COUNT(*) FROM soal WHERE tingkat = 'XII'")->fetchColumn();

$totalSoalAktif = (int)$pdo->query("SELECT COUNT(*) FROM soal WHERE is_active = 1")->fetchColumn();
$totalSoalNonaktif = (int)$pdo->query("SELECT COUNT(*) FROM soal WHERE is_active = 0")->fetchColumn();
$totalSiswa = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
$totalPengumpulan = (int)$pdo->query("SELECT COUNT(*) FROM pengumpulan")->fetchColumn();

// Ambil soal berdasarkan filter tingkat
if ($tingkatFilter === 'XI') {
    $stmtAllSoal = $pdo->prepare("SELECT * FROM soal WHERE tingkat = 'XI' ORDER BY sesi ASC, kategori ASC");
    $stmtAllSoal->execute();
} elseif ($tingkatFilter === 'XII') {
    $stmtAllSoal = $pdo->prepare("SELECT * FROM soal WHERE tingkat = 'XII' ORDER BY sesi ASC, kategori ASC");
    $stmtAllSoal->execute();
} else {
    $stmtAllSoal = $pdo->query("SELECT * FROM soal ORDER BY tingkat ASC, sesi ASC, kategori ASC");
}
$allSoal = $stmtAllSoal->fetchAll();
?>

<!-- Header Halaman -->
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 24px; color: var(--primary-dark);">🎛️ Kontrol Sesi Ujian &amp; Aktivasi Soal</h1>
        <p style="color: var(--text-muted); font-size: 14px;">
            Kelola saklar status aktif atau nonaktif paket soal ujian praktikum untuk Kelas XI dan Kelas XII.
        </p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <a href="index.php?page=pengumpulan" class="btn btn-primary" style="font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
            📥 Monitoring Pengumpulan Jawaban Live &rarr;
        </a>
        <a href="index.php?page=petunjuk" class="btn btn-warning" style="font-size: 12.5px;">
            ⚠️ Standar Petunjuk Lab
        </a>
    </div>
</div>

<!-- Statistik Ringkas Sesi Ujian (HD Fluid KPI Bar) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Total Paket Soal</div>
        <div class="kpi-value" style="color: var(--primary);"><?= $countAll ?></div>
        <div class="kpi-sub">Tersedia di kurikulum lab</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Soal Sedang Aktif</div>
        <div class="kpi-value" style="color: var(--success);"><?= $totalSoalAktif ?></div>
        <div class="kpi-sub" style="color: #166534; font-weight: 600;">Dapat diakses oleh siswa</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Soal Nonaktif</div>
        <div class="kpi-value" style="color: #64748b;"><?= $totalSoalNonaktif ?></div>
        <div class="kpi-sub">Terkunci / belum dimulai</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Jawaban Masuk</div>
        <div class="kpi-value" style="color: #0284c7;"><?= $totalPengumpulan ?></div>
        <div class="kpi-sub">Dari <?= $totalSiswa ?> siswa terdaftar</div>
    </div>
</div>

<!-- Filter Tab Kelas XI vs Kelas XII untuk Guru -->
<div class="filter-tabs">
    <a href="index.php?page=kontrol_sesi" class="filter-tab <?= $tingkatFilter === 'ALL' ? 'active' : '' ?>">
        🎓 Semua Paket Soal <span class="tab-count"><?= $countAll ?></span>
    </a>
    <a href="index.php?page=kontrol_sesi&tingkat=XI" class="filter-tab <?= $tingkatFilter === 'XI' ? 'active' : '' ?>">
        📘 Kelas XI (PHP &amp; C#) <span class="tab-count"><?= $countXI ?></span>
    </a>
    <a href="index.php?page=kontrol_sesi&tingkat=XII" class="filter-tab <?= $tingkatFilter === 'XII' ? 'active' : '' ?>">
        📙 Kelas XII (Laravel &amp; C#) <span class="tab-count"><?= $countXII ?></span>
    </a>
</div>

<!-- Manajemen Aktivasi Soal -->
<div class="card">
    <div class="card-header">
        <span class="card-title">
            🎛️ Daftar Paket Soal &amp; Saklar Status Ujian
            <?php if ($tingkatFilter === 'XI'): ?>
                - Khusus Tingkat XI
            <?php elseif ($tingkatFilter === 'XII'): ?>
                - Khusus Tingkat XII
            <?php endif; ?>
        </span>
        <span style="font-size: 12px; color: #64748b;">Hanya soal dengan status AKTIF yang dapat dilihat dan dikerjakan oleh siswa</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 45px;">No</th>
                        <th style="width: 100px;">Tingkat</th>
                        <th>Sesi &amp; Kategori</th>
                        <th>Judul Paket Soal</th>
                        <th>Format Jawaban</th>
                        <th style="width: 140px; text-align: center;">Status Ujian</th>
                        <th style="width: 220px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($allSoal as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if ($s['tingkat'] === 'XI'): ?>
                                    <span class="badge" style="background: #dbeafe; color: #1e40af; font-weight: 700;">📘 KELAS XI</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #ffedd5; color: #9a3412; font-weight: 700;">📙 KELAS XII</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-info"><?= htmlspecialchars($s['sesi']) ?></span>
                                <span class="badge badge-secondary"><?= htmlspecialchars($s['kategori']) ?></span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($s['judul']) ?></strong>
                            </td>
                            <td>
                                <?php if ($s['tipe_pengumpulan'] === 'github_link'): ?>
                                    <span class="badge" style="background: #ede9fe; color: #6d28d9;">🔗 GitHub Link</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #e0f2fe; color: #0369a1;">💾 Berkas SQL</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($s['is_active']): ?>
                                    <span class="badge badge-success" style="padding: 6px 12px;">● SEDANG AKTIF</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary" style="padding: 6px 12px;">○ NONAKTIF</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div class="btn-group-inline" style="justify-content: flex-end;">
                                    <a href="index.php?page=pengumpulan&filter_soal=<?= $s['id'] ?>" class="btn btn-primary" style="padding: 5px 9px; font-size: 11.5px;" title="Lihat Rekap Pengumpulan Siswa">
                                        📥 Jawaban
                                    </a>
                                    <a href="index.php?page=soal&id=<?= $s['id'] ?>" class="btn btn-secondary" target="_blank" style="padding: 5px 9px; font-size: 11.5px;">
                                        📖 Buka Soal
                                    </a>
                                    <?php if (!empty($s['file_sql_path'])): ?>
                                        <a href="index.php?page=view_sql&id=<?= $s['id'] ?>" class="btn btn-secondary" target="_blank" style="padding: 5px 8px; font-size: 11.5px; color: #92400e;">
                                            🗄️ SQL
                                        </a>
                                    <?php endif; ?>
                                    <form action="index.php?action=toggle_soal" method="POST" style="display: inline;">
                                        <input type="hidden" name="soal_id" value="<?= $s['id'] ?>">
                                        <input type="hidden" name="redirect_to" value="kontrol_sesi">
                                        <input type="hidden" name="tingkat" value="<?= htmlspecialchars($tingkatFilter) ?>">
                                        <input type="hidden" name="status" value="<?= $s['is_active'] ? 0 : 1 ?>">
                                        <?php if ($s['is_active']): ?>
                                            <button type="submit" class="btn btn-danger" style="padding: 5px 9px; font-size: 11.5px;">
                                                Nonaktifkan
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-success" style="padding: 5px 9px; font-size: 11.5px;">
                                                Aktifkan
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
