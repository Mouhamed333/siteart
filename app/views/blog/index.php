<div class="page-header"><div class="container"><h1>Blog</h1></div></div>
<div class="container blog-page">
    <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
        <article class="blog-card">
            <div class="blog-card-image"><div class="blog-placeholder"></div></div>
            <div class="blog-card-body">
                <span class="blog-category"><?= e($post['category']) ?></span>
                <h2><a href="<?= APP_URL ?>/blog/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h2>
                <p><?= e($post['excerpt']) ?></p>
                <div class="blog-meta">
                    <span><?= e($post['first_name']) ?> <?= e($post['last_name']) ?></span>
                    <span><?= date('d M Y', strtotime($post['published_at'])) ?></span>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</div>
