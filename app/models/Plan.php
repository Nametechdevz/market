<?php

namespace App\Models;

use App\Core\Database;

class Plan
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($onlyActive = false)
    {
        $sql = "SELECT * FROM plans";
        if ($onlyActive) {
            $sql .= " WHERE status = 'active'";
        }
        $sql .= " ORDER BY price ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM plans WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data)
    {
        $features = json_encode($data['features'] ?? []);
        $stmt = $this->db->prepare(
            "INSERT INTO plans (name, description, price, max_products, duration_days, features, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $status = $data['status'] ?? 'active';
        $stmt->bind_param(
            'ssdiiss',
            $data['name'],
            $data['description'],
            $data['price'],
            $data['max_products'],
            $data['duration_days'],
            $features,
            $status
        );
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $data)
    {
        $features = json_encode($data['features'] ?? []);
        $stmt = $this->db->prepare(
            "UPDATE plans SET name = ?, description = ?, price = ?, max_products = ?,
             duration_days = ?, features = ?, status = ? WHERE id = ?"
        );
        $stmt->bind_param(
            'ssdiissi',
            $data['name'],
            $data['description'],
            $data['price'],
            $data['max_products'],
            $data['duration_days'],
            $features,
            $data['status'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM plans WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getActiveSubscribersCount($planId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM subscriptions
             WHERE plan_id = ? AND status = 'active'"
        );
        $stmt->bind_param('i', $planId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}
