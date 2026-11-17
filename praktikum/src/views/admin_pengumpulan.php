<?php
// praktikum/src/views/admin_pengumpulan.php
requireAdmin();

$pdo = getDatabaseConnection();
$pageTitle = "Monitoring Pengumpulan Jawaban Lab";
require_once __DIR__ . '/layout_header.php';

// Filter tingkat soal
$tingkatFilter = isset($_GET['tingkat']) && in_array(strtoupper($_GET['tingkat']), ['XI', 'XII']) ? strtoupper($_GET['tingkat']) : 'ALL';

// Filter soal jika dipilih
$filterSoalId = isset($_GET['filter_soal']) ? (int)$_GET['filter_soal'] : 0;

// Seluruh soal untuk dropdown filter jawaban
$allSoalOptions = $pdo->query("SELECT * FROM soal ORDER BY tingkat ASC, sesi ASC, kategori ASC")->fetchAll();

// Ambil statistik
$totalSiswa = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
$totalSoalAktif = (int)$pdo->query("SELECT COUNT(*) FROM soal WHERE is_active = 1")->fetchColumn();
$totalPengumpulan = (int)$pdo->query("SELECT COUNT(*) FROM pengumpulan")->fetchColumn();

// Query rekap pengumpulan dengan relasi ke user dan soal
$queryRekap = "
    SELECT 
        p.*,
        u.nis, u.nama, u.kelas,
        s.judul AS judul_soal, s.tipe_pengumpulan, s.sesi, s.kategori, s.tingkat
    FROM pengumpulan p
    JOIN users u ON p.user_id = u.id
    JOIN soal s ON p.soal_id = s.id
";
$paramsRekap = [];

if ($filterSoalId > 0) {
    $queryRekap .= " WHERE p.soal_id = :filter_soal ";
    $paramsRekap[':filter_soal'] = $filterSoalId;
}

if ($tingkatFilter === 'XI') {
    $queryRekap .= ($filterSoalId > 0 ? " AND " : " WHERE ") . " s.tingkat = 'XI' ";
} elseif ($tingkatFilter === 'XII') {
    $queryRekap .= ($filterSoalId > 0 ? " AND " : " WHERE ") . " s.tingkat = 'XII' ";
}

$queryRekap .= " ORDER BY p.id DESC ";
$stmtRekap = $pdo->prepare($queryRekap);
$stmtRekap->execute($paramsRekap);
$rekapRows = $stmtRekap->fetchAll();
$stmtRekap->closeCursor();

// Deteksi duplikasi telemetri perangkat
$duplicateMap = detectDuplicateDevices($pdo, $filterSoalId > 0 ? $filterSoalId : null);

// Cari ID pengumpulan tertinggi saat ini untuk inisialisasi polling
$maxRowId = 0;
foreach ($rekapRows as $r) {
    if ((int)$r['id'] > $maxRowId) {
        $maxRowId = (int)$r['id'];
    }
}
?>

