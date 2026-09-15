<div class="auth-page">
    <div class="auth-card">
        <h1>Mot de passe oublié</h1>
        <?php flashMessages(); ?>
        <form action="<?= APP_URL ?>/mot-de-passe-oublie" method="POST">
            <?= CSRF::field() ?>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <button type="submit" class="btn btn-primary btn-block">Envoyer le lien</button>
        </form>
        <p class="auth-links"><a href="<?= APP_URL ?>/connexion">Retour à la connexion</a></p>
    </div>
</div>
