<?php
$title = 'Contacto';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>📞 Contacto</h1>
        <p>¿Preguntas o sugerencias? Nos encantaría escucharte</p>
    </div>

    <div class="contact-grid">
        <div class="contact-form-section">
            <h2>Envíanos un Mensaje</h2>
            <form class="contact-form">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Asunto *</label>
                    <input type="text" name="subject" required>
                </div>

                <div class="form-group">
                    <label>Mensaje *</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Enviar Mensaje</button>
            </form>
        </div>

        <div class="contact-info-section">
            <h2>Información de Contacto</h2>

            <div class="contact-info-item">
                <h3>📧 Email</h3>
                <p><a href="mailto:support@streammarket.com">support@streammarket.com</a></p>
            </div>

            <div class="contact-info-item">
                <h3>🌐 Sitio Web</h3>
                <p>www.streammarket.com</p>
            </div>

            <div class="contact-info-item">
                <h3>🏢 Oficina</h3>
                <p>Colombia</p>
            </div>

            <div class="contact-info-item">
                <h3>⏰ Horario</h3>
                <p>Lunes - Viernes: 8:00 AM - 6:00 PM<br>Sábado - Domingo: 9:00 AM - 2:00 PM</p>
            </div>

            <div class="faq-box">
                <h3>❓ Preguntas Frecuentes</h3>
                <ul>
                    <li><a href="#faq">¿Cómo funciona el marketplace?</a></li>
                    <li><a href="#faq">¿Cómo vendo un producto?</a></li>
                    <li><a href="#faq">¿Cómo compro un producto?</a></li>
                    <li><a href="#faq">¿Cómo me pagan las comisiones?</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin: 2rem 0;
}

.contact-form-section, .contact-info-section {
    background: white;
    padding: 2rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.contact-form-section h2, .contact-info-section h2 {
    color: var(--primary);
    margin-top: 0;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group label {
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}

.form-group input, .form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-family: inherit;
}

.contact-info-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.contact-info-item:last-child {
    border: none;
}

.contact-info-item h3 {
    margin: 0 0 0.5rem;
    color: var(--primary);
}

.contact-info-item p {
    margin: 0;
    color: #4b5563;
}

.contact-info-item a {
    color: var(--primary);
    text-decoration: none;
}

.faq-box {
    background: #f0f7ff;
    padding: 1.5rem;
    border-radius: 4px;
    border-left: 4px solid var(--primary);
}

.faq-box h3 {
    margin-top: 0;
    color: var(--primary);
}

.faq-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.faq-box li {
    padding: 0.5rem 0;
}

.faq-box a {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.3s;
}

.faq-box a:hover {
    text-decoration: underline;
}

.btn-lg {
    padding: 1rem;
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}
</style>
