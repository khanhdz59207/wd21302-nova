<h4 class="fw-bold text-center text-dark mb-1">Đăng Nhập</h4>
<p class="text-center text-muted small mb-4">Chào mừng bạn quay lại với Nova Shop</p>

<form action="<?= base_url('auth/login') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Địa chỉ Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="name@example.com" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold small mb-0">Mật khẩu</label>
            <a href="#" class="small text-decoration-none">Quên mật khẩu?</a>
        </div>
        <div class="input-group mt-1">
            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="rememberMe">
        <label class="form-check-label small" for="rememberMe">Ghi nhớ đăng nhập</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
        ĐĂNG NHẬP
    </button>
</form>

<div class="text-center mt-4 small">
    Chưa có tài khoản? <a href="<?= base_url('auth/register') ?>" class="text-primary fw-semibold text-decoration-none">Đăng ký ngay</a>
</div>

