<?php
$title = 'Términos de Servicio';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📋 Términos de Servicio</h1>
    </div>

    <div class="content-page">
        <h2>1. Aceptación de Términos</h2>
        <p>Al utilizar StreamMarket, aceptas estos términos de servicio en su totalidad.</p>

        <h2>2. Descripción del Servicio</h2>
        <p>StreamMarket es una plataforma que conecta vendedores y compradores de productos digitales. Somos un intermediario tecnológico, no una tienda directa.</p>

        <h2>3. Responsabilidades del Vendedor</h2>
        <ul>
            <li>Entregar el producto o servicio acordado</li>
            <li>Respetar derechos de autor y propiedad intelectual</li>
            <li>Proporcionar información legal y clara</li>
            <li>Garantizar que el contenido es legal en tu jurisdicción</li>
        </ul>

        <h2>4. Responsabilidades del Comprador</h2>
        <ul>
            <li>Pagar según lo acordado</li>
            <li>No redistribuir el contenido comprado</li>
            <li>Usar los productos de forma legal y ética</li>
        </ul>

        <h2>5. Responsabilidad de StreamMarket</h2>
        <p>StreamMarket NO es responsable de:</p>
        <ul>
            <li>La calidad del producto entregado</li>
            <li>La entrega del producto (es responsabilidad del vendedor)</li>
            <li>Disputas entre comprador y vendedor</li>
            <li>Infracciones de derechos de autor</li>
        </ul>

        <h2>6. Pago y Suscripciones</h2>
        <p>Las suscripciones se renuevan automáticamente. Puedes cancelar en cualquier momento. No hay reembolsos por suscripciones ya activas.</p>

        <h2>7. Terminación de Cuenta</h2>
        <p>Podemos suspender o terminar tu cuenta por:</p>
        <ul>
            <li>Violación de estos términos</li>
            <li>Contenido ilegal</li>
            <li>Fraude o actividad maliciosa</li>
        </ul>

        <h2>8. Limitación de Responsabilidad</h2>
        <p>En la máxima medida permitida por la ley, StreamMarket no será responsable por daños indirectos, incidentales o especiales.</p>

        <h2>9. Cambios a los Términos</h2>
        <p>Podemos modificar estos términos en cualquier momento. El uso continuado de la plataforma implica aceptación.</p>

        <h2>10. Contacto</h2>
        <p>Para preguntas sobre estos términos, contacta a: support@streammarket.com</p>
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
