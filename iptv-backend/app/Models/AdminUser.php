<?php

namespace App\Models;

use App\Core\Database;

class AdminUser
{
    public static function findByUsername(string $username): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM iptv_admins WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $username, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = Database::connection()->prepare(
            'INSERT INTO iptv_admins (username, password) VALUES (?, ?)'
        );
        $stmt->execute([$username, $hash]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function count(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM iptv_admins')->fetchColumn();
    }

    public static function verify(string $username, string $password): ?array
    {
        $admin = self::findByUsername($username);
        if (!$admin || !password_verify($password, $admin['password'])) {
            return null;
        }
        return $admin;
    }
}
