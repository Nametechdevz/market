<?php

namespace App\Models;

use App\Core\Database;

class Review
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reviews (order_id, reviewer_id, rating, comment)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'iiis',
            $data['order_id'],
            $data['reviewer_id'],
            $data['rating'],
            $data['comment']
        );
        return $stmt->execute();
    }

    public function getByProduct($productId)
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.name, u.avatar
             FROM reviews r
             JOIN users u ON r.reviewer_id = u.id
             JOIN orders o ON r.order_id = o.id
             WHERE o.product_id = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAvgRating($productId)
    {
        $stmt = $this->db->prepare(
            "SELECT AVG(r.rating) as avg_rating, COUNT(*) as count
             FROM reviews r
             JOIN orders o ON r.order_id = o.id
             WHERE o.product_id = ?"
        );
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByBuyer($buyerId)
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, p.title as product_title, p.image, o.created_at as order_date
             FROM reviews r
             JOIN orders o ON r.order_id = o.id
             JOIN products p ON o.product_id = p.id
             WHERE r.reviewer_id = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->bind_param('i', $buyerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
