<?php
$reviews = $reviews ?? [];
$galleryImages = $galleryImages ?? [];
$specs = $specs ?? [];
$highlights = $highlights ?? [];
$variants = $variants ?? [];
$relatedProducts = $relatedProducts ?? [];

$basePrice = (float)$product['price'];
$baseSalePrice = (!empty($product['sale_price']) && (float)$product['sale_price'] > 0 && (float)$product['sale_price'] < $basePrice)
    ? (float)$product['sale_price']
    : 0.0;
$stock = (int)($product['stock'] ?? 0);
$inStock = $stock > 0;
$maxQty = max(1, $stock);
$warranty = trim((string)($product['warranty'] ?? '')) ?: 'chính hãng';

$rating = (float)($product['rating'] ?? 0);
$ratingCount = max((int)($product['rating_count'] ?? 0), count($reviews));
if ($rating <= 0 && $reviews) {
    $ratingSum = 0;
    foreach ($reviews as $review) {
        $ratingSum += max(0, min(5, (int)($review['rating'] ?? 0)));
    }
    $rating = round($ratingSum / count($reviews), 1);
}

if (empty($specs) && !empty($product['specs'])) {
    $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string)$product['specs']))));
    foreach ($lines as $line) {
        $parts = preg_split('/\s*[:：]\s*/', $line, 2);
        if (count($parts) === 2 && $parts[1] !== '') {
            $specs[] = ['label' => $parts[0], 'value' => $parts[1]];
        } else {
            $specs[] = ['label' => '', 'value' => $line];
        }
    }
}
$quickSpecs = array_slice($specs, 0, 4);
$specCount = count($specs);

$highlights = array_values(array_filter(array_map(
    static fn(array $item): string => trim((string)($item['content'] ?? '')),
    $highlights
)));

$promotions = [];
if (!empty($product['promotions'])) {
    $decodedPromotions = json_decode((string)$product['promotions'], true);
    if (is_array($decodedPromotions)) {
        foreach ($decodedPromotions as $promotion) {
            if (is_string($promotion) && trim($promotion) !== '') {
                $promotions[] = trim($promotion);
            }
        }
    }
}

$selectedVariant = null;
foreach ($variants as $variant) {
    if (!empty($product['sku']) && (string)($variant['sku'] ?? '') === (string)$product['sku']) {
        $selectedVariant = $variant;
        break;
    }
}
if (!$selectedVariant && $variants) {
    $selectedVariant = $variants[0];
}
$selectedVariantPrice = $selectedVariant && (float)($selectedVariant['price'] ?? 0) > 0
    ? (float)$selectedVariant['price']
    : 0.0;
$displayPrice = $selectedVariantPrice > 0
    ? $selectedVariantPrice
    : ($baseSalePrice > 0 ? $baseSalePrice : $basePrice);
$displayOldPrice = $selectedVariantPrice > 0
    ? ($selectedVariantPrice < $basePrice ? $basePrice : 0.0)
    : ($baseSalePrice > 0 ? $basePrice : 0.0);
$discount = $displayOldPrice > 0 ? calculate_discount_percent($displayOldPrice, $displayPrice) : 0;
$initialSku = $selectedVariant && !empty($selectedVariant['sku'])
    ? (string)$selectedVariant['sku']
    : (string)($product['sku'] ?? ('NOVA-' . $product['id']));

if (empty($galleryImages)) {
    $galleryImages = [[
        'image' => $product['image'],
        'is_main' => 1,
        'sort_order' => 0,
    ]];
}
$galleryCount = count($galleryImages);
$fallbackImage = upload_url(null);

$descriptionHtml = !empty($product['description_html'])
    ? sanitize_product_html((string)$product['description_html'])
    : '';