<style>
/* Real-time Indicator Pulse */
.live-pulse {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #16a34a;
    box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
    animation: livePulseAnim 1.8s infinite;
}
@keyframes livePulseAnim {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(22, 163, 74, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
}

/* Animasi highlight lembut saat pengumpulan baru tiba */
@keyframes glowHighlight {
    0% { background-color: #bbf7d0; }
    50% { background-color: #dcfce7; }
    100% { background-color: transparent; }
}
.highlight-new-row {
    animation: glowHighlight 4s ease-out forwards;
}

/* Toast Container & Notification Card */
.toast-card {
    background: #ffffff;
    border-left: 5px solid #16a34a;
    border-radius: 8px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    padding: 12px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    pointer-events: auto;
    animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transition: opacity 0.3s ease, transform 0.3s ease;
    border-top: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}
@keyframes toastSlideIn {
    from { opacity: 0; transform: translateX(100%); }
    to { opacity: 1; transform: translateX(0); }
}
.toast-hiding {
    opacity: 0;
    transform: translateX(100%);
}

/* Modal Overlay & Dialog */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal-dialog {
    background: #ffffff;
    border-radius: 12px;
    max-width: 1300px;
    width: 95vw;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    animation: modalPopIn 0.25s ease-out;
}
@keyframes modalPopIn {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}
.modal-header {
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
}
.modal-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
}
.modal-subtitle {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}
.modal-close {
    background: transparent;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #64748b;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 6px;
}
.modal-close:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.modal-body {
    padding: 20px 22px;
    overflow-y: auto;
    flex: 1;
}
.modal-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 12.5px;
}
.meta-label {
    display: block;
    color: #64748b;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
}
.meta-value {
    color: #1e293b;
    font-weight: 600;
}
.code-viewer-container {
    border: 1px solid #334155;
    border-radius: 8px;
    background: #0f172a;
    overflow: hidden;
}
.code-viewer {
    margin: 0;
    padding: 16px;
    font-family: "Cascadia Code", Consolas, Monaco, "Courier New", monospace;
    font-size: 12.5px;
    line-height: 1.6;
    color: #f8fafc;
    background: transparent;
    max-height: 320px;
    overflow-y: auto;
    white-space: pre;
    tab-size: 4;
}
.modal-grading-section {
    margin-top: 20px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 16px 18px;
}
.btn-preset {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    padding: 4px 9px;
    border-radius: 4px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    color: #1e3a8a;
}
.btn-preset:hover {
    background: #1e3a8a;
    color: #ffffff;
    border-color: #1e3a8a;
}
.btn-preset-danger {
    color: #b91c1c;
    border-color: #fecaca;
}
.btn-preset-danger:hover {
    background: #b91c1c;
    color: #ffffff;
    border-color: #b91c1c;
}
.modal-footer {
    padding: 12px 22px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    background: #f8fafc;
}
</style>

<!-- Header Halaman -->
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 24px; color: var(--primary-dark);">📥 Rekapitulasi Pengumpulan Jawaban</h1>
        <p style="color: var(--text-muted); font-size: 14px;">
            Pemantauan langsung pengumpulan jawaban siswa di laboratorium, audit telemetri perangkat, pratinjau naskah, dan penilaian instan.
        </p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <!-- Badge Status Auto-Update -->
        <span id="liveStatusBadge" class="badge" style="background: #dcfce7; color: #15803d; font-size: 12px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #bbf7d0;">
            <span class="live-pulse"></span> Auto-Update: Aktif (3.5s)
        </span>

        <!-- Saklar Mute/Unmute Suara -->
        <button id="btnSoundToggle" type="button" class="btn" onclick="toggleSound()" style="font-size: 12px; padding: 6px 12px; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; display: inline-flex; align-items: center; gap: 6px;">
            <span id="soundIcon">🔊</span> <span id="soundText">Suara: ON</span>
        </button>

        <a href="index.php?page=kontrol_sesi" class="btn btn-secondary" style="font-size: 12.5px;">
            🎛️ Kontrol Sesi Ujian
        </a>
        <a href="index.php?page=nilai" class="btn btn-primary" style="font-size: 12.5px;">
            📊 Rekap Nilai Siswa &rarr;
        </a>
    </div>
</div>

<!-- Statistik Ringkas Monitoring (HD Fluid KPI Bar) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Jawaban Terkumpul</div>
        <div id="statTotalPengumpulan" class="kpi-value" style="color: #0284c7;"><?= $totalPengumpulan ?></div>
        <div class="kpi-sub">Total naskah siswa masuk</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Siswa Terdaftar</div>
        <div id="statTotalSiswa" class="kpi-value" style="color: var(--primary);"><?= $totalSiswa ?></div>
        <div class="kpi-sub">Akun siswa aktif di lab</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Soal Sedang Aktif</div>
        <div id="statTotalSoalAktif" class="kpi-value" style="color: var(--success);"><?= $totalSoalAktif ?></div>
        <div class="kpi-sub">Sesi ujian berlangsung</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Deteksi PC Duplikat</div>
        <div id="statTotalAlerts" class="kpi-value" style="color: <?= count($duplicateMap) > 0 ? '#b91c1c' : '#15803d' ?>;">
            <?= count($duplicateMap) ?> Alert
        </div>
        <div class="kpi-sub">Indikasi perangkat sama</div>
    </div>
</div>

<!-- Rekapitulasi Jawaban & Audit Integritas Perangkat -->
<div class="card">
    <div class="card-header">
        <div>
            <span class="card-title">📊 Tabel Pengumpulan Jawaban Siswa &amp; Audit Integritas Komputer Lab</span>
            <div style="font-size: 12px; color: #64748b; margin-top: 3px;">
                Merekam IP, Nama Komputer Lab, pratinjau naskah SQL instan, dan penilaian cepat side-by-side.
            </div>
        </div>
        <div>
            <form method="GET" action="index.php" style="display: flex; gap: 8px;">
                <input type="hidden" name="page" value="pengumpulan">
                <?php if ($tingkatFilter !== 'ALL'): ?>
                    <input type="hidden" name="tingkat" value="<?= htmlspecialchars($tingkatFilter) ?>">
                <?php endif; ?>
                <select name="filter_soal" class="form-control" style="font-size: 12px; padding: 6px 10px;" onchange="this.form.submit()">
                    <option value="0">-- Semua Soal Praktikum --</option>
                    <optgroup label="📘 KELAS XI (PHP & C#)">
                        <?php foreach ($allSoalOptions as $s): if ($s['tingkat'] === 'XI'): ?>
                            <option value="<?= $s['id'] ?>" <?= $filterSoalId === (int)$s['id'] ? 'selected' : '' ?>>
                                [XI] <?= htmlspecialchars($s['sesi']) ?> - <?= htmlspecialchars($s['judul']) ?>
                            </option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                    <optgroup label="📙 KELAS XII (Laravel & C#)">
                        <?php foreach ($allSoalOptions as $s): if ($s['tingkat'] === 'XII'): ?>
                            <option value="<?= $s['id'] ?>" <?= $filterSoalId === (int)$s['id'] ? 'selected' : '' ?>>
                                [XII] <?= htmlspecialchars($s['sesi']) ?> - <?= htmlspecialchars($s['judul']) ?>
                            </option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div style="overflow-x: auto;">
            <table id="submissionsTable">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Identitas Siswa</th>
                        <th>Soal</th>
                        <th>Jawaban Terkirim</th>
                        <th>Telemetri Komputer Lab (IP & Host)</th>
                        <th>Waktu Kirim</th>
                        <th style="width: 220px;">Nilai & Feedback</th>
                    </tr>
                </thead>
                <tbody id="submissionsBody">
                    <?php if (empty($rekapRows)): ?>
                        <tr id="emptyRow">
                            <td colspan="7" style="text-align: center; padding: 35px 20px; color: var(--text-muted); font-size: 13.5px;">
                                Belum ada siswa yang mengumpulkan jawaban pada kategori ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $no = 1; 
                        foreach ($rekapRows as $r): 
                            $checkKey = $r['client_ip'] . '_soal_' . $r['soal_id'];
                            $isDuplicate = isset($duplicateMap[$checkKey]) && $duplicateMap[$checkKey] > 1;
                        ?>
                            <tr id="row-sub-<?= $r['id'] ?>" data-id="<?= $r['id'] ?>" style="<?= $isDuplicate ? 'background-color: #fff1f2;' : '' ?>">
                                <td><span class="row-num"><?= $no++ ?></span></td>
                                <td>
                                    <strong><?= htmlspecialchars($r['nama']) ?></strong><br>
                                    <span style="font-size: 11px; color: #64748b;">NIS: <?= htmlspecialchars($r['nis']) ?> (<?= htmlspecialchars($r['kelas']) ?>)</span>
                                </td>
                                <td>
                                    <?php if ($r['tingkat'] === 'XI'): ?>
                                        <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 10px; padding: 2px 5px;">📘 XI</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #ffedd5; color: #9a3412; font-size: 10px; padding: 2px 5px;">📙 XII</span>
                                    <?php endif; ?>
                                    <span style="font-size: 12px; font-weight: 600; color: #334155; margin-left: 3px;"><?= htmlspecialchars($r['judul_soal']) ?></span>
                                </td>
                                <td>
                                    <div class="btn-group-inline" style="margin-bottom: 3px;">
                                        <button type="button" class="btn btn-primary" onclick="openPreviewModal(<?= $r['id'] ?>)" style="padding: 4px 9px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 4px;">
                                            👁️ Pratinjau &amp; Nilai
                                        </button>

                                        <?php if ($r['tipe_jawaban'] === 'github_link'): ?>
                                            <a href="<?= htmlspecialchars($r['jawaban_konten']) ?>" target="_blank" class="btn btn-secondary" style="padding: 4px 8px; font-size: 11px; text-decoration: none;">
                                                🔗 GitHub &rarr;
                                            </a>
                                        <?php else: ?>
                                            <a href="src/uploads/<?= htmlspecialchars($r['jawaban_konten']) ?>" target="_blank" download class="btn btn-secondary" style="padding: 4px 8px; font-size: 11px; text-decoration: none;">
                                                💾 <?= htmlspecialchars(basename($r['jawaban_konten'])) ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($r['catatan_siswa'])): ?>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                            "<?= htmlspecialchars($r['catatan_siswa']) ?>"
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="device-pill">IP: <?= htmlspecialchars($r['client_ip']) ?> | Host: <?= htmlspecialchars($r['client_hostname']) ?></div>

                                    <?php if ($isDuplicate): ?>
                                        <div class="device-alert">
                                            ⚠️ PERINGATAN: Perangkat/IP sama dipakai oleh <?= $duplicateMap[$checkKey] ?> siswa! (Indikasi titip kirim)
                                        </div>
                                    <?php else: ?>
                                        <div style="font-size: 11px; color: #166534; margin-top: 3px;">
                                            ✅ Perangkat Terverifikasi Unik
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 12px; color: #64748b;">
                                    <?= htmlspecialchars($r['waktu_kirim']) ?>
                                </td>
                                <td>
                                    <!-- Form Input Nilai Cepat Instan via AJAX -->
                                    <form id="inline-form-<?= $r['id'] ?>" onsubmit="saveInlineGrade(event, <?= $r['id'] ?>)" style="display: flex; flex-direction: column; gap: 4px;">
                                        <input type="hidden" name="pengumpulan_id" value="<?= $r['id'] ?>">
                                        <div style="display: flex; gap: 4px;">
                                            <input type="number" id="inline-nilai-<?= $r['id'] ?>" name="nilai" value="<?= $r['nilai'] !== null ? htmlspecialchars($r['nilai']) : '' ?>" placeholder="0-100" min="0" max="100" class="form-control" style="width: 70px; padding: 4px 6px; font-size: 12px;" required>
                                            <button type="submit" id="inline-btn-<?= $r['id'] ?>" class="btn btn-primary" style="padding: 4px 8px; font-size: 11px;">Simpan</button>
                                        </div>
                                        <input type="text" id="inline-feedback-<?= $r['id'] ?>" name="feedback" value="<?= htmlspecialchars($r['feedback_guru'] ?? '') ?>" placeholder="Catatan penguji" class="form-control" style="padding: 3px 6px; font-size: 11px;">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- KOMPONEN: TOAST CONTAINER (NOTIFIKASI REAL-TIME)        -->
