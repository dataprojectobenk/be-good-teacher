<?php
// praktikum/src/views/layout_footer.php
$ip = $clientIp ?? getClientIP();
$host = $clientHost ?? getClientHostname($ip);
?>
    </div> <!-- .main-container -->

    <footer style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 18px 24px; font-size: 12px; color: #64748b; text-align: center; margin-top: auto;">
        <div style="max-width: 1140px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: gap; gap: 10px;">
            <div>
                &copy; <?= date('Y') ?> <strong>Lab Rekayasa Perangkat Lunak</strong> — Sistem Praktikum Jaringan Lokal
            </div>
            <div style="font-family: monospace; background: #f1f5f9; padding: 4px 10px; border-radius: 4px; border: 1px solid #e2e8f0;">
                Terhubung dari: <strong><?= htmlspecialchars($ip) ?></strong> (<?= htmlspecialchars($host) ?>)
            </div>
        </div>
    </footer>

</body>
</html>
