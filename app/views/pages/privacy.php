<?php
$title = 'Política de Privacidad';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>🔒 Política de Privacidad</h1>
    </div>

    <div class="content-page">
        <h2>Información que Recopilamos</h2>
        <ul>
            <li><strong>Información de Registro:</strong> Nombre, email, teléfono</li>
            <li><strong>Información de Pago:</strong> Métodos de pago y comprobantes</li>
            <li><strong>Información de Transacciones:</strong> Historial de compras y ventas</li>
            <li><strong>Información de Uso:</strong> Logs de acceso, IP, navegador</li>
        </ul>

        <h2>Cómo Usamos tu Información</h2>
        <ul>
            <li>Procesar transacciones y pagos</li>
            <li>Comunicaciones entre comprador y vendedor</li>
            <li>Mejorar nuestros servicios</li>
            <li>Cumplimiento legal</li>
            <li>Prevención de fraude</li>
        </ul>

        <h2>Protección de Datos</h2>
        <p>Utilizamos encriptación SSL/TLS para proteger tu información. Las contraseñas se almacenan con hash bcrypt.</p>

        <h2>Compartir Información</h2>
        <p>NO compartimos tu información con terceros, excepto:</p>
        <ul>
            <li>Cuando es necesario para la transacción (ej: vendedor para entrega)</li>
            <li>Por orden legal o de gobierno</li>
            <li>Con tu consentimiento explícito</li>
        </ul>

        <h2>Tus Derechos</h2>
        <ul>
            <li>Acceder a tu información personal</li>
            <li>Solicitar correcciones</li>
            <li>Solicitar eliminación de datos</li>
            <li>Optar por no recibir comunicaciones</li>
        </ul>

        <h2>Cookies</h2>
        <p>Usamos cookies para mantener sesión y mejorar experiencia. Puedes deshabilitarlas en tu navegador.</p>

        <h2>Cambios a esta Política</h2>
        <p>Podemos actualizar esta política en cualquier momento. Te notificaremos de cambios significativos.</p>

        <h2>Contacto</h2>
        <p>Para preguntas sobre privacidad: privacy@streammarket.com</p>
    </div>
</div>

<style>
.content-page {
    max-width: 800px;
    background: white;
    padding: 2rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin: 2rem 0;
}

.content-page h2 {
    color: var(--primary);
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-page p, .content-page ul {
    color: #4b5563;
    line-height: 1.8;
}

.content-page ul {
    list-style: disc;
    padding-left: 2rem;
}

.content-page li {
    margin: 0.5rem 0;
}
</style>
