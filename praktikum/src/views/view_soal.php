<?php
// praktikum/src/views/view_soal.php
requireLogin();
$user = currentUser();

$soalId = (int)($_GET['id'] ?? 0);
$pdo = getDatabaseConnection();

$stmt = $pdo->prepare("SELECT * FROM soal WHERE id = :id");
$stmt->execute([':id' => $soalId]);
$soal = $stmt->fetch();

if (!$soal) {
    setFlashMessage('error', 'Soal tidak ditemukan.');
    header('Location: index.php?page=dashboard');
    exit;
}

// Hanya admin atau jika soal aktif DAN sesuai tingkat kelas siswa yang bisa membaca
if (!isAdmin()) {
    $studentTingkat = getStudentTingkat($user['kelas'] ?? '');
    if (!$soal['is_active'] || $soal['tingkat'] !== $studentTingkat) {
        setFlashMessage('error', 'Akses ditolak! Soal praktikum tidak tersedia untuk tingkat kelas Anda (' . htmlspecialchars($user['kelas'] ?? '') . ') atau sedang dinonaktifkan.');
        header('Location: index.php?page=dashboard');
        exit;
    }
}

$pageTitle = $soal['judul'] . " - Lembar Kerja Praktikum";
require_once __DIR__ . '/layout_header.php';

// Ambil daftar soal untuk switcher cepat di halaman baca soal (terisolasi per tingkat untuk siswa)
if (!isAdmin()) {
    $stmtOther = $pdo->prepare("SELECT id, tingkat, sesi, kategori, judul FROM soal WHERE is_active = 1 AND tingkat = :tingkat ORDER BY sesi ASC, kategori ASC");
    $stmtOther->execute([':tingkat' => $studentTingkat]);
} else {
    $stmtOther = $pdo->prepare("SELECT id, tingkat, sesi, kategori, judul FROM soal ORDER BY tingkat ASC, sesi ASC, kategori ASC");
    $stmtOther->execute();
}
$otherSoals = $stmtOther->fetchAll();
$stmtOther->closeCursor();

// Ambil data pengumpulan siswa untuk soal aktif ini jika bukan admin
$userSubmission = null;
if (!isAdmin()) {
    $stmtSub = $pdo->prepare("SELECT * FROM pengumpulan WHERE user_id = :uid AND soal_id = :sid LIMIT 1");
    $stmtSub->execute([':uid' => $user['id'], ':sid' => $soalId]);
    $userSubmission = $stmtSub->fetch();
    $stmtSub->closeCursor();
} else {
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM pengumpulan WHERE soal_id = :sid");
    $stmtCount->execute([':sid' => $soalId]);
    $teacherSubmissionsCount = (int)$stmtCount->fetchColumn();
    $stmtCount->closeCursor();
}

// Baca isi berkas Markdown
$filePath = __DIR__ . '/../../' . $soal['file_soal_path'];
$content = file_exists($filePath) ? file_get_contents($filePath) : "Berkas soal tidak ditemukan pada path: " . htmlspecialchars($soal['file_soal_path']);
$hasSqlData = !empty($soal['file_sql_path']);

