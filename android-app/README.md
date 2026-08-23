# IPTV Player — App Android

App Android (Kotlin + Jetpack Compose + Media3/ExoPlayer) estilo Xuper TV / Magic TV:
login contra el panel (`../iptv-backend`), navegación de TV en vivo por categorías,
EPG (ahora/próximos) y reproducción de canales.

## ⚠️ Estado de esta entrega

Este proyecto se generó en un entorno **sin Android SDK ni acceso al repositorio
Maven de Google** (sandbox aislado), por lo que el código **no pudo compilarse ni
ejecutarse aquí**. Se verificó manualmente (sintaxis, imports, balance de
llaves/paréntesis en los 20 archivos Kotlin) pero **debes abrirlo en Android
Studio antes de darlo por bueno**: instala el SDK que Studio te pida (compileSdk
34) y deja que sincronice Gradle — puede que necesites ajustar alguna versión de
librería si para entonces hay una más nueva.

## Requisitos

- Android Studio (Koala o más reciente)
- JDK 17 (incluido en Android Studio)

## Configuración

La URL del backend se define en `app/build.gradle.kts` (`API_BASE_URL`, por
defecto `http://10.0.2.2:8080/` para apuntar al `php -S` local desde el
emulador). En la pantalla de login también se puede pegar otra URL de panel
sin recompilar: se guarda en el dispositivo y se usa desde ahí en adelante.

Si tu panel usa HTTP plano (típico en servidores Xtream), ya está habilitado
`usesCleartextTraffic` en el manifest — restringe el dominio en
`res/xml/network_security_config.xml` para producción.

## Abrir y ejecutar

1. Abre la carpeta `android-app/` en Android Studio.
2. Deja que sincronice Gradle (usa el wrapper incluido en `gradle/wrapper/`).
3. Levanta el backend (`iptv-backend`) y crea al menos un servidor + un usuario
   desde el panel admin.
4. Ejecuta la app en un emulador o dispositivo, inicia sesión con ese usuario.

## Estructura

```
app/src/main/java/com/streammarket/iptvplayer/
├── data/
│   ├── model/        # DTOs de la API
│   ├── remote/        # Retrofit + servicio
│   ├── local/          # SessionManager (DataStore: token, base URL)
│   └── repository/    # AuthRepository, LiveRepository
├── ui/
│   ├── login/          # Pantalla de login
│   ├── home/            # Categorías + grilla de canales + buscador
│   ├── player/          # ExoPlayer + panel de EPG
│   ├── navigation/    # NavGraph (Compose Navigation)
│   └── theme/            # Colores/tipografía (mismo estilo que el panel admin)
└── MainActivity.kt
```

## Funciones incluidas (fase 1)

- Login contra el panel (usuario propio, no las credenciales Xtream).
- Categorías de TV en vivo + grilla de canales con logo.
- Buscador de canales por nombre.
- Reproducción en vivo (ExoPlayer, HLS/TS).
- EPG corta (ahora/próximos) superpuesta en el reproductor.

## Roadmap

- Películas (VOD) y series
- Favoritos
- Selección de múltiples servidores/líneas por usuario
- Modo TV (Android TV / leanback) y control remoto
