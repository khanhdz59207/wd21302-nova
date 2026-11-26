<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model {
    protected string $table = 'reviews';
    protected array $fillable = ['product_id', 'user_id', 'rating', 'comment', 'status'];

    public function getByProduct(int $productId): array {
        $stmt = $this->db()->prepare("
            SELECT r.*, u.name AS user_name, u.avatar AS user_avatar
            FROM `reviews` r
            LEFT JOIN `users` u ON r.user_id = u.id
            WHERE r.product_id = :product_id AND r.status = 1
            ORDER BY r.id DESC
        ");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }
}

