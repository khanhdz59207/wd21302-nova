<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
            <li class="breadcrumb-item active">Lịch sử đơn hàng</li>
        </ol>
    </nav>

    <h4 class="fw-bold text-dark mb-4"><i class="bi bi-clock-history me-2 text-primary"></i>Lịch sử đơn hàng của bạn</h4>

    <?php if (!empty($orders)): ?>
        <div class="card border-0 shadow-sm p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light small">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Người nhận</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $ord): ?>
                            <tr>
                                <td class="fw-bold text-primary">#<?= e($ord['order_code']) ?></td>
                                <td class="small text-muted"><?= format_date($ord['created_at']) ?></td>
                                <td class="small"><?= e($ord['customer_name']) ?></td>
                                <td class="fw-bold text-danger"><?= format_currency($ord['total_amount']) ?></td>
                                <td>
                                    <?php 
                                        $badge = match($ord['order_status']) {
                                            ORDER_STATUS_COMPLETED => 'bg-success',
                                            ORDER_STATUS_SHIPPING => 'bg-info text-dark',
                                            ORDER_STATUS_CANCELLED => 'bg-danger',
                                            default => 'bg-warning text-dark'
                                        };
                                    ?>
                                    <span class="badge <?= $badge ?> text-uppercase"><?= e($ord['order_status']) ?></span>
                                </td>
                                <td>
                                    <a href="<?= base_url('order/' . $ord['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-receipt-cutoff display-1 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold">Bạn chưa có đơn hàng nào!</h5>
                <p class="text-muted small">Hãy chọn sản phẩm bạn yêu thích và trải nghiệm dịch vụ của Nova Shop.</p>
                <a href="<?= base_url('products') ?>" class="btn btn-primary rounded-pill px-4 mt-2">Mua sắm ngay</a>
            </div>
        </div>
    <?php endif; ?>
</div>

