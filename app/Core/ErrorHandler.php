<?php

namespace App\Core;

use Throwable;

class ErrorHandler {
    public static function register(): void {
        error_reporting(E_ALL);
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleError(int $level, string $message, string $file = '', int $line = 0): bool {
        if (error_reporting() & $level) {
            self::logError("PHP Notice/Warning [$level]: $message in $file:$line");
        }
        return true;
    }

    public static function handleException(Throwable $e): void {
        $msg = sprintf(
            "Exception [%s]: %s in %s:%d\nStack Trace:\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
        self::logError($msg);

        $code = $e->getCode();
        $httpCode = (is_int($code) && $code >= 400 && $code < 600) ? $code : 500;
        http_response_code($httpCode);

        if (App::config('app.debug')) {
            echo "<div style='font-family:sans-serif;padding:24px;background:#fef2f2;border-left:5px solid #ef4444;margin:20px;border-radius:6px;'>";
            echo "<h2 style='color:#b91c1c;margin-top:0;'>⚠️ Lỗi Hệ Thống (" . get_class($e) . ")</h2>";
            echo "<p><strong>Thông báo:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Dòng: " . $e->getLine() . ")</p>";
            echo "<pre style='background:#1f2937;color:#f3f4f6;padding:16px;border-radius:6px;overflow:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            echo "</div>";
        } else {
            if ($httpCode === 404) {
                View::render('errors/404', ['title' => '404 - Không tìm thấy trang'], 'client');
            } elseif ($httpCode === 403) {
                View::render('errors/403', ['title' => '403 - Quyền truy cập bị từ chối'], 'client');
            } else {
                View::render('errors/500', ['title' => '500 - Lỗi máy chủ nội bộ'], 'client');
            }
        }
        exit;
    }

    public static function logError(string $message): void {
        $logDir = STORAGE_PATH . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . '/app.log';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message\n----------------------------------------\n", FILE_APPEND | LOCK_EX);
    }
}

