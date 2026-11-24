-- ===================================================
-- NOVA SHOP SEED DATA (DEL-04)
-- Dữ liệu mẫu chuẩn bị sẵn phục vụ kiểm thử và nghiệm thu
-- ===================================================

USE `nova_shop`;

-- 1. USERS MẪU
-- Mật khẩu mặc định của admin@novashop.vn là: admin123
-- Mật khẩu mặc định của customer@novashop.vn là: customer123
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `status`) VALUES
(1, 'Quản Trị Viên Nova Shop', 'admin@novashop.vn', '$2y$10$5UDmRnnLsv3c/Jp.e1/Uo.ag6tfwGbr4KTrdGYuZsG.1Vb25RzmQi', '0988888888', 'Hà Nội, Việt Nam', 'admin', 1),
(2, 'Nguyễn Văn Khách Hàng', 'customer@novashop.vn', '$2y$10$lMdlKXYHtg6JUOS11HF71.cTdHyAopCp4okynURm4rGcGRwJ53dC6', '0912345678', 'Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội', 'customer', 1);

-- 2. CATEGORIES MẪU
INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `image`, `description`, `order_index`, `status`) VALUES
(1, NULL, 'Laptop & Macbook', 'laptop-macbook', 'laptop.png', 'Laptop gaming, laptop đồ họa, laptop mỏng nhẹ văn phòng chính hãng', 1, 1),
(2, NULL, 'PC - Máy tính để bàn', 'pc-may-tinh-de-ban', 'pc.png', 'PC Gaming, PC Đồ họa, Máy trạm Workstation cấu hình mạnh', 2, 1),
(3, NULL, 'Linh kiện máy tính', 'linh-kien-may-tinh', 'components.png', 'CPU, RAM, SSD, Card màn hình VGA, Mainboard, Nguồn PSU', 3, 1),
(4, NULL, 'Màn hình máy tính', 'man-hinh-may-tinh', 'monitors.png', 'Màn hình Gaming 144Hz - 240Hz, Màn hình đồ họa IPS 4K', 4, 1),
(5, NULL, 'Bàn phím & Chuột Gaming', 'ban-phim-chuot', 'gear.png', 'Bàn phím cơ không dây, Chuột gaming độ nhạy cao', 5, 1);

-- 3. PRODUCTS MẪU
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `sku`, `price`, `sale_price`, `stock`, `image`, `description`, `specs`, `status`, `is_featured`, `views`) VALUES
(1, 1, 'Laptop Asus ROG Strix G16 G614JV (i7-13650HX / RTX 4060 / 16GB / 512GB)', 'laptop-asus-rog-strix-g16-g614jv', 'NOVA-ROG-G16', 39990000.00, 36490000.00, 15, 'laptop-rog.png', 
 'Asus ROG Strix G16 mang sức mạnh vượt trội từ CPU Intel Core i7-13650HX thế hệ 13 và card đồ họa NVIDIA GeForce RTX 4060. Tần số quét 165Hz cho hình ảnh mượt mà trong mọi trận chiến game đỉnh cao.',
 'CPU: Intel Core i7-13650HX (14 cores, 20 threads)\nRAM: 16GB DDR5 4800MHz (2 khe)\nỔ cứng: 512GB PCIe 4.0 NVMe M.2 SSD\nVGA: NVIDIA GeForce RTX 4060 8GB GDDR6\nMàn hình: 16.0 inch FHD+ (1920 x 1200), IPS 165Hz', 1, 1, 1250),

(2, 1, 'Laptop Acer Predator Helios Neo 16 (i7-13700HX / RTX 4060 / 16GB / 512GB)', 'laptop-acer-predator-helios-neo-16', 'NOVA-ACER-NEO16', 38990000.00, 35990000.00, 20, 'laptop-predator.png',
 'Acer Predator Helios Neo 16 là cỗ máy chiến game hoàn hảo với quạt làm mát kim loại AeroBlade 3D thế hệ thứ 5 độc quyền, giải phóng tối đa hiệu năng của vi xử lý.',
 'CPU: Intel Core i7-13700HX\nRAM: 16GB DDR5 4800MHz\nỔ cứng: 512GB NVMe SSD\nVGA: NVIDIA GeForce RTX 4060 8GB\nMàn hình: 16 inch WQXGA (2560 x 1600) 165Hz', 1, 1, 980),

(3, 1, 'Apple MacBook Air 13 inch M2 2022 (8GB RAM / 256GB SSD)', 'apple-macbook-air-13-inch-m2-2022', 'NOVA-MAC-AIR-M2', 26990000.00, 24490000.00, 25, 'macbook-air.png',
 'MacBook Air M2 siêu mỏng nhẹ, pin trâu lên đến 18 tiếng, màn hình Liquid Retina tuyệt đẹp, phù hợp tối đa cho sinh viên và giới văn phòng sáng tạo.',
 'Chip: Apple M2 8-core CPU, 8-core GPU\nRAM: 8GB Unified Memory\nỔ cứng: 256GB SSD\nMàn hình: 13.6 inch Liquid Retina (2560 x 1664)\nTrọng lượng: 1.24 kg', 1, 1, 2100),

