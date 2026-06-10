<?php
$title = 'Gestión de Planes';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📦 Planes de Suscripción</h1>
        <div>
            <a href="<?php echo APP_URL; ?>/admin/dashboard" class="btn btn-secondary">← Volver</a>
            <a href="<?php echo APP_URL; ?>/admin/plans/create" class="btn btn-primary">+ Nuevo Plan</a>
        </div>
    </div>

    <div class="plans-grid">
        <?php foreach ($plans as $plan):
            $features = json_decode($plan['features'], true) ?? [];
        ?>
        <div class="plan-card">
            <div class="plan-header">
                <h3><?php echo htmlspecialchars($plan['name']); ?></h3>
                <span class="badge badge-<?php echo $plan['status'] === 'active' ? 'success' : 'danger'; ?>">
                    <?php echo ucfirst($plan['status']); ?>
                </span>
            </div>
            <div class="plan-price">
                $<?php echo number_format($plan['price'], 0, ',', '.'); ?>
                <small>/ <?php echo $plan['duration_days']; ?> días</small>
            </div>
            <p class="plan-description"><?php echo htmlspecialchars($plan['description'] ?? ''); ?></p>
            <ul class="plan-features">
                <li><strong>Productos máximos:</strong> <?php echo $plan['max_products']; ?></li>
                <?php foreach ($features as $feature): ?>
                    <li><?php echo htmlspecialchars($feature); ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="plan-meta">
                <span>👥 <?php echo $plan['subscribers']; ?> suscriptores</span>
            </div>
            <div class="plan-actions">
                <a href="<?php echo APP_URL; ?>/admin/plans/<?php echo $plan['id']; ?>/edit" class="btn btn-sm btn-primary">Editar</a>
                <form method="POST" action="<?php echo APP_URL; ?>/admin/plans/<?php echo $plan['id']; ?>/delete" style="display:inline;" onsubmit="return confirm('¿Eliminar plan?');">
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($plans)): ?>
        <div class="empty-state">
            <p>No hay planes creados. <a href="<?php echo APP_URL; ?>/admin/plans/create">Crear el primero</a></p>
        </div>
        <?php endif; ?>
    </div>
</div>
