<div class="page-section">
    <div class="container-site">
        <nav class="breadcrumb-wrap" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
            </ol>
        </nav>

        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">
                <i class="bi bi-cart3 text-primary" aria-hidden="true"></i> Giỏ hàng của bạn
            </h1>
            <?php if (!empty($cartItems)): ?>
                <span class="text-muted small"><?= (int)$totalCount ?> sản phẩm trong giỏ</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($cartItems)): ?>
            <div class="row g-3">
                <div class="col-12 col-lg-8">
                    <div class="cart-card">
                        <div class="table-responsive">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 45%;">Sản phẩm</th>
                                        <th scope="col" class="text-center">Đơn giá</th>
                                        <th scope="col" class="text-center" style="width: 120px;">Số lượng</th>
                                        <th scope="col" class="text-end">Thành tiền</th>
                                        <th scope="col" class="text-center" style="width: 48px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td class="cart-product-cell" data-label="Sản phẩm">
                                                <div class="cart-product">
                                                    <img src="<?= upload_url($item['image']) ?>" alt="<?= e($item['name']) ?>" width="64" height="64" loading="lazy">
                                                    <div>
                                                        <a href="<?= base_url('product/' . $item['id']) ?>"><?= e($item['name']) ?></a>
                                                        <?php if (!empty($item['variant_name'])): ?>
                                                            <small class="d-block text-muted">Phân loại: <?= e($item['variant_name']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center" data-label="Đơn giá">
                                                <span class="cart-price"><?= format_currency($item['price']) ?></span>
                                            </td>
                                            <td data-label="Số lượng">
                                                <form action="<?= base_url('cart/update') ?>" method="POST" class="d-flex justify-content-center js-cart-update">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="cart_key" value="<?= e($item['cart_key'] ?? (string)$item['id']) ?>">
                                                    <input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>">
                                                    <input type="number" name="quantity" class="form-control form-control-sm text-center cart-qty-input"
                                                           value="<?= (int)$item['quantity'] ?>" min="1" max="99"
                                                           aria-label="Số lượng <?= e($item['name']) ?>"
                                                           data-auto-submit>
                                                </form>
                                            </td>
                                            <td class="text-end" data-label="Thành tiền">
                                                <span class="cart-price"><?= format_currency($item['price'] * $item['quantity']) ?></span>
                                            </td>
                                            <td class="text-center" data-label="Xóa">
                                                <form action="<?= base_url('cart/remove') ?>" method="POST" class="js-cart-remove">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="cart_key" value="<?= e($item['cart_key'] ?? (string)$item['id']) ?>">
                                                    <input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm border-0" title="Xóa sản phẩm" aria-label="Xóa <?= e($item['name']) ?> khỏi giỏ hàng" data-confirm="Xóa sản phẩm này khỏi giỏ hàng?">
                                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="cart-links">
                            <a href="<?= base_url('products') ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left" aria-hidden="true"></i> Tiếp tục mua hàng
                            </a>
                            <form action="<?= base_url('cart/clear') ?>" method="POST">
                                <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                            data-confirm="Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?">
                                        <i class="bi bi-trash3" aria-hidden="true"></i> Xóa tất cả
                                    </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <aside class="cart-summary">
                        <h2>Tóm tắt đơn hàng</h2>

                        <div class="summary-row">
                            <span>Tổng số lượng</span>
                            <strong><?= (int)$totalCount ?> sản phẩm</strong>
                        </div>
                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <strong><?= format_currency($totalPrice) ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển</span>
                            <span class="free">Miễn phí</span>
                        </div>

                        <div class="summary-total">
                            <span>Tổng thanh toán</span>
                            <span class="cart-total-price"><?= format_currency($totalPrice) ?></span>
                        </div>

                        <a href="<?= base_url('checkout') ?>" class="btn-checkout">
                            <i class="bi bi-credit-card" aria-hidden="true"></i> Tiến hành thanh toán
                        </a>

                        <p class="text-muted small mt-3 mb-0">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                            Giá đã bao gồm VAT. Miễn phí giao hàng toàn quốc.
                        </p>
                    </aside>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-cart-x" aria-hidden="true"></i>
                <h3>Giỏ hàng của bạn đang trống</h3>
                <p>Hãy chọn thêm những sản phẩm công nghệ tuyệt vời từ Nova Shop nhé.</p>
                <a href="<?= base_url('products') ?>" class="btn btn-primary">
                    <i class="bi bi-bag" aria-hidden="true"></i> Khám phá sản phẩm
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
