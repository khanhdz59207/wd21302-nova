<?php
$categoryIcons = [
    'laptop-macbook' => 'bi-laptop',
    'pc-may-tinh-de-ban' => 'bi-pc-display',
    'linh-kien-may-tinh' => 'bi-cpu',
    'man-hinh-may-tinh' => 'bi-display',
    'ban-phim-chuot' => 'bi-keyboard',
    'ban-phim' => 'bi-keyboard',
    'chuot' => 'bi-mouse',
    'tai-nghe-loa' => 'bi-headphones',
    'mang-camera' => 'bi-router',
    'may-in-van-phong' => 'bi-printer',
    'phu-kien' => 'bi-plug',
];

$categories = $categories ?? [];
$featuredProducts = $featuredProducts ?? [];
$latestProducts = $latestProducts ?? [];

$isSale = static function (array $p): bool {
    return !empty($p['sale_price'])
        && (float)$p['sale_price'] > 0
        && (float)$p['sale_price'] < (float)$p['price'];
};

$saleProducts = array_values(array_filter($featuredProducts, $isSale));
if (count($saleProducts) < 4) {
    $seen = array_column($saleProducts, 'id');
    foreach ($latestProducts as $p) {
        if ($isSale($p) && !in_array($p['id'], $seen, true)) {
            $saleProducts[] = $p;
            $seen[] = $p['id'];
        }
        if (count($saleProducts) >= 8) {
            break;
        }
    }
}
if ($saleProducts === []) {
    $saleProducts = $featuredProducts;
}
$saleProducts = array_slice($saleProducts, 0, 10);
$featuredProducts = array_slice($featuredProducts, 0, 10);
$latestProducts = array_slice($latestProducts, 0, 10);
?>

<!-- Hero: category sidebar + banner carousel -->
<section class="hero-section">
    <div class="container-site">
        <div class="hero-row">
            <aside class="category-sidebar d-none d-lg-block" aria-label="Danh mục nhanh">
                <ul class="sidebar-menu">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="<?= base_url('category/' . $category['id']) ?>">
                                <i class="bi <?= $categoryIcons[$category['slug']] ?? 'bi-grid' ?>" aria-hidden="true"></i>
                                <span class="sidebar-label"><?= e($category['name']) ?></span>
                                <i class="bi bi-chevron-right sidebar-arrow" aria-hidden="true"></i>
                            </a>
                            <div class="sidebar-flyout">
                                <p class="flyout-title"><?= e($category['name']) ?></p>
                                <a href="<?= base_url('category/' . $category['id']) ?>">
                                    <i class="bi bi-grid" aria-hidden="true"></i> Xem tất cả <?= e($category['name']) ?>
                                </a>
                                <a href="<?= base_url('search?q=' . urlencode($category['name'])) ?>">
                                    <i class="bi bi-search" aria-hidden="true"></i> Sản phẩm nổi bật
                                </a>
                                <a href="<?= base_url('products') ?>">
                                    <i class="bi bi-lightning-charge" aria-hidden="true"></i> Khuyến mãi hôm nay
                                </a>
                                <a href="<?= base_url('products') ?>">
                                    <i class="bi bi-credit-card" aria-hidden="true"></i> Trả góp 0%
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <a href="<?= base_url('products') ?>">
                            <i class="bi bi-grid" aria-hidden="true"></i>
                            <span class="sidebar-label">Tất cả sản phẩm</span>
                            <i class="bi bi-chevron-right sidebar-arrow" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('category/3') ?>">
                            <i class="bi bi-tools" aria-hidden="true"></i>
                            <span class="sidebar-label">Build PC theo cấu hình</span>
                            <i class="bi bi-chevron-right sidebar-arrow" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="hero-carousel-wrap">
                <div id="heroBanner" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="4500" data-bs-pause="hover">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroBanner" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Banner 1"></button>
                        <button type="button" data-bs-target="#heroBanner" data-bs-slide-to="1" aria-label="Banner 2"></button>
                        <button type="button" data-bs-target="#heroBanner" data-bs-slide-to="2" aria-label="Banner 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <a href="<?= base_url('category/1') ?>" aria-label="Khám phá laptop chính hãng">
                                <img src="<?= asset('img/banner/laptop-uu-dai.jpg') ?>" alt="Laptop chính hãng — ưu đãi tại Nova Shop" width="800" height="280" fetchpriority="high">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="<?= base_url('category/2') ?>" aria-label="Khám phá PC và linh kiện">
                                <img src="<?= asset('img/banner/combo-pc.jpg') ?>" alt="PC và linh kiện — Build PC tại Nova Shop" width="1667" height="584" loading="lazy">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="<?= base_url('products') ?>" aria-label="Ưu đãi bảo hành màn hình">
                                <img src="<?= asset('img/banner/bao-hanh-man-hinh.jpg') ?>" alt="Ưu đãi bảo hành màn hình tại Nova Shop" width="800" height="280" loading="lazy">
                            </a>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroBanner" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Trước</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroBanner" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sau</span>
                    </button>
                </div>
            </div>

            <div class="hero-side-banners d-none d-xl-flex">
                <a href="<?= base_url('category/1') ?>" class="hero-side-card side-card-gift">
                    <span class="side-card-badge">HOT</span>
                    <i class="bi bi-gift side-card-icon" aria-hidden="true"></i>
                    <span class="side-card-body">
                        <strong>BỘ QUÀ LAPTOP</strong>
                        <span>Đến 3 triệu</span>
                        <small>Xem ưu đãi <i class="bi bi-arrow-right" aria-hidden="true"></i></small>
                    </span>
                </a>
                <a href="<?= base_url('search?q=iphone') ?>" class="hero-side-card side-card-phone">
                    <span class="side-card-badge">DEAL</span>
                    <i class="bi bi-phone side-card-icon" aria-hidden="true"></i>
                    <span class="side-card-body">
                        <strong>IPHONE &amp; iPAD</strong>
                        <span>Giảm đến 2 triệu</span>
                        <small>Mua ngay <i class="bi bi-arrow-right" aria-hidden="true"></i></small>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Service commitments -->