$descriptionFallback = nl2br(e($product['description'] ?? 'Đang cập nhật mô tả chi tiết cho sản phẩm này.'));
$categoryUrl = base_url('category/' . $product['category_id']);
?>
<div class="page-section">
    <div class="container-site">
        <nav class="breadcrumb-wrap" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('products') ?>">Sản phẩm</a></li>
                <?php if (!empty($product['category_name'])): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= $categoryUrl ?>"><?= e($product['category_name']) ?></a>
                    </li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-truncate" style="max-width: 320px;" aria-current="page"><?= e($product['name']) ?></li>
            </ol>
        </nav>

        <div class="product-detail-wrap mb-3">
            <div class="row g-4">
                <div class="col-12 col-md-5">
                    <div class="product-gallery" data-gallery>
                        <div class="main-img-wrap">
                            <button type="button" class="main-img-button" data-gallery-open aria-label="Xem ảnh lớn của <?= e($product['name']) ?>">
                                <img src="<?= upload_url($galleryImages[0]['image']) ?>"
                                     alt="<?= e($product['name']) ?>"
                                     class="main-img"
                                     data-gallery-main
                                     data-gallery-index="0"
                                     data-fallback="<?= e($fallbackImage) ?>"
                                     width="480" height="340"
                                     fetchpriority="high">
                            </button>
                            <span class="gallery-count" data-gallery-count>1 / <?= $galleryCount ?></span>
                            <span class="gallery-hint" aria-hidden="true">
                                <i class="bi bi-arrows-fullscreen"></i> Xem ảnh lớn
                            </span>
                        </div>

                        <?php if ($galleryCount > 1): ?>
                            <div class="detail-thumbs" data-gallery-thumbs role="list" aria-label="Ảnh sản phẩm">
                                <?php foreach ($galleryImages as $index => $image): ?>
                                    <button type="button"
                                            class="detail-thumb <?= $index === 0 ? 'active' : '' ?>"
                                            data-gallery-src="<?= upload_url($image['image']) ?>"
                                            data-gallery-alt="<?= e($product['name'] . ' - ảnh ' . ($index + 1)) ?>"
                                            data-gallery-index="<?= $index ?>"
                                            aria-label="Xem ảnh <?= $index + 1 ?> của <?= e($product['name']) ?>"
                                            aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>">
                                        <img src="<?= upload_url($image['image']) ?>"
                                             alt=""
                                             width="72" height="56"
                                             loading="lazy"
                                             data-fallback="<?= e($fallbackImage) ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12 col-md-7">
                    <h1 class="product-detail-name"><?= e($product['name']) ?></h1>

                    <div class="detail-meta">
                        <span>Mã SP: <strong data-detail-sku><?= e($initialSku) ?></strong></span>
                        <?php if (!empty($product['brand'])): ?>
                            <span>Thương hiệu: <strong><?= e($product['brand']) ?></strong></span>
                        <?php endif; ?>
                        <span>Lượt xem: <strong><?= (int)($product['views'] ?? 0) ?></strong></span>
                        <span class="<?= $inStock ? 'text-success' : 'text-danger' ?>">
                            <i class="bi <?= $inStock ? 'bi-check-circle-fill' : 'bi-x-circle' ?>" aria-hidden="true"></i>
                            <?= $inStock ? 'Còn hàng (' . $stock . ')' : 'Tạm hết hàng' ?>
                        </span>
                    </div>

                    <div class="detail-rating-row">
                        <span class="detail-stars" aria-label="Đánh giá <?= number_format($rating, 1, ',', '.') ?> trên 5 sao">
                            <?php for ($star = 1; $star <= 5; $star++): ?>
                                <?php if ($rating >= $star): ?>
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                <?php elseif ($rating >= $star - 0.5): ?>
                                    <i class="bi bi-star-half" aria-hidden="true"></i>
                                <?php else: ?>
                                    <i class="bi bi-star" aria-hidden="true"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </span>
                        <span class="detail-rating-text">
                            <?php if ($ratingCount > 0): ?>
                                <?= number_format($rating, 1, ',', '.') ?> · <?= $ratingCount ?> đánh giá
                            <?php else: ?>
                                Chưa có đánh giá
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="detail-price-block"
                         data-price-block
                         data-base-price="<?= $basePrice ?>"
                         data-base-sale="<?= $baseSalePrice ?>">
                        <span class="detail-price-sale" data-price-current><?= format_currency($displayPrice) ?></span>
                        <span class="detail-price-old" data-price-old <?= $displayOldPrice > 0 ? '' : 'hidden' ?>><?= format_currency($displayOldPrice) ?></span>
                        <span class="detail-badge-sale" data-price-discount <?= $discount > 0 ? '' : 'hidden' ?>>-<?= $discount ?>%</span>
                    </div>
                    <p class="detail-vat">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        Giá đã bao gồm VAT &amp; bảo hành <?= e($warranty) ?>
                    </p>

                    <?php if ($promotions): ?>
                        <div class="detail-promotions">
                            <div class="detail-promotions-head">
                                <i class="bi bi-gift-fill" aria-hidden="true"></i>
                                Ưu đãi khi mua
                            </div>
                            <ul>
                                <?php foreach ($promotions as $promotion): ?>
                                    <li><?= e($promotion) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($quickSpecs): ?>
                        <ul class="spec-quick-list">
                            <?php foreach ($quickSpecs as $spec): ?>
                                <li>
                                    <?php if ($spec['label'] !== ''): ?>
                                        <span><?= e($spec['label']) ?>:</span>
                                    <?php endif; ?>
                                    <?= e($spec['value']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <form action="<?= base_url('cart/add') ?>" method="POST" data-detail-form>
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

                        <?php if ($variants): ?>
                            <div class="detail-variants" data-variants>
                                <div class="detail-field-label">Chọn phiên bản</div>
                                <div class="variant-options" role="radiogroup" aria-label="Chọn phiên bản sản phẩm">
                                    <?php foreach ($variants as $variant): ?>
                                        <label class="variant-option">
                                            <input type="radio"
                                                   name="variant_id"
                                                   value="<?= (int)$variant['id'] ?>"
                                                   data-variant-name="<?= e($variant['name']) ?>"
                                                   data-variant-sku="<?= e($variant['sku'] ?? '') ?>"
                                                   data-variant-price="<?= (float)($variant['price'] ?? 0) > 0 ? (float)$variant['price'] : '' ?>"
                                                   <?= $selectedVariant && (int)$selectedVariant['id'] === (int)$variant['id'] ? 'checked' : '' ?>>
                                            <span class="variant-option-body">
                                                <span class="variant-option-name"><?= e($variant['name']) ?></span>
                                                <?php if (!empty($variant['sku'])): ?>
                                                    <span class="variant-option-sku">SKU <?= e($variant['sku']) ?></span>
                                                <?php endif; ?>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="detail-actions">
                            <div class="qty-control" data-qty>
                                <button type="button" class="qty-btn" data-qty-minus aria-label="Giảm số lượng" <?= $inStock ? '' : 'disabled' ?>>&minus;</button>
                                <input type="number" name="quantity" class="qty-input" value="1"
                                       min="1" max="<?= $maxQty ?>" aria-label="Số lượng" <?= $inStock ? '' : 'disabled' ?>>
                                <button type="button" class="qty-btn" data-qty-plus aria-label="Tăng số lượng" <?= $inStock ? '' : 'disabled' ?>>+</button>
                            </div>
                            <button type="submit" class="btn-buy-now" <?= $inStock ? '' : 'disabled' ?>>
                                <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i> Mua ngay
                            </button>
                            <button type="submit" class="btn-add-cart-detail" data-add-cart <?= $inStock ? '' : 'disabled' ?>>
                                <i class="bi bi-cart-plus" aria-hidden="true"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </form>

                    <div class="detail-policies">
                        <div class="policy-item">
                            <i class="bi bi-truck" aria-hidden="true"></i>
                            Miễn phí giao hàng toàn quốc cho đơn từ 500.000₫
                        </div>
                        <div class="policy-item">
                            <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                            Đổi trả 1:1 trong 30 ngày nếu lỗi nhà sản xuất
                        </div>
                        <div class="policy-item">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                            Bảo hành <?= e($warranty) ?>, hỗ trợ tận nơi
                        </div>
                        <div class="policy-item">
                            <i class="bi bi-credit-card" aria-hidden="true"></i>
                            Trả góp 0% qua thẻ tín dụng và công ty tài chính
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 detail-content-row">
            <div class="col-12 <?= $highlights ? 'col-lg-8' : '' ?>">
                <section class="detail-card detail-tabs-card">
                    <div class="detail-tabs" role="tablist" aria-label="Thông tin sản phẩm">
                        <button type="button" class="detail-tab-btn active" id="detail-tab-btn-description" data-detail-tab="description" role="tab" aria-selected="true" aria-controls="detail-tab-description" tabindex="0">
                            Mô tả sản phẩm
                        </button>
                        <button type="button" class="detail-tab-btn" id="detail-tab-btn-specs" data-detail-tab="specs" role="tab" aria-selected="false" aria-controls="detail-tab-specs" tabindex="-1">
                            Thông số kỹ thuật
                        </button>
                        <button type="button" class="detail-tab-btn" id="detail-tab-btn-reviews" data-detail-tab="reviews" role="tab" aria-selected="false" aria-controls="detail-tab-reviews" tabindex="-1">
                            Đánh giá (<?= count($reviews) ?>)
                        </button>
                    </div>

                    <div class="detail-tab-panels">
                        <div class="detail-tab-panel" id="detail-tab-description" data-detail-panel="description" role="tabpanel" aria-labelledby="detail-tab-btn-description" tabindex="0">
                            <?php if ($descriptionHtml !== ''): ?>
                                <div class="product-description"><?= $descriptionHtml ?></div>
                            <?php else: ?>
                                <div class="product-description is-plain"><?= $descriptionFallback ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="detail-tab-panel" id="detail-tab-specs" data-detail-panel="specs" role="tabpanel" aria-labelledby="detail-tab-btn-specs" tabindex="0" hidden>
                            <?php if ($specs): ?>
                                <div class="spec-table-wrap">
                                    <table class="spec-table">
                                        <tbody>
                                            <?php foreach ($specs as $spec): ?>
                                                <tr>
                                                    <?php if ($spec['label'] !== ''): ?>
                                                        <th scope="row"><?= e($spec['label']) ?></th>
                                                        <td><?= nl2br(e($spec['value'])) ?></td>
                                                    <?php else: ?>
                                                        <td colspan="2"><?= nl2br(e($spec['value'])) ?></td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="content mb-0">Đang cập nhật thông số chi tiết.</p>
                            <?php endif; ?>
                        </div>

                        <div class="detail-tab-panel" id="detail-tab-reviews" data-detail-panel="reviews" role="tabpanel" aria-labelledby="detail-tab-btn-reviews" tabindex="0" hidden>
                            <div class="review-summary">
                                <div class="review-score">
                                    <strong><?= number_format($rating, 1, ',', '.') ?></strong>
                                    <span>/ 5</span>
                                </div>
                                <div>
                                    <div class="detail-stars" aria-hidden="true">
                                        <?php for ($star = 1; $star <= 5; $star++): ?>
                                            <?php if ($rating >= $star): ?>
                                                <i class="bi bi-star-fill"></i>
                                            <?php elseif ($rating >= $star - 0.5): ?>
                                                <i class="bi bi-star-half"></i>
                                            <?php else: ?>
                                                <i class="bi bi-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="review-summary-text"><?= $ratingCount > 0 ? $ratingCount . ' đánh giá từ khách hàng' : 'Chưa có đánh giá nào' ?></span>
                                </div>
                            </div>

                            <?php if ($reviews): ?>
                                <?php foreach ($reviews as $review): ?>
                                    <div class="review-item">
                                        <div class="review-head">
                                            <strong><?= e($review['user_name'] ?? 'Khách hàng') ?></strong>
                                            <span class="review-stars" aria-label="<?= (int)$review['rating'] ?> trên 5 sao">
                                                <?= str_repeat('★', max(0, (int)$review['rating'])) . str_repeat('☆', max(0, 5 - (int)$review['rating'])) ?>
                                            </span>
                                        </div>
                                        <p class="review-comment"><?= e($review['comment']) ?></p>
                                        <span class="review-date"><?= format_date($review['created_at'] ?? null) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="content mb-0">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên trải nghiệm!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>

            <?php if ($highlights): ?>
                <div class="col-12 col-lg-4">
                    <section class="detail-card detail-highlights">
                        <h2>Điểm nổi bật</h2>
                        <ul class="highlight-list">
                            <?php foreach ($highlights as $highlight): ?>
                                <li>
                                    <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                    <span><?= e($highlight) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($relatedProducts): ?>
            <section class="detail-related-section">
                <div class="section-heading">
                    <h2 class="section-title">Sản phẩm cùng danh mục</h2>
                    <a href="<?= $categoryUrl ?>" class="detail-related-link">
                        Xem tất cả <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="row g-3 row-cols-2 row-cols-md-4">
                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="col">
                            <?php \App\Core\View::partial('product-card', ['product' => $related]); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</div>

<div class="gallery-lightbox <?= $galleryCount < 2 ? 'is-single' : '' ?>"
     data-gallery-lightbox
     role="dialog"
     aria-modal="true"
     aria-label="Xem ảnh sản phẩm"
     aria-hidden="true"
     hidden>
    <button type="button" class="lightbox-close" data-lightbox-close aria-label="Đóng ảnh lớn">
        <i class="bi bi-x-lg" aria-hidden="true"></i>
    </button>
    <button type="button" class="lightbox-nav is-prev" data-lightbox-prev aria-label="Ảnh trước">
        <i class="bi bi-chevron-left" aria-hidden="true"></i>
    </button>
    <div class="lightbox-stage" data-lightbox-stage>
        <img src="<?= upload_url($galleryImages[0]['image']) ?>"
             alt="<?= e($product['name']) ?>"
             data-lightbox-img
             data-fallback="<?= e($fallbackImage) ?>">
    </div>
    <button type="button" class="lightbox-nav is-next" data-lightbox-next aria-label="Ảnh sau">
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
    </button>
    <div class="lightbox-counter" data-lightbox-counter aria-live="polite">1 / <?= $galleryCount ?></div>
</div>
