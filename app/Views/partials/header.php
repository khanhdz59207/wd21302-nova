<?php
use App\Core\Auth;
use App\Models\Cart;

$cartCount = Cart::totalCount();
$user = Auth::user();
$isLoggedIn = $user !== null;
$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/', '/');
$isHome = $path === '' || $path === 'index.php';

$navCategories = [
    ['id' => 1, 'name' => 'Laptop', 'icon' => 'bi-laptop'],
    ['id' => 2, 'name' => 'PC - Máy tính bàn', 'icon' => 'bi-pc-display'],
    ['id' => 3, 'name' => 'Linh kiện', 'icon' => 'bi-cpu'],
    ['id' => 4, 'name' => 'Màn hình', 'icon' => 'bi-display'],
    ['id' => 6, 'name' => 'Bàn phím', 'icon' => 'bi-keyboard'],
    ['id' => 7, 'name' => 'Chuột', 'icon' => 'bi-mouse'],
];
?>
<div class="topbar">
    <div class="container-site topbar-inner">
        <div class="topbar-left">
            <span><i class="bi bi-truck me-1" aria-hidden="true"></i>Miễn phí vận chuyển đơn từ 500.000₫</span>
            <span class="d-none d-md-inline">
                <i class="bi bi-telephone-fill me-1" aria-hidden="true"></i>
                Hotline: <strong><a href="tel:18006867">1800 6867</a></strong>
                <span class="ms-1">(8:00–21:00)</span>
            </span>
        </div>
        <div class="topbar-right">
            <a href="<?= base_url('products') ?>" class="d-none d-sm-inline">Hệ thống cửa hàng</a>
            <a href="<?= base_url('orders') ?>">Tra cứu đơn hàng</a>
            <?php if ($isLoggedIn): ?>
                <span class="topbar-greeting d-none d-lg-inline">Xin chào, <strong><?= e($user['name']) ?></strong></span>
                <?php if (Auth::isAdmin()): ?>
                    <a href="<?= base_url('admin/dashboard') ?>" class="topbar-admin">Quản trị</a>
                <?php endif; ?>
                <a href="<?= base_url('auth/logout') ?>">Đăng xuất</a>
            <?php else: ?>
                <a href="<?= base_url('auth/login') ?>">Đăng nhập</a>
                <a href="<?= base_url('auth/register') ?>" class="d-none d-sm-inline">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<header class="main-header" id="main-header">
    <div class="container-site header-inner">
        <button class="mobile-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileDrawer" aria-controls="mobileDrawer" aria-label="Mở menu">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>

        <a href="<?= base_url() ?>" class="header-logo" aria-label="Nova Shop - Trang chủ">
            <img src="<?= asset('img/logo.svg') ?>" alt="Nova Shop" width="42" height="42">
            <span class="header-logo-text">
                <strong>NOVA SHOP</strong>
                <small>Công nghệ chính hãng</small>
            </span>
        </a>

        <div class="header-categories">
            <button class="btn-categories" type="button" data-categories-toggle aria-expanded="false" aria-controls="categoriesPanel">
                <i class="bi bi-grid-3x3-gap-fill" aria-hidden="true"></i>
                <span>Danh mục</span>
                <i class="bi bi-chevron-down caret" aria-hidden="true"></i>
            </button>
            <div class="categories-panel" id="categoriesPanel" data-categories-panel>
                <?php foreach ($navCategories as $cat): ?>
                    <a href="<?= base_url('category/' . $cat['id']) ?>">
                        <i class="bi <?= $cat['icon'] ?>" aria-hidden="true"></i>
                        <?= e($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
                <a href="<?= base_url('products') ?>">
                    <i class="bi bi-grid" aria-hidden="true"></i> Tất cả sản phẩm
                </a>
                <a href="<?= base_url('category/3') ?>">
                    <i class="bi bi-tools" aria-hidden="true"></i> Build PC theo cấu hình
                </a>
                <a href="<?= base_url('products') ?>">
                    <i class="bi bi-lightning-charge" aria-hidden="true"></i> Flash Sale hôm nay
                </a>
            </div>
        </div>

        <div class="header-search">
            <form action="<?= base_url('search') ?>" method="GET" role="search">
                <label class="visually-hidden" for="search-input">Tìm sản phẩm</label>
                <input id="search-input" type="text" name="q" class="form-control search-input"
                       value="<?= e($_GET['q'] ?? '') ?>"
                       placeholder="Bạn cần tìm gì hôm nay?"
                       autocomplete="off">
                <button type="submit" class="btn btn-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <span>Tìm kiếm</span>
                </button>
            </form>
            <div class="search-popular">
                <span>Phổ biến:</span>
                <a href="<?= base_url('search?q=laptop gaming') ?>">laptop gaming</a>
                <a href="<?= base_url('search?q=iphone') ?>">iphone</a>
                <a href="<?= base_url('search?q=pc văn phòng') ?>">pc văn phòng</a>
                <a href="<?= base_url('search?q=màn hình 144hz') ?>">màn hình 144hz</a>
                <a href="<?= base_url('search?q=bàn phím cơ') ?>">bàn phím cơ</a>
            </div>
        </div>

        <div class="header-actions">
            <a href="<?= base_url('orders') ?>" class="header-action-btn">
                <i class="bi bi-receipt" aria-hidden="true"></i>
                <span>Tra cứu đơn</span>
            </a>
            <a href="<?= $isLoggedIn ? base_url('orders') : base_url('auth/login') ?>" class="header-action-btn">
                <i class="bi bi-person" aria-hidden="true"></i>
                <span><?= $isLoggedIn ? 'Tài khoản' : 'Đăng nhập' ?></span>
            </a>
            <a href="<?= base_url('cart') ?>" class="header-action-btn header-cart">
                <span class="action-icon-wrap">
                    <i class="bi bi-cart3" aria-hidden="true"></i>
                    <span class="cart-badge" id="cart-badge-count"><?= $cartCount ?></span>
                </span>
                <span>Giỏ hàng</span>
            </a>
        </div>
    </div>
</header>

<nav class="category-nav" aria-label="Danh mục sản phẩm">
    <div class="container-site">
        <ul class="nav-list">
            <li class="nav-item nav-all">
                <a href="<?= base_url('products') ?>" class="nav-link">
                    <i class="bi bi-list" aria-hidden="true"></i> Tất cả danh mục
                </a>
                <div class="mega-menu">
                    <?php foreach ($navCategories as $cat): ?>
                        <a href="<?= base_url('category/' . $cat['id']) ?>">
                            <i class="bi <?= $cat['icon'] ?>" aria-hidden="true"></i>
                            <?= e($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?= base_url('products') ?>">
                        <i class="bi bi-grid" aria-hidden="true"></i> Tất cả sản phẩm
                    </a>
                    <a href="<?= base_url('category/3') ?>">
                        <i class="bi bi-tools" aria-hidden="true"></i> Build PC theo cấu hình
                    </a>
                </div>
            </li>
            <?php foreach ($navCategories as $cat): ?>
                <li class="nav-item">
                    <a href="<?= base_url('category/' . $cat['id']) ?>" class="nav-link"><?= e($cat['name']) ?></a>
                </li>
            <?php endforeach; ?>
            <li class="nav-item nav-sale">
                <a href="<?= base_url('products') ?>" class="nav-link">
                    <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i> Flash Sale
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="offcanvas offcanvas-start mobile-drawer" tabindex="-1" id="mobileDrawer" aria-labelledby="mobileDrawerLabel">
    <div class="offcanvas-header">
        <a href="<?= base_url() ?>" class="header-logo">
            <img src="<?= asset('img/logo.svg') ?>" alt="Nova Shop" width="36" height="36">
            <span class="header-logo-text">
                <strong>NOVA SHOP</strong>
            </span>
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
    </div>
    <div class="offcanvas-body">
        <p class="drawer-label" id="mobileDrawerLabel">Danh mục</p>
        <nav class="drawer-nav" aria-label="Danh mục di động">
            <a href="<?= base_url() ?>"><i class="bi bi-house" aria-hidden="true"></i> Trang chủ</a>
            <?php foreach ($navCategories as $cat): ?>
                <a href="<?= base_url('category/' . $cat['id']) ?>">
                    <i class="bi <?= $cat['icon'] ?>" aria-hidden="true"></i> <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <p class="drawer-label">Hỗ trợ</p>
        <nav class="drawer-nav" aria-label="Hỗ trợ">
            <a href="<?= base_url('products') ?>"><i class="bi bi-grid" aria-hidden="true"></i> Tất cả sản phẩm</a>
            <a href="<?= base_url('cart') ?>"><i class="bi bi-cart3" aria-hidden="true"></i> Giỏ hàng</a>
            <a href="<?= base_url('orders') ?>"><i class="bi bi-receipt" aria-hidden="true"></i> Đơn hàng của tôi</a>
            <a href="tel:18006867"><i class="bi bi-telephone" aria-hidden="true"></i> Gọi 1800 6867</a>
        </nav>

        <div class="drawer-foot">
            <?php if ($isLoggedIn): ?>
                <p>Xin chào, <strong><?= e($user['name']) ?></strong></p>
                <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-primary w-100">Đăng xuất</a>
            <?php else: ?>
                <a href="<?= base_url('auth/login') ?>" class="btn btn-primary w-100">Đăng nhập</a>
                <a href="<?= base_url('auth/register') ?>" class="btn btn-outline-primary w-100">Tạo tài khoản</a>
            <?php endif; ?>
        </div>
    </div>
</div>