if (!function_exists('parseSimpleMarkdown')) {
    function parseSimpleMarkdown(string $md): string {
        $lines = explode("\n", $md);
        $html = "";
        $inCodeBlock = false;
        $inTable = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Code block toggle
            if (strpos($trimmed, '```') === 0) {
                if ($inCodeBlock) {
                    $html .= "</code></pre></div>\n";
                    $inCodeBlock = false;
                } else {
                    $html .= "<div class='code-block-container' style='position: relative; margin: 18px 0;'>";
                    $html .= "<button type='button' onclick='copyCodeBlock(this)' style='position: absolute; top: 10px; right: 10px; background: #334155; color: #f8fafc; border: 1px solid #475569; border-radius: 5px; padding: 5px 10px; font-size: 11.5px; cursor: pointer; font-weight: 600; z-index: 10; transition: background 0.2s;'>📋 Salin Kode</button>";
                    $html .= "<pre style='background: #0f172a; color: #f8fafc; padding: 18px 16px; border-radius: 8px; overflow-x: auto; margin: 0; font-size: 13px; line-height: 1.55;'><code>";
                    $inCodeBlock = true;
                }
                continue;
            }

            if ($inCodeBlock) {
                $html .= htmlspecialchars($line) . "\n";
                continue;
            }

            // Table
            if (strpos($trimmed, '|') === 0) {
                if (strpos($trimmed, '| :---') !== false || strpos($trimmed, '|---') !== false) {
                    continue;
                }

                if (!$inTable) {
                    $html .= "<div style='overflow-x: auto; margin: 16px 0;'><table style='width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1;'>\n";
                    $inTable = true;
                    $cells = array_filter(array_map('trim', explode('|', $trimmed)));
                    $html .= "<thead style='background: #f1f5f9;'><tr>\n";
                    foreach ($cells as $cell) {
                        $html .= "<th style='padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;'>" . parseInlineMd($cell) . "</th>\n";
                    }
                    $html .= "</tr></thead><tbody>\n";
                } else {
                    $cells = array_filter(array_map('trim', explode('|', $trimmed)));
                    $html .= "<tr>\n";
                    foreach ($cells as $cell) {
                        $html .= "<td style='padding: 9px 12px; border: 1px solid #e2e8f0;'>" . parseInlineMd($cell) . "</td>\n";
                    }
                    $html .= "</tr>\n";
                }
                continue;
            } else {
                if ($inTable) {
                    $html .= "</tbody></table></div>\n";
                    $inTable = false;
                }
            }

            // Headings
            if (strpos($trimmed, '# ') === 0) {
                $html .= "<h1 style='font-size: 21px; color: #1e3a8a; margin: 24px 0 12px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;'>" . parseInlineMd(substr($trimmed, 2)) . "</h1>\n";
            } elseif (strpos($trimmed, '## ') === 0) {
                $html .= "<h2 style='font-size: 17px; color: #0f172a; margin: 20px 0 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;'>" . parseInlineMd(substr($trimmed, 3)) . "</h2>\n";
            } elseif (strpos($trimmed, '### ') === 0) {
                $html .= "<h3 style='font-size: 14.5px; color: #334155; margin: 16px 0 8px; font-weight: 700;'>" . parseInlineMd(substr($trimmed, 4)) . "</h3>\n";
            } elseif (strpos($trimmed, '---') === 0) {
                $html .= "<hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>\n";
            } elseif (strpos($trimmed, '* ') === 0 || strpos($trimmed, '- ') === 0) {
                $html .= "<li style='margin-left: 22px; margin-bottom: 6px; font-size: 13.5px; color: #334155;'>" . parseInlineMd(substr($trimmed, 2)) . "</li>\n";
            } elseif (!empty($trimmed)) {
                $html .= "<p style='margin-bottom: 12px; font-size: 13.5px; color: #334155; line-height: 1.65;'>" . parseInlineMd($line) . "</p>\n";
            }
        }

        if ($inTable) {
            $html .= "</tbody></table></div>\n";
        }

        return $html;
    }
}

if (!function_exists('parseInlineMd')) {
    function parseInlineMd(string $text): string {
        $text = htmlspecialchars($text);
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/(?<!\*)\*(?!\*)(.*?)(?<!\*)\*(?!\*)/', '<em>$1</em>', $text);
        $text = preg_replace('/`(.*?)`/', '<code style="background: #f1f5f9; color: #0f172a; padding: 2px 5px; border-radius: 4px; font-family: monospace; font-size: 12.5px;">$1</code>', $text);
        return $text;
    }
}
?>

