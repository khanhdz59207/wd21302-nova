<?php

namespace App\Middleware;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use Exception;

class CsrfMiddleware {
    public function handle(Request $request): void {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            $token = $request->input('_csrf_token') ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!Csrf::validate($token)) {
                Flash::error('Phiên làm việc hoặc mã bảo mật (CSRF token) đã hết hạn. Vui lòng tải lại trang.');
                throw new Exception('CSRF token validation failed (SYS-04).', 403);
            }
        }
    }
}

