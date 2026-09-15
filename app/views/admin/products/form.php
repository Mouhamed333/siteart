<div class="admin-card">
    <form action="<?= APP_URL ?>/admin/produits/<?= $product ? 'modifier/' . $product['id'] : 'ajouter' ?>" method="POST" enctype="multipart/form-data">
        <?= CSRF::field() ?>
        <div class="form-row">
            <div class="form-group"><label>Nom *</label><input type="text" name="name" value="<?= e($product['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Catégorie *</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Photos du produit</label>
            <?php $currentImages = $product ? (json_decode($product['images'] ?? '[]', true) ?: []) : []; ?>
            <?php if (!empty($currentImages)): ?>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px;">
                <?php foreach ($currentImages as $img): ?>
                <img src="<?= APP_URL ?>/assets/images/products/<?= e($img) ?>" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #eee;">
                <?php endforeach; ?>
            </div>
            <p style="font-size:12px;color:#888;margin:0 0 10px;">Photos actuelles. Sélectionnez de nouvelles photos ci-dessous pour les remplacer, ou laissez vide pour les conserver.</p>
            <?php endif; ?>
            <input type="file" id="product-images-input" name="images[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple>
            <p style="font-size:12px;color:#888;margin:6px 0 0;">Vous pouvez sélectionner plusieurs photos à la fois (maintenez Ctrl ou Cmd enfoncé dans la fenêtre de sélection). Formats acceptés : JPEG, PNG, WEBP, GIF. Taille max par photo : <?= (int)(MAX_UPLOAD_SIZE / 1024 / 1024) ?> Mo.</p>
            <div id="new-images-preview" style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;"></div>
        </div>

        <script>
        (function () {
            var input = document.getElementById('product-images-input');
            var preview = document.getElementById('new-images-preview');
            var dt = new DataTransfer();

            input.addEventListener('change', function () {
                // Ajoute les nouveaux fichiers choisis à la sélection déjà en cours
                Array.from(input.files).forEach(function (file) {
                    dt.items.add(file);
                });
                input.files = dt.files;
                renderPreview();
            });

            function renderPreview() {
                preview.innerHTML = '';
                Array.from(dt.files).forEach(function (file, index) {
                    var url = URL.createObjectURL(file);
                    var wrap = document.createElement('div');
                    wrap.style.cssText = 'position:relative;width:80px;height:80px;';
                    wrap.innerHTML =
                        '<img src="' + url + '" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #eee;">' +
                        '<button type="button" data-index="' + index + '" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;border:none;background:#C0392B;color:#fff;cursor:pointer;font-size:12px;line-height:1;">×</button>';
                    preview.appendChild(wrap);
                });
                preview.querySelectorAll('button').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var idx = parseInt(btn.getAttribute('data-index'), 10);
                        var newDt = new DataTransfer();
                        Array.from(dt.files).forEach(function (file, i) {
                            if (i !== idx) newDt.items.add(file);
                        });
                        dt = newDt;
                        input.files = dt.files;
                        renderPreview();
                    });
                });
            }
        })();
        </script>

        <div class="form-group"><label>Description courte</label><input type="text" name="short_description" value="<?= e($product['short_description'] ?? '') ?>"></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="5"><?= e($product['description'] ?? '') ?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Prix *</label><input type="number" name="price" value="<?= $product['price'] ?? '' ?>" required></div>
            <div class="form-group"><label>Prix promo</label><input type="number" name="sale_price" value="<?= $product['sale_price'] ?? '' ?>"></div>
            <div class="form-group"><label>Stock</label><input type="number" name="stock" value="<?= $product['stock'] ?? 0 ?>"></div>
            <div class="form-group"><label>SKU</label><input type="text" name="sku" value="<?= e($product['sku'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Matière</label><input type="text" name="material" value="<?= e($product['material'] ?? '') ?>"></div>
            <div class="form-group"><label>Couleur</label><input type="text" name="color" value="<?= e($product['color'] ?? '') ?>"></div>
            <div class="form-group"><label>Tailles (virgules)</label><input type="text" name="sizes" value="<?= e(implode(',', json_decode($product['sizes'] ?? '[]', true) ?: [])) ?>"></div>
        </div>
        <div class="form-row">
            <label><input type="checkbox" name="is_featured" <?= !empty($product['is_featured']) ? 'checked' : '' ?>> Vedette</label>
            <label><input type="checkbox" name="is_new" <?= !empty($product['is_new']) ? 'checked' : '' ?>> Nouveau</label>
            <label><input type="checkbox" name="is_promo" <?= !empty($product['is_promo']) ? 'checked' : '' ?>> Promo</label>
        </div>
        <button type="submit" class="btn btn-primary"><?= $product ? 'Mettre à jour' : 'Créer' ?></button>
    </form>
</div>
