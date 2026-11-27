<?php

namespace App\Core;

class App {
    protected static array $config = [];
    protected static ?Router $router = null;

    public static function bootstrap(): void {
        // Load constants
        require_once dirname(__DIR__, 2) . '/config/constants.php';

        // Load .env if exists
        self::loadEnv(ROOT_PATH . '/.env');

        // Setup Autoload fallback if composer vendor autoload not present
        if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
            require_once ROOT_PATH . '/vendor/autoload.php';
        } else {
            self::registerFallbackAutoloader();
            require_once APP_PATH . '/Helpers/format.php';
            require_once APP_PATH . '/Helpers/url.php';
            require_once APP_PATH . '/Helpers/security.php';
            require_once APP_PATH . '/Helpers/payment.php';
        }

        // Load configs
        self::$config['app'] = require CONFIG_PATH . '/app.php';
        self::$config['database'] = require CONFIG_PATH . '/database.php';

        // Setup Timezone
        date_default_timezone_set(self::$config['app']['timezone'] ?? 'Asia/Ho_Chi_Minh');

        // Initialize Session
        Session::start();

        // Initialize Error Handler
        ErrorHandler::register();

        // Initialize Router
        self::$router = new Router();

        // Load Routes
        require_once APP_PATH . '/Routes/web.php';
        require_once APP_PATH . '/Routes/admin.php';
    }

    public static function run(): void {
        $request = new Request();
        self::$router->dispatch($request);
    }

    public static function config(string $key, mixed $default = null): mixed {
        $parts = explode('.', $key);
        $value = self::$config;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }

    public static function router(): Router {
        return self::$router;
    }

    protected static function loadEnv(string $path): void {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, '"\'');

            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }

    protected static function registerFallbackAutoloader(): void {
        spl_autoload_register(function (string $class) {
            $prefix = 'App\\';
            $baseDir = APP_PATH . '/';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
            }
        });
    }
}

