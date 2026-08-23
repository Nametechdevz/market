<?php

namespace App\Models;

use App\Core\Database;

class IptvUser
{
    public static function all(): array
    {
        return Database::connection()->query(
            'SELECT u.*, s.name AS server_name
             FROM iptv_users u
             JOIN iptv_servers s ON s.id = u.server_id
             ORDER BY u.created_at DESC'
        )->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM iptv_users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByUsername(string $username): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM iptv_users WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO iptv_users
                (username, password, server_id, xtream_username, xtream_password,
                 max_connections, status, expires_at, notes)
             VALUES
                (:username, :password, :server_id, :xtream_username, :xtream_password,
                 :max_connections, :status, :expires_at, :notes)'
        );
        $stmt->execute([
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'server_id' => $data['server_id'],
            'xtream_username' => $data['xtream_username'],
            'xtream_password' => $data['xtream_password'],
            'max_connections' => $data['max_connections'] ?? 1,
            'status' => $data['status'] ?? 'active',
            'expires_at' => $data['expires_at'] ?: null,
            'notes' => $data['notes'] ?? null,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $sql = 'UPDATE iptv_users
                SET username = :username, server_id = :server_id,
                    xtream_username = :xtream_username, xtream_password = :xtream_password,
                    max_connections = :max_connections, status = :status,
                    expires_at = :expires_at, notes = :notes';
        $params = [
            'id' => $id,
            'username' => $data['username'],
            'server_id' => $data['server_id'],
            'xtream_username' => $data['xtream_username'],
            'xtream_password' => $data['xtream_password'],
            'max_connections' => $data['max_connections'] ?? 1,
            'status' => $data['status'] ?? 'active',
            'expires_at' => $data['expires_at'] ?: null,
            'notes' => $data['notes'] ?? null,
        ];

        if (!empty($data['password'])) {
            $sql .= ', password = :password';
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = :id';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM iptv_users WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function verify(string $username, string $password): ?array
    {
        $user = self::findByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        if ($user['status'] !== 'active') {
            return null;
        }
        if (!empty($user['expires_at']) && $user['expires_at'] < date('Y-m-d')) {
            return null;
        }
        return $user;
    }
}
