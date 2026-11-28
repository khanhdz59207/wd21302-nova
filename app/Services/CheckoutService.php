<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;

class CheckoutService {
    private const PENDING_KEY = '_pending_checkout';
    private const PENDING_TTL = 1800;

    private const PENDING_FIELDS = [
        'customer_name', 'customer_email', 'customer_phone',
        'customer_address', 'note', 'payment_method',
    ];

    private const PAYMENT_METHODS = ['cod', 'vnpay', 'bank_transfer'];

    public static function remember(array $data): void {
        $payload = [];
        foreach (self::PENDING_FIELDS as $field) {
            $payload[$field] = trim((string)($data[$field] ?? ''));
        }

        Session::set(self::PENDING_KEY, [
            'data'       => $payload,
            'created_at' => time(),
        ]);
    }

    public static function pending(): ?array {
        $pending = Session::get(self::PENDING_KEY);
        if (!is_array($pending) || empty($pending['data']) || !is_array($pending['data'])) {
            self::clearPending();
            return null;
        }

        $createdAt = (int)($pending['created_at'] ?? 0);
        if ($createdAt <= 0 || time() - $createdAt > self::PENDING_TTL || empty(Cart::get())) {
            self::clearPending();
            return null;
        }

        return $pending;
    }

    public static function pendingSummary(): ?array {
        $pending = self::pending();
        if ($pending === null) {
            return null;
        }

        $count = 0;
        $total = 0.0;
        foreach (Cart::get() as $item) {
            $quantity = (int)($item['quantity'] ?? 0);
            $count += $quantity;
            $total += (float)($item['price'] ?? 0) * $quantity;
        }

        return [
            'count'          => $count,
            'total'          => $total,
            'payment_method' => $pending['data']['payment_method'] ?? 'cod',
        ];
    }

    public static function clearPending(): void {
        Session::remove(self::PENDING_KEY);
    }

    public static function placeOrder(array $data, int $userId): array {
        if ($userId <= 0) {
            throw new Exception('Vui lòng đăng nhập để xác nhận đơn hàng.');
        }

        foreach (['customer_name', 'customer_email', 'customer_phone', 'customer_address'] as $field) {
            if (trim((string)($data[$field] ?? '')) === '') {
                throw new Exception('Thông tin nhận hàng chưa đầy đủ.');
            }
        }

        $cartItems = Cart::get();
        if (empty($cartItems)) {
            throw new Exception('Giỏ hàng đang trống.');
        }

        $paymentMethod = (string)($data['payment_method'] ?? 'cod');
        if (!in_array($paymentMethod, self::PAYMENT_METHODS, true)) {
            throw new Exception('Phương thức thanh toán không hợp lệ.');
        }

        $db = Database::getInstance();
        $orderModel = new Order();
        $orderItemModel = new OrderItem();

        try {
            $db->beginTransaction();

            $totalAmount = 0.0;
            foreach ($cartItems as $item) {
                $totalAmount += (float)$item['price'] * (int)$item['quantity'];
            }

            $orderCode = $orderModel->generateOrderCode();
            $orderId = $orderModel->create([
                'order_code'       => $orderCode,
                'user_id'          => $userId,
                'customer_name'    => $data['customer_name'],
                'customer_email'   => $data['customer_email'],
                'customer_phone'   => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'note'             => $data['note'] ?? '',
                'total_amount'     => $totalAmount,
                'payment_method'   => $paymentMethod,
                'payment_status'   => 'unpaid',
                'order_status'     => ORDER_STATUS_PENDING,
            ]);

            foreach ($cartItems as $item) {
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];
                $productName = (string)$item['name'];
                if (!empty($item['variant_name'])) {
                    $productName .= ' - ' . $item['variant_name'];
                }

                $orderItemModel->create([
                    'order_id'      => $orderId,
                    'product_id'    => $item['id'],
                    'product_name'  => $productName,
                    'product_image' => $item['image'] ?? '',
                    'price'         => $price,
                    'quantity'      => $quantity,
                    'subtotal'      => $price * $quantity,
                ]);
            }

            $db->commit();
            Cart::clear();

            return [
                'id'           => (int)$orderId,
                'order_code'   => $orderCode,
                'total_amount' => $totalAmount,
            ];
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }
}
