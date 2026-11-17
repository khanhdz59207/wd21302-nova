<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Quản lý Đơn hàng</h3>
        <small class="text-muted">Tổng cộng <?= $orders['total'] ?? 0 ?> đơn đặt hàng</small>
    </div>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái đơn</th>
                    <th>Ngày đặt</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders['data'])): ?>
                    <?php foreach ($orders['data'] as $o): ?>
                        <tr>
                            <td class="fw-bold text-primary">#<?= e($o['order_code']) ?></td>
                            <td><?= e($o['customer_name']) ?></td>
                            <td><?= e($o['customer_phone']) ?></td>
                            <td class="fw-bold text-danger"><?= format_currency($o['total_amount']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark text-uppercase"><?= e($o['payment_method']) ?></span>
                                <small class="d-block <?= ($o['payment_status'] === 'paid') ? 'text-success' : 'text-danger' ?>">
                                    <?= ($o['payment_status'] === 'paid') ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                                </small>
                            </td>
                            <td>
                                <?php 
                                    $badge = match($o['order_status']) {
                                        ORDER_STATUS_COMPLETED => 'bg-success',
                                        ORDER_STATUS_SHIPPING => 'bg-info text-dark',
                                        ORDER_STATUS_CANCELLED => 'bg-danger',
                                        default => 'bg-warning text-dark'
                                    };
                                ?>
                                <span class="badge <?= $badge ?> text-uppercase"><?= e($o['order_status']) ?></span>
                            </td>
                            <td class="small text-muted"><?= format_date($o['created_at']) ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('admin/orders/' . $o['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php \App\Core\View::partial('pagination', ['pagination' => $orders]); ?>
</div>

