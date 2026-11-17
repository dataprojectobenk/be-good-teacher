<?php
// praktikum/src/views/reset_password.php
$pageTitle = "Reset Kata Sandi Siswa";
require_once __DIR__ . '/layout_header.php';
?>

<div style="max-width: 460px; margin: 35px auto;">
    <div class="card" style="box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
        <div class="card-header" style="text-align: center; display: block; padding: 22px 20px;">
            <div style="font-size: 32px; margin-bottom: 6px;">🔑</div>
            <h2 style="font-size: 20px; color: var(--primary);">Reset Kata Sandi Mandiri</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                Masukkan NIS Anda untuk mengatur ulang kata sandi baru
            </p>
        </div>

        <div class="card-body">
            <form action="index.php?action=reset_password" method="POST">
                <div class="form-group">
                    <label for="nis">Nomor Induk Siswa (NIS)</label>
                    <input type="text" id="nis" name="nis" class="form-control" placeholder="Masukkan NIS Anda" required autofocus>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label for="new_password">Sandi Baru</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Ulangi Sandi</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi sandi baru" required>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; margin-bottom: 18px; font-size: 11.5px; color: #475569;">
                    <div style="font-weight: 600; color: #334155; margin-bottom: 2px;">🛡️ Verifikasi Cepat NIS:</div>
                    <div>Sistem akan memeriksa keabsahan NIS Anda di basis data siswa dan langsung memperbarui kata sandi baru akun Anda.</div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 11px; font-size: 14px;">
                    Simpan Kata Sandi Baru
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 16px; display: flex; justify-content: space-between;">
                <a href="index.php?page=login" style="color: var(--primary); font-weight: 600; text-decoration: none;">&larr; Masuk ke Akun</a>
                <a href="index.php?page=register" style="color: #64748b; text-decoration: none;">Daftar Akun Baru</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
