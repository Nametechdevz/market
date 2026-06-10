<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="<?php echo APP_URL; ?>/">
                <strong>🛍️ StreamMarket</strong>
            </a>
        </div>
        <ul class="navbar-menu">
            <li><a href="<?php echo APP_URL; ?>/marketplace">🔍 Explorar</a></li>

            <?php if (Auth::isLoggedIn()): ?>
                <?php if (Auth::hasRole('buyer')): ?>
                <li><a href="<?php echo APP_URL; ?>/cart" class="cart-link">🛒 Carrito</a></li>
                <li><a href="<?php echo APP_URL; ?>/buyer/purchases">📦 Mis Compras</a></li>
                <?php endif; ?>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        👤 <?php echo Auth::user()['name']; ?> ▼
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (Auth::hasRole('admin')): ?>
                            <li><a href="<?php echo APP_URL; ?>/admin/dashboard">📊 Panel Admin</a></li>
                        <?php elseif (Auth::hasRole('seller')): ?>
                            <li><a href="<?php echo APP_URL; ?>/seller/dashboard">🏪 Mi Tienda</a></li>
                            <li><a href="<?php echo APP_URL; ?>/seller/products">📦 Productos</a></li>
                            <li><a href="<?php echo APP_URL; ?>/seller/payment-methods">💳 Métodos de Pago</a></li>
                            <li><a href="<?php echo APP_URL; ?>/seller/orders">📋 Órdenes</a></li>
                        <?php endif; ?>
                        <li><hr style="margin: 0.25rem 0;"></li>
                        <li><a href="<?php echo APP_URL; ?>/logout">🚪 Cerrar Sesión</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="<?php echo APP_URL; ?>/login" class="btn btn-primary">Inicia Sesión</a></li>
                <li><a href="<?php echo APP_URL; ?>/register" class="btn btn-secondary">Regístrate</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
