<?php
$title = 'Registrarse';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Crear Cuenta</h2>

        <form method="POST" action="<?php echo APP_URL; ?>/register" class="form">
            <div class="form-group">
                <label for="name">Nombre Completo</label>
                <input type="text" id="name" name="name" required minlength="3" placeholder="Tu nombre">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required minlength="8" placeholder="Mínimo 8 caracteres">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmar Contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" required placeholder="Repite tu contraseña">
            </div>

            <div class="form-group">
                <label for="role">¿Qué tipo de cuenta deseas?</label>
                <select id="role" name="role" required>
                    <option value="buyer">Comprador (Acceso a productos)</option>
                    <option value="seller">Vendedor (Vender productos)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
        </form>

        <p class="auth-link">
            ¿Ya tienes cuenta? <a href="<?php echo APP_URL; ?>/login">Inicia sesión aquí</a>
        </p>
    </div>
</div>
