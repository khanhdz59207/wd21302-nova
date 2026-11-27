<?php

use App\Core\App;

if (!function_exists('vietqr_bank')) {
    function vietqr_bank(): array {
        return (array)App::config('app.payment.bank', []);
    }
}

if (!function_exists('vietqr_url')) {
    function vietqr_url(float|int|string|null $amount, string $addInfo = ''): string {
        $bank = vietqr_bank();
        $bankId = trim((string)($bank['id'] ?? ''));
        $accountNo = preg_replace('/\s+/', '', (string)($bank['account_no'] ?? ''));
        $accountName = trim((string)($bank['account_name'] ?? ''));

        if ($bankId === '' || $accountNo === '') {
            return '';
        }

        $template = trim((string)App::config('app.payment.vietqr_template', 'compact2'));
        $template = $template !== '' ? $template : 'compact2';

        $query = [
            'accountName' => $accountName,
        ];

        $amount = max(0, (int)round((float)$amount));
        if ($amount > 0) {
            $query['amount'] = $amount;
        }

        $addInfo = trim($addInfo);
        if ($addInfo !== '') {
            $query['addInfo'] = $addInfo;
        }

        return 'https://img.vietqr.io/image/'
            . rawurlencode($bankId) . '-'
            . rawurlencode($accountNo) . '-'
            . rawurlencode($template) . '.png?'
            . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }
}

if (!function_exists('payment_method_label')) {
    function payment_method_label(?string $method): string {
        return match ((string)$method) {
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'vnpay' => 'VNPAY / QR',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            default => (string)$method,
        };
    }
}
