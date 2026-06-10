<?php

namespace App\Models;

use App\Core\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, phone, role)
             VALUES (?, ?, ?, ?, ?)"
        );

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $role = $data['role'] ?? 'buyer';

        $stmt->bind_param(
            'sssss',
            $data['name'],
            $data['email'],
            $hashedPassword,
            $data['phone'] ?? null,
            $role
        );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? AND status = 'active'");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function emailExists($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function update($id, $data)
    {
        $allowed = ['name', 'phone', 'avatar'];
        $updates = [];
        $values = [];
        $types = '';

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $updates[] = "$key = ?";
                $values[] = $value;
                $types .= 's';
            }
        }

        if (empty($updates)) return false;

        $types .= 'i';
        $values[] = $id;

        $stmt = $this->db->prepare("UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?");
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    public function getAllSellers()
    {
        $result = $this->db->query(
            "SELECT u.*, s.status as subscription_status, p.name as plan_name
             FROM users u
             LEFT JOIN subscriptions s ON u.id = s.user_id AND s.status = 'active'
             LEFT JOIN plans p ON s.plan_id = p.id
             WHERE u.role = 'seller' AND u.status = 'active'"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSellerStats($sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT
                (SELECT COUNT(*) FROM products WHERE seller_id = ?) as total_products,
                (SELECT COUNT(*) FROM orders WHERE seller_id = ? AND status = 'delivered') as total_sales,
                (SELECT SUM(amount) FROM orders WHERE seller_id = ? AND status = 'delivered') as total_revenue,
                (SELECT AVG(r.rating) FROM reviews r
                 JOIN orders o ON r.order_id = o.id
                 WHERE o.seller_id = ?) as avg_rating"
        );

        $stmt->bind_param('iiii', $sellerId, $sellerId, $sellerId, $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
