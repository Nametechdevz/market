<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SellerPaymentMethod;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Plan;
use App\Core\Auth;

class SellerController
{
    private $productModel;
    private $categoryModel;
    private $paymentMethodModel;
    private $orderModel;
    private $subscriptionModel;
    private $planModel;
    private $sellerId;

    public function __construct()
    {
        if (!Auth::hasRole('seller')) {
            http_response_code(403);
            exit('Acceso denegado');
        }

        $this->sellerId = Auth::userId();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->paymentMethodModel = new SellerPaymentMethod();
        $this->orderModel = new Order();
        $this->subscriptionModel = new Subscription();
        $this->planModel = new Plan();
    }

    public function dashboard()
    {
        $stats = $this->productModel->getSellerStats($this->sellerId);
        $orderStats = $this->orderModel->getStats($this->sellerId);
        $subscription = $this->subscriptionModel->getActiveSubscription($this->sellerId);
        $paymentMethods = $this->paymentMethodModel->getBySeller($this->sellerId);

        require __DIR__ . '/../views/seller/dashboard.php';
    }

    // ==================== PRODUCTOS ====================
    public function products()
    {
        $products = $this->productModel->getBySeller($this->sellerId);
        $subscription = $this->subscriptionModel->getActiveSubscription($this->sellerId);
        require __DIR__ . '/../views/seller/products.php';
    }

