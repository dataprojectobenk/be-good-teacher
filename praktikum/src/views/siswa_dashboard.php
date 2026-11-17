<?php
// praktikum/src/views/siswa_dashboard.php
$pageTitle = "Dashboard Siswa";
require_once __DIR__ . '/layout_header.php';

$pdo = getDatabaseConnection();
$userId = (int)$user['id'];

// Ambil tingkat siswa berdasarkan kelas terdaftar
$studentTingkat = getStudentTingkat($user['kelas'] ?? '');

// Ambil HANYA soal aktif yang sesuai dengan tingkat kelas siswa
$stmtSoal = $pdo->prepare("
    SELECT * FROM soal 
    WHERE is_active = 1 AND tingkat = :tingkat 
    ORDER BY sesi ASC, kategori ASC
");
$stmtSoal->execute([':tingkat' => $studentTingkat]);
$soalAktif = $stmtSoal->fetchAll();
$stmtSoal->closeCursor();

// Ambil data pengumpulan siswa untuk soal-soal tersebut
$stmtPengumpulan = $pdo->prepare("SELECT * FROM pengumpulan WHERE user_id = :user_id");
$stmtPengumpulan->execute([':user_id' => $userId]);
$pengumpulanRows = $stmtPengumpulan->fetchAll();
$stmtPengumpulan->closeCursor();

$submissionsMap = [];
foreach ($pengumpulanRows as $p) {
    $submissionsMap[$p['soal_id']] = $p;
}
?>

<!-- Header Toolbar Dashboard Siswa -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 style="font-size: 22px; color: var(--primary-dark); font-weight: 700;">Dashboard Siswa</h1>
        <p style="color: var(--text-muted); font-size: 13.5px; margin-top: 2px;">
            Selamat datang, <strong><?= htmlspecialchars($user['nama']) ?></strong> (NIS: <?= htmlspecialchars($user['nis']) ?> - Kelas: <?= htmlspecialchars($user['kelas']) ?>)
        </p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <span class="device-pill" style="padding: 6px 12px; font-size: 12px;">
            💻 <?= htmlspecialchars($clientHost) ?> (<?= htmlspecialchars($clientIp) ?>)
        </span>
        <a href="index.php?page=nilai" class="btn btn-primary" style="font-size: 12.5px; background: #059669; border: 1px solid #047857; display: inline-flex; align-items: center; gap: 6px;">
            📊 Rapor &amp; Nilai Saya &rarr;
        </a>
    </div>
</div>

<!-- Banner Pengingat Pembersihan Akun Lab (Compact & Ergonomis) -->
<div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 12px 18px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <span style="font-size: 20px;">⚠️</span>
        <div style="font-size: 13px; color: #92400e;">
            <strong>Penting Sebelum Ujian:</strong> Pastikan Anda telah menghapus <em>Windows Credentials</em> dan me-<em>logout</em> akun GitHub lama di Visual Studio Code komputer lab.
        </div>
    </div>
    <a href="index.php?page=petunjuk" class="btn btn-warning" style="font-size: 12px; padding: 6px 14px; background-color: #d97706; color: white; white-space: nowrap;">
        Buka Petunjuk Lab &rarr;
    </a>
</div>

<!-- Informasi Tingkat Kelas & Ketersediaan Soal -->
<div style="background: #ffffff; border: 1px solid var(--border); border-radius: 8px; padding: 12px 18px; margin-bottom: 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 20px;">🎯</span>
        <div>
            <div style="font-weight: 700; color: var(--primary-dark); font-size: 14px;">
                Paket Ujian Praktikum Tingkat <?= htmlspecialchars($studentTingkat) ?>
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">
                Siswa kelas <strong><?= htmlspecialchars($user['kelas']) ?></strong> hanya mengakses lembar kerja Tingkat <?= htmlspecialchars($studentTingkat) ?> yang berstatus aktif.
            </div>
        </div>
    </div>
    <div>
        <span class="badge badge-success" style="font-size: 12px; padding: 6px 12px;">
            <?= count($soalAktif) ?> Paket Soal Aktif
        </span>
    </div>
</div>

<!-- Daftar Paket Soal Praktikum Aktif -->
<div class="card">
    <div class="card-header" style="background: #fafbfc; padding: 14px 20px;">
        <span class="card-title" style="font-size: 15px;">
            📋 Paket Soal Ujian Praktikum - Tingkat <?= htmlspecialchars($studentTingkat) ?>
        </span>
        <span style="font-size: 12px; color: #64748b;">Gunakan tombol Lembar Kerja untuk membaca petunjuk lengkap &amp; mengumpulkan jawaban</span>
    </div>
    <div class="card-body" style="padding: 20px;">
        <?php if (empty($soalAktif)): ?>
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <div style="font-size: 40px; margin-bottom: 10px;">⏳</div>
                <h3 style="font-size: 16px; color: var(--text-main); margin-bottom: 6px;">Belum Ada Soal Aktif</h3>
                <p style="font-size: 13px;">Guru penguji belum mengaktifkan sesi soal untuk Tingkat <?= htmlspecialchars($studentTingkat) ?>. Silakan tunggu aba-aba dari pengawas laboratorium.</p>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($soalAktif as $soal): 
                    $hasSubmitted = isset($submissionsMap[$soal['id']]);
                    $sub = $hasSubmitted ? $submissionsMap[$soal['id']] : null;
                    $hasSqlData = !empty($soal['file_sql_path']);
                    $isGithub = ($soal['tipe_pengumpulan'] === 'github_link');
                ?>
                    <div style="border: 1px solid var(--border); border-radius: 10px; background: #ffffff; box-shadow: 0 1px 4px rgba(0,0,0,0.03); overflow: hidden;">
                        
                        <!-- Header Kartu Soal -->
                        <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                                    <?php if ($soal['tingkat'] === 'XI'): ?>
                                        <span class="badge" style="background: #dbeafe; color: #1e40af; font-weight: 700;">📘 KELAS XI</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #ffedd5; color: #9a3412; font-weight: 700;">📙 KELAS XII</span>
                                    <?php endif; ?>
                                    <span class="badge badge-info"><?= htmlspecialchars($soal['sesi']) ?></span>
                                    <span class="badge badge-secondary"><?= htmlspecialchars($soal['kategori']) ?></span>
                                    <span class="badge badge-purple">Format: <?= $isGithub ? '🔗 GitHub' : '💾 SQL (.sql)' ?></span>
                                </div>
                                <h2 style="font-size: 18px; color: var(--primary-dark); font-weight: 700; margin-top: 4px;">
                                    <a href="index.php?page=soal&id=<?= $soal['id'] ?>" style="color: inherit; text-decoration: none;">
                                        <?= htmlspecialchars($soal['judul']) ?>
                                    </a>
                                </h2>
                            </div>
                            <div>
                                <?php if ($hasSubmitted): ?>
                                    <span class="badge badge-success" style="font-size: 12px; padding: 6px 14px;">✅ Sudah Mengumpulkan</span>
                                <?php else: ?>
                                    <span class="badge badge-danger" style="font-size: 12px; padding: 6px 14px;">⏳ Belum Mengumpulkan</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Isi Kartu: Petunjuk Inti & Sasaran Praktikum (HD Ergonomic Layout) -->
                        <div style="padding: 20px; display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: center;">
                            <div>
                                <div style="font-size: 13px; color: #475569; margin-bottom: 12px; line-height: 1.6;">
                                    🎯 <strong>Sasaran Praktikum:</strong> Kerjakan studi kasus pemodelan basis data relasional di MariaDB localhost, pastikan integritas <em>foreign key</em> dan query pengujian valid.
                                </div>
                                <div style="display: flex; gap: 18px; font-size: 12px; color: #64748b; flex-wrap: wrap;">
                                    <span>🎯 KKM Kelulusan: <strong style="color: #0f172a;">75 Poin</strong></span>
                                    <span>⏱️ Sesi Ujian: <strong style="color: #0f172a;"><?= htmlspecialchars($soal['sesi']) ?></strong></span>
                                    <span>📁 Format Unggah: <strong style="color: #0f172a;"><?= $isGithub ? 'URL GitHub Publik' : 'File .sql / .zip' ?></strong></span>
                                </div>

                                <!-- Baris Tombol Aksi Cepat -->
                                <div style="display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap; align-items: center;">
                                    <a href="index.php?page=soal&id=<?= $soal['id'] ?>" class="btn btn-primary" style="font-size: 13px; padding: 8px 18px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                        🚀 Buka Lembar Kerja &amp; Kumpul Jawaban &rarr;
                                    </a>
                                    <?php if ($soal['tingkat'] === 'XII' && strpos($soal['kategori'], 'Web') !== false): ?>
                                        <a href="index.php?action=download_base_app" class="btn btn-success" style="font-size: 12px; padding: 7px 12px; display: inline-flex; align-items: center; gap: 6px;">
                                            📦 Unduh base_app.zip
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($hasSqlData): ?>
                                        <a href="index.php?page=view_sql&id=<?= $soal['id'] ?>" class="btn btn-warning" target="_blank" style="font-size: 12px; padding: 7px 12px; background-color: #d97706; color: white;">
                                            🗄️ Lihat Data Dummy SQL
                                        </a>
                                        <a href="index.php?action=download_sql&id=<?= $soal['id'] ?>" class="btn btn-secondary" style="font-size: 12px; padding: 7px 12px; border: 1px solid #d97706; color: #92400e;">
                                            💾 Unduh SQL (.sql)
                                        </a>
                                    <?php endif; ?>
                                    <a href="index.php?action=download_soal&id=<?= $soal['id'] ?>" class="btn btn-secondary" style="font-size: 12px; padding: 7px 12px;">
                                        📥 Unduh Soal (.md)
                                    </a>
                                </div>
                            </div>

                            <!-- Panel Status Pengumpulan Mini di Sisi Kanan Kartu -->
                            <div style="min-width: 280px; max-width: 340px; background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 14px 16px; font-size: 12px;">
                                <?php if ($hasSubmitted): ?>
                                    <div style="color: #15803d; font-weight: 700; margin-bottom: 6px; font-size: 12.5px;">
                                        Terkumpul: <?= htmlspecialchars($sub['waktu_kirim']) ?>
                                    </div>
                                    <div style="margin-bottom: 8px; word-break: break-all;">
                                        <?php if ($sub['tipe_jawaban'] === 'github_link'): ?>
                                            <a href="<?= htmlspecialchars($sub['jawaban_konten']) ?>" target="_blank" style="color: #1e40af; text-decoration: underline; font-weight: 600;">
                                                <?= htmlspecialchars($sub['jawaban_konten']) ?> 🔗
                                            </a>
                                        <?php else: ?>
                                            <a href="src/uploads/<?= htmlspecialchars($sub['jawaban_konten']) ?>" target="_blank" download style="color: #1e40af; text-decoration: underline; font-weight: 600;">
                                                <?= htmlspecialchars($sub['jawaban_konten']) ?> 💾
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($sub['nilai'] !== null): 
                                        $pass = ((int)$sub['nilai'] >= 75);
                                    ?>
                                        <div style="padding: 8px 10px; background: <?= $pass ? '#dcfce7' : '#fee2e2' ?>; border-radius: 6px; margin-top: 6px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-weight: 800; font-size: 16px; color: <?= $pass ? '#15803d' : '#b91c1c' ?>;">
                                                    Nilai: <?= htmlspecialchars($sub['nilai']) ?>/100
                                                </span>
                                                <span class="<?= $pass ? 'badge-kkm-pass' : 'badge-kkm-fail' ?>">
                                                    <?= $pass ? 'Tuntas' : 'Remedial' ?>
                                                </span>
                                            </div>
                                            <?php if (!empty($sub['feedback_guru'])): ?>
                                                <div style="font-size: 11px; color: #334155; margin-top: 4px;">
                                                    <?= htmlspecialchars($sub['feedback_guru']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div style="color: #64748b; font-size: 11.5px; margin-top: 4px;">
                                            ⏳ Menunggu penilaian guru penguji
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div style="color: #b91c1c; font-weight: 700; margin-bottom: 4px;">
                                        Status: Belum Mengumpulkan
                                    </div>
                                    <div style="color: #64748b; line-height: 1.5;">
                                        Silakan klik tombol <strong>Buka Lembar Kerja</strong> untuk membaca soal dan mengunggah berkas jawaban.
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>