<?php
$title = 'Detalle de Orden';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📋 Orden #<?php echo $order['id']; ?></h1>
        <a href="<?php echo APP_URL; ?>/seller/orders" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="order-grid">
        <div class="order-section">
            <h3>Información de la Orden</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Producto</label>
                    <p><?php echo htmlspecialchars($order['title']); ?></p>
                </div>
                <div class="info-item">
                    <label>Monto</label>
                    <p class="amount">$<?php echo number_format($order['amount'], 0, ',', '.'); ?></p>
                </div>
                <div class="info-item">
                    <label>Estado</label>
                    <p>
                        <span class="badge badge-<?php
                            echo $order['status'] === 'pending' ? 'warning' :
                                ($order['status'] === 'paid' ? 'info' :
                                ($order['status'] === 'delivered' ? 'success' : 'danger'));
                        ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </p>
                </div>
                <div class="info-item">
                    <label>Fecha</label>
                    <p><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <div class="order-section">
            <h3>Datos del Cliente</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nombre</label>
                    <p><?php echo htmlspecialchars($order['buyer_name']); ?></p>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <p><a href="mailto:<?php echo htmlspecialchars($order['email']); ?>"><?php echo htmlspecialchars($order['email']); ?></a></p>
                </div>
                <div class="info-item">
                    <label>Teléfono</label>
                    <p><?php echo htmlspecialchars($order['phone'] ?? $order['buyer_contact'] ?? '—'); ?></p>
                </div>
                <div class="info-item">
                    <label>Contacto Para Entregar</label>
                    <p><?php echo htmlspecialchars($order['buyer_contact']); ?></p>
                </div>
            </div>
        </div>

        <?php if ($order['payment_proof']): ?>
        <div class="order-section">
            <h3>Comprobante de Pago</h3>
            <img src="<?php echo APP_URL . htmlspecialchars($order['payment_proof']); ?>" alt="Comprobante" class="payment-proof">
        </div>
        <?php endif; ?>

        <div class="order-section">
            <h3>Actualizar Estado</h3>
            <form method="POST" action="<?php echo APP_URL; ?>/seller/orders/<?php echo $order['id']; ?>/update" class="form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nuevo Estado</label>
                        <select name="status" required>
                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="paid" <?php echo $order['status'] === 'paid' ? 'selected' : ''; ?>>Pagado</option>
                            <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Entregado</option>
                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nota (opcional)</label>
                    <textarea name="note" rows="3" placeholder="Ej: Enviado por WhatsApp, credenciales compartidas..."><?php echo htmlspecialchars($order['notes'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Orden</button>
            </form>
        </div>

        <div class="order-section info-banner">
            <h3>💡 Próximos Pasos</h3>
            <ol>
                <li><strong>Verificar pago:</strong> El cliente enviará comprobante</li>
                <li><strong>Confirmar pago:</strong> Marcar como "Pagado"</li>
                <li><strong>Entregar producto:</strong> Enviar acceso/credenciales al cliente</li>
                <li><strong>Marcar entregado:</strong> El cliente podrá dejar reseña</li>
            </ol>
        </div>
    </div>
</div>

<style>
.order-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.order-grid > * {
    grid-column: span 1;
}

.order-grid > .info-banner {
    grid-column: 1 / -1;
}

.order-section {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.order-section h3 {
    margin-top: 0;
    color: var(--primary);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.info-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.85rem;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.25rem;
}

.info-item p {
    margin: 0;
    color: var(--dark);
}

.amount {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--success);
}

.payment-proof {
    max-width: 300px;
    border-radius: var(--radius);
    border: 1px solid var(--border);
}

.info-banner {
    background: #fef3c7;
    border-left: 4px solid var(--warning);
}

.info-banner h3 {
    color: var(--warning);
}

.info-banner ol {
    margin: 0.5rem 0;
    padding-left: 1.5rem;
}

.info-banner li {
    margin: 0.5rem 0;
    color: #92400e;
}

@media (max-width: 768px) {
    .order-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
