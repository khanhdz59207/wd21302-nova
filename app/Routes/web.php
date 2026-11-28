<?php

use App\Core\App;
use App\Controllers\Client\HomeController;
use App\Controllers\Client\ProductController;
use App\Controllers\Client\CategoryController;
use App\Controllers\Client\SearchController;
use App\Controllers\Client\CartController;
use App\Controllers\Client\CheckoutController;
use App\Controllers\Client\OrderController;
use App\Controllers\Client\AuthController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;

$router = App::router();

// Trang chủ
$router->get('/', [HomeController::class, 'index']);

// Sản phẩm
$router->get('/products', [ProductController::class, 'list']);
$router->get('/product/{id}', [ProductController::class, 'detail']);

// Danh mục
$router->get('/category/{id}', [CategoryController::class, 'show']);

// Tìm kiếm
$router->get('/search', [SearchController::class, 'index']);

// Giỏ hàng
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add'], [CsrfMiddleware::class]);
$router->post('/cart/update', [CartController::class, 'update'], [CsrfMiddleware::class]);
$router->post('/cart/remove', [CartController::class, 'remove'], [CsrfMiddleware::class]);
$router->post('/cart/clear', [CartController::class, 'clear'], [CsrfMiddleware::class]);

// Thanh toán
$router->get('/checkout', [CheckoutController::class, 'index']);
$router->post('/checkout/process', [CheckoutController::class, 'process'], [CsrfMiddleware::class]);
$router->get('/checkout/success/{id}', [CheckoutController::class, 'success']);

// Đơn hàng (yêu cầu đăng nhập)
$router->get('/orders', [OrderController::class, 'history'], [AuthMiddleware::class]);
$router->get('/order/{id}', [OrderController::class, 'detail'], [AuthMiddleware::class]);

// Xác thực tài khoản khách hàng
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->post('/auth/login', [AuthController::class, 'login'], [CsrfMiddleware::class]);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/auth/register', [AuthController::class, 'register'], [CsrfMiddleware::class]);
$router->post('/auth/logout', [AuthController::class, 'logout'], [CsrfMiddleware::class]);
$router->get('/auth/logout', [AuthController::class, 'logout']);

