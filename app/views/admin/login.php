<div class="auth-page"><div class="auth-card">
    <h1>Administration</h1>
    <?php flashMessages(); ?>
    <form action="<?= APP_URL ?>/admin/connexion" method="POST">
        <?= CSRF::field() ?>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
        <button type="submit" class="btn btn-primary btn-block">Connexion</button>
    </form>
</div></div>
