<?php
$isEdit = isset($category);
$title = $isEdit ? 'Editar Categoría' : 'Nueva Categoría';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $isEdit ? '✏️ Editar Categoría' : '➕ Nueva Categoría'; ?></h1>
        <a href="<?php echo APP_URL; ?>/admin/categories" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card">
        <form method="POST" class="form">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="name" required
                       value="<?php echo $isEdit ? htmlspecialchars($category['name']) : ''; ?>"
                       placeholder="Cursos">
            </div>

            <div class="form-group">
                <label>Icono (emoji)</label>
                <input type="text" name="icon" maxlength="10"
                       value="<?php echo $isEdit ? htmlspecialchars($category['icon']) : ''; ?>"
                       placeholder="🎓">
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="description" rows="3"><?php echo $isEdit ? htmlspecialchars($category['description']) : ''; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Actualizar' : 'Crear'; ?></button>
        </form>
    </div>
</div>
