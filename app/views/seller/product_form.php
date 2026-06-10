<?php
$isEdit = isset($product);
$title = $isEdit ? 'Editar Producto' : 'Nuevo Producto';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $isEdit ? '✏️ Editar Producto' : '➕ Nuevo Producto'; ?></h1>
        <a href="<?php echo APP_URL; ?>/seller/products" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card">
        <form method="POST" enctype="multipart/form-data" class="form">
            <div class="form-row">
                <div class="form-group">
                    <label>Título del Producto *</label>
                    <input type="text" name="title" required
                           value="<?php echo $isEdit ? htmlspecialchars($product['title']) : ''; ?>"
                           placeholder="Mi Curso de React">
                </div>

                <div class="form-group">
                    <label>Categoría *</label>
                    <select name="category_id" required>
                        <option value="">-- Selecciona categoría --</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                                <?php echo $isEdit && $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                            <?php echo $cat['icon']; ?> <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Precio (COP) *</label>
                    <input type="number" name="price" min="0" step="any" required
                           value="<?php echo $isEdit ? $product['price'] : ''; ?>"
                           placeholder="99000">
                </div>

                <div class="form-group">
                    <label>Tipo de Producto *</label>
                    <select name="type" required>
                        <option value="course" <?php echo $isEdit && $product['type'] === 'course' ? 'selected' : ''; ?>>📚 Curso</option>
                        <option value="streaming" <?php echo $isEdit && $product['type'] === 'streaming' ? 'selected' : ''; ?>>📺 Streaming</option>
                        <option value="ebook" <?php echo $isEdit && $product['type'] === 'ebook' ? 'selected' : ''; ?>>📖 E-book</option>
                        <option value="software" <?php echo $isEdit && $product['type'] === 'software' ? 'selected' : ''; ?>>💻 Software</option>
                        <option value="other" <?php echo $isEdit && $product['type'] === 'other' ? 'selected' : ''; ?>>📦 Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descripción *</label>
                <textarea name="description" rows="5" required
                          placeholder="Describe detalladamente qué incluye tu producto..."><?php echo $isEdit ? htmlspecialchars($product['description']) : ''; ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="<?php echo $isEdit ? $product['stock'] : '-1'; ?>"
                           placeholder="-1 para ilimitado">
                    <small>Usa -1 para productos digitales (ilimitados)</small>
                </div>

                <div class="form-group">
                    <label>Imagen del Producto</label>
                    <input type="file" name="image" accept="image/*">
                    <small>JPG, PNG o WebP. Máximo 5MB</small>
                    <?php if ($isEdit && $product['image']): ?>
                        <div style="margin-top: 0.5rem;">
                            <img src="<?php echo APP_URL . htmlspecialchars($product['image']); ?>" alt="" style="max-width: 150px; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Información de Entrega *</label>
                <textarea name="delivery_info" rows="3" required
                          placeholder="Cómo se entregará el producto (link de descarga, credenciales, video tutorial, etc)..."><?php echo $isEdit ? htmlspecialchars($product['delivery_info']) : ''; ?></textarea>
                <small>Esta información se mostrará al cliente después de confirmar el pago</small>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="status">
                    <option value="draft" <?php echo $isEdit && $product['status'] === 'draft' ? 'selected' : ''; ?>>Borrador (no visible)</option>
                    <option value="active" <?php echo $isEdit && $product['status'] === 'active' ? 'selected' : ''; ?>>Publicado (visible)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-lg"><?php echo $isEdit ? 'Actualizar' : 'Publicar'; ?> Producto</button>
        </form>
    </div>
</div>
