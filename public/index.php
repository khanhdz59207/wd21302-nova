<?php

/**
 * Nova Shop - Front Controller
 * Ứng dụng Thương Mại Điện Tử theo mô hình MVC chuẩn OOP
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/Core/App.php';

use App\Core\App;

// Bootstrap application
App::bootstrap();

// Run application
App::run();

