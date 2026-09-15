<div class="container confirmation-page">
    <div class="confirmation-box">
        <div class="confirmation-icon"><i class="fas fa-check-circle"></i></div>
        <h1>Commande confirmée !</h1>
        <p>Merci pour votre achat. Votre commande <strong>#<?= e($order['order_number']) ?></strong> a été enregistrée.</p>
        <div class="order-details">
            <p><strong>Total:</strong> <?= formatPrice((float)$order['total']) ?></p>
            <p><strong>Statut:</strong> <?= e(ucfirst($order['status'])) ?></p>
            <p><strong>Paiement:</strong> <?= e(ucfirst(str_replace('_', ' ', $order['payment_method']))) ?></p>
        </div>
        <div class="confirmation-actions">
            <?php
                $waNumber = preg_replace('/\D/', '', Settings::get('whatsapp_order_number', '221777929623'));
                $lines = ["Bonjour Art' Afric, voici ma commande #" . $order['order_number'] . " :"];
                foreach (($order['items'] ?? []) as $item) {
                    $lines[] = '- ' . $item['quantity'] . ' x ' . $item['product_name'] . ' (' . formatPrice((float) $item['price']) . ')';
                }
                $lines[] = 'Total : ' . formatPrice((float) $order['total']);
                $waMessage = implode("\n", $lines);
            ?>
            <?php if ($waNumber !== ''): ?>
            <a href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode($waMessage) ?>" target="_blank" rel="noopener" class="btn" style="background:#25D366;color:#fff;">
                <i class="fab fa-whatsapp"></i> Envoyer ma commande sur WhatsApp
            </a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/mon-compte/commandes" class="btn btn-primary">Voir mes commandes</a>
            <a href="<?= APP_URL ?>/boutique" class="btn btn-outline">Continuer mes achats</a>
        </div>
    </div>
</div>
