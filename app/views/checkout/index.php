<div class="page-header">
    <div class="container"><h1>Finaliser ma commande</h1></div>
</div>

<div class="container checkout-page">
    <?php flashMessages(); ?>

    <div class="checkout-steps">
        <span class="step active">1. Informations</span>
        <span class="step">2. Adresse</span>
        <span class="step">3. Paiement</span>
        <span class="step">4. Confirmation</span>
    </div>

    <form action="<?= APP_URL ?>/commande" method="POST" class="checkout-form">
        <?= CSRF::field() ?>

        <div class="checkout-layout">
            <div class="checkout-main">
                <section class="checkout-section">
                    <h2>Informations client</h2>
                    <div class="form-row">
                        <div class="form-group"><label>Prénom *</label><input type="text" name="first_name" value="<?= e($user['first_name'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Nom *</label><input type="text" name="last_name" value="<?= e($user['last_name'] ?? '') ?>" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Email *</label><input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Téléphone *</label><input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" required></div>
                    </div>
                </section>

                <section class="checkout-section">
                    <h2>Adresse de livraison</h2>
                    <div class="form-group"><label>Adresse *</label><input type="text" name="address" required></div>
                    <div class="form-row">
                        <div class="form-group"><label>Ville *</label><input type="text" name="city" required></div>
                        <div class="form-group"><label>Région</label><input type="text" name="region"></div>
                    </div>
                    <div class="form-group"><label>Pays</label><input type="text" name="country" value="Sénégal"></div>
                </section>

                <section class="checkout-section">
                    <h2>Paiement</h2>
                    <p>Le paiement se fait à la livraison ou par transfert (Orange Money / Wave), vous serez contacté par téléphone pour finaliser le règlement une fois la commande confirmée.</p>
                </section>
            </div>

            <div class="checkout-summary">
                <h3>Votre commande</h3>
                <?php foreach ($summary['items'] as $item): ?>
                <div class="checkout-item">
                    <span><?= e($item['name']) ?> × <?= $item['quantity'] ?></span>
                    <span><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                </div>
                <?php endforeach; ?>
                <hr>
                <div class="summary-line"><span>Sous-total</span><span><?= formatPrice($summary['subtotal']) ?></span></div>
                <?php if ($summary['discount'] > 0): ?>
                <div class="summary-line"><span>Réduction</span><span>-<?= formatPrice($summary['discount']) ?></span></div>
                <?php endif; ?>
                <div class="summary-line total"><span>Total</span><span><?= formatPrice($summary['total']) ?></span></div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">Confirmer la commande</button>
            </div>
        </div>
    </form>
</div>
