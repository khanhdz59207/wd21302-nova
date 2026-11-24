-- ===================================================
-- NOVA SHOP DATABASE SCHEMA (DEL-03)
-- Hệ thống cơ sở dữ liệu website TMĐT thiết bị công nghệ
-- ===================================================

CREATE DATABASE IF NOT EXISTS `nova_shop` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nova_shop`;

-- 1. BẢNG NGƯỜI DÙNG (USERS)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `address` VARCHAR(255) NULL,
    `role` ENUM('admin', 'customer') DEFAULT 'customer',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1: Hoạt động, 0: Bị khóa',
    `avatar` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. BẢNG DANH MỤC (CATEGORIES)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `parent_id` INT UNSIGNED NULL DEFAULT NULL,
    `name` VARCHAR(120) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `image` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `order_index` INT DEFAULT 0,
    `status` TINYINT(1) DEFAULT 1 COMMENT '1: Hiển thị, 0: Ẩn',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    INDEX `idx_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BẢNG SẢN PHẨM (PRODUCTS)
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `sku` VARCHAR(50) NULL UNIQUE,
    `price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `sale_price` DECIMAL(15, 2) NULL DEFAULT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `image` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `specs` TEXT NULL COMMENT 'Thông số kỹ thuật (CPU, RAM, VGA,...)',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1: Hiển thị bán, 0: Ẩn',
    `is_featured` TINYINT(1) DEFAULT 0 COMMENT '1: Nổi bật, 0: Thường',
    `views` INT UNSIGNED DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
    INDEX `idx_products_category` (`category_id`),
    INDEX `idx_products_price` (`price`),
    INDEX `idx_products_featured` (`is_featured`),
    INDEX `idx_products_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BẢNG ĐƠN HÀNG (ORDERS)
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_code` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED NULL DEFAULT NULL,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(150) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `customer_address` VARCHAR(255) NOT NULL,
    `note` TEXT NULL,
    `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `payment_method` VARCHAR(50) NOT NULL DEFAULT 'cod',
    `payment_status` ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    `order_status` ENUM('pending', 'processing', 'shipping', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_orders_code` (`order_code`),
    INDEX `idx_orders_user` (`user_id`),
    INDEX `idx_orders_status` (`order_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. BẢNG CHI TIẾT ĐƠN HÀNG (ORDER_ITEMS)
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NULL DEFAULT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_image` VARCHAR(255) NULL,
    `price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL,
    INDEX `idx_items_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. BẢNG ĐÁNH GIÁ SẢN PHẨM (REVIEWS)
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `comment` TEXT NOT NULL,
    `status` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_reviews_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

