<?php

namespace App\Models;

use App\Core\Model;

class OrderItem extends Model {
    protected string $table = 'order_items';
    protected array $fillable = [
        'order_id', 'product_id', 'product_name', 'product_image',
        'price', 'quantity', 'subtotal'
    ];

    public function getByOrder(int $orderId): array {
        return $this->where('order_id', $orderId, 'id ASC');
    }
}

