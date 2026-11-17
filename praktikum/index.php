<?php
// praktikum/index.php
// Front Controller & Router Lab Classroom Praktikum RPL

require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/helpers/auth.php';
require_once __DIR__ . '/src/helpers/telemetry.php';

$pdo = getDatabaseConnection();
$action = $_GET['action'] ?? '';
$page = $_GET['page'] ?? '';
$user = currentUser();

// ==========================================
// 1. PENANGANAN AKSI POST / GET (ACTIONS)
// ==========================================

// Aksi: Registrasi Siswa Baru
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = sanitize($_POST['nis'] ?? '');
    $nama = sanitize($_POST['nama'] ?? '');
    $kelas = sanitize($_POST['kelas'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (empty($nis) || empty($nama) || empty($kelas) || empty($password)) {
        setFlashMessage('error', 'Semua kolom formulir pendaftaran wajib diisi.');
        header('Location: index.php?page=register');
        exit;
    }

    if ($password !== $passwordConfirm) {
        setFlashMessage('error', 'Konfirmasi kata sandi tidak cocok.');
        header('Location: index.php?page=register');
        exit;
    }

    if (strlen($password) < 6) {
        setFlashMessage('error', 'Kata sandi minimal harus berjumlah 6 karakter.');
        header('Location: index.php?page=register');
        exit;
    }

    $allowedKelas = ['XI-PPLG', 'XII-PPLG'];
    if (!in_array($kelas, $allowedKelas, true)) {
        setFlashMessage('error', 'Pilihan kelas tidak valid. Harap pilih XI-PPLG atau XII-PPLG.');
        header('Location: index.php?page=register');
        exit;
    }

    $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM users WHERE nis = :nis");
    $stmtCek->execute([':nis' => $nis]);
    $userExists = ((int)$stmtCek->fetchColumn()) > 0;
    $stmtCek->closeCursor();

    if ($userExists) {
        setFlashMessage('error', 'NIS tersebut sudah terdaftar di sistem.');
        header('Location: index.php?page=register');
        exit;
    }

    $ip = getClientIP();
    $host = getClientHostname($ip);

    $stmtInsert = $pdo->prepare("
        INSERT INTO users (nis, nama, kelas, password, role, registered_ip, registered_hostname)
        VALUES (:nis, :nama, :kelas, :password, 'siswa', :ip, :host)
    ");
    $stmtInsert->execute([
        ':nis' => $nis,
        ':nama' => $nama,
        ':kelas' => $kelas,
        ':password' => password_hash($password, PASSWORD_BCRYPT),
        ':ip' => $ip,
        ':host' => $host
    ]);
    $stmtInsert->closeCursor();

    setFlashMessage('success', 'Pendaftaran akun berhasil! Silakan masuk menggunakan NIS Anda.');
    header('Location: index.php?page=login');
    exit;
}

// Aksi: Masuk (Login)
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = sanitize($_POST['nis'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nis) || empty($password)) {
        setFlashMessage('error', 'NIS / Username dan Kata Sandi wajib diisi.');
        header('Location: index.php?page=login');
        exit;
    }

    $stmtUser = $pdo->prepare("SELECT * FROM users WHERE nis = :nis LIMIT 1");
    $stmtUser->execute([':nis' => $nis]);
    $user = $stmtUser->fetch();
    $stmtUser->closeCursor();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nis' => $user['nis'],
            'nama' => $user['nama'],
            'kelas' => $user['kelas'],
            'role' => $user['role']
        ];
        $_SESSION['client_pc_user'] = getClientHostname(getClientIP());

        setFlashMessage('success', 'Selamat datang, ' . htmlspecialchars($user['nama']) . '!');
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        setFlashMessage('error', 'NIS atau Kata Sandi yang Anda masukkan salah.');
        header('Location: index.php?page=login');
        exit;
    }
}

