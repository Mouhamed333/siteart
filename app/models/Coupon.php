<?php

declare(strict_types=1);

class Coupon extends Model
{
    protected string $table = 'coupons';

    public function findByCode(string $code): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE code = :code AND is_active = 1
             AND (starts_at IS NULL OR starts_at <= NOW())
             AND (expires_at IS NULL OR expires_at >= NOW())
             AND (max_uses IS NULL OR used_count < max_uses) LIMIT 1"
        );
        $stmt->execute(['code' => strtoupper($code)]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function incrementUsage(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
