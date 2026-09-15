<div class="admin-card">
    <form action="<?= APP_URL ?>/admin/coupons" method="POST" class="admin-inline-form">
        <?= CSRF::field() ?>
        <input type="text" name="code" placeholder="Code" required>
        <select name="type"><option value="percentage">%</option><option value="fixed">Fixe</option></select>
        <input type="number" name="value" placeholder="Valeur" required>
        <input type="number" name="min_order" placeholder="Min. commande">
        <input type="datetime-local" name="expires_at">
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
    <table class="data-table">
        <thead><tr><th>Code</th><th>Type</th><th>Valeur</th><th>Utilisations</th><th>Expire</th></tr></thead>
        <tbody>
            <?php foreach ($coupons as $c): ?>
            <tr><td><?= e($c['code']) ?></td><td><?= e($c['type']) ?></td><td><?= $c['value'] ?></td><td><?= $c['used_count'] ?>/<?= $c['max_uses'] ?? '∞' ?></td><td><?= $c['expires_at'] ? date('d/m/Y', strtotime($c['expires_at'])) : '-' ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
