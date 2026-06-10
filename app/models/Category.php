<?php

namespace App\Models;

use App\Core\Database;

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data)
    {
        $slug = $this->generateSlug($data['name']);
        $stmt = $this->db->prepare(
            "INSERT INTO categories (name, slug, icon, description) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'ssss',
            $data['name'],
            $slug,
            $data['icon'],
            $data['description']
        );
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $data)
    {
        $slug = $this->generateSlug($data['name']);
        $stmt = $this->db->prepare(
            "UPDATE categories SET name = ?, slug = ?, icon = ?, description = ? WHERE id = ?"
        );
        $stmt->bind_param(
            'ssssi',
            $data['name'],
            $slug,
            $data['icon'],
            $data['description'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getProductCount($categoryId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM products WHERE category_id = ? AND status = 'active'"
        );
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    private function generateSlug($name)
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[áàäâ]/u', 'a', $slug);
        $slug = preg_replace('/[éèëê]/u', 'e', $slug);
        $slug = preg_replace('/[íìïî]/u', 'i', $slug);
        $slug = preg_replace('/[óòöô]/u', 'o', $slug);
        $slug = preg_replace('/[úùüû]/u', 'u', $slug);
        $slug = preg_replace('/[ñ]/u', 'n', $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
}
