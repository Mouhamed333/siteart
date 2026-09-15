<?php flashMessages(); ?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="container hero-content">
        <span class="hero-subtitle">Artisanat Africain Premium</span>
        <h1>Découvrez toute la richesse de l'art africain.</h1>
        <p>Des créations authentiques, élégantes et uniques — directement des artisans africains à votre porte.</p>
        <div class="hero-buttons">
            <a href="<?= APP_URL ?>/boutique" class="btn btn-primary">Acheter maintenant</a>
            <a href="<?= APP_URL ?>/boutique" class="btn btn-outline">Nos collections</a>
        </div>
    </div>
</section>

<section class="section categories-section">
    <div class="container">
        <h2 class="section-title">Nos Catégories</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= APP_URL ?>/boutique/categorie/<?= e($cat['slug']) ?>" class="category-card">
                <div class="category-icon"><i class="fas fa-gem"></i></div>
                <h3><?= e($cat['name']) ?></h3>
                <span><?= $cat['product_count'] ?> produits</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nouveautés</h2>
            <a href="<?= APP_URL ?>/boutique?nouveaute=1" class="link-more">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="products-grid">
            <?php foreach ($newProducts as $product): ?>
                <?php require APP_PATH . '/views/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Promotions</h2>
            <a href="<?= APP_URL ?>/boutique?promo=1" class="link-more">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="products-grid">
            <?php foreach ($promos as $product): ?>
                <?php require APP_PATH . '/views/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Produits Populaires</h2>
            <a href="<?= APP_URL ?>/boutique" class="link-more">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="products-grid">
            <?php foreach ($featured as $product): ?>
                <?php require APP_PATH . '/views/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section why-section">
    <div class="container">
        <h2 class="section-title text-center">Pourquoi choisir Art' Afric</h2>
        <div class="why-grid">
            <div class="why-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>Artisanat Authentique</h3>
                <p>Chaque produit est sélectionné auprès d'artisans africains certifiés.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Paiement Sécurisé</h3>
                <p>Transactions protégées avec les meilleures méthodes de paiement.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-truck"></i>
                <h3>Expédition Rapide</h3>
                <p>Vos commandes sont préparées et expédiées sous 48h.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-undo"></i>
                <h3>Satisfait ou Remboursé</h3>
                <p>Retours gratuits sous 14 jours, sans condition.</p>
            </div>
        </div>
    </div>
</section>

<section class="section testimonials-section">
    <div class="container">
        <h2 class="section-title text-center">Témoignages Clients</h2>
        <div class="swiper testimonials-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide testimonial-card">
                    <div class="stars"><?= stars(5) ?></div>
                    <p>"Des produits magnifiques et une qualité exceptionnelle. Mon boubou brodé est une œuvre d'art !"</p>
                    <strong>Fatou S. — Dakar</strong>
                </div>
                <div class="swiper-slide testimonial-card">
                    <div class="stars"><?= stars(5) ?></div>
                    <p>"Livraison rapide et emballage soigné. Je recommande Art' Afric les yeux fermés."</p>
                    <strong>Marie K. — Paris</strong>
                </div>
                <div class="swiper-slide testimonial-card">
                    <div class="stars"><?= stars(4.5) ?></div>
                    <p>"Enfin une boutique qui valorise l'artisanat africain avec élégance et respect."</p>
                    <strong>Amadou D. — Abidjan</strong>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<section class="section newsletter-section">
    <div class="container newsletter-box">
        <h2>Restez informé</h2>
        <p>Recevez nos nouveautés, promotions exclusives et articles culturels.</p>
        <form action="<?= APP_URL ?>/newsletter" method="POST" class="newsletter-form-inline">
            <?= CSRF::field() ?>
            <input type="email" name="email" placeholder="Votre adresse email" required>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>
</section>

<section class="section partners-section">
    <div class="container">
        <h2 class="section-title text-center">Nos Partenaires</h2>
        <div class="partners-logos">
            <?php if (empty($partners)): ?>
                <span>Artisans du Sénégal</span>
            <?php else: ?>
                <?php foreach ($partners as $partner): ?>
                    <?php if (!empty($partner['website_url'])): ?>
                    <a href="<?= e($partner['website_url']) ?>" target="_blank" rel="noopener"><?= e($partner['name']) ?></a>
                    <?php else: ?>
                    <span><?= e($partner['name']) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    new Swiper('.testimonials-slider', { slidesPerView: 1, spaceBetween: 30, pagination: { el: '.swiper-pagination', clickable: true }, breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } } });
});
</script>
