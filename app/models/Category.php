<?php

declare(strict_types=1);

class Category extends Model
{
    protected string $table = 'categories';

    public function getActive(): array
    {
        $stmt = $this->db->query(
            "SELECT c.*, COUNT(p.id) as product_count
             FROM categories c LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
             WHERE c.is_active = 1 GROUP BY c.id ORDER BY c.sort_order"
        );
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
