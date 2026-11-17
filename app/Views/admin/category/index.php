<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Quản lý Danh mục</h3>
        <small class="text-muted">Tổng cộng <?= count($categories) ?> danh mục sản phẩm</small>
    </div>
    <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-1"></i> Thêm danh mục mới
    </a>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug URL</th>
                    <th>Số sản phẩm</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th class="text-end" style="width: 140px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td class="fw-bold text-dark"><?= e($cat['name']) ?></td>
                            <td class="text-muted small"><code><?= e($cat['slug']) ?></code></td>
                            <td>
                                <span class="badge bg-info text-dark"><?= $cat['product_count'] ?? 0 ?></span>
                            </td>
                            <td><?= $cat['order_index'] ?></td>
                            <td>
                                <?= ($cat['status'] == 1) ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('admin/categories/' . $cat['id'] . '/edit') ?>" class="btn btn-outline-primary btn-sm rounded-circle me-1" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?= base_url('admin/categories/' . $cat['id'] . '/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
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
                        <td colspan="7" class="text-center py-4 text-muted">Chưa có danh mục nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

