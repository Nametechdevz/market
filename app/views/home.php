<?php
$title = 'Inicio';
$content = __FILE__;
require __DIR__ . '/layout.php';
?>

<section class="hero">
    <div class="container">
        <h1>Bienvenido a StreamMarket</h1>
        <p>La plataforma completa para comprar y vender cursos, plataformas streaming y productos digitales</p>
        <div class="hero-buttons">
            <?php if (!Auth::isLoggedIn()): ?>
                <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-primary">Explorar Productos</a>
                <a href="<?php echo APP_URL; ?>/register?role=seller" class="btn btn-secondary">Convertirse en Vendedor</a>
            <?php else: ?>
                <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-primary">Ir al Marketplace</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="features container">
    <h2>¿Cómo Funciona?</h2>
    <div class="features-grid">
        <div class="feature-card">
            <h3>Para Compradores</h3>
            <ul>
                <li>Acceso a miles de productos</li>
                <li>Sistemas de pago flexibles</li>
                <li>Protección de comprador</li>
                <li>Reseñas y calificaciones</li>
            </ul>
        </div>
        <div class="feature-card">
            <h3>Para Vendedores</h3>
            <ul>
                <li>Panel completo para gestionar productos</li>
                <li>Múltiples métodos de pago (Nequi, Bancolombia, Bre-B, etc)</li>
                <li>Planes de suscripción flexible</li>
                <li>Análitica de ventas en tiempo real</li>
            </ul>
        </div>
    </div>
</section>
