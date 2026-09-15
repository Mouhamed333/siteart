<div class="page-header">
    <div class="container">
        <h1><?= e($category['name']) ?></h1>
        <nav class="breadcrumb">
            <a href="<?= APP_URL ?>">Accueil</a> /
            <a href="<?= APP_URL ?>/boutique">Boutique</a> /
            <?= e($category['name']) ?>
        </nav>
        <?php if (!empty($category['description'])): ?>
        <p><?= e($category['description']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container shop-products">
    <div class="shop-toolbar">
        <span><?= count($products) ?> produit(s) trouvé(s)</span>
    </div>

    <div class="products-grid" id="products-grid">
        <?php if (empty($products)): ?>
            <p class="no-results">Aucun produit dans cette catégorie pour le moment.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <?php require APP_PATH . '/views/partials/product-card.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($pages > 1): ?>
    <nav class="pagination" aria-label="Pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </nav>
    <?php endif; ?>
</div>
