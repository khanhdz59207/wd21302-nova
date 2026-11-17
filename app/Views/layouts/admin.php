<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Quản trị hệ thống - Nova Shop') ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/responsive.css') ?>">
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div style="width: 260px; flex-shrink: 0;">
            <?php \App\Core\View::partial('sidebar'); ?>
        </div>

        <!-- Main Content Area -->
        <div class="flex-grow-1 min-vh-100 d-flex flex-column">
            <?php \App\Core\View::partial('topbar'); ?>

            <div class="p-4 flex-grow-1">
                <?php \App\Core\View::partial('flash'); ?>
                <?php \App\Core\View::partial('errors'); ?>
                <?= $content ?>
            </div>

            <footer class="bg-white border-top py-3 text-center text-muted small">
                &copy; <?= date('Y') ?> Nova Shop Admin Panel - Hệ thống quản lý bán lẻ FPT Polytechnic.
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/main.js') ?>"></script>
    <script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>

