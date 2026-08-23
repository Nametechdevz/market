<?php
$isEdit = !empty($user['id']);
ob_start();
?>
<h1><?= $isEdit ? 'Editar usuario' : 'Agregar usuario' ?></h1>
<form class="form-card" method="post"
      action="<?= $isEdit ? '/users/' . (int) $user['id'] . '/update' : '/users' ?>">
    <label>Usuario (login en la app)
        <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
        <?php if (!empty($errors['username'])): ?><span class="field-error"><?= $errors['username'] ?></span><?php endif; ?>
    </label>
    <label>Contraseña <?= $isEdit ? '(dejar vacío para no cambiarla)' : '' ?>
        <input type="password" name="password" <?= $isEdit ? '' : 'required' ?>>
        <?php if (!empty($errors['password'])): ?><span class="field-error"><?= $errors['password'] ?></span><?php endif; ?>
    </label>
    <label>Servidor Xtream
        <select name="server_id" required>
            <option value="">Selecciona...</option>
            <?php foreach ($servers as $server): ?>
                <option value="<?= (int) $server['id'] ?>"
                    <?= (int) ($user['server_id'] ?? 0) === (int) $server['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($server['name']) ?> (<?= htmlspecialchars($server['dns']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['server_id'])): ?><span class="field-error"><?= $errors['server_id'] ?></span><?php endif; ?>
    </label>
    <div class="field-group">
        <label>Usuario Xtream (línea en el servidor)
            <input type="text" name="xtream_username" value="<?= htmlspecialchars($user['xtream_username'] ?? '') ?>" required>
        </label>
        <label>Clave Xtream
            <input type="text" name="xtream_password" value="<?= htmlspecialchars($user['xtream_password'] ?? '') ?>" required>
        </label>
    </div>
    <?php if (!empty($errors['xtream'])): ?><span class="field-error"><?= $errors['xtream'] ?></span><?php endif; ?>
    <label>Conexiones máximas
        <input type="number" name="max_connections" min="1" value="<?= (int) ($user['max_connections'] ?? 1) ?>">
    </label>
    <label>Fecha de vencimiento (opcional)
        <input type="date" name="expires_at" value="<?= htmlspecialchars($user['expires_at'] ?? '') ?>">
    </label>
    <label>Estado
        <select name="status">
            <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="disabled" <?= ($user['status'] ?? '') === 'disabled' ? 'selected' : '' ?>>Deshabilitado</option>
        </select>
    </label>
    <label>Notas
        <textarea name="notes" rows="3"><?= htmlspecialchars($user['notes'] ?? '') ?></textarea>
    </label>
    <div class="form-actions">
        <button type="submit" class="btn"><?= $isEdit ? 'Guardar cambios' : 'Crear usuario' ?></button>
        <a href="/users" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
$active = 'users';
require __DIR__ . '/../layout.php';