    public function createProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $categories = $this->categoryModel->getAll();
            require __DIR__ . '/../views/seller/product_form.php';
            return;
        }

        // Validar límite de productos
        if (!$this->productModel->canAddProduct($this->sellerId, null)) {
            $_SESSION['errors'] = ['Has alcanzado el límite de productos para tu plan'];
            Auth::redirect('/seller/products');
        }

        // Procesar imagen
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
            if (!$image) {
                $_SESSION['errors'] = ['Error al subir la imagen'];
                Auth::redirect('/seller/products/create');
            }
        }

        $data = [
            'seller_id' => $this->sellerId,
            'category_id' => intval($_POST['category_id']),
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'price' => floatval($_POST['price']),
            'type' => trim($_POST['type']),
            'image' => $image,
            'stock' => trim($_POST['stock'] ?? '-1'),
            'delivery_info' => trim($_POST['delivery_info']),
            'status' => $_POST['status'] ?? 'draft',
        ];

        if (empty($data['title']) || empty($data['category_id'])) {
            $_SESSION['errors'] = ['Título y categoría son requeridos'];
            Auth::redirect('/seller/products/create');
        }

        if ($this->productModel->create($data)) {
            $_SESSION['success'] = 'Producto creado exitosamente';
            Auth::redirect('/seller/products');
        } else {
            $_SESSION['errors'] = ['Error al crear el producto'];
            Auth::redirect('/seller/products/create');
        }
    }

    public function editProduct($id)
    {
        $product = $this->productModel->findById($id);
        if (!$product || $product['seller_id'] !== $this->sellerId) {
            http_response_code(404);
            exit('Producto no encontrado');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = $product['image'];
            if (!empty($_FILES['image']['name'])) {
                $newImage = $this->uploadImage($_FILES['image']);
                if ($newImage) {
                    $image = $newImage;
                }
            }

            $data = [
                'category_id' => intval($_POST['category_id']),
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'price' => floatval($_POST['price']),
                'type' => trim($_POST['type']),
                'stock' => trim($_POST['stock'] ?? '-1'),
                'delivery_info' => trim($_POST['delivery_info']),
                'status' => $_POST['status'],
            ];

            if ($this->productModel->update($id, $this->sellerId, $data)) {
                $_SESSION['success'] = 'Producto actualizado';
                Auth::redirect('/seller/products');
            }
        }

        $categories = $this->categoryModel->getAll();
        require __DIR__ . '/../views/seller/product_form.php';
    }

    public function deleteProduct($id)
    {
        if ($this->productModel->delete($id, $this->sellerId)) {
            $_SESSION['success'] = 'Producto eliminado';
        }
        Auth::redirect('/seller/products');
    }

    public function toggleFeatured($id)
    {
        $this->productModel->toggleFeatured($id, $this->sellerId);
        Auth::redirect('/seller/products');
    }

    // ==================== MÉTODOS DE PAGO ====================
    public function paymentMethods()
    {
        $methods = $this->paymentMethodModel->getBySeller($this->sellerId);
        require __DIR__ . '/../views/seller/payment_methods.php';
    }

    public function createPaymentMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/seller/payment_method_form.php';
            return;
        }

        $qrImage = null;
        if (!empty($_FILES['qr_image']['name'])) {
            $qrImage = $this->uploadImage($_FILES['qr_image']);
        }

        $data = [
            'seller_id' => $this->sellerId,
            'method_type' => trim($_POST['method_type']),
            'account_number' => trim($_POST['account_number'] ?? ''),
            'account_holder' => trim($_POST['account_holder']),
            'identification_number' => trim($_POST['identification_number']),
            'qr_image' => $qrImage,
        ];

        if (empty($data['method_type']) || empty($data['account_holder'])) {
            $_SESSION['errors'] = ['Todos los campos son requeridos'];
            Auth::redirect('/seller/payment-methods/create');
        }

        if ($this->paymentMethodModel->create($data)) {
            $_SESSION['success'] = 'Método de pago agregado';
            Auth::redirect('/seller/payment-methods');
        }
    }

    public function editPaymentMethod($id)
    {
        $method = $this->paymentMethodModel->findById($id, $this->sellerId);
        if (!$method) {
            http_response_code(404);
            exit('Método no encontrado');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qrImage = $method['qr_image'];
            if (!empty($_FILES['qr_image']['name'])) {
                $newQR = $this->uploadImage($_FILES['qr_image']);
                if ($newQR) $qrImage = $newQR;
            }

            $data = [
                'account_number' => trim($_POST['account_number']),
                'account_holder' => trim($_POST['account_holder']),
                'identification_number' => trim($_POST['identification_number']),
                'qr_image' => $qrImage,
                'is_primary' => intval($_POST['is_primary'] ?? 0),
            ];

            if ($this->paymentMethodModel->update($id, $this->sellerId, $data)) {
                $_SESSION['success'] = 'Método actualizado';
                Auth::redirect('/seller/payment-methods');
            }
        }

        require __DIR__ . '/../views/seller/payment_method_form.php';
    }

    public function deletePaymentMethod($id)
    {
        if ($this->paymentMethodModel->delete($id, $this->sellerId)) {
            $_SESSION['success'] = 'Método eliminado';
        }
        Auth::redirect('/seller/payment-methods');
    }

    public function setPrimaryPaymentMethod($id)
    {
        $this->paymentMethodModel->setPrimary($id, $this->sellerId);
        Auth::redirect('/seller/payment-methods');
    }

    // ==================== ÓRDENES ====================
    public function orders()
    {
        $status = $_GET['status'] ?? null;
        $orders = $this->orderModel->getBySeller($this->sellerId, $status);
        $stats = $this->orderModel->getStats($this->sellerId);
        require __DIR__ . '/../views/seller/orders.php';
    }

    public function viewOrder($id)
    {
        $order = $this->orderModel->findById($id, $this->sellerId);
        if (!$order) {
            http_response_code(404);
            exit('Orden no encontrada');
        }
        require __DIR__ . '/../views/seller/order_detail.php';
    }

    public function updateOrderStatus($id)
    {
        $status = trim($_POST['status'] ?? '');
        $note = trim($_POST['note'] ?? '');

        if ($this->orderModel->updateStatus($id, $this->sellerId, $status)) {
            if ($note) {
                $this->orderModel->addNote($id, $this->sellerId, $note);
            }
            $_SESSION['success'] = 'Orden actualizada';
        }

        Auth::redirect('/seller/orders/' . $id);
    }

    // ==================== PLANES ====================
    public function upgradePlan()
    {
        $plans = $this->planModel->getAll(true);
        $currentSubscription = $this->subscriptionModel->getActiveSubscription($this->sellerId);
        require __DIR__ . '/../views/seller/upgrade_plan.php';
    }

    public function buyPlan()
    {
        $planId = intval($_POST['plan_id'] ?? 0);
        $plan = $this->planModel->findById($planId);

        if (!$plan || !in_array($planId, [1, 2, 3, 4])) {
            $_SESSION['errors'] = ['Plan inválido'];
            Auth::redirect('/seller/upgrade-plan');
        }

        // Aquí normalmente iría integración con pasarela de pago
        // Por ahora, simulamos que se asigna directamente
        if ($this->subscriptionModel->create($this->sellerId, $planId)) {
            $_SESSION['success'] = 'Suscripción actualizada correctamente';
            Auth::redirect('/seller/dashboard');
        } else {
            $_SESSION['errors'] = ['Error al actualizar la suscripción'];
            Auth::redirect('/seller/upgrade-plan');
        }
    }

    // ==================== UTILIDADES ====================
    private function uploadImage($file)
    {
        $maxSize = 5 * 1024 * 1024; // 5MB
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if ($file['size'] > $maxSize) {
            return false;
        }

        if (!in_array($file['type'], $allowed)) {
            return false;
        }

        $uploadDir = __DIR__ . '/../../public/assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid('product_') . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/assets/uploads/' . $filename;
        }

        return false;
    }
}
