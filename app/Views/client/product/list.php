<?php
$total = (int)($products['total'] ?? 0);
$keyword = $keyword ?? '';
$isSearch = !empty($keyword);
$pageTitle = $isSearch ? 'Kết quả tìm kiếm' : 'Tất cả sản phẩm';
?>
<div class="page-section">
    <div class="container-site">
        <nav class="breadcrumb-wrap" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('products') ?>">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= e($pageTitle) ?></li>
            </ol>
        </nav>

        <?php if ($isSearch): ?>
            <div class="listing-banner">
                <h1>Kết quả tìm kiếm cho &ldquo;<?= e($keyword) ?>&rdquo;</h1>
                <p>Tìm thấy <strong><?= $total ?></strong> sản phẩm phù hợp.</p>
            </div>
        <?php endif; ?>

        <?php \App\Core\View::partial('product-listing', [
            'products' => $products,
            'categories' => $categories ?? [],
            'filters' => $filters ?? [],
            'brandFacets' => $brandFacets ?? [],
            'priceFacets' => $priceFacets ?? [],
            'filterAction' => $filterAction ?? base_url('products'),
            'hiddenFilters' => $hiddenFilters ?? [],
            'activeCategoryId' => $activeCategoryId ?? null,
            'isSearch' => $isSearch,
            'keyword' => $keyword,
        ]); ?>
    </div>
</div>
