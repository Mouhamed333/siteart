<?php

declare(strict_types=1);

class Settings extends Model
{
    protected string $table = 'settings';

    /** @var array<string,string>|null */
    private static ?array $cache = null;

    public static function get(string $key, string $default = ''): string
    {
        self::loadAll();
        return self::$cache[$key] ?? $default;
    }

    /** @return array<string,string> */
    public static function getAll(): array
    {
        self::loadAll();
        return self::$cache;
    }

    public static function set(string $key, string $value): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare(
            "INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE setting_value = :v2"
        );
        $stmt->execute(['k' => $key, 'v' => $value, 'v2' => $value]);

        if (self::$cache !== null) {
            self::$cache[$key] = $value;
        }
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::set($key, (string) $value);
        }
    }

    private static function loadAll(): void
    {
        if (self::$cache !== null) {
            return;
        }
        self::$cache = [];
        try {
            $stmt = Database::getInstance()->query("SELECT setting_key, setting_value FROM settings");
            foreach ($stmt->fetchAll() as $row) {
                self::$cache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Throwable $e) {
            // La table n'existe pas encore (ancienne base non mise à jour) : on garde le cache vide,
            // les valeurs par défaut passées à Settings::get() seront utilisées.
            self::$cache = [];
        }
    }
}
