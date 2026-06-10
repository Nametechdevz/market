# StreamMarket - Marketplace

Plataforma completa para compra y venta de cursos, plataformas streaming y productos digitales con sistema de suscripciones y métodos de pago personalizados.

## 📋 Requisitos

- PHP 8.0+
- MySQL 5.7+
- Apache con mod_rewrite (o Nginx)

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone <repo-url>
cd market
```

### 2. Crear Base de Datos

```bash
mysql -u root -p < database/schema.sql
```

O importar `database/schema.sql` desde phpMyAdmin.

### 3. Configurar la aplicación

```bash
cp config/database.php.example config/database.php
```

Editar `config/database.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'tu_contraseña');
define('DB_NAME', 'marketplace');
define('APP_URL', 'http://localhost/market');
```

### 4. Crear directorio de uploads

```bash
mkdir -p public/assets/uploads
chmod 755 public/assets/uploads
```

### 5. Configurar servidor web

#### Apache

El `.htaccess` ya está configurado. Solo asegúrate de que `mod_rewrite` esté habilitado:

```bash
a2enmod rewrite
systemctl restart apache2
```

DocumentRoot debe apuntar a `/path/to/market/public`

#### Nginx

```nginx
server {
    listen 80;
    server_name market.local;
    root /path/to/market/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### 6. Crear usuario admin (temporal)

```php
php bin/create-admin.php
```

## 📁 Estructura del Proyecto

```
market/
├── public/              # DocumentRoot
│   ├── index.php       # Punto de entrada
│   ├── .htaccess       # Reescritura de URLs
│   └── assets/
│       ├── css/        # Estilos
│       ├── js/         # JavaScript
│       └── uploads/    # Archivos subidos
├── app/
│   ├── controllers/    # Lógica de rutas
│   ├── models/         # BD
│   ├── core/           # Clases base
│   └── views/          # Templates
├── config/             # Configuración
├── database/           # Schema SQL
└── README.md
```

## 🔐 Seguridad

- ✅ Contraseñas hasheadas con `password_hash()`
- ✅ Validación de entrada en todos los formularios
- ✅ Prepared statements contra SQL injection
- ✅ CSRF tokens (implementar en Fase 2)
- ⚠️ HTTPS obligatorio en producción
- ⚠️ Implementar rate limiting en login

## 🎯 Roadmap

### Fase 1 (✅ Actual)
- [x] Estructura base
- [x] Autenticación (login/registro)
- [x] Roles (admin, seller, buyer)
- [x] BD completa

### Fase 2 (Próximo)
- [ ] Panel de admin (gestión de usuarios/planes)
- [ ] Panel de vendedor (productos/pagos)
- [ ] Sistema de suscripciones
- [ ] CSRF tokens

### Fase 3
- [ ] Marketplace público
- [ ] Flujo de compra
- [ ] Métodos de pago personalizados
- [ ] Upload de QR/comprobantes

### Fase 4
- [ ] Sistema de reseñas
- [ ] Chat comprador-vendedor
- [ ] Gestión de disputas

### Fase 5
- [ ] Análitica para vendedores
- [ ] Email notifications
- [ ] Sistema de referidos

## 🛠️ Development

### Ejecutar servidor local

```bash
php -S localhost:8000 -t public/
```

Abre `http://localhost:8000` en tu navegador.

### Crear usuario de prueba

1. Regístrate en `/register`
2. Selecciona "Vendedor"
3. Accede a `/seller/dashboard`

## 📝 Notas

- La BD incluye planes default. Puedes crearlos desde admin panel (Fase 2)
- Los uploads de imágenes van a `public/assets/uploads/`
- Las sesiones expiran en 1 hora (configurable en `config/database.php`)

## 📞 Soporte

Para reportar bugs o sugerencias, abre un issue en el repositorio.

---

**Made with ❤️ by StreamMarket Team**
