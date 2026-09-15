<div class="admin-card">
    <h2>Commande #<?= e($order['order_number']) ?></h2>
    <p><strong>Statut:</strong> <span class="status-badge <?= e($order['status']) ?>"><?= e($order['status']) ?></span> | <strong>Paiement:</strong> <span class="status-badge <?= e($order['payment_status']) ?>"><?= e($order['payment_status']) ?></span></p>

    <form action="<?= APP_URL ?>/admin/commandes/<?= $order['id'] ?>/statut" method="POST" class="admin-inline-form">
        <?= CSRF::field() ?>
        <div class="form-group">
            <label>Statut de la commande</label>
            <select name="status">
                <?php $statuses = ['pending' => 'En attente', 'processing' => 'En traitement', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée', 'refunded' => 'Remboursée']; ?>
                <?php foreach ($statuses as $value => $label): ?>
                <option value="<?= $value ?>" <?= $order['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Statut du paiement</label>
            <select name="payment_status">
                <?php $paymentStatuses = ['pending' => 'En attente', 'paid' => 'Payé', 'failed' => 'Échoué', 'refunded' => 'Remboursé']; ?>
                <?php foreach ($paymentStatuses as $value => $label): ?>
                <option value="<?= $value ?>" <?= $order['payment_status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>

    <table class="data-table">
        <thead><tr><th></th><th>Produit</th><th>Qté</th><th>Prix</th><th>Total</th></tr></thead>
        <tbody>
            <?php foreach ($order['items'] as $item): ?>
            <?php
                $itemImages = json_decode($item['product_images'] ?? '[]', true) ?: [];
                $itemImage = $itemImages[0] ?? 'placeholder.jpg';
            ?>
            <tr>
                <td><img src="<?= APP_URL ?>/assets/images/products/<?= e($itemImage) ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;"></td>
                <td><?= e($item['product_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= formatPrice((float)$item['unit_price']) ?></td>
                <td><?= formatPrice((float)$item['total']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Total:</strong> <?= formatPrice((float)$order['total']) ?></p>
</div>
