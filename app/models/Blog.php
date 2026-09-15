<?php

declare(strict_types=1);

class Blog extends Model
{
    protected string $table = 'blog_posts';

    public function getPublished(int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, u.first_name, u.last_name FROM blog_posts b
             JOIN users u ON b.author_id = u.id
             WHERE b.is_published = 1 ORDER BY b.published_at DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, u.first_name, u.last_name FROM blog_posts b
             JOIN users u ON b.author_id = u.id
             WHERE b.slug = :slug AND b.is_published = 1 LIMIT 1"
        );
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
