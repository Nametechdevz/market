<?php
$title = 'Mi Tienda';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <h1>Mi Tienda</h1>

    <div class="seller-dashboard">
        <section class="stats-grid">
            <div class="stat-card">
                <h3>Productos</h3>
                <p class="stat-value">0</p>
                <a href="<?php echo APP_URL; ?>/seller/products">Ver productos</a>
            </div>
            <div class="stat-card">
                <h3>Ventas Totales</h3>
                <p class="stat-value">$0</p>
                <a href="<?php echo APP_URL; ?>/seller/orders">Ver órdenes</a>
            </div>
            <div class="stat-card">
                <h3>Calificación</h3>
                <p class="stat-value">⭐ 0</p>
            </div>
            <div class="stat-card">
                <h3>Mi Suscripción</h3>
                <p class="stat-value">Free</p>
                <a href="<?php echo APP_URL; ?>/seller/plans">Upgradear</a>
            </div>
        </section>

        <section class="quick-actions">
            <h2>Acciones Rápidas</h2>
            <div class="button-group">
                <a href="<?php echo APP_URL; ?>/seller/products/create" class="btn btn-primary">+ Nuevo Producto</a>
                <a href="<?php echo APP_URL; ?>/seller/payment-methods" class="btn btn-secondary">Configurar Pagos</a>
                <a href="<?php echo APP_URL; ?>/seller/orders" class="btn btn-secondary">Ver Órdenes</a>
            </div>
        </section>
    </div>
</div>
