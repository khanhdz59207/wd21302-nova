<?php

namespace App\Controllers\Client;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Order;
use Exception;

class OrderController extends Controller {
    public function history(): void {
        $userId = Auth::id();
        $orderModel = new Order();
        $orders = $orderModel->getByUser($userId);

        $this->render('client/order/history', [
            'title'  => 'Lịch sử đơn hàng - Nova Shop',
            'orders' => $orders,
        ], 'client');
    }

    public function detail(string|int $id): void {
        $userId = Auth::id();
        $orderModel = new Order();
        $order = $orderModel->getWithDetails((int)$id);

        if (!$order || ($order['user_id'] != $userId && !Auth::isAdmin())) {
            throw new Exception("Bạn không có quyền xem thông tin đơn hàng này.", 403);
        }

        $this->render('client/order/detail', [
            'title' => "Chi tiết đơn hàng #{$order['order_code']} - Nova Shop",
            'order' => $order,
        ], 'client');
    }
}