<!-- ======================================================== -->
<div id="toastContainer" style="position: fixed; top: 75px; right: 24px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; pointer-events: none; max-width: 380px; width: 100%;"></div>

<!-- ======================================================== -->
<!-- KOMPONEN: MODAL PRATINJAU BERKAS & PENILAIAN TERPADU    -->
<!-- ======================================================== -->
<div id="previewModal" class="modal-overlay" style="display: none;" onclick="handleModalOverlayClick(event)">
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="modalStudentName">Pratinjau Jawaban Siswa</h3>
                <div class="modal-subtitle" id="modalStudentMeta">NIS: - | Kelas: -</div>
            </div>
            <button type="button" class="modal-close" onclick="closePreviewModal()">&times;</button>
        </div>
        
        <div class="modal-body" style="padding: 18px 22px; max-height: calc(90vh - 120px); overflow-y: auto;">
            <div class="modal-split-grid">
                
                <!-- KOLOM KIRI: PRATINJAU NASKAH KODE SQL / LINK GITHUB (65%) -->
                <div style="min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 13px; color: #1e293b;" id="modalFileLabel">📄 Isi Berkas SQL:</strong>
                            <span id="modalFileSize" class="badge badge-info" style="font-size: 11px;">-</span>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="btn btn-secondary" onclick="copyModalCode()" id="btnCopyCode" style="padding: 4px 10px; font-size: 11.5px;">
                                📋 Salin Kode SQL
                            </button>
                            <a id="modalDownloadBtn" href="#" download class="btn btn-secondary" style="padding: 4px 10px; font-size: 11.5px; text-decoration: none;">
                                💾 Unduh Berkas
                            </a>
                        </div>
                    </div>

                    <!-- Code container HD Viewport -->
                    <div id="modalCodeWrapper" class="code-viewer-container" style="border-radius: 8px; overflow: hidden; border: 1px solid #1e293b;">
                        <pre class="code-viewer" style="height: calc(88vh - 240px); min-height: 440px; margin: 0; background: #0f172a; color: #f8fafc; padding: 14px 16px; overflow: auto; font-family: monospace; font-size: 12.5px; line-height: 1.55;"><code id="modalCodeContent">Memuat naskah jawaban...</code></pre>
                    </div>
                    
                    <!-- Link container jika jawaban GitHub -->
                    <div id="modalGithubWrapper" style="display: none; padding: 40px 20px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 8px; text-align: center;">
                        <div style="font-size: 32px; margin-bottom: 10px;">🐙</div>
                        <p style="margin-bottom: 12px; color: #475569; font-size: 14px;">Jawaban dikumpulkan dalam bentuk repositori GitHub:</p>
                        <a id="modalGithubLink" href="#" target="_blank" class="btn btn-primary" style="font-size: 13.5px; padding: 8px 20px; text-decoration: none;">
                            🔗 Buka Repositori GitHub Siswa &rarr;
                        </a>
                    </div>
                </div>

                <!-- KOLOM KANAN: METADATA, AUDIT PERANGKAT, & FORM PENILAIAN SIDE-BY-SIDE (35%) -->
                <div style="min-width: 0; display: flex; flex-direction: column; gap: 14px;">
                    
                    <!-- Info Box Meta Soal & Telemetri -->
                    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 14px; font-size: 12px; line-height: 1.6;">
                        <div style="margin-bottom: 8px;">
                            <span style="color: #64748b; font-weight: 700; display: block; font-size: 10.5px; text-transform: uppercase;">Paket Soal:</span>
                            <strong style="color: #0f172a; font-size: 13px;" id="modalSoalTitle">-</strong>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <span style="color: #64748b; font-weight: 700; display: block; font-size: 10.5px; text-transform: uppercase;">Waktu Kirim:</span>
                            <span style="color: #334155;" id="modalWaktuKirim">-</span>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <span style="color: #64748b; font-weight: 700; display: block; font-size: 10.5px; text-transform: uppercase;">Audit Integritas Komputer Lab:</span>
                            <div id="modalTelemetri" style="margin-top: 2px;">-</div>
                        </div>
                        <div>
                            <span style="color: #64748b; font-weight: 700; display: block; font-size: 10.5px; text-transform: uppercase;">Catatan Tambahan Siswa:</span>
                            <div id="modalCatatanSiswa" style="color: #334155; font-style: italic; background: #ffffff; border: 1px solid #e2e8f0; padding: 6px 10px; border-radius: 4px; margin-top: 3px;">
                                <em>Tidak ada catatan</em>
                            </div>
                        </div>
                    </div>

                    <!-- Section Form Penilaian Cepat -->
                    <div class="modal-grading-section" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 16px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                        <h4 style="font-size: 13.5px; color: #0f172a; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; font-weight: 700;">
                            <span>🎯</span> Form Penilaian Cepat
                        </h4>

                        <!-- Quick presets -->
                        <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 12px; flex-wrap: wrap;">
                            <span style="font-size: 11px; color: #64748b; font-weight: 700;">Preset:</span>
                            <button type="button" class="btn-preset" onclick="setPresetGrade(100)">100</button>
                            <button type="button" class="btn-preset" onclick="setPresetGrade(90)">90</button>
                            <button type="button" class="btn-preset" onclick="setPresetGrade(85)">85</button>
                            <button type="button" class="btn-preset" onclick="setPresetGrade(80)">80</button>
                            <button type="button" class="btn-preset" onclick="setPresetGrade(75)">75 (KKM)</button>
                            <button type="button" class="btn-preset btn-preset-danger" onclick="setPresetGrade(60)">Remedial (60)</button>
                        </div>

                        <form id="modalGradeForm" onsubmit="saveModalGrade(event)">
                            <input type="hidden" id="modalPengumpulanId" value="0">
                            <div style="margin-bottom: 12px;">
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #1e3a8a; margin-bottom: 4px;">
                                    Nilai Ujian (0–100, KKM: 75):
                                </label>
                                <input type="number" id="modalInputNilai" min="0" max="100" class="form-control" placeholder="0-100" required style="font-size: 18px; font-weight: 800; color: #1e3a8a; text-align: center; padding: 8px;">
                            </div>
                            <div style="margin-bottom: 14px;">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 4px;">
                                    Umpan Balik / Catatan Penguji:
                                </label>
                                <textarea id="modalInputFeedback" rows="3" class="form-control" placeholder="Tuliskan catatan perbaikan sintaks query, kelengkapan tabel, atau apresiasi..." style="font-size: 12px;"></textarea>
                            </div>
                            <div>
                                <button type="submit" id="btnSaveModalGrade" class="btn btn-success" style="width: 100%; padding: 10px; font-size: 13px; font-weight: 700; display: flex; justify-content: center; align-items: center; gap: 6px;">
                                    💾 Simpan Nilai Sekarang
                                </button>
                            </div>
                            <div id="modalGradeAlert" style="display: none; margin-top: 10px; padding: 8px 12px; border-radius: 6px; font-size: 12px;"></div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closePreviewModal()">Tutup Jendela (Esc)</button>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- JAVASCRIPT: POLLING REAL-TIME, AUDIO, MODAL & GRADING   -->
