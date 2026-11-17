<?php
/**
 * Shared product filter form.
 *
 * @var array $filters
 * @var array $brandFacets
 * @var array $priceFacets
 * @var array $categories
 * @var string $filterAction
 * @var array $hiddenFilters
 * @var int|null $activeCategoryId
 * @var bool $isSearch
 */
$filters = $filters ?? [];
$brandFacets = $brandFacets ?? [];
$priceFacets = $priceFacets ?? [];
$categories = $categories ?? [];
$filterAction = $filterAction ?? base_url('products');
$hiddenFilters = $hiddenFilters ?? [];
$activeCategoryId = $activeCategoryId ?? null;
$isSearch = $isSearch ?? false;

$selectedBrands = $filters['brands'] ?? [];
$selectedPrice = (string)($filters['price'] ?? '');
$priceRanges = \App\Models\Product::PRICE_RANGES;

$clearQuery = $hiddenFilters;
$clearUrl = $filterAction . (!empty($clearQuery) ? '?' . http_build_query($clearQuery) : '');

$categoryQuery = $_GET;
unset($categoryQuery['page'], $categoryQuery['q']);
$categoryQueryString = !empty($categoryQuery) ? '?' . http_build_query($categoryQuery) : '';
$allProductsUrl = base_url('products') . $categoryQueryString;
$allProductsActive = $activeCategoryId === null && !$isSearch;
?>
<form method="GET" action="<?= e($filterAction) ?>" class="filter-form">
    <?php foreach ($hiddenFilters as $name => $value): ?>
        <?php if (is_array($value)): ?>
            <?php foreach ($value as $item): ?>
                <input type="hidden" name="<?= e((string)$name) ?>[]" value="<?= e((string)$item) ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <input type="hidden" name="<?= e((string)$name) ?>" value="<?= e((string)$value) ?>">
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="filter-section">
        <h2 class="filter-title">Danh mục sản phẩm</h2>
        <a href="<?= e($allProductsUrl) ?>" class="filter-cat-link <?= $allProductsActive ? 'is-active' : '' ?>">
            Tất cả sản phẩm
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= e(base_url('category/' . $cat['id']) . $categoryQueryString) ?>"
               class="filter-cat-link <?= ((int)$cat['id'] === (int)$activeCategoryId) ? 'is-active' : '' ?>">
                <?= e($cat['name']) ?>
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="filter-section">
        <h2 class="filter-title">Thương hiệu</h2>
        <?php if (!empty($brandFacets)): ?>
            <div class="filter-scroll">
                <?php foreach ($brandFacets as $brand): ?>
                    <?php $brandName = (string)($brand['brand'] ?? ''); ?>
                    <?php if ($brandName === '') continue; ?>
                    <label class="filter-option">
                        <input type="checkbox" name="brand[]" value="<?= e($brandName) ?>"
                            <?= in_array($brandName, $selectedBrands, true) ? 'checked' : '' ?>>
                        <span class="filter-option-label"><?= e($brandName) ?></span>
                        <span class="filter-count"><?= (int)($brand['total'] ?? 0) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="filter-empty">Không có thương hiệu phù hợp.</p>
        <?php endif; ?>
    </div>

    <div class="filter-section">
        <h2 class="filter-title">Khoảng giá</h2>
        <label class="filter-option">
            <input type="radio" name="price" value="all" <?= $selectedPrice === '' ? 'checked' : '' ?>>
            <span class="filter-option-label">Tất cả</span>
        </label>
        <?php foreach ($priceRanges as $key => $range): ?>
            <label class="filter-option">
                <input type="radio" name="price" value="<?= e((string)$key) ?>"
                    <?= $selectedPrice === (string)$key ? 'checked' : '' ?>>
                <span class="filter-option-label"><?= e($range['label']) ?></span>
                <?php if (isset($priceFacets[$key])): ?>
                    <span class="filter-count"><?= (int)$priceFacets[$key] ?></span>
                <?php endif; ?>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="filter-section">
        <h2 class="filter-title">Tình trạng</h2>
        <label class="filter-option">
            <input type="checkbox" name="stock" value="1" <?= !empty($filters['stock']) ? 'checked' : '' ?>>
            <span class="filter-option-label">Còn hàng</span>
        </label>
        <label class="filter-option">
            <input type="checkbox" name="sale" value="1" <?= !empty($filters['sale']) ? 'checked' : '' ?>>
            <span class="filter-option-label">Đang giảm giá</span>
        </label>
    </div>

    <div class="filter-actions">
        <button type="submit" class="btn btn-primary w-100">Áp dụng bộ lọc</button>
        <a href="<?= e($clearUrl) ?>" class="btn btn-link w-100">Xóa bộ lọc</a>
    </div>
</form>
