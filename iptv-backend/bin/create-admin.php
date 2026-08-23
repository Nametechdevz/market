<?php
/**
 * Crea el primer administrador del panel.
 * Uso: php bin/create-admin.php <usuario> <password>
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Models\AdminUser;

[$script, $username, $password] = array_pad($argv, 3, null);

if (!$username || !$password) {
    fwrite(STDERR, "Uso: php bin/create-admin.php <usuario> <password>\n");
    exit(1);
}

if (AdminUser::findByUsername($username)) {
    fwrite(STDERR, "Ya existe un administrador con ese usuario.\n");
    exit(1);
}

$id = AdminUser::create($username, $password);
echo "Administrador creado (id={$id}). Ya puedes iniciar sesión en /login.\n";
