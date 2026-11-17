<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('orders') ?>">Lịch sử đơn hàng</a></li>
            <li class="breadcrumb-item active">Đơn #<?= e($order['order_code']) ?></li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
                <h4 class="fw-bold mb-1">Chi tiết đơn hàng: <span class="text-primary">#<?= e($order['order_code']) ?></span></h4>
                <small class="text-muted">Ngày đặt: <?= format_date($order['created_at']) ?></small>
            </div>
            <div>
                <span class="badge bg-primary fs-6 text-uppercase"><?= e($order['order_status']) ?></span>
            </div>
        </div>

        <div class="row g-3 mb-4 small">
            <div class="col-md-6">
                <h6 class="fw-bold text-dark">Thông tin người nhận</h6>
                <p class="mb-1"><strong>Họ tên:</strong> <?= e($order['customer_name']) ?></p>
                <p class="mb-1"><strong>Số điện thoại:</strong> <?= e($order['customer_phone']) ?></p>
                <p class="mb-1"><strong>Email:</strong> <?= e($order['customer_email']) ?></p>
                <p class="mb-0"><strong>Địa chỉ:</strong> <?= e($order['customer_address']) ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-dark">Thông tin đơn hàng</h6>
                <p class="mb-1"><strong>Phương thức thanh toán:</strong> <span><?= e(payment_method_label($order['payment_method'])) ?></span></p>
                <p class="mb-1"><strong>Trạng thái thanh toán:</strong> <span class="badge bg-secondary"><?= e($order['payment_status']) ?></span></p>
                <p class="mb-0"><strong>Ghi chú:</strong> <?= e($order['note'] ?: 'Không có') ?></p>
            </div>
        </div>

        <h6 class="fw-bold mb-3">Danh sách sản phẩm trong đơn</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light small">
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($order['items'])): ?>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= upload_url($item['product_image']) ?>" alt="<?= e($item['product_name']) ?>" width="48" height="48" class="rounded object-fit-contain border p-1">
                                        <span class="fw-semibold small"><?= e($item['product_name']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center small"><?= format_currency($item['price']) ?></td>
                                <td class="text-center small"><?= $item['quantity'] ?></td>
                                <td class="text-end fw-bold text-danger small"><?= format_currency($item['subtotal']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Tổng thanh toán:</td>
                        <td class="text-end fw-bold text-danger fs-5"><?= format_currency($order['total_amount']) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-3">
            <a href="<?= base_url('orders') ?>" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <?php if (($order['payment_method'] ?? '') === 'bank_transfer'): ?>
        <div class="mt-4">
            <?php \App\Core\View::partial('bank-transfer-qr', ['order' => $order]); ?>
        </div>
    <?php endif; ?>
</div>

