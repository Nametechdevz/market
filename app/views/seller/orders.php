<?php
$title = 'Mis Órdenes';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📋 Órdenes Recibidas</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <h3>Total de Órdenes</h3>
            <p class="stat-value"><?php echo $stats['total_orders']; ?></p>
        </div>
        <div class="stat-card stat-warning">
            <h3>Pendientes</h3>
            <p class="stat-value"><?php echo $stats['pending']; ?></p>
        </div>
        <div class="stat-card stat-info">
            <h3>Pagadas</h3>
            <p class="stat-value"><?php echo $stats['paid']; ?></p>
        </div>
        <div class="stat-card stat-success">
            <h3>Entregadas</h3>
            <p class="stat-value"><?php echo $stats['delivered']; ?></p>
        </div>
    </div>

    <div class="card">
        <div class="filter-bar">
            <a href="<?php echo APP_URL; ?>/seller/orders" class="btn btn-sm btn-secondary">Todas</a>
            <a href="<?php echo APP_URL; ?>/seller/orders?status=pending" class="btn btn-sm btn-warning">Pendientes (<?php echo $stats['pending']; ?>)</a>
            <a href="<?php echo APP_URL; ?>/seller/orders?status=paid" class="btn btn-sm btn-info">Pagadas (<?php echo $stats['paid']; ?>)</a>
            <a href="<?php echo APP_URL; ?>/seller/orders?status=delivered" class="btn btn-sm btn-success">Entregadas (<?php echo $stats['delivered']; ?>)</a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Método de Pago</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($order['product_title']); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($order['buyer_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($order['email']); ?></small>
                        </td>
                        <td>$<?php echo number_format($order['amount'], 0, ',', '.'); ?></td>
                        <td>
                            <span class="badge badge-<?php
                                echo $order['status'] === 'pending' ? 'warning' :
                                    ($order['status'] === 'paid' ? 'info' :
                                    ($order['status'] === 'delivered' ? 'success' : 'danger'));
                            ?>">
                                <?php
                                echo match($order['status']) {
                                    'pending' => '⏳ Pendiente',
                                    'paid' => '💳 Pagado',
                                    'delivered' => '✓ Entregado',
                                    'cancelled' => '✕ Cancelado',
                                    default => ucfirst($order['status'])
                                };
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $order['method_type'] ? ucfirst($order['method_type']) : '—'; ?>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/seller/orders/<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">Ver</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($orders)): ?>
                    <tr><td colspan="8" class="empty-state">No hay órdenes</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.stat-info { border-left-color: #3b82f6; }
</style>
