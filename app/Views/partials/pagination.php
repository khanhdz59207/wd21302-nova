<?php
/**
 * @var array $pagination ['current_page' => 1, 'total_pages' => 5, ...]
 * @var string $baseUrl
 */
if (empty($pagination) || $pagination['total_pages'] <= 1) {
    return;
}

$current = $pagination['current_page'];
$total = $pagination['total_pages'];
$query = $_GET;
?>
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php if ($pagination['has_prev']): ?>
            <?php $query['page'] = $current - 1; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($query) ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo; Trang trước</span>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled"><span class="page-link">&laquo; Trang trước</span></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total; $i++): ?>
            <?php $query['page'] = $i; ?>
            <li class="page-item <?= ($i === $current) ? 'active' : '' ?>">
                <a class="page-link" href="?<?= http_build_query($query) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($pagination['has_next']): ?>
            <?php $query['page'] = $current + 1; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($query) ?>" aria-label="Next">
                    <span aria-hidden="true">Trang sau &raquo;</span>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled"><span class="page-link">Trang sau &raquo;</span></li>
        <?php endif; ?>
    </ul>
</nav>

