<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\User;

class UserController extends Controller {
    public function index(): void {
        $userModel = new User();
        $page = (int)$this->request->query('page', 1);
        $users = $userModel->paginate($page, ADMIN_PAGE_SIZE, '', [], 'id DESC');

        $this->render('admin/user/index', [
            'title' => 'Quản lý Người dùng - Quản trị',
            'users' => $users,
        ], 'admin');
    }

    public function toggleStatus(string|int $id): void {
        $userModel = new User();
        $user = $userModel->find((int)$id);

        if (!$user) {
            Flash::error('Người dùng không tồn tại.');
            $this->redirect('admin/users');
        }

        $newStatus = ($user['status'] == 1) ? 0 : 1;
        $userModel->update((int)$id, ['status' => $newStatus]);

        Flash::success('Cập nhật trạng thái người dùng thành công!');
        $this->redirect('admin/users');
    }
}

