<?php

namespace App\Controllers\Client;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Services\CheckoutService;
use Exception;

class CheckoutController extends Controller {
    public function index(): void {
        $cartItems = Cart::get();
        if (empty($cartItems)) {
            Flash::warning('Giỏ hàng đang trống, vui lòng chọn sản phẩm trước khi thanh toán.');
            $this->redirect('cart');
        }

        $totalPrice = Cart::totalPrice();
        $user = Auth::user();
        $pendingCheckout = CheckoutService::pending();

        $this->render('client/checkout/index', [
            'title'        => 'Thanh toán đơn hàng - Nova Shop',
            'cartItems'    => $cartItems,
            'totalPrice'   => $totalPrice,
            'user'         => $user,
            'checkoutData' => $pendingCheckout['data'] ?? [],
        ], 'client');
    }

    public function process(): void {
        $cartItems = Cart::get();
        if (empty($cartItems)) {
            Flash::warning('Giỏ hàng trống.');
            $this->redirect('cart');
        }

        $data = $this->validate([
            'customer_name'    => 'required|min:3|max:100',
            'customer_email'   => 'required|email',
            'customer_phone'   => 'required|min:10|max:15',
            'customer_address' => 'required|min:5|max:255',
            'payment_method'   => 'required',
        ]);

        if (!Auth::check()) {
            CheckoutService::remember($data);
            $this->redirect('auth/login?checkout=1');
        }

        try {
            $order = CheckoutService::placeOrder($data, (int)Auth::id());
            CheckoutService::clearPending();
            $this->rememberOrderAccess($order['id']);

            Flash::success("Đặt hàng thành công! Mã đơn của bạn: #{$order['order_code']}");
            $this->redirect('checkout/success/' . $order['id']);
        } catch (Exception $e) {
            Flash::error('Đã xảy ra lỗi khi tạo đơn hàng: ' . $e->getMessage());
            $this->back();
        }
    }

    public function success(string|int $orderId): void {
        $orderModel = new Order();
        $order = $orderModel->getWithDetails((int)$orderId);

        if (!$order) {
            throw new Exception("Đơn hàng không tồn tại.", 404);
        }

        if (!$this->canViewOrder($order)) {
            throw new Exception("Bạn không có quyền xem thông tin đơn hàng này.", 403);
        }

        $this->render('client/checkout/success', [
            'title' => 'Đặt hàng thành công - Nova Shop',
            'order' => $order,
        ], 'client');
    }

    private function rememberOrderAccess(int $orderId): void {
        $orders = Session::get('_checkout_order_access', []);
        $orders = is_array($orders) ? array_map('intval', $orders) : [];
        $orders[] = $orderId;
        $orders = array_values(array_unique($orders));
        Session::set('_checkout_order_access', array_slice($orders, -10));
    }

    private function canViewOrder(array $order): bool {
        $user = Auth::user();
        if ($user && (Auth::isAdmin() || (int)($order['user_id'] ?? 0) === (int)$user['id'])) {
            return true;
        }

        $orders = Session::get('_checkout_order_access', []);
        return is_array($orders) && in_array((int)$order['id'], array_map('intval', $orders), true);
    }
}

