<?php
$title = 'Panel de Admin';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <h1>Panel de Administración</h1>

    <div class="admin-dashboard">
        <section class="stats-grid">
            <div class="stat-card">
                <h3>Usuarios Totales</h3>
                <p class="stat-value">0</p>
            </div>
            <div class="stat-card">
                <h3>Vendedores Activos</h3>
                <p class="stat-value">0</p>
            </div>
            <div class="stat-card">
                <h3>Ingresos Totales</h3>
                <p class="stat-value">$0</p>
            </div>
            <div class="stat-card">
                <h3>Órdenes Hoy</h3>
                <p class="stat-value">0</p>
            </div>
        </section>

        <section class="admin-menu">
            <h2>Gestión</h2>
            <div class="menu-grid">
                <a href="<?php echo APP_URL; ?>/admin/users" class="menu-item">👥 Usuarios</a>
                <a href="<?php echo APP_URL; ?>/admin/plans" class="menu-item">📦 Planes</a>
                <a href="<?php echo APP_URL; ?>/admin/categories" class="menu-item">📂 Categorías</a>
                <a href="<?php echo APP_URL; ?>/admin/products" class="menu-item">🛍️ Productos</a>
                <a href="<?php echo APP_URL; ?>/admin/orders" class="menu-item">📋 Órdenes</a>
                <a href="<?php echo APP_URL; ?>/admin/disputes" class="menu-item">⚠️ Disputas</a>
                <a href="<?php echo APP_URL; ?>/admin/analytics" class="menu-item">📊 Análitica</a>
                <a href="<?php echo APP_URL; ?>/admin/settings" class="menu-item">⚙️ Configuración</a>
            </div>
        </section>
    </div>
</div>
