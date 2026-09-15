<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?= APP_URL ?>" class="logo">
                    <span class="logo-icon">✦</span>
                    <span class="logo-text">Art' Afric</span>
                </a>
                <p><?= e(Settings::get('footer_description', "Boutique premium dédiée à l'artisanat africain authentique.")) ?></p>
                <?php
                    $socials = [
                        'social_facebook'  => ['fab fa-facebook', 'Facebook'],
                        'social_instagram' => ['fab fa-instagram', 'Instagram'],
                        'social_whatsapp'  => ['fab fa-whatsapp', 'WhatsApp'],
                        'social_pinterest' => ['fab fa-pinterest', 'Pinterest'],
                    ];
                    $hasSocials = false;
                    foreach ($socials as $key => $meta) {
                        if (Settings::get($key) !== '') { $hasSocials = true; break; }
                    }
                ?>
                <?php if ($hasSocials): ?>
                <div class="social-links">
                    <?php foreach ($socials as $key => [$icon, $label]): ?>
                        <?php $url = Settings::get($key); ?>
                        <?php if ($url !== ''): ?>
                        <a href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= $label ?>"><i class="<?= $icon ?>"></i></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="footer-links">
                <h4>Liens rapides</h4>
                <a href="<?= APP_URL ?>/boutique">Boutique</a>
                <a href="<?= APP_URL ?>/a-propos">À propos</a>
                <a href="<?= APP_URL ?>/blog">Blog</a>
                <a href="<?= APP_URL ?>/contact">Contact</a>
                <a href="<?= APP_URL ?>/mon-compte">Mon compte</a>
            </div>

            <div class="footer-links">
                <h4>Catégories</h4>
                <a href="<?= APP_URL ?>/boutique/categorie/vetements">Vêtements</a>
                <a href="<?= APP_URL ?>/boutique/categorie/bijoux">Bijoux</a>
                <a href="<?= APP_URL ?>/boutique/categorie/sacs">Sacs</a>
                <a href="<?= APP_URL ?>/boutique/categorie/artisanat">Artisanat</a>
            </div>

            <div class="footer-contact">
                <h4>Contact</h4>
                <?php if (Settings::get('contact_address') !== ''): ?><p><i class="fas fa-map-marker-alt"></i> <?= e(Settings::get('contact_address')) ?></p><?php endif; ?>
                <?php if (Settings::get('contact_phone') !== ''): ?><p><i class="fas fa-phone"></i> <?= e(Settings::get('contact_phone')) ?></p><?php endif; ?>
                <?php if (Settings::get('contact_email') !== ''): ?><p><i class="fas fa-envelope"></i> <?= e(Settings::get('contact_email')) ?></p><?php endif; ?>
                <form class="newsletter-form" action="<?= APP_URL ?>/newsletter" method="POST">
                    <?= CSRF::field() ?>
                    <input type="email" name="email" placeholder="Votre email" required aria-label="Email newsletter">
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
        </div>

        <div class="footer-payments">
            <span>Moyens de paiement :</span>
            <i class="fab fa-cc-visa" title="Visa"></i>
            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
            <i class="fab fa-cc-paypal" title="PayPal"></i>
            <i class="fab fa-stripe" title="Stripe"></i>
            <span class="payment-badge">Orange Money</span>
            <span class="payment-badge">Wave</span>
            <span class="payment-badge">Free Money</span>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Art' Afric. Tous droits réservés.</p>
            <div>
                <a href="#">Mentions légales</a>
                <a href="#">Confidentialité</a>
                <a href="#">CGV</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bouton WhatsApp flottant -->
<?php $waNumber = preg_replace('/\D/', '', Settings::get('whatsapp_order_number', '221777929623')); ?>
<?php if ($waNumber !== ''): ?>
<a id="whatsapp-float"
   href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode("Bonjour Art' Afric, j'ai une question.") ?>"
   target="_blank" rel="noopener"
   aria-label="Discuter sur WhatsApp"
   class="whatsapp-float">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>
