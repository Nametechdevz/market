<?php
// Autoload de clases
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

require __DIR__ . '/../config/database.php';

use App\Core\Router;
use App\Core\Auth;

session_start();

$router = new Router();

// ==================== Rutas Públicas ====================
$router->get('/', function() {
    require __DIR__ . '/../app/views/home.php';
});

$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');

$router->get('/logout', 'AuthController@logout');

// ==================== Rutas Marketplace ====================
$router->get('/marketplace', 'BuyerController@marketplace');
$router->get('/product/{id}', 'BuyerController@productDetail');

// Carrito
$router->get('/cart', 'BuyerController@viewCart');
$router->post('/add-to-cart/{productId}', 'BuyerController@addToCart');
$router->get('/remove-from-cart/{productId}', 'BuyerController@removeFromCart');
$router->post('/update-cart', 'BuyerController@updateCart');

// Checkout
$router->get('/checkout', 'BuyerController@checkout');
$router->post('/process-payment', 'BuyerController@processPayment');

// Buyer Dashboard
$router->get('/buyer/purchases', 'BuyerController@myPurchases');
$router->get('/buyer/reviews', 'BuyerController@myReviews');
$router->get('/buyer/order/{id}', 'BuyerController@orderDetail');
$router->post('/buyer/order/{id}/review', 'BuyerController@submitReview');
$router->post('/buyer/order/{id}/message', 'BuyerController@sendMessage');

// ==================== Dashboard Redirect ====================
$router->get('/dashboard', function() {
    if (!Auth::check()) Auth::redirect('/login');
    $role = Auth::role();
    if ($role === 'admin') Auth::redirect('/admin/dashboard');
    elseif ($role === 'seller') Auth::redirect('/seller/dashboard');
    else Auth::redirect('/marketplace');
});

// ==================== Rutas Admin ====================
$router->get('/admin/dashboard', 'AdminController@dashboard');

// Usuarios
$router->get('/admin/users', 'AdminController@users');
$router->post('/admin/users/toggle-status', 'AdminController@toggleUserStatus');

// Planes
$router->get('/admin/plans', 'AdminController@plans');
$router->get('/admin/plans/create', 'AdminController@createPlan');
$router->post('/admin/plans/create', 'AdminController@createPlan');
$router->get('/admin/plans/{id}/edit', 'AdminController@editPlan');
$router->post('/admin/plans/{id}/edit', 'AdminController@editPlan');
$router->post('/admin/plans/{id}/delete', 'AdminController@deletePlan');

// Categorías
$router->get('/admin/categories', 'AdminController@categories');
$router->get('/admin/categories/create', 'AdminController@createCategory');
$router->post('/admin/categories/create', 'AdminController@createCategory');
$router->get('/admin/categories/{id}/edit', 'AdminController@editCategory');
$router->post('/admin/categories/{id}/edit', 'AdminController@editCategory');
$router->post('/admin/categories/{id}/delete', 'AdminController@deleteCategory');

// Suscripciones
$router->get('/admin/subscriptions', 'AdminController@subscriptions');
$router->get('/admin/subscriptions/assign', 'AdminController@assignSubscription');
$router->post('/admin/subscriptions/assign', 'AdminController@assignSubscription');
$router->post('/admin/subscriptions/{id}/cancel', 'AdminController@cancelSubscription');

// ==================== Rutas Seller ====================
$router->get('/seller/dashboard', 'SellerController@dashboard');

// Productos
$router->get('/seller/products', 'SellerController@products');
$router->get('/seller/products/create', 'SellerController@createProduct');
$router->post('/seller/products/create', 'SellerController@createProduct');
$router->get('/seller/products/{id}/edit', 'SellerController@editProduct');
$router->post('/seller/products/{id}/edit', 'SellerController@editProduct');
$router->post('/seller/products/{id}/delete', 'SellerController@deleteProduct');
$router->post('/seller/products/{id}/toggle-featured', 'SellerController@toggleFeatured');

// Métodos de Pago
$router->get('/seller/payment-methods', 'SellerController@paymentMethods');
$router->get('/seller/payment-methods/create', 'SellerController@createPaymentMethod');
$router->post('/seller/payment-methods/create', 'SellerController@createPaymentMethod');
$router->get('/seller/payment-methods/{id}/edit', 'SellerController@editPaymentMethod');
$router->post('/seller/payment-methods/{id}/edit', 'SellerController@editPaymentMethod');
$router->post('/seller/payment-methods/{id}/delete', 'SellerController@deletePaymentMethod');
$router->post('/seller/payment-methods/{id}/set-primary', 'SellerController@setPrimaryPaymentMethod');

// Órdenes
$router->get('/seller/orders', 'SellerController@orders');
$router->get('/seller/orders/{id}', 'SellerController@viewOrder');
$router->post('/seller/orders/{id}/update', 'SellerController@updateOrderStatus');

// Planes
$router->get('/seller/upgrade-plan', 'SellerController@upgradePlan');
$router->post('/seller/buy-plan', 'SellerController@buyPlan');

// ==================== Páginas Estáticas ====================
$router->get('/terms', function() {
    require __DIR__ . '/../app/views/pages/terms.php';
});

$router->get('/privacy', function() {
    require __DIR__ . '/../app/views/pages/privacy.php';
});

$router->get('/contact', function() {
    require __DIR__ . '/../app/views/pages/contact.php';
});

// Dispatch
$router->dispatch();