<section class="service-strip reveal">
    <div class="container-site">
        <div class="service-strip-inner">
            <div class="service-item">
                <i class="bi bi-truck" aria-hidden="true"></i>
                <div><strong>Miễn phí vận chuyển</strong><span>Đơn hàng từ 500.000₫</span></div>
            </div>
            <div class="service-item">
                <i class="bi bi-shield-check" aria-hidden="true"></i>
                <div><strong>Bảo hành chính hãng</strong><span>Lên đến 36 tháng</span></div>
            </div>
            <div class="service-item">
                <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                <div><strong>Đổi trả 1:1</strong><span>Trong 30 ngày</span></div>
            </div>
            <div class="service-item">
                <i class="bi bi-headset" aria-hidden="true"></i>
                <div><strong>Hỗ trợ 24/7</strong><span>Hotline 1800 6867</span></div>
            </div>
        </div>
    </div>
</section>

<!-- Category icons -->
<section class="cat-icons-section">
    <div class="container-site">
        <div class="row g-2 row-cols-4 row-cols-sm-6 row-cols-md-8 row-cols-lg-10">
            <?php foreach ($categories as $category): ?>
                <div class="col">
                    <a href="<?= base_url('category/' . $category['id']) ?>" class="cat-icon-card">
                        <div class="cat-icon-img">
                            <i class="bi <?= $categoryIcons[$category['slug']] ?? 'bi-grid' ?>" aria-hidden="true"></i>
                        </div>
                        <span class="cat-icon-label"><?= e($category['name']) ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
            <div class="col">
                <a href="<?= base_url('products') ?>" class="cat-icon-card">
                    <div class="cat-icon-img"><i class="bi bi-lightning-charge" aria-hidden="true"></i></div>
                    <span class="cat-icon-label">Flash Sale</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('category/3') ?>" class="cat-icon-card">
                    <div class="cat-icon-img"><i class="bi bi-tools" aria-hidden="true"></i></div>
                    <span class="cat-icon-label">Build PC</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= base_url('products') ?>" class="cat-icon-card">
                    <div class="cat-icon-img"><i class="bi bi-grid" aria-hidden="true"></i></div>
                    <span class="cat-icon-label">Tất cả</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Flash sale -->
<?php if ($saleProducts): ?>
<section class="flash-sale-section reveal">
    <div class="container-site">
        <div class="flash-sale-head">
            <div class="flash-sale-head-left">
                <h2 class="flash-sale-title"><i class="bi bi-lightning-charge-fill" aria-hidden="true"></i> Flash Sale</h2>
                <div class="countdown" data-countdown aria-label="Đếm ngược ưu đãi">
                    <span class="countdown-block"><span id="cd-h">00</span><small>Giờ</small></span>
                    <span class="countdown-sep">:</span>
                    <span class="countdown-block"><span id="cd-m">00</span><small>Phút</small></span>
                    <span class="countdown-sep">:</span>
                    <span class="countdown-block"><span id="cd-s">00</span><small>Giây</small></span>
                </div>
                <div class="flash-sale-progress">
                    <span class="flash-progress-label"><i class="bi bi-fire" aria-hidden="true"></i> Đã bán 68%</span>
                    <div class="flash-progress-track">
                        <span class="flash-progress-fill"></span>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('products') ?>" class="btn-see-all flash-sale-more">Xem tất cả <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="product-scroll-row">
            <?php foreach ($saleProducts as $product): ?>
                <div class="product-scroll-item">
                    <?php \App\Core\View::partial('product-card', ['product' => $product]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured products -->
<section class="products-section reveal">
    <div class="container-site">
        <div class="section-heading">
            <h2 class="section-title">Sản phẩm nổi bật</h2>
            <div class="section-tabs d-none d-md-flex">
                <a href="<?= base_url('products') ?>" class="tab-link active">Tất cả</a>
                <?php foreach (array_slice($categories, 0, 3) as $category): ?>
                    <a href="<?= base_url('category/' . $category['id']) ?>" class="tab-link"><?= e($category['name']) ?></a>
                <?php endforeach; ?>
            </div>
            <a href="<?= base_url('products') ?>" class="btn-see-all ms-auto">Xem tất cả <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col">
                    <?php \App\Core\View::partial('product-card', ['product' => $product]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest products -->
<?php if ($latestProducts): ?>
<section class="products-section reveal">
    <div class="container-site">
        <div class="section-heading">
            <h2 class="section-title">Mới về tại Nova</h2>
            <a href="<?= base_url('products') ?>" class="btn-see-all ms-auto">Xem tất cả <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5">
            <?php foreach ($latestProducts as $product): ?>
                <div class="col">
                    <?php \App\Core\View::partial('product-card', ['product' => $product]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Brands -->
<section class="brands-section reveal">
    <div class="container-site">
        <h3 class="section-title mb-3">Thương hiệu nổi bật</h3>
        <div class="brands-row">
            <?php foreach (['ASUS', 'ACER', 'APPLE', 'DELL', 'HP', 'LENOVO', 'MSI', 'SAMSUNG', 'LG', 'LOGITECH'] as $brand): ?>
                <a href="<?= base_url('products') ?>" class="brand-logo-card"><?= $brand ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

