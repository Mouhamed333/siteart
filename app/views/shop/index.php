<div class="page-header">
    <div class="container">
        <h1>Boutique</h1>
        <nav class="breadcrumb"><a href="<?= APP_URL ?>">Accueil</a> / Boutique</nav>
    </div>
</div>

<div class="container shop-layout">
    <aside class="shop-filters">
        <h3>Filtres</h3>
        <form id="filter-form" method="GET" action="<?= APP_URL ?>/boutique">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="q" value="<?= e($filters['search'] ?? '') ?>" placeholder="Rechercher...">
            </div>
            <div class="filter-group">
                <label>Catégorie</label>
                <select name="categorie">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= e($cat['slug']) ?>" <?= ($filters['category'] ?? '') === $cat['slug'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Prix min (FCFA)</label>
                <input type="number" name="prix_min" value="<?= e($filters['min_price'] ?? '') ?>" min="0">
            </div>
            <div class="filter-group">
                <label>Prix max (FCFA)</label>
                <input type="number" name="prix_max" value="<?= e($filters['max_price'] ?? '') ?>" min="0">
            </div>
            <div class="filter-group">
                <label>Couleur</label>
                <input type="text" name="couleur" value="<?= e($filters['color'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>Matière</label>
                <input type="text" name="matiere" value="<?= e($filters['material'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label><input type="checkbox" name="promo" value="1" <?= !empty($filters['promo']) ? 'checked' : '' ?>> Promotions</label>
            </div>
            <div class="filter-group">
                <label><input type="checkbox" name="nouveaute" value="1" <?= !empty($filters['new']) ? 'checked' : '' ?>> Nouveautés</label>
            </div>
            <div class="filter-group">
                <label>Trier par</label>
                <select name="tri">
                    <option value="popular" <?= ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>>Popularité</option>
                    <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Nouveautés</option>
                    <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                    <option value="rating" <?= ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Meilleures notes</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Appliquer</button>
        </form>
    </aside>

    <div class="shop-products">
        <div class="shop-toolbar">
            <span><?= $total ?> produit(s) trouvé(s)</span>
        </div>
        <div class="products-grid" id="products-grid">
            <?php if (empty($products)): ?>
                <p class="no-results">Aucun produit trouvé.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <?php require APP_PATH . '/views/partials/product-card.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?page=<?= $i ?>&<?= http_build_query(array_filter($filters)) ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </nav>
        <?php endif; ?>
    </div>
</div>

<script src="<?= APP_URL ?>/assets/js/shop.js"></script>
