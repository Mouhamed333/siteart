<div class="admin-card">
    <table class="data-table">
        <thead><tr><th>N° Commande</th><th>Client</th><th>Total</th><th>Statut</th><th>Date</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= e($o['order_number']) ?></td>
                <td><?= e(($o['first_name'] ?? '') . ' ' . ($o['last_name'] ?? '')) ?></td>
                <td><?= formatPrice((float)$o['total']) ?></td>
                <td><span class="status-badge <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
                <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                <td><a href="<?= APP_URL ?>/admin/commandes/<?= $o['id'] ?>">Voir</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
