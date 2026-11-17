<?php
// praktikum/src/helpers/telemetry.php

function getClientIP(): string {
    $ipKeys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function getClientHostname(string $ip): string {
    static $cache = [];
    if (isset($cache[$ip])) {
        return $cache[$ip];
    }

    if ($ip === '127.0.0.1' || $ip === '::1') {
        return $cache[$ip] = 'localhost (' . (gethostname() ?: 'HOST-SERVER') . ')';
    }

    // Resolusi instan berbasis segmen IP LAN (0ms) untuk menghindari DNS PTR timeout (~5s)
    $cleanIp = str_replace(['.', ':'], '-', $ip);
    $lastDot = strrpos($ip, '.');
    $suffix = ($lastDot !== false) ? substr($ip, $lastDot + 1) : substr($cleanIp, -4);

    return $cache[$ip] = 'PC-LAN-' . $suffix;
}

function getClientUserAgent(): string {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown User-Agent';
}

function getClientPCUser(): string {
    // Tangkap data identitas PC jika dikirim via header atau form input lab
    if (!empty($_POST['client_pc_user'])) {
        return htmlspecialchars(trim($_POST['client_pc_user']), ENT_QUOTES, 'UTF-8');
    }
    if (!empty($_SESSION['client_pc_user'])) {
        return $_SESSION['client_pc_user'];
    }
    return 'Lab-User';
}

/**
 * Memeriksa apakah ada beberapa siswa berbeda yang mengumpulkan tugas dari IP atau Hostname yang sama.
 * Mengembalikan array daftar IP/Hostname yang terindikasi ganda (duplikat).
 */
function detectDuplicateDevices(PDO $pdo, ?int $soalId = null): array {
    $query = "
        SELECT client_ip, soal_id, COUNT(DISTINCT user_id) as jumlah_siswa
        FROM pengumpulan
    ";
    $params = [];

    if ($soalId !== null) {
        $query .= " WHERE soal_id = :soal_id ";
        $params[':soal_id'] = $soalId;
    }

    $query .= " GROUP BY client_ip, soal_id HAVING COUNT(DISTINCT user_id) > 1";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $duplicates = $stmt->fetchAll();
    $stmt->closeCursor();

    $suspiciousMap = [];
    foreach ($duplicates as $row) {
        $key = $row['client_ip'] . '_soal_' . $row['soal_id'];
        $suspiciousMap[$key] = (int)$row['jumlah_siswa'];
    }

    return $suspiciousMap;
}
