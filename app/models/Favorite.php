<?php

declare(strict_types=1);

class Favorite extends Model
{
    protected string $table = 'favorites';

    public function toggle(int $userId, int $productId): bool
    {
        $existing = $this->db->prepare("SELECT id FROM favorites WHERE user_id = :uid AND product_id = :pid");
        $existing->execute(['uid' => $userId, 'pid' => $productId]);
        $row = $existing->fetch();

        if ($row) {
            $this->delete($row['id']);
            return false;
        }

        $this->create(['user_id' => $userId, 'product_id' => $productId]);
        return true;
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name FROM favorites f
             JOIN products p ON f.product_id = p.id
             JOIN categories c ON p.category_id = c.id
             WHERE f.user_id = :uid ORDER BY f.created_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function isFavorite(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = :uid AND product_id = :pid");
        $stmt->execute(['uid' => $userId, 'pid' => $productId]);
        return (bool) $stmt->fetch();
    }
}
