<?php
$title = 'Mi Carrito';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <h1>🛒 Mi Carrito</h1>

    <?php if (empty($cart)): ?>
    <div class="empty-cart">
        <p>Tu carrito está vacío</p>
        <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-primary">Seguir comprando</a>
    </div>
    <?php else: ?>

    <div class="cart-grid">
        <div class="cart-items">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; foreach ($cart as $productId => $item): ?>
                    <tr>
                        <td>
                            <div class="cart-item-info">
                                <?php if (isset($item['image']) && $item['image']): ?>
                                    <img src="<?php echo APP_URL . htmlspecialchars($item['image']); ?>" alt="">
                                <?php else: ?>
                                    <div class="cart-item-placeholder">📦</div>
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo htmlspecialchars($item['title']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($item['seller_name'] ?? 'Vendedor'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>$<?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                        <td>
                            <input type="number" name="qty" min="1" value="<?php echo $item['quantity']; ?>" class="qty-input">
                        </td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/remove-from-cart/<?php echo $productId; ?>" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                    <?php $total += $item['price'] * $item['quantity']; endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <div class="summary-card">
                <h3>Resumen</h3>

                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>

                <div class="summary-row">
                    <span>Envío:</span>
                    <span>Digital (Gratis)</span>
                </div>

                <div class="summary-row total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>

                <a href="<?php echo APP_URL; ?>/checkout" class="btn btn-primary btn-block">Proceder al Pago</a>
                <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-secondary btn-block">Seguir Comprando</a>
            </div>

            <div class="promo-card">
                <h4>💡 Consejo</h4>
                <p>Los productos son digitales y se entregan inmediatamente después de confirmar el pago.</p>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<style>
.empty-cart {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.cart-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin: 2rem 0;
}

.cart-items {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    background: var(--light);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border);
}

.cart-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
}

.cart-item-info {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.cart-table img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
}

.cart-item-placeholder {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light);
    border-radius: 4px;
    font-size: 1.5rem;
}

.qty-input {
    width: 70px;
    padding: 0.5rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    text-align: center;
}

.cart-summary {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.summary-card {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.summary-card h3 {
    margin-top: 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border);
}

.summary-row.total {
    border: none;
    font-weight: bold;
    font-size: 1.25rem;
    color: var(--primary);
    padding: 1rem 0;
    margin-top: 0.5rem;
}

.btn-block {
    width: 100%;
    margin: 0.5rem 0;
}

.promo-card {
    background: #fef3c7;
    padding: 1.5rem;
    border-radius: var(--radius);
    border-left: 4px solid var(--warning);
}

.promo-card h4 {
    margin-top: 0;
    color: #92400e;
}

.promo-card p {
    margin: 0;
    color: #92400e;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .cart-grid {
        grid-template-columns: 1fr;
    }

    .cart-table {
        font-size: 0.9rem;
    }

    .cart-table th, .cart-table td {
        padding: 0.5rem;
    }
}
</style>
