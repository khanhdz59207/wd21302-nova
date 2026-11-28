<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Models\User;

class AuthController extends Controller {
    public function showLogin(): void {
        if (Auth::isAdmin()) {
            $this->redirect('admin/dashboard');
        }
        $this->render('admin/auth/login', [
            'title' => 'Đăng nhập Quản trị viên - Nova Shop'
        ], 'auth');
    }

    public function login(): void {
        $data = $this->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $userModel = new User();
        $user = $userModel->findByEmail($data['email']);

        if (!$user || !$userModel->verifyPassword($data['password'], $user['password'])) {
            Flash::error('Tài khoản hoặc mật khẩu không chính xác.');
            $this->back();
        }

        if ($user['role'] !== ROLE_ADMIN) {
            Flash::error('Tài khoản này không có quyền quản trị.');
            $this->back();
        }

        if (isset($user['status']) && $user['status'] == 0) {
            Flash::error('Tài khoản đã bị khóa.');
            $this->back();
        }

        Auth::login($user);
        Flash::success("Chào mừng Quản trị viên: {$user['name']}!");
        $this->redirect('admin/dashboard');
    }

    public function logout(): void {
        Auth::logout();
        Flash::info('Đã đăng xuất khỏi trang quản trị.');
        $this->redirect('admin/login');
    }
}

