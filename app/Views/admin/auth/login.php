<div class="text-center mb-3">
    <i class="bi bi-shield-lock-fill text-danger" style="font-size: 3rem;"></i>
</div>
<h4 class="fw-bold text-center text-dark mb-1">Đăng Nhập Quản Trị</h4>
<p class="text-center text-muted small mb-4">Hệ thống quản trị nội bộ Nova Shop</p>

<form action="<?= base_url('admin/login') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Email Quản trị viên</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
            <input type="email" name="email" class="form-control" value="<?= old('email', 'admin@novashop.vn') ?>" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Mật khẩu</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
            <input type="password" name="password" class="form-control" value="admin123" required>
        </div>
    </div>

    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold mt-2">
        <i class="bi bi-box-arrow-in-right me-1"></i> ĐĂNG NHẬP ADMIN
    </button>
</form>

<div class="mt-4 p-2 bg-light rounded text-center small text-muted">
    Tài khoản mẫu: <code>admin@novashop.vn</code> / <code>admin123</code>
</div>

