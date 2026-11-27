<?php

use App\Core\App;

if (!function_exists('base_url')) {
    /**
     * Lấy URL gốc của ứng dụng
     */
    function base_url(string $path = ''): string {
        $baseUrl = App::config('app.url', 'http://localhost:8000');
        $path = ltrim($path, '/');
        return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Đường dẫn tài nguyên tĩnh (css, js, img)
     */
    function asset(string $path): string {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('upload_url')) {
    /**
     * Đường dẫn file upload
     */
    function upload_url(?string $filename, string $default = 'assets/img/placeholder.png'): string {
        if (empty($filename)) {
            return base_url($default);
        }
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }
        $filename = ltrim($filename, '/');
        if (file_exists(PUBLIC_PATH . '/uploads/' . $filename)) {
            return base_url('uploads/' . $filename);
        }
        if (file_exists(PUBLIC_PATH . '/assets/img/' . $filename)) {
            return base_url('assets/img/' . $filename);
        }
        if (file_exists(PUBLIC_PATH . '/assets/img/products/' . $filename)) {
            return base_url('assets/img/products/' . $filename);
        }
        return base_url($default);
    }
}

if (!function_exists('redirect')) {
    /**
     * Điều hướng sang URL khác
     */
    function redirect(string $path, int $statusCode = 302): void {
        $url = str_starts_with($path, 'http') ? $path : base_url($path);
        header("Location: $url", true, $statusCode);
        exit;
    }
}

