<?php
$title = 'Marketplace';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <div class="marketplace-header">
        <h1>🛍️ Marketplace</h1>
        <p>Explora miles de cursos, streaming y productos digitales</p>
    </div>

    <div class="marketplace-filters">
        <form method="GET" class="search-form">
            <input type="search" name="search" placeholder="Buscar productos..."
                   value="<?php echo htmlspecialchars($search); ?>" class="search-input">

            <select name="category" class="filter-select" onchange="this.form.submit()">
                <option value="">📂 Todas las categorías</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                    <?php echo $cat['icon']; ?> <?php echo htmlspecialchars($cat['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if ($search || $category): ?>
                <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-secondary">Limpiar</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <a href="<?php echo APP_URL; ?>/product/<?php echo $product['id']; ?>" class="product-card">
            <div class="product-image">
                <?php if ($product['image']): ?>
                    <img src="<?php echo APP_URL . htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                <?php else: ?>
                    <div class="placeholder">📦</div>
                <?php endif; ?>
                <?php if ($product['featured']): ?>
                    <span class="badge-featured">⭐ Destacado</span>
                <?php endif; ?>
            </div>

            <div class="product-info">
                <h3><?php echo htmlspecialchars($product['title']); ?></h3>

                <p class="product-seller">
                    Por <strong><?php echo htmlspecialchars($product['seller_name']); ?></strong>
                </p>

                <div class="product-rating">
                    <?php
                    $avg = round($product['avg_rating'] ?? 0);
                    for ($i = 0; $i < 5; $i++) {
                        echo $i < $avg ? '⭐' : '☆';
                    }
                    ?>
                    <span>(<?php echo $product['review_count'] ?? 0; ?>)</span>
                </div>

                <p class="product-price">$<?php echo number_format($product['price'], 0, ',', '.'); ?></p>

                <p class="product-category">
                    <?php echo $product['icon']; ?> <?php echo htmlspecialchars($product['category_name']); ?>
                </p>
            </div>
        </a>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
        <div class="empty-state" style="grid-column: 1/-1; padding: 3rem;">
            <p>No se encontraron productos.</p>
            <a href="<?php echo APP_URL; ?>/marketplace" class="btn btn-primary">Ver todos</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.marketplace-header {
    text-align: center;
    padding: 2rem 0;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: var(--radius);
    margin-bottom: 2rem;
}

.marketplace-header h1 {
    margin: 0;
    font-size: 2rem;
}

.marketplace-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.search-form {
    display: flex;
    gap: 0.5rem;
    flex: 1;
    min-width: 300px;
}

.search-input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.filter-select {
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-8px);
}

.product-image {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
    background: var(--light);
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image .placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 3rem;
}

.badge-featured {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(255, 193, 7, 0.9);
    color: black;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-info {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-info h3 {
    margin: 0 0 0.5rem;
    font-size: 1rem;
    line-height: 1.3;
    color: var(--dark);
}

.product-seller {
    font-size: 0.85rem;
    color: #6b7280;
    margin: 0.25rem 0;
}

.product-rating {
    font-size: 0.9rem;
    color: #f59e0b;
    margin: 0.5rem 0;
}

.product-rating span {
    color: #6b7280;
    font-size: 0.8rem;
    margin-left: 0.25rem;
}

.product-price {
    font-size: 1.25rem;
    font-weight: bold;
    color: var(--primary);
    margin: 0.5rem 0;
}

.product-category {
    font-size: 0.8rem;
    color: #6b7280;
    margin: 0;
    margin-top: auto;
}

@media (max-width: 768px) {
    .marketplace-filters {
        flex-direction: column;
    }

    .search-form {
        flex-direction: column;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}
</style>

