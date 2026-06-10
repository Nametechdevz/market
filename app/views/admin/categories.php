<?php
$title = 'Categorías';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📂 Categorías</h1>
        <div>
            <a href="<?php echo APP_URL; ?>/admin/dashboard" class="btn btn-secondary">← Volver</a>
            <a href="<?php echo APP_URL; ?>/admin/categories/create" class="btn btn-primary">+ Nueva Categoría</a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Icono</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="font-size: 1.5rem;"><?php echo htmlspecialchars($cat['icon']); ?></td>
                        <td><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                        <td><?php echo $cat['product_count']; ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/admin/categories/<?php echo $cat['id']; ?>/edit" class="btn btn-sm btn-primary">Editar</a>
                            <form method="POST" action="<?php echo APP_URL; ?>/admin/categories/<?php echo $cat['id']; ?>/delete" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="empty-state">No hay categorías</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
