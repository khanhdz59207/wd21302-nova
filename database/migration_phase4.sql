-- ===================================================
-- NOVA SHOP - PHASE 4 MIGRATION
-- Bo sung cau truc cho du lieu crawl tu phongvu.vn
-- Idempotent: co the chay lai nhieu lan
-- ===================================================

USE `nova_shop`;

DROP PROCEDURE IF EXISTS `nova_add_column`;
DROP PROCEDURE IF EXISTS `nova_add_index`;

DELIMITER $$

CREATE PROCEDURE `nova_add_column`(IN `p_table` VARCHAR(64), IN `p_column` VARCHAR(64), IN `p_definition` TEXT)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM `information_schema`.`COLUMNS`
        WHERE `TABLE_SCHEMA` = DATABASE()
          AND `TABLE_NAME` = `p_table`
          AND `COLUMN_NAME` = `p_column`
    ) THEN
        SET @ddl = CONCAT('ALTER TABLE `', `p_table`, '` ADD COLUMN `', `p_column`, '` ', `p_definition`);
        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

CREATE PROCEDURE `nova_add_index`(IN `p_table` VARCHAR(64), IN `p_index` VARCHAR(64), IN `p_definition` TEXT)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM `information_schema`.`STATISTICS`
        WHERE `TABLE_SCHEMA` = DATABASE()
          AND `TABLE_NAME` = `p_table`
          AND `INDEX_NAME` = `p_index`
    ) THEN
        SET @ddl = CONCAT('ALTER TABLE `', `p_table`, '` ADD INDEX `', `p_index`, '` ', `p_definition`);
        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

CALL `nova_add_column`('products', 'brand', 'VARCHAR(120) NULL AFTER `name`');
CALL `nova_add_column`('products', 'rating', 'DECIMAL(2,1) NOT NULL DEFAULT 0.0 AFTER `is_featured`');
CALL `nova_add_column`('products', 'rating_count', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER `rating`');
CALL `nova_add_column`('products', 'warranty', 'VARCHAR(150) NULL AFTER `rating_count`');
CALL `nova_add_column`('products', 'promotions', 'JSON NULL AFTER `warranty`');
CALL `nova_add_column`('products', 'description_html', 'MEDIUMTEXT NULL AFTER `description`');
CALL `nova_add_column`('products', 'source_url', 'VARCHAR(500) NULL AFTER `promotions`');

CALL `nova_add_index`('products', 'idx_products_brand', '(`brand`)');
CALL `nova_add_index`('products', 'idx_products_source_url', '(`source_url`(191))');

DROP PROCEDURE IF EXISTS `nova_add_column`;
DROP PROCEDURE IF EXISTS `nova_add_index`;

CREATE TABLE IF NOT EXISTS `product_images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_main` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uq_product_images_image` (`product_id`, `image`),
    INDEX `idx_product_images_product` (`product_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_specs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `label` VARCHAR(150) NOT NULL,
    `value` TEXT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_specs_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uq_product_specs_label` (`product_id`, `label`),
    INDEX `idx_product_specs_product` (`product_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_highlights` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_highlights_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_product_highlights_product` (`product_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_variants` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `sku` VARCHAR(50) NULL,
    `price` DECIMAL(15,2) NULL DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_product_variants_product` (`product_id`, `sort_order`),
    INDEX `idx_product_variants_sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `banners` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(20) NOT NULL,
    `file` VARCHAR(255) NOT NULL,
    `label` VARCHAR(255) NULL,
    `target_url` VARCHAR(500) NULL,
    `source_url` VARCHAR(500) NULL,
    `width` INT UNSIGNED NOT NULL DEFAULT 0,
    `height` INT UNSIGNED NOT NULL DEFAULT 0,
    `block_name` VARCHAR(100) NULL,
    `block_type` VARCHAR(100) NULL,
    `block_id` INT NULL,
    `sha1` CHAR(40) NULL,
    `bytes` INT UNSIGNED NOT NULL DEFAULT 0,
    `temporary` TINYINT(1) NOT NULL DEFAULT 1,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_banners_file` (`file`),
    INDEX `idx_banners_type_status` (`type`, `status`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
