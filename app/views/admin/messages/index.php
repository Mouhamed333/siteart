<div class="admin-card">
    <table class="data-table">
        <thead><tr><th>Nom</th><th>Email</th><th>Sujet</th><th>Date</th><th>Lu</th></tr></thead>
        <tbody>
            <?php foreach ($messages as $m): ?>
            <tr class="<?= $m['is_read'] ? '' : 'unread' ?>">
                <td><?= e($m['name']) ?></td><td><?= e($m['email']) ?></td><td><?= e($m['subject']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td><td><?= $m['is_read'] ? '✓' : '●' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
