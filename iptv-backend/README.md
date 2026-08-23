# IPTV Backend — Panel de administración + API

Módulo **aislado** (tablas, autenticación y controladores propios, sin tocar el
marketplace existente) que permite:

- Dar de alta **servidores Xtream Codes** (DNS/host, puerto, HTTPS) desde un panel web.
- Crear y administrar **usuarios de la app Android** (login propio, mapeado a una
  línea usuario/clave dentro de uno de esos servidores, conexiones máximas, vencimiento).
- Exponer una **API REST con token** que consume la app Android para autenticarse
  y navegar canales en vivo + EPG, sin exponer nunca las credenciales reales del
  servidor Xtream al cliente (el backend actúa de intermediario/proxy de metadatos).

## Requisitos

- PHP 8.0+ con extensiones `pdo_mysql`, `curl`, `json`
- MySQL 5.7+
- Composer

## Instalación

```bash
cd iptv-backend
composer install

cp config/config.php.example config/config.php
# editar config/config.php con tus credenciales de BD

mysql -u root -p < database/schema.sql

php bin/create-admin.php admin "tu-password-segura"
```

Servidor de desarrollo:

```bash
php -S localhost:8080 -t public
```

Abre `http://localhost:8080/login`.

En producción, el DocumentRoot debe apuntar a `iptv-backend/public` (Apache con
`mod_rewrite` o Nginx con `try_files ... /index.php`), igual que el resto del proyecto.

## Flujo de uso

1. **Servidores → Agregar servidor**: nombre, DNS/host (sin `http://`), puerto,
   HTTPS opcional. Se puede "Probar conexión" con una línea Xtream real.
2. **Usuarios → Agregar usuario**: usuario/clave que el cliente usará para entrar
   a la app Android, el servidor al que pertenece, y el usuario/clave *reales*
   de su línea en ese servidor Xtream. Conexiones máximas y fecha de vencimiento
   opcionales.
3. La app Android hace login contra `/api/auth/login` con esas credenciales
   propias (no las de Xtream) y recibe un token Bearer.

## API (consumida por la app Android)

Todas las rutas (excepto login) requieren `Authorization: Bearer <token>`.

| Método | Ruta | Descripción |
|---|---|---|
| POST | `/api/auth/login` | `{username, password, device_info}` → `{token, user}` |
| POST | `/api/auth/logout` | Revoca el token actual |
| GET | `/api/profile` | Datos del usuario autenticado |
| GET | `/api/live/categories` | Categorías de TV en vivo |
| GET | `/api/live/streams?category_id=` | Canales de una categoría |
| GET | `/api/epg?stream_id=&limit=` | EPG corta (ahora/próximos) de un canal |
| GET | `/api/stream/live/{stream_id}?ext=ts` | URL reproducible del canal |

## Notas de seguridad

- Las contraseñas (admin y usuarios de la app) se guardan con `password_hash()`.
- Los tokens de la API expiran (`API_TOKEN_TTL_DAYS`, 30 días por defecto).
- `config/config.php` está en `.gitignore`: nunca commitees credenciales reales.
- El usuario/clave de Xtream de cada línea vive solo en el backend; la app
  Android nunca los recibe directamente, solo URLs de stream ya resueltas.

## Roadmap (no incluido en esta primera versión)

- Películas (VOD) y series
- Favoritos y multi-perfil
- Multi-servidor/multi-línea por usuario
