<?php
$isEdit = !empty($product);
$action = $isEdit ? base_url('admin/products/' . $product['id'] . '/update') : base_url('admin/products/store');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><?= $isEdit ? 'Chỉnh sửa sản phẩm: #' . $product['id'] : 'Thêm mới sản phẩm' ?></h3>
    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm p-4">
    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Left col -->
            <div class="col-12 col-lg-8">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= old('name', $product['name'] ?? '') ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (old('category_id', $product['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mã sản phẩm (SKU)</label>
                        <input type="text" name="sku" class="form-control" value="<?= old('sku', $product['sku'] ?? '') ?>" placeholder="Tự sinh nếu bỏ trống">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" value="<?= old('price', $product['price'] ?? '') ?>" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" name="sale_price" class="form-control" value="<?= old('sale_price', $product['sale_price'] ?? '') ?>" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Số lượng trong kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" value="<?= old('stock', $product['stock'] ?? 10) ?>" min="0" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả chi tiết</label>
                    <textarea name="description" class="form-control" rows="5"><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Thông số kỹ thuật</label>
                    <textarea name="specs" class="form-control" rows="4" placeholder="CPU: Intel Core i7 13700H&#10;RAM: 16GB DDR5&#10;SSD: 512GB NVMe&#10;VGA: RTX 4060 8GB"><?= old('specs', $product['specs'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Right col -->
            <div class="col-12 col-lg-4">
                <div class="border rounded p-3 bg-light mb-4">
                    <label class="form-label fw-semibold">Ảnh sản phẩm</label>
                    <input type="file" name="image" class="form-control mb-2" accept="image/*">
                    <small class="text-muted d-block mb-3">Chấp nhận JPG, PNG, WEBP (tối đa 5MB)</small>

                    <?php if ($isEdit && !empty($product['image'])): ?>
                        <div class="text-center p-2 border rounded bg-white">
                            <small class="d-block text-muted mb-1">Ảnh hiện tại:</small>
                            <img src="<?= upload_url($product['image']) ?>" alt="Preview" class="img-fluid" style="max-height: 160px; object-fit: contain;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="border rounded p-3 bg-light mb-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Trạng thái hiển thị</label>
                        <select name="status" class="form-select">
                            <option value="1" <?= (old('status', $product['status'] ?? 1) == 1) ? 'selected' : '' ?>>Hiển thị bán</option>
                            <option value="0" <?= (old('status', $product['status'] ?? 1) == 0) ? 'selected' : '' ?>>Ẩn / Ngừng bán</option>
                        </select>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" <?= (old('is_featured', $product['is_featured'] ?? 0) == 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="isFeatured">Đặt làm Sản phẩm nổi bật</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                    <i class="bi bi-save me-1"></i> <?= $isEdit ? 'LƯU THAY ĐỔI' : 'LƯU SẢN PHẨM' ?>
                </button>
            </div>
        </div>
    </form>
</div>

