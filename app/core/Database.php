<?php

namespace App\Core;

require_once __DIR__ . '/../../config/database.php';

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $this->connection = new \mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME
            );

            if ($this->connection->connect_error) {
                throw new \Exception("Error de conexión: " . $this->connection->connect_error);
            }

            $this->connection->set_charset(DB_CHARSET);
        } catch (\Exception $e) {
            die("Error de base de datos: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql, $types = null, $params = null)
    {
        if ($params !== null) {
            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Error preparando query: " . $this->connection->error);
            }
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return $stmt;
        }

        $result = $this->connection->query($sql);
        if (!$result) {
            throw new \Exception("Error en query: " . $this->connection->error);
        }
        return $result;
    }

    public function getLastInsertId()
    {
        return $this->connection->insert_id;
    }

    public function getAffectedRows()
    {
        return $this->connection->affected_rows;
    }

    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    private function __clone() {}
    private function __wakeup() {}
}
