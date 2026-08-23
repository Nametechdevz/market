<?php ob_start(); ?>
<div class="page-header">
    <h1>Usuarios de la app</h1>
    <a href="/users/create" class="btn">+ Agregar usuario</a>
</div>
<table class="table">
    <thead>
    <tr>
        <th>Usuario</th>
        <th>Servidor</th>
        <th>Conexiones</th>
        <th>Vence</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['server_name']) ?></td>
            <td><?= (int) $user['max_connections'] ?></td>
            <td><?= htmlspecialchars($user['expires_at'] ?? 'Sin vencimiento') ?></td>
            <td>
                <span class="badge badge-<?= $user['status'] === 'active' ? 'ok' : 'off' ?>">
                    <?= $user['status'] === 'active' ? 'Activo' : 'Deshabilitado' ?>
                </span>
            </td>
            <td class="actions">
                <a href="/users/<?= (int) $user['id'] ?>/edit">Editar</a>
                <form action="/users/<?= (int) $user['id'] ?>/delete" method="post"
                      onsubmit="return confirm('¿Eliminar este usuario?');">
                    <button type="submit" class="link-btn">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$users): ?>
        <tr><td colspan="6" class="empty">No hay usuarios registrados todavía.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
$active = 'users';
require __DIR__ . '/../layout.php';
