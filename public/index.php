<?php
// Autoload de clases
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

require __DIR__ . '/../config/database.php';

use App\Core\Router;
use App\Core\Auth;
use App\Controllers\AuthController;

$router = new Router();

// Rutas públicas
$router->get('/', function() {
    require __DIR__ . '/../app/views/home.php';
});

$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');

$router->get('/logout', 'AuthController@logout');

// Rutas protegidas (requieren login)
$router->get('/dashboard', function() {
    if (!Auth::check()) {
        Auth::redirect('/login');
    }
    $role = Auth::role();
    if ($role === 'admin') {
        Auth::redirect('/admin/dashboard');
    } elseif ($role === 'seller') {
        Auth::redirect('/seller/dashboard');
    } else {
        Auth::redirect('/marketplace');
    }
});

$router->get('/marketplace', function() {
    require __DIR__ . '/../app/views/marketplace/index.php';
});

// Rutas del vendedor (próximamente)
$router->get('/seller/dashboard', function() {
    if (!Auth::hasRole('seller')) {
        http_response_code(403);
        exit('Acceso denegado');
    }
    require __DIR__ . '/../app/views/seller/dashboard.php';
});

// Rutas del admin (próximamente)
$router->get('/admin/dashboard', function() {
    if (!Auth::hasRole('admin')) {
        http_response_code(403);
        exit('Acceso denegado');
    }
    require __DIR__ . '/../app/views/admin/dashboard.php';
});

// Dispatch
$router->dispatch();
