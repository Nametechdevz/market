<?php

namespace App\Core;

class Auth
{
    public static function login($userId, $userData)
    {
        session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['name'] = $userData['name'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['login_time'] = time();
    }

    public static function logout()
    {
        session_start();
        session_destroy();
    }

    public static function isLoggedIn()
    {
        session_start();
        return isset($_SESSION['user_id']);
    }

    public static function user()
    {
        session_start();
        return $_SESSION ?? null;
    }

    public static function userId()
    {
        session_start();
        return $_SESSION['user_id'] ?? null;
    }

    public static function role()
    {
        session_start();
        return $_SESSION['role'] ?? null;
    }

    public static function check($role = null)
    {
        if (!self::isLoggedIn()) {
            return false;
        }
        if ($role !== null) {
            return self::role() === $role;
        }
        return true;
    }

    public static function hasRole($roles)
    {
        $currentRole = self::role();
        return in_array($currentRole, is_array($roles) ? $roles : [$roles]);
    }

    public static function redirect($path)
    {
        header("Location: " . APP_URL . $path);
        exit;
    }

    public static function isSessionExpired()
    {
        session_start();
        if (isset($_SESSION['login_time'])) {
            if (time() - $_SESSION['login_time'] > SESSION_TIMEOUT) {
                self::logout();
                return true;
            }
        }
        return false;
    }
}
