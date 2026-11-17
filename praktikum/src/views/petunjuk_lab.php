<?php
// praktikum/src/views/petunjuk_lab.php
$pageTitle = "Prosedur Wajib Lab: Pembersihan Akun Komputer";
require_once __DIR__ . '/layout_header.php';
$isUserLoggedIn = isLoggedIn();
?>

<div style="max-width: 880px; margin: 10px auto 40px;">

    <!-- Banner Selamat Datang & Peringatan Utama -->
    <div style="background: linear-gradient(135deg, #1e3a8a, #0f172a); color: #ffffff; padding: 32px 30px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 4px 18px rgba(30, 58, 138, 0.25);">
        <span class="badge" style="background-color: #f59e0b; color: #78350f; font-weight: 800; font-size: 12px; padding: 5px 12px; margin-bottom: 12px;">
            LANGKAH 1: WAJIB DIBACA SEBELUM UJIAN
        </span>
        <h1 style="font-size: 24px; margin-bottom: 10px; line-height: 1.3;">
            Selamat Datang di Lab Classroom Praktikum RPL
        </h1>
        <p style="font-size: 14.5px; color: #cbd5e1; line-height: 1.6; margin-bottom: 20px;">
            Komputer laboratorium digunakan bergantian antar-kelas. Sebelum Anda mengerjakan praktikum, Anda <strong>WAJIB</strong> memastikan akun GitHub milik siswa sesi sebelumnya telah dibersihkan dari sistem operasi Windows dan Visual Studio Code.
        </p>

        <!-- Tombol Aksi Cepat di Header -->
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <?php if ($isUserLoggedIn): ?>
                <a href="index.php?page=dashboard" class="btn btn-success" style="padding: 11px 22px; font-size: 14px; font-weight: 700;">
                    📋 Menuju Dashboard Soal Praktikum &rarr;
                </a>
            <?php else: ?>
                <a href="index.php?page=login" class="btn btn-warning" style="background-color: #f59e0b; color: #78350f; padding: 11px 22px; font-size: 14px; font-weight: 700;">
                    🔑 Masuk (Login) ke Sistem
                </a>
                <a href="index.php?page=register" class="btn btn-secondary" style="background-color: rgba(255,255,255,0.2); color: #ffffff; padding: 11px 20px; font-size: 14px;">
                    📝 Daftar Siswa Baru
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Kotak Bahaya / Risiko -->
    <div style="background-color: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 10px; padding: 18px 20px; margin-bottom: 25px;">
        <h3 style="color: #9f1239; font-size: 15px; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
            ⚠️ Mengapa Pembersihan Akun Ini Wajib Dilakukan?
        </h3>
        <p style="color: #881337; font-size: 13.5px; line-height: 1.6;">
            Jika kredensial siswa sebelumnya masih tersimpan di komputer lab, ketika Anda melakukan <code>git push</code>, proyek tugas Anda akan <strong>terunggah menggunakan akun GitHub siswa lain</strong>. Akibatnya, nilai praktikum Anda tidak dapat tercatat atau dicurigai sebagai pelanggaran integritas!
        </p>
    </div>

    <!-- Langkah 1: Windows Credential -->
    <div class="card">
        <div class="card-header" style="background-color: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="background: #1e3a8a; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">1</span>
                <span class="card-title" style="font-size: 16px;">Hapus Kredensial GitHub di Windows Credentials</span>
            </div>
            <span class="badge badge-warning">Langkah Kritis</span>
        </div>
        <div class="card-body">
            <ol style="margin-left: 20px; font-size: 14px; line-height: 1.8; color: #334155;">
                <li>Tekan tombol kombinasi keyboard <kbd style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-family: monospace;">Windows + S</kbd> di PC lab Anda.</li>
                <li>Ketik <strong>Credential Manager</strong> (atau <em>Pengelola Kredensial</em>) lalu tekan <kbd>Enter</kbd>.</li>
                <li>Pilih tab <strong>Windows Credentials</strong> (Kredensial Windows).</li>
                <li>Gulir ke bawah pada bagian <strong>Generic Credentials</strong>.</li>
                <li>Cari dan temukan entri yang bernama <strong><code>git:https://github.com</code></strong> atau entri yang mengandung kata <code>github</code> / <code>vscode</code>.</li>
                <li>Klik tanda panah ke bawah pada baris tersebut, lalu klik tombol <strong>Remove</strong> (Hapus).</li>
                <li>Klik tombol <strong>Yes</strong> saat muncul konfirmasi penghapusan.</li>
            </ol>
            <div style="margin-top: 14px; background: #f8fafc; border: 1px dashed #cbd5e1; padding: 12px; border-radius: 6px; font-size: 12.5px; color: #475569;">
                💡 <em>Hasil yang diharapkan: Git di Windows akan meminta Anda login ke akun GitHub pribadi Anda saat perintah push pertama dijalankan.</em>
            </div>
        </div>
    </div>

    <!-- Langkah 2: VS Code Sign Out -->
    <div class="card">
        <div class="card-header" style="background-color: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="background: #1e3a8a; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">2</span>
                <span class="card-title" style="font-size: 16px;">Sign Out Akun GitHub di Visual Studio Code</span>
            </div>
            <span class="badge badge-warning">Langkah Kritis</span>
        </div>
        <div class="card-body">
            <ol style="margin-left: 20px; font-size: 14px; line-height: 1.8; color: #334155;">
                <li>Buka aplikasi <strong>Visual Studio Code</strong> di komputer Anda.</li>
                <li>Lihat ke <strong>pojok kiri paling bawah</strong> jendela VS Code, klik ikon <strong>Akun (Ikon gambar orang)</strong>.</li>
                <li>Jika terdapat nama akun GitHub yang sedang aktif (nama siswa sesi sebelumnya), klik nama akun tersebut lalu pilih <strong>Sign Out</strong>.</li>
                <li>Buka tab <strong>Source Control</strong> (<kbd>Ctrl + Shift + G</kbd>). Pastikan tidak ada akun tertaut yang bukan milik Anda.</li>
                <li>Ketika Anda mempublikasikan repositori nanti, masuklah menggunakan akun GitHub pribadi Anda sendiri.</li>
            </ol>
        </div>
    </div>

    <!-- Langkah 3: Git Identity -->
    <div class="card">
        <div class="card-header" style="background-color: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="background: #1e3a8a; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">3</span>
                <span class="card-title" style="font-size: 16px;">Setel Identitas Git Pribadi di Terminal Komputer</span>
            </div>
            <span class="badge badge-info">Identitas Siswa</span>
        </div>
        <div class="card-body">
            <p style="font-size: 14px; color: #334155; margin-bottom: 12px;">
                Buka Terminal VS Code atau Command Prompt komputer lab dan jalankan perintah berikut:
            </p>
            <div style="background-color: #0f172a; color: #f8fafc; padding: 14px 18px; border-radius: 8px; font-family: monospace; font-size: 13px; margin-bottom: 15px; line-height: 1.8;">
                <div><span style="color: #64748b;"># Masukkan nama lengkap dan email Anda sendiri:</span></div>
                <div>git config --global user.name "<span style="color: #38bdf8;">Nama Lengkap Anda</span>"</div>
                <div>git config --global user.email "<span style="color: #38bdf8;">email.anda@gmail.com</span>"</div>
            </div>
            <p style="font-size: 13px; color: #64748b;">
                Periksa apakah identitas sudah tersimpan dengan perintah: <code>git config --list</code>.
            </p>
        </div>
    </div>

    <!-- Tombol Konfirmasi Selesai di Bawah Halaman -->
    <div style="text-align: center; margin: 35px 0 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; padding: 25px;">
        <h3 style="font-size: 17px; color: var(--primary-dark); margin-bottom: 8px;">Sudah Menyelesaikan 3 Langkah di Atas?</h3>
        <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 18px;">
            Lanjutkan ke sistem untuk melihat naskah soal dan mulai praktikum.
        </p>

        <?php if ($isUserLoggedIn): ?>
            <a href="index.php?page=dashboard" class="btn btn-success" style="padding: 13px 30px; font-size: 15px; font-weight: 700; box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);">
                ✅ Saya Sudah Membersihkan Akun &rarr; Buka Dashboard Soal
            </a>
        <?php else: ?>
            <div style="display: inline-flex; gap: 12px; flex-wrap: wrap; justify-content: center;">
                <a href="index.php?page=login" class="btn btn-primary" style="padding: 13px 28px; font-size: 15px; font-weight: 700;">
                    🔑 Masuk ke Akun Saya (Login)
                </a>
                <a href="index.php?page=register" class="btn btn-secondary" style="padding: 13px 26px; font-size: 15px; font-weight: 600;">
                    📝 Belum Punya Akun? Registrasi Siswa
                </a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
