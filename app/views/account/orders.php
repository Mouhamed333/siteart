<div class="page-header"><div class="container"><h1>Mes Commandes</h1></div></div>
<div class="container account-page">
    <div class="account-layout">
        <aside class="account-nav">
            <a href="<?= APP_URL ?>/mon-compte">Profil</a>
            <a href="<?= APP_URL ?>/mon-compte/commandes" class="active">Commandes</a>
            <a href="<?= APP_URL ?>/mon-compte/favoris">Favoris</a>
            <a href="<?= APP_URL ?>/deconnexion">Déconnexion</a>
        </aside>
        <div class="account-content">
            <?php if (empty($orders)): ?>
                <p>Aucune commande pour le moment.</p>
            <?php else: ?>
            <table class="data-table">
                <thead><tr><th>N° Commande</th><th>Date</th><th>Total</th><th>Statut</th></tr></thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= e($order['order_number']) ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        <td><?= formatPrice((float)$order['total']) ?></td>
                        <td><span class="status-badge"><?= e($order['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
