<?php

namespace App\Controllers\Client;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Models\User;
use App\Services\CheckoutService;
use Exception;

class AuthController extends Controller {
    public function showLogin(): void {
        if (Auth::check()) {
            $this->redirect('');
        }

        $checkoutIntent = $this->request->query('checkout') === '1';
        $pendingSummary = $checkoutIntent ? CheckoutService::pendingSummary() : null;

        $this->render('client/auth/login', [
            'title'           => 'Đăng nhập tài khoản - Nova Shop',
            'checkoutIntent'  => $pendingSummary !== null,
            'pendingSummary'  => $pendingSummary,
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
            Flash::error('Email hoặc mật khẩu không chính xác.');
            $this->back();
        }

        if (isset($user['status']) && $user['status'] == 0) {
            Flash::error('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ.');
            $this->back();
        }

        Auth::login($user);

        if ($user['role'] === ROLE_ADMIN) {
            Flash::success("Chào mừng Quản trị viên: {$user['name']}!");
            $this->redirect('admin/dashboard');
        }

        if ($this->request->post('_checkout_continue') === '1') {
            $this->completePendingCheckout($user);
        }

        Flash::success("Chào mừng trở lại, {$user['name']}!");
        $this->redirect('');
    }

    public function showRegister(): void {
        if (Auth::check()) {
            $this->redirect('');
        }

        $checkoutIntent = $this->request->query('checkout') === '1';
        $pending = $checkoutIntent ? CheckoutService::pending() : null;
        $pendingSummary = $pending ? CheckoutService::pendingSummary() : null;

        $this->render('client/auth/register', [
            'title'          => 'Đăng ký tài khoản - Nova Shop',
            'checkoutIntent' => $pendingSummary !== null,
            'pendingSummary' => $pendingSummary,
            'checkoutData'   => $pending['data'] ?? [],
        ], 'auth');
    }

    public function register(): void {
        $data = $this->validate([
            'name'     => 'required|min:2|max:50',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone'    => 'required|min:10|max:15',
        ]);

        $userModel = new User();
        $userId = $userModel->createWithHash([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'phone'    => $data['phone'],
            'address'  => $this->request->post('address', ''),
            'role'     => ROLE_CUSTOMER,
            'status'   => 1,
        ]);

        $user = $userModel->find($userId);
        Auth::login($user);

        if ($this->request->post('_checkout_continue') === '1') {
            $this->completePendingCheckout($user);
        }

        Flash::success('Đăng ký tài khoản thành công!');
        $this->redirect('');
    }

    public function logout(): void {
        Auth::logout();
        Flash::info('Bạn đã đăng xuất an toàn.');
        $this->redirect('auth/login');
    }

    private function completePendingCheckout(array $user): void {
        $pending = CheckoutService::pending();

        if ($pending === null) {
            Flash::warning('Phiên xác nhận đơn hàng đã hết hạn. Vui lòng kiểm tra lại giỏ hàng và đặt hàng lại.');
            $this->redirect('checkout');
        }

        try {
            $order = CheckoutService::placeOrder($pending['data'], (int)$user['id']);
            CheckoutService::clearPending();

            Flash::success("Đơn hàng #{$order['order_code']} đã được xác nhận thành công!");
            $this->redirect('checkout/success/' . $order['id']);
        } catch (Exception $e) {
            Flash::error('Chưa thể xác nhận đơn hàng: ' . $e->getMessage() . ' Vui lòng kiểm tra lại giỏ hàng.');
            $this->redirect('checkout');
        }
    }
}

