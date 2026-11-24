<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model {
    protected string $table = 'categories';
    protected array $fillable = ['parent_id', 'name', 'slug', 'image', 'description', 'status', 'order_index'];

    public function getActive(): array {
        return $this->where('status', 1, 'order_index ASC, id ASC');
    }

    public function getWithProductCount(): array {
        $stmt = $this->db()->query("
            SELECT c.*, COUNT(p.id) AS product_count
            FROM `categories` c
            LEFT JOIN `products` p ON c.id = p.category_id AND p.status = 1
            WHERE c.status = 1
            GROUP BY c.id
            ORDER BY c.order_index ASC, c.id ASC
        ");
        return $stmt->fetchAll();
    }
}

