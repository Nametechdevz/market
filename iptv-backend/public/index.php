<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\Api\AuthApiController;
use App\Controllers\Api\LiveApiController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ServerController;
use App\Controllers\UserController;
use App\Core\Request;
use App\Core\Router;

$router = new Router();
$request = new Request();

// --- Panel de administración (web, sesión) ---
$auth = new AuthController();
$router->get('/', fn ($req) => header('Location: /login'));
$router->get('/login', [$auth, 'showLogin']);
$router->post('/login', [$auth, 'login']);
$router->post('/logout', [$auth, 'logout']);

$dashboard = new DashboardController();
$router->get('/dashboard', [$dashboard, 'index']);

$servers = new ServerController();
$router->get('/servers', [$servers, 'index']);
$router->get('/servers/create', [$servers, 'create']);
$router->post('/servers', [$servers, 'store']);
$router->get('/servers/{id}/edit', [$servers, 'edit']);
$router->post('/servers/{id}/update', [$servers, 'update']);
$router->post('/servers/{id}/delete', [$servers, 'delete']);
$router->post('/servers/{id}/test', [$servers, 'test']);

$users = new UserController();
$router->get('/users', [$users, 'index']);
$router->get('/users/create', [$users, 'create']);
$router->post('/users', [$users, 'store']);
$router->get('/users/{id}/edit', [$users, 'edit']);
$router->post('/users/{id}/update', [$users, 'update']);
$router->post('/users/{id}/delete', [$users, 'delete']);

// --- API REST (token) consumida por la app Android ---
$authApi = new AuthApiController();
$router->post('/api/auth/login', [$authApi, 'login']);
$router->post('/api/auth/logout', [$authApi, 'logout']);
$router->get('/api/profile', [$authApi, 'profile']);

$liveApi = new LiveApiController();
$router->get('/api/live/categories', [$liveApi, 'categories']);
$router->get('/api/live/streams', [$liveApi, 'streams']);
$router->get('/api/epg', [$liveApi, 'epg']);
$router->get('/api/stream/live/{stream_id}', [$liveApi, 'streamUrl']);

$router->dispatch($request);
