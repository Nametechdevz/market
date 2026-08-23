<?php ob_start(); ?>
<h1>Dashboard</h1>
<div class="cards">
    <div class="card">
        <span class="card-value"><?= (int) $stats['servers'] ?></span>
        <span class="card-label">Servidores Xtream</span>
    </div>
    <div class="card">
        <span class="card-value"><?= (int) $stats['servers_active'] ?></span>
        <span class="card-label">Servidores activos</span>
    </div>
    <div class="card">
        <span class="card-value"><?= (int) $stats['users'] ?></span>
        <span class="card-label">Usuarios totales</span>
    </div>
    <div class="card">
        <span class="card-value"><?= (int) $stats['users_active'] ?></span>
        <span class="card-label">Usuarios activos</span>
    </div>
</div>
<p class="hint">Desde aquí administras los servidores Xtream Codes (DNS/host) y los usuarios que inician sesión en la app Android.</p>
<?php
$content = ob_get_clean();
$active = 'dashboard';
require __DIR__ . '/layout.php';
