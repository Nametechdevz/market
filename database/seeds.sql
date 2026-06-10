-- Datos iniciales para StreamMarket

-- Usuario admin por defecto (password: admin123)
INSERT INTO users (name, email, password, role, status) VALUES
('Super Admin', 'admin@streammarket.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- Planes de suscripción
INSERT INTO plans (name, description, price, max_products, duration_days, features, status) VALUES
('Free',
 'Plan gratuito para comenzar',
 0.00,
 3,
 30,
 '["Sin marca de agua","Soporte por email","Hasta 3 productos"]',
 'active'),

('Basic',
 'Para vendedores que están comenzando',
 15000.00,
 30,
 30,
 '["Sin marca de agua","Soporte prioritario","Hasta 30 productos","Análitica básica"]',
 'active'),

('Pro',
 'Para vendedores establecidos',
 45000.00,
 100,
 30,
 '["Sin marca de agua","Productos destacados","Badge verificado","Hasta 100 productos","Análitica avanzada","Soporte 24/7"]',
 'active'),

('Elite',
 'Para vendedores con alto volumen',
 90000.00,
 -1,
 30,
 '["Todo lo del plan Pro","Productos ilimitados","Top en búsquedas","Marketing destacado","Manager dedicado","API access"]',
 'active');

-- Categorías por defecto
INSERT INTO categories (name, slug, icon, description) VALUES
('Cursos Online', 'cursos-online', '🎓', 'Cursos digitales de todas las áreas'),
('Streaming', 'streaming', '📺', 'Plataformas y suscripciones de streaming'),
('E-books', 'ebooks', '📚', 'Libros digitales y guías'),
('Software', 'software', '💻', 'Licencias y aplicaciones'),
('Diseño', 'diseno', '🎨', 'Templates, fuentes, recursos de diseño'),
('Música', 'musica', '🎵', 'Beats, samples, instrumentos virtuales'),
('Marketing', 'marketing', '📈', 'Recursos de marketing y publicidad'),
('Desarrollo', 'desarrollo', '⚡', 'Templates de código, plugins, scripts');
