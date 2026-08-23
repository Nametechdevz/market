<?php ob_start(); ?>
<div class="page-header">
    <h1>Servidores Xtream Codes</h1>
    <a href="/servers/create" class="btn">+ Agregar servidor</a>
</div>
<table class="table">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>DNS / Host</th>
        <th>Puerto</th>
        <th>HTTPS</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($servers as $server): ?>
        <tr>
            <td><?= htmlspecialchars($server['name']) ?></td>
            <td><?= htmlspecialchars($server['dns']) ?></td>
            <td><?= (int) $server['port'] ?></td>
            <td><?= ((int) $server['use_https']) === 1 ? 'Sí' : 'No' ?></td>
            <td>
                <span class="badge badge-<?= $server['status'] === 'active' ? 'ok' : 'off' ?>">
                    <?= $server['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                </span>
            </td>
            <td class="actions">
                <a href="/servers/<?= (int) $server['id'] ?>/edit">Editar</a>
                <form action="/servers/<?= (int) $server['id'] ?>/delete" method="post"
                      onsubmit="return confirm('¿Eliminar este servidor?');">
                    <button type="submit" class="link-btn">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$servers): ?>
        <tr><td colspan="6" class="empty">No hay servidores registrados todavía.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
$active = 'servers';
require __DIR__ . '/../layout.php';
