<?php
$title = 'Mi Tienda';
$content = __FILE__;
require __DIR__ . '/../layout.php';
use App\Models\SellerPaymentMethod;
?>

<div class="container">
    <div class="page-header">
        <h1>🛍️ Mi Tienda</h1>
        <p>Bienvenido <?php echo Auth::user()['name']; ?></p>
    </div>

    <div class="seller-dashboard">
        <section class="stats-grid">
            <div class="stat-card stat-primary">
                <h3>Productos Activos</h3>
                <p class="stat-value"><?php echo $stats['active_products']; ?> / <?php echo $stats['total_products']; ?></p>
                <small><a href="<?php echo APP_URL; ?>/seller/products">Gestionar →</a></small>
            </div>
            <div class="stat-card stat-success">
                <h3>Órdenes Pendientes</h3>
                <p class="stat-value"><?php echo $orderStats['pending']; ?></p>
                <small><?php echo $orderStats['paid']; ?> pagadas, <?php echo $orderStats['delivered']; ?> completadas</small>
            </div>
            <div class="stat-card stat-warning">
                <h3>Ingresos Totales</h3>
                <p class="stat-value">$<?php echo number_format($orderStats['revenue'] ?? 0, 0, ',', '.'); ?></p>
            </div>
            <div class="stat-card stat-danger">
                <h3>Calificación</h3>
                <p class="stat-value">⭐ <?php echo number_format($stats['avg_rating'] ?? 0, 1); ?></p>
                <small>En <?php echo $orderStats['delivered']; ?> órdenes entregadas</small>
            </div>
        </section>

        <section class="subscription-banner">
            <?php if ($subscription): ?>
            <div class="subscription-card active">
                <h3>📦 Plan Actual: <?php echo htmlspecialchars($subscription['plan_name']); ?></h3>
                <p>Tienes acceso a <strong><?php echo $subscription['max_products'] === -1 ? 'ilimitados' : $subscription['max_products']; ?> productos</strong></p>
                <p class="subscription-valid">Válido hasta: <strong><?php echo date('d/m/Y', strtotime($subscription['ends_at'])); ?></strong></p>
                <a href="<?php echo APP_URL; ?>/seller/upgrade-plan" class="btn btn-secondary">Cambiar Plan</a>
            </div>
            <?php else: ?>
            <div class="subscription-card free">
                <h3>📦 Plan: Gratuito</h3>
                <p>Estás en el plan gratuito. Tienes acceso a <strong>3 productos máximo</strong></p>
                <a href="<?php echo APP_URL; ?>/seller/upgrade-plan" class="btn btn-primary">Upgradear Plan</a>
            </div>
            <?php endif; ?>
        </section>

        <?php if (empty($paymentMethods)): ?>
        <section class="warning-banner">
            <h3>⚠️ Configura tus métodos de pago</h3>
            <p>Necesitas agregar al menos un método de pago (Nequi, Bancolombia, Bre-B, etc) para recibir dinero de tus clientes.</p>
            <a href="<?php echo APP_URL; ?>/seller/payment-methods" class="btn btn-primary">Configurar Ahora</a>
        </section>
        <?php endif; ?>

        <section class="quick-actions">
            <h2>Acciones Rápidas</h2>
            <div class="button-group">
                <a href="<?php echo APP_URL; ?>/seller/products/create" class="btn btn-primary btn-lg">
                    ➕ Nuevo Producto
                </a>
                <a href="<?php echo APP_URL; ?>/seller/payment-methods" class="btn btn-secondary">
                    💳 Métodos de Pago
                </a>
                <a href="<?php echo APP_URL; ?>/seller/orders" class="btn btn-secondary">
                    📋 Ver Órdenes (<?php echo $orderStats['pending']; ?>)
                </a>
            </div>
        </section>
    </div>
</div>
