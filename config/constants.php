<?php

// Đường dẫn thư mục gốc
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Trạng thái đơn hàng
define('ORDER_STATUS_PENDING', 'pending');
define('ORDER_STATUS_PROCESSING', 'processing');
define('ORDER_STATUS_SHIPPING', 'shipping');
define('ORDER_STATUS_COMPLETED', 'completed');
define('ORDER_STATUS_CANCELLED', 'cancelled');

// Vai trò người dùng
define('ROLE_ADMIN', 'admin');
define('ROLE_CUSTOMER', 'customer');

// Phân trang mặc định
define('DEFAULT_PAGE_SIZE', 12);
define('ADMIN_PAGE_SIZE', 15);

