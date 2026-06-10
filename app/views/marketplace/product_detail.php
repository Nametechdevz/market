<?php
$title = htmlspecialchars($product['title']);
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-secondary" style="margin-bottom: 1.5rem;">← Volver al Marketplace</a>

    <div class="product-detail-grid">
        <div class="product-detail-left">
            <div class="product-detail-image">
                <?php if ($product['image']): ?>
                    <img src="<?php echo APP_URL . htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                <?php else: ?>
                    <div class="placeholder-large">📦</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-detail-right">
            <h1><?php echo htmlspecialchars($product['title']); ?></h1>

            <div class="product-meta">
                <span class="category-badge">
                    <?php echo $category['icon']; ?> <?php echo htmlspecialchars($category['name']); ?>
                </span>
                <span class="type-badge">
                    <?php echo match($product['type']) {
                        'course' => '📚 Curso',
                        'streaming' => '📺 Streaming',
                        'ebook' => '📖 E-book',
                        'software' => '💻 Software',
                        default => '📦 Producto'
                    }; ?>
                </span>
            </div>

            <div class="product-rating-large">
                <?php
                $avg = round($ratingData['avg_rating'] ?? 0);
                for ($i = 0; $i < 5; $i++) {
                    echo $i < $avg ? '⭐' : '☆';
                }
                ?>
                <span>(<?php echo $ratingData['count'] ?? 0; ?> reseñas)</span>
            </div>

            <div class="product-seller-card">
                <h3>Vendedor</h3>
                <p><strong><?php echo htmlspecialchars($seller['name']); ?></strong></p>
                <p><?php echo htmlspecialchars($seller['email']); ?></p>
            </div>

            <div class="product-price-section">
                <p class="price">$<?php echo number_format($product['price'], 0, ',', '.'); ?></p>

                <?php if (Auth::check()): ?>
                    <form method="POST" action="<?php echo APP_URL; ?>/add-to-cart/<?php echo $product['id']; ?>">
                        <button type="submit" class="btn btn-primary btn-lg">🛒 Agregar al Carrito</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo APP_URL; ?>/login" class="btn btn-primary btn-lg">Inicia sesión para comprar</a>
                <?php endif; ?>
            </div>

            <div class="product-description">
                <h3>Descripción</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>

            <div class="product-info-box">
                <h3>Información de Entrega</h3>
                <p><?php echo nl2br(htmlspecialchars($product['delivery_info'])); ?></p>
            </div>

            <div class="payment-methods">
                <h3>Métodos de Pago</h3>
                <p>Luego de comprar, podrás pagar mediante:</p>
                <div class="methods-list">
                    <?php foreach ($paymentMethods as $method): ?>
                    <div class="method-item">
                        <span class="method-icon">
                            <?php echo match($method['method_type']) {
                                'nequi' => '📱',
                                'bancolombia' => '🏦',
                                'bre-b' => '💳',
                                'daviplata' => '📲',
                                'ps_bank' => '💰',
                                default => '💵'
                            }; ?>
                        </span>
                        <span><?php echo htmlspecialchars($method['account_holder']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Reseñas -->
    <section class="reviews-section">
        <h2>Reseñas (<?php echo count($reviews); ?>)</h2>

        <?php if (empty($reviews)): ?>
        <p class="empty-text">Sin reseñas aún. ¡Sé el primero en dejar una!</p>
        <?php else: ?>
        <div class="reviews-list">
            <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <div class="review-header">
                    <strong><?php echo htmlspecialchars($review['name'] ?? 'Anónimo'); ?></strong>
                    <div class="review-rating">
                        <?php for ($i = 0; $i < 5; $i++) {
                            echo $i < $review['rating'] ? '⭐' : '☆';
                        } ?>
                    </div>
                </div>
                <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                <small><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
</div>

<style>
.product-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.product-detail-image {
    width: 100%;
    aspect-ratio: 1;
    border-radius: var(--radius);
    overflow: hidden;
    background: var(--light);
}

.product-detail-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-large {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 4rem;
}

.product-meta {
    display: flex;
    gap: 0.75rem;
    margin: 1rem 0;
}

.category-badge, .type-badge {
    display: inline-block;
    padding: 0.4rem 0.75rem;
    background: var(--light);
    border-radius: 999px;
    font-size: 0.85rem;
    color: var(--dark);
}

.product-rating-large {
    font-size: 1.25rem;
    color: #f59e0b;
    margin: 1rem 0;
}

.product-seller-card {
    background: #f0f7ff;
    padding: 1.5rem;
    border-radius: var(--radius);
    margin: 1.5rem 0;
    border-left: 4px solid var(--primary);
}

.product-seller-card p {
    margin: 0.25rem 0;
}

.product-price-section {
    margin: 2rem 0;
}

.price {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--primary);
    margin: 0.5rem 0;
}

.btn-lg {
    width: 100%;
    padding: 1rem;
    font-size: 1.1rem;
}

.product-description, .product-info-box {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    margin: 1.5rem 0;
    border: 1px solid var(--border);
}

.payment-methods {
    background: #fef3c7;
    padding: 1.5rem;
    border-radius: var(--radius);
    margin: 1.5rem 0;
}

.payment-methods h3 {
    margin-top: 0;
    color: #92400e;
}

.methods-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.method-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: white;
    border-radius: 4px;
}

.method-icon {
    font-size: 1.5rem;
}

.reviews-section {
    background: white;
    padding: 2rem;
    border-radius: var(--radius);
    margin: 3rem 0;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.review-card {
    padding: 1.5rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.review-rating {
    color: #f59e0b;
    font-size: 0.9rem;
}

.review-card p {
    margin: 0.75rem 0;
    color: #4b5563;
}

.review-card small {
    color: #9ca3af;
}

.empty-text {
    text-align: center;
    color: #6b7280;
    padding: 2rem;
}

@media (max-width: 768px) {
    .product-detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>
