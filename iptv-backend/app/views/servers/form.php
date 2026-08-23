<?php
$isEdit = !empty($server['id']);
ob_start();
?>
<h1><?= $isEdit ? 'Editar servidor' : 'Agregar servidor Xtream' ?></h1>
<form class="form-card" method="post"
      action="<?= $isEdit ? '/servers/' . (int) $server['id'] . '/update' : '/servers' ?>">
    <label>Nombre
        <input type="text" name="name" value="<?= htmlspecialchars($server['name'] ?? '') ?>" required>
        <?php if (!empty($errors['name'])): ?><span class="field-error"><?= $errors['name'] ?></span><?php endif; ?>
    </label>
    <label>DNS / Host (sin http:// ni https://)
        <input type="text" name="dns" placeholder="mi-servidor.com" value="<?= htmlspecialchars($server['dns'] ?? '') ?>" required>
        <?php if (!empty($errors['dns'])): ?><span class="field-error"><?= $errors['dns'] ?></span><?php endif; ?>
    </label>
    <label>Puerto
        <input type="number" name="port" value="<?= htmlspecialchars((string) ($server['port'] ?? 80)) ?>" required>
        <?php if (!empty($errors['port'])): ?><span class="field-error"><?= $errors['port'] ?></span><?php endif; ?>
    </label>
    <label class="checkbox">
        <input type="checkbox" name="use_https" value="1" <?= !empty($server['use_https']) ? 'checked' : '' ?>>
        Usar HTTPS
    </label>
    <label>Estado
        <select name="status">
            <option value="active" <?= ($server['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="inactive" <?= ($server['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </label>
    <label>Notas
        <textarea name="notes" rows="3"><?= htmlspecialchars($server['notes'] ?? '') ?></textarea>
    </label>
    <div class="form-actions">
        <button type="submit" class="btn"><?= $isEdit ? 'Guardar cambios' : 'Crear servidor' ?></button>
        <a href="/servers" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php if ($isEdit): ?>
<div class="test-card">
    <h2>Probar conexión</h2>
    <p class="hint">Ingresa una línea (usuario/clave) real de este servidor para verificar que responde.</p>
    <div class="test-form">
        <input type="text" id="test-user" placeholder="Usuario Xtream de prueba">
        <input type="text" id="test-pass" placeholder="Clave Xtream de prueba">
        <button type="button" id="test-btn" class="btn">Probar</button>
    </div>
    <p id="test-result"></p>
</div>
<script>
document.getElementById('test-btn').addEventListener('click', async () => {
    const user = document.getElementById('test-user').value;
    const pass = document.getElementById('test-pass').value;
    const result = document.getElementById('test-result');
    result.textContent = 'Probando...';
    try {
        const res = await fetch('/servers/<?= (int) $server['id'] ?>/test', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'xtream_username=' + encodeURIComponent(user) + '&xtream_password=' + encodeURIComponent(pass),
        });
        const data = await res.json();
        result.textContent = data.message;
        result.className = data.ok ? 'test-ok' : 'test-fail';
    } catch (e) {
        result.textContent = 'Error de red al probar la conexión';
        result.className = 'test-fail';
    }
});
</script>
<?php endif; ?>
<?php
$content = ob_get_clean();
$active = 'servers';
require __DIR__ . '/../layout.php';
