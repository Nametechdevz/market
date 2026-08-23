<?php

namespace App\Controllers\Api;

use App\Core\ApiAuth;
use App\Core\Request;
use App\Core\Response;
use App\Models\AuthToken;
use App\Models\IptvUser;

class AuthApiController
{
    public function login(Request $request): void
    {
        $username = trim((string) $request->input('username', ''));
        $password = (string) $request->input('password', '');
        $device = trim((string) $request->input('device_info', ''));

        if ($username === '' || $password === '') {
            Response::json(['error' => 'Usuario y contraseña son obligatorios'], 422);
            return;
        }

        $user = IptvUser::verify($username, $password);
        if (!$user) {
            Response::json(['error' => 'Credenciales inválidas, usuario deshabilitado o vencido'], 401);
            return;
        }

        $token = AuthToken::issue((int) $user['id'], $device ?: null);

        Response::json([
            'token' => $token,
            'user' => [
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'expires_at' => $user['expires_at'],
                'max_connections' => (int) $user['max_connections'],
            ],
        ]);
    }

    public function logout(Request $request): void
    {
        $token = $request->bearerToken();
        if ($token) {
            AuthToken::revoke($token);
        }
        Response::json(['ok' => true]);
    }

    public function profile(Request $request): void
    {
        $userId = ApiAuth::requireUser($request);
        $user = IptvUser::find($userId);

        Response::json([
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'status' => $user['status'],
            'expires_at' => $user['expires_at'],
            'max_connections' => (int) $user['max_connections'],
        ]);
    }
}
