<?php
$title = 'Mis Reseñas';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="page-header">
        <h1>⭐ Mis Reseñas</h1>
    </div>

    <div class="tabs">
        <a href="<?php echo APP_URL; ?>/buyer/purchases" class="tab">Compras</a>
        <a href="<?php echo APP_URL; ?>/buyer/reviews" class="tab active">Mis Reseñas</a>
    </div>

    <div class="reviews-container">
        <?php if (empty($reviews)): ?>
        <div class="empty-state">
            <p>No has dejado reseñas aún</p>
            <a href="<?php echo APP_URL; ?>/buyer/purchases" class="btn btn-primary">Ver mis compras</a>
        </div>
        <?php else: ?>

        <?php foreach ($reviews as $review): ?>
        <div class="review-card-full">
            <div class="review-header">
                <div>
                    <h3><?php echo htmlspecialchars($review['product_title']); ?></h3>
                    <p class="review-date">Reseñado: <?php echo date('d/m/Y', strtotime($review['created_at'])); ?></p>
                </div>
                <div class="review-rating-large">
                    <?php for ($i = 0; $i < 5; $i++) {
                        echo $i < $review['rating'] ? '⭐' : '☆';
                    } ?>
                </div>
            </div>

            <p class="review-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>

            <div class="review-product">
                <?php if ($review['image']): ?>
                    <img src="<?php echo APP_URL . htmlspecialchars($review['image']); ?>" alt="">
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<style>
.reviews-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin: 2rem 0;
}

.review-card-full {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
}

.review-header h3 {
    margin: 0 0 0.5rem;
}

.review-date {
    margin: 0;
    font-size: 0.85rem;
    color: #6b7280;
}

.review-rating-large {
    font-size: 1.5rem;
    color: #f59e0b;
}

.review-text {
    color: #4b5563;
    line-height: 1.6;
    margin: 1rem 0;
}

.review-product {
    margin-top: 1rem;
}

.review-product img {
    max-width: 150px;
    border-radius: 4px;
}
</style>
