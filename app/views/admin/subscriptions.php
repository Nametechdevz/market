<?php
$title = 'Suscripciones';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>💳 Suscripciones</h1>
        <div>
            <a href="<?php echo APP_URL; ?>/admin/dashboard" class="btn btn-secondary">← Volver</a>
            <a href="<?php echo APP_URL; ?>/admin/subscriptions/assign" class="btn btn-primary">+ Asignar Suscripción</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <h3>Total</h3>
            <p class="stat-value"><?php echo $stats['total']; ?></p>
        </div>
        <div class="stat-card stat-success">
            <h3>Activas</h3>
            <p class="stat-value"><?php echo $stats['active']; ?></p>
        </div>
        <div class="stat-card stat-warning">
            <h3>Expiradas</h3>
            <p class="stat-value"><?php echo $stats['expired']; ?></p>
        </div>
        <div class="stat-card stat-danger">
            <h3>Canceladas</h3>
            <p class="stat-value"><?php echo $stats['cancelled']; ?></p>
        </div>
    </div>

    <div class="card">
        <div class="filter-bar">
            <a href="<?php echo APP_URL; ?>/admin/subscriptions" class="btn btn-sm btn-secondary">Todas</a>
            <a href="<?php echo APP_URL; ?>/admin/subscriptions?status=active" class="btn btn-sm btn-success">Activas</a>
            <a href="<?php echo APP_URL; ?>/admin/subscriptions?status=expired" class="btn btn-sm btn-warning">Expiradas</a>
            <a href="<?php echo APP_URL; ?>/admin/subscriptions?status=cancelled" class="btn btn-sm btn-danger">Canceladas</a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Plan</th>
                        <th>Precio</th>
                        <th>Inicio</th>
                        <th>Vence</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscriptions as $sub): ?>
                    <tr>
                        <td>#<?php echo $sub['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($sub['user_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($sub['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($sub['plan_name']); ?></td>
                        <td>$<?php echo number_format($sub['price'], 0, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($sub['starts_at'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($sub['ends_at'])); ?></td>
                        <td>
                            <span class="badge badge-<?php
                                echo $sub['status'] === 'active' ? 'success' :
                                    ($sub['status'] === 'expired' ? 'warning' : 'danger');
                            ?>">
                                <?php echo ucfirst($sub['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($sub['status'] === 'active'): ?>
                            <form method="POST" action="<?php echo APP_URL; ?>/admin/subscriptions/<?php echo $sub['id']; ?>/cancel" style="display:inline;" onsubmit="return confirm('¿Cancelar?');">
                                <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($subscriptions)): ?>
                    <tr><td colspan="8" class="empty-state">No hay suscripciones</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
