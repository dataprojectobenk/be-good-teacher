<?php
// praktikum/src/views/siswa_nilai.php
requireLogin();

// Jika admin membuka halaman ini, arahkan ke buku nilai admin
if (isAdmin()) {
    header('Location: index.php?page=nilai');
    exit;
}

$user = currentUser();
$userId = (int)$user['id'];
$pdo = getDatabaseConnection();
$pageTitle = "Transkrip Nilai Praktikum Siswa";
require_once __DIR__ . '/layout_header.php';

// Ambil tingkat siswa berdasarkan kelas terdaftar
$studentTingkat = getStudentTingkat($user['kelas'] ?? '');

// Ambil seluruh soal yang pernah dikumpulkan siswa ATAU soal yang sedang aktif untuk tingkat siswa
$stmt = $pdo->prepare("
    SELECT 
        s.id as soal_id, s.judul, s.tingkat, s.sesi, s.kategori, s.tipe_pengumpulan, s.is_active,
        p.id as pengumpulan_id, p.tipe_jawaban, p.jawaban_konten, p.catatan_siswa,
        p.waktu_kirim, p.nilai, p.feedback_guru
    FROM soal s
    LEFT JOIN pengumpulan p ON s.id = p.soal_id AND p.user_id = :user_id
    WHERE (p.id IS NOT NULL OR (s.is_active = 1 AND s.tingkat = :tingkat))
    ORDER BY s.sesi ASC, s.kategori ASC
");
$stmt->execute([
    ':user_id' => $userId,
    ':tingkat' => $studentTingkat
]);
$rows = $stmt->fetchAll();
$stmt->closeCursor();

// Kalkulasi statistik akademik mandiri
$totalSoal = count($rows);
$totalDikumpulkan = 0;
$totalDinilai = 0;
$totalTuntas = 0;
$totalRemedial = 0;
$sumNilai = 0;

foreach ($rows as $r) {
    if (!empty($r['pengumpulan_id'])) {
        $totalDikumpulkan++;
        if ($r['nilai'] !== null) {
            $totalDinilai++;
            $sumNilai += (int)$r['nilai'];
            if ((int)$r['nilai'] >= 75) {
                $totalTuntas++;
            } else {
                $totalRemedial++;
            }
        }
    }
}

$rataRata = $totalDinilai > 0 ? round($sumNilai / $totalDinilai, 1) : null;

// Predikat nilai
$predikat = 'Belum Ada Nilai';
$predikatBadgeColor = '#64748b';
if ($rataRata !== null) {
    if ($rataRata >= 90) {
        $predikat = 'A (Sangat Mahir)';
        $predikatBadgeColor = '#15803d';
    } elseif ($rataRata >= 80) {
        $predikat = 'B (Terampil & Baik)';
        $predikatBadgeColor = '#0369a1';
    } elseif ($rataRata >= 75) {
        $predikat = 'C (Tuntas KKM)';
        $predikatBadgeColor = '#d97706';
    } else {
        $predikat = 'D (Perlu Remedial)';
        $predikatBadgeColor = '#b91c1c';
    }
}
?>

<!-- Header Khusus Cetak Dokumen Resmi (Hanya Muncul Saat Print / PDF) -->
<div class="print-only" style="margin-bottom: 25px; border-bottom: 3px double #0f172a; padding-bottom: 12px; text-align: center;">
    <h2 style="font-size: 18px; text-transform: uppercase; margin-bottom: 3px; color: #0f172a;">
        LEMBAR TRANSKRIP HASIL EVALUASI PRAKTIKUM RPL
    </h2>
    <div style="font-size: 13px; font-weight: 600; color: #334155;">
        LABORATORIUM KOMPUTER REKAYASA PERANGKAT LUNAK — TAHUN AJARAN 2026/2027
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 15px; font-size: 12px; text-align: left;">
        <div>
            Nama Siswa: <strong><?= htmlspecialchars($user['nama']) ?></strong><br>
            NIS: <strong><?= htmlspecialchars($user['nis']) ?></strong>
        </div>
        <div style="text-align: right;">
            Kelas / Program: <strong><?= htmlspecialchars($user['kelas']) ?></strong><br>
            Tingkat Ujian: <strong>Tingkat <?= htmlspecialchars($studentTingkat) ?></strong>
        </div>
    </div>
</div>

<!-- Header Antarmuka Web -->
<div class="no-print" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 24px; color: var(--primary-dark);">📊 Transkrip &amp; Nilai Hasil Praktikum</h1>
        <p style="color: var(--text-muted); font-size: 14px;">
            Pantau perolehan skor, status kelulusan KKM (75), dan umpan balik langsung dari guru penguji.
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <button type="button" class="btn btn-secondary" onclick="window.print()" style="font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
            🖨️ Cetak Transkrip Nilai
        </button>
        <a href="index.php?page=dashboard" class="btn btn-primary" style="font-size: 12.5px;">
            📝 Lembar Soal Ujian &rarr;
        </a>
    </div>
</div>

<!-- Statistik Ringkas Hasil Belajar Siswa (HD Fluid KPI Bar) -->
<div class="kpi-grid no-print">
    <div class="kpi-card">
        <div class="kpi-label">Nilai Rata-rata Praktikum</div>
        <div class="kpi-value" style="color: <?= $rataRata !== null ? ($rataRata >= 75 ? '#15803d' : '#b91c1c') : '#64748b' ?>;">
            <?= $rataRata !== null ? $rataRata : '-' ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;">/ 100</span>
        </div>
        <div style="margin-top: 6px;">
            <span class="badge" style="background: <?= $predikatBadgeColor ?>; color: #ffffff; font-size: 11px; padding: 3px 8px;">
                Predikat: <?= htmlspecialchars($predikat) ?>
            </span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Ketuntasan Standar KKM (75)</div>
        <div class="kpi-value" style="color: #0284c7;">
            <?= $totalTuntas ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;">Tuntas</span>
        </div>
        <div class="kpi-sub">
            <?= $totalRemedial > 0 ? "⚠️ {$totalRemedial} tugas di bawah KKM" : "✅ Seluruh tugas dinilai tuntas" ?>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Progres Pengumpulan Tugas</div>
        <div class="kpi-value" style="color: var(--primary);">
            <?= $totalDikumpulkan ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;">/ <?= $totalSoal ?> Soal</span>
        </div>
        <div class="kpi-sub">
            <?= $totalSoal - $totalDikumpulkan > 0 ? '⏳ ' . ($totalSoal - $totalDikumpulkan) . ' belum dikerjakan' : '🎉 Semua soal aktif selesai' ?>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Status Pemeriksaan Guru</div>
        <div class="kpi-value" style="color: #d97706;">
            <?= $totalDinilai ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;">/ <?= $totalDikumpulkan ?> Dinilai</span>
        </div>
        <div class="kpi-sub">
            <?= ($totalDikumpulkan - $totalDinilai) > 0 ? '⏳ ' . ($totalDikumpulkan - $totalDinilai) . ' menunggu skor' : '✅ Semua telah dinilai' ?>
        </div>
    </div>
</div>

<!-- Tabel Rincian Nilai Tiap Sesi Praktikum -->
<div class="card">
    <div class="card-header">
        <span class="card-title">
            📋 Rincian Nilai Praktikum - Tingkat <?= htmlspecialchars($studentTingkat) ?>
        </span>
        <span style="font-size: 12px; color: #64748b;">Standar Kriteria Ketuntasan Minimal (KKM): <strong>75.0</strong></span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 45px;">No</th>
                        <th>Sesi &amp; Kategori</th>
                        <th>Judul Paket Praktikum</th>
                        <th>Waktu Kirim Jawaban</th>
                        <th style="text-align: center; width: 120px;">Nilai (0–100)</th>
                        <th style="text-align: center; width: 130px;">Status KKM</th>
                        <th style="width: 300px;">Catatan &amp; Umpan Balik Guru</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 35px 20px; color: var(--text-muted);">
                                Belum ada sesi praktikum aktif untuk kelas Anda saat ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($rows as $r): 
                            $hasSubmitted = !empty($r['pengumpulan_id']);
                            $isGraded = $r['nilai'] !== null;
                            $score = $isGraded ? (int)$r['nilai'] : null;
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <span class="badge badge-info"><?= htmlspecialchars($r['sesi']) ?></span>
                                    <span class="badge badge-secondary"><?= htmlspecialchars($r['kategori']) ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($r['judul']) ?></strong>
                                    <?php if ($hasSubmitted): ?>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                            Berkas: <code><?= htmlspecialchars(basename($r['jawaban_konten'])) ?></code>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($hasSubmitted): ?>
                                        <span style="font-size: 12px; color: #334155;">
                                            <?= htmlspecialchars($r['waktu_kirim']) ?>
                                        </span>
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
                                    <?php elseif ($hasSubmitted): ?>
                                        <span class="badge badge-warning" style="font-size: 11px;">
                                            ⏳ Menunggu
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-weight: 700;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($isGraded): ?>
                                        <?php if ($score >= 75): ?>
                                            <span class="badge badge-success" style="padding: 5px 10px; font-size: 11px;">
                                                ✅ TUNTAS
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-danger" style="padding: 5px 10px; font-size: 11px;">
                                                ⚠️ REMEDIAL
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif ($hasSubmitted): ?>
                                        <span class="badge badge-secondary" style="font-size: 11px;">
                                            Belum Dinilai
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger" style="font-size: 10.5px;">
                                            Belum Kirim
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($r['feedback_guru'])): ?>
                                        <div style="background: #f8fafc; border-left: 3px solid #3b82f6; padding: 6px 10px; border-radius: 4px; font-size: 12px; color: #1e293b; line-height: 1.5;">
                                            💬 <?= htmlspecialchars($r['feedback_guru']) ?>
                                        </div>
                                    <?php elseif ($isGraded): ?>
                                        <span style="color: #94a3b8; font-size: 11.5px; font-style: italic;">
                                            Tidak ada catatan tambahan dari guru
                                        </span>
                                    <?php elseif ($hasSubmitted): ?>
                                        <span style="color: #94a3b8; font-size: 11.5px; font-style: italic;">
                                            Menunggu koreksi penguji lab
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-size: 11.5px;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tanda Tangan Cetak Lembar Rapor Mandiri -->
<div class="print-only" style="margin-top: 40px; display: flex; justify-content: space-between; page-break-inside: avoid;">
    <div style="text-align: center; width: 220px;">
        <p style="font-size: 12px; margin-bottom: 60px;">Mengetahui,<br>Siswa Praktikan</p>
        <p style="font-size: 12px; font-weight: 700; text-decoration: underline;"><?= htmlspecialchars($user['nama']) ?></p>
        <p style="font-size: 11px; color: #475569;">NIS: <?= htmlspecialchars($user['nis']) ?></p>
    </div>
    <div style="text-align: center; width: 220px;">
        <p style="font-size: 12px; margin-bottom: 60px;">Disahkan oleh,<br>Guru Penguji Praktikum RPL</p>
        <p style="font-size: 12px; font-weight: 700; text-decoration: underline;">Guru Pembimbing Lab</p>
        <p style="font-size: 11px; color: #475569;">NIP/NUPTK Laboratorium</p>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
