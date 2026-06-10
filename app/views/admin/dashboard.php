<?php
$title = 'Panel de Admin';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📊 Panel de Administración</h1>
        <p>Bienvenido <?php echo Auth::user()['name']; ?></p>
    </div>

    <div class="admin-dashboard">
        <section class="stats-grid">
            <div class="stat-card stat-primary">
                <h3>Usuarios Totales</h3>
                <p class="stat-value"><?php echo number_format($stats['total_users']); ?></p>
                <small>Vendedores: <?php echo $stats['total_sellers']; ?> | Compradores: <?php echo $stats['total_buyers']; ?></small>
            </div>
            <div class="stat-card stat-success">
                <h3>Productos Activos</h3>
                <p class="stat-value"><?php echo number_format($stats['total_products']); ?></p>
            </div>
            <div class="stat-card stat-warning">
                <h3>Órdenes Totales</h3>
                <p class="stat-value"><?php echo number_format($stats['total_orders']); ?></p>
                <small>Hoy: <?php echo $stats['orders_today']; ?></small>
            </div>
            <div class="stat-card stat-danger">
                <h3>Ingresos por Suscripciones</h3>
                <p class="stat-value">$<?php echo number_format($stats['revenue'] ?? 0, 0, ',', '.'); ?></p>
                <small>Suscripciones activas: <?php echo $stats['active_subscriptions']; ?></small>
            </div>
        </section>

        <section class="admin-menu">
            <h2>Gestión</h2>
            <div class="menu-grid">
                <a href="<?php echo APP_URL; ?>/admin/users" class="menu-item">
                    <span class="menu-icon">👥</span>
                    <span class="menu-label">Usuarios</span>
                </a>
                <a href="<?php echo APP_URL; ?>/admin/plans" class="menu-item">
                    <span class="menu-icon">📦</span>
                    <span class="menu-label">Planes</span>
                </a>
                <a href="<?php echo APP_URL; ?>/admin/categories" class="menu-item">
                    <span class="menu-icon">📂</span>
                    <span class="menu-label">Categorías</span>
                </a>
                <a href="<?php echo APP_URL; ?>/admin/subscriptions" class="menu-item">
                    <span class="menu-icon">💳</span>
                    <span class="menu-label">Suscripciones</span>
                </a>
                <a href="<?php echo APP_URL; ?>/admin/products" class="menu-item">
                    <span class="menu-icon">🛍️</span>
                    <span class="menu-label">Productos</span>
                </a>
                <a href="<?php echo APP_URL; ?>/admin/orders" class="menu-item">
                    <span class="menu-icon">📋</span>
                    <span class="menu-label">Órdenes</span>
                </a>
                <a href="<?php echo APP_URL; ?>/admin/disputes" class="menu-item">
                    <span class="menu-icon">⚠️</span>
                    <span class="menu-label">Disputas</span>
                </a>
                <a href="<?php echo APP_URL; ?>/admin/settings" class="menu-item">
                    <span class="menu-icon">⚙️</span>
                    <span class="menu-label">Configuración</span>
                </a>
            </div>
        </section>
    </div>
</div>
