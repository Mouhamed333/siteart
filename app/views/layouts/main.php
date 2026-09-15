<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? "Art' Afric") ?></title>
    <meta name="description" content="Art' Afric - Boutique premium de produits africains authentiques : vêtements, bijoux, sacs, décoration et artisanat.">
    <meta property="og:title" content="<?= e($title ?? "Art' Afric") ?>">
    <meta property="og:description" content="Découvrez toute la richesse de l'art africain.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/main.css">
    <script>window.APP_URL = '<?= APP_URL ?>'; window.CSRF_TOKEN = '<?= CSRF::generateToken() ?>';</script>
</head>
<body>
    <?php require APP_PATH . '/views/partials/header.php'; ?>

    <main id="main-content">
        <?= $pageContent ?>
    </main>

    <?php require APP_PATH . '/views/partials/footer.php'; ?>

    <div id="search-modal" class="modal" aria-hidden="true">
        <div class="modal-content search-modal">
            <button class="modal-close" aria-label="Fermer">&times;</button>
            <input type="search" id="live-search" placeholder="Rechercher un produit..." autocomplete="off">
            <div id="search-results"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= APP_URL ?>/assets/js/main.js"></script>
    <script src="<?= APP_URL ?>/assets/js/cart.js"></script>
</body>
</html>
