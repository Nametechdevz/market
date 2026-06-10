<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\Category;
use App\Models\Subscription;
use App\Core\Auth;
use App\Core\Database;

class AdminController
{
    private $userModel;
    private $planModel;
    private $categoryModel;
    private $subscriptionModel;

    public function __construct()
    {
        if (!Auth::hasRole('admin')) {
            http_response_code(403);
            exit('Acceso denegado');
        }

        $this->userModel = new User();
        $this->planModel = new Plan();
        $this->categoryModel = new Category();
        $this->subscriptionModel = new Subscription();
    }

    public function dashboard()
    {
        $db = Database::getInstance()->getConnection();

        $stats = [
            'total_users' => $db->query("SELECT COUNT(*) as t FROM users")->fetch_assoc()['t'],
            'total_sellers' => $db->query("SELECT COUNT(*) as t FROM users WHERE role = 'seller'")->fetch_assoc()['t'],
            'total_buyers' => $db->query("SELECT COUNT(*) as t FROM users WHERE role = 'buyer'")->fetch_assoc()['t'],
            'total_products' => $db->query("SELECT COUNT(*) as t FROM products WHERE status = 'active'")->fetch_assoc()['t'],
            'total_orders' => $db->query("SELECT COUNT(*) as t FROM orders")->fetch_assoc()['t'],
            'orders_today' => $db->query("SELECT COUNT(*) as t FROM orders WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['t'],
            'revenue' => $this->subscriptionModel->getTotalRevenue(),
            'active_subscriptions' => $this->subscriptionModel->getStats()['active'],
        ];

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    // ==================== USUARIOS ====================
    public function users()
    {
        $db = Database::getInstance()->getConnection();
        $users = $db->query(
            "SELECT u.*, p.name as plan_name, s.ends_at as subscription_ends
             FROM users u
             LEFT JOIN subscriptions s ON u.id = s.user_id AND s.status = 'active'
             LEFT JOIN plans p ON s.plan_id = p.id
             ORDER BY u.created_at DESC"
        )->fetch_all(MYSQLI_ASSOC);

        require __DIR__ . '/../views/admin/users.php';
    }

    public function toggleUserStatus()
    {
        $userId = $_POST['user_id'] ?? null;
        $status = $_POST['status'] ?? 'active';

        if (!$userId) {
            $_SESSION['errors'] = ['Usuario inválido'];
            Auth::redirect('/admin/users');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $userId);
        $stmt->execute();

        $_SESSION['success'] = 'Usuario actualizado';
        Auth::redirect('/admin/users');
    }

    // ==================== PLANES ====================
    public function plans()
    {
        $plans = $this->planModel->getAll();
        foreach ($plans as &$plan) {
            $plan['subscribers'] = $this->planModel->getActiveSubscribersCount($plan['id']);
        }
        require __DIR__ . '/../views/admin/plans.php';
    }

    public function createPlan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/admin/plan_form.php';
            return;
        }

        $features = array_filter(explode("\n", $_POST['features'] ?? ''));
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0),
            'max_products' => intval($_POST['max_products'] ?? 10),
            'duration_days' => intval($_POST['duration_days'] ?? 30),
            'features' => array_map('trim', $features),
            'status' => $_POST['status'] ?? 'active',
        ];

        if (empty($data['name'])) {
            $_SESSION['errors'] = ['El nombre es requerido'];
            Auth::redirect('/admin/plans/create');
        }

        if ($this->planModel->create($data)) {
            $_SESSION['success'] = 'Plan creado exitosamente';
            Auth::redirect('/admin/plans');
        } else {
            $_SESSION['errors'] = ['Error al crear el plan'];
            Auth::redirect('/admin/plans/create');
        }
    }

    public function editPlan($id)
    {
        $plan = $this->planModel->findById($id);
        if (!$plan) {
            http_response_code(404);
            exit('Plan no encontrado');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $features = array_filter(explode("\n", $_POST['features'] ?? ''));
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => floatval($_POST['price']),
                'max_products' => intval($_POST['max_products']),
                'duration_days' => intval($_POST['duration_days']),
                'features' => array_map('trim', $features),
                'status' => $_POST['status'],
            ];

            if ($this->planModel->update($id, $data)) {
                $_SESSION['success'] = 'Plan actualizado';
                Auth::redirect('/admin/plans');
            }
        }

        $plan['features'] = json_decode($plan['features'], true) ?? [];
        require __DIR__ . '/../views/admin/plan_form.php';
    }

    public function deletePlan($id)
    {
        if ($this->planModel->delete($id)) {
            $_SESSION['success'] = 'Plan eliminado';
        } else {
            $_SESSION['errors'] = ['No se pudo eliminar el plan'];
        }
        Auth::redirect('/admin/plans');
    }

    // ==================== CATEGORÍAS ====================
    public function categories()
    {
        $categories = $this->categoryModel->getAll();
        foreach ($categories as &$cat) {
            $cat['product_count'] = $this->categoryModel->getProductCount($cat['id']);
        }
        require __DIR__ . '/../views/admin/categories.php';
    }

    public function createCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/admin/category_form.php';
            return;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'icon' => trim($_POST['icon'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ];

        if (empty($data['name'])) {
            $_SESSION['errors'] = ['El nombre es requerido'];
            Auth::redirect('/admin/categories/create');
        }

        if ($this->categoryModel->create($data)) {
            $_SESSION['success'] = 'Categoría creada';
            Auth::redirect('/admin/categories');
        }
    }

    public function editCategory($id)
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            http_response_code(404);
            exit('Categoría no encontrada');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'icon' => trim($_POST['icon']),
                'description' => trim($_POST['description']),
            ];

            if ($this->categoryModel->update($id, $data)) {
                $_SESSION['success'] = 'Categoría actualizada';
                Auth::redirect('/admin/categories');
            }
        }

        require __DIR__ . '/../views/admin/category_form.php';
    }

    public function deleteCategory($id)
    {
        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = 'Categoría eliminada';
        } else {
            $_SESSION['errors'] = ['No se pudo eliminar la categoría'];
        }
        Auth::redirect('/admin/categories');
    }

    // ==================== SUSCRIPCIONES ====================
    public function subscriptions()
    {
        $filter = $_GET['status'] ?? null;
        $subscriptions = $this->subscriptionModel->getAll(['status' => $filter]);
        $stats = $this->subscriptionModel->getStats();

        require __DIR__ . '/../views/admin/subscriptions.php';
    }

    public function assignSubscription()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $users = $this->userModel->getAllSellers();
            $plans = $this->planModel->getAll(true);
            require __DIR__ . '/../views/admin/assign_subscription.php';
            return;
        }

        $userId = intval($_POST['user_id'] ?? 0);
        $planId = intval($_POST['plan_id'] ?? 0);

        if ($this->subscriptionModel->create($userId, $planId)) {
            $_SESSION['success'] = 'Suscripción asignada exitosamente';
            Auth::redirect('/admin/subscriptions');
        } else {
            $_SESSION['errors'] = ['Error al asignar suscripción'];
            Auth::redirect('/admin/subscriptions/assign');
        }
    }

    public function cancelSubscription($id)
    {
        if ($this->subscriptionModel->cancel($id)) {
            $_SESSION['success'] = 'Suscripción cancelada';
        }
        Auth::redirect('/admin/subscriptions');
    }
}