// Aksi: Reset Kata Sandi Mandiri (Khusus Siswa - Berbasis NIS)
if ($action === 'reset_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = sanitize($_POST['nis'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($nis) || empty($newPassword)) {
        setFlashMessage('error', 'NIS dan kata sandi baru wajib diisi.');
        header('Location: index.php?page=reset_password');
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        setFlashMessage('error', 'Konfirmasi kata sandi baru tidak cocok.');
        header('Location: index.php?page=reset_password');
        exit;
    }

    if (strlen($newPassword) < 6) {
        setFlashMessage('error', 'Kata sandi baru minimal harus berjumlah 6 karakter.');
        header('Location: index.php?page=reset_password');
        exit;
    }

    // Cari data siswa berdasarkan NIS
    $stmtCek = $pdo->prepare("SELECT id, nis, nama, role FROM users WHERE nis = :nis LIMIT 1");
    $stmtCek->execute([':nis' => $nis]);
    $user = $stmtCek->fetch();
    $stmtCek->closeCursor();

    // Verifikasi: Harus ada dan harus role siswa (akun admin dilarang reset dari sini)
    if (!$user || $user['role'] !== 'siswa') {
        setFlashMessage('error', 'NIS siswa tidak terdaftar di sistem laboratorium.');
        header('Location: index.php?page=reset_password');
        exit;
    }

    // Update kata sandi baru
    $stmtUpdate = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmtUpdate->execute([
        ':password' => password_hash($newPassword, PASSWORD_BCRYPT),
        ':id' => $user['id']
    ]);
    $stmtUpdate->closeCursor();

    setFlashMessage('success', 'Kata sandi untuk ' . htmlspecialchars($user['nama']) . ' (NIS: ' . htmlspecialchars($user['nis']) . ') berhasil diperbarui! Silakan masuk.');
    header('Location: index.php?page=login');
    exit;
}

