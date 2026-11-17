<?php
/**
 * Product card (phongvu.vn style)
 * @var array $product
 */
$hasSale = !empty($product['sale_price']) && (float)$product['sale_price'] > 0 && (float)$product['sale_price'] < (float)$product['price'];
$displayPrice = $hasSale ? $product['sale_price'] : $product['price'];
$discount = $hasSale ? calculate_discount_percent((float)$product['price'], (float)$product['sale_price']) : 0;
$productUrl = base_url('product/' . $product['id']);
$inStock = (int)($product['stock'] ?? 0) > 0;
$rating = max(0, min(5, (float)($product['rating'] ?? 0)));
$ratingCount = max(0, (int)($product['rating_count'] ?? 0));
$fullStars = (int)round($rating);

$specLine = '';
if (!empty($product['specs'])) {
    $specLines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string)$product['specs']))));
    $specLine = $specLines[0] ?? '';
}
?>
<article class="product-card" data-price="<?= (float)$displayPrice ?>" data-name="<?= e($product['name']) ?>">
    <div class="product-badges">
        <?php if ($discount > 0): ?>
            <span class="badge-sale">-<?= $discount ?>%</span>
        <?php endif; ?>
        <?php if (!empty($product['is_featured'])): ?>
            <span class="badge-hot">HOT</span>
        <?php endif; ?>
    </div>

    <a href="<?= $productUrl ?>" class="product-img-link" tabindex="-1" aria-hidden="true">
        <div class="product-img-wrap">
            <img src="<?= upload_url($product['image']) ?>"
                 alt="<?= e($product['name']) ?>"
                 class="product-img"
                 width="300" height="220"
                 loading="lazy" decoding="async">
        </div>
    </a>

    <div class="product-info">
        <a href="<?= $productUrl ?>" class="product-name"><?= e(truncate_text($product['name'], 70)) ?></a>

        <?php if ($specLine !== ''): ?>
            <p class="product-specs"><?= e(truncate_text($specLine, 46)) ?></p>
        <?php endif; ?>

        <div class="product-price-block">
            <span class="price-sale"><?= format_currency($displayPrice) ?></span>
            <?php if ($hasSale): ?>
                <span class="price-old"><?= format_currency($product['price']) ?></span>
            <?php endif; ?>
        </div>

        <span class="product-installment">
            <i class="bi bi-credit-card" aria-hidden="true"></i> Trả góp 0%
        </span>

        <div class="product-rating">
            <?php if ($rating > 0): ?>
                <span class="product-stars" aria-label="Đánh giá <?= number_format($rating, 1, ',', '.') ?> trên 5 sao">
                    <?php for ($star = 1; $star <= 5; $star++): ?>
                        <i class="bi <?= $star <= $fullStars ? 'bi-star-fill' : 'bi-star' ?>" aria-hidden="true"></i>
                    <?php endfor; ?>
                </span>
                <?php if ($ratingCount > 0): ?>
                    <span class="product-rating-count">(<?= $ratingCount ?>)</span>
                <?php endif; ?>
            <?php else: ?>
                <span class="product-no-rating">Chưa có đánh giá</span>
            <?php endif; ?>
            <?php if ($inStock): ?>
                <span class="product-stock"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Còn hàng</span>
            <?php else: ?>
                <span class="product-stock is-out"><i class="bi bi-x-circle" aria-hidden="true"></i> Tạm hết hàng</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="product-action">
        <form action="<?= base_url('cart/add') ?>" method="POST" class="js-ajax-cart">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn-add-cart" aria-label="Thêm <?= e($product['name']) ?> vào giỏ hàng">
                <i class="bi bi-bag-plus" aria-hidden="true"></i> Thêm vào giỏ
            </button>
        </form>
    </div>
</article>
