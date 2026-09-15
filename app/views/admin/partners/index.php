<div class="admin-card">
    <h2 style="margin-top:0;">Ajouter un partenaire</h2>
    <form action="<?= APP_URL ?>/admin/partenaires" method="POST" class="admin-inline-form">
        <?= CSRF::field() ?>
        <div class="form-group"><label>Nom *</label><input type="text" name="name" placeholder="Nom du partenaire" required></div>
        <div class="form-group"><label>Site web (optionnel)</label><input type="url" name="website_url" placeholder="https://..."></div>
        <div class="form-group"><label>Ordre d'affichage</label><input type="number" name="sort_order" value="0" style="width:90px;"></div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<div class="admin-card">
    <table class="data-table">
        <thead><tr><th>Nom</th><th>Site web</th><th>Ordre</th><th>Statut</th><th></th></tr></thead>
        <tbody>
            <?php if (empty($partners)): ?>
            <tr><td colspan="5">Aucun partenaire pour le moment.</td></tr>
            <?php endif; ?>
            <?php foreach ($partners as $p): ?>
            <tr>
                <td><?= e($p['name']) ?></td>
                <td><?= $p['website_url'] ? '<a href="' . e($p['website_url']) . '" target="_blank">' . e($p['website_url']) . '</a>' : '—' ?></td>
                <td><?= (int) $p['sort_order'] ?></td>
                <td><span class="status-badge <?= $p['is_active'] ? 'paid' : 'cancelled' ?>"><?= $p['is_active'] ? 'Actif' : 'Masqué' ?></span></td>
                <td>
                    <form action="<?= APP_URL ?>/admin/partenaires/<?= $p['id'] ?>/toggle" method="POST" style="display:inline;">
                        <?= CSRF::field() ?>
                        <button type="submit" class="btn btn-sm"><?= $p['is_active'] ? 'Masquer' : 'Afficher' ?></button>
                    </form>
                    <form action="<?= APP_URL ?>/admin/partenaires/<?= $p['id'] ?>/supprimer" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce partenaire ?');">
                        <?= CSRF::field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
