<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Bảng điều khiển hệ thống</h3>
    <span class="badge bg-primary fs-6">Hôm nay: <?= date('d/m/Y') ?></span>
</div>

<!-- Stats counter cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 border-start border-primary border-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Tổng Sản Phẩm</span>
                    <h3 class="fw-bold my-1 text-primary"><?= $totalProducts ?></h3>
                    <a href="<?= base_url('admin/products') ?>" class="small text-decoration-none">Xem danh sách &rarr;</a>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                    <i class="bi bi-laptop fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 border-start border-success border-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Tổng Đơn Hàng</span>
                    <h3 class="fw-bold my-1 text-success"><?= $totalOrders ?></h3>
                    <a href="<?= base_url('admin/orders') ?>" class="small text-decoration-none">Xem đơn hàng &rarr;</a>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-circle">
                    <i class="bi bi-cart-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 border-start border-warning border-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Khách Hàng</span>
                    <h3 class="fw-bold my-1 text-warning"><?= $totalUsers ?></h3>
                    <a href="<?= base_url('admin/users') ?>" class="small text-decoration-none">Xem thành viên &rarr;</a>
                </div>
                <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                    <i class="bi bi-people fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 border-start border-info border-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Danh Mục</span>
                    <h3 class="fw-bold my-1 text-info"><?= $totalCategories ?></h3>
                    <a href="<?= base_url('admin/categories') ?>" class="small text-decoration-none">Xem danh mục &rarr;</a>
                </div>
                <div class="bg-info-subtle text-info p-3 rounded-circle">
                    <i class="bi bi-tags fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card border-0 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Đơn hàng mới nhất</h5>
        <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-primary btn-sm rounded-pill">Xem tất cả</a>
    </div>

    <?php if (!empty($recentOrders)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light small">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td class="fw-bold text-primary">#<?= e($order['order_code']) ?></td>
                            <td><?= e($order['customer_name']) ?></td>
                            <td><?= e($order['customer_phone']) ?></td>
                            <td class="fw-bold text-danger"><?= format_currency($order['total_amount']) ?></td>
                            <td>
                                <span class="badge bg-secondary text-uppercase"><?= e($order['order_status']) ?></span>
                            </td>
                            <td class="small text-muted"><?= format_date($order['created_at']) ?></td>
                            <td>
                                <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted small mb-0">Chưa có đơn hàng nào trong hệ thống.</p>
    <?php endif; ?>
</div>

