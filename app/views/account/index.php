<div class="page-header"><div class="container"><h1>Mon Compte</h1></div></div>
<div class="container account-page">
    <?php flashMessages(); ?>
    <div class="account-layout">
        <aside class="account-nav">
            <a href="<?= APP_URL ?>/mon-compte" class="active">Profil</a>
            <a href="<?= APP_URL ?>/mon-compte/commandes">Commandes</a>
            <a href="<?= APP_URL ?>/mon-compte/favoris">Favoris</a>
            <a href="<?= APP_URL ?>/mon-compte/adresses">Adresses</a>
            <a href="<?= APP_URL ?>/deconnexion">Déconnexion</a>
        </aside>
        <div class="account-content">
            <h2>Bienvenue, <?= e($user['first_name']) ?> !</h2>
            <form action="<?= APP_URL ?>/mon-compte/profil" method="POST">
                <?= CSRF::field() ?>
                <div class="form-row">
                    <div class="form-group"><label>Prénom</label><input type="text" name="first_name" value="<?= e($user['first_name']) ?>"></div>
                    <div class="form-group"><label>Nom</label><input type="text" name="last_name" value="<?= e($user['last_name']) ?>"></div>
                </div>
                <div class="form-group"><label>Email</label><input type="email" value="<?= e($user['email']) ?>" disabled></div>
                <div class="form-group"><label>Téléphone</label><input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>"></div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
