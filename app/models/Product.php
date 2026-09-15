<?php

declare(strict_types=1);

class Product extends Model
{
    protected string $table = 'products';

    public function getAll(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $where = ['p.is_active = 1'];
        $params = [];

        if (!empty($filters['category'])) {
            $where[] = 'c.slug = :category';
            $params['category'] = $filters['category'];
        }
        if (!empty($filters['min_price'])) {
            $where[] = 'COALESCE(p.sale_price, p.price) >= :min_price';
            $params['min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = 'COALESCE(p.sale_price, p.price) <= :max_price';
            $params['max_price'] = $filters['max_price'];
        }
        if (!empty($filters['color'])) {
            $where[] = 'p.color LIKE :color';
            $params['color'] = '%' . $filters['color'] . '%';
        }
        if (!empty($filters['material'])) {
            $where[] = 'p.material LIKE :material';
            $params['material'] = '%' . $filters['material'] . '%';
        }
        if (!empty($filters['promo'])) {
            $where[] = 'p.is_promo = 1';
        }
        if (!empty($filters['new'])) {
            $where[] = 'p.is_new = 1';
        }
        if (!empty($filters['search'])) {
            $where[] = '(p.name LIKE :search OR p.description LIKE :search2)';
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }

        $orderBy = match ($filters['sort'] ?? 'popular') {
            'price_asc'  => 'COALESCE(p.sale_price, p.price) ASC',
            'price_desc' => 'COALESCE(p.sale_price, p.price) DESC',
            'newest'     => 'p.created_at DESC',
            'rating'     => 'p.rating_avg DESC',
            default      => 'p.views DESC, p.rating_count DESC',
        };

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM products p JOIN categories c ON p.category_id = c.id WHERE {$whereClause}"
        );
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM products p JOIN categories c ON p.category_id = c.id
             WHERE {$whereClause}
             ORDER BY {$orderBy}
             LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'products' => $stmt->fetchAll(),
            'total'    => $total,
            'pages'    => (int) ceil($total / $perPage),
            'page'     => $page,
        ];
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM products p JOIN categories c ON p.category_id = c.id
             WHERE p.slug = :slug AND p.is_active = 1 LIMIT 1"
        );
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getFeatured(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_featured = 1 AND p.is_active = 1 ORDER BY p.created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getNew(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_new = 1 AND p.is_active = 1 ORDER BY p.created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPromo(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_promo = 1 AND p.is_active = 1 ORDER BY p.created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSimilar(int $categoryId, int $productId, int $limit = 4): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.category_id = :cat AND p.id != :id AND p.is_active = 1
             ORDER BY RAND() LIMIT :limit"
        );
        $stmt->bindValue(':cat', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE products SET views = views + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function search(string $query, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.id, p.name, p.slug, p.price, p.sale_price, p.images, c.name as category_name
             FROM products p JOIN categories c ON p.category_id = c.id
             WHERE p.is_active = 1 AND (p.name LIKE :q OR p.description LIKE :q2 OR c.name LIKE :q3)
             ORDER BY p.rating_avg DESC LIMIT :limit"
        );
        $like = '%' . $query . '%';
        $stmt->bindValue(':q', $like);
        $stmt->bindValue(':q2', $like);
        $stmt->bindValue(':q3', $like);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEffectivePrice(array $product): float
    {
        return (float) ($product['sale_price'] ?? $product['price']);
    }
}
