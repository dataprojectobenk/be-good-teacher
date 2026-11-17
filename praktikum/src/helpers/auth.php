<?php
// praktikum/src/helpers/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

function isAdmin(): bool {
    return isLoggedIn() && ($_SESSION['user']['role'] ?? '') === 'admin';
}

function currentUser(): ?array {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
        return null;
    }
    // Auto-rehydrate profil siswa dari basis data jika data kelas belum ada di sesi
    if (empty($_SESSION['user']['kelas']) && function_exists('getDatabaseConnection')) {
        try {
            $pdo = getDatabaseConnection();
            $stmt = $pdo->prepare("SELECT id, nis, nama, kelas, role FROM users WHERE id = :id");
            $stmt->execute([':id' => (int)$_SESSION['user']['id']]);
            $dbUser = $stmt->fetch();
            if ($dbUser) {
                $_SESSION['user']['kelas'] = $dbUser['kelas'];
                $_SESSION['user']['nama'] = $dbUser['nama'];
                $_SESSION['user']['nis'] = $dbUser['nis'];
                $_SESSION['user']['role'] = $dbUser['role'];
            }
            $stmt->closeCursor();
        } catch (Throwable $e) {
            // Abaikan kesalahan koneksi sementara
        }
    }
    return $_SESSION['user'];
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Silakan masuk terlebih dahulu untuk mengakses sistem.');
        header('Location: index.php?page=login');
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        setFlashMessage('error', 'Akses ditolak! Halaman ini hanya untuk Guru / Administrator.');
        header('Location: index.php?page=dashboard');
        exit;
    }
}

function setFlashMessage(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'error', 'info', 'warning'
        'message' => $message
    ];
}

function getFlashMessage(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function sanitize(string $data): string {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi tautan repositori GitHub
 * Contoh valid: https://github.com/username/repository atau https://github.com/username/repository.git
 */
function isValidGithubUrl(string $url): bool {
    $url = trim($url);
    $pattern = '/^(https?:\/\/)?(www\.)?github\.com\/[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-\.]+\/?$/';
    return (bool)preg_match($pattern, $url);
}

/**
 * Ekstraksi tingkat (XI atau XII) berdasarkan nama kelas siswa
 * Mendukung format: XI-PPLG, XI RPL 1, 11 PPLG => 'XI'; XII-PPLG, XII RPL 2, 12 PPLG => 'XII'
 */
function getStudentTingkat(string $kelas): string {
    $upper = strtoupper(trim($kelas));
    if (strpos($upper, 'XII') === 0 || strpos($upper, '12') === 0 || strpos($upper, 'KELAS 12') !== false || strpos($upper, 'KELAS XII') !== false) {
        return 'XII';
    }
    if (strpos($upper, 'XI') === 0 || strpos($upper, '11') === 0 || strpos($upper, 'KELAS 11') !== false || strpos($upper, 'KELAS XI') !== false) {
        return 'XI';
    }
    if (strpos($upper, 'XII') !== false) {
        return 'XII';
    }
    if (strpos($upper, 'XI') !== false) {
        return 'XI';
    }
    return 'XI';
}
