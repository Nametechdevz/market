<?php
$isEdit = isset($method);
$title = $isEdit ? 'Editar Método de Pago' : 'Agregar Método de Pago';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $isEdit ? '✏️ Editar Método de Pago' : '➕ Agregar Método de Pago'; ?></h1>
        <a href="<?php echo APP_URL; ?>/seller/payment-methods" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card">
        <form method="POST" enctype="multipart/form-data" class="form">
            <div class="info-box">
                <h3>Métodos de Pago Disponibles</h3>
                <ul>
                    <li>📱 <strong>Nequi</strong> - Billetera digital</li>
                    <li>🏦 <strong>Bancolombia</strong> - Transferencia bancaria</li>
                    <li>💳 <strong>Bre-B</strong> - Plataforma de pagos</li>
                    <li>📲 <strong>DaviPlata</strong> - Billetera de Davivienda</li>
                    <li>💰 <strong>Banco Privado</strong> - PSBank</li>
                </ul>
            </div>

            <div class="form-group">
                <label>Método de Pago *</label>
                <select name="method_type" required <?php echo $isEdit ? 'disabled' : ''; ?>>
                    <option value="">-- Selecciona un método --</option>
                    <option value="nequi" <?php echo $isEdit && $method['method_type'] === 'nequi' ? 'selected' : ''; ?>>📱 Nequi</option>
                    <option value="bancolombia" <?php echo $isEdit && $method['method_type'] === 'bancolombia' ? 'selected' : ''; ?>>🏦 Bancolombia</option>
                    <option value="bre-b" <?php echo $isEdit && $method['method_type'] === 'bre-b' ? 'selected' : ''; ?>>💳 Bre-B</option>
                    <option value="daviplata" <?php echo $isEdit && $method['method_type'] === 'daviplata' ? 'selected' : ''; ?>>📲 DaviPlata</option>
                    <option value="ps_bank" <?php echo $isEdit && $method['method_type'] === 'ps_bank' ? 'selected' : ''; ?>>💰 Banco Privado</option>
                </select>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="method_type" value="<?php echo $method['method_type']; ?>">
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Titular de la Cuenta *</label>
                    <input type="text" name="account_holder" required
                           value="<?php echo $isEdit ? htmlspecialchars($method['account_holder']) : ''; ?>"
                           placeholder="Tu nombre completo">
                </div>

                <div class="form-group">
                    <label>Cédula / Documento *</label>
                    <input type="text" name="identification_number" required
                           value="<?php echo $isEdit ? htmlspecialchars($method['identification_number']) : ''; ?>"
                           placeholder="12345678">
                </div>
            </div>

            <div class="form-group">
                <label>Número de Cuenta / Teléfono</label>
                <input type="text" name="account_number"
                       value="<?php echo $isEdit ? htmlspecialchars($method['account_number']) : ''; ?>"
                       placeholder="Ej: 3105551234 para Nequi">
                <small>Para Nequi: Tu número de teléfono. Para bancos: Tu número de cuenta o IBAN</small>
            </div>

            <div class="form-group">
                <label>Código QR (opcional)</label>
                <input type="file" name="qr_image" accept="image/*">
                <small>Si usas Bre-B o tienes un QR, puedes subirlo aquí. Máximo 5MB</small>
                <?php if ($isEdit && $method['qr_image']): ?>
                    <div style="margin-top: 0.5rem;">
                        <img src="<?php echo APP_URL . htmlspecialchars($method['qr_image']); ?>" alt="" style="width: 150px; border-radius: 4px;">
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($isEdit): ?>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_primary" value="1" <?php echo $method['is_primary'] ? 'checked' : ''; ?>>
                    Usar como método predeterminado
                </label>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary btn-lg"><?php echo $isEdit ? 'Actualizar' : 'Agregar'; ?> Método</button>
        </form>
    </div>
</div>

<style>
.info-box {
    background: #f0fdf4;
    border-left: 4px solid var(--success);
    padding: 1.5rem;
    border-radius: var(--radius);
    margin-bottom: 2rem;
}

.info-box h3 {
    margin-top: 0;
    color: #15803d;
}

.info-box ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0 0;
}

.info-box li {
    padding: 0.5rem 0;
    color: #166534;
}
</style>
