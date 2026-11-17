<?php
use App\Core\Flash;
$messages = Flash::all();
?>
<?php if (!empty($messages)): ?>
    <div class="pv-flash-container">
        <?php foreach ($messages as $type => $message): ?>
            <?php 
                $bgClass = match($type) {
                    'success' => 'alert-success',
                    'error'   => 'alert-danger',
                    'warning' => 'alert-warning',
                    default   => 'alert-info'
                };
            ?>
            <div class="alert <?= $bgClass ?> alert-dismissible fade show shadow-sm" role="alert">
                <?= e($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

