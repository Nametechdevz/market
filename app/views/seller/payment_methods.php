<?php
$title = 'Métodos de Pago';
$content = __FILE__;
require __DIR__ . '/../layout.php';
use App\Models\SellerPaymentMethod;
?>

<div class="container">
    <div class="page-header">
        <h1>💳 Mis Métodos de Pago</h1>
        <a href="<?php echo APP_URL; ?>/seller/payment-methods/create" class="btn btn-primary">+ Agregar Método</a>
    </div>

    <div class="info-banner">
        <h3>💡 ¿Cómo funciona?</h3>
        <p>Configura los métodos de pago donde deseas recibir dinero de tus clientes. Pueden usar Nequi, Bancolombia, Bre-B o cualquier otro método que configures.</p>
    </div>

    <div class="payment-methods-grid">
        <?php foreach ($methods as $method): ?>
        <div class="payment-card">
            <div class="payment-header">
                <h3><?php echo SellerPaymentMethod::getMethodLabel($method['method_type']); ?></h3>
                <?php if ($method['is_primary']): ?>
                    <span class="badge badge-primary">Predeterminado</span>
                <?php endif; ?>
            </div>

            <div class="payment-details">
                <p><strong>Titular:</strong> <?php echo htmlspecialchars($method['account_holder']); ?></p>
                <?php if ($method['account_number']): ?>
                    <p><strong>Número:</strong> <?php echo htmlspecialchars(str_repeat('*', strlen($method['account_number']) - 4) . substr($method['account_number'], -4)); ?></p>
                <?php endif; ?>
                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($method['identification_number']); ?></p>

                <?php if ($method['qr_image']): ?>
                    <div class="qr-preview">
                        <img src="<?php echo APP_URL . htmlspecialchars($method['qr_image']); ?>" alt="QR">
                    </div>
                <?php endif; ?>
            </div>

            <div class="payment-actions">
                <a href="<?php echo APP_URL; ?>/seller/payment-methods/<?php echo $method['id']; ?>/edit" class="btn btn-sm btn-primary">Editar</a>

                <?php if (!$method['is_primary']): ?>
                <form method="POST" action="<?php echo APP_URL; ?>/seller/payment-methods/<?php echo $method['id']; ?>/set-primary" style="display:inline;">
                    <button type="submit" class="btn btn-sm btn-secondary">Usar Como Principal</button>
                </form>
                <?php endif; ?>

                <form method="POST" action="<?php echo APP_URL; ?>/seller/payment-methods/<?php echo $method['id']; ?>/delete" style="display:inline;" onsubmit="return confirm('¿Eliminar este método?');">
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($methods)): ?>
        <div class="empty-state" style="grid-column: 1/-1;">
            <p>No has agregado métodos de pago. <a href="<?php echo APP_URL; ?>/seller/payment-methods/create">Agregar ahora</a></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.payment-methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.payment-card {
    background: white;
    border: 2px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    transition: all 0.3s;
}

.payment-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
}

.payment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
}

.payment-header h3 {
    margin: 0;
    font-size: 1.1rem;
}

.payment-details {
    margin-bottom: 1rem;
}

.payment-details p {
    margin: 0.5rem 0;
    font-size: 0.9rem;
}

.qr-preview {
    margin-top: 1rem;
    text-align: center;
}

.qr-preview img {
    width: 150px;
    height: 150px;
    border: 1px solid var(--border);
    border-radius: 4px;
}

.payment-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}

.info-banner {
    background: #e0e7ff;
    border-left: 4px solid var(--primary);
    padding: 1.5rem;
    border-radius: var(--radius);
    margin-bottom: 2rem;
}

.info-banner h3 {
    margin-top: 0;
    color: var(--primary);
}

.info-banner p {
    margin: 0;
    color: #3730a3;
}
</style>
