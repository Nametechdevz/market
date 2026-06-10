<?php
$title = 'Checkout';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <h1>💳 Finalizar Compra</h1>

    <form method="POST" action="<?php echo APP_URL; ?>/process-payment" enctype="multipart/form-data" class="checkout-form">
        <div class="checkout-grid">
            <div class="checkout-items">
                <h2>Productos</h2>

                <?php $total = 0; foreach ($cartDetails as $detail): $subtotal = $detail['product']['price'] * $detail['quantity']; $total += $subtotal; ?>
                <div class="checkout-item">
                    <div class="item-header">
                        <h4><?php echo htmlspecialchars($detail['product']['title']); ?></h4>
                        <p class="item-price">$<?php echo number_format($subtotal, 0, ',', '.'); ?></p>
                    </div>

                    <p class="item-seller">Vendedor: <strong><?php echo htmlspecialchars($detail['product']['seller_name']); ?></strong></p>

                    <div class="payment-selection">
                        <label>Método de Pago</label>
                        <select name="payment_method_<?php echo $detail['product']['id']; ?>" required>
                            <option value="">-- Selecciona método de pago --</option>
                            <?php foreach ($detail['payment_methods'] as $method): ?>
                            <option value="<?php echo $method['id']; ?>">
                                <?php echo match($method['method_type']) {
                                    'nequi' => '📱 Nequi',
                                    'bancolombia' => '🏦 Bancolombia',
                                    'bre-b' => '💳 Bre-B',
                                    'daviplata' => '📲 DaviPlata',
                                    'ps_bank' => '💰 Banco Privado',
                                    default => ucfirst($method['method_type'])
                                }; ?> - <?php echo htmlspecialchars($method['account_holder']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if (empty($detail['payment_methods'])): ?>
                        <p class="warning">⚠️ Este vendedor no ha configurado métodos de pago</p>
                        <?php endif; ?>
                    </div>

                    <div class="proof-upload">
                        <label>Comprobante de Pago</label>
                        <input type="file" name="proof_<?php echo $detail['product']['id']; ?>" accept="image/*" required>
                        <small>Sube una captura del comprobante de pago (Nequi, transferencia, etc)</small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="checkout-sidebar">
                <div class="summary-card">
                    <h3>Resumen de Pago</h3>

                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($total, 0, ',', '.'); ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Impuestos (0%):</span>
                        <span>$0</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="contact-info">
                    <h3>Información de Contacto</h3>

                    <div class="form-group">
                        <label>Contacto (Teléfono o Email)</label>
                        <input type="text" name="contact" required placeholder="3105551234 o tu@email.com">
                        <small>Los vendedores te contactarán por aquí para confirmar y entregar</small>
                    </div>
                </div>

                <div class="info-box">
                    <h4>📋 Próximos Pasos</h4>
                    <ol>
                        <li>Completa el pago según el método del vendedor</li>
                        <li>Sube el comprobante para cada producto</li>
                        <li>El vendedor confirmará el pago</li>
                        <li>Recibirás el acceso/credenciales por email o teléfono</li>
                    </ol>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">✓ Finalizar Compra</button>

                <a href="<?php echo APP_URL; ?>/cart" class="btn btn-secondary btn-block">← Volver al Carrito</a>
            </div>
        </div>
    </form>
</div>

<style>
.checkout-form {
    margin: 2rem 0;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.checkout-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.checkout-item {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    border-left: 4px solid var(--primary);
    box-shadow: var(--shadow);
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
}

.item-header h4 {
    margin: 0;
    flex: 1;
}

.item-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
    margin: 0;
}

.item-seller {
    color: #6b7280;
    font-size: 0.9rem;
    margin: 0.5rem 0 1rem;
}

.payment-selection, .proof-upload {
    margin-bottom: 1rem;
}

.payment-selection label, .proof-upload label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.payment-selection select, .proof-upload input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 4px;
}

.proof-upload small {
    color: #6b7280;
    display: block;
    margin-top: 0.25rem;
}

.warning {
    background: #fee2e2;
    color: #991b1b;
    padding: 0.75rem;
    border-radius: 4px;
    font-size: 0.9rem;
    margin: 0.5rem 0;
}

.checkout-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.summary-card, .contact-info, .info-box {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
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

.contact-info h3, .summary-card h3 {
    margin-top: 0;
}

.contact-info .form-group {
    margin: 0;
}

.contact-info input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 4px;
}

.contact-info small {
    color: #6b7280;
    display: block;
    margin-top: 0.25rem;
}

.info-box {
    background: #fef3c7;
    border-left: 4px solid var(--warning);
}

.info-box h4 {
    margin-top: 0;
    color: #92400e;
}

.info-box ol {
    margin: 0;
    padding-left: 1.5rem;
    color: #92400e;
}

.info-box li {
    margin: 0.5rem 0;
}

.btn-lg {
    padding: 1rem;
    font-size: 1.1rem;
}

.btn-block {
    width: 100%;
    margin: 0.5rem 0;
}

@media (max-width: 768px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }

    .item-header {
        flex-direction: column;
    }
}
</style>
