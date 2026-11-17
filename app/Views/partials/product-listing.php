<?php
/**
 * Shared product listing: desktop sidebar, mobile filter drawer, sort, chips and grid.
 *
 * @var array $products
 * @var array $categories
 * @var array $filters
 * @var array $brandFacets
 * @var array $priceFacets
 * @var string $filterAction
 * @var array $hiddenFilters
 * @var int|null $activeCategoryId
 * @var bool $isSearch
 * @var string $keyword
 */
$products = $products ?? ['data' => [], 'total' => 0, 'current_page' => 1, 'total_pages' => 0];
$categories = $categories ?? [];
$filters = $filters ?? [];
$brandFacets = $brandFacets ?? [];
$priceFacets = $priceFacets ?? [];
$filterAction = $filterAction ?? base_url('products');
$hiddenFilters = $hiddenFilters ?? [];
$activeCategoryId = $activeCategoryId ?? null;
$isSearch = $isSearch ?? false;
$keyword = $keyword ?? '';

$total = (int)($products['total'] ?? 0);
$selectedBrands = $filters['brands'] ?? [];
$selectedPrice = (string)($filters['price'] ?? '');
$hasStockFilter = !empty($filters['stock']);
$hasSaleFilter = !empty($filters['sale']);
$hasFilters = !empty($selectedBrands)
    || $selectedPrice !== ''
    || $hasStockFilter
    || $hasSaleFilter;
$activeFilterCount = count($selectedBrands)
    + ($selectedPrice !== '' ? 1 : 0)
    + ($hasStockFilter ? 1 : 0)
    + ($hasSaleFilter ? 1 : 0);

$sortOptions = \App\Models\Product::SORT_OPTIONS;
$priceRanges = \App\Models\Product::PRICE_RANGES;
$currentQuery = $_GET;
unset($currentQuery['page']);

