<?php
// praktikum/src/views/view_sql.php
requireLogin();
$user = currentUser();

$soalId = (int)($_GET['id'] ?? 0);
$pdo = getDatabaseConnection();

$stmt = $pdo->prepare("SELECT * FROM soal WHERE id = :id");
$stmt->execute([':id' => $soalId]);
$soal = $stmt->fetch();

if (!$soal || empty($soal['file_sql_path'])) {
    setFlashMessage('error', 'Data dummy SQL untuk soal ini tidak ditemukan.');
    header('Location: index.php?page=dashboard');
    exit;
}

// Hak akses: Admin bebas akses, siswa HANYA jika soal aktif DAN sesuai tingkat kelasnya
if (!isAdmin()) {
    $studentTingkat = getStudentTingkat($user['kelas'] ?? '');
    if (!$soal['is_active'] || $soal['tingkat'] !== $studentTingkat) {
        setFlashMessage('error', 'Akses ditolak! Data SQL ini tidak tersedia untuk tingkat kelas Anda (' . htmlspecialchars($user['kelas'] ?? '') . ') atau sedang dinonaktifkan.');
        header('Location: index.php?page=dashboard');
        exit;
    }
}

$pageTitle = "Data Dummy SQL: " . $soal['judul'];
require_once __DIR__ . '/layout_header.php';

$sqlFilePath = __DIR__ . '/../../' . $soal['file_sql_path'];
$sqlContent = file_exists($sqlFilePath) ? file_get_contents($sqlFilePath) : "-- Berkas SQL tidak ditemukan pada path: " . htmlspecialchars($soal['file_sql_path']);
$sqlFileName = basename($soal['file_sql_path']);
?>

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="index.php?page=dashboard" class="btn btn-secondary">
            &larr; Kembali ke Dashboard
        </a>
        <a href="index.php?page=soal&id=<?= $soal['id'] ?>" class="btn btn-secondary">
            📖 Buka Naskah Soal (.md)
        </a>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" onclick="copySqlToClipboard()" class="btn btn-warning" id="btnCopy" style="background-color: #d97706; color: white;">
            📋 Salin Seluruh SQL
        </button>
        <a href="index.php?action=download_sql&id=<?= $soal['id'] ?>" class="btn btn-primary">
            💾 Unduh Berkas <?= htmlspecialchars($sqlFileName) ?>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header" style="background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span class="badge badge-info" style="margin-right: 6px;"><?= htmlspecialchars($soal['sesi']) ?></span>
            <span class="badge badge-secondary"><?= htmlspecialchars($soal['kategori']) ?></span>
            <h1 style="font-size: 18px; color: var(--primary-dark); margin-top: 6px;">
                🗄️ Skrip Data Dummy SQL: <code><?= htmlspecialchars($sqlFileName) ?></code>
            </h1>
        </div>
        <div>
            <span style="font-size: 12px; color: #64748b;">Ukuran: <?= strlen($sqlContent) ?> bytes</span>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div style="background-color: #0f172a; color: #f8fafc; padding: 20px; border-radius: 0 0 10px 10px; overflow-x: auto; font-family: 'Consolas', 'Courier New', monospace; font-size: 13.5px; line-height: 1.6;">
            <pre id="sqlText" style="margin: 0; white-space: pre-wrap; word-break: break-all;"><code><?= htmlspecialchars($sqlContent) ?></code></pre>
        </div>
    </div>
</div>

<script>
function copySqlToClipboard() {
    const text = document.getElementById('sqlText').innerText;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('btnCopy');
        const origText = btn.innerHTML;
        btn.innerHTML = '✅ Berhasil Disalin!';
        btn.style.backgroundColor = '#15803d';
        setTimeout(() => {
            btn.innerHTML = origText;
            btn.style.backgroundColor = '#d97706';
        }, 2500);
    }).catch(err => {
        alert('Gagal menyalin teks: ' + err);
    });
}
</script>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