<!-- ======================================================== -->
<script>
// State Management
let highestSubmissionId = <?= (int)$maxRowId ?>;
const activeFilterSoal = <?= (int)$filterSoalId ?>;
const activeTingkat = '<?= htmlspecialchars($tingkatFilter) ?>';
let audioContextInstance = null;

// Inisialisasi Preferensi Suara dari LocalStorage
function isSoundEnabled() {
    return localStorage.getItem('lab_sound_enabled') !== '0';
}

function updateSoundUI() {
    const enabled = isSoundEnabled();
    const icon = document.getElementById('soundIcon');
    const text = document.getElementById('soundText');
    const btn = document.getElementById('btnSoundToggle');
    if (enabled) {
        icon.textContent = '🔊';
        text.textContent = 'Suara: ON';
        btn.style.background = '#e0f2fe';
        btn.style.color = '#0369a1';
        btn.style.borderColor = '#bae6fd';
    } else {
        icon.textContent = '🔇';
        text.textContent = 'Suara: OFF';
        btn.style.background = '#f1f5f9';
        btn.style.color = '#64748b';
        btn.style.borderColor = '#cbd5e1';
    }
}

function toggleSound() {
    const newState = isSoundEnabled() ? '0' : '1';
    localStorage.setItem('lab_sound_enabled', newState);
    updateSoundUI();
    if (newState === '1') {
        playNotificationChime();
    }
}

