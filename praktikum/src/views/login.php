<?php
// praktikum/src/views/login.php
$pageTitle = "Masuk Sistem Lab";
require_once __DIR__ . '/layout_header.php';
?>

<div style="max-width: 440px; margin: 40px auto;">
    <div class="card" style="box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
        <div class="card-header" style="text-align: center; display: block; padding: 22px 20px;">
            <div style="font-size: 32px; margin-bottom: 6px;">🔐</div>
            <h2 style="font-size: 20px; color: var(--primary);">Masuk Lab Classroom</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Akses ujian praktikum dan pengumpulan tugas</p>
        </div>

        <div class="card-body">
            <form action="index.php?action=login" method="POST">
                <div class="form-group">
                    <label for="nis">NIS / Username</label>
                    <input type="text" id="nis" name="nis" class="form-control" placeholder="Masukkan NIS Anda atau 'admin'" required autofocus>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="password" style="margin-bottom: 0;">Kata Sandi</label>
                        <a href="index.php?page=reset_password" style="font-size: 12px; color: var(--primary); text-decoration: none;">Lupa sandi?</a>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required>
                </div>

                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; margin-bottom: 18px; font-size: 11.5px; color: #475569;">
                    <div style="font-weight: 600; color: #334155; margin-bottom: 3px;">🛰️ Telemetri Komputer Lab Terdeteksi:</div>
                    <div>IP Jaringan: <strong><?= htmlspecialchars($clientIp) ?></strong></div>
                    <div>Nama Komputer: <strong><?= htmlspecialchars($clientHost) ?></strong></div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 11px; font-size: 14px;">
                    Masuk ke Sistem
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                Siswa belum memiliki akun? <a href="index.php?page=register" style="color: var(--primary); font-weight: 600; text-decoration: none;">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
