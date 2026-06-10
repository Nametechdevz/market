<?php
$title = 'Mis Compras';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>🛍️ Mis Compras</h1>
    </div>

    <div class="tabs">
        <a href="<?php echo APP_URL; ?>/buyer/purchases" class="tab active">Compras</a>
        <a href="<?php echo APP_URL; ?>/buyer/reviews" class="tab">Mis Reseñas</a>
    </div>

    <div class="purchases-grid">
        <?php foreach ($orders as $order): ?>
        <div class="purchase-card">
            <div class="purchase-image">
                <?php if ($order['image']): ?>
                    <img src="<?php echo APP_URL . htmlspecialchars($order['image']); ?>" alt="">
                <?php else: ?>
                    <div class="placeholder">📦</div>
                <?php endif; ?>
            </div>

            <div class="purchase-info">
                <h3><?php echo htmlspecialchars($order['title']); ?></h3>
                <p class="seller">Por <strong><?php echo htmlspecialchars($order['seller_name']); ?></strong></p>
                <p class="price">$<?php echo number_format($order['amount'], 0, ',', '.'); ?></p>

                <div class="status-badge">
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
                </div>

                <p class="date">Comprado: <?php echo date('d/m/Y', strtotime($order['created_at'])); ?></p>

                <a href="<?php echo APP_URL; ?>/buyer/order/<?php echo $order['id']; ?>" class="btn btn-primary btn-block">Ver Detalles</a>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($orders)): ?>
        <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 3rem;">
            <p>No has realizado compras aún</p>
            <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-primary">Explorar Marketplace</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.tabs {
    display: flex;
    gap: 1rem;
    margin: 2rem 0;
    border-bottom: 2px solid var(--border);
}

.tab {
    padding: 1rem;
    text-decoration: none;
    color: #6b7280;
    font-weight: 500;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.tab:hover {
    color: var(--primary);
}

.tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.purchases-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.purchase-card {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    transition: all 0.3s;
}

.purchase-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

.purchase-image {
    width: 100%;
    height: 150px;
    background: var(--light);
    overflow: hidden;
}

.purchase-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.purchase-image .placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 2.5rem;
}

.purchase-info {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.purchase-info h3 {
    margin: 0 0 0.5rem;
    font-size: 1rem;
    line-height: 1.3;
}

.seller {
    font-size: 0.85rem;
    color: #6b7280;
    margin: 0.25rem 0;
}

.price {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
    margin: 0.5rem 0;
}

.status-badge {
    margin: 1rem 0;
}

.date {
    font-size: 0.85rem;
    color: #9ca3af;
    margin: 0;
}

.btn-block {
    width: 100%;
    margin-top: auto;
}
</style>
