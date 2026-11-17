<h4 class="fw-bold text-center text-dark mb-1">Đăng Ký Tài Khoản</h4>
<p class="text-center text-muted small mb-4">Tạo tài khoản để nhận ngập tràn ưu đãi từ Nova Shop</p>

<form action="<?= base_url('auth/register') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Họ và tên <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" placeholder="Nguyễn Văn A" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Địa chỉ Email <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="name@example.com" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Số điện thoại <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
            <input type="tel" name="phone" class="form-control" value="<?= old('phone') ?>" placeholder="0901234567" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Mật khẩu <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Xác nhận mật khẩu <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
        ĐĂNG KÝ NGAY
    </button>
</form>

<div class="text-center mt-4 small">
    Đã có tài khoản? <a href="<?= base_url('auth/login') ?>" class="text-primary fw-semibold text-decoration-none">Đăng nhập</a>
</div>

