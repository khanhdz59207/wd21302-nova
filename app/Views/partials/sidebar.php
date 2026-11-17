<aside class="pv-admin-sidebar bg-dark text-white min-vh-100 p-3">
    <div class="d-flex align-items-center pb-3 mb-3 border-bottom border-secondary">
        <a href="<?= base_url('admin/dashboard') ?>" class="text-white text-decoration-none d-flex align-items-center">
            <i class="bi bi-cpu-fill fs-3 text-primary me-2"></i>
            <span class="fs-5 fw-bold">Nova Shop Admin</span>
        </a>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/dashboard') ?>" class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active bg-primary' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i> Bảng điều khiển
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/categories') ?>" class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], 'categories') ? 'active bg-primary' : '' ?>">
                <i class="bi bi-tags-fill me-2"></i> Quản lý Danh mục
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/products') ?>" class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], 'products') ? 'active bg-primary' : '' ?>">
                <i class="bi bi-laptop me-2"></i> Quản lý Sản phẩm
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/orders') ?>" class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], 'orders') ? 'active bg-primary' : '' ?>">
                <i class="bi bi-receipt me-2"></i> Quản lý Đơn hàng
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/users') ?>" class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], 'users') ? 'active bg-primary' : '' ?>">
                <i class="bi bi-people-fill me-2"></i> Quản lý Người dùng
            </a>
        </li>
    </ul>

    <hr class="border-secondary my-3">

    <div class="dropdown">
        <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-light btn-sm w-100 mb-2">
            <i class="bi bi-box-arrow-up-right me-1"></i> Xem Website Client
        </a>
        <a href="<?= base_url('admin/logout') ?>" class="btn btn-danger btn-sm w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
        </a>
    </div>
</aside>