<!-- Toolbar Atas Workspace -->
<div style="margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <a href="<?= isAdmin() ? 'index.php?page=kontrol_sesi' : 'index.php?page=dashboard' ?>" class="btn btn-secondary" style="font-size: 12.5px; padding: 6px 12px;">
            &larr; <?= isAdmin() ? 'Kontrol Sesi' : 'Dashboard Siswa' ?>
        </a>

        <?php if (count($otherSoals) > 1): ?>
            <form method="GET" action="index.php" style="display: inline-flex; align-items: center; gap: 6px;">
                <input type="hidden" name="page" value="soal">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Ganti Paket:</span>
                <select name="id" class="form-control" style="font-size: 12px; padding: 4px 8px; width: auto; max-width: 280px;" onchange="this.form.submit()">
                    <?php foreach ($otherSoals as $os): ?>
                        <option value="<?= $os['id'] ?>" <?= $os['id'] == $soalId ? 'selected' : '' ?>>
                            [<?= $os['tingkat'] ?>] <?= htmlspecialchars($os['sesi']) ?> - <?= htmlspecialchars($os['kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>
    </div>

    <div class="btn-group-inline">
        <?php if ($soal['tingkat'] === 'XII' && strpos($soal['kategori'], 'Web') !== false): ?>
            <a href="index.php?action=download_base_app" class="btn btn-success" style="font-size: 12px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px;">
                📦 Unduh Starter Laravel (base_app.zip)
            </a>
        <?php endif; ?>
        <?php if ($hasSqlData): ?>
            <a href="index.php?page=view_sql&id=<?= $soal['id'] ?>" class="btn btn-warning" target="_blank" style="font-size: 12px; padding: 6px 12px; background-color: #d97706; color: white;">
                🗄️ Buka Data Dummy SQL
            </a>
            <a href="index.php?action=download_sql&id=<?= $soal['id'] ?>" class="btn btn-secondary" style="font-size: 12px; padding: 6px 12px; border: 1px solid #d97706; color: #92400e;">
                💾 Unduh SQL (.sql)
            </a>
        <?php endif; ?>
        <a href="index.php?action=download_soal&id=<?= $soal['id'] ?>" class="btn btn-primary" style="font-size: 12px; padding: 6px 12px;">
            📥 Unduh File Soal (.md)
        </a>
    </div>
</div>

<!-- Grid Workspace Dual-Pane (Soal Kiri, Form Pengumpulan & Aksi Kanan) -->
<div class="workspace-layout">
    
    <!-- KOLOM KIRI: NASKAH SOAL DAN PETUNJUK LENGKAP -->
    <div style="min-width: 0;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="background: #fafbfc; border-bottom: 1px solid var(--border); padding: 16px 22px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                        <?php if ($soal['tingkat'] === 'XI'): ?>
                            <span class="badge" style="background: #dbeafe; color: #1e40af; font-weight: 700;">📘 KELAS XI</span>
                        <?php else: ?>
                            <span class="badge" style="background: #ffedd5; color: #9a3412; font-weight: 700;">📙 KELAS XII</span>
                        <?php endif; ?>
                        <span class="badge badge-info"><?= htmlspecialchars($soal['sesi']) ?></span>
                        <span class="badge badge-secondary"><?= htmlspecialchars($soal['kategori']) ?></span>
                        <span class="badge badge-purple">Format: <?= $soal['tipe_pengumpulan'] === 'github_link' ? '🔗 Repositori GitHub' : '💾 Naskah SQL (.sql)' ?></span>
                    </div>
                    <h1 style="font-size: 20px; color: var(--primary-dark); font-weight: 700; line-height: 1.3;">
                        <?= htmlspecialchars($soal['judul']) ?>
                    </h1>
                </div>
            </div>

            <!-- Bar Ringkas Sasaran & KKM Standar -->
            <div style="background: #f1f5f9; padding: 10px 22px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 12.5px;">
                <div style="color: #475569;">
                    🎯 <strong>Standar Kelulusan (KKM): 75</strong> | Pastikan relasi tabel, tipe data, dan query telah diuji coba di MariaDB.
                </div>
                <div>
                    <span style="color: #64748b;">Host Komputer Lab: </span>
                    <strong style="color: #1e293b;"><?= htmlspecialchars($clientHost) ?> (<?= htmlspecialchars($clientIp) ?>)</strong>
                </div>
            </div>

            <div class="card-body" style="padding: 24px 28px;">
                <?= parseSimpleMarkdown($content) ?>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: PANEL AKSI & PENGUMPULAN JAWABAN STICKY -->
    <div class="sticky-panel">
        
        <?php if (!isAdmin()): ?>
            <!-- CARD 1: STATUS PENGUMPULAN & NILAI SISWA -->
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="background: #f8fafc; padding: 12px 16px;">
                    <span class="card-title" style="font-size: 14px; display: flex; align-items: center; gap: 6px;">
                        <span>📤</span> Lembar Pengumpulan Tugas
                    </span>
                    <?php if ($userSubmission): ?>
                        <span class="badge badge-success" style="font-size: 11px;">Sudah Dikirim</span>
                    <?php else: ?>
                        <span class="badge badge-danger" style="font-size: 11px;">Belum Dikirim</span>
                    <?php endif; ?>
                </div>
                <div class="card-body" style="padding: 16px;">
                    
                    <?php if ($userSubmission): ?>
                        <!-- Status jika sudah mengumpulkan -->
                        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 6px; padding: 12px; margin-bottom: 14px;">
                            <div style="font-size: 12px; color: #065f46; font-weight: 700; margin-bottom: 4px;">
                                ✅ Jawaban Anda Telah Diterima Server
                            </div>
                            <div style="font-size: 11.5px; color: #047857;">
                                Waktu kirim: <strong><?= htmlspecialchars($userSubmission['waktu_kirim']) ?></strong>
                            </div>

                            <div style="margin-top: 8px; font-size: 12px;">
                                <?php if ($userSubmission['tipe_jawaban'] === 'github_link'): ?>
                                    <span style="color: #475569;">Repositori:</span><br>
                                    <a href="<?= htmlspecialchars($userSubmission['jawaban_konten']) ?>" target="_blank" style="color: #1e40af; font-weight: 600; text-decoration: underline; word-break: break-all;">
                                        <?= htmlspecialchars($userSubmission['jawaban_konten']) ?> 🔗
                                    </a>
                                <?php else: ?>
                                    <span style="color: #475569;">Berkas:</span><br>
                                    <a href="src/uploads/<?= htmlspecialchars($userSubmission['jawaban_konten']) ?>" target="_blank" download style="color: #1e40af; font-weight: 600; text-decoration: underline; word-break: break-all;">
                                        <?= htmlspecialchars($userSubmission['jawaban_konten']) ?> 💾
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($userSubmission['catatan_siswa'])): ?>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 6px; font-style: italic;">
                                    "<?= htmlspecialchars($userSubmission['catatan_siswa']) ?>"
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Nilai & Feedback Guru jika sudah diperiksa -->
                        <?php if ($userSubmission['nilai'] !== null): 
                            $isPass = ((int)$userSubmission['nilai'] >= 75);
                        ?>
                            <div style="background: #ffffff; border: 1.5px solid <?= $isPass ? '#86efac' : '#fca5a5' ?>; border-radius: 8px; padding: 14px; margin-bottom: 14px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <span style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase;">Hasil Penilaian</span>
                                    <?php if ($isPass): ?>
                                        <span class="badge-kkm-pass">🏆 Tuntas KKM</span>
                                    <?php else: ?>
                                        <span class="badge-kkm-fail">⚠️ Belum Tuntas</span>
                                    <?php endif; ?>
                                </div>
                                <div style="font-size: 26px; font-weight: 800; color: <?= $isPass ? '#15803d' : '#b91c1c' ?>;">
                                    <?= htmlspecialchars($userSubmission['nilai']) ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;">/ 100</span>
                                </div>
                                <?php if (!empty($userSubmission['feedback_guru'])): ?>
                                    <div style="font-size: 12px; color: #334155; margin-top: 6px; background: #f8fafc; padding: 8px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                        💬 <strong>Catatan Penguji:</strong> <?= htmlspecialchars($userSubmission['feedback_guru']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 10px; margin-bottom: 14px; font-size: 12px; color: #64748b; text-align: center;">
                                ⏳ Naskah jawaban Anda sedang dalam antrean koreksi guru penguji.
                            </div>
                        <?php endif; ?>

                        <!-- Tombol Perbarui / Kirim Ulang Jawaban -->
                        <details>
                            <summary style="font-size: 12px; font-weight: 600; color: #1e40af; cursor: pointer; padding: 4px 0;">
                                🔄 Kirim Ulang / Perbarui Jawaban
                            </summary>
                            <div style="margin-top: 10px; border-top: 1px dashed #cbd5e1; padding-top: 12px;">
                                <form action="index.php?action=submit_jawaban" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="soal_id" value="<?= $soal['id'] ?>">
                                    <input type="hidden" name="redirect" value="soal">
                                    <input type="hidden" name="client_pc_user" value="<?= htmlspecialchars($clientHost) ?>">

                                    <?php if ($soal['tipe_pengumpulan'] === 'github_link'): ?>
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label style="font-size: 12px; font-weight: 600; color: #334155;">Tautan GitHub Baru:</label>
                                            <input type="url" name="github_url" class="form-control" placeholder="https://github.com/..." required style="font-size: 12px; padding: 7px 10px;">
                                        </div>
                                    <?php else: ?>
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label style="font-size: 12px; font-weight: 600; color: #334155;">Pilih File SQL Baru (.sql / .zip):</label>
                                            <input type="file" name="file_jawaban" class="form-control" accept=".sql,.txt,.zip" required style="font-size: 11.5px; padding: 5px;">
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group" style="margin-bottom: 12px;">
                                        <label style="font-size: 11.5px; color: #64748b;">Catatan Tambahan:</label>
                                        <input type="text" name="catatan" class="form-control" placeholder="Opsional" style="font-size: 11.5px; padding: 6px 8px;">
                                    </div>

                                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 12px; padding: 8px;">
                                        Perbarui Jawaban Sekarang
                                    </button>
                                </form>
                            </div>
                        </details>

                    <?php else: ?>
                        <!-- Form Kirim Pertama Kali Langsung di Sidebar -->
                        <div style="margin-bottom: 10px; font-size: 12.5px; color: #334155;">
                            Kirimkan hasil pengerjaan Anda langsung tanpa perlu berpindah tab browser:
                        </div>

                        <form action="index.php?action=submit_jawaban" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="soal_id" value="<?= $soal['id'] ?>">
                            <input type="hidden" name="redirect" value="soal">
                            <input type="hidden" name="client_pc_user" value="<?= htmlspecialchars($clientHost) ?>">

                            <?php if ($soal['tipe_pengumpulan'] === 'github_link'): ?>
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label style="font-size: 12px; font-weight: 700; color: var(--primary);">
                                        🔗 URL Repositori GitHub:
                                    </label>
                                    <input type="url" name="github_url" class="form-control" placeholder="https://github.com/username/nama-repo" required style="font-size: 12.5px; padding: 8px 10px;">
                                    <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">
                                        * Pastikan repositori bersifat publik dan telah di-push dari komputer lab.
                                    </small>
                                </div>
                            <?php else: ?>
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label style="font-size: 12px; font-weight: 700; color: var(--primary);">
                                        💾 Unggah Berkas Jawaban SQL (.sql):
                                    </label>
                                    <input type="file" name="file_jawaban" class="form-control" accept=".sql,.txt,.zip" required style="font-size: 11.5px; padding: 6px;">
                                    <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">
                                        * Unggah naskah file query SQL atau zip arsip proyek Anda.
                                    </small>
                                </div>
                            <?php endif; ?>

                            <div class="form-group" style="margin-bottom: 14px;">
                                <label style="font-size: 12px; color: #475569;">Catatan untuk Penguji (Opsional):</label>
                                <input type="text" name="catatan" class="form-control" placeholder="Contoh: Sudah menguji relasi tabel" style="font-size: 12px; padding: 7px 10px;">
                            </div>

                            <button type="submit" class="btn btn-success" style="width: 100%; font-size: 13px; padding: 10px; font-weight: 700;">
                                🚀 Kirim Jawaban Sekarang
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>

        <?php else: ?>
            <!-- CARD 1 GURU PENGUJI: KONTROL & STATISTIK SOAL -->
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="background: #f8fafc; padding: 12px 16px;">
                    <span class="card-title" style="font-size: 14px;">👨‍🏫 Mode Guru Penguji</span>
                    <span class="badge <?= $soal['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                        <?= $soal['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                </div>
                <div class="card-body" style="padding: 16px;">
                    <div style="font-size: 12.5px; color: #475569; margin-bottom: 12px;">
                        Jumlah jawaban masuk untuk paket ini:
                        <div style="font-size: 24px; font-weight: 800; color: #0284c7; margin: 4px 0;">
                            <?= $teacherSubmissionsCount ?> Jawaban
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="index.php?page=pengumpulan&filter_soal=<?= $soal['id'] ?>" class="btn btn-primary" style="font-size: 12px; text-align: center;">
                            📥 Tinjau &amp; Nilai Jawaban Paket Ini &rarr;
                        </a>
                        <a href="index.php?page=kontrol_sesi" class="btn btn-secondary" style="font-size: 12px; text-align: center;">
                            🎛️ Buka Kontrol Sesi Ujian
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- CARD 2: PANDUAN INTEGRITAS LAB & CHECKLIST -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header" style="background: #f8fafc; padding: 12px 16px;">
                <span class="card-title" style="font-size: 13.5px;">📋 Panduan &amp; Checklist Praktikum</span>
            </div>
            <div class="card-body" style="padding: 14px 16px; font-size: 12px; line-height: 1.6; color: #334155;">
                <ul style="padding-left: 18px; margin-bottom: 10px;">
                    <li style="margin-bottom: 6px;">
                        <strong>Audit Akun:</strong> Pastikan akun GitHub lama di VS Code telah di-<em>sign out</em>.
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>Database:</strong> Gunakan MariaDB / MySQL localhost port 3306.
                    </li>
                    <li style="margin-bottom: 6px;">
                        <strong>Sintaks SQL:</strong> Pastikan naskah SQL memuat <code>CREATE TABLE</code>, primary key, dan foreign key.
                    </li>
                    <li>
                        <strong>Batas Waktu:</strong> Kumpulkan jawaban sebelum sesi ujian ditutup oleh guru penguji.
                    </li>
                </ul>
                <a href="index.php?page=petunjuk" class="btn btn-warning" style="width: 100%; font-size: 11.5px; padding: 6px; text-align: center; text-decoration: none; display: block;">
                    ⚠️ Buka Petunjuk Bersihkan Akun Lab
                </a>
            </div>
        </div>

    </div>

</div>

<script>
function copyCodeBlock(btn) {
    const pre = btn.parentElement.querySelector('pre');
    if (!pre) return;
    const text = pre.innerText;
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '✅ Berhasil Disalin!';
        btn.style.backgroundColor = '#15803d';
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.style.backgroundColor = '#334155';
        }, 2200);
    }).catch(err => {
        alert('Gagal menyalin kode: ' + err);
    });
}
</script>

<?php require_once __DIR__ . '/layout_footer.php'; ?>