<?php
/**
 * @var array $breadcrumbs [ ['title' => 'Trang chủ', 'url' => '/'], ['title' => 'Laptop', 'url' => ''] ]
 */
?>
<?php if (!empty($breadcrumbs)): ?>
    <nav aria-label="breadcrumb" class="my-3">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none">Trang chủ</a></li>
            <?php foreach ($breadcrumbs as $index => $item): ?>
                <?php if (!empty($item['url']) && $index < count($breadcrumbs) - 1): ?>
                    <li class="breadcrumb-item"><a href="<?= base_url($item['url']) ?>" class="text-decoration-none"><?= e($item['title']) ?></a></li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($item['title']) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>

