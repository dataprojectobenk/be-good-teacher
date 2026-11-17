<?php
// praktikum/src/views/register.php
$pageTitle = "Pendaftaran Siswa Baru";
require_once __DIR__ . '/layout_header.php';
?>

<div style="max-width: 480px; margin: 30px auto;">
    <div class="card" style="box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
        <div class="card-header" style="text-align: center; display: block; padding: 22px 20px;">
            <div style="font-size: 32px; margin-bottom: 6px;">📝</div>
            <h2 style="font-size: 20px; color: var(--primary);">Registrasi Siswa Lab</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Daftarkan identitas Anda sebelum memulai praktikum</p>
        </div>

        <div class="card-body">
            <form action="index.php?action=register" method="POST">
                <div class="form-group">
                    <label for="nis">Nomor Induk Siswa (NIS)</label>
                    <input type="text" id="nis" name="nis" class="form-control" placeholder="Contoh: 23241001" required autofocus>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap Siswa</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Ahmad Maulana" required>
                </div>

                <div class="form-group">
                    <label for="kelas">Kelas & Jurusan</label>
                    <select id="kelas" name="kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        <option value="XI-PPLG">XI-PPLG</option>
                        <option value="XII-PPLG">XII-PPLG</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Ulangi Sandi</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Ketik ulang sandi" required>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; margin-bottom: 18px; font-size: 11.5px; color: #475569;">
                    <div style="font-weight: 600; color: #334155; margin-bottom: 3px;">🛰️ Perangkat Komputer Terkunci:</div>
                    <div>IP: <strong><?= htmlspecialchars($clientIp) ?></strong> (<?= htmlspecialchars($clientHost) ?>)</div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Akun akan ditautkan dengan komputer ini selama sesi lab berlangsung.</div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 11px; font-size: 14px;">
                    Daftar Akun Siswa
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                Sudah pernah mendaftar? <a href="index.php?page=login" style="color: var(--primary); font-weight: 600; text-decoration: none;">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