// Aksi: Pengumpulan Jawaban (Khusus Siswa)
if ($action === 'submit_jawaban' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireLogin();
    $user = currentUser();
    $soalId = (int)($_POST['soal_id'] ?? 0);
    $catatan = sanitize($_POST['catatan'] ?? '');
    $clientPcUser = sanitize($_POST['client_pc_user'] ?? getClientHostname(getClientIP()));
    $redirectTarget = sanitize($_POST['redirect'] ?? 'dashboard');
    $redirectUrl = ($redirectTarget === 'soal' && $soalId > 0) ? 'index.php?page=soal&id=' . $soalId : 'index.php?page=dashboard';

    $stmtSoal = $pdo->prepare("SELECT * FROM soal WHERE id = :id AND is_active = 1");
    $stmtSoal->execute([':id' => $soalId]);
    $soal = $stmtSoal->fetch();
    $stmtSoal->closeCursor();

    if (!$soal) {
        setFlashMessage('error', 'Sesi soal tidak ditemukan atau sedang tidak aktif.');
        header('Location: ' . $redirectUrl);
        exit;
    }

    // Siswa hanya boleh mengumpulkan tugas sesuai tingkat kelasnya
    if (!isAdmin()) {
        $studentTingkat = getStudentTingkat($user['kelas'] ?? '');
        if ($soal['tingkat'] !== $studentTingkat) {
            setFlashMessage('error', 'Akses ditolak! Anda hanya dapat mengumpulkan tugas praktikum Tingkat ' . htmlspecialchars($studentTingkat) . ' sesuai kelas Anda.');
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    $tipeJawaban = $soal['tipe_pengumpulan'];
    $jawabanKonten = '';

    if ($tipeJawaban === 'github_link') {
        $githubUrl = trim($_POST['github_url'] ?? '');
        if (empty($githubUrl) || !isValidGithubUrl($githubUrl)) {
            setFlashMessage('error', 'Format tautan GitHub tidak valid. Harap masukkan tautan repositori yang benar (contoh: https://github.com/username/nama-repo).');
            header('Location: ' . $redirectUrl);
            exit;
        }
        $jawabanKonten = $githubUrl;
    } else {
        if (!isset($_FILES['file_jawaban']) || $_FILES['file_jawaban']['error'] !== UPLOAD_ERR_OK) {
            setFlashMessage('error', 'Silakan pilih berkas jawaban SQL (.sql) untuk diunggah.');
            header('Location: ' . $redirectUrl);
            exit;
        }

        $fileTmp = $_FILES['file_jawaban']['tmp_name'];
        $fileName = $_FILES['file_jawaban']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['sql', 'txt', 'zip'];
        if (!in_array($ext, $allowedExts)) {
            setFlashMessage('error', 'Ekstensi file tidak diizinkan. Harap unggah file berekstensi .sql atau .zip.');
            header('Location: ' . $redirectUrl);
            exit;
        }

        $safeFileName = 'JWB_' . $soal['id'] . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $user['nis']) . '_' . time() . '.' . $ext;
        $destPath = __DIR__ . '/src/uploads/' . $safeFileName;

        if (!move_uploaded_file($fileTmp, $destPath)) {
            setFlashMessage('error', 'Gagal menyimpan berkas ke server laboratorium.');
            header('Location: ' . $redirectUrl);
            exit;
        }
        $jawabanKonten = $safeFileName;
    }

    $clientIp = getClientIP();
    $clientHost = getClientHostname($clientIp);
    $userAgent = getClientUserAgent();

    $stmtCekSub = $pdo->prepare("SELECT id FROM pengumpulan WHERE user_id = :user_id AND soal_id = :soal_id");
    $stmtCekSub->execute([':user_id' => $user['id'], ':soal_id' => $soalId]);
    $existingId = $stmtCekSub->fetchColumn();
    $stmtCekSub->closeCursor();

    if ($existingId) {
        $stmtUpdate = $pdo->prepare("
            UPDATE pengumpulan 
            SET tipe_jawaban = :tipe, jawaban_konten = :konten, catatan_siswa = :catatan,
                client_ip = :ip, client_hostname = :host, client_pc_user = :pc_user,
                user_agent = :ua, waktu_kirim = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        $stmtUpdate->execute([
            ':tipe' => $tipeJawaban,
            ':konten' => $jawabanKonten,
            ':catatan' => $catatan,
            ':ip' => $clientIp,
            ':host' => $clientHost,
            ':pc_user' => $clientPcUser,
            ':ua' => $userAgent,
            ':id' => $existingId
        ]);
        $stmtUpdate->closeCursor();
        setFlashMessage('success', 'Jawaban berhasil diperbarui!');
    } else {
        $stmtInsert = $pdo->prepare("
            INSERT INTO pengumpulan 
            (user_id, soal_id, tipe_jawaban, jawaban_konten, catatan_siswa, client_ip, client_hostname, client_pc_user, user_agent)
            VALUES (:user_id, :soal_id, :tipe, :konten, :catatan, :ip, :host, :pc_user, :ua)
        ");
        $stmtInsert->execute([
            ':user_id' => $user['id'],
            ':soal_id' => $soalId,
            ':tipe' => $tipeJawaban,
            ':konten' => $jawabanKonten,
            ':catatan' => $catatan,
            ':ip' => $clientIp,
            ':host' => $clientHost,
            ':pc_user' => $clientPcUser,
            ':ua' => $userAgent
        ]);
        $stmtInsert->closeCursor();
        setFlashMessage('success', 'Jawaban praktikum berhasil dikirimkan!');
    }

    header('Location: ' . $redirectUrl);
    exit;
}

// Aksi: Saklar Aktifkan / Nonaktifkan Soal (Khusus Guru / Admin)
if ($action === 'toggle_soal' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    $soalId = (int)($_POST['soal_id'] ?? 0);
    $status = (int)($_POST['status'] ?? 0);
    $tingkat = sanitize($_POST['tingkat'] ?? '');

    $stmt = $pdo->prepare("UPDATE soal SET is_active = :status WHERE id = :id");
    $stmt->execute([':status' => $status, ':id' => $soalId]);
    $stmt->closeCursor();

    setFlashMessage('info', 'Status aktif soal berhasil diperbarui.');
    $redirectUrl = 'index.php?page=kontrol_sesi' . (!empty($tingkat) && $tingkat !== 'ALL' ? '&tingkat=' . urlencode($tingkat) : '');
    header('Location: ' . $redirectUrl);
    exit;
}

// Aksi: Penilaian Jawaban Siswa (Khusus Guru / Admin)
if ($action === 'nilai_jawaban' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    $pengumpulanId = (int)($_POST['pengumpulan_id'] ?? 0);
    $nilai = (int)($_POST['nilai'] ?? 0);
    $feedback = sanitize($_POST['feedback'] ?? '');
    $filterSoal = (int)($_POST['filter_soal'] ?? 0);

    $stmt = $pdo->prepare("UPDATE pengumpulan SET nilai = :nilai, feedback_guru = :feedback WHERE id = :id");
    $stmt->execute([
        ':nilai' => $nilai,
        ':feedback' => $feedback,
        ':id' => $pengumpulanId
    ]);
    $stmt->closeCursor();

    setFlashMessage('success', 'Nilai dan umpan balik berhasil disimpan.');
    $redirectUrl = 'index.php?page=pengumpulan' . ($filterSoal > 0 ? '&filter_soal=' . $filterSoal : '');
    header('Location: ' . $redirectUrl);
    exit;
}

// Aksi: Unduh Berkas Soal Asli (.md)
if ($action === 'download_soal') {
    requireLogin();
    $soalId = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM soal WHERE id = :id");
    $stmt->execute([':id' => $soalId]);
    $soal = $stmt->fetch();
    $stmt->closeCursor();

    if ($soal) {
        // Siswa hanya boleh mengunduh soal aktif sesuai tingkat kelasnya
        if (!isAdmin()) {
            $user = currentUser();
            $studentTingkat = getStudentTingkat($user['kelas'] ?? '');
            if (!$soal['is_active'] || $soal['tingkat'] !== $studentTingkat) {
                setFlashMessage('error', 'Akses ditolak! Berkas soal tidak tersedia untuk kelas Anda atau sedang dinonaktifkan.');
                header('Location: index.php?page=dashboard');
                exit;
            }
        }

        $filePath = __DIR__ . '/' . $soal['file_soal_path'];
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        }
    }
    setFlashMessage('error', 'Berkas soal tidak ditemukan di server.');
    header('Location: index.php?page=dashboard');
    exit;
}

// Aksi: Unduh Berkas Data Dummy SQL (.sql)
if ($action === 'download_sql') {
    requireLogin();
    $soalId = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM soal WHERE id = :id");
    $stmt->execute([':id' => $soalId]);
    $soal = $stmt->fetch();
    $stmt->closeCursor();

    if ($soal && !empty($soal['file_sql_path'])) {
        // Siswa hanya boleh mengunduh data SQL aktif sesuai tingkat kelasnya
        if (!isAdmin()) {
            $user = currentUser();
            $studentTingkat = getStudentTingkat($user['kelas'] ?? '');
            if (!$soal['is_active'] || $soal['tingkat'] !== $studentTingkat) {
                setFlashMessage('error', 'Akses ditolak! Berkas SQL tidak tersedia untuk kelas Anda atau sedang dinonaktifkan.');
                header('Location: index.php?page=dashboard');
                exit;
            }
        }

        $filePath = __DIR__ . '/' . $soal['file_sql_path'];
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        }
    }
    setFlashMessage('error', 'Berkas data dummy SQL tidak ditemukan.');
    header('Location: index.php?page=dashboard');
    exit;
}

// Aksi: Unduh Starter Base App Laravel (base_app.zip)
if ($action === 'download_base_app') {
    requireLogin();
    $filePath = __DIR__ . '/xii/base_app.zip';
    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="base_app.zip"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
    setFlashMessage('error', 'Berkas base_app.zip tidak ditemukan di server.');
    header('Location: index.php?page=dashboard');
    exit;
}

// Aksi API: Polling Pengumpulan Baru Real-time (Khusus Guru / Admin)
if ($action === 'api_submissions_poll') {
    requireAdmin();
    header('Content-Type: application/json; charset=utf-8');

    $lastId = (int)($_GET['last_id'] ?? 0);
    $filterSoalId = (int)($_GET['filter_soal'] ?? 0);
    $tingkatFilter = isset($_GET['tingkat']) && in_array(strtoupper($_GET['tingkat']), ['XI', 'XII']) ? strtoupper($_GET['tingkat']) : 'ALL';

    // Statistik global terbaru
    $totalPengumpulan = (int)$pdo->query("SELECT COUNT(*) FROM pengumpulan")->fetchColumn();
    $totalSiswa = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
    $totalSoalAktif = (int)$pdo->query("SELECT COUNT(*) FROM soal WHERE is_active = 1")->fetchColumn();
    $maxIdInDb = (int)$pdo->query("SELECT COALESCE(MAX(id), 0) FROM pengumpulan")->fetchColumn();

    // Deteksi duplikasi telemetri
    $duplicateMap = detectDuplicateDevices($pdo, $filterSoalId > 0 ? $filterSoalId : null);
    $totalAlerts = count($duplicateMap);

    // Ambil pengumpulan baru jika ada (id > lastId)
    $newItems = [];
    if ($lastId >= 0) {
        $query = "
            SELECT 
                p.*,
                u.nis, u.nama, u.kelas,
                s.judul AS judul_soal, s.tipe_pengumpulan, s.sesi, s.kategori, s.tingkat
            FROM pengumpulan p
            JOIN users u ON p.user_id = u.id
            JOIN soal s ON p.soal_id = s.id
            WHERE p.id > :last_id
        ";
        $params = [':last_id' => $lastId];

        if ($filterSoalId > 0) {
            $query .= " AND p.soal_id = :filter_soal ";
            $params[':filter_soal'] = $filterSoalId;
        }

        if ($tingkatFilter === 'XI') {
            $query .= " AND s.tingkat = 'XI' ";
        } elseif ($tingkatFilter === 'XII') {
            $query .= " AND s.tingkat = 'XII' ";
        }

        $query .= " ORDER BY p.id ASC ";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        foreach ($rows as $r) {
            $checkKey = $r['client_ip'] . '_soal_' . $r['soal_id'];
            $isDuplicate = isset($duplicateMap[$checkKey]) && $duplicateMap[$checkKey] > 1;
            $newItems[] = [
                'id' => (int)$r['id'],
                'user_id' => (int)$r['user_id'],
                'nis' => $r['nis'],
                'nama' => $r['nama'],
                'kelas' => $r['kelas'],
                'soal_id' => (int)$r['soal_id'],
                'judul_soal' => $r['judul_soal'],
                'tingkat' => $r['tingkat'],
                'sesi' => $r['sesi'],
                'kategori' => $r['kategori'],
                'tipe_jawaban' => $r['tipe_jawaban'],
                'jawaban_konten' => $r['jawaban_konten'],
                'catatan_siswa' => $r['catatan_siswa'] ?? '',
                'client_ip' => $r['client_ip'],
                'client_hostname' => $r['client_hostname'],
                'waktu_kirim' => $r['waktu_kirim'],
                'nilai' => $r['nilai'] !== null ? (int)$r['nilai'] : null,
                'feedback_guru' => $r['feedback_guru'] ?? '',
                'is_duplicate' => $isDuplicate,
                'duplicate_count' => $duplicateMap[$checkKey] ?? 0
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'last_id' => $maxIdInDb,
        'total_pengumpulan' => $totalPengumpulan,
        'total_siswa' => $totalSiswa,
        'total_soal_aktif' => $totalSoalAktif,
        'total_alerts' => $totalAlerts,
        'new_items' => $newItems
    ]);
    exit;
}

// Aksi API: Pratinjau Berkas & Detail Jawaban Siswa (Khusus Guru / Admin)
if ($action === 'api_preview_submission') {
    requireAdmin();
    header('Content-Type: application/json; charset=utf-8');

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Parameter ID pengumpulan tidak valid.']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT 
            p.*,
            u.nis, u.nama, u.kelas,
            s.judul AS judul_soal, s.tipe_pengumpulan, s.sesi, s.kategori, s.tingkat
        FROM pengumpulan p
        JOIN users u ON p.user_id = u.id
        JOIN soal s ON p.soal_id = s.id
        WHERE p.id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $p = $stmt->fetch();
    $stmt->closeCursor();

    if (!$p) {
        echo json_encode(['success' => false, 'message' => 'Data pengumpulan tidak ditemukan di sistem.']);
        exit;
    }

    $fileContent = null;
    $fileName = null;
    $fileSize = null;
    $isGithub = ($p['tipe_jawaban'] === 'github_link');

    if (!$isGithub) {
        $fileName = basename($p['jawaban_konten']);
        $filePath = __DIR__ . '/src/uploads/' . $fileName;

        if (file_exists($filePath)) {
            $fileSize = filesize($filePath);
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($ext === 'zip') {
                $fileContent = "[BERKAS ARSIP ZIP]\nNama berkas: " . $fileName . "\nUkuran: " . round($fileSize / 1024, 2) . " KB\n\nPratinjau naskah kode langsung tidak didukung untuk format arsip ZIP. Silakan unduh berkas untuk mengekstrak isi proyek.";
            } elseif ($fileSize > 1048576) {
                // Batasi 1MB agar tidak membebani memori dan browser
                $fileContent = "/* PERINGATAN: Ukuran berkas sangat besar (" . round($fileSize / 1024, 1) . " KB). Menampilkan 1 MB pertama: */\n\n" . file_get_contents($filePath, false, null, 0, 1048576);
            } else {
                $rawContent = file_get_contents($filePath);
                $fileContent = mb_convert_encoding($rawContent, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
            }
        } else {
            $fileContent = "-- PERINGATAN: Berkas tidak ditemukan pada server laboratorium (src/uploads/{$fileName}) --";
        }
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => (int)$p['id'],
            'user_id' => (int)$p['user_id'],
            'nama' => $p['nama'],
            'nis' => $p['nis'],
            'kelas' => $p['kelas'],
            'soal_id' => (int)$p['soal_id'],
            'judul_soal' => $p['judul_soal'],
            'tingkat' => $p['tingkat'],
            'sesi' => $p['sesi'],
            'kategori' => $p['kategori'],
            'tipe_jawaban' => $p['tipe_jawaban'],
            'jawaban_konten' => $p['jawaban_konten'],
            'catatan_siswa' => $p['catatan_siswa'] ?? '',
            'client_ip' => $p['client_ip'],
            'client_hostname' => $p['client_hostname'],
            'client_pc_user' => $p['client_pc_user'] ?? '',
            'waktu_kirim' => $p['waktu_kirim'],
            'nilai' => $p['nilai'] !== null ? (int)$p['nilai'] : null,
            'feedback_guru' => $p['feedback_guru'] ?? '',
            'is_github' => $isGithub,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_size_formatted' => $fileSize !== null ? ($fileSize < 1024 ? $fileSize . ' B' : round($fileSize / 1024, 1) . ' KB') : null,
            'file_content' => $fileContent
        ]
    ]);
    exit;
}

// Aksi API: Simpan Nilai & Feedback Jawaban via AJAX (Khusus Guru / Admin)
if ($action === 'api_save_grade' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    header('Content-Type: application/json; charset=utf-8');

    $input = $_POST;
    if (empty($input)) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        if (is_array($json)) {
            $input = $json;
        }
    }

    $id = (int)($input['id'] ?? $input['pengumpulan_id'] ?? 0);
    $rawNilai = $input['nilai'] ?? null;
    $feedback = sanitize($input['feedback'] ?? $input['feedback_guru'] ?? '');

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID pengumpulan tidak valid.']);
        exit;
    }

    $nilai = null;
    if ($rawNilai !== null && $rawNilai !== '') {
        $nilai = max(0, min(100, (int)$rawNilai));
    }

    $stmt = $pdo->prepare("UPDATE pengumpulan SET nilai = :nilai, feedback_guru = :feedback WHERE id = :id");
    $stmt->execute([
        ':nilai' => $nilai,
        ':feedback' => $feedback,
        ':id' => $id
    ]);
    $stmt->closeCursor();

    echo json_encode([
        'success' => true,
        'message' => 'Nilai dan umpan balik berhasil disimpan!',
        'id' => $id,
        'nilai' => $nilai,
        'feedback' => $feedback
    ]);
    exit;
}

