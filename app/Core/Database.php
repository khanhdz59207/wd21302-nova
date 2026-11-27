<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = App::config('database');
            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s;charset=%s",
                $config['driver'],
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                ErrorHandler::logError("Database Connection Failed: " . $e->getMessage());
                if (App::config('app.debug')) {
                    throw new PDOException("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage(), (int)$e->getCode());
                } else {
                    die("Hệ thống đang bảo trì cơ sở dữ liệu, vui lòng thử lại sau.");
                }
            }
        }

        return self::$instance;
    }
}

