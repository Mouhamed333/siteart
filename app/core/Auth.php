<?php

declare(strict_types=1);

class Auth
{
    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function check(): bool
    {
        return Session::has('user');
    }

    public static function id(): ?int
    {
        return Session::get('user')['id'] ?? null;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && in_array($user['role'], ['admin', 'employee'], true);
    }

    public static function isClient(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'client';
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        unset($user['password']);
        Session::set('user', $user);
        Session::set('_created', time());
    }

    public static function logout(): void
    {
        Session::remove('user');
        session_regenerate_id(true);
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Veuillez vous connecter pour accéder à cette page.');
            header('Location: ' . APP_URL . '/connexion');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            http_response_code(403);
            Session::flash('error', 'Accès non autorisé.');
            header('Location: ' . APP_URL . '/admin/connexion');
            exit;
        }
    }

    public static function checkLoginAttempts(string $email): bool
    {
        $key = 'login_attempts_' . md5($email);
        $attempts = Session::get($key, ['count' => 0, 'locked_until' => 0]);

        if ($attempts['locked_until'] > time()) {
            return false;
        }

        return $attempts['count'] < MAX_LOGIN_ATTEMPTS;
    }

    public static function recordLoginAttempt(string $email, bool $success): void
    {
        $key = 'login_attempts_' . md5($email);

        if ($success) {
            Session::remove($key);
            return;
        }

        $attempts = Session::get($key, ['count' => 0, 'locked_until' => 0]);
        $attempts['count']++;

        if ($attempts['count'] >= MAX_LOGIN_ATTEMPTS) {
            $attempts['locked_until'] = time() + LOGIN_LOCKOUT_TIME;
        }

        Session::set($key, $attempts);
    }
}
