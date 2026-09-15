<div class="admin-card">
    <form action="<?= APP_URL ?>/admin/parametres" method="POST">
        <?= CSRF::field() ?>
        <h2 style="margin-top:0;">Bandeau publicitaire (haut du site)</h2>
        <p style="font-size:13px;color:#888;margin-top:-8px;">Affiché tout en haut de chaque page. Laissez le texte vide pour masquer le bandeau.</p>
        <div class="form-group"><label>Texte de l'annonce</label><input type="text" name="header_ad_text" placeholder="Ex : -20% sur toute la collection Wax cette semaine" value="<?= e($settings['header_ad_text'] ?? '') ?>"></div>
        <div class="form-group"><label>Lien (optionnel, où le clic doit mener)</label><input type="url" name="header_ad_link" placeholder="https://..." value="<?= e($settings['header_ad_link'] ?? '') ?>"></div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<div class="admin-card">
    <form action="<?= APP_URL ?>/admin/parametres" method="POST">
        <?= CSRF::field() ?>

        <h2 style="margin-top:0;">Pied de page (footer)</h2>
        <div class="form-group">
            <label>Description de la boutique</label>
            <textarea name="footer_description" rows="3"><?= e($settings['footer_description'] ?? '') ?></textarea>
        </div>

        <h3>Coordonnées</h3>
        <div class="form-row">
            <div class="form-group"><label>Adresse</label><input type="text" name="contact_address" value="<?= e($settings['contact_address'] ?? '') ?>"></div>
            <div class="form-group"><label>Téléphone</label><input type="text" name="contact_phone" value="<?= e($settings['contact_phone'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Email</label><input type="email" name="contact_email" value="<?= e($settings['contact_email'] ?? '') ?>"></div>

        <h3>WhatsApp</h3>
        <p style="font-size:13px;color:#888;margin-top:-8px;">Numéro utilisé pour le bouton de chat flottant et pour recevoir les commandes des clients (avec l'indicatif pays, sans espaces ni "+"). Exemple pour le Sénégal : 221777929623.</p>
        <div class="form-group"><label>Numéro WhatsApp</label><input type="text" name="whatsapp_order_number" placeholder="221777929623" value="<?= e($settings['whatsapp_order_number'] ?? '') ?>"></div>

        <h3>Réseaux sociaux</h3>
        <p style="font-size:13px;color:#888;margin-top:-8px;">Laissez un champ vide pour ne pas afficher l'icône correspondante dans le pied de page.</p>
        <div class="form-row">
            <div class="form-group"><label><i class="fab fa-facebook"></i> Facebook</label><input type="url" name="social_facebook" placeholder="https://facebook.com/..." value="<?= e($settings['social_facebook'] ?? '') ?>"></div>
            <div class="form-group"><label><i class="fab fa-instagram"></i> Instagram</label><input type="url" name="social_instagram" placeholder="https://instagram.com/..." value="<?= e($settings['social_instagram'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label><i class="fab fa-whatsapp"></i> WhatsApp</label><input type="url" name="social_whatsapp" placeholder="https://wa.me/221..." value="<?= e($settings['social_whatsapp'] ?? '') ?>"></div>
            <div class="form-group"><label><i class="fab fa-pinterest"></i> Pinterest</label><input type="url" name="social_pinterest" placeholder="https://pinterest.com/..." value="<?= e($settings['social_pinterest'] ?? '') ?>"></div>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<div class="admin-card">
    <form action="<?= APP_URL ?>/admin/parametres" method="POST">
        <?= CSRF::field() ?>
        <h2 style="margin-top:0;">Page "À propos"</h2>

        <div class="form-group">
            <label>Notre histoire</label>
            <textarea name="about_story" rows="4"><?= e($settings['about_story'] ?? "Fondée en 2020 à Dakar, Art' Afric est née d'une passion profonde pour l'artisanat africain et d'une volonté de le faire rayonner sur la scène internationale. Nous collaborons directement avec plus de 200 artisans à travers le continent.") ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Mission</label><textarea name="about_mission" rows="3"><?= e($settings['about_mission'] ?? "Valoriser l'artisanat africain en le rendant accessible au monde entier, tout en garantissant une rémunération équitable aux créateurs.") ?></textarea></div>
            <div class="form-group"><label>Vision</label><textarea name="about_vision" rows="3"><?= e($settings['about_vision'] ?? "Devenir la référence mondiale de l'e-commerce premium pour les produits africains authentiques.") ?></textarea></div>
        </div>
        <div class="form-group">
            <label>Valeurs</label>
            <textarea name="about_values" rows="3"><?= e($settings['about_values'] ?? "Authenticité, excellence, équité, durabilité et fierté culturelle.") ?></textarea>
        </div>
        <div class="form-group">
            <label>Équipe (une personne par ligne, format : Nom | Poste)</label>
            <textarea name="about_team" rows="4" placeholder="Amadou Diop | Fondateur & CEO"><?= e($settings['about_team'] ?? "Amadou Diop | Fondateur & CEO\nFatou Sow | Directrice Artistique\nMoussa Kane | Responsable Logistique") ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
