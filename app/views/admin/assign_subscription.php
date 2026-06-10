<?php
$title = 'Asignar Suscripción';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>➕ Asignar Suscripción</h1>
        <a href="<?php echo APP_URL; ?>/admin/subscriptions" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card">
        <form method="POST" class="form">
            <div class="form-group">
                <label>Vendedor *</label>
                <select name="user_id" required>
                    <option value="">-- Selecciona un vendedor --</option>
                    <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>">
                        <?php echo htmlspecialchars($user['name']); ?> (<?php echo $user['email']; ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Plan *</label>
                <select name="plan_id" required>
                    <option value="">-- Selecciona un plan --</option>
                    <?php foreach ($plans as $plan): ?>
                    <option value="<?php echo $plan['id']; ?>">
                        <?php echo htmlspecialchars($plan['name']); ?> - $<?php echo number_format($plan['price'], 0, ',', '.'); ?>
                        (<?php echo $plan['max_products']; ?> productos, <?php echo $plan['duration_days']; ?> días)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Asignar Suscripción</button>
        </form>
    </div>
</div>
