<?php
$title = 'Upgradear Plan';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📦 Cambiar Plan</h1>
        <a href="<?php echo APP_URL; ?>/seller/dashboard" class="btn btn-secondary">← Volver</a>
    </div>

    <?php if ($currentSubscription): ?>
    <div class="current-plan-banner">
        <h3>Plan Actual: <?php echo htmlspecialchars($currentSubscription['plan_name']); ?></h3>
        <p>Válido hasta: <strong><?php echo date('d/m/Y', strtotime($currentSubscription['ends_at'])); ?></strong></p>
    </div>
    <?php endif; ?>

    <div class="plans-grid">
        <?php foreach ($plans as $plan):
            $features = json_decode($plan['features'], true) ?? [];
            $isCurrentPlan = $currentSubscription && $currentSubscription['plan_id'] === $plan['id'];
        ?>
        <div class="plan-card <?php echo $isCurrentPlan ? 'active' : ''; ?>">
            <div class="plan-header">
                <h3><?php echo htmlspecialchars($plan['name']); ?></h3>
                <?php if ($isCurrentPlan): ?>
                    <span class="badge badge-success">Plan Actual</span>
                <?php endif; ?>
            </div>

            <div class="plan-price">
                <?php if ($plan['price'] == 0): ?>
                    <span class="price-free">Gratis</span>
                <?php else: ?>
                    $<?php echo number_format($plan['price'], 0, ',', '.'); ?>
                    <small>/ mes</small>
                <?php endif; ?>
            </div>

            <p class="plan-duration"><?php echo $plan['duration_days']; ?> días de acceso</p>

            <div class="plan-features">
                <p class="feature-highlight">Incluye hasta <strong><?php echo $plan['max_products'] === -1 ? '∞ ilimitados' : $plan['max_products']; ?> productos</strong></p>
                <?php foreach ($features as $feature): ?>
                    <div class="feature-item">
                        <span class="checkmark">✓</span>
                        <?php echo htmlspecialchars($feature); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="POST" action="<?php echo APP_URL; ?>/seller/buy-plan" style="width: 100%;">
                <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
                <button type="submit" class="btn btn-primary btn-block"
                        <?php echo $isCurrentPlan ? 'disabled' : ''; ?>>
                    <?php echo $isCurrentPlan ? 'Plan Actual' : ($plan['price'] == 0 ? 'Usar Plan Gratuito' : 'Upgradear Ahora'); ?>
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="faq-section">
        <h2>Preguntas Frecuentes</h2>
        <div class="faq-item">
            <h4>¿Puedo cambiar de plan en cualquier momento?</h4>
            <p>Sí, puedes cambiar tu plan en cualquier momento. El cambio se efectuará inmediatamente.</p>
        </div>
        <div class="faq-item">
            <h4>¿Qué pasa si cancelo mi suscripción?</h4>
            <p>Tu acceso se limitará al plan gratuito (3 productos máximo). Tus productos existentes seguirán en la plataforma.</p>
        </div>
        <div class="faq-item">
            <h4>¿Hay reembolsos?</h4>
            <p>Las suscripciones son no reembolsables. Sin embargo, el acceso incluye toda la duración del plan (30 días).</p>
        </div>
    </div>
</div>

<style>
.current-plan-banner {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    margin-bottom: 2rem;
}

.current-plan-banner h3 {
    margin-top: 0;
    font-size: 1.5rem;
}

.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.plan-card {
    background: white;
    border: 2px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
}

.plan-card:hover:not(.active) {
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
    transform: translateY(-8px);
}

.plan-card.active {
    border-color: var(--primary);
    background: #f0f7ff;
    box-shadow: var(--shadow-lg);
}

.plan-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.plan-header h3 {
    margin: 0;
    color: var(--primary);
}

.plan-price {
    font-size: 2rem;
    font-weight: bold;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.price-free {
    font-size: 1.5rem;
    color: var(--success);
}

.plan-price small {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: normal;
    display: block;
}

.plan-duration {
    color: #6b7280;
    margin: 0.5rem 0 1rem;
    font-size: 0.9rem;
}

.plan-features {
    flex: 1;
    margin-bottom: 1.5rem;
}

.feature-highlight {
    font-weight: 600;
    color: var(--primary);
    margin: 0.5rem 0 1rem;
}

.feature-item {
    padding: 0.5rem 0;
    color: #4b5563;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
}

.checkmark {
    color: var(--success);
    font-weight: bold;
    margin-right: 0.75rem;
}

.btn-block {
    width: 100%;
}

.faq-section {
    background: white;
    padding: 2rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.faq-section h2 {
    margin-top: 0;
    color: var(--primary);
}

.faq-item {
    margin-bottom: 1.5rem;
}

.faq-item h4 {
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.faq-item p {
    color: #6b7280;
    margin: 0;
}
</style>
