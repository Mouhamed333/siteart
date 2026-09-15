<div class="page-header"><div class="container"><h1>Contact</h1></div></div>
<div class="container contact-page">
    <?php flashMessages(); ?>
    <div class="contact-layout">
        <div class="contact-info">
            <h2>Contactez-nous</h2>
            <p><i class="fas fa-map-marker-alt"></i> Plateau, Dakar, Sénégal</p>
            <p><i class="fas fa-phone"></i> +221 78 122 24 82</p>
            <p><i class="fas fa-envelope"></i> artafric@gmail.com</p>
            <p><i class="fab fa-whatsapp"></i> <a href="https://wa.me/221781222482">WhatsApp</a></p>
            <div class="map-placeholder">
                <iframe src="https://maps.google.com/maps?q=Dakar,Senegal&output=embed" width="100%" height="250" style="border:0;" loading="lazy"></iframe>
            </div>
        </div>
        <form action="<?= APP_URL ?>/contact" method="POST" class="contact-form">
            <?= CSRF::field() ?>
            <div class="form-group"><label>Nom *</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Email *</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Téléphone</label><input type="tel" name="phone"></div>
            <div class="form-group"><label>Sujet *</label><input type="text" name="subject" required></div>
            <div class="form-group"><label>Message *</label><textarea name="message" rows="5" required></textarea></div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>

    <section class="faq-section">
        <h2>FAQ</h2>
        <details><summary>Quels sont les délais de livraison ?</summary><p>Livraison standard en 3-5 jours ouvrés au Sénégal, 7-14 jours internationalement.</p></details>
        <details><summary>Quels moyens de paiement acceptez-vous ?</summary><p>Carte bancaire, Orange Money, Wave, Free Money, PayPal et Stripe.</p></details>
    </section>
</div>
