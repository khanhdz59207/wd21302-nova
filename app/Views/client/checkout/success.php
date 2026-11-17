<div class="container my-5 text-center">
    <div class="card border-0 shadow-sm p-5 mx-auto rounded-4" style="max-width: 650px;">
        <div class="mb-3">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p class="text-muted">Cảm ơn quý khách đã mua sắm tại hệ thống Nova Shop.</p>

        <div class="bg-light p-3 rounded-3 my-4 text-start small">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Mã đơn hàng:</span>
                <strong class="text-primary fs-6">#<?= e($order['order_code']) ?></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Người nhận:</span>
                <strong><?= e($order['customer_name']) ?> (<?= e($order['customer_phone']) ?>)</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Địa chỉ nhận hàng:</span>
                <span><?= e($order['customer_address']) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Phương thức thanh toán:</span>
                <span class="badge bg-secondary"><?= e(payment_method_label($order['payment_method'])) ?></span>
            </div>
            <div class="d-flex justify-content-between pt-2 border-top">
                <span class="fw-bold fs-6">Tổng thanh toán:</span>
                <strong class="text-danger fs-5"><?= format_currency($order['total_amount']) ?></strong>
            </div>
        </div>

        <p class="small text-muted mb-4">
            Bộ phận CSKH Nova Shop sẽ liên hệ với quý khách trong vòng 30 phút để xác nhận lịch giao hàng.
        </p>

        <div class="d-flex justify-content-center gap-3">
            <a href="<?= base_url() ?>" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-house-door me-1"></i> Tiếp tục mua sắm
            </a>
            <?php if (\App\Core\Auth::check()): ?>
                <a href="<?= base_url('orders') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-clock-history me-1"></i> Xem lịch sử đơn
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (($order['payment_method'] ?? '') === 'bank_transfer'): ?>
        <div class="mt-4 text-start">
            <?php \App\Core\View::partial('bank-transfer-qr', ['order' => $order]); ?>
        </div>
    <?php endif; ?>
</div>

