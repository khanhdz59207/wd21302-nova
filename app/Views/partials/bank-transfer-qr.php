<?php
if (($order['payment_method'] ?? '') !== 'bank_transfer') {
    return;
}

$bank = vietqr_bank();
$orderCode = (string)($order['order_code'] ?? '');
$amount = (float)($order['total_amount'] ?? 0);
$isPaid = ($order['payment_status'] ?? '') === 'paid';
$qrUrl = vietqr_url($amount, $orderCode);

if ($qrUrl === '') {
    return;
}
?>
<section class="bank-transfer-card" aria-labelledby="bank-transfer-title">
    <div class="bank-transfer-head">
        <span class="bank-transfer-icon" aria-hidden="true">
            <i class="bi bi-qr-code-scan"></i>
        </span>
        <div>
            <h5 class="bank-transfer-title" id="bank-transfer-title">Thanh toán bằng VietQR</h5>
            <p class="bank-transfer-subtitle">
                <?= $isPaid ? 'Đơn hàng đã được ghi nhận thanh toán.' : 'Mở ứng dụng ngân hàng bất kỳ và quét mã để chuyển khoản nhanh.' ?>
            </p>
        </div>
    </div>

    <?php if ($isPaid): ?>
        <div class="bank-transfer-paid" role="status">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            <span>Đơn hàng <strong>#<?= e($orderCode) ?></strong> đã được thanh toán.</span>
        </div>
    <?php else: ?>
        <div class="bank-transfer-grid">
            <div class="bank-transfer-qr">
                <img src="<?= e($qrUrl) ?>"
                     alt="Mã VietQR thanh toán đơn hàng #<?= e($orderCode) ?>"
                     class="vietqr-image"
                     width="260" height="260"
                     loading="lazy"
                     decoding="async">
                <span class="bank-transfer-scan-hint">
                    <i class="bi bi-phone" aria-hidden="true"></i> Quét bằng app ngân hàng
                </span>
            </div>
            <div class="bank-transfer-info">
                <dl class="bank-transfer-details">
                    <div class="bank-transfer-row">
                        <dt>Ngân hàng</dt>
                        <dd><?= e($bank['name'] ?? 'Vietcombank') ?></dd>
                    </div>
                    <div class="bank-transfer-row">
                        <dt>Số tài khoản</dt>
                        <dd><?= e($bank['account_no'] ?? '') ?></dd>
                    </div>
                    <div class="bank-transfer-row">
                        <dt>Chủ tài khoản</dt>
                        <dd><?= e($bank['account_name'] ?? '') ?></dd>
                    </div>
                    <div class="bank-transfer-row">
                        <dt>Số tiền</dt>
                        <dd class="bank-transfer-amount"><?= format_currency($amount) ?></dd>
                    </div>
                    <div class="bank-transfer-row">
                        <dt>Nội dung chuyển khoản</dt>
                        <dd class="bank-transfer-content"><?= e($orderCode) ?></dd>
                    </div>
                </dl>
                <p class="bank-transfer-note">
                    <i class="bi bi-info-circle" aria-hidden="true"></i>
                    Vui lòng giữ nguyên nội dung chuyển khoản để Nova Shop đối soát đơn hàng nhanh nhất.
                </p>
            </div>
        </div>
    <?php endif; ?>
</section>
