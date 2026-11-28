<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Order;

class OrderController extends Controller {
    public function index(): void {
        $orderModel = new Order();
        $page = (int)$this->request->query('page', 1);
        $orders = $orderModel->paginate($page, ADMIN_PAGE_SIZE, '', [], 'id DESC');

        $this->render('admin/order/index', [
            'title'  => 'Quản lý Đơn hàng - Quản trị',
            'orders' => $orders,
        ], 'admin');
    }

    public function detail(string|int $id): void {
        $orderModel = new Order();
        $order = $orderModel->getWithDetails((int)$id);

        if (!$order) {
            Flash::error('Đơn hàng không tồn tại.');
            $this->redirect('admin/orders');
        }

        $this->render('admin/order/detail', [
            'title' => "Chi tiết Đơn hàng #{$order['order_code']} - Quản trị",
            'order' => $order,
        ], 'admin');
    }

    public function updateStatus(string|int $id): void {
        $orderModel = new Order();
        $status = $this->request->post('order_status');
        $paymentStatus = $this->request->post('payment_status');

        $orderModel->update((int)$id, [
            'order_status'   => $status,
            'payment_status' => $paymentStatus,
        ]);

        Flash::success('Cập nhật trạng thái đơn hàng thành công!');
        $this->redirect('admin/orders/' . $id);
    }
}

