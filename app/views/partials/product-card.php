<?php
$price = $product['sale_price'] ?? $product['price'];
$oldPrice = $product['sale_price'] ? $product['price'] : null;
$images = json_decode($product['images'] ?? '[]', true) ?: ['placeholder.jpg'];
?>
<article class="product-card" data-id="<?= $product['id'] ?>">
    <div class="product-card-image">
        <?php if ($product['is_new']): ?><span class="badge badge-new">Nouveau</span><?php endif; ?>
        <?php if ($product['is_promo']): ?><span class="badge badge-promo">Promo</span><?php endif; ?>
        <a href="<?= APP_URL ?>/produit/<?= e($product['slug']) ?>">
            <img src="<?= APP_URL ?>/assets/images/products/<?= e($images[0]) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
        </a>
        <div class="product-card-actions">
            <button class="btn-icon add-to-cart" data-id="<?= $product['id'] ?>" title="Ajouter au panier"><i class="fas fa-shopping-bag"></i></button>
            <button class="btn-icon toggle-favorite" data-id="<?= $product['id'] ?>" title="Favoris"><i class="far fa-heart"></i></button>
            <a href="<?= APP_URL ?>/produit/<?= e($product['slug']) ?>" class="btn-icon" title="Voir détails"><i class="fas fa-eye"></i></a>
        </div>
    </div>
    <div class="product-card-body">
        <span class="product-category"><?= e($product['category_name'] ?? '') ?></span>
        <h3><a href="<?= APP_URL ?>/produit/<?= e($product['slug']) ?>"><?= e($product['name']) ?></a></h3>
        <div class="product-rating"><?= stars((float)($product['rating_avg'] ?? 0)) ?> <span>(<?= $product['rating_count'] ?? 0 ?>)</span></div>
        <div class="product-price">
            <span class="price-current"><?= formatPrice((float)$price) ?></span>
            <?php if ($oldPrice): ?><span class="price-old"><?= formatPrice((float)$oldPrice) ?></span><?php endif; ?>
        </div>
        <span class="stock-status <?= $product['stock'] > 0 ? 'in-stock' : 'out-stock' ?>">
            <?= $product['stock'] > 0 ? 'En stock' : 'Rupture' ?>
        </span>
    </div>
</article>