// Inisialisasi Audio Chime sintetis Web Audio API (Bekerja 100% offline di jaringan LAN)
function getAudioContext() {
    if (!audioContextInstance) {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (AudioCtx) {
            audioContextInstance = new AudioCtx();
        }
    }
    if (audioContextInstance && audioContextInstance.state === 'suspended') {
        audioContextInstance.resume();
    }
    return audioContextInstance;
}

function playNotificationChime() {
    if (!isSoundEnabled()) return;
    try {
        const ctx = getAudioContext();
        if (!ctx) return;
        const now = ctx.currentTime;

        // Nada 1: D5 (587.33 Hz)
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(587.33, now);
        gain1.gain.setValueAtTime(0.18, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.32);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.32);

        // Nada 2: A5 (880.00 Hz)
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(880.00, now + 0.12);
        gain2.gain.setValueAtTime(0.24, now + 0.12);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.65);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.12);
        osc2.stop(now + 0.65);
    } catch (err) {
        console.warn('Audio chime warning:', err);
    }
}

// Komponen Toast Visual
function showToastNotification(nama, kelas, judulSoal, submissionId) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'toast-card';
    toast.innerHTML = `
        <div style="font-size: 22px; line-height: 1;">🔔</div>
        <div style="flex: 1;">
            <div style="font-size: 13.5px; font-weight: 700; color: #166534; display: flex; justify-content: space-between; align-items: center;">
                <span>Jawaban Baru Masuk!</span>
                <span style="font-size: 10.5px; color: #64748b; font-weight: 500;">Baru saja</span>
            </div>
            <div style="font-size: 12.5px; color: #1e293b; margin-top: 3px;">
                <strong>${escapeHtml(nama)}</strong> (${escapeHtml(kelas)})
            </div>
            <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                ${escapeHtml(judulSoal)}
            </div>
            <div style="margin-top: 8px;">
                <button type="button" class="btn btn-primary" onclick="openPreviewModal(${submissionId})" style="padding: 3px 10px; font-size: 11px;">
                    👁️ Buka &amp; Nilai Langsung
                </button>
            </div>
        </div>
        <button type="button" onclick="dismissToast(this.parentElement)" style="background: none; border: none; font-size: 18px; color: #94a3b8; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
    `;

    container.appendChild(toast);

    // Auto dismiss dalam 6 detik
    setTimeout(() => {
        dismissToast(toast);
    }, 6000);
}

function dismissToast(toastElement) {
    if (!toastElement || !toastElement.parentElement) return;
    toastElement.classList.add('toast-hiding');
    setTimeout(() => {
        if (toastElement.parentElement) {
            toastElement.parentElement.removeChild(toastElement);
        }
    }, 320);
}

// XSS Sanitizer Helper untuk DOM
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Re-index penomoran tabel
function reindexTableRows() {
    const rows = document.querySelectorAll('#submissionsBody tr');
    let idx = 1;
    rows.forEach(r => {
        const numCell = r.querySelector('.row-num');
        if (numCell) {
            numCell.textContent = idx++;
        }
    });
}

