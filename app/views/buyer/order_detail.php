<?php
$title = 'Detalle de Orden';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <a href="<?php echo APP_URL; ?>/buyer/purchases" class="btn btn-secondary" style="margin-bottom: 1.5rem;">← Mis Compras</a>

    <div class="order-detail-grid">
        <div class="order-detail-main">
            <div class="order-section">
                <h2>Orden #<?php echo $order['id']; ?></h2>

                <div class="order-info-grid">
                    <div class="info-item">
                        <label>Producto</label>
                        <p><?php echo htmlspecialchars($order['title']); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Vendedor</label>
                        <p><?php echo htmlspecialchars($order['seller_name']); ?></p>
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
                </div>
            </div>

            <!-- Información de Entrega -->
            <?php if ($order['status'] === 'delivered' && $order['delivery_info']): ?>
            <div class="order-section">
                <h3>📦 Información de Entrega</h3>
                <div class="delivery-info">
                    <?php echo nl2br(htmlspecialchars($order['delivery_info'])); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Chat -->
            <div class="order-section">
                <h3>💬 Mensajes con el Vendedor</h3>

                <div class="messages-list">
                    <?php foreach ($messages as $msg): ?>
                    <div class="message" style="<?php echo $msg['sender_id'] == Auth::userId() ? 'margin-left: 2rem;' : ''; ?>">
                        <div class="message-header">
                            <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                            <small><?php echo date('d/m H:i', strtotime($msg['created_at'])); ?></small>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($order['status'] !== 'cancelled'): ?>
                <form method="POST" action="<?php echo APP_URL; ?>/buyer/order/<?php echo $order['id']; ?>/message" class="message-form">
                    <textarea name="message" placeholder="Escribe un mensaje..." required></textarea>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
                <?php endif; ?>
            </div>

            <!-- Reseña -->
            <?php if ($order['status'] === 'delivered'): ?>
            <div class="order-section">
                <h3>⭐ Reseña</h3>

                <?php if ($existingReview): ?>
                <div class="existing-review">
                    <p><strong>Tu reseña:</strong></p>
                    <div class="review-stars">
                        <?php for ($i = 0; $i < 5; $i++) {
                            echo $i < $existingReview['rating'] ? '⭐' : '☆';
                        } ?>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($existingReview['comment'])); ?></p>
                </div>
                <?php else: ?>
                <form method="POST" action="<?php echo APP_URL; ?>/buyer/order/<?php echo $order['id']; ?>/review" class="review-form">
                    <div class="form-group">
                        <label>Calificación</label>
                        <div class="rating-selector">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <label class="star-label">
                                <input type="radio" name="rating" value="<?php echo $i; ?>" required>
                                <span>⭐</span>
                            </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Comentario</label>
                        <textarea name="comment" placeholder="Comparte tu experiencia..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Reseña</button>
                </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="order-sidebar">
            <div class="order-card">
                <h3>Resumen</h3>
                <div class="order-summary">
                    <div class="row">
                        <span>Producto:</span>
                        <strong><?php echo htmlspecialchars($order['title']); ?></strong>
                    </div>
                    <div class="row">
                        <span>Precio:</span>
                        <strong>$<?php echo number_format($order['amount'], 0, ',', '.'); ?></strong>
                    </div>
                    <div class="row">
                        <span>Fecha:</span>
                        <span><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                    </div>
                </div>
            </div>

            <div class="order-card">
                <h3>Vendedor</h3>
                <p><strong><?php echo htmlspecialchars($order['seller_name']); ?></strong></p>
                <p><a href="mailto:<?php echo htmlspecialchars($order['email']); ?>"><?php echo htmlspecialchars($order['email']); ?></a></p>
            </div>

            <div class="order-card status">
                <h3>Estado Actual</h3>
                <p>
                    <span class="badge badge-lg badge-<?php
                        echo $order['status'] === 'pending' ? 'warning' :
                            ($order['status'] === 'paid' ? 'info' :
                            ($order['status'] === 'delivered' ? 'success' : 'danger'));
                    ?>">
                        <?php
                        echo match($order['status']) {
                            'pending' => '⏳ Pendiente de Pago',
                            'paid' => '💳 Pagado - En Proceso',
                            'delivered' => '✓ Entregado',
                            'cancelled' => '✕ Cancelado',
                            default => ucfirst($order['status'])
                        };
                        ?>
                    </span>
                </p>

                <?php
                $steps = [
                    ['Pendiente', $order['status'] !== 'cancelled'],
                    ['Pagado', in_array($order['status'], ['paid', 'delivered'])],
                    ['Entregado', $order['status'] === 'delivered']
                ];
                ?>
                <div class="progress-steps">
                    <?php foreach ($steps as $i => $step): ?>
                    <div class="step <?php echo $step[1] ? 'completed' : ''; ?>">
                        <span><?php echo $i + 1; ?></span>
                        <p><?php echo $step[0]; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin: 2rem 0;
}

.order-section {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}

.order-section h2 {
    margin-top: 0;
}

.order-section h3 {
    margin-top: 0;
    color: var(--primary);
}

.order-info-grid {
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
    color: var(--primary);
}

.delivery-info {
    background: #f0fdf4;
    border-left: 4px solid var(--success);
    padding: 1rem;
    border-radius: 4px;
    color: #166534;
}

.messages-list {
    background: #fafafa;
    border: 1px solid var(--border);
    border-radius: 4px;
    padding: 1rem;
    margin-bottom: 1rem;
    max-height: 300px;
    overflow-y: auto;
}

.message {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: white;
    border-radius: 4px;
}

.message-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.25rem;
}

.message-header small {
    color: #9ca3af;
}

.message p {
    margin: 0;
    color: #4b5563;
    font-size: 0.9rem;
}

.message-form {
    display: flex;
    gap: 0.5rem;
}

.message-form textarea {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-family: inherit;
    resize: none;
    height: 80px;
}

.review-form {
    padding: 1rem;
    background: #f9fafb;
    border-radius: 4px;
}

.review-form .form-group {
    margin-bottom: 1rem;
}

.review-form label {
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}

.review-form textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
}

.rating-selector {
    display: flex;
    gap: 0.5rem;
}

.star-label {
    cursor: pointer;
    font-size: 2rem;
    opacity: 0.3;
    transition: opacity 0.2s;
}

.star-label input {
    display: none;
}

.star-label input:checked ~ span,
.star-label:hover span {
    opacity: 1;
}

.existing-review {
    background: #f0fdf4;
    padding: 1rem;
    border-radius: 4px;
    border-left: 4px solid var(--success);
}

.review-stars {
    color: #f59e0b;
    margin: 0.5rem 0;
}

.order-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.order-card {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.order-card h3 {
    margin-top: 0;
}

.order-card p {
    margin: 0.5rem 0;
}

.order-summary {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.order-summary .row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border);
}

.order-summary .row:last-child {
    border: none;
}

.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.progress-steps {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.step {
    flex: 1;
    text-align: center;
    opacity: 0.3;
    transition: opacity 0.3s;
}

.step.completed {
    opacity: 1;
}

.step span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    background: var(--light);
    border-radius: 50%;
    margin: 0 auto 0.25rem;
    font-weight: bold;
    color: var(--primary);
}

.step.completed span {
    background: var(--success);
    color: white;
}

.step p {
    margin: 0;
    font-size: 0.75rem;
    color: #6b7280;
}

@media (max-width: 768px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
    }

    .order-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
