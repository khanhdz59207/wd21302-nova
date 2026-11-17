<?php
use App\Core\Auth;
$adminUser = Auth::user();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4 py-2">
    <div class="container-fluid">
        <span class="navbar-text fw-bold text-dark">
            <i class="bi bi-shield-check text-success me-1"></i> Trang quản trị hệ thống
        </span>

        <div class="d-flex align-items-center ms-auto">
            <div class="me-3 text-end d-none d-md-block">
                <div class="fw-bold text-dark"><?= e($adminUser['name'] ?? 'Admin') ?></div>
                <div class="small text-muted"><?= e($adminUser['email'] ?? 'admin@novashop.vn') ?></div>
            </div>
            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                <?= strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) ?>
            </div>
        </div>
    </div>
</nav>

