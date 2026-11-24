-- ===================================================
-- RESET DATABASE SCRIPT
-- Xóa toàn bộ dữ liệu và cấu trúc để dựng lại từ đầu
-- ===================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `product_specs`;
DROP TABLE IF EXISTS `product_highlights`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- Sau đó chạy file schema.sql và seed.sql

