<div class="admin-card">
    <table class="data-table">
        <thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Inscrit le</th></tr></thead>
        <tbody>
            <?php foreach ($customers as $c): ?>
            <tr><td><?= $c['id'] ?></td><td><?= e($c['first_name'] . ' ' . $c['last_name']) ?></td><td><?= e($c['email']) ?></td><td><?= e($c['phone'] ?? '') ?></td><td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
