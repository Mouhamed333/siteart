<?php
/**
 * Art' Afric — page de diagnostic
 * Ouvrez http://localhost/art-afric/public/check.php pour vérifier
 * que votre environnement (PHP, extensions, base de données) est prêt.
 * Vous pouvez supprimer ce fichier une fois le site fonctionnel.
 */

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../app/config/config.php';

$checks = [];

$checks[] = [
    'label' => 'Version de PHP',
    'ok'    => version_compare(PHP_VERSION, '7.4.0', '>='),
    'detail' => PHP_VERSION . ' (7.4 minimum requis)',
];

foreach (['pdo', 'pdo_mysql', 'mbstring', 'json'] as $ext) {
    $checks[] = [
        'label' => "Extension PHP: {$ext}",
        'ok'    => extension_loaded($ext),
        'detail' => extension_loaded($ext) ? 'installée' : 'MANQUANTE — activez-la dans php.ini',
    ];
}

$checks[] = [
    'label' => 'mod_rewrite (URLs propres)',
    'ok'    => function_exists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules(), true) : true,
    'detail' => function_exists('apache_get_modules')
        ? (in_array('mod_rewrite', apache_get_modules(), true) ? 'activé' : 'désactivé — activez-le dans httpd.conf')
        : 'impossible à vérifier automatiquement (PHP en mode CGI/FPM) — vérifiez manuellement',
];

$checks[] = [
    'label' => 'Dossier storage/logs accessible en écriture',
    'ok'    => is_writable(APP_ROOT . '/storage/logs'),
    'detail' => is_writable(APP_ROOT . '/storage/logs') ? 'OK' : 'MANQUANT ou non accessible en écriture : ' . APP_ROOT . '/storage/logs',
];

$checks[] = [
    'label' => "Dossier des images produits accessible en écriture",
    'ok'    => is_writable(UPLOAD_PATH),
    'detail' => is_writable(UPLOAD_PATH) ? 'OK (' . UPLOAD_PATH . ')' : 'MANQUANT ou non accessible en écriture : ' . UPLOAD_PATH,
];

$uploadMaxBytes = (function (string $val): int {
    $val = trim($val);
    $last = strtolower($val[strlen($val) - 1] ?? '');
    $num = (int) $val;
    switch ($last) {
        case 'g': $num *= 1024;
        case 'm': $num *= 1024;
        case 'k': $num *= 1024;
    }
    return $num;
})(ini_get('upload_max_filesize') ?: '2M');

$checks[] = [
    'label' => 'Limite upload_max_filesize (php.ini)',
    'ok'    => $uploadMaxBytes >= MAX_UPLOAD_SIZE,
    'detail' => ini_get('upload_max_filesize') . ' — le site autorise jusqu\'à ' . (int)(MAX_UPLOAD_SIZE / 1024 / 1024) . ' Mo par photo.'
        . ($uploadMaxBytes < MAX_UPLOAD_SIZE ? ' ⚠️ Augmentez upload_max_filesize (et post_max_size) dans php.ini puis redémarrez Apache, sinon les photos volumineuses seront rejetées silencieusement.' : ''),
];

$dbOk = false;
$dbDetail = '';
try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;charset=%s', DB_HOST, DB_CHARSET),
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $dbOk = true;
    $dbDetail = 'Connexion au serveur MySQL réussie (' . DB_HOST . ')';

    $stmt = $pdo->query("SHOW DATABASES LIKE " . $pdo->quote(DB_NAME));
    $dbExists = (bool) $stmt->fetch();
    $checks[] = [
        'label' => "Base de données '" . DB_NAME . "'",
        'ok'    => $dbExists,
        'detail' => $dbExists
            ? 'trouvée'
            : "N'EXISTE PAS — créez-la dans phpMyAdmin et importez database/schema.sql",
    ];

    if ($dbExists) {
        $pdo2 = new PDO(
            sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET),
            DB_USER,
            DB_PASS
        );
        $stmt2 = $pdo2->query("SHOW TABLES LIKE 'users'");
        $tableExists = (bool) $stmt2->fetch();
        $checks[] = [
            'label' => "Table 'users'",
            'ok'    => $tableExists,
            'detail' => $tableExists
                ? 'trouvée'
                : "MANQUANTE — importez database/schema.sql dans phpMyAdmin",
        ];
        if ($tableExists) {
            $stmt3 = $pdo2->query("SELECT COUNT(*) FROM users WHERE role='admin'");
            $adminCount = (int) $stmt3->fetchColumn();
            $checks[] = [
                'label' => 'Compte admin',
                'ok'    => $adminCount > 0,
                'detail' => $adminCount > 0
                    ? "trouvé ({$adminCount}) — admin@artafric.com / password"
                    : "aucun compte admin trouvé dans la table users",
            ];
        }
    }
} catch (Throwable $e) {
    $dbDetail = 'ÉCHEC : ' . $e->getMessage();
}

array_splice($checks, 1, 0, [[
    'label' => 'Connexion au serveur MySQL',
    'ok'    => $dbOk,
    'detail' => $dbDetail,
]]);

$allOk = true;
foreach ($checks as $c) {
    if (!$c['ok']) $allOk = false;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Diagnostic — Art' Afric</title>
<style>
    body { font-family: -apple-system, Arial, sans-serif; background: #F5F0E8; margin: 0; padding: 40px 20px; color: #2C1810; }
    .wrap { max-width: 720px; margin: 0 auto; background: #fff; border-radius: 10px; padding: 32px; box-shadow: 0 4px 20px rgba(44,24,16,0.1); }
    h1 { font-size: 22px; margin-top: 0; }
    .summary { padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; }
    .summary.ok { background: #D4EDDA; color: #2D5016; }
    .summary.ko { background: #F8D7DA; color: #C0392B; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 10px 8px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: top; }
    .status { font-size: 18px; width: 28px; }
    .label { font-weight: 600; width: 260px; }
    .detail { color: #555; }
    code { background: #F5F0E8; padding: 2px 6px; border-radius: 4px; }
</style>
</head>
<body>
<div class="wrap">
    <h1>Diagnostic — Art' Afric</h1>
    <div class="summary <?= $allOk ? 'ok' : 'ko' ?>">
        <?= $allOk ? '✅ Tout est prêt ! Ouvrez /public pour voir le site.' : '⚠️ Un ou plusieurs points bloquent le fonctionnement du site — voir le détail ci-dessous.' ?>
    </div>
    <table>
        <?php foreach ($checks as $c): ?>
        <tr>
            <td class="status"><?= $c['ok'] ? '✅' : '❌' ?></td>
            <td class="label"><?= htmlspecialchars($c['label']) ?></td>
            <td class="detail"><?= htmlspecialchars($c['detail']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p style="margin-top:24px;font-size:13px;color:#888;">Vous pouvez supprimer ce fichier (<code>public/check.php</code>) une fois le site fonctionnel.</p>
</div>
</body>
</html>
