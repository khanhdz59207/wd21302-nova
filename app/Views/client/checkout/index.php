<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('cart') ?>">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Thanh toán</li>
        </ol>
    </nav>

    <h4 class="fw-bold text-dark mb-4"><i class="bi bi-credit-card me-2 text-primary"></i>Thông tin đặt hàng & thanh toán</h4>

    <form action="<?= base_url('checkout/process') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row g-4">
            <!-- Left: Delivery info -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">1. Thông tin người nhận</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" value="<?= old('customer_name', $user['name'] ?? '') ?>" placeholder="Nguyễn Văn A" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" value="<?= old('customer_phone', $user['phone'] ?? '') ?>" placeholder="0901234567" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email nhận hóa đơn <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" class="form-control" value="<?= old('customer_email', $user['email'] ?? '') ?>" placeholder="email@example.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ nhận hàng chi tiết <span class="text-danger">*</span></label>
                        <input type="text" name="customer_address" class="form-control" value="<?= old('customer_address', $user['address'] ?? '') ?>" placeholder="Số nhà, ngõ, tên đường, phường/xã, quận/huyện, tỉnh/thành" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú đơn hàng (nếu có)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi đến..."><?= old('note') ?></textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">2. Phương thức thanh toán</h5>
                    
                    <div class="form-check mb-3 p-3 border rounded">
                        <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_cod" value="cod" checked>
                        <label class="form-check-label fw-semibold" for="pay_cod">
                            <i class="bi bi-cash-stack text-success fs-5 me-2 align-middle"></i> Thanh toán tiền mặt khi nhận hàng (COD)
                            <div class="small text-muted fw-normal mt-1">Quý khách thanh toán cho nhân viên giao hàng sau khi kiểm tra đơn.</div>
                        </label>
                    </div>

                    <div class="form-check mb-3 p-3 border rounded">
                        <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_vnpay" value="vnpay">
                        <label class="form-check-label fw-semibold" for="pay_vnpay">
                            <i class="bi bi-qr-code-scan text-primary fs-5 me-2 align-middle"></i> Cổng thanh toán VNPAY / Quét mã QR
                            <div class="small text-muted fw-normal mt-1">Hỗ trợ tất cả các ứng dụng ngân hàng và ví điện tử Việt Nam.</div>
                        </label>
                    </div>

                    <?php $bank = vietqr_bank(); ?>
                    <div class="form-check p-3 border rounded">
                        <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_bank" value="bank_transfer">
                        <label class="form-check-label fw-semibold" for="pay_bank">
                            <i class="bi bi-bank text-danger fs-5 me-2 align-middle"></i> Chuyển khoản ngân hàng trực tiếp
                            <div class="small text-muted fw-normal mt-1">Nhân viên Nova Shop sẽ liên hệ xác nhận sau khi nhận được tiền.</div>
                            <?php if (!empty($bank['account_no'])): ?>
                                <div class="bank-transfer-account">
                                    <span><strong><?= e($bank['name'] ?? 'Vietcombank') ?></strong> · STK <?= e($bank['account_no']) ?></span>
                                    <span>Chủ tài khoản: <?= e($bank['account_name'] ?? '') ?></span>
                                    <span class="text-primary">Mã VietQR sẽ hiển thị ngay sau khi bạn xác nhận đặt hàng.</span>
                                </div>
                            <?php endif; ?>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Order items summary -->
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 80px;">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Đơn hàng của bạn (<?= count($cartItems) ?> SP)</h5>

                    <div class="pv-checkout-items mb-3" style="max-height: 280px; overflow-y: auto;">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= upload_url($item['image']) ?>" alt="<?= e($item['name']) ?>" width="48" height="48" class="rounded object-fit-contain border p-1">
                                    <div>
                                        <div class="small fw-semibold text-truncate" style="max-width: 180px;"><?= e($item['name']) ?></div>
                                        <?php if (!empty($item['variant_name'])): ?>
                                            <small class="text-muted d-block">Phân loại: <?= e($item['variant_name']) ?></small>
                                        <?php endif; ?>
                                        <small class="text-muted">SL: <?= $item['quantity'] ?> x <?= format_currency($item['price']) ?></small>
                                    </div>
                                </div>
                                <div class="fw-bold text-danger small">
                                    <?= format_currency($item['price'] * $item['quantity']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold"><?= format_currency($totalPrice) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phí giao hàng:</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fs-5 fw-bold">Tổng thanh toán:</span>
                        <span class="fs-4 fw-bold text-danger"><?= format_currency($totalPrice) ?></span>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold">
                        XÁC NHẬN ĐẶT HÀNG
                    </button>
                    <small class="text-center text-muted d-block mt-2">Bằng cách đặt hàng, bạn đồng ý với Điều khoản của Nova Shop.</small>
                </div>
            </div>
        </div>
    </form>
</div>

