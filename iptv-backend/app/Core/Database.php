<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function connection(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = self::loadConfig();
        $dsn = self::buildDsn($config);

        try {
            self::$instance = new PDO($dsn, $config['user'] ?? null, $config['pass'] ?? null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('No se pudo conectar a la base de datos: ' . $e->getMessage());
        }

        return self::$instance;
    }

    /**
     * Permite inyectar una conexión ya abierta (usado por el smoke test con SQLite).
     */
    public static function setConnection(PDO $pdo): void
    {
        self::$instance = $pdo;
    }

    private static function loadConfig(): array
    {
        $configFile = __DIR__ . '/../../config/config.php';
        $config = is_file($configFile) ? require $configFile : [];
        $db = $config['db'] ?? [];

        // Variables de entorno tienen prioridad (útil para tests / despliegues).
        return [
            'driver' => getenv('DB_DRIVER') ?: ($db['driver'] ?? 'mysql'),
            'host' => getenv('DB_HOST') ?: ($db['host'] ?? 'localhost'),
            'port' => getenv('DB_PORT') ?: ($db['port'] ?? 3306),
            'name' => getenv('DB_NAME') ?: ($db['name'] ?? 'iptv_panel'),
            'user' => getenv('DB_USER') ?: ($db['user'] ?? 'root'),
            'pass' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($db['pass'] ?? ''),
        ];
    }

    private static function buildDsn(array $config): string
    {
        if ($config['driver'] === 'sqlite') {
            $path = getenv('DB_SQLITE_PATH') ?: ':memory:';
            return "sqlite:{$path}";
        }

        return sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['name']
        );
    }
}