// Polling Pengumpulan Baru Real-time
async function pollNewSubmissions() {
    try {
        const url = `index.php?action=api_submissions_poll&last_id=${highestSubmissionId}&filter_soal=${activeFilterSoal}&tingkat=${encodeURIComponent(activeTingkat)}`;
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return;

        const data = await res.json();
        if (!data.success) return;

        // Perbarui statistik counter di kartu dashboard
        if (data.total_pengumpulan !== undefined) {
            const el = document.getElementById('statTotalPengumpulan');
            if (el) el.textContent = data.total_pengumpulan;
        }
        if (data.total_siswa !== undefined) {
            const el = document.getElementById('statTotalSiswa');
            if (el) el.textContent = data.total_siswa;
        }
        if (data.total_soal_aktif !== undefined) {
            const el = document.getElementById('statTotalSoalAktif');
            if (el) el.textContent = data.total_soal_aktif;
        }
        if (data.total_alerts !== undefined) {
            const el = document.getElementById('statTotalAlerts');
            if (el) {
                el.textContent = `${data.total_alerts} Alert`;
                el.style.color = data.total_alerts > 0 ? '#b91c1c' : '#15803d';
            }
        }

        // Tangani baris pengumpulan baru
        if (data.new_items && data.new_items.length > 0) {
            const tbody = document.getElementById('submissionsBody');
            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow && emptyRow.parentElement) {
                emptyRow.parentElement.removeChild(emptyRow);
            }

            data.new_items.forEach(item => {
                // Perbarui highestSubmissionId
                if (item.id > highestSubmissionId) {
                    highestSubmissionId = item.id;
                }

                // Cek apakah baris sudah ada di DOM (menghindari duplikasi)
                if (document.getElementById(`row-sub-${item.id}`)) return;

                const tr = document.createElement('tr');
                tr.id = `row-sub-${item.id}`;
                tr.setAttribute('data-id', item.id);
                tr.className = 'highlight-new-row';
                if (item.is_duplicate) {
                    tr.style.backgroundColor = '#fff1f2';
                }

                const tingkatBadge = item.tingkat === 'XI' 
                    ? `<span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 10px; padding: 2px 5px;">📘 XI</span>`
                    : `<span class="badge" style="background: #ffedd5; color: #9a3412; font-size: 10px; padding: 2px 5px;">📙 XII</span>`;

                const fileBtn = item.tipe_jawaban === 'github_link'
                    ? `<a href="${escapeHtml(item.jawaban_konten)}" target="_blank" class="btn btn-secondary" style="padding: 4px 8px; font-size: 11px; text-decoration: none;">🔗 Buka GitHub Repo &rarr;</a>`
                    : `<a href="src/uploads/${escapeHtml(item.jawaban_konten)}" target="_blank" download class="btn btn-secondary" style="padding: 4px 8px; font-size: 11px; text-decoration: none;">💾 Unduh ${escapeHtml(item.jawaban_konten)}</a>`;

                const catatanHtml = item.catatan_siswa 
                    ? `<div style="font-size: 11px; color: #64748b; margin-top: 4px;">"${escapeHtml(item.catatan_siswa)}"</div>` 
                    : '';

                const alertHtml = item.is_duplicate
                    ? `<div class="device-alert">⚠️ PERINGATAN: Perangkat/IP sama dipakai oleh ${item.duplicate_count} siswa! (Indikasi titip kirim)</div>`
                    : `<div style="font-size: 11px; color: #166534; margin-top: 3px;">✅ Perangkat Terverifikasi Unik</div>`;

                const nilaiVal = item.nilai !== null && item.nilai !== undefined ? item.nilai : '';
                const feedbackVal = item.feedback_guru || '';

                tr.innerHTML = `
                    <td><span class="row-num">-</span></td>
                    <td>
                        <strong>${escapeHtml(item.nama)}</strong><br>
                        <span style="font-size: 11px; color: #64748b;">NIS: ${escapeHtml(item.nis)} (${escapeHtml(item.kelas)})</span>
                    </td>
                    <td>
                        ${tingkatBadge}
                        <span style="font-size: 12px; font-weight: 600; color: #334155; margin-left: 3px;">${escapeHtml(item.judul_soal)}</span>
                    </td>
                    <td>
                        <div class="btn-group-inline" style="margin-bottom: 3px;">
                            <button type="button" class="btn btn-primary" onclick="openPreviewModal(${item.id})" style="padding: 4px 9px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 4px;">
                                👁️ Pratinjau &amp; Nilai
                            </button>
                            ${fileBtn}
                        </div>
                        ${catatanHtml}
                    </td>
                    <td>
                        <div class="device-pill">IP: ${escapeHtml(item.client_ip)} | Host: ${escapeHtml(item.client_hostname)}</div>
                        ${alertHtml}
                    </td>
                    <td style="font-size: 12px; color: #64748b;">
                        ${escapeHtml(item.waktu_kirim)}
                    </td>
                    <td>
                        <form id="inline-form-${item.id}" onsubmit="saveInlineGrade(event, ${item.id})" style="display: flex; flex-direction: column; gap: 4px;">
                            <input type="hidden" name="pengumpulan_id" value="${item.id}">
                            <div style="display: flex; gap: 4px;">
                                <input type="number" id="inline-nilai-${item.id}" name="nilai" value="${escapeHtml(nilaiVal)}" placeholder="0-100" min="0" max="100" class="form-control" style="width: 70px; padding: 4px 6px; font-size: 12px;" required>
                                <button type="submit" id="inline-btn-${item.id}" class="btn btn-primary" style="padding: 4px 8px; font-size: 11px;">Simpan</button>
                            </div>
                            <input type="text" id="inline-feedback-${item.id}" name="feedback" value="${escapeHtml(feedbackVal)}" placeholder="Catatan penguji" class="form-control" style="padding: 3px 6px; font-size: 11px;">
                        </form>
                    </td>
                `;

                // Sisipkan di baris paling atas
                if (tbody.firstChild) {
                    tbody.insertBefore(tr, tbody.firstChild);
                } else {
                    tbody.appendChild(tr);
                }

                // Mainkan bunyi chime dan tampilkan toast
                playNotificationChime();
                showToastNotification(item.nama, item.kelas, item.judul_soal, item.id);
            });

            reindexTableRows();
        } else if (data.last_id > highestSubmissionId) {
            highestSubmissionId = data.last_id;
        }
    } catch (err) {
        console.warn('Polling error:', err);
    }
}

