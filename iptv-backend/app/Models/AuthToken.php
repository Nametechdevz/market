<?php

namespace App\Models;

use App\Core\Database;

class AuthToken
{
    private static function ttlDays(): int
    {
        $configFile = __DIR__ . '/../../config/config.php';
        $config = is_file($configFile) ? require $configFile : [];
        return (int) (getenv('API_TOKEN_TTL_DAYS') ?: ($config['api_token_ttl_days'] ?? 30));
    }

    public static function issue(int $userId, ?string $deviceInfo = null): string
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . self::ttlDays() . ' days'));

        $stmt = Database::connection()->prepare(
            'INSERT INTO iptv_auth_tokens (user_id, token, device_info, expires_at)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $token, $deviceInfo, $expiresAt]);

        return $token;
    }

    public static function validate(string $token): ?int
    {
        $stmt = Database::connection()->prepare(
            "SELECT user_id, expires_at FROM iptv_auth_tokens WHERE token = ?"
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }
        if (strtotime($row['expires_at']) < time()) {
            return null;
        }

        return (int) $row['user_id'];
    }

    public static function revoke(string $token): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM iptv_auth_tokens WHERE token = ?');
        $stmt->execute([$token]);
    }
}
