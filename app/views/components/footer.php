<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>🛍️ StreamMarket</h3>
                <p>Plataforma de compra y venta de cursos, streaming y productos digitales.</p>
                <p class="footer-tagline">Conectando vendedores y compradores de contenido digital.</p>
            </div>
            <div class="footer-section">
                <h4>📍 Navegación</h4>
                <ul>
                    <li><a href="<?php echo APP_URL; ?>/">Inicio</a></li>
                    <li><a href="<?php echo APP_URL; ?>/marketplace">Explorar Marketplace</a></li>
                    <li><a href="<?php echo APP_URL; ?>/contact">Contacto</a></li>
                    <?php if (!Auth::isLoggedIn()): ?>
                    <li><a href="<?php echo APP_URL; ?>/register">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-section">
                <h4>⚖️ Legal</h4>
                <ul>
                    <li><a href="<?php echo APP_URL; ?>/terms">Términos de Servicio</a></li>
                    <li><a href="<?php echo APP_URL; ?>/privacy">Política de Privacidad</a></li>
                    <li><a href="<?php echo APP_URL; ?>/contact">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>📞 Soporte</h4>
                <ul>
                    <li>📧 support@streammarket.com</li>
                    <li>🌐 www.streammarket.com</li>
                    <li>🇨🇴 Colombia</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 StreamMarket. Todos los derechos reservados. | Hecho con ❤️ en Colombia</p>
        </div>
    </div>
</footer>
