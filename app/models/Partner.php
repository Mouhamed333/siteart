<?php

declare(strict_types=1);

class Partner extends Model
{
    protected string $table = 'partners';

    public function getActive(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order, id"
        );
        return $stmt->fetchAll();
    }

    public function getAllOrdered(): array
    {
        $stmt = $this->db->query("SELECT * FROM partners ORDER BY sort_order, id");
        return $stmt->fetchAll();
    }
}
