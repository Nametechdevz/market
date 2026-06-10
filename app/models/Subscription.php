<?php

namespace App\Models;

use App\Core\Database;

class Subscription
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getActiveSubscription($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, p.name as plan_name, p.max_products, p.features, p.price
             FROM subscriptions s
             JOIN plans p ON s.plan_id = p.id
             WHERE s.user_id = ? AND s.status = 'active' AND s.ends_at > NOW()
             ORDER BY s.created_at DESC LIMIT 1"
        );
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($userId, $planId)
    {
        $planStmt = $this->db->prepare("SELECT duration_days FROM plans WHERE id = ?");
        $planStmt->bind_param('i', $planId);
        $planStmt->execute();
        $plan = $planStmt->get_result()->fetch_assoc();

        if (!$plan) return false;

        $this->db->query("UPDATE subscriptions SET status = 'cancelled'
                          WHERE user_id = $userId AND status = 'active'");

        $startsAt = date('Y-m-d H:i:s');
        $endsAt = date('Y-m-d H:i:s', strtotime("+{$plan['duration_days']} days"));

        $stmt = $this->db->prepare(
            "INSERT INTO subscriptions (user_id, plan_id, starts_at, ends_at, status)
             VALUES (?, ?, ?, ?, 'active')"
        );
        $stmt->bind_param('iiss', $userId, $planId, $startsAt, $endsAt);

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT s.*, u.name as user_name, u.email, p.name as plan_name, p.price
                FROM subscriptions s
                JOIN users u ON s.user_id = u.id
                JOIN plans p ON s.plan_id = p.id
                WHERE 1=1";

        if (!empty($filters['status'])) {
            $status = $this->db->real_escape_string($filters['status']);
            $sql .= " AND s.status = '$status'";
        }

        $sql .= " ORDER BY s.created_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function cancel($id)
    {
        $stmt = $this->db->prepare("UPDATE subscriptions SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getStats()
    {
        $result = $this->db->query(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
             FROM subscriptions"
        );
        return $result->fetch_assoc();
    }

    public function getTotalRevenue()
    {
        $result = $this->db->query(
            "SELECT SUM(p.price) as revenue
             FROM subscriptions s
             JOIN plans p ON s.plan_id = p.id
             WHERE s.status IN ('active', 'expired')"
        );
        return $result->fetch_assoc()['revenue'] ?? 0;
    }
}
