<?php

namespace App\Core;

class Response {
    public static function status(int $code): void {
        http_response_code($code);
    }

    public static function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function redirect(string $url, int $status = 302): void {
        header("Location: $url", true, $status);
        exit;
    }
}

