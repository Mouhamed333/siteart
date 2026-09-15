<?php
$price = $product['sale_price'] ?? $product['price'];
$oldPrice = $product['sale_price'] ? $product['price'] : null;
?>

<div class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="<?= APP_URL ?>">Accueil</a> /
            <a href="<?= APP_URL ?>/boutique">Boutique</a> /
            <a href="<?= APP_URL ?>/boutique/categorie/<?= e($product['category_slug']) ?>"><?= e($product['category_name']) ?></a> /
            <?= e($product['name']) ?>
        </nav>
    </div>
</div>

<div class="container product-detail">
    <div class="product-gallery">
        <div class="gallery-main">
            <img id="main-image" src="<?= APP_URL ?>/assets/images/products/<?= e($images[0]) ?>" alt="<?= e($product['name']) ?>">
        </div>
        <div class="gallery-thumbs">
            <?php foreach ($images as $i => $img): ?>
            <button class="thumb <?= $i === 0 ? 'active' : '' ?>" data-src="<?= APP_URL ?>/assets/images/products/<?= e($img) ?>">
                <img src="<?= APP_URL ?>/assets/images/products/<?= e($img) ?>" alt="">
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="product-info">
        <span class="product-category"><?= e($product['category_name']) ?></span>
        <h1><?= e($product['name']) ?></h1>
        <div class="product-rating"><?= stars((float)$product['rating_avg']) ?> <span>(<?= $product['rating_count'] ?> avis)</span></div>

        <div class="product-price-lg">
            <span class="price-current"><?= formatPrice((float)$price) ?></span>
            <?php if ($oldPrice): ?><span class="price-old"><?= formatPrice((float)$oldPrice) ?></span><?php endif; ?>
        </div>

        <p class="product-short-desc"><?= e($product['short_description']) ?></p>

        <form action="<?= APP_URL ?>/panier/ajouter" method="POST" class="add-to-cart-form">
            <?= CSRF::field() ?>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

            <?php if (!empty($sizes)): ?>
            <div class="form-group">
                <label>Taille</label>
                <select name="size" required>
                    <?php foreach ($sizes as $size): ?>
                    <option value="<?= e($size) ?>"><?= e($size) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <?php if ($product['color']): ?>
            <div class="form-group">
                <label>Couleur</label>
                <input type="hidden" name="color" value="<?= e($product['color']) ?>">
                <span class="color-swatch"><?= e($product['color']) ?></span>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Quantité</label>
                <div class="qty-selector">
                    <button type="button" class="qty-minus">-</button>
                    <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                    <button type="button" class="qty-plus">+</button>
                </div>
            </div>

            <span class="stock-status <?= $product['stock'] > 0 ? 'in-stock' : 'out-stock' ?>">
                <?= $product['stock'] > 0 ? "En stock ({$product['stock']} disponibles)" : 'Rupture de stock' ?>
            </span>

            <div class="product-actions">
                <button type="submit" class="btn btn-primary btn-lg" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                    <i class="fas fa-shopping-bag"></i> Ajouter au panier
                </button>
                <button type="button" class="btn btn-outline toggle-favorite" data-id="<?= $product['id'] ?>">
                    <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart"></i>
                </button>
            </div>
        </form>

        <div class="product-meta">
            <p><strong>SKU:</strong> <?= e($product['sku']) ?></p>
            <p><strong>Matière:</strong> <?= e($product['material']) ?></p>
            <p><strong>Couleur:</strong> <?= e($product['color']) ?></p>
        </div>
    </div>
</div>

<div class="container product-tabs">
    <div class="tabs">
        <button class="tab active" data-tab="description">Description</button>
        <button class="tab" data-tab="reviews">Avis (<?= count($reviews) ?>)</button>
        <button class="tab" data-tab="qa">Questions</button>
    </div>
    <div class="tab-content active" id="description">
        <?= nl2br(e($product['description'])) ?>
    </div>
    <div class="tab-content" id="reviews">
        <?php foreach ($reviews as $review): ?>
        <div class="review-item">
            <div class="review-header">
                <strong><?= e($review['first_name']) ?> <?= e(substr($review['last_name'], 0, 1)) ?>.</strong>
                <?= stars((float)$review['rating']) ?>
                <span><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
            </div>
            <?php if ($review['title']): ?><h4><?= e($review['title']) ?></h4><?php endif; ?>
            <p><?= e($review['comment']) ?></p>
        </div>
        <?php endforeach; ?>
        <?php if (Auth::check()): ?>
        <form id="review-form" class="review-form">
            <h4>Laisser un avis</h4>
            <select name="rating" required>
                <option value="5">5 étoiles</option>
                <option value="4">4 étoiles</option>
                <option value="3">3 étoiles</option>
                <option value="2">2 étoiles</option>
                <option value="1">1 étoile</option>
            </select>
            <input type="text" name="title" placeholder="Titre">
            <textarea name="comment" placeholder="Votre avis" required></textarea>
            <button type="submit" class="btn btn-primary">Publier</button>
        </form>
        <?php endif; ?>
    </div>
    <div class="tab-content" id="qa">
        <p>Posez vos questions sur ce produit. Notre équipe vous répondra sous 24h.</p>
    </div>
</div>

<?php if (!empty($similar)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Produits Similaires</h2>
        <div class="products-grid">
            <?php foreach ($similar as $product): ?>
                <?php require APP_PATH . '/views/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
document.querySelectorAll('.thumb').forEach(t => t.addEventListener('click', () => {
    document.getElementById('main-image').src = t.dataset.src;
    document.querySelectorAll('.thumb').forEach(x => x.classList.remove('active'));
    t.classList.add('active');
}));
document.querySelectorAll('.tab').forEach(tab => tab.addEventListener('click', () => {
    document.querySelectorAll('.tab, .tab-content').forEach(el => el.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById(tab.dataset.tab).classList.add('active');
}));
document.querySelector('.qty-minus')?.addEventListener('click', () => { const i = document.querySelector('[name=quantity]'); if (i.value > 1) i.value--; });
document.querySelector('.qty-plus')?.addEventListener('click', () => { const i = document.querySelector('[name=quantity]'); if (+i.value < +i.max) i.value++; });
</script>
