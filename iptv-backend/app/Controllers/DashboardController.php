<?php

namespace App\Controllers;

use App\Core\AdminAuth;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;

class DashboardController
{
    public function index(Request $request): void
    {
        AdminAuth::requireLogin();

        $db = Database::connection();
        $stats = [
            'servers' => (int) $db->query('SELECT COUNT(*) FROM iptv_servers')->fetchColumn(),
            'servers_active' => (int) $db->query("SELECT COUNT(*) FROM iptv_servers WHERE status = 'active'")->fetchColumn(),
            'users' => (int) $db->query('SELECT COUNT(*) FROM iptv_users')->fetchColumn(),
            'users_active' => (int) $db->query("SELECT COUNT(*) FROM iptv_users WHERE status = 'active'")->fetchColumn(),
        ];

        Response::view('dashboard', ['stats' => $stats, 'admin' => AdminAuth::username()]);
    }
}
