<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Quản lý Sản phẩm</h3>
        <small class="text-muted">Tổng cộng <?= $products['total'] ?? 0 ?> sản phẩm trong kho</small>
    </div>
    <a href="<?= base_url('admin/products/create') ?>" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm mới
    </a>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 80px;">Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Nổi bật</th>
                    <th>Trạng thái</th>
                    <th class="text-end" style="width: 140px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products['data'])): ?>
                    <?php foreach ($products['data'] as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td>
                                <img src="<?= upload_url($p['image']) ?>" alt="<?= e($p['name']) ?>" width="48" height="48" class="rounded object-fit-contain border p-1">
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?= e($p['name']) ?></div>
                                <small class="text-muted">SKU: <?= e($p['sku'] ?? 'N/A') ?></small>
                            </td>
                            <td>
                                <div class="fw-bold text-danger"><?= format_currency($p['sale_price'] ?? $p['price']) ?></div>
                                <?php if (!empty($p['sale_price'])): ?>
                                    <small class="text-muted text-decoration-line-through"><?= format_currency($p['price']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= ($p['stock'] > 5) ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $p['stock'] ?>
                                </span>
                            </td>
                            <td>
                                <?= ($p['is_featured'] == 1) ? '<span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Có</span>' : '<span class="text-muted small">Không</span>' ?>
                            </td>
                            <td>
                                <?= ($p['status'] == 1) ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('admin/products/' . $p['id'] . '/edit') ?>" class="btn btn-outline-primary btn-sm rounded-circle me-1" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?= base_url('admin/products/' . $p['id'] . '/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Chưa có sản phẩm nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php \App\Core\View::partial('pagination', ['pagination' => $products]); ?>
</div>

