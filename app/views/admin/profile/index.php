<div class="admin-card" style="max-width:520px;">
    <form action="<?= APP_URL ?>/admin/profil" method="POST">
        <?= CSRF::field() ?>

        <div class="form-row">
            <div class="form-group"><label>Prénom</label><input type="text" name="first_name" required value="<?= e($user['first_name']) ?>"></div>
            <div class="form-group"><label>Nom</label><input type="text" name="last_name" required value="<?= e($user['last_name']) ?>"></div>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?= e($user['email']) ?>">
        </div>

        <hr style="margin:20px 0;border:none;border-top:1px solid var(--gray-200);">

        <div class="form-group">
            <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="new_password" minlength="8" autocomplete="new-password">
        </div>
        <div class="form-group">
            <label>Confirmer le nouveau mot de passe</label>
            <input type="password" name="new_password_confirm" minlength="8" autocomplete="new-password">
        </div>

        <hr style="margin:20px 0;border:none;border-top:1px solid var(--gray-200);">

        <div class="form-group">
            <label>Mot de passe actuel * <span style="font-weight:400;color:#888;">(requis pour confirmer tout changement)</span></label>
            <input type="password" name="current_password" required autocomplete="current-password">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
