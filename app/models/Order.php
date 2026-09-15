<?php

declare(strict_types=1);

class Order extends Model
{
    protected string $table = 'orders';

    public function createOrder(array $orderData, array $items): int
    {
        $this->db->beginTransaction();
        try {
            $orderId = $this->create($orderData);

            $stmt = $this->db->prepare(
                "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, size, color, total)
                 VALUES (:oid, :pid, :name, :qty, :price, :size, :color, :total)"
            );

            foreach ($items as $item) {
                $stmt->execute([
                    'oid'   => $orderId,
                    'pid'   => $item['product_id'],
                    'name'  => $item['name'],
                    'qty'   => $item['quantity'],
                    'price' => $item['price'],
                    'size'  => $item['size'],
                    'color' => $item['color'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                $updateStock = $this->db->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty2");
                $updateStock->execute(['qty' => $item['quantity'], 'id' => $item['product_id'], 'qty2' => $item['quantity']]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function generateOrderNumber(): string
    {
        return 'AA-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE user_id = :uid ORDER BY created_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getWithItems(int $id): ?array
    {
        $order = $this->find($id);
        if (!$order) return null;

        $stmt = $this->db->prepare(
            "SELECT oi.*, p.images AS product_images
             FROM order_items oi
             LEFT JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = :id"
        );
        $stmt->execute(['id' => $id]);
        $order['items'] = $stmt->fetchAll();
        return $order;
    }

    public function getAllOrders(int $limit = 50, int $offset = 0, ?string $status = null): array
    {
        $where = $status ? "WHERE status = :status" : "";
        $stmt = $this->db->prepare(
            "SELECT o.*, u.first_name, u.last_name, u.email FROM orders o
             LEFT JOIN users u ON o.user_id = u.id {$where}
             ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset"
        );
        if ($status) $stmt->bindValue(':status', $status);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStats(): array
    {
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) as total, SUM(total) as revenue FROM orders WHERE payment_status = 'paid'");
        $stats['orders'] = $stmt->fetch();

        $stmt = $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'client'");
        $stats['clients'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products WHERE is_active = 1");
        $stats['products'] = $stmt->fetchColumn();

        $stmt = $this->db->query(
            "SELECT DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders
             FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at) ORDER BY date"
        );
        $stats['daily'] = $stmt->fetchAll();

        return $stats;
    }
}
