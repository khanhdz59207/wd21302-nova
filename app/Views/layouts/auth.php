<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Tài khoản - Nova Shop') ?></title>
    <meta name="theme-color" content="#0B74E5">
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/client.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/responsive.css') ?>">
</head>
<body class="client-page auth-page d-flex flex-column align-items-center justify-content-center min-vh-100 py-4">
    <div class="container" style="max-width: 440px;">
        <div class="text-center mb-3">
            <a href="<?= base_url() ?>" class="auth-logo">
                <img src="<?= asset('img/logo.svg') ?>" alt="Nova Shop">
                <strong>NOVA<span>SHOP</span></strong>
            </a>
        </div>

        <?php \App\Core\View::partial('flash'); ?>
        <?php \App\Core\View::partial('errors'); ?>

        <div class="auth-card">
            <div class="card-body">
                <?= $content ?>
            </div>
        </div>

        <div class="text-center mt-3 small">
            <a href="<?= base_url() ?>" class="text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Quay về trang chủ
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