(4, 2, 'PC Gaming Nova Shop Apex Ultra (i5-13400F / RTX 3060 12GB / 16GB / 500GB)', 'pc-gaming-phong-vu-apex-ultra', 'NOVA-PC-APEX-01', 22990000.00, 19990000.00, 8, 'pc-apex.png',
 'Bộ máy tính PC Gaming cấu hình tối ưu do chuyên gia Nova Shop tuyển chọn linh kiện kỹ lưỡng, cân mượt mọi tựa game AAA và phần mềm Premiere, Photoshop.',
 'Mainboard: B760M Gaming\nCPU: Intel Core i5-13400F\nRAM: 16GB (2x8GB) DDR4 3200MHz\nVGA: NVIDIA RTX 3060 12GB\nPSU: 650W chuẩn 80 Plus Bronze', 1, 1, 1420),

(5, 3, 'Card Màn Hình ASUS TUF Gaming GeForce RTX 4070 SUPER 12GB', 'card-man-hinh-asus-tuf-rtx-4070-super', 'NOVA-VGA-TUF4070', 19990000.00, 18890000.00, 12, 'vga-4070.png',
 'Dòng card đồ họa TUF Gaming danh tiếng với độ bền chuẩn quân sự, quạt Axial-tech làm mát vượt trội, hỗ trợ Ray Tracing và DLSS 3 chân thực.',
 'Nhân đồ họa: GeForce RTX 4070 SUPER\nBộ nhớ: 12GB GDDR6X\nBus bộ nhớ: 192-bit\nCổng xuất hình: 1x HDMI 2.1a, 3x DisplayPort 1.4a', 1, 0, 750),

(6, 4, 'Màn Hình Gaming LG UltraGear 27GR75Q-B 27 inch 2K IPS 165Hz', 'man-hinh-lg-ultragear-27gr75q-b', 'NOVA-MON-LG27', 7490000.00, 6290000.00, 18, 'monitor-lg.png',
 'Màn hình chơi game tốc độ cao với độ phân giải QHD 2K sắc nét, tần số quét 165Hz và thời gian phản hồi 1ms GtG cho góc nhìn sống động không bóng mờ.',
 'Kích thước: 27 inch\nĐộ phân giải: 2K QHD (2560 x 1440)\nTấm nền: IPS\nTần số quét: 165Hz\nThời gian đáp ứng: 1ms (GtG)\nHỗ trợ: G-Sync Compatible, AMD FreeSync', 1, 1, 1890),

(7, 5, 'Bàn Phím Cơ Không Dây Logitech G515 LIGHTSPEED TKL', 'ban-phim-co-logitech-g515-lightspeed', 'NOVA-KB-LOGI-G515', 3490000.00, 2990000.00, 30, 'keyboard-logi.png',
 'Thiết kế Low-Profile siêu mỏng thanh lịch, kết nối 3 chế độ: LIGHTSPEED không dây, Bluetooth và cáp Type-C có dây linh hoạt.',
 'Layout: TKL (Tenkeyless)\nSwitch: GL Mechanical Low Profile\nKết nối: Lightspeed Wireless / Bluetooth / Type-C\nLED: LIGHTSYNC RGB 16.8 triệu màu', 1, 0, 430),

(8, 5, 'Chuột Gaming Không Dây Logitech G Pro X Superlight 2', 'chuot-logitech-g-pro-x-superlight-2', 'NOVA-MOUSE-GPX2', 3890000.00, 3290000.00, 22, 'mouse-logi.png',
 'Chuột gaming nhẹ nhất thế giới dành cho các game thủ chuyên nghiệp Esports, cảm biến HERO 2 độ chính xác sub-micron.',
 'Trọng lượng: 60g\nCảm biến: HERO 2 (32.000 DPI)\nTốc độ báo cáo: 4000Hz (4K Polling Rate)\nPin: Lên đến 95 giờ sử dụng liên tục', 1, 1, 880);

-- 4. REVIEWS MẪU
INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `status`) VALUES
(1, 1, 2, 5, 'Máy chạy cực kỳ êm và mát, chơi Cyberpunk 2077 max setting mượt mà trên 80 FPS. Rất hài lòng với dịch vụ Nova Shop!', 1),
(2, 3, 2, 5, 'MacBook mỏng đẹp, mở máy là làm việc được ngay, pin dùng thoải mái cả ngày không cần đem theo cục sạc.', 1),
(3, 6, 2, 5, 'Màn hình hiển thị 2K rất sắc nét, màu sắc chuẩn đồ họa và chơi game 165Hz siêu mượt.', 1);

