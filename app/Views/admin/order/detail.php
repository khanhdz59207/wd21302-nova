<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Chi tiết đơn hàng: <span class="text-primary">#<?= e($order['order_code']) ?></span></h3>
        <small class="text-muted">Đặt lúc: <?= format_date($order['created_at']) ?></small>
    </div>
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- Cập nhật trạng thái -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h5 class="fw-bold border-bottom pb-2 mb-3">Xử lý đơn hàng</h5>
            <form action="<?= base_url('admin/orders/' . $order['id'] . '/status') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái đơn hàng</label>
                    <select name="order_status" class="form-select">
                        <option value="pending" <?= ($order['order_status'] === 'pending') ? 'selected' : '' ?>>Chờ xử lý (Pending)</option>
                        <option value="processing" <?= ($order['order_status'] === 'processing') ? 'selected' : '' ?>>Đang đóng gói (Processing)</option>
                        <option value="shipping" <?= ($order['order_status'] === 'shipping') ? 'selected' : '' ?>>Đang giao hàng (Shipping)</option>
                        <option value="completed" <?= ($order['order_status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành (Completed)</option>
                        <option value="cancelled" <?= ($order['order_status'] === 'cancelled') ? 'selected' : '' ?>>Đã hủy (Cancelled)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái thanh toán</label>
                    <select name="payment_status" class="form-select">
                        <option value="unpaid" <?= ($order['payment_status'] === 'unpaid') ? 'selected' : '' ?>>Chưa thanh toán</option>
                        <option value="paid" <?= ($order['payment_status'] === 'paid') ? 'selected' : '' ?>>Đã thanh toán</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                    <i class="bi bi-check2-circle me-1"></i> CẬP NHẬT TRẠNG THÁI
                </button>
            </form>
        </div>
    </div>

    <!-- Thông tin khách hàng & địa chỉ -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h5 class="fw-bold border-bottom pb-2 mb-3">Thông tin giao nhận</h5>
            <div class="row g-3 small">
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Khách hàng:</p>
                    <h6 class="fw-bold"><?= e($order['customer_name']) ?></h6>
                    <p class="mb-1 text-muted">Số điện thoại:</p>
                    <h6><?= e($order['customer_phone']) ?></h6>
                    <p class="mb-1 text-muted">Email:</p>
                    <h6><?= e($order['customer_email']) ?></h6>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Địa chỉ nhận hàng:</p>
                    <h6><?= e($order['customer_address']) ?></h6>
                    <p class="mb-1 text-muted">Hình thức thanh toán:</p>
                    <h6 class="text-uppercase"><?= e($order['payment_method']) ?></h6>
                    <p class="mb-1 text-muted">Ghi chú của khách:</p>
                    <p class="fst-italic text-dark mb-0"><?= e($order['note'] ?: 'Không có') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách sản phẩm -->
<div class="card border-0 shadow-sm p-4">
    <h5 class="fw-bold border-bottom pb-2 mb-3">Danh sách sản phẩm trong đơn</h5>
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
                                <div class="d-flex align-items-center gap-3">
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
                    <td colspan="3" class="text-end fw-bold">Tổng giá trị đơn hàng:</td>
                    <td class="text-end fw-bold text-danger fs-5"><?= format_currency($order['total_amount']) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

