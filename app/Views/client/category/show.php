<?php
$total = (int)($products['total'] ?? 0);
?>
<div class="page-section">
    <div class="container-site">
        <nav class="breadcrumb-wrap" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('products') ?>">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= e($category['name']) ?></li>
            </ol>
        </nav>

        <div class="listing-banner">
            <h1><?= e($category['name']) ?></h1>
            <p><?= e($category['description'] ?? 'Các dòng sản phẩm chính hãng, bảo hành toàn diện tại Nova Shop.') ?></p>
            <p class="mt-1">Tìm thấy <strong><?= $total ?></strong> sản phẩm.</p>
        </div>

        <?php \App\Core\View::partial('product-listing', [
            'products' => $products,
            'categories' => $categories ?? [],
            'filters' => $filters ?? [],
            'brandFacets' => $brandFacets ?? [],
            'priceFacets' => $priceFacets ?? [],
            'filterAction' => $filterAction ?? base_url('category/' . $category['id']),
            'hiddenFilters' => $hiddenFilters ?? [],
            'activeCategoryId' => $activeCategoryId ?? (int)$category['id'],
            'isSearch' => false,
            'keyword' => '',
        ]); ?>
    </div>
</div>
