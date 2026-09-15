<div class="auth-page">
    <div class="auth-card">
        <h1>Inscription</h1>
        <?php flashMessages(); ?>
        <form action="<?= APP_URL ?>/inscription" method="POST">
            <?= CSRF::field() ?>
            <div class="form-row">
                <div class="form-group"><label>Prénom</label><input type="text" name="first_name" required></div>
                <div class="form-group"><label>Nom</label><input type="text" name="last_name" required></div>
            </div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Téléphone</label><input type="tel" name="phone"></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" minlength="8" required></div>
            <div class="form-group"><label>Confirmer le mot de passe</label><input type="password" name="password_confirm" required></div>
            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>
        <p class="auth-links"><a href="<?= APP_URL ?>/connexion">Déjà un compte ? Se connecter</a></p>
    </div>
</div>
