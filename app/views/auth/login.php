<div class="auth-page">
    <div class="auth-card">
        <h1>Connexion</h1>
        <?php flashMessages(); ?>
        <form action="<?= APP_URL ?>/connexion" method="POST">
            <?= CSRF::field() ?>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>
        <p class="auth-links"><a href="<?= APP_URL ?>/mot-de-passe-oublie">Mot de passe oublié ?</a> · <a href="<?= APP_URL ?>/inscription">Créer un compte</a></p>
    </div>
</div>
