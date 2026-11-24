-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: nova_shop
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order_index` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '1: Hiển thị, 0: Ẩn',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_categories_parent` (`parent_id`),
  KEY `idx_categories_slug` (`slug`),
  CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Laptop & Macbook','laptop-macbook','laptop.png','Laptop gaming, laptop đồ họa, laptop mỏng nhẹ văn phòng chính hãng',1,1,'2026-11-07 03:10:17','2026-11-07 03:10:17'),(2,NULL,'PC - Máy tính để bàn','pc-may-tinh-de-ban','pc.png','PC Gaming, PC Đồ họa, Máy trạm Workstation cấu hình mạnh',2,1,'2026-11-07 03:10:17','2026-11-07 03:10:17'),(3,NULL,'Linh kiện máy tính','linh-kien-may-tinh','components.png','CPU, RAM, SSD, Card màn hình VGA, Mainboard, Nguồn PSU',3,1,'2026-11-07 03:10:17','2026-11-07 03:10:17'),(4,NULL,'Màn hình máy tính','man-hinh-may-tinh','monitors.png','Màn hình Gaming 144Hz - 240Hz, Màn hình đồ họa IPS 4K',4,1,'2026-11-07 03:10:17','2026-11-07 03:10:17'),(5,NULL,'Bàn phím & Chuột Gaming','ban-phim-chuot','gear.png','Bàn phím cơ không dây, Chuột gaming độ nhạy cao',5,1,'2026-11-07 03:10:17','2026-11-07 03:10:17');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `product_id` int unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_items_product` (`product_id`),
  KEY `idx_items_order` (`order_id`),
  CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `payment_status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `order_status` enum('pending','processing','shipping','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_code` (`order_code`),
  KEY `idx_orders_code` (`order_code`),
  KEY `idx_orders_user` (`user_id`),
  KEY `idx_orders_status` (`order_status`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(15,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `specs` text COLLATE utf8mb4_unicode_ci COMMENT 'Thông số kỹ thuật (CPU, RAM, VGA,...)',
  `status` tinyint(1) DEFAULT '1' COMMENT '1: Hiển thị bán, 0: Ẩn',
  `is_featured` tinyint(1) DEFAULT '0' COMMENT '1: Nổi bật, 0: Thường',
  `views` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_products_category` (`category_id`),
  KEY `idx_products_price` (`price`),
  KEY `idx_products_featured` (`is_featured`),
  KEY `idx_products_status` (`status`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Laptop Asus ROG Strix G16 G614JV (i7-13650HX / RTX 4060 / 16GB / 512GB)','laptop-asus-rog-strix-g16-g614jv','NOVA-ROG-G16',39990000.00,36490000.00,15,'laptop-rog.png','Asus ROG Strix G16 mang sức mạnh vượt trội từ CPU Intel Core i7-13650HX thế hệ 13 và card đồ họa NVIDIA GeForce RTX 4060. Tần số quét 165Hz cho hình ảnh mượt mà trong mọi trận chiến game đỉnh cao.','CPU: Intel Core i7-13650HX (14 cores, 20 threads)\nRAM: 16GB DDR5 4800MHz (2 khe)\nỔ cứng: 512GB PCIe 4.0 NVMe M.2 SSD\nVGA: NVIDIA GeForce RTX 4060 8GB GDDR6\nMàn hình: 16.0 inch FHD+ (1920 x 1200), IPS 165Hz',1,1,1267,'2026-11-07 03:10:18','2026-11-09 03:09:44'),(2,1,'Laptop Acer Predator Helios Neo 16 (i7-13700HX / RTX 4060 / 16GB / 512GB)','laptop-acer-predator-helios-neo-16','NOVA-ACER-NEO16',38990000.00,35990000.00,20,'laptop-predator.png','Acer Predator Helios Neo 16 là cỗ máy chiến game hoàn hảo với quạt làm mát kim loại AeroBlade 3D thế hệ thứ 5 độc quyền, giải phóng tối đa hiệu năng của vi xử lý.','CPU: Intel Core i7-13700HX\nRAM: 16GB DDR5 4800MHz\nỔ cứng: 512GB NVMe SSD\nVGA: NVIDIA GeForce RTX 4060 8GB\nMàn hình: 16 inch WQXGA (2560 x 1600) 165Hz',1,1,980,'2026-11-07 03:10:18','2026-11-07 03:40:55'),(3,1,'Apple MacBook Air 13 inch M2 2022 (8GB RAM / 256GB SSD)','apple-macbook-air-13-inch-m2-2022','NOVA-MAC-AIR-M2',26990000.00,24490000.00,25,'macbook-air.png','MacBook Air M2 siêu mỏng nhẹ, pin trâu lên đến 18 tiếng, màn hình Liquid Retina tuyệt đẹp, phù hợp tối đa cho sinh viên và giới văn phòng sáng tạo.','Chip: Apple M2 8-core CPU, 8-core GPU\nRAM: 8GB Unified Memory\nỔ cứng: 256GB SSD\nMàn hình: 13.6 inch Liquid Retina (2560 x 1664)\nTrọng lượng: 1.24 kg',1,1,2100,'2026-11-07 03:10:18','2026-11-07 03:40:55'),(4,2,'PC Gaming Nova Shop Apex Ultra (i5-13400F / RTX 3060 12GB / 16GB / 500GB)','pc-gaming-phong-vu-apex-ultra','NOVA-PC-APEX-01',22990000.00,19990000.00,8,'pc-apex.png','Bộ máy tính PC Gaming cấu hình tối ưu do chuyên gia Nova Shop tuyển chọn linh kiện kỹ lưỡng, cân mượt mọi tựa game AAA và phần mềm Premiere, Photoshop.','Mainboard: B760M Gaming\nCPU: Intel Core i5-13400F\nRAM: 16GB (2x8GB) DDR4 3200MHz\nVGA: NVIDIA RTX 3060 12GB\nPSU: 650W chuẩn 80 Plus Bronze',1,1,1422,'2026-11-07 03:10:18','2026-11-09 04:07:38'),(5,3,'Card Màn Hình ASUS TUF Gaming GeForce RTX 4070 SUPER 12GB','card-man-hinh-asus-tuf-rtx-4070-super','NOVA-VGA-TUF4070',19990000.00,18890000.00,12,'vga-4070.png','Dòng card đồ họa TUF Gaming danh tiếng với độ bền chuẩn quân sự, quạt Axial-tech làm mát vượt trội, hỗ trợ Ray Tracing và DLSS 3 chân thực.','Nhân đồ họa: GeForce RTX 4070 SUPER\nBộ nhớ: 12GB GDDR6X\nBus bộ nhớ: 192-bit\nCổng xuất hình: 1x HDMI 2.1a, 3x DisplayPort 1.4a',1,0,750,'2026-11-07 03:10:18','2026-11-07 03:40:55'),(6,4,'Màn Hình Gaming LG UltraGear 27GR75Q-B 27 inch 2K IPS 165Hz','man-hinh-lg-ultragear-27gr75q-b','NOVA-MON-LG27',7490000.00,6290000.00,18,'monitor-lg.png','Màn hình chơi game tốc độ cao với độ phân giải QHD 2K sắc nét, tần số quét 165Hz và thời gian phản hồi 1ms GtG cho góc nhìn sống động không bóng mờ.','Kích thước: 27 inch\nĐộ phân giải: 2K QHD (2560 x 1440)\nTấm nền: IPS\nTần số quét: 165Hz\nThời gian đáp ứng: 1ms (GtG)\nHỗ trợ: G-Sync Compatible, AMD FreeSync',1,1,1890,'2026-11-07 03:10:18','2026-11-07 03:40:55'),(7,5,'Bàn Phím Cơ Không Dây Logitech G515 LIGHTSPEED TKL','ban-phim-co-logitech-g515-lightspeed','NOVA-KB-LOGI-G515',3490000.00,2990000.00,30,'keyboard-logi.png','Thiết kế Low-Profile siêu mỏng thanh lịch, kết nối 3 chế độ: LIGHTSPEED không dây, Bluetooth và cáp Type-C có dây linh hoạt.','Layout: TKL (Tenkeyless)\nSwitch: GL Mechanical Low Profile\nKết nối: Lightspeed Wireless / Bluetooth / Type-C\nLED: LIGHTSYNC RGB 16.8 triệu màu',1,0,430,'2026-11-07 03:10:18','2026-11-07 03:40:55'),(8,5,'Chuột Gaming Không Dây Logitech G Pro X Superlight 2','chuot-logitech-g-pro-x-superlight-2','NOVA-MOUSE-GPX2',3890000.00,3290000.00,22,'mouse-logi.png','Chuột gaming nhẹ nhất thế giới dành cho các game thủ chuyên nghiệp Esports, cảm biến HERO 2 độ chính xác sub-micron.','Trọng lượng: 60g\nCảm biến: HERO 2 (32.000 DPI)\nTốc độ báo cáo: 4000Hz (4K Polling Rate)\nPin: Lên đến 95 giờ sử dụng liên tục',1,1,880,'2026-11-07 03:10:18','2026-11-07 03:40:55');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_reviews_user` (`user_id`),
  KEY `idx_reviews_product` (`product_id`),
  CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,2,5,'Máy chạy cực kỳ êm và mát, chơi Cyberpunk 2077 max setting mượt mà trên 80 FPS. Rất hài lòng với dịch vụ Nova Shop!',1,'2026-11-07 03:10:18'),(2,3,2,5,'MacBook mỏng đẹp, mở máy là làm việc được ngay, pin dùng thoải mái cả ngày không cần đem theo cục sạc.',1,'2026-11-07 03:10:18'),(3,6,2,5,'Màn hình hiển thị 2K rất sắc nét, màu sắc chuẩn đồ họa và chơi game 165Hz siêu mượt.',1,'2026-11-07 03:10:18');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  `status` tinyint(1) DEFAULT '1' COMMENT '1: Hoạt động, 0: Bị khóa',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Quản Trị Viên Nova Shop','admin@novashop.vn','$2y$10$5UDmRnnLsv3c/Jp.e1/Uo.ag6tfwGbr4KTrdGYuZsG.1Vb25RzmQi','0988888888','Hà Nội, Việt Nam','admin',1,NULL,'2026-11-07 03:10:17','2026-11-07 03:10:17'),(2,'Nguyễn Văn Khách Hàng','customer@novashop.vn','$2y$10$lMdlKXYHtg6JUOS11HF71.cTdHyAopCp4okynURm4rGcGRwJ53dC6','0912345678','Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội','customer',1,NULL,'2026-11-07 03:10:17','2026-11-07 03:10:17');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'nova_shop'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-12 16:47:09
