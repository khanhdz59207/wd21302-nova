<?php
$formErrors = $_SESSION['_form_errors'] ?? [];
?>
<?php if (!empty($formErrors)): ?>
    <div class="alert alert-danger shadow-sm">
        <h6 class="fw-bold mb-2">Vui lòng sửa các lỗi sau:</h6>
        <ul class="mb-0 ps-3">
            <?php foreach ($formErrors as $fieldErrors): ?>
                <?php foreach ((array)$fieldErrors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

