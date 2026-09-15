<div class="admin-card">
    <div class="admin-toolbar">
        <a href="<?= APP_URL ?>/admin/produits/ajouter" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter</a>
    </div>
    <table class="data-table">
        <thead><tr><th>ID</th><th>Nom</th><th>Prix</th><th>Stock</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= e($p['name']) ?></td>
                <td><?= formatPrice((float)$p['price']) ?></td>
                <td><?= $p['stock'] ?></td>
                <td><?= $p['is_active'] ? 'Actif' : 'Inactif' ?></td>
                <td>
                    <a href="<?= APP_URL ?>/admin/produits/modifier/<?= $p['id'] ?>" class="btn-sm">Modifier</a>
                    <form action="<?= APP_URL ?>/admin/produits/supprimer/<?= $p['id'] ?>" method="POST" style="display:inline"><?= CSRF::field() ?><button class="btn-sm btn-danger">Suppr.</button></form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