// Aksi: Ekspor Rekap Nilai Siswa ke CSV / Excel (Khusus Guru / Admin)
if ($action === 'export_nilai_csv') {
    requireAdmin();
    $filterKelas = sanitize($_GET['kelas'] ?? 'ALL');
    $filterSoalId = (int)($_GET['soal_id'] ?? 0);

    $query = "
        SELECT 
            u.nis, u.nama, u.kelas,
            s.judul AS judul_soal, s.tingkat, s.sesi,
            p.waktu_kirim, p.nilai, p.feedback_guru
        FROM users u
        LEFT JOIN pengumpulan p ON u.id = p.user_id " . ($filterSoalId > 0 ? "AND p.soal_id = :soal_id" : "") . "
        LEFT JOIN soal s ON p.soal_id = s.id
        WHERE u.role = 'siswa'
    ";
    $params = [];
    if ($filterSoalId > 0) {
        $params[':soal_id'] = $filterSoalId;
    }
    if ($filterKelas !== 'ALL' && !empty($filterKelas)) {
        $query .= " AND u.kelas = :kelas ";
        $params[':kelas'] = $filterKelas;
    }
    $query .= " ORDER BY u.kelas ASC, u.nama ASC, p.waktu_kirim DESC ";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    $stmt->closeCursor();

    $fileName = 'Rekap_Nilai_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filterKelas) . '_' . date('Ymd_His') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $fp = fopen('php://output', 'w');
    // UTF-8 BOM agar Excel membaca karakter khusus dengan benar
    fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

    // Baris Header Kolom
    fputcsv($fp, ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Paket Soal', 'Tingkat', 'Waktu Kirim', 'Nilai (0-100)', 'Status KKM', 'Catatan Guru']);

    $no = 1;
    foreach ($rows as $r) {
        $nilai = $r['nilai'] !== null ? (int)$r['nilai'] : '';
        $statusKkm = '';
        if ($nilai !== '') {
            $statusKkm = $nilai >= 75 ? 'Tuntas' : 'Remedial';
        } else {
            $statusKkm = 'Belum Dinilai / Belum Mengumpulkan';
        }

        fputcsv($fp, [
            $no++,
            $r['nis'],
            $r['nama'],
            $r['kelas'],
            $r['judul_soal'] ?? '-',
            $r['tingkat'] ?? '-',
            $r['waktu_kirim'] ?? '-',
            $nilai,
            $statusKkm,
            $r['feedback_guru'] ?? ''
        ]);
    }

    fclose($fp);
    exit;
}

// ==========================================
// 2. PERUTEAN HALAMAN (PAGE ROUTING)
// ==========================================

switch ($page) {
    case 'login':
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/src/views/login.php';
        break;

    case 'register':
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/src/views/register.php';
        break;

    case 'reset_password':
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/src/views/reset_password.php';
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header('Location: index.php?page=petunjuk');
        exit;

    case 'petunjuk':
        require_once __DIR__ . '/src/views/petunjuk_lab.php';
        break;

    case 'soal':
        require_once __DIR__ . '/src/views/view_soal.php';
        break;

    case 'view_sql':
        requireLogin();
        require_once __DIR__ . '/src/views/view_sql.php';
        break;

    case 'dashboard':
        requireLogin();
        if (isAdmin()) {
            require_once __DIR__ . '/src/views/admin_sesi.php';
        } else {
            require_once __DIR__ . '/src/views/siswa_dashboard.php';
        }
        break;

    case 'kontrol_sesi':
        requireLogin();
        requireAdmin();
        require_once __DIR__ . '/src/views/admin_sesi.php';
        break;

    case 'pengumpulan':
        requireLogin();
        requireAdmin();
        require_once __DIR__ . '/src/views/admin_pengumpulan.php';
        break;

    case 'nilai':
        requireLogin();
        if (isAdmin()) {
            require_once __DIR__ . '/src/views/admin_nilai.php';
        } else {
            require_once __DIR__ . '/src/views/siswa_nilai.php';
        }
        break;

    // Halaman Pertama kali dibuka: Petunjuk Pembersihan Akun
    default:
        require_once __DIR__ . '/src/views/petunjuk_lab.php';
        break;
}
