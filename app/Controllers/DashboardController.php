<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller {
    public function index(): void {
        $productModel = new Product();
        $orderModel = new Order();
        $userModel = new User();
        $categoryModel = new Category();

        $totalProducts = $productModel->count();
        $totalOrders = $orderModel->count();
        $totalUsers = $userModel->count();
        $totalCategories = $categoryModel->count();

        $recentOrders = $orderModel->paginate(1, 5, '', [], 'id DESC');

        $this->render('admin/dashboard/index', [
            'title'           => 'Bảng điều khiển - Quản trị Nova Shop',
            'totalProducts'   => $totalProducts,
            'totalOrders'     => $totalOrders,
            'totalUsers'      => $totalUsers,
            'totalCategories' => $totalCategories,
            'recentOrders'    => $recentOrders['data'] ?? [],
        ], 'admin');
    }
}

