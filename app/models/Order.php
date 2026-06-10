<?php

namespace App\Models;

use App\Core\Database;

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO orders
             (buyer_id, product_id, seller_id, payment_method_id, amount, payment_proof, buyer_contact, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $status = 'pending';
        $stmt->bind_param(
            'iiidssss',
            $data['buyer_id'],
            $data['product_id'],
            $data['seller_id'],
            $data['payment_method_id'] ?? null,
            $data['amount'],
            $data['payment_proof'] ?? null,
            $data['buyer_contact'],
            $status
        );

        return $stmt->execute() ? $this->db->insert_id : false;
    }

    public function getBySeller($sellerId, $status = null)
    {
        $sql = "SELECT o.*, p.title as product_title, u.name as buyer_name, u.email,
                       spm.method_type
                FROM orders o
                JOIN products p ON o.product_id = p.id
                JOIN users u ON o.buyer_id = u.id
                LEFT JOIN seller_payment_methods spm ON o.payment_method_id = spm.id
                WHERE o.seller_id = ?";

        if ($status) {
            $sql .= " AND o.status = '$status'";
        }

        $sql .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id, $sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT o.*, p.title, p.price, u.name as buyer_name, u.email, u.phone
             FROM orders o
             JOIN products p ON o.product_id = p.id
             JOIN users u ON o.buyer_id = u.id
             WHERE o.id = ? AND o.seller_id = ?"
        );
        $stmt->bind_param('ii', $id, $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStatus($id, $sellerId, $status)
    {
        $allowed = ['paid', 'delivered', 'cancelled', 'refunded'];
        if (!in_array($status, $allowed)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE orders SET status = ? WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param('sii', $status, $id, $sellerId);
        return $stmt->execute();
    }

    public function addNote($id, $sellerId, $note)
    {
        $stmt = $this->db->prepare(
            "UPDATE orders SET notes = ? WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param('sii', $note, $id, $sellerId);
        return $stmt->execute();
    }

    public function getSellerPendingCount($sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count FROM orders WHERE seller_id = ? AND status IN ('pending', 'paid')"
        );
        $stmt->bind_param('i', $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'];
    }

    public function getStats($sellerId)
    {
        $result = $this->db->query(
            "SELECT
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status IN ('paid', 'delivered') THEN amount ELSE 0 END) as revenue
             FROM orders WHERE seller_id = $sellerId"
        );
        return $result->fetch_assoc();
    }
}