$buildUrl = static function (array $query) use ($filterAction): string {
    return $filterAction . (!empty($query) ? '?' . http_build_query($query) : '');
};
$removeFilterUrl = static function (string $key) use ($currentQuery, $buildUrl): string {
    $query = $currentQuery;
    unset($query[$key]);
    return $buildUrl($query);
};
$removeBrandUrl = static function (string $brand) use ($currentQuery, $buildUrl): string {
    $query = $currentQuery;
    $brands = $query['brand'] ?? [];
    if (!is_array($brands)) {
        $brands = [$brands];
    }
    $brands = array_values(array_filter(
        array_map('strval', $brands),
        static fn(string $item): bool => $item !== $brand
    ));
    if ($brands === []) {
        unset($query['brand']);
    } else {
        $query['brand'] = $brands;
    }
    return $buildUrl($query);
};
$clearUrl = $buildUrl($hiddenFilters);
$filterDrawerId = 'productFilterDrawer';
?>
<div class="row g-3 listing-layout">
    <aside class="col-lg-3 d-none d-lg-block">
        <div class="filter-sidebar">
            <?php \App\Core\View::partial('product-filters', [
                'filters' => $filters,
                'brandFacets' => $brandFacets,
                'priceFacets' => $priceFacets,
                'categories' => $categories,
                'filterAction' => $filterAction,
                'hiddenFilters' => $hiddenFilters,
                'activeCategoryId' => $activeCategoryId,
                'isSearch' => $isSearch,
            ]); ?>
        </div>
    </aside>

    <div class="col-12 col-lg-9">
        <div class="sort-bar">
            <button type="button" class="btn btn-outline-secondary btn-filter-mobile d-lg-none"
                    data-bs-toggle="offcanvas" data-bs-target="#<?= e($filterDrawerId) ?>"
                    aria-controls="<?= e($filterDrawerId) ?>">
                <i class="bi bi-sliders" aria-hidden="true"></i>
                Bộ lọc
                <?php if ($activeFilterCount > 0): ?>
                    <span class="filter-count-badge"><?= $activeFilterCount ?></span>
                <?php endif; ?>
            </button>

            <form method="GET" action="<?= e($filterAction) ?>" class="sort-form">
                <?php foreach ($hiddenFilters as $name => $value): ?>
                    <?php if (is_array($value)): ?>
                        <?php foreach ($value as $item): ?>
                            <input type="hidden" name="<?= e((string)$name) ?>[]" value="<?= e((string)$item) ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <input type="hidden" name="<?= e((string)$name) ?>" value="<?= e((string)$value) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php foreach ($selectedBrands as $brand): ?>
                    <input type="hidden" name="brand[]" value="<?= e($brand) ?>">
                <?php endforeach; ?>
                <?php if ($selectedPrice !== ''): ?>
                    <input type="hidden" name="price" value="<?= e($selectedPrice) ?>">
                <?php endif; ?>
                <?php if ($hasStockFilter): ?>
                    <input type="hidden" name="stock" value="1">
                <?php endif; ?>
                <?php if ($hasSaleFilter): ?>
                    <input type="hidden" name="sale" value="1">
                <?php endif; ?>

                <label class="sort-label" for="sort-select">Sắp xếp:</label>
                <select id="sort-select" name="sort" class="form-select form-select-sm sort-select" data-auto-submit>
                    <?php foreach ($sortOptions as $value => $label): ?>
                        <option value="<?= e((string)$value) ?>" <?= ($filters['sort'] ?? 'popular') === $value ? 'selected' : '' ?>>
                            <?= e($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <noscript><button type="submit" class="btn btn-sm btn-outline-secondary">Sắp xếp</button></noscript>
            </form>

            <span class="result-count"><?= $total ?> sản phẩm</span>
        </div>

        <?php if ($hasFilters): ?>
            <div class="active-filters" aria-label="Bộ lọc đang áp dụng">
                <span class="active-filters-label">Đang lọc:</span>

                <?php foreach ($selectedBrands as $brand): ?>
                    <a class="filter-chip" href="<?= e($removeBrandUrl($brand)) ?>">
                        <?= e($brand) ?>
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        <span class="visually-hidden">Xóa thương hiệu <?= e($brand) ?></span>
                    </a>
                <?php endforeach; ?>

                <?php if ($selectedPrice !== '' && isset($priceRanges[$selectedPrice])): ?>
                    <a class="filter-chip" href="<?= e($removeFilterUrl('price')) ?>">
                        <?= e($priceRanges[$selectedPrice]['label']) ?>
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        <span class="visually-hidden">Xóa khoảng giá</span>
                    </a>
                <?php endif; ?>

                <?php if ($hasStockFilter): ?>
                    <a class="filter-chip" href="<?= e($removeFilterUrl('stock')) ?>">
                        Còn hàng
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        <span class="visually-hidden">Xóa lọc còn hàng</span>
                    </a>
                <?php endif; ?>

                <?php if ($hasSaleFilter): ?>
                    <a class="filter-chip" href="<?= e($removeFilterUrl('sale')) ?>">
                        Đang giảm giá
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        <span class="visually-hidden">Xóa lọc đang giảm giá</span>
                    </a>
                <?php endif; ?>

                <a class="clear-all" href="<?= e($clearUrl) ?>">Xóa tất cả</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($products['data'])): ?>
            <div class="row g-3 row-cols-2 row-cols-md-3" id="product-grid">
                <?php foreach ($products['data'] as $product): ?>
                    <div class="col">
                        <?php \App\Core\View::partial('product-card', ['product' => $product]); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php \App\Core\View::partial('pagination', ['pagination' => $products]); ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-search" aria-hidden="true"></i>
                <h3>Không có sản phẩm phù hợp</h3>
                <p>
                    <?php if ($hasFilters): ?>
                        Thử bỏ một vài bộ lọc để xem thêm sản phẩm.
                    <?php elseif ($isSearch): ?>
                        Không có kết quả cho từ khoá này. Hãy thử từ khoá khác.
                    <?php else: ?>
                        Danh mục sản phẩm hiện đang được cập nhật.
                    <?php endif; ?>
                </p>
                <?php if ($hasFilters): ?>
                    <a href="<?= e($clearUrl) ?>" class="btn btn-primary">Xóa bộ lọc</a>
                <?php else: ?>
                    <a href="<?= base_url('products') ?>" class="btn btn-primary">Xem tất cả sản phẩm</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="offcanvas offcanvas-start filter-drawer" tabindex="-1" id="<?= e($filterDrawerId) ?>"
     aria-labelledby="<?= e($filterDrawerId) ?>Label">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="<?= e($filterDrawerId) ?>Label">Bộ lọc sản phẩm</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
    </div>
    <div class="offcanvas-body">
        <?php \App\Core\View::partial('product-filters', [
            'filters' => $filters,
            'brandFacets' => $brandFacets,
            'priceFacets' => $priceFacets,
            'categories' => $categories,
            'filterAction' => $filterAction,
            'hiddenFilters' => $hiddenFilters,
            'activeCategoryId' => $activeCategoryId,
            'isSearch' => $isSearch,
        ]); ?>
    </div>
</div>
