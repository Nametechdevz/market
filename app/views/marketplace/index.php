<?php
$title = 'Marketplace';
$content = __FILE__;
require __DIR__ . '/../layout.php';
?>

<div class="container">
    <h1>Marketplace</h1>
    <p>Explora nuestros productos disponibles</p>

    <div class="marketplace-filters">
        <input type="search" placeholder="Buscar productos..." class="search-input">
        <select class="filter-select">
            <option>Todas las categorías</option>
            <option>Cursos</option>
            <option>Streaming</option>
            <option>E-books</option>
        </select>
    </div>

    <div class="products-grid" id="productsGrid">
        <p>Cargando productos...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('<?php echo APP_URL; ?>/api/products')
        .then(res => res.json())
        .then(data => {
            console.log('Productos:', data);
            // TODO: Renderizar productos
        });
});
</script>
