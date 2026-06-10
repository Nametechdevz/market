<?php

namespace App\Models;

use App\Core\Database;

class SellerPaymentMethod
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        // Si es el primer método, hacerlo principal
        $existingCount = $this->getCountByUser($data['seller_id']);

        $stmt = $this->db->prepare(
            "INSERT INTO seller_payment_methods
             (seller_id, method_type, account_number, account_holder, identification_number, qr_image, is_primary, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $isPrimary = $existingCount === 0 ? 1 : intval($data['is_primary'] ?? 0);
        $isActive = 1;

        $stmt->bind_param(
            'isssssii',
            $data['seller_id'],
            $data['method_type'],
            $data['account_number'] ?? null,
            $data['account_holder'],
            $data['identification_number'],
            $data['qr_image'] ?? null,
            $isPrimary,
            $isActive
        );

        return $stmt->execute() ? $this->db->insert_id : false;
    }

    public function getBySeller($sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM seller_payment_methods WHERE seller_id = ? AND is_active = 1 ORDER BY is_primary DESC"
        );
        $stmt->bind_param('i', $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id, $sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM seller_payment_methods WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param('ii', $id, $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $sellerId, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE seller_payment_methods
             SET account_number = ?, account_holder = ?, identification_number = ?, qr_image = ?, is_primary = ?
             WHERE id = ? AND seller_id = ?"
        );

        $stmt->bind_param(
            'sssisii',
            $data['account_number'],
            $data['account_holder'],
            $data['identification_number'],
            $data['qr_image'],
            intval($data['is_primary'] ?? 0),
            $id,
            $sellerId
        );

        return $stmt->execute();
    }

    public function delete($id, $sellerId)
    {
        $stmt = $this->db->prepare(
            "UPDATE seller_payment_methods SET is_active = 0 WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param('ii', $id, $sellerId);
        return $stmt->execute();
    }

    public function setPrimary($id, $sellerId)
    {
        // Desactivar todos los demás
        $this->db->query("UPDATE seller_payment_methods SET is_primary = 0 WHERE seller_id = $sellerId");

        // Activar este
        $stmt = $this->db->prepare("UPDATE seller_payment_methods SET is_primary = 1 WHERE id = ? AND seller_id = ?");
        $stmt->bind_param('ii', $id, $sellerId);
        return $stmt->execute();
    }

    private function getCountByUser($sellerId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM seller_payment_methods WHERE seller_id = ? AND is_active = 1");
        $stmt->bind_param('i', $sellerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'];
    }

    public static function getMethodLabel($type)
    {
        return match($type) {
            'nequi' => '📱 Nequi',
            'bancolombia' => '🏦 Bancolombia',
            'bre-b' => '💳 Bre-B',
            'daviplata' => '📲 DaviPlata',
            'ps_bank' => '💰 Banco Privado',
            default => '💵 ' . ucfirst($type)
        };
    }
}
