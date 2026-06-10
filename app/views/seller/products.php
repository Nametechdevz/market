<?php
$title = 'Mis Productos';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>🛍️ Mis Productos</h1>
        <a href="<?php echo APP_URL; ?>/seller/products/create" class="btn btn-primary">+ Nuevo Producto</a>
    </div>

    <?php if (isset($subscription) && $subscription['max_products'] !== -1): ?>
    <div class="progress-bar">
        <p><?php echo count($products); ?> / <?php echo $subscription['max_products']; ?> productos usados</p>
        <div class="bar">
            <div class="fill" style="width: <?php echo (count($products) / $subscription['max_products'] * 100); ?>%"></div>
        </div>
        <?php if (count($products) >= $subscription['max_products']): ?>
            <p class="warning-text">Has alcanzado el límite. <a href="<?php echo APP_URL; ?>/seller/upgrade-plan">Upgradea tu plan</a></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="products-table">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if ($product['image']): ?>
                                <img src="<?php echo APP_URL . htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-thumb">
                            <?php else: ?>
                                <div class="product-thumb placeholder">📦</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($product['title']); ?></strong><br>
                            <small><?php echo htmlspecialchars($product['category_name']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td>$<?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $product['status'] === 'active' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($product['status']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo APP_URL; ?>/seller/products/<?php echo $product['id']; ?>/toggle-featured" style="display:inline;">
                                <button type="submit" class="btn btn-sm <?php echo $product['featured'] ? 'btn-primary' : 'btn-secondary'; ?>">
                                    <?php echo $product['featured'] ? '⭐ Destacado' : '☆ Destacar'; ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/seller/products/<?php echo $product['id']; ?>/edit" class="btn btn-sm btn-primary">Editar</a>
                            <form method="POST" action="<?php echo APP_URL; ?>/seller/products/<?php echo $product['id']; ?>/delete" style="display:inline;" onsubmit="return confirm('¿Eliminar producto?');">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            No tienes productos. <a href="<?php echo APP_URL; ?>/seller/products/create">Crear el primero</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.product-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.product-thumb.placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light);
    font-size: 1.5rem;
}

.progress-bar {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
}

.progress-bar .bar {
    height: 8px;
    background: var(--border);
    border-radius: 999px;
    margin: 0.5rem 0;
    overflow: hidden;
}

.progress-bar .fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    transition: width 0.3s;
}

.warning-text {
    color: var(--danger);
    font-weight: 500;
    margin-top: 0.5rem;
}
</style>
