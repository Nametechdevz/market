<?php

namespace App\Models;

use App\Core\Database;

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO products (seller_id, category_id, title, description, price, type, image, stock, delivery_info, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $status = $data['status'] ?? 'draft';
        $stock = $data['stock'] === '-1' ? -1 : intval($data['stock'] ?? 1);

        $stmt->bind_param(
            'iissdssiss',
            $data['seller_id'],
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['type'],
            $data['image'],
            $stock,
            $data['delivery_info'],
            $status
        );
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getBySeller($sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name, c.icon
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.seller_id = ?
             ORDER BY p.created_at DESC"
        );
        $stmt->bind_param('i', $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $sellerId, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE products SET category_id = ?, title = ?, description = ?, price = ?,
             type = ?, stock = ?, delivery_info = ?, status = ? WHERE id = ? AND seller_id = ?"
        );
        $stock = $data['stock'] === '-1' ? -1 : intval($data['stock']);
        $stmt->bind_param(
            'issdssisii',
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['type'],
            $stock,
            $data['delivery_info'],
            $data['status'],
            $id,
            $sellerId
        );
        return $stmt->execute();
    }

    public function delete($id, $sellerId)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
        $stmt->bind_param('ii', $id, $sellerId);
        return $stmt->execute();
    }

    public function toggleFeatured($id, $sellerId)
    {
        $stmt = $this->db->prepare(
            "UPDATE products SET featured = !featured WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param('ii', $id, $sellerId);
        return $stmt->execute();
    }

    public function getSellerStats($sellerId)
    {
        $result = $this->db->query(
            "SELECT
                (SELECT COUNT(*) FROM products WHERE seller_id = $sellerId) as total_products,
                (SELECT COUNT(*) FROM products WHERE seller_id = $sellerId AND status = 'active') as active_products,
                (SELECT COUNT(*) FROM orders WHERE seller_id = $sellerId) as total_orders,
                (SELECT COUNT(*) FROM orders WHERE seller_id = $sellerId AND status = 'delivered') as completed_orders,
                (SELECT COUNT(*) FROM orders WHERE seller_id = $sellerId AND status = 'pending') as pending_orders,
                (SELECT SUM(amount) FROM orders WHERE seller_id = $sellerId AND status = 'delivered') as total_revenue,
                (SELECT AVG(r.rating) FROM reviews r
                 JOIN orders o ON r.order_id = o.id
                 WHERE o.seller_id = $sellerId) as avg_rating"
        );
        return $result->fetch_assoc();
    }

    public function canAddProduct($sellerId, $planId)
    {
        $db = Database::getInstance()->getConnection();

        // Obtener plan del vendedor
        $planStmt = $db->prepare(
            "SELECT p.max_products FROM subscriptions s
             JOIN plans p ON s.plan_id = p.id
             WHERE s.user_id = ? AND s.status = 'active'"
        );
        $planStmt->bind_param('i', $sellerId);
        $planStmt->execute();
        $planResult = $planStmt->get_result()->fetch_assoc();

        // Si no tiene suscripción activa, usar Free (3 productos)
        $maxProducts = $planResult['max_products'] ?? 3;

        // Si es ilimitado (-1), permitir
        if ($maxProducts == -1) return true;

        // Contar productos actuales
        $countStmt = $db->prepare("SELECT COUNT(*) as count FROM products WHERE seller_id = ?");
        $countStmt->bind_param('i', $sellerId);
        $countStmt->execute();
        $count = $countStmt->get_result()->fetch_assoc()['count'];

        return $count < $maxProducts;
    }
}
