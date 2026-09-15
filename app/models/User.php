<?php

declare(strict_types=1);

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function register(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = 'client';
        return $this->create($data);
    }

    public function logLogin(int $userId, string $email, bool $success): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO login_logs (user_id, email, ip_address, user_agent, success) VALUES (:uid, :email, :ip, :ua, :success)"
        );
        $stmt->execute([
            'uid'     => $userId ?: null,
            'email'   => $email,
            'ip'      => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'ua'      => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'success' => $success ? 1 : 0,
        ]);
    }

    public function getClients(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, first_name, last_name, email, phone, created_at FROM {$this->table} WHERE role = 'client' ORDER BY created_at DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
