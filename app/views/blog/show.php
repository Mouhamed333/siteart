<div class="container blog-article">
    <article>
        <span class="blog-category"><?= e($post['category']) ?></span>
        <h1><?= e($post['title']) ?></h1>
        <div class="blog-meta">
            <span>Par <?= e($post['first_name']) ?> <?= e($post['last_name']) ?></span>
            <span><?= date('d M Y', strtotime($post['published_at'])) ?></span>
        </div>
        <div class="blog-content"><?= $post['content'] ?></div>
    </article>
    <a href="<?= APP_URL ?>/blog" class="btn btn-outline">← Retour au blog</a>
</div>
