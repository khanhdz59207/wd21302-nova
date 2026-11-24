<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model {
    protected string $table = 'orders';
    protected array $fillable = [
        'order_code', 'user_id', 'customer_name', 'customer_email',
        'customer_phone', 'customer_address', 'note', 'total_amount',
        'payment_method', 'payment_status', 'order_status'
    ];

    public function generateOrderCode(): string {
        return 'NOVA-' . strtoupper(date('ymd')) . '-' . rand(1000, 9999);
    }

    public function getByUser(int $userId): array {
        return $this->where('user_id', $userId, 'id DESC');
    }

    public function getWithDetails(int $orderId): ?array {
        $order = $this->find($orderId);
        if (!$order) {
            return null;
        }

        $orderItemModel = new OrderItem();
        $order['items'] = $orderItemModel->getByOrder($orderId);

        return $order;
    }
}

