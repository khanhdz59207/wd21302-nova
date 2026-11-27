<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;

class AdminMiddleware {
    public function handle(Request $request): void {
        if (!Auth::check()) {
            Flash::warning('Vui lòng đăng nhập với quyền Quản trị viên.');
            Response::redirect(base_url('admin/login'));
        }

        if (!Auth::isAdmin()) {
            Flash::error('Bạn không có quyền truy cập vào khu vực quản trị (403 Forbidden).');
            Response::redirect(base_url('errors/403'));
        }
    }
}

