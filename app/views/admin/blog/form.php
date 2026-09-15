<div class="admin-card">
    <form action="<?= $post ? APP_URL . '/admin/blog/' . $post['id'] . '/modifier' : APP_URL . '/admin/blog' ?>" method="POST">
        <?= CSRF::field() ?>

        <div class="form-group">
            <label>Titre *</label>
            <input type="text" name="title" required value="<?= e($post['title'] ?? '') ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Catégorie</label>
                <input type="text" name="category" value="<?= e($post['category'] ?? 'Culture') ?>">
            </div>
            <div class="form-group" style="align-self:flex-end;">
                <label><input type="checkbox" name="is_published" value="1" <?= !empty($post['is_published']) ? 'checked' : '' ?>> Publié (visible sur le site)</label>
            </div>
        </div>

        <div class="form-group">
            <label>Extrait (résumé court affiché dans la liste)</label>
            <textarea name="excerpt" rows="2"><?= e($post['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Contenu *</label>
            <textarea name="content" rows="14" required><?= e($post['content'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><?= $post ? 'Mettre à jour' : 'Créer l\'article' ?></button>
        <a href="<?= APP_URL ?>/admin/blog" class="btn">Annuler</a>
    </form>
</div>
