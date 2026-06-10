<?php
$isEdit = isset($plan);
$title = $isEdit ? 'Editar Plan' : 'Nuevo Plan';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $isEdit ? '✏️ Editar Plan' : '➕ Nuevo Plan'; ?></h1>
        <a href="<?php echo APP_URL; ?>/admin/plans" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card">
        <form method="POST" class="form">
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre del Plan *</label>
                    <input type="text" name="name" required
                           value="<?php echo $isEdit ? htmlspecialchars($plan['name']) : ''; ?>"
                           placeholder="Ej: Pro">
                </div>

                <div class="form-group">
                    <label>Precio (COP) *</label>
                    <input type="number" name="price" min="0" step="any" required
                           value="<?php echo $isEdit ? $plan['price'] : '0'; ?>"
                           placeholder="45000">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Productos máximos *</label>
                    <input type="number" name="max_products" min="-1" required
                           value="<?php echo $isEdit ? $plan['max_products'] : '30'; ?>">
                    <small>Usar -1 para ilimitado</small>
                </div>

                <div class="form-group">
                    <label>Duración (días) *</label>
                    <input type="number" name="duration_days" min="1" required
                           value="<?php echo $isEdit ? $plan['duration_days'] : '30'; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="description" rows="3"><?php echo $isEdit ? htmlspecialchars($plan['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Features (uno por línea)</label>
                <textarea name="features" rows="5" placeholder="Sin marca de agua&#10;Productos destacados&#10;Badge verificado"><?php echo $isEdit ? implode("\n", $plan['features']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="status">
                    <option value="active" <?php echo ($isEdit && $plan['status'] === 'active') ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactive" <?php echo ($isEdit && $plan['status'] === 'inactive') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Actualizar' : 'Crear'; ?> Plan</button>
        </form>
    </div>
</div>
