<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">
    <?php if (Auth::isAdmin()): ?>
    <aside class="admin-sidebar">
        <div class="admin-logo">
            <a href="<?= APP_URL ?>/admin">Art' Afric</a>
            <span>Administration</span>
        </div>
        <nav>
            <a href="<?= APP_URL ?>/admin"><i class="fas fa-chart-line"></i> Tableau de bord</a>
            <a href="<?= APP_URL ?>/admin/produits"><i class="fas fa-box"></i> Produits</a>
            <a href="<?= APP_URL ?>/admin/commandes"><i class="fas fa-shopping-bag"></i> Commandes</a>
            <a href="<?= APP_URL ?>/admin/clients"><i class="fas fa-users"></i> Clients</a>
            <a href="<?= APP_URL ?>/admin/categories"><i class="fas fa-tags"></i> Catégories</a>
            <a href="<?= APP_URL ?>/admin/coupons"><i class="fas fa-ticket"></i> Coupons</a>
            <a href="<?= APP_URL ?>/admin/partenaires"><i class="fas fa-handshake"></i> Partenaires</a>
            <a href="<?= APP_URL ?>/admin/blog"><i class="fas fa-newspaper"></i> Blog</a>
            <a href="<?= APP_URL ?>/admin/messages"><i class="fas fa-envelope"></i> Messages</a>
            <a href="<?= APP_URL ?>/admin/statistiques"><i class="fas fa-chart-bar"></i> Statistiques</a>
            <a href="<?= APP_URL ?>/admin/parametres"><i class="fas fa-cog"></i> Paramètres</a>
            <a href="<?= APP_URL ?>/admin/profil"><i class="fas fa-user-circle"></i> Mon profil</a>
            <a href="<?= APP_URL ?>" target="_blank"><i class="fas fa-external-link-alt"></i> Voir le site</a>
            <a href="<?= APP_URL ?>/deconnexion"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>
    <?php endif; ?>

    <div class="admin-main">
        <?php if (Auth::isAdmin()): ?>
        <header class="admin-header">
            <h1><?= e($title ?? '') ?></h1>
            <a href="<?= APP_URL ?>/admin/profil"><?= e(Auth::user()['first_name'] ?? '') ?></a>
        </header>
        <?php endif; ?>
        <div class="admin-content">
            <?php flashMessages(); ?>
            <?= $pageContent ?>
        </div>
    </div>
</body>
</html>
