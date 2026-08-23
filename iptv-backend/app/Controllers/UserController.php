<?php

namespace App\Controllers;

use App\Core\AdminAuth;
use App\Core\Request;
use App\Core\Response;
use App\Models\IptvUser;
use App\Models\Server;

class UserController
{
    public function index(Request $request): void
    {
        AdminAuth::requireLogin();
        Response::view('users/index', ['users' => IptvUser::all(), 'admin' => AdminAuth::username()]);
    }

    public function create(Request $request): void
    {
        AdminAuth::requireLogin();
        Response::view('users/form', [
            'user' => null,
            'errors' => [],
            'servers' => Server::all(),
            'admin' => AdminAuth::username(),
        ]);
    }

    public function store(Request $request): void
    {
        AdminAuth::requireLogin();
        $data = $this->extract($request);
        $errors = $this->validate($data, true);

        if ($errors) {
            Response::view('users/form', [
                'user' => $data,
                'errors' => $errors,
                'servers' => Server::all(),
                'admin' => AdminAuth::username(),
            ]);
            return;
        }

        IptvUser::create($data);
        Response::redirect('/users');
    }

    public function edit(Request $request): void
    {
        AdminAuth::requireLogin();
        $user = IptvUser::find((int) $request->param('id'));
        if (!$user) {
            Response::redirect('/users');
            return;
        }
        Response::view('users/form', [
            'user' => $user,
            'errors' => [],
            'servers' => Server::all(),
            'admin' => AdminAuth::username(),
        ]);
    }

    public function update(Request $request): void
    {
        AdminAuth::requireLogin();
        $id = (int) $request->param('id');
        $data = $this->extract($request);
        $errors = $this->validate($data, false);

        if ($errors) {
            $data['id'] = $id;
            Response::view('users/form', [
                'user' => $data,
                'errors' => $errors,
                'servers' => Server::all(),
                'admin' => AdminAuth::username(),
            ]);
            return;
        }

        IptvUser::update($id, $data);
        Response::redirect('/users');
    }

    public function delete(Request $request): void
    {
        AdminAuth::requireLogin();
        IptvUser::delete((int) $request->param('id'));
        Response::redirect('/users');
    }

    private function extract(Request $request): array
    {
        return [
            'username' => trim((string) $request->input('username', '')),
            'password' => (string) $request->input('password', ''),
            'server_id' => (int) $request->input('server_id', 0),
            'xtream_username' => trim((string) $request->input('xtream_username', '')),
            'xtream_password' => trim((string) $request->input('xtream_password', '')),
            'max_connections' => max(1, (int) $request->input('max_connections', 1)),
            'status' => $request->input('status', 'active') === 'disabled' ? 'disabled' : 'active',
            'expires_at' => trim((string) $request->input('expires_at', '')) ?: null,
            'notes' => trim((string) $request->input('notes', '')),
        ];
    }

    private function validate(array $data, bool $isCreate): array
    {
        $errors = [];
        if ($data['username'] === '') {
            $errors['username'] = 'El usuario es obligatorio';
        }
        if ($isCreate && $data['password'] === '') {
            $errors['password'] = 'La contraseña es obligatoria';
        }
        if ($data['server_id'] <= 0) {
            $errors['server_id'] = 'Selecciona un servidor';
        }
        if ($data['xtream_username'] === '' || $data['xtream_password'] === '') {
            $errors['xtream'] = 'Indica el usuario y clave de la línea Xtream en ese servidor';
        }
        return $errors;
    }
}