// ========================================================
// FUNGSI MODAL PRATINJAU & PENILAIAN CEPAT
// ========================================================
async function openPreviewModal(submissionId) {
    const modal = document.getElementById('previewModal');
    if (!modal) return;

    // Reset status modal
    document.getElementById('modalStudentName').textContent = 'Memuat Data Jawaban...';
    document.getElementById('modalStudentMeta').textContent = 'Mengambil data dari server laboratorium...';
    document.getElementById('modalSoalTitle').textContent = '-';
    document.getElementById('modalWaktuKirim').textContent = '-';
    document.getElementById('modalTelemetri').textContent = '-';
    document.getElementById('modalCatatanSiswa').innerHTML = '<em>Memuat...</em>';
    document.getElementById('modalCodeContent').textContent = 'Mengambil naskah berkas SQL...';
    document.getElementById('modalFileSize').textContent = '-';
    document.getElementById('modalGradeAlert').style.display = 'none';
    document.getElementById('modalPengumpulanId').value = submissionId;
    document.getElementById('btnCopyCode').textContent = '📋 Salin Kode SQL';

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Buka audio context pada interaksi pertama
    getAudioContext();

    try {
        const res = await fetch(`index.php?action=api_preview_submission&id=${submissionId}`);
        const result = await res.json();

        if (!result.success) {
            alert(result.message || 'Gagal memuat pratinjau.');
            closePreviewModal();
            return;
        }

        const d = result.data;
        document.getElementById('modalStudentName').textContent = d.nama;
        document.getElementById('modalStudentMeta').textContent = `NIS: ${d.nis} | Kelas: ${d.kelas}`;
        document.getElementById('modalSoalTitle').textContent = `[${d.tingkat}] ${d.sesi} - ${d.judul_soal}`;
        document.getElementById('modalWaktuKirim').textContent = d.waktu_kirim;
        document.getElementById('modalTelemetri').textContent = `IP: ${d.client_ip} (${d.client_hostname})`;
        document.getElementById('modalCatatanSiswa').textContent = d.catatan_siswa ? `"${d.catatan_siswa}"` : '(Tidak ada catatan siswa)';

        const codeWrapper = document.getElementById('modalCodeWrapper');
        const githubWrapper = document.getElementById('modalGithubWrapper');
        const downloadBtn = document.getElementById('modalDownloadBtn');
        const fileLabel = document.getElementById('modalFileLabel');

        if (d.is_github) {
            codeWrapper.style.display = 'none';
            githubWrapper.style.display = 'block';
            document.getElementById('modalGithubLink').href = d.jawaban_konten;
            fileLabel.textContent = '🔗 Tautan GitHub Siswa';
            document.getElementById('modalFileSize').textContent = 'Repositori Git';
            downloadBtn.style.display = 'none';
            document.getElementById('btnCopyCode').style.display = 'none';
        } else {
            codeWrapper.style.display = 'block';
            githubWrapper.style.display = 'none';
            document.getElementById('modalCodeContent').textContent = d.file_content || '-- Berkas kosong --';
            fileLabel.textContent = `📄 Berkas: ${d.file_name || 'jawaban.sql'}`;
            document.getElementById('modalFileSize').textContent = d.file_size_formatted || '';
            downloadBtn.style.display = 'inline-block';
            downloadBtn.href = `src/uploads/${d.jawaban_konten}`;
            downloadBtn.download = d.file_name || d.jawaban_konten;
            document.getElementById('btnCopyCode').style.display = 'inline-block';
        }

        // Isi form penilaian
        document.getElementById('modalInputNilai').value = d.nilai !== null && d.nilai !== undefined ? d.nilai : '';
        document.getElementById('modalInputFeedback').value = d.feedback_guru || '';

    } catch (err) {
        console.error('Error fetching preview:', err);
        alert('Terjadi kesalahan jaringan saat memuat pratinjau.');
        closePreviewModal();
    }
}

