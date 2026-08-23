<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? 'Panel IPTV') ?></title>
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<?php if (!empty($admin)): ?>
<div class="app">
    <aside class="sidebar">
        <div class="brand">📺 Panel IPTV</div>
        <nav>
            <a href="/dashboard" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="/servers" class="<?= ($active ?? '') === 'servers' ? 'active' : '' ?>">Servidores Xtream</a>
            <a href="/users" class="<?= ($active ?? '') === 'users' ? 'active' : '' ?>">Usuarios</a>
        </nav>
        <form action="/logout" method="post" class="logout-form">
            <span class="admin-name"><?= htmlspecialchars($admin) ?></span>
            <button type="submit">Salir</button>
        </form>
    </aside>
    <main class="content">
        <?= $content ?>
    </main>
</div>
<?php else: ?>
<?= $content ?>
<?php endif; ?>
</body>
</html>
