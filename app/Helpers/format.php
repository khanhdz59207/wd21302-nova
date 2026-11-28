<?php

if (!function_exists('format_currency')) {
    /**
     * Định dạng tiền tệ VND (vd: 15.000.000 đ)
     */
    function format_currency(float|int|string|null $amount): string {
        $num = (float)($amount ?? 0);
        return number_format($num, 0, ',', '.') . ' ₫';
    }
}

if (!function_exists('format_date')) {
    /**
     * Định dạng ngày tháng
     */
    function format_date(?string $datetime, string $format = 'd/m/Y H:i'): string {
        if (!$datetime) return '';
        $timestamp = strtotime($datetime);
        return $timestamp ? date($format, $timestamp) : '';
    }
}

if (!function_exists('calculate_discount_percent')) {
    /**
     * Tính % giảm giá giữa giá gốc và giá khuyến mãi
     */
    function calculate_discount_percent(float $originalPrice, float $salePrice): int {
        if ($originalPrice <= 0 || $salePrice >= $originalPrice) {
            return 0;
        }
        return (int)round((($originalPrice - $salePrice) / $originalPrice) * 100);
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Cắt ngắn chuỗi văn bản
     */
    function truncate_text(string $text, int $limit = 100, string $end = '...'): string {
        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return $text;
        }
        return mb_substr($text, 0, $limit, 'UTF-8') . $end;
    }
}

