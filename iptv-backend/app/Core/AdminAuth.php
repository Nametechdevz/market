<?php

namespace App\Core;

class AdminAuth
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function login(array $admin): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['admin_id']);
    }

    public static function id(): ?int
    {
        self::start();
        return $_SESSION['admin_id'] ?? null;
    }

    public static function username(): ?string
    {
        self::start();
        return $_SESSION['admin_username'] ?? null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            Response::redirect('/login');
            exit;
        }
    }
}
