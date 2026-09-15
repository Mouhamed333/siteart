<div class="page-header">
    <div class="container">
        <h1>Mon Panier</h1>
        <nav class="breadcrumb"><a href="<?= APP_URL ?>">Accueil</a> / Panier</nav>
    </div>
</div>

<div class="container cart-page">
    <?php flashMessages(); ?>

    <?php if (empty($summary['items'])): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-bag"></i>
            <h2>Votre panier est vide</h2>
            <p>Découvrez nos magnifiques créations africaines.</p>
            <a href="<?= APP_URL ?>/boutique" class="btn btn-primary">Continuer mes achats</a>
        </div>
    <?php else: ?>
    <div class="cart-layout">
        <div class="cart-items">
            <table class="cart-table">
                <thead>
                    <tr><th>Produit</th><th>Prix</th><th>Quantité</th><th>Total</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($summary['items'] as $key => $item): ?>
                    <tr data-key="<?= e($key) ?>">
                        <td class="cart-product">
                            <img src="<?= APP_URL ?>/assets/images/products/<?= e($item['image']) ?>" alt="">
                            <div>
                                <strong><?= e($item['name']) ?></strong>
                                <?php if ($item['size']): ?><span>Taille: <?= e($item['size']) ?></span><?php endif; ?>
                            </div>
                        </td>
                        <td><?= formatPrice((float)$item['price']) ?></td>
                        <td>
                            <div class="qty-selector">
                                <button class="qty-minus" data-key="<?= e($key) ?>">-</button>
                                <input type="number" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" data-key="<?= e($key) ?>">
                                <button class="qty-plus" data-key="<?= e($key) ?>">+</button>
                            </div>
                        </td>
                        <td><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                        <td><button class="btn-icon remove-item" data-key="<?= e($key) ?>"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <h3>Récapitulatif</h3>
            <div class="summary-line"><span>Sous-total</span><span id="subtotal"><?= formatPrice($summary['subtotal']) ?></span></div>
            <?php if ($summary['discount'] > 0): ?>
            <div class="summary-line discount"><span>Réduction</span><span id="discount">-<?= formatPrice($summary['discount']) ?></span></div>
            <?php endif; ?>
            <div class="summary-line total"><span>Total</span><span id="total"><?= formatPrice($summary['total']) ?></span></div>

            <form id="coupon-form" class="coupon-form">
                <?= CSRF::field() ?>
                <input type="text" name="code" placeholder="Code promo" value="<?= e($summary['coupon']['code'] ?? '') ?>">
                <button type="submit" class="btn btn-outline">Appliquer</button>
            </form>

            <a href="<?= APP_URL ?>/commande" class="btn btn-primary btn-block btn-lg">Passer la commande</a>
            <a href="<?= APP_URL ?>/boutique" class="btn btn-outline btn-block">Continuer mes achats</a>
        </div>
    </div>
    <?php endif; ?>
</div>
