<?php

namespace App\Models;

use App\Core\Database;

class Message
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function send($orderId, $senderId, $receiverId, $message)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO messages (order_id, sender_id, receiver_id, message)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('iiis', $orderId, $senderId, $receiverId, $message);
        return $stmt->execute();
    }

    public function getByOrder($orderId)
    {
        $stmt = $this->db->prepare(
            "SELECT m.*, u.name, u.avatar
             FROM messages m
             JOIN users u ON m.sender_id = u.id
             WHERE m.order_id = ?
             ORDER BY m.created_at ASC"
        );
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function markAsRead($orderId, $userId)
    {
        $stmt = $this->db->prepare(
            "UPDATE messages SET is_read = 1 WHERE order_id = ? AND receiver_id = ?"
        );
        $stmt->bind_param('ii', $orderId, $userId);
        return $stmt->execute();
    }

    public function getUnreadCount($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count FROM messages WHERE receiver_id = ? AND is_read = 0"
        );
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'];
    }
}
