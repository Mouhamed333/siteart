<div class="admin-card">
    <form action="<?= APP_URL ?>/admin/categories" method="POST" class="admin-inline-form">
        <?= CSRF::field() ?>
        <input type="text" name="name" placeholder="Nom catégorie" required>
        <input type="text" name="description" placeholder="Description">
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
    <table class="data-table">
        <thead><tr><th>ID</th><th>Nom</th><th>Slug</th><th>Produits</th></tr></thead>
        <tbody>
            <?php foreach ($categories as $c): ?>
            <tr><td><?= $c['id'] ?></td><td><?= e($c['name']) ?></td><td><?= e($c['slug']) ?></td><td><?= $c['product_count'] ?? 0 ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
