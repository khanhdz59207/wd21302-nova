<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;

class AuthMiddleware {
    public function handle(Request $request): void {
        if (!Auth::check()) {
            Flash::warning('Vui lòng đăng nhập để tiếp tục.');
            Response::redirect(base_url('auth/login'));
        }
    }
}

