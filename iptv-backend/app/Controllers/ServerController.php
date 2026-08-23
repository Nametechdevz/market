<?php

namespace App\Controllers;

use App\Core\AdminAuth;
use App\Core\Request;
use App\Core\Response;
use App\Models\Server;
use App\Services\XtreamClient;

class ServerController
{
    public function index(Request $request): void
    {
        AdminAuth::requireLogin();
        Response::view('servers/index', ['servers' => Server::all(), 'admin' => AdminAuth::username()]);
    }

    public function create(Request $request): void
    {
        AdminAuth::requireLogin();
        Response::view('servers/form', ['server' => null, 'errors' => [], 'admin' => AdminAuth::username()]);
    }

    public function store(Request $request): void
    {
        AdminAuth::requireLogin();

        $data = $this->extract($request);
        $errors = $this->validate($data);

        if ($errors) {
            Response::view('servers/form', ['server' => $data, 'errors' => $errors, 'admin' => AdminAuth::username()]);
            return;
        }

        Server::create($data);
        Response::redirect('/servers');
    }

    public function edit(Request $request): void
    {
        AdminAuth::requireLogin();
        $server = Server::find((int) $request->param('id'));
        if (!$server) {
            Response::redirect('/servers');
            return;
        }
        Response::view('servers/form', ['server' => $server, 'errors' => [], 'admin' => AdminAuth::username()]);
    }

    public function update(Request $request): void
    {
        AdminAuth::requireLogin();
        $id = (int) $request->param('id');
        $data = $this->extract($request);
        $errors = $this->validate($data);

        if ($errors) {
            $data['id'] = $id;
            Response::view('servers/form', ['server' => $data, 'errors' => $errors, 'admin' => AdminAuth::username()]);
            return;
        }

        Server::update($id, $data);
        Response::redirect('/servers');
    }

    public function delete(Request $request): void
    {
        AdminAuth::requireLogin();
        $id = (int) $request->param('id');

        if (Server::inUseCount($id) > 0) {
            Response::redirect('/servers');
            return;
        }

        Server::delete($id);
        Response::redirect('/servers');
    }

    public function test(Request $request): void
    {
        AdminAuth::requireLogin();
        $id = (int) $request->param('id');
        $server = Server::find($id);

        if (!$server) {
            Response::json(['ok' => false, 'message' => 'Servidor no encontrado'], 404);
            return;
        }

        $testUser = (string) $request->input('xtream_username', '');
        $testPass = (string) $request->input('xtream_password', '');

        if ($testUser === '' || $testPass === '') {
            Response::json(['ok' => false, 'message' => 'Indica un usuario/clave Xtream de prueba']);
            return;
        }

        $client = new XtreamClient(Server::baseUrl($server), $testUser, $testPass);
        $result = $client->authenticate();

        $authOk = isset($result['user_info']['auth']) && (int) $result['user_info']['auth'] === 1;

        Response::json([
            'ok' => $authOk,
            'message' => $authOk ? 'Conexión exitosa' : 'No se pudo autenticar contra el servidor Xtream',
            'info' => $result['user_info'] ?? null,
        ]);
    }

    private function extract(Request $request): array
    {
        return [
            'name' => trim((string) $request->input('name', '')),
            'dns' => trim((string) $request->input('dns', '')),
            'port' => (int) $request->input('port', 80),
            'use_https' => (bool) $request->input('use_https', false),
            'status' => $request->input('status', 'active') === 'inactive' ? 'inactive' : 'active',
            'notes' => trim((string) $request->input('notes', '')),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['name'] === '') {
            $errors['name'] = 'El nombre es obligatorio';
        }
        if ($data['dns'] === '') {
            $errors['dns'] = 'El DNS/host es obligatorio';
        } elseif (preg_match('#^https?://#i', $data['dns'])) {
            $errors['dns'] = 'No incluyas http:// o https://, solo el host';
        }
        if ($data['port'] < 1 || $data['port'] > 65535) {
            $errors['port'] = 'Puerto inválido';
        }
        return $errors;
    }
}
