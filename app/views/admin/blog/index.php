<div class="admin-toolbar">
    <a href="<?= APP_URL ?>/admin/blog/nouveau" class="btn btn-primary"><i class="fas fa-plus"></i> Nouvel article</a>
</div>

<div class="admin-card">
    <table class="data-table">
        <thead><tr><th>Titre</th><th>Catégorie</th><th>Auteur</th><th>Statut</th><th>Date</th><th></th></tr></thead>
        <tbody>
            <?php if (empty($posts)): ?>
            <tr><td colspan="6">Aucun article pour le moment.</td></tr>
            <?php endif; ?>
            <?php foreach ($posts as $p): ?>
            <tr>
                <td><?= e($p['title']) ?></td>
                <td><?= e($p['category']) ?></td>
                <td><?= e($p['first_name'] . ' ' . $p['last_name']) ?></td>
                <td><span class="status-badge <?= $p['is_published'] ? 'paid' : 'pending' ?>"><?= $p['is_published'] ? 'Publié' : 'Brouillon' ?></span></td>
                <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                <td>
                    <a href="<?= APP_URL ?>/admin/blog/<?= $p['id'] ?>/modifier" class="btn btn-sm">Modifier</a>
                    <form action="<?= APP_URL ?>/admin/blog/<?= $p['id'] ?>/supprimer" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet article ?');">
                        <?= CSRF::field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
