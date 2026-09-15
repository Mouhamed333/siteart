<div class="page-header"><div class="container"><h1>Mes Adresses</h1></div></div>
<div class="container account-page">
    <?php flashMessages(); ?>
    <div class="account-layout">
        <aside class="account-nav">
            <a href="<?= APP_URL ?>/mon-compte">Profil</a>
            <a href="<?= APP_URL ?>/mon-compte/commandes">Commandes</a>
            <a href="<?= APP_URL ?>/mon-compte/favoris">Favoris</a>
            <a href="<?= APP_URL ?>/mon-compte/adresses" class="active">Adresses</a>
            <a href="<?= APP_URL ?>/deconnexion">Déconnexion</a>
        </aside>
        <div class="account-content">
            <h2>Mes adresses de livraison</h2>
            <p>Vous n'avez pas encore enregistré d'adresse. Vos adresses de livraison apparaîtront ici après votre première commande.</p>
            <a href="<?= APP_URL ?>/boutique" class="btn btn-primary">Découvrir la boutique</a>
        </div>
    </div>
</div>
