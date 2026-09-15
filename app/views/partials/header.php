<header class="site-header">
    <?php $adText = Settings::get('header_ad_text', ''); ?>
    <?php if ($adText !== ''): ?>
    <div class="header-top">
        <div class="container">
            <?php $adLink = Settings::get('header_ad_link', ''); ?>
            <?php if ($adLink !== ''): ?>
            <a href="<?= e($adLink) ?>" class="header-ad-link"><i class="fas fa-bullhorn"></i> <?= e($adText) ?></a>
            <?php else: ?>
            <span><i class="fas fa-bullhorn"></i> <?= e($adText) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="header-main">
        <div class="container header-inner">
            <a href="<?= APP_URL ?>" class="logo">
                <span class="logo-icon">✦</span>
                <span class="logo-text">Art' Afric</span>
            </a>

            <nav class="main-nav" aria-label="Navigation principale">
                <a href="<?= APP_URL ?>" class="<?= activeLink('') ?>">Accueil</a>
                <a href="<?= APP_URL ?>/boutique" class="<?= activeLink('boutique') ?>">Boutique</a>
                <div class="nav-dropdown">
                    <a href="#">Catégories <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="<?= APP_URL ?>/boutique/categorie/vetements">Vêtements</a>
                        <a href="<?= APP_URL ?>/boutique/categorie/sacs">Sacs</a>
                        <a href="<?= APP_URL ?>/boutique/categorie/bijoux">Bijoux</a>
                        <a href="<?= APP_URL ?>/boutique/categorie/chaussures">Chaussures</a>
                        <a href="<?= APP_URL ?>/boutique/categorie/decoration">Décoration</a>
                        <a href="<?= APP_URL ?>/boutique/categorie/tissus">Tissus</a>
                        <a href="<?= APP_URL ?>/boutique/categorie/accessoires">Accessoires</a>
                        <a href="<?= APP_URL ?>/boutique/categorie/artisanat">Artisanat</a>
                    </div>
                </div>
                <a href="<?= APP_URL ?>/blog">Blog</a>
                <a href="<?= APP_URL ?>/a-propos">À propos</a>
                <a href="<?= APP_URL ?>/contact">Contact</a>
            </nav>

            <div class="header-actions">
                <button class="icon-btn" id="search-toggle" aria-label="Rechercher"><i class="fas fa-search"></i></button>
                <?php if (Auth::check()): ?>
                    <a href="<?= APP_URL ?>/mon-compte/favoris" class="icon-btn" aria-label="Favoris"><i class="fas fa-heart"></i></a>
                    <a href="<?= APP_URL ?>/mon-compte" class="icon-btn" aria-label="Mon compte"><i class="fas fa-user"></i></a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/connexion" class="icon-btn" aria-label="Connexion"><i class="fas fa-user"></i></a>
                <?php endif; ?>
                <a href="<?= APP_URL ?>/panier" class="icon-btn cart-btn" aria-label="Panier">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count" id="cart-count"><?= Cart::count() ?></span>
                </a>
                <button class="mobile-menu-toggle" aria-label="Menu"><i class="fas fa-bars"></i></button>
            </div>
        </div>
    </div>
</header>
