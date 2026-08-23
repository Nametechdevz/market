<?php

namespace App\Controllers;

use App\Core\AdminAuth;
use App\Core\Request;
use App\Core\Response;
use App\Models\AdminUser;

class AuthController
{
    public function showLogin(Request $request): void
    {
        if (AdminAuth::check()) {
            Response::redirect('/dashboard');
            return;
        }
        Response::view('login', ['error' => null]);
    }

    public function login(Request $request): void
    {
        $username = trim((string) $request->input('username', ''));
        $password = (string) $request->input('password', '');

        $admin = AdminUser::verify($username, $password);
        if (!$admin) {
            Response::view('login', ['error' => 'Usuario o contraseña incorrectos']);
            return;
        }

        AdminAuth::login($admin);
        Response::redirect('/dashboard');
    }

    public function logout(Request $request): void
    {
        AdminAuth::logout();
        Response::redirect('/login');
    }
}
