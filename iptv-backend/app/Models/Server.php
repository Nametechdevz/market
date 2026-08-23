<?php

namespace App\Models;

use App\Core\Database;

class Server
{
    public static function all(): array
    {
        return Database::connection()
            ->query('SELECT * FROM iptv_servers ORDER BY created_at DESC')
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM iptv_servers WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO iptv_servers (name, dns, port, use_https, status, notes)
             VALUES (:name, :dns, :port, :use_https, :status, :notes)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'dns' => $data['dns'],
            'port' => $data['port'],
            'use_https' => $data['use_https'] ? 1 : 0,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE iptv_servers
             SET name = :name, dns = :dns, port = :port, use_https = :use_https,
                 status = :status, notes = :notes
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'dns' => $data['dns'],
            'port' => $data['port'],
            'use_https' => $data['use_https'] ? 1 : 0,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM iptv_servers WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function inUseCount(int $id): int
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM iptv_users WHERE server_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public static function baseUrl(array $server): string
    {
        $scheme = ((int) $server['use_https']) === 1 ? 'https' : 'http';
        return sprintf('%s://%s:%d', $scheme, $server['dns'], $server['port']);
    }
}
