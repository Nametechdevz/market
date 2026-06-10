<?php
$title = 'Iniciar Sesión';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Iniciar Sesión</h2>

        <form method="POST" action="<?php echo APP_URL; ?>/login" class="form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Tu contraseña">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </form>

        <p class="auth-link">
            ¿No tienes cuenta? <a href="<?php echo APP_URL; ?>/register">Regístrate aquí</a>
        </p>
    </div>
</div>
