<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Nova Shop - Laptop, PC & linh kiện chính hãng') ?></title>
    <meta name="description" content="Mua điện thoại, máy tính laptop, Build PC, linh kiện máy tính, màn hình, thiết bị công nghệ chính hãng | Giá rẻ, trả góp 0%, Free Ship.">
    <meta name="theme-color" content="#0B74E5">
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/client.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/responsive.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/effects.css') ?>">
</head>
<body class="client-page d-flex flex-column min-vh-100">
    <a class="skip-link" href="#main-content">Bỏ qua điều hướng</a>

    <?php \App\Core\View::partial('header'); ?>

    <main class="flex-grow-1" id="main-content">
        <div class="container-site mt-2">
            <?php \App\Core\View::partial('flash'); ?>
        </div>
        <?= $content ?>
    </main>

    <?php \App\Core\View::partial('footer'); ?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= asset('js/main.js') ?>"></script>
    <script src="<?= asset('js/effects.js') ?>"></script>
    <script src="<?= asset('js/cart.js') ?>"></script>
    <script src="<?= asset('js/search.js') ?>"></script>
</body>
</html>
