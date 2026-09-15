<?php

declare(strict_types=1);

class Review extends Model
{
    protected string $table = 'reviews';

    public function getByProduct(int $productId, bool $approvedOnly = true): array
    {
        $where = $approvedOnly ? 'AND r.is_approved = 1' : '';
        $stmt = $this->db->prepare(
            "SELECT r.*, u.first_name, u.last_name FROM reviews r
             JOIN users u ON r.user_id = u.id
             WHERE r.product_id = :pid {$where} ORDER BY r.created_at DESC"
        );
        $stmt->execute(['pid' => $productId]);
        return $stmt->fetchAll();
    }

    public function addReview(array $data): int
    {
        $id = $this->create($data);
        $this->updateProductRating($data['product_id']);
        return $id;
    }

    private function updateProductRating(int $productId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE products SET rating_avg = (
                SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE product_id = :pid AND is_approved = 1
             ), rating_count = (
                SELECT COUNT(*) FROM reviews WHERE product_id = :pid2 AND is_approved = 1
             ) WHERE id = :pid3"
        );
        $stmt->execute(['pid' => $productId, 'pid2' => $productId, 'pid3' => $productId]);
    }
}
