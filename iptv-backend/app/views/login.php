<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ingresar - Panel IPTV</title>
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="login-body">
<form class="login-card" method="post" action="/login">
    <div class="brand">📺 Panel IPTV</div>
    <h1>Iniciar sesión</h1>
    <?php if (!empty($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <label>Usuario
        <input type="text" name="username" required autofocus>
    </label>
    <label>Contraseña
        <input type="password" name="password" required>
    </label>
    <button type="submit">Entrar</button>
</form>
</body>
</html>
