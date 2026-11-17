<?php

return [
    'name'       => $_ENV['APP_NAME'] ?? 'Nova Shop',
    'env'        => $_ENV['APP_ENV'] ?? 'development',
    'debug'      => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'url'        => rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/'),
    'timezone'   => 'Asia/Ho_Chi_Minh',
    'locale'     => 'vi',
    'session'    => [
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 120),
        'name'     => 'NOVA_SESSION',
    ],
    'csrf'       => [
        'lifetime' => (int)($_ENV['CSRF_EXPIRE'] ?? 3600),
    ],
    'upload'     => [
        'max_size'      => (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880), // 5MB
        'allowed_types' => explode(',', $_ENV['ALLOWED_IMAGE_TYPES'] ?? 'jpg,jpeg,png,webp'),
    ],
    'payment'    => [
        'bank' => [
            'id'           => $_ENV['VIETQR_BANK_ID'] ?? 'VCB',
            'name'         => $_ENV['VIETQR_BANK_NAME'] ?? 'Vietcombank',
            'account_no'   => $_ENV['VIETQR_ACCOUNT_NO'] ?? '1054950421',
            'account_name' => $_ENV['VIETQR_ACCOUNT_NAME'] ?? 'TRAN QUOC KHANH',
        ],
        'vietqr_template' => $_ENV['VIETQR_TEMPLATE'] ?? 'compact2',
    ],
];