function closePreviewModal() {
    const modal = document.getElementById('previewModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function handleModalOverlayClick(e) {
    if (e.target.id === 'previewModal') {
        closePreviewModal();
    }
}

// Keyboard shortcut: Tekan tombol Esc untuk menutup modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});

// Salin isi kode ke clipboard
function copyModalCode() {
    const code = document.getElementById('modalCodeContent').textContent;
    const btn = document.getElementById('btnCopyCode');
    if (!navigator.clipboard) {
        const textarea = document.createElement('textarea');
        textarea.value = code;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        btn.textContent = '✅ Disalin!';
        setTimeout(() => { btn.textContent = '📋 Salin Kode SQL'; }, 2000);
        return;
    }
    navigator.clipboard.writeText(code).then(() => {
        btn.textContent = '✅ Disalin!';
        setTimeout(() => { btn.textContent = '📋 Salin Kode SQL'; }, 2000);
    }).catch(err => {
        alert('Gagal menyalin teks ke clipboard.');
    });
}

// Preset nilai cepat
function setPresetGrade(val) {
    const input = document.getElementById('modalInputNilai');
    const feedback = document.getElementById('modalInputFeedback');
    input.value = val;

    // Saran feedback otomatis jika masih kosong
    if (!feedback.value.trim()) {
        if (val === 100) {
            feedback.value = 'Sangat sempurna! Seluruh query DDL, relasi Foreign Key, dan data dummy lengkap dan valid.';
        } else if (val >= 85) {
            feedback.value = 'Pengerjaan sangat baik dan query rapi. Sedikit penyesuaian sintaks telah diverifikasi.';
        } else if (val >= 75) {
            feedback.value = 'Tuntas memenuhi KKM. Perhatikan kembali struktur tipe data dan konsistensi penamaan kolom.';
        } else if (val <= 60) {
            feedback.value = 'Perlu perbaikan mandiri (Remedial): lengkapi relasi foreign key dan periksa error sintaks.';
        }
    }
    input.focus();
}

// Simpan nilai dari Modal via AJAX
async function saveModalGrade(e) {
    e.preventDefault();
    const id = document.getElementById('modalPengumpulanId').value;
    const nilai = document.getElementById('modalInputNilai').value;
    const feedback = document.getElementById('modalInputFeedback').value;
    const alertBox = document.getElementById('modalGradeAlert');
    const btn = document.getElementById('btnSaveModalGrade');

    btn.disabled = true;
    btn.textContent = '⏳ Menyimpan...';
    alertBox.style.display = 'none';

    try {
        const formData = new URLSearchParams();
        formData.append('id', id);
        formData.append('nilai', nilai);
        formData.append('feedback', feedback);

        const res = await fetch('index.php?action=api_save_grade', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        });

        const result = await res.json();
        if (result.success) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#dcfce7';
            alertBox.style.color = '#166534';
            alertBox.style.border = '1px solid #bbf7d0';
            alertBox.innerHTML = `✅ <strong>Berhasil!</strong> Nilai ${result.nilai !== null ? result.nilai : '-'} dan umpan balik tersimpan di basis data.`;

            // Perbarui juga nilai di baris tabel secara instan
            const inlineNilai = document.getElementById(`inline-nilai-${id}`);
            const inlineFeedback = document.getElementById(`inline-feedback-${id}`);
            if (inlineNilai) inlineNilai.value = result.nilai !== null ? result.nilai : '';
            if (inlineFeedback) inlineFeedback.value = result.feedback || '';

            // Flash baris tabel sebagai konfirmasi visual
            const row = document.getElementById(`row-sub-${id}`);
            if (row) {
                row.classList.remove('highlight-new-row');
                void row.offsetWidth; // trigger reflow
                row.classList.add('highlight-new-row');
            }
        } else {
            alertBox.style.display = 'block';
            alertBox.style.background = '#fee2e2';
            alertBox.style.color = '#991b1b';
            alertBox.style.border = '1px solid #fecaca';
            alertBox.textContent = `❌ ${result.message || 'Gagal menyimpan nilai.'}`;
        }
    } catch (err) {
        alertBox.style.display = 'block';
        alertBox.style.background = '#fee2e2';
        alertBox.style.color = '#991b1b';
        alertBox.style.border = '1px solid #fecaca';
        alertBox.textContent = '❌ Terjadi kesalahan jaringan saat menyimpan nilai.';
    } finally {
        btn.disabled = false;
        btn.textContent = '💾 Simpan Nilai';
    }
}

// Simpan nilai dari formulir inline pada tabel via AJAX
async function saveInlineGrade(e, id) {
    e.preventDefault();
    const nilaiInput = document.getElementById(`inline-nilai-${id}`);
    const feedbackInput = document.getElementById(`inline-feedback-${id}`);
    const btn = document.getElementById(`inline-btn-${id}`);

    const nilai = nilaiInput ? nilaiInput.value : '';
    const feedback = feedbackInput ? feedbackInput.value : '';

    const origText = btn.textContent;
    btn.disabled = true;
    btn.textContent = '⏳';

    try {
        const formData = new URLSearchParams();
        formData.append('id', id);
        formData.append('nilai', nilai);
        formData.append('feedback', feedback);

        const res = await fetch('index.php?action=api_save_grade', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        });

        const result = await res.json();
        if (result.success) {
            btn.textContent = '✅';
            btn.style.background = '#16a34a';
            setTimeout(() => {
                btn.textContent = origText;
                btn.style.background = '';
                btn.disabled = false;
            }, 1500);

            const row = document.getElementById(`row-sub-${id}`);
            if (row) {
                row.classList.remove('highlight-new-row');
                void row.offsetWidth;
                row.classList.add('highlight-new-row');
            }
        } else {
            alert(result.message || 'Gagal menyimpan nilai.');
            btn.textContent = origText;
            btn.disabled = false;
        }
    } catch (err) {
        alert('Terjadi kesalahan jaringan.');
        btn.textContent = origText;
        btn.disabled = false;
    }
}

// Jalankan Polling setiap 3.5 detik
document.addEventListener('DOMContentLoaded', () => {
    updateSoundUI();
    setInterval(pollNewSubmissions, 3500);
});
</script>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
