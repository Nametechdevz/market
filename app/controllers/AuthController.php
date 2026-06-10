<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showRegister()
    {
        if (Auth::isLoggedIn()) {
            Auth::redirect('/dashboard');
        }
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $errors = [];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $role = $_POST['role'] ?? 'buyer';

        // Validaciones
        if (strlen($name) < 3) {
            $errors[] = 'El nombre debe tener al menos 3 caracteres';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }
        if ($this->userModel->emailExists($email)) {
            $errors[] = 'El email ya está registrado';
        }
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres';
        }
        if ($password !== $passwordConfirm) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        if (!in_array($role, ['buyer', 'seller'])) {
            $errors[] = 'Rol inválido';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            Auth::redirect('/register');
        }

        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ]);

        if ($userId) {
            $_SESSION['success'] = 'Registro exitoso. Por favor inicia sesión.';
            Auth::redirect('/login');
        } else {
            $_SESSION['errors'] = ['Error al registrar el usuario'];
            Auth::redirect('/register');
        }
    }

    public function showLogin()
    {
        if (Auth::isLoggedIn()) {
            Auth::redirect('/dashboard');
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['errors'] = ['Email y contraseña son requeridos'];
            Auth::redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['errors'] = ['Email o contraseña incorrectos'];
            Auth::redirect('/login');
        }

        Auth::login($user['id'], $user);

        if ($user['role'] === 'admin') {
            Auth::redirect('/admin/dashboard');
        } elseif ($user['role'] === 'seller') {
            Auth::redirect('/seller/dashboard');
        } else {
            Auth::redirect('/marketplace');
        }
    }

    public function logout()
    {
        Auth::logout();
        Auth::redirect('/');
    }
}
