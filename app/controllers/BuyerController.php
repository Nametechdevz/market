<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Order;
use App\Models\SellerPaymentMethod;
use App\Models\Message;
use App\Core\Auth;
use App\Core\Database;

class BuyerController
{
    private $productModel;
    private $categoryModel;
    private $reviewModel;
    private $orderModel;
    private $paymentMethodModel;
    private $messageModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->reviewModel = new Review();
        $this->orderModel = new Order();
        $this->paymentMethodModel = new SellerPaymentMethod();
        $this->messageModel = new Message();
    }

    // ==================== CATÁLOGO ====================
    public function marketplace()
    {
        $categories = $this->categoryModel->getAll();
        $search = trim($_GET['search'] ?? '');
        $category = intval($_GET['category'] ?? 0);

        $db = Database::getInstance()->getConnection();

        $sql = "SELECT p.*, c.name as category_name, c.icon,
                       u.name as seller_name,
                       AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
                FROM products p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.seller_id = u.id
                LEFT JOIN reviews r ON (SELECT o.id FROM orders o WHERE o.product_id = p.id AND r.order_id = o.id)
                WHERE p.status = 'active'";

        if ($search) {
            $search = $db->real_escape_string($search);
            $sql .= " AND (p.title LIKE '%$search%' OR p.description LIKE '%$search%')";
        }

        if ($category > 0) {
            $sql .= " AND p.category_id = $category";
        }

        $sql .= " GROUP BY p.id ORDER BY p.featured DESC, p.created_at DESC LIMIT 100";

        $result = $db->query($sql);
        $products = $result->fetch_all(MYSQLI_ASSOC);

        require __DIR__ . '/../views/marketplace/index.php';
    }

    public function productDetail($id)
    {
        $product = $this->productModel->findById($id);
        if (!$product || $product['status'] !== 'active') {
            http_response_code(404);
            exit('Producto no encontrado');
        }

        $db = Database::getInstance()->getConnection();
        $seller = $db->query("SELECT * FROM users WHERE id = {$product['seller_id']}")->fetch_assoc();
        $category = $db->query("SELECT * FROM categories WHERE id = {$product['category_id']}")->fetch_assoc();

        $ratingData = $this->reviewModel->getAvgRating($id);
        $reviews = $this->reviewModel->getByProduct($id);
        $paymentMethods = $this->paymentMethodModel->getBySeller($product['seller_id']);

        require __DIR__ . '/../views/marketplace/product_detail.php';
    }

    // ==================== CARRITO ====================
    public function addToCart($productId)
    {
        if (!Auth::check()) {
            Auth::redirect('/login');
        }

        $product = $this->productModel->findById($productId);
        if (!$product || $product['status'] !== 'active') {
            $_SESSION['errors'] = ['Producto no disponible'];
            Auth::redirect('/marketplace');
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'title' => $product['title'],
                'price' => $product['price'],
                'seller_id' => $product['seller_id'],
                'quantity' => 1
            ];
        }

        $_SESSION['success'] = 'Producto agregado al carrito';
        Auth::redirect('/cart');
    }

    public function viewCart()
    {
        if (!Auth::check()) {
            Auth::redirect('/login');
        }

        $cart = $_SESSION['cart'] ?? [];
        $db = Database::getInstance()->getConnection();

        foreach ($cart as $productId => &$item) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                $item['image'] = $product['image'];
                $seller = $db->query("SELECT name FROM users WHERE id = {$product['seller_id']}")->fetch_assoc();
                $item['seller_name'] = $seller['name'] ?? 'Vendedor';
            }
        }

        require __DIR__ . '/../views/marketplace/cart.php';
    }

    public function removeFromCart($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['success'] = 'Producto removido del carrito';
        }
        Auth::redirect('/cart');
    }

    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Auth::redirect('/cart');
        }

        foreach ($_POST['quantity'] ?? [] as $productId => $quantity) {
            $quantity = intval($quantity);
            if ($quantity > 0 && isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
            } elseif ($quantity <= 0 && isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
        }

        Auth::redirect('/cart');
    }

    // ==================== COMPRA ====================
    public function checkout()
    {
        if (!Auth::check()) {
            Auth::redirect('/login');
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['errors'] = ['El carrito está vacío'];
            Auth::redirect('/cart');
        }

        $db = Database::getInstance()->getConnection();
        $cartDetails = [];

        foreach ($cart as $productId => $item) {
            $product = $this->productModel->findById($productId);
            $paymentMethods = $this->paymentMethodModel->getBySeller($product['seller_id']);

            $cartDetails[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'subtotal' => $product['price'] * $item['quantity'],
                'payment_methods' => $paymentMethods
            ];
        }

        require __DIR__ . '/../views/marketplace/checkout.php';
    }

    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::check()) {
            Auth::redirect('/marketplace');
        }

        $buyerId = Auth::userId();
        $cart = $_SESSION['cart'] ?? [];

        foreach ($cart as $productId => $item) {
            $product = $this->productModel->findById($productId);
            $paymentMethodId = intval($_POST["payment_method_$productId"] ?? 0);

            // Procesar comprobante
            $paymentProof = null;
            if (!empty($_FILES["proof_$productId"]['name'])) {
                $paymentProof = $this->uploadPaymentProof($_FILES["proof_$productId"]);
            }

            $orderData = [
                'buyer_id' => $buyerId,
                'product_id' => $productId,
                'seller_id' => $product['seller_id'],
                'payment_method_id' => $paymentMethodId ?: null,
                'amount' => $product['price'] * $item['quantity'],
                'payment_proof' => $paymentProof,
                'buyer_contact' => trim($_POST['contact']),
            ];

            $this->orderModel->create($orderData);
        }

        unset($_SESSION['cart']);
        $_SESSION['success'] = 'Compra realizada. El vendedor confirmará el pago pronto.';
        Auth::redirect('/buyer/purchases');
    }

    // ==================== COMPRADOR ====================
    public function myPurchases()
    {
        if (!Auth::check()) {
            Auth::redirect('/login');
        }

        $db = Database::getInstance()->getConnection();
        $buyerId = Auth::userId();

        $orders = $db->query(
            "SELECT o.*, p.title, p.image, u.name as seller_name
             FROM orders o
             JOIN products p ON o.product_id = p.id
             JOIN users u ON o.seller_id = u.id
             WHERE o.buyer_id = $buyerId
             ORDER BY o.created_at DESC"
        )->fetch_all(MYSQLI_ASSOC);

        require __DIR__ . '/../views/buyer/purchases.php';
    }

    public function myReviews()
    {
        if (!Auth::check()) {
            Auth::redirect('/login');
        }

        $reviews = $this->reviewModel->getByBuyer(Auth::userId());
        require __DIR__ . '/../views/buyer/reviews.php';
    }

    public function orderDetail($id)
    {
        if (!Auth::check()) {
            Auth::redirect('/login');
        }

        $db = Database::getInstance()->getConnection();
        $order = $db->query(
            "SELECT o.*, p.title, p.image, p.delivery_info, u.name as seller_name, u.email
             FROM orders o
             JOIN products p ON o.product_id = p.id
             JOIN users u ON o.seller_id = u.id
             WHERE o.id = $id AND o.buyer_id = " . Auth::userId()
        )->fetch_assoc();

        if (!$order) {
            http_response_code(404);
            exit('Orden no encontrada');
        }

        $messages = $this->messageModel->getByOrder($id);
        $this->messageModel->markAsRead($id, Auth::userId());

        $existingReview = $db->query(
            "SELECT * FROM reviews WHERE order_id = $id"
        )->fetch_assoc();

        require __DIR__ . '/../views/buyer/order_detail.php';
    }

    public function submitReview($orderId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::check()) {
            Auth::redirect('/buyer/purchases');
        }

        $db = Database::getInstance()->getConnection();
        $order = $db->query(
            "SELECT * FROM orders WHERE id = $orderId AND buyer_id = " . Auth::userId()
        )->fetch_assoc();

        if (!$order) {
            http_response_code(404);
            exit('Orden no encontrada');
        }

        $this->reviewModel->create([
            'order_id' => $orderId,
            'reviewer_id' => Auth::userId(),
            'rating' => intval($_POST['rating']),
            'comment' => trim($_POST['comment'])
        ]);

        $_SESSION['success'] = 'Reseña enviada';
        Auth::redirect('/buyer/order/' . $orderId);
    }

    public function sendMessage($orderId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::check()) {
            Auth::redirect('/marketplace');
        }

        $db = Database::getInstance()->getConnection();
        $order = $db->query(
            "SELECT * FROM orders WHERE id = $orderId AND buyer_id = " . Auth::userId()
        )->fetch_assoc();

        if (!$order) {
            http_response_code(404);
            exit('Orden no encontrada');
        }

        $this->messageModel->send(
            $orderId,
            Auth::userId(),
            $order['seller_id'],
            trim($_POST['message'])
        );

        Auth::redirect('/buyer/order/' . $orderId);
    }

    // ==================== UTILIDADES ====================
    private function uploadPaymentProof($file)
    {
        $maxSize = 3 * 1024 * 1024;
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if ($file['size'] > $maxSize || !in_array($file['type'], $allowed)) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/assets/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $filename = uniqid('proof_') . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/assets/uploads/' . $filename;
        }

        return null;
    }
}
