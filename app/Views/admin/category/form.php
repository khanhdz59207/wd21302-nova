<?php
$isEdit = !empty($category);
$action = $isEdit ? base_url('admin/categories/' . $category['id'] . '/update') : base_url('admin/categories/store');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><?= $isEdit ? 'Chỉnh sửa Danh mục: #' . $category['id'] : 'Thêm mới Danh mục' ?></h3>
    <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
    <form action="<?= $action ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $category['name'] ?? '') ?>" placeholder="Ví dụ: Laptop Gaming, Bàn phím cơ..." required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Thứ tự hiển thị</label>
            <input type="number" name="order_index" class="form-control" value="<?= old('order_index', $category['order_index'] ?? 0) ?>" min="0">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Mô tả ngắn</label>
            <textarea name="description" class="form-control" rows="3"><?= old('description', $category['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="1" <?= (old('status', $category['status'] ?? 1) == 1) ? 'selected' : '' ?>>Hiển thị</option>
                <option value="0" <?= (old('status', $category['status'] ?? 1) == 0) ? 'selected' : '' ?>>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="bi bi-save me-1"></i> <?= $isEdit ? 'CẬP NHẬT DANH MỤC' : 'TẠO DANH MỤC' ?>
        </button>
    </form>
</div>

