<div class="page-header"><div class="container"><h1>Mes Favoris</h1></div></div>
<div class="container">
    <?php if (empty($favorites)): ?>
        <p>Aucun favori. <a href="<?= APP_URL ?>/boutique">Découvrir la boutique</a></p>
    <?php else: ?>
    <div class="products-grid">
        <?php foreach ($favorites as $product): ?>
            <?php require APP_PATH . '/views/partials/product-card.php'; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
