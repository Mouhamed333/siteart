<div class="page-header about-header">
    <div class="container"><h1>À propos d'Art' Afric</h1></div>
</div>
<div class="container about-page">
    <section class="about-intro">
        <h2>Notre Histoire</h2>
        <p><?= nl2br(e(Settings::get('about_story', "Fondée en 2020 à Dakar, Art' Afric est née d'une passion profonde pour l'artisanat africain et d'une volonté de le faire rayonner sur la scène internationale. Nous collaborons directement avec plus de 200 artisans à travers le continent."))) ?></p>
    </section>
    <div class="about-grid">
        <div class="about-card"><h3><i class="fas fa-bullseye"></i> Mission</h3><p><?= nl2br(e(Settings::get('about_mission', "Valoriser l'artisanat africain en le rendant accessible au monde entier, tout en garantissant une rémunération équitable aux créateurs."))) ?></p></div>
        <div class="about-card"><h3><i class="fas fa-eye"></i> Vision</h3><p><?= nl2br(e(Settings::get('about_vision', "Devenir la référence mondiale de l'e-commerce premium pour les produits africains authentiques."))) ?></p></div>
        <div class="about-card"><h3><i class="fas fa-heart"></i> Valeurs</h3><p><?= nl2br(e(Settings::get('about_values', "Authenticité, excellence, équité, durabilité et fierté culturelle."))) ?></p></div>
    </div>
    <section class="team-section">
        <h2>Notre Équipe</h2>
        <div class="team-grid">
            <?php
                $teamRaw = Settings::get('about_team', "Amadou Diop | Fondateur & CEO\nFatou Sow | Directrice Artistique\nMoussa Kane | Responsable Logistique");
                $teamLines = array_filter(array_map('trim', explode("\n", $teamRaw)));
            ?>
            <?php foreach ($teamLines as $line): ?>
                <?php
                    $parts = array_map('trim', explode('|', $line, 2));
                    $name = $parts[0] ?? '';
                    $role = $parts[1] ?? '';
                    $initials = '';
                    foreach (explode(' ', $name) as $word) { $initials .= mb_substr($word, 0, 1); }
                ?>
                <?php if ($name !== ''): ?>
                <div class="team-member">
                    <div class="team-avatar"><?= e(mb_strtoupper($initials)) ?></div>
                    <h4><?= e($name) ?></h4>
                    <?php if ($role !== ''): ?><span><?= e($role) ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
</div>

