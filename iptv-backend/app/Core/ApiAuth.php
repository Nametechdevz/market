<?php

namespace App\Core;

use App\Models\AuthToken;

class ApiAuth
{
    /**
     * Valida el Bearer token de la petición y devuelve el user_id, o
     * responde 401 y termina la ejecución si no es válido.
     */
    public static function requireUser(Request $request): int
    {
        $token = $request->bearerToken();
        if (!$token) {
            Response::json(['error' => 'Falta el token de autenticación'], 401);
            exit;
        }

        $userId = AuthToken::validate($token);
        if (!$userId) {
            Response::json(['error' => 'Token inválido o expirado'], 401);
            exit;
        }

        return $userId;
    }
}
