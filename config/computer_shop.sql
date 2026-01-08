-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 09:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `computer_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `description`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'MSI', 'msi', NULL, NULL, 'active', 0, '2026-01-08 02:35:08', '2026-01-08 02:35:08'),
(2, 'ACER', 'acer', NULL, NULL, 'active', 0, '2026-01-08 10:25:09', '2026-01-08 10:25:09'),
(3, 'ASUS', 'asus', NULL, NULL, 'active', 0, '2026-01-08 19:59:44', '2026-01-08 19:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '2025-12-31 16:43:30', '2025-12-31 16:43:30'),
(2, 4, NULL, '2026-01-03 13:09:53', '2026-01-03 13:09:53'),
(5, 5, NULL, '2026-01-07 10:51:03', '2026-01-07 10:51:03'),
(6, 6, NULL, '2026-01-07 16:06:42', '2026-01-07 16:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(2, 1, 15, 6, '2026-01-08 02:13:34', '2026-01-08 10:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Laptop', 'laptop', 'Laptop gaming, van phong, do hoa cac thuong hieu', NULL, NULL, 1, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07'),
(2, 'PC Gaming', 'pc-gaming', 'May tinh de ban gaming cau hinh cao', NULL, NULL, 2, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07'),
(3, 'Linh kien may tinh', 'linh-kien', 'CPU, RAM, VGA, SSD va cac linh kien khac', NULL, NULL, 3, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07'),
(4, 'Man hinh', 'man-hinh', 'Man hinh gaming, do hoa cac kich thuoc', NULL, NULL, 4, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07'),
(5, 'Ban phim', 'ban-phim', 'Ban phim co, ban phim gaming', NULL, NULL, 5, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07'),
(6, 'Chuot', 'chuot', 'Chuot gaming, chuot van phong', NULL, NULL, 6, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07'),
(7, 'Tai nghe', 'tai-nghe', 'Tai nghe gaming, tai nghe bluetooth', NULL, NULL, 7, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07'),
(8, 'Phu kien', 'phu-kien', 'Phu kien may tinh cac loai', NULL, NULL, 8, 'active', '2025-12-31 16:06:27', '2026-01-06 05:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','read','replied') DEFAULT 'pending',
  `reply` text DEFAULT NULL,
  `replied_by` int(11) DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `status` enum('open','pending','closed') DEFAULT 'open',
  `last_message_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `assigned_to`, `subject`, `status`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 5, 1, NULL, 'open', '2026-01-08 10:06:21', '2026-01-08 08:27:14', '2026-01-08 09:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_type` enum('percent','fixed') NOT NULL,
  `discount_value` decimal(15,2) NOT NULL,
  `min_order_value` decimal(15,0) DEFAULT 0,
  `max_discount` decimal(15,0) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('user','employee','admin','system') NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `sender_type`, `content`, `image`, `is_read`, `read_at`, `created_at`) VALUES
(1, 1, 5, 'user', 'tôi cần tư vấn về laptop', NULL, 1, '2026-01-08 16:01:46', '2026-01-08 08:27:14'),
(2, 1, 1, 'employee', 'Xin chào, tôi có thể giúp gì?', NULL, 1, '2026-01-08 15:52:53', '2026-01-08 08:52:35'),
(3, 1, 5, 'user', 'tư vấn laptop', NULL, 1, '2026-01-08 16:01:46', '2026-01-08 08:57:51'),
(4, 1, 1, 'employee', 'laptop nào ạ', NULL, 1, '2026-01-08 16:03:12', '2026-01-08 08:58:41'),
(5, 1, 5, 'user', 'laptop msi ấy shop', NULL, 1, '2026-01-08 16:05:58', '2026-01-08 09:05:40'),
(6, 1, 1, 'employee', 'anh chị vui lòng đợi bên em kiểm tra lại ạ', NULL, 1, '2026-01-08 16:06:29', '2026-01-08 09:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_ward` varchar(100) DEFAULT NULL,
  `shipping_district` varchar(100) DEFAULT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `subtotal` decimal(15,0) NOT NULL,
  `shipping_fee` decimal(15,0) DEFAULT 0,
  `discount` decimal(15,0) DEFAULT 0,
  `total` decimal(15,0) NOT NULL,
  `payment_method` enum('cod','bank_transfer','momo','vnpay','credit_card') DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `status` enum('pending','confirmed','processing','shipping','delivered','cancelled','returned') DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `assigned_employee` int(11) DEFAULT NULL,
  `cancelled_reason` varchar(500) DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `shipping_ward`, `shipping_district`, `shipping_city`, `subtotal`, `shipping_fee`, `discount`, `total`, `payment_method`, `payment_status`, `status`, `note`, `admin_note`, `assigned_employee`, `cancelled_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1, 'TS20260103E80E3D', 4, 'Mai Tuấn Đạt', 'maituandat2004@gmail.com', '0123456789', 'hhhh', '00001', '002', '01', 92990000, 0, 0, 92990000, 'cod', 'pending', 'delivered', '', NULL, NULL, NULL, '2026-01-06 07:10:35', '2026-01-03 15:57:50', '2026-01-06 06:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `order_id`, `status`, `note`, `created_by`, `created_at`) VALUES
(1, 1, 'pending', 'Đơn hàng được tạo', 4, '2026-01-03 15:57:50'),
(2, 1, 'confirmed', NULL, NULL, '2026-01-03 16:20:29'),
(3, 1, 'confirmed', 'Đơn hàng đã được xác nhận', 1, '2026-01-03 16:20:29'),
(4, 1, 'delivered', NULL, NULL, '2026-01-06 06:10:35'),
(5, 1, 'delivered', 'Đơn hàng đã giao thành công', 1, '2026-01-06 06:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_sku` varchar(50) DEFAULT NULL,
  `price` decimal(15,0) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(15,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `product_sku`, `price`, `quantity`, `total`) VALUES
(1, 1, 13, 'Laptop gaming MSI Stealth 18 HX AI A2XWIG 017VN', 'https://product.hstatic.net/200000722513/product/1024__3__5335344653aa44169e8e9f763154aa25_master.png', NULL, 92990000, 1, 92990000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `price` decimal(15,0) NOT NULL,
  `sale_price` decimal(15,0) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','out_of_stock') DEFAULT 'active',
  `rating` decimal(2,1) DEFAULT 0.0,
  `review_count` int(11) DEFAULT 0,
  `sold_count` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `specifications`, `short_description`, `price`, `sale_price`, `category_id`, `brand`, `brand_id`, `sku`, `stock`, `featured`, `status`, `rating`, `review_count`, `sold_count`, `views`, `created_at`, `updated_at`) VALUES
(13, 'Laptop gaming MSI Stealth 18 HX AI A2XWIG 017VN', 'laptop-gaming-msi-stealth-18-hx-ai-a2xwig-017vn', '', '<figure class=\"table\"><table><tbody><tr><td><a href=\"https://gearvn.com/collections/cpu-bo-vi-xu-ly\"><strong>CPU</strong></a></td><td>Intel® Core™ Ultra 9 processor 275HX (up to 5.4Ghz, 24 Core 24 Threads, 36MB cache)</td></tr><tr><td><a href=\"https://gearvn.com/collections/ram-pc\"><strong>RAM</strong></a></td><td>32GB (16x2) DDR5 6400MHz (2x SO-DIMM socket, up to 96GB SDRAM)</td></tr><tr><td><a href=\"https://gearvn.com/collections/o-cung-di-dong-hdd-box\"><strong>Ổ lưu trữ</strong></a></td><td>2TB SSD M.2 NVMe ( 1 x M.2 NVMe )</td></tr><tr><td><a href=\"https://gearvn.com/collections/vga-card-man-hinh\"><strong>Card đồ họa</strong></a></td><td>GeForce RTX™ 5080 16GB GDDR7 Intel® Arc™ Graphics ( 2002MHz , 150W , 1334 AI TOPS )</td></tr><tr><td><a href=\"https://gearvn.com/pages/man-hinh\"><strong>Màn hình</strong></a></td><td>18\" ( 3840 x 2400 ) UHD (4K) 16:10 , Mini LED IPS , 120Hz , không cảm ứng , 1000 nits , 100% DCI-P3</td></tr><tr><td><br><br><strong>Cổng giao tiếp</strong></td><td>2 x USB 3.2<br>2 x Thunderbolt 4<br>1 x SD card slot<br>Audio combo<br>1 x HDMI<br>LAN 2500 Mbps</td></tr><tr><td><a href=\"https://gearvn.com/collections/ban-phim-may-tinh\"><strong>Bàn phím</strong></a></td><td>Per-Key RGB Gaming Keyboard by SteelSeries with Copilot Key</td></tr><tr><td><strong>Audio</strong></td><td>Nahimic, Hi-Res Audio</td></tr><tr><td><strong>LAN</strong></td><td>REALTEK/RTL8125BG-CG (Up to 2.5G)</td></tr><tr><td><strong>Wifi + Bluetooth</strong></td><td>Intel® Killer™ Wi-Fi 7 BE1750, Bluetooth v5.4</td></tr><tr><td><a href=\"https://gearvn.com/collections/webcam\"><strong>Webcam</strong></a></td><td>IR FHD type (30fps@1080p) with HDR</td></tr><tr><td><strong>Pin</strong></td><td>4 Cell 99.9WHrs</td></tr><tr><td><strong>Trọng lượng</strong></td><td>2.89 kg</td></tr><tr><td><strong>Hệ điều hành</strong></td><td>Windows 11 Home&nbsp;</td></tr><tr><td><strong>Màu sắc</strong></td><td>Midnight Black</td></tr><tr><td><strong>Chất liệu</strong></td><td>A, C: Metal (MgAl), B:&nbsp;Plastic, D:&nbsp;Metal</td></tr><tr><td><strong>Kích thước</strong></td><td>399.99 x 289.67 x 19.9-23.99 mm</td></tr></tbody></table></figure>', '', 92990000, NULL, 1, 'MSI', 1, 'msi01', 9, 1, 'active', 5.0, 1, 1, 22, '2026-01-03 14:43:57', '2026-01-08 10:38:05'),
(15, 'Laptop MSI Prestige 13 AI Evo A1MG 062VN', 'laptop-msi-prestige-13-ai-evo-a1mg-062vn', '<p>VGA: Arc Intel</p><p>CPU: Ultra 7 155H</p><p>RAM: 32 GB</p><p>LCD: 13.3 inch 2.8K OLED</p><p>SSD: 1 TB</p>', '<figure class=\"table\"><table><tbody><tr><td><a href=\"https://gearvn.com/collections/cpu-bo-vi-xu-ly\"><strong>CPU</strong></a></td><td>Intel® Core™ Ultra 7 processor 155H with Intel® AI Boost (NPU) 16 cores (6 P-cores + 8 E-cores + 2 Low Power E-cores), Max Turbo Frequency 4.8 GHz</td></tr><tr><td><a href=\"https://gearvn.com/collections/ram-pc\"><strong>RAM</strong></a></td><td>32GB LPDDR5 6400MHz (không nâng cấp)</td></tr><tr><td><a href=\"https://gearvn.com/collections/o-cung-di-dong-hdd-box\"><strong>Ổ lưu trữ</strong></a></td><td>1TB NVMe PCIe Gen4x4 SSD&nbsp;(1 x slots M.2 NVMe PCIe Gen4)</td></tr><tr><td><a href=\"https://gearvn.com/collections/vga-card-man-hinh\"><strong>Card đồ họa</strong></a></td><td>Intel® Arc™ graphics<br>(Intel® Arc™ graphics requires configuration with 16GB dual-channel memory or above)</td></tr><tr><td><a href=\"https://gearvn.com/pages/man-hinh\"><strong>Màn hình</strong></a></td><td>13.3\" 2.8K (2880 x 1800), OLED, VESA DisplayHDR™ 500 Certified, 100% DCI-P3 (Typical)</td></tr><tr><td><br><br><strong>Cổng giao tiếp</strong></td><td>2x Type-C (USB / DP / Thunderbolt™ 4) with PD charging<br>1x Type-A USB3.2 Gen1<br>1x HDMI™ 2.1 (8K @ 60Hz / 4K @ 120Hz)<br>1x Micro SD<br>1x Mic-in/Headphone-out Combo Jack</td></tr><tr><td><a href=\"https://gearvn.com/collections/ban-phim-may-tinh\"><strong>Bàn phím</strong></a></td><td>Single Backlit Keyboard (White)</td></tr><tr><td><strong>Audio</strong></td><td>2x 2W Audio Speaker Hi-Res Audio Ready, DTS Audio Processing<br>Spatial Array Microphone (3 Mic)</td></tr><tr><td><strong>LAN</strong></td><td>None</td></tr><tr><td><strong>Wifi + Bluetooth</strong></td><td>Intel® Killer™ Wi-Fi 7 BE1750, Bluetooth v5.4</td></tr><tr><td><a href=\"https://gearvn.com/collections/webcam\"><strong>Webcam</strong></a></td><td>IR FHD type (30fps@1080p) with HDR<br>3D Noise Reduction+ (3DNR+)</td></tr><tr><td><strong>Bảo mật</strong></td><td>Fingerprint Security<br>Discrete Trusted Platform Module (dTPM) 2.0<br>Firmware Trusted Platform Module (fTPM) 2.0<br>Webcam Shutter, Kensington Lock</td></tr><tr><td><strong>Pin</strong></td><td>4-Cell 75 Whrs</td></tr><tr><td><strong>Trọng lượng</strong></td><td>0.99 kg</td></tr><tr><td><strong>Hệ điều hành</strong></td><td>Windows 11 Home</td></tr><tr><td><strong>Màu sắc</strong></td><td>Stellar Gray</td></tr><tr><td><strong>Kích thước</strong></td><td>299 x 210 x 16.9 mm</td></tr></tbody></table></figure>', '', 32190000, NULL, 1, 'MSI', 1, NULL, 10, 1, 'active', 0.0, 0, 0, 6, '2026-01-08 01:03:31', '2026-01-08 10:39:51'),
(18, 'Laptop Acer Swift X14 SFX14 72G 77F9', 'laptop-acer-swift-x14-sfx14-72g-77f9', '<p>Acer Swift X14 SFX14 72G 77F9 được trang bị hệ thống&nbsp;phần cứng bao gồm CPU&nbsp;Intel® Ultra 7 155H,&nbsp;1.40 GHz upto 4.80 GHz,&nbsp;16 nhân 22 luồng,&nbsp;24MB Intel® Smart Cache mang lại&nbsp;hiệu năng mạnh mẽ, xử lý mượt mà các tác vụ thiết kế đồ hoạ, render video,... trên các phần mềm Photoshop, AI, Premiere,... hay thỏa sức cho bạn sáng tạo nội dung.&nbsp;Acer Swift X14 còn được trang bị card đồ họa&nbsp;NVIDIA® GeForce RTX™ Graphics 4050 with 6 GB GDDR6 VRAM giúp&nbsp;bạn thoải mái chơi game, thoải mái biến những thước phim của mình thành những tác phẩm nghệ thuật đầy màu sắc.</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>', '<p>Audio DTS® X:Ultra Audio; featuring optimized Bass; Loudness; Speaker</p><p>Tần số quét 120Hz</p><p>Màu sắc Steel Gray</p><p>Số nhân; luồng 16 nhân 22 luồng</p><p>Bộ nhớ Cache 24MB Intel® Smart Cache</p><p>Màn hình 14.5\" 2.8K WQXGA+ (2880x1800) OLED; 120Hz; 400 nits. DCI-P3 100%; Adobe100%</p><p>Thương hiệu Acer</p><p>Card đồ họa NVIDIA® GeForce RTX™ 4050 with 6 GB GDDR6 VRAM</p><p>Tốc độ 1.40 GHz upto 4.80 GHz</p><p>Ổ cứng 1TB PCIe NVMe SED SSD (Không nâng cấp được)</p><p>Chất liệu Cover A/C/D: Aluminum; Cover B: Plastic</p><p>Hệ điều hành Windows 11 Home Single Language</p><p>Bảo mật Firmware Trusted Platform Module (TPM) solution BIOS user; supervisor passwords</p><p>Trọng lượng 1.5kg</p><p>Pin 76Whr Li-ion battery</p><p>Kích thước 22.79 (W) x 228.12 (D) x 17.9 (H) mm</p><p>Bluetooth 5.3</p><p>CPU Intel® Core™ Ultra 7 155H</p><p>Chuẩn WIFI Killer™ Wireless Wi-Fi 6E 1675i (802.11 a/b/g/n/ac/ax wireless LAN)</p><p>RAM 32GB LPDDR5 Onboard 6400Mhz</p><p>Bàn phím Có led trắng; bảo mật vân tay</p><p>Webcam FHD Camera</p><p>Bảo hành 24 tháng</p>', '', 35490000, 35490000, 1, '', NULL, '', 10, 0, 'active', 0.0, 0, 0, 4, '2026-01-08 10:33:35', '2026-01-08 10:37:50'),
(19, 'Laptop MSI Modern 14 F13MG 466VN', 'laptop-msi-modern-14-f13mg-466vn', '', '<figure class=\"table\"><table><tbody><tr><td><a href=\"https://gearvn.com/collections/cpu-bo-vi-xu-ly\"><strong>CPU</strong></a></td><td>i5-1334U processor&nbsp;10 cores (2 P-cores + 8 E-cores), Max Turbo Frequency 4.6 GHz</td></tr><tr><td><a href=\"https://gearvn.com/collections/ram-laptop\"><strong>RAM</strong></a></td><td>16GB (8x2) DDR4 3200MHz ( 2 slot, tối đa 64 MB)</td></tr><tr><td><a href=\"https://gearvn.com/collections/hdd-o-cung-pc\"><strong>Ổ cứng</strong></a></td><td>512 GB NVMe PCIe SSD Gen4x4 w/o DRAM ( Tổng 1 slot , max 4TB)</td></tr><tr><td><a href=\"https://gearvn.com/collections/vga-card-man-hinh\"><strong>Card đồ họa</strong></a></td><td>Intel® Iris® Xe (*Intel® Iris® Xe Graphics capability requires the system to be configured with dual-channel memory)</td></tr><tr><td><a href=\"https://gearvn.com/pages/man-hinh\"><strong>Màn hình</strong></a></td><td>14.0inch FHD(1920x1080),IPS-Level,&nbsp;60Hz, 45% NTSC,&nbsp;Non-touch,&nbsp;250 nits</td></tr><tr><td><strong>Cổng giao tiếp</strong></td><td><ul><li>1x Type-C (USB3.2 Gen2 / DisplayPort™/ Power Delivery 3.0)</li><li>3x USB-A 3.2 Gen 1</li><li>1x Micro SD</li><li>1x HDMI™ (4K @ 30Hz)</li><li>1x LAN</li><li>1x Audio/Mic 3.5mm</li></ul></td></tr><tr><td><a href=\"https://gearvn.com/collections/thiet-bi-tai-nghe-loa-audio-chuyen-nghiep\"><strong>Audio</strong></a></td><td>2x 2W Speaker,&nbsp;Hi-Res Audio Ready</td></tr><tr><td><a href=\"https://gearvn.com/collections/ban-phim-may-tinh\"><strong>Bàn phím</strong></a></td><td>Single Backlit Keyboard (White) with Copilot Key</td></tr><tr><td><strong>Chuẩn WIFI</strong></td><td>Wi-Fi 6E 802.11ax</td></tr><tr><td><strong>Bluetooth</strong></td><td>v5.3</td></tr><tr><td><a href=\"https://gearvn.com/collections/webcam\"><strong>Webcam</strong></a></td><td>HD type (30fps@720p)</td></tr><tr><td><strong>Hệ điều hành</strong></td><td>Windows 11 Home Single Language</td></tr><tr><td><strong>Pin</strong></td><td>46.8 Whrs 3 cell</td></tr><tr><td><strong>Trọng lượng</strong></td><td>1.6kg</td></tr><tr><td><strong>Màu sắc</strong></td><td>Urban Silver</td></tr><tr><td><strong>Kích thước</strong></td><td>313.7 x 236 x 18.6 mm</td></tr></tbody></table></figure>', '', 14190000, 14190000, 1, '', NULL, NULL, 7, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 19:58:07', '2026-01-08 19:58:07'),
(20, 'Laptop Acer Aspire Lite AL15 72P 7232', 'laptop-acer-aspire-lite-al15-72p-7232', '', '<figure class=\"table\"><table><tbody><tr><td><a href=\"https://gearvn.com/collections/cpu-bo-vi-xu-ly\"><strong>CPU</strong></a></td><td>Intel® Core™ i7-13620H (10 nhân, 16 luồng)&nbsp;Tần số Turbo tối đa của P-core : 4.9 GHz,&nbsp;24 MB Intel® Smart Cache</td></tr><tr><td><a href=\"https://gearvn.com/collections/ram-laptop\"><strong>RAM</strong></a></td><td>16GBx1 DDR5 4800MHz (Tổng 2 slot ram,&nbsp;Nâng cấp tối đa 64GB)</td></tr><tr><td><a href=\"https://gearvn.com/collections/ssd-o-cung-the-ran\"><strong>Ổ cứng</strong></a></td><td>512GB PCIe NVMe SSD (chỉ 1 slot,nâng cấp tối đa 2TB SSD)</td></tr><tr><td><a href=\"https://gearvn.com/collections/vga-card-man-hinh\"><strong>Card đồ họa</strong></a></td><td>Intel® UHD Graphics</td></tr><tr><td><a href=\"https://gearvn.com/collections/man-hinh\"><strong>Màn hình</strong></a></td><td>15.6\" FHD (1920x1080) IPS SlimBezel,&nbsp;250nits,NTSC 45%,60Hz,Acer ComfyView™ LED-backlit TFT LCD Wide viewing angle</td></tr><tr><td><strong>Cổng giao tiếp</strong></td><td>USB Type-C<br>&nbsp;1x Full-function USB Type-C™ port supporting:<br>&nbsp;• USB 3.2 Gen 1 (up to 5 Gbps)<br>&nbsp;• DisplayPort over USB-C<br>&nbsp;• USB charging 5/9/12/15/20 V; 3.25 A<br>&nbsp;<br>&nbsp;USB Standard A<br>&nbsp;3x USB Standard-A ports, supporting:<br>&nbsp;• 3x ports for USB 3.2 Gen 1<br>&nbsp;<br>&nbsp;HDMI® 1.4 port with HDCP support<br>&nbsp;<br>&nbsp;DC-in jack<br>&nbsp;<br>&nbsp;microSD card up to 512 GB (SDXC compatible, exFAT compatible)<br>&nbsp;<br>&nbsp;3.5 mm headphone/speaker jack, supporting headsets with built-in microphone</td></tr><tr><td><a href=\"https://gearvn.com/collections/thiet-bi-tai-nghe-loa-audio-chuyen-nghiep\"><strong>Audio</strong></a></td><td>Two built-in stereo speakers<br>Two built-in digital microphones</td></tr><tr><td><strong>Bàn phím</strong></td><td>Có&nbsp;Copilot Key, Không đèn, Có phím số</td></tr><tr><td><strong>Chuẩn WIFI</strong></td><td>802.11a/b/g/n/acR2+ax wireless LAN</td></tr><tr><td><strong>Bluetooth</strong></td><td>v5.1</td></tr><tr><td><a href=\"https://gearvn.com/collections/webcam\"><strong>Webcam</strong></a></td><td>Camera FHD Camera MISC 2M FHD Camera_CTE</td></tr><tr><td><strong>Hệ điều hành</strong></td><td>Windows 11 Home Single Language</td></tr><tr><td><strong>Pin</strong></td><td>58Wh 3-cell Li-ion battery&nbsp;<br>&nbsp;90W AC adapter</td></tr><tr><td><strong>Trọng lượng</strong></td><td>1.79 kg</td></tr><tr><td><strong>Chất liệu</strong></td><td>Plastic</td></tr><tr><td><strong>Màu sắc</strong></td><td>Light Silver</td></tr><tr><td><strong>Kích thước</strong></td><td>357.5 (W) x 229.1 (D) x 19.9/19.9 (H) mm</td></tr></tbody></table></figure>', '', 15990000, 15990000, 1, '', NULL, NULL, 7, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 19:59:19', '2026-01-08 19:59:19'),
(21, 'PC GVN Intel i3-12100F/ VGA RX 6500XT (Powered by ASUS)', 'pc-gvn-intel-i3-12100f-vga-rx-6500xt-powered-by-asus', '', '<figure class=\"table\"><table><tbody><tr><td><strong>Mainboard</strong></td><td>Mainboard ASUS PRIME H610M-A WIFI D4</td><td>36 Tháng</td></tr><tr><td><strong>CPU</strong></td><td>Intel Core i3 12100F / 3.3GHz Turbo 4.3GHz / 4 Nhân 8 Luồng / 12MB / LGA 1700</td><td>36 Tháng</td></tr><tr><td><strong>RAM</strong></td><td>RAM V-Color Skywalker Plus 1x16GB 3600 RGB Black DDR4</td><td>36 Tháng</td></tr><tr><td><strong>VGA - Card đồ họa</strong></td><td>ASUS Dual Radeon RX 6500 XT V2 OC Edition 4GB GDDR6</td><td>36 Tháng</td></tr><tr><td><strong>HDD</strong></td><td>Có thể tùy chọn&nbsp;Nâng cấp</td><td>24 Tháng</td></tr><tr><td><strong>SSD</strong></td><td>PNY CS900 250GB Sata3</td><td>36 Tháng</td></tr><tr><td><strong>PSU</strong></td><td>Nguồn FSP HV PRO 550W - 80 Plus Bronze</td><td>36 Tháng</td></tr><tr><td><strong>Case&nbsp;</strong></td><td>Vỏ máy tính Xigmatek QUANTUM 4AF</td><td>12 Tháng</td></tr></tbody></table></figure>', '', 11390000, 11390000, 2, '', NULL, NULL, 2, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:00:50', '2026-01-08 20:00:50'),
(22, 'PC GVN x MSI PROJECT ZERO WHITE (Intel i5-13400/ VGA RTX 5060)', 'pc-gvn-x-msi-project-zero-white-intel-i5-13400-vga-rtx-5060', '', '<figure class=\"table\"><table><tbody><tr><td><strong>Mainboard&nbsp;</strong></td><td>MSI B760M PROJECT ZERO</td></tr><tr><td><strong>CPU</strong></td><td>Bộ vi xử lý Intel Core i5 13400 / 2.5GHz Turbo 4.6GHz / 10 Nhân 16 Luồng</td></tr><tr><td><strong>RAM</strong></td><td>RAM Kingston Fury Beast 16GB (1x16GB) bus 5600 DDR5 (KF556C40BB-16WP)</td></tr><tr><td><strong>VGA</strong></td><td>Card màn hình MSI GeForce RTX 5060 Ventus 2X OC White 8GB</td></tr><tr><td><strong>SSD</strong></td><td>Ổ cứng SSD Kingston NV3 1TB M.2 PCIe NVMe Gen4</td></tr><tr><td><strong>HDD</strong></td><td>Tùy chọn nâng cấp</td></tr><tr><td><strong>PSU&nbsp;</strong></td><td>MSI MAG A750BN PCIE5 - 80 Plus Bronze (750W)</td></tr><tr><td><strong>CASE</strong></td><td>MSI MAG PANO M100R PZ WHITE</td></tr><tr><td><strong>COOLING</strong></td><td>Tản nhiệt AIO MSI MAG CORELIQUID I240 WHITE</td></tr></tbody></table></figure>', '', 34690000, 34690000, 2, '', NULL, NULL, 3, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:02:09', '2026-01-08 20:02:09'),
(23, 'PC GVN Intel i5-12400F/ VGA RTX 5060 (Main H)', 'pc-gvn-intel-i5-12400f-vga-rtx-5060-main-h', '', '<figure class=\"table\"><table><tbody><tr><td>Mainboard</td><td>Bo mạch chủ GIGABYTE H610M-H V3 (DDR4)</td></tr><tr><td>CPU</td><td>CPU Intel Core i5 12400F / 2.5GHz Turbo 4.4GHz / 6 Nhân 12 Luồng (Chính hãng - Full box)</td></tr><tr><td>RAM</td><td>Ram T-Group T-Force Delta 1x8GB 3600 RGB White</td></tr><tr><td>VGA&nbsp;</td><td>Card màn hình Asus GeForce RTX 5060 DUAL OC 8GB&nbsp;</td></tr><tr><td>HDD</td><td>Có thể tùy chọn&nbsp;Nâng cấp</td></tr><tr><td>SSD</td><td>SSD PNY CS1031 M.2 2280 256GB Gen 3x4</td></tr><tr><td>PSU</td><td>Nguồn máy tính MSI MAG A650BN 80 PLUS bronze ( 650W )</td></tr><tr><td>Case</td><td>Vỏ máy tính Xigmatek QUANTUM 4AF</td></tr><tr><td>Tản nhiệt</td><td>Cooler Master Hyper 212 Spectrum V3 ARGB</td></tr></tbody></table></figure>', '', 19990000, 19990000, 2, '', NULL, NULL, 3, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:03:18', '2026-01-08 20:03:18'),
(24, 'PC GVN Intel i5-12400F/VGA ARC B580', 'pc-gvn-intel-i5-12400fvga-arc-b580', '', '<figure class=\"table\"><table><tbody><tr><td><strong>Mainboard&nbsp;</strong></td><td>Bo mạch chủ GIGABYTE B760M D DDR4 – GEARVN.COM</td></tr><tr><td><strong>CPU</strong></td><td>CPU Intel Core i5 12400F / 2.5GHz Turbo 4.4GHz / 6 Nhân 12 Luồng</td></tr><tr><td><strong>RAM&nbsp;</strong></td><td>Ram T-Group T-Force Delta 1x8GB 3600 RGB White</td></tr><tr><td><strong>VGA&nbsp;</strong></td><td>Card màn hình Intel Arc B580 12GB</td></tr><tr><td><strong>HDD&nbsp;</strong></td><td>Có thể tùy chọn&nbsp;nâng cấp</td></tr><tr><td><strong>SSD&nbsp;</strong></td><td>Ổ Cứng SSD Verbatim Vi550 256GB Sata3</td></tr><tr><td><strong>PSU&nbsp;</strong></td><td>Nguồn máy tính MSI MAG A650BN 80 PLUS bronze ( 650W )</td></tr><tr><td><strong>Case&nbsp;</strong></td><td>Vỏ máy tính Xigmatek QUANTUM 4AF&nbsp;</td></tr><tr><td><strong>Cooling</strong></td><td>Cooler Master Hyper 212 Spectrum V3 ARGB</td></tr></tbody></table></figure>', '', 19790000, 19790000, 2, '', NULL, NULL, 4, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:04:23', '2026-01-08 20:04:23'),
(25, 'PC GVN x Corsair iCUE (Intel i5-14400F/ VGA RTX 5060)', 'pc-gvn-x-corsair-icue-intel-i5-14400f-vga-rtx-5060', '', '<figure class=\"table\"><table><tbody><tr><td>Mainboard</td><td>Mainboard ASUS TUF GAMING B760M-PLUS WIFI II DDR5</td></tr><tr><td>CPU</td><td>Bộ vi xử lý Intel Core i5 13400 / 2.5GHz Turbo 4.6GHz / 10 Nhân 16 Luồng</td></tr><tr><td>RAM</td><td>Ram Corsair Vengeance RGB 32GB 5600 DDR5</td></tr><tr><td>VGA&nbsp;</td><td>Card màn hình Asus GeForce RTX 5060 DUAL OC 8GB</td></tr><tr><td>HDD</td><td>Có thể tùy chọn&nbsp;Nâng cấp</td></tr><tr><td>SSD</td><td>Ổ cứng SSD Kingston NV3 500GB M.2 PCIe NVMe Gen4</td></tr><tr><td>PSU</td><td>Corsair CX750 - 80 Plus Bronze (750W)</td></tr><tr><td>Case</td><td>Corsair 3500X TG Mid Tower Black</td></tr><tr><td>Quạt</td><td>Bộ 3 quạt Corsair RS120 ARGB BLACK</td></tr></tbody></table></figure>', '', 34690000, 34690000, 2, '', NULL, 'pc', 5, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:05:44', '2026-01-08 20:08:45'),
(26, 'Card màn hình MSI GeForce RTX 5090 32G GAMING TRIO OC', 'card-man-hinh-msi-geforce-rtx-5090-32g-gaming-trio-oc', '', '<figure class=\"table\"><table><tbody><tr><td><strong>Marketing Name</strong></td><td>GeForce RTX™ 5090 32G GAMING TRIO OC</td></tr><tr><td><strong>Model Name</strong></td><td>G5090-32GTC</td></tr><tr><td><strong>Graphics Processing Unit</strong></td><td>NVIDIA® GeForce RTX™ 5090</td></tr><tr><td><strong>Interface</strong></td><td>PCI Express® Gen 5</td></tr><tr><td><strong>Core Clocks</strong></td><td>Extreme Performance: 2497 MHz (MSI Center)<br>Boost: 2482 MHz (GAMING &amp; SILENT Mode)</td></tr><tr><td><strong>CUDA® CORES</strong></td><td>21760 Units</td></tr><tr><td><strong>Memory Speed</strong></td><td>28 Gbps</td></tr><tr><td><strong>Memory</strong></td><td>32GB GDDR7</td></tr><tr><td><strong>Memory Bus</strong></td><td>512-bit</td></tr><tr><td><strong>Output</strong></td><td>DisplayPort x 3 (v2.1b)<br>HDMI™ x 1 (As specified in HDMI™ 2.1b: up to 4K 480Hz or 8K 120Hz with DSC, Gaming VRR, HDR)</td></tr><tr><td><strong>HDCP Support</strong></td><td>Y</td></tr><tr><td><strong>Power consumption</strong></td><td>575 W</td></tr><tr><td><strong>Power connectors</strong></td><td>16-pin x 1 (ATX 3.1 PSU recommended)</td></tr><tr><td><strong>Recommended </strong><a href=\"https://gearvn.com/collections/psu-nguon-may-tinh\"><strong>PSU</strong></a></td><td>1000 W</td></tr><tr><td><strong>Card Dimension (mm)</strong></td><td>359 x 149 x 70 mm</td></tr><tr><td><strong>Weight (Card / Package)</strong></td><td>2119 g / 2735 g</td></tr><tr><td><strong>DirectX Version Support</strong></td><td>12 Ultimate</td></tr><tr><td><strong>OpenGL Version Support</strong></td><td>45812</td></tr><tr><td><strong>Maximum Displays</strong></td><td>4</td></tr><tr><td><strong>G-SYNC® technology</strong></td><td>Y</td></tr><tr><td><strong>Digital Maximum Resolution</strong></td><td>7680 x 4320</td></tr></tbody></table></figure>', '', 99990000, 99990000, 3, '', NULL, NULL, 3, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:07:10', '2026-01-08 20:07:10'),
(27, 'Card màn hình ASUS ROG Astral GeForce RTX 5090 32GB GDDR7 OC Edition ', 'card-man-hinh-asus-rog-astral-geforce-rtx-5090-32gb-gddr7-oc-edition', '', '<figure class=\"table\"><table><tbody><tr><td><strong>Nhân đồ họa&nbsp;</strong></td><td>NVIDIA&nbsp;GeForce&nbsp;RTX&nbsp;5090</td></tr><tr><td><strong>Hiệu năng AI</strong></td><td>&nbsp;</td></tr><tr><td><strong>Bus tiêu chuẩn</strong></td><td>PCI Express 5.0</td></tr><tr><td><strong>Xung nhịp</strong></td><td>&nbsp;</td></tr><tr><td><strong>Nhân CUDA</strong></td><td>21760</td></tr><tr><td><strong>Tốc độ bộ nhớ</strong></td><td>28 Gbps</td></tr><tr><td><strong>OpenGL</strong></td><td>OpenGL 4.6</td></tr><tr><td><strong>Bộ nhớ Video</strong></td><td>32 GB GDDR7</td></tr><tr><td><strong>Giao thức bộ nhớ</strong></td><td>512-bit</td></tr><tr><td><strong>Độ phân giải</strong></td><td>Độ phân giải tối đa 7680 x 4320</td></tr><tr><td><strong>Giao thức</strong></td><td><p>Có x 2 (Native HDMI 2.1)</p><p>Có x 3 (Native DisplayPort 1.4a)</p><p>Hỗ trợ HDCP&nbsp;(2.3)</p></td></tr><tr><td><strong>Số lượng màn hình tối đa hỗ trợ</strong></td><td>4</td></tr><tr><td><strong>Hỗ trợ NVlink/ Crossfire&nbsp;</strong></td><td>Không</td></tr><tr><td><br><strong>Phụ kiện</strong></td><td>1 x Hướng dẫn nhanh<br>1 x Giá đỡ card đồ họa ROG<br>1 x Móc dán ROG Velcro<br>1 x Nam châm ROG<br>1 x Keycap card đồ họa ROG<br>1 x Thước đo PCB ROG<br>1 x Thẻ cảm ơn<br>1 x Cáp chuyển đổi (1 đến 4)​</td></tr><tr><td><strong>Phần mềm</strong></td><td>ASUS GPU Tweak III &amp; GeForce Game Ready Driver &amp; Studio Driver: vui lòng tải xuống tất cả phần mềm từ trang web hỗ trợ.</td></tr><tr><td><strong>Kích thước</strong></td><td>357.6 x 149.3 x 76 mm<br>14.1 x 5.9 x 3 inch</td></tr><tr><td><a href=\"https://gearvn.com/collections/psu-nguon-may-tinh\"><strong>PSU</strong></a><strong> kiến nghị</strong></td><td>1000W</td></tr><tr><td><strong>Kết nối nguồn</strong></td><td>1 x 16 pin</td></tr><tr><td><strong>Khe cắm</strong></td><td>3.8</td></tr><tr><td><strong>AURA SYNC</strong></td><td>ARGB</td></tr></tbody></table></figure>', '', 109990000, 109990000, 3, '', NULL, NULL, 3, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:08:22', '2026-01-08 20:08:22'),
(28, 'Bo mạch chủ ASUS ROG Strix Z890-E GAMING WIFI (DDR5)', 'bo-mach-chu-asus-rog-strix-z890-e-gaming-wifi-ddr5', '', '<figure class=\"table\"><table><tbody><tr><td><a href=\"https://gearvn.com/collections/cpu-bo-vi-xu-ly\"><strong>CPU</strong></a></td><td><ul><li>Intel&nbsp;Socket LGA1851 dành cho Bộ xử lý Intel Core™ Ultra (Dòng 2)&nbsp;</li><li>Hỗ trợ Công nghệ Intel Turbo Boost 2.0 và Công nghệ Intel Turbo Boost Max 3.0**</li></ul><p>* Tham khảo https://www.asus.com/support/download-center/ để biết danh sách hỗ trợ CPU.<br>** Hỗ trợ Công nghệ Intel&nbsp;Turbo Boost Max 3.0 tùy thuộc vào loại CPU.</p></td></tr><tr><td><strong>Chipset</strong></td><td>Intel® Z890</td></tr><tr><td><strong>Bộ nhớ</strong></td><td><ul><li>4 khe cắm DIMM, tối đa 192GB, DDR5&nbsp;</li><li>Hỗ trợ lên đến 8800+ MT/giây (OC), Không ECC, Clocked Unbuffered DIMM (CUDIMM)*&nbsp;</li><li>Kiến trúc bộ nhớ kênh đôi&nbsp;</li><li>Hỗ trợ mô-đun bộ nhớ Intel&nbsp;Extreme Memory Profile (XMP)</li><li>Hỗ trợ DIMM Flex</li><li>Công nghệ NitroPath DRAM</li><li>DIMM Fit</li><li>ASUS Enhanced Memory Profile III (AEMP III)</li></ul><p>* Các loại bộ nhớ, tốc độ dữ liệu và số lượng mô-đun DRAM được hỗ trợ khác nhau tùy thuộc vào cấu hình CPU và bộ nhớ, để biết thêm thông tin, vui lòng tham khảo danh sách Hỗ trợ CPU/Bộ nhớ trong tab Hỗ trợ của trang thông tin sản phẩm hoặc truy cập https://www.asus.com/support/download-center/.<br>* Bộ nhớ DDR5 Không ECC, không đệm hỗ trợ chức năng ECC trên khuôn.</p></td></tr><tr><td><strong>Đồ họa</strong></td><td><ul><li>1 x DisplayPort **&nbsp;</li><li>1 x Cổng HDMI ***&nbsp;</li><li>2 x Intel Thunderbolt&nbsp;4 hỗ trợ đầu ra video DisplayPort và Thunderbol****</li></ul><p>* Thông số kỹ thuật đồ họa có thể khác nhau giữa các loại CPU. Vui lòng tham khảo www.intel.com để biết bất kỳ bản cập nhật nào.<br>** Hỗ trợ tối đa 8K@60Hz như được chỉ định trong DisplayPort 1.4.<br>*** Hỗ trợ tối đa 8K@60Hz như được chỉ định trong HDMI 2.1. &nbsp;<br>**** Ở chế độ Thunderbolt&nbsp;4, hỗ trợ lên đến 8K@60Hz x1 với DSC hoặc 4K@60Hz x 2, tổng băng thông tối đa lên đến 23,8Gbps hoặc 16Gbps/16Gbps, để biết hỗ trợ độ phân giải, vui lòng kiểm tra thông số kỹ thuật DisplayPort 2.1.<br>***** Ở chế độ DP alt, Chỉ có một cổng USB Type-C hỗ trợ tối đa UHBR20 tại một thời điểm.<br>****** Hỗ trợ độ phân giải VGA phụ thuộc vào độ phân giải của bộ xử lý hoặc card đồ họa.<br>*******Khi cài đặt hệ điều hành, hãy đảm bảo rằng màn hình của bạn được kết nối với cổng HDMI trên bảng I/O phía sau hoặc với card đồ họa rời.</p></td></tr><tr><td><strong>Khe mở rộng</strong></td><td><p><strong>Bộ xử lý Intel Core Ultra (Series 2) *</strong></p><ul><li>1 khe cắm PCIe 5.0 x16 (hỗ trợ chế độ x16 hoặc x8/x4/x4)</li></ul><p><strong>Chipset Intel Z890</strong></p><ul><li>1 khe cắm PCIe 4.0 x 16&nbsp;(hỗ trợ chế độ x4)</li></ul><p>* Vui lòng kiểm tra bảng phân nhánh PCIe trên trang web hỗ trợ (https://www.asus.com/support/FAQ/1037507/).<br>- Để đảm bảo khả năng tương thích của thiết bị được cài đặt, vui lòng tham khảo https://www.asus.com/support/ để biết danh sách các thiết bị ngoại vi được hỗ trợ.</p></td></tr><tr><td><strong>Lưu trữ</strong></td><td><p><strong>Tổng cộng hỗ trợ 7 khe cắm M.2 và 4 cổng SATA 6Gb/s*</strong><br><strong>Bộ xử lý Intel Core Ultra (Series 2) *</strong></p><ul><li>Khe cắm M.2_1 (Khóa M), loại 2242/ 2260/ 2280/ 22110 (hỗ trợ chế độ PCIe 5.0 x4)&nbsp;</li><li>Khe cắm M.2_2 (Khóa M), loại 2280 (hỗ trợ chế độ PCIe 4.0 x4)&nbsp;</li><li>Khe cắm M.2_3 (Khóa M), loại 2242/ 2260/ 2280 (hỗ trợ chế độ PCIe 5.0 x4)**&nbsp;</li><li>Khe cắm M.2_4 (Khóa M), loại 2242/ 2260/ 2280 (hỗ trợ chế độ PCIe 5.0 x4)**&nbsp;</li></ul><p><strong>Chipset Intel ® Z890</strong></p><ul><li>Khe cắm M.2_5 (Khóa M), loại&nbsp;2280 (hỗ trợ chế độ PCIe 4.0 x4)</li><li>Khe cắm M.2_6 (Khóa M), loại&nbsp;2280 (hỗ trợ chế độ PCIe 4.0 x4)</li><li>Khe cắm M.2_7 (Khóa M), loại 2242/ 2260/ 2280 (hỗ trợ chế độ PCIe 4.0 x4 và SATA)</li><li>4 cổng SATA 6Gb/giây</li></ul><p>* Công nghệ Intel Rapid&nbsp;Storage hỗ trợ PCIe RAID 0/1/5/10, SATA RAID 0/1/5/10, khe cắm M.2 từ CPU chỉ hỗ trợ RAID 0/1/5<br>** Khe cắm M.2_3 &amp; M.2_4 chia sẻ băng thông với PCIEX16(G5). Khi M.2_3 &amp; M.2_4 được sử dụng bởi các thiết bị SSD, PCIEX16(G5) sẽ chỉ chạy x8.</p></td></tr><tr><td><strong>LAN</strong></td><td>1 x Realtek 5Gb Ethernet<br>ASUS LANGuard</td></tr><tr><td><strong>Không dây &amp; Bluetooth</strong></td><td><strong>Wi-Fi 7*&nbsp;</strong><br>2x2 Wi-Fi 7 (802.11be)<br>Hỗ trợ băng tần 2,4/5/6GHz**<br>Hỗ trợ băng thông Wi-Fi 7 320MHz, tốc độ truyền tải lên đến 5,8Gbps.<br>Bluetooth&nbsp;v5.4***<br><br>*Các tính năng Wi-Fi có thể khác nhau tùy thuộc vào hệ điều hành<br>Đối với Windows 11, Wi-Fi 7 sẽ yêu cầu phiên bản 24H2 trở lên để có đầy đủ chức năng, Windows 11 21H2/22H2/23H2 chỉ hỗ trợ Wi-Fi 6E.<br>Đối với Windows 10, chỉ hỗ trợ Wi-Fi 6.<br>** Quy định về băng tần và băng thông Wi-Fi 6GHz có thể khác nhau giữa các quốc gia.<br>*** Phiên bản Bluetooth&nbsp;có thể khác nhau, vui lòng tham khảo trang web của nhà sản xuất mô-đun Wi-Fi để biết thông số kỹ thuật mới nhất.</td></tr><tr><td><strong>USB</strong></td><td><p><strong>Cổng USB phía sau (Tổng cộng 14 cổng)</strong></p><ul><li>2 cổng Thunderbolt&nbsp;4 (2 cổng USB Type-C)</li><li>9 cổng USB 10Gbps (7 x Type-A + 1 x USB Type-C* + 1 x USB Type-C&nbsp;with up to 30W PD Fast-charge)**</li><li>3 cổng USB 5Gbps (3 cổng Type-A)</li></ul><p><strong>Cổng USB phía trước (Tổng cộng 11 cổng)</strong></p><ul><li>1 đầu nối USB 20Gbps (hỗ trợ USB Type-C)</li><li>2 đầu cắm USB 5Gbps hỗ trợ bổ sung 4 cổng USB 5Gbps</li><li>3 đầu cắm USB 2.0 hỗ trợ bổ sung 6 cổng USB 2.0&nbsp;</li></ul><p>* Đầu ra cấp nguồn USB Type-C: tối đa 5V/3A<br>**&nbsp;Đầu ra cấp nguồn USB Type-C: tối đa 5V/9V. tối đa 3A, 12V 2,5A, tối đa 15V 2A.</p></td></tr><tr><td><strong>Âm thanh</strong></td><td><strong>ROG SupremeFX 7.1 Surround Sound High Definition Audio CODEC ALC4080*&nbsp;</strong><br>- Cảm biến trở kháng cho đầu ra tai nghe phía trước và phía sau<br>- Hỗ trợ: Phát hiện giắc cắm, Phát trực tuyến nhiều luồng, Phân nhiệm lại giắc cắm MIC mặt trước<br>- Đầu ra phát lại âm thanh nổi chất lượng cao với SNR 120 dB và đầu vào ghi âm với SNR 110 dB<br>- Hỗ trợ phát lại lên đến 32-Bit/384 kHz<br><strong>Tính năng âm thanh</strong><br>- Che chắn âm thanh<br>- Savitech SV3H712 AMP &nbsp;<br>- Giắc cắm âm thanh mạ vàng<br>- Cổng ra quang S/PDIF phía sau<br>- Tụ âm thanh cao cấp<br>- Nắp âm thanh<br>* Cổng LINE OUT ở mặt sau không hỗ trợ âm thanh không gian. Nếu bạn muốn sử dụng âm thanh không gian, hãy đảm bảo kết nối thiết bị đầu ra âm thanh của bạn với giắc cắm âm thanh ở mặt trước của khung máy hoặc sử dụng thiết bị âm thanh giao diện USB.</td></tr><tr><td><strong>Cổng I / O mặt sau</strong></td><td><ul><li>2 x cổng&nbsp;Thunderbolt&nbsp;4 (2 x USB Type-C)</li><li>9 x cổng&nbsp;USB 10Gbps (7 x Type-A + 1 x USB Type-C + 1 x USB Type-C&nbsp;with up to 30W PD Fast-charge)</li><li>3 x USB 5Gbps ports (3 x Type-A)</li><li>1 x cổng DisplayPort</li><li>1 x cổng HDMI</li><li>1 x Wi-Fi Module</li><li>1 x Realtek 5Gb Ethernet</li><li>2 x Gold-plated audio jacks</li><li>1 x cổng Optical S/PDIF out</li><li>1 x nút BIOS FlashBack</li><li>1 x nút Clear CMOS</li></ul></td></tr><tr><td><strong>Đầu nối I / O nội bộ</strong></td><td><p><strong>Liên Quan Đến Quạt và Làm Mát</strong></p><ul><li>1 x Đầu cắm&nbsp;quạt CPU 4 chân</li><li>1 x Đầu cắm&nbsp;quạt OPT CPU 4 chân</li><li>1 x Đầy cắm&nbsp;AIO Pump 4 chân</li><li>5 x Đầu cắm quạt khung gầm 4 chân</li></ul><p><strong>Liên Quan Đến Sức Mạnh</strong></p><ul><li>1 x Đầu nối nguồn chính 24 pin</li><li>2 x Đầu nối nguồn CPU 8 pin +12V</li></ul><p><strong>Liên Quan Đến Lưu Trữ</strong></p><ul><li>7 x khe M.2 (Key M)</li><li>4 x cổng SATA 6Gb/s</li></ul><p><strong>USB</strong></p><ul><li>1 x đầu nối USB 20Gbps (hỗ trợ USB Type-C)</li><li>2 x đầu cắm USB 5Gbps hỗ trợ&nbsp;bổ sung 4 cổng USB 5Gbps</li><li>3 x đầu cắm&nbsp;USB 2.0 hỗ trợ bổ sung 6 cổng USB 2.0</li></ul><p><strong>Linh tinh</strong></p><ul><li>3 x chân cắm Addressable&nbsp;Gen 2</li><li>1 x đầu cắm xâm nhập khung gầm</li><li>1 x bộ nhảy quá áp CPU</li><li>1 x nut FlexKey</li><li>1 x đầu cắm âm thanh bảng điều khiển phía trước (F_AUDIO)</li><li>1 x nút Start</li><li>1 x đầu cắm bảng điều khiển hệ thống 10-1 chân</li><li>1 x đầu cắm cảm biến nhiệt&nbsp;</li><li>1 x đầu cắm Thunderbolt (USB4)</li></ul></td></tr><tr><td><strong>Các tính năng đặc biệt</strong></td><td><strong>Extreme Engine Digi+</strong><br>- 5K Black Metallic Capacitors<br><strong>ASUS Q-Design</strong><br>- M.2 Q-Latch<br>- M.2 Q-Release<br>- M.2 Q-Slide<br>- PCIe Slot Q-Release Slim (with PCIe SafeSlot)<br>- Q-Antenna<br>- Q-Code<br>- Q-Dashboard<br>- Q-LED (CPU [red], DRAM [yellow], VGA [white], Boot Device [yellow green])<br>- Q-Slot<br><strong>ASUS Thermal Solution</strong><br>- M.2 heatsinks<br>- M.2 heatsink backplate<br>- VRM heatsink design<br><strong>ASUS EZ DIY</strong><br>- BIOS FlashBack™ button<br>- BIOS FlashBack™ LED<br>- Clear CMOS button<br>- ProCool II<br>- Pre-mounted I/O shield<br>- SafeSlot<br>- SafeDIMM<br><strong>Aura Sync</strong><br>- Addressable Gen2 headers</td></tr><tr><td><strong>Các tính năng độc đáo</strong></td><td><strong>ROG Exclusive Software</strong><br>- ROG CPU-Z<br>- Dolby Atmos<br><strong>ASUS Exclusive Software</strong><br>Armoury Crate<br>- AIDA64 Extreme (60 days free trial)<br>- Aura Creator<br>- Aura Sync<br>- Fan Xpert 4 (with AI Cooling II)<br>- GameFirst<br>- HWiNFO<br>- Power Saving<br>ASUS Driver Hub<br>ASUS GlideX<br>TurboV Core<br>USB Wattage Watcher<br>Adobe Creative Cloud (Free Trial)<br>Norton 360 for Gamers (60 Days Free Trial)<br>WinRAR (40 Days Free Trial)<br><strong>UEFI BIOS</strong><br>AI Overclocking Guide<br>ASUS EZ DIY<br>- ASUS CrashFree BIOS 3<br>- ASUS EZ Flash<br>- ASUS UEFI BIOS EZ Mode<br>- ASUS MyHotkey<br>NPU Boost<br>FlexKey</td></tr><tr><td><strong>BIOS</strong></td><td>256 Mb Flash ROM, UEFI AMI BIOS</td></tr><tr><td><strong>Khả năng quản lý</strong></td><td>WOL by PME, PXE</td></tr><tr><td><strong>Phụ kiện đi kèm</strong></td><td><p><strong>Cáp</strong></p><ul><li>2 x cáp SATA 6Gb/s</li></ul><p><strong>Bộ làm mát bổ sung</strong></p><ul><li>1 x Miếng tản nhiệt cho M.2 22110</li></ul><p><strong>Linh tinh</strong></p><p>1 x ASUS Wi-Fi Q-Antenna&nbsp;</p><p>1 x Bộ dây buộc cáp&nbsp;</p><p>1 x Gói M.2 Q-Latch&nbsp;</p><p>2 x Gói M.2 Q-Slides</p><p>&nbsp;1 x Móc chìa khóa ROG&nbsp;</p><p>1 x Miếng dán ROG Strix&nbsp;</p><p>7 x Cao su M.2</p><p><strong>Tài liệu</strong></p><ul><li>1 x Hướng dẫn bắt đầu nhanh</li></ul></td></tr><tr><td><strong>Hệ điều hành</strong></td><td>Windows 11 (22H2 &amp; later)</td></tr><tr><td><strong>Kích thước</strong></td><td>Hệ số hình thức ATX<br>12 inch x 9,6 inch (30,5 cm x 24,4 cm)</td></tr></tbody></table></figure>', '', 15790000, 15790000, 3, '', NULL, NULL, 7, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:10:12', '2026-01-08 20:10:12'),
(29, 'Bo mạch chủ MSI MPG Z890 EDGE TI WIFI (DDR5)', 'bo-mach-chu-msi-mpg-z890-edge-ti-wifi-ddr5', '', '<figure class=\"table\"><table><tbody><tr><td><strong>Bộ xử lý</strong></td><td><ul><li>Hỗ trợ Bộ xử lý Intel® Core™ Ultra (Dòng 2)</li><li>LGA 1851</li></ul></td></tr><tr><td><strong>Chipset</strong></td><td>Intel®&nbsp;Z890 Chipset</td></tr><tr><td><strong>Bộ nhớ</strong></td><td><p>4x DDR5 UDIMM, Dung lượng bộ nhớ tối đa 256GB<br>Hỗ trợ bộ nhớ 9200 - 6400 (OC) MT/giây / 6400 - 4800 (JEDEC) MT/giây<br>Tần số ép xung tối đa:<br>• 1DPC 1R Tốc độ tối đa lên đến 9200+ MT/giây<br>• 1DPC 2R Tốc độ tối đa lên đến 7200+ MT/giây<br>• 2DPC 1R Tốc độ tối đa lên đến 4800+ MT/giây<br>• 2DPC 2R Tốc độ tối đa lên đến 4800+ MT/giây</p><p>Hỗ trợ Intel POR Speed ​​và JEDEC Speed<br>Hỗ trợ ép xung bộ nhớ và Intel XMP 3.0<br>Hỗ trợ chế độ Dual-Controller Dual-Channel<br>Hỗ trợ bộ nhớ Non-ECC, Un-buffered<br>Hỗ trợ CUDIMM</p><p>• Các khe cắm DIMM trên bo mạch chủ này có chốt một bên.<br>• Khả năng tương thích bộ nhớ và tốc độ được hỗ trợ có thể khác nhau tùy thuộc vào cấu hình CPU và bộ nhớ. Để biết thông tin chi tiết, vui lòng tham khảo Danh sách tương thích bộ nhớ có trên trang Hỗ trợ của sản phẩm hoặc truy cập https://www.msi.com/support/.</p></td></tr><tr><td><strong>Đồ họa tích hợp</strong></td><td><p>1x HDMI™<br>Hỗ trợ HDMITM 2.1 với cổng FRL, độ phân giải tối đa 8K 60Hz*<br>2x Type-C DisplayPort<br>ThundeboltTM 4 cổng, hỗ trợ DisplayPort 1.4 với HBR3 qua USB Type-C, với độ phân giải tối đa 8K@60Hz*</p><p>*Chỉ khả dụng trên bộ xử lý có đồ họa tích hợp. Thông số kỹ thuật đồ họa có thể thay đổi tùy thuộc vào CPU được cài đặt.</p></td></tr><tr><td><strong>Khe mở rộng</strong></td><td>2x khe cắm PCI-E x16<br>1x khe cắm PCI-E x1<br>PCI_E1 Gen PCIe 5.0 hỗ trợ tối đa x16 (Từ CPU)<br>PCI_E2 Gen PCIe 4.0 hỗ trợ tối đa x1 (Từ Chipset)<br>PCI_E3 Gen PCIe 4.0 hỗ trợ tối đa x4 (Từ Chipset)</td></tr><tr><td><strong>Audio</strong></td><td>Realtek®&nbsp;ALC1220P Codec<br>7.1-Channel USB High Definition Audio<br>Supports S/PDIF output</td></tr><tr><td><strong>Lưu trữ</strong></td><td><p>&nbsp;</p><ul><li>5x M.2</li><li>M.2_1 Nguồn (Từ CPU) hỗ trợ tối đa PCIe 5.0 x4, hỗ trợ các thiết bị 2280/2260</li><li>M.2_2 Nguồn (Từ CPU) hỗ trợ tối đa PCIe 4.0 x4, hỗ trợ các thiết bị 2280/2260</li><li>M.2_3 Nguồn (Từ Chipset) hỗ trợ tối đa PCIe 4.0 x4, hỗ trợ các thiết bị 2280/2260</li><li>M.2_4 Nguồn (Từ Chipset) hỗ trợ tối đa PCIe 4.0 x4, hỗ trợ các thiết bị 22110/2280</li><li>M.2_5 Nguồn (Từ Chipset) hỗ trợ tối đa PCIe 4.0 x4 / chế độ SATA, hỗ trợ các thiết bị 22110/2280/2260</li><li>4x SATA 6G</li></ul><p>*Vui lòng tham khảo hướng dẫn để biết các hạn chế về tản nhiệt SSD M.2.</p><p>&nbsp;</p><p>&nbsp;</p></td></tr><tr><td><strong>RAID</strong></td><td>Hỗ trợ RAID 0, RAID 1, RAID 5 và RAID 10 cho các thiết bị lưu trữ SATA<br>Hỗ trợ RAID 0, RAID 1, RAID 5 và RAID 10 cho các thiết bị lưu trữ M.2 NVMe</td></tr><tr><td><strong>Thunderbolt tích hợp</strong></td><td><ul><li>2 cổng Thunderbolt4 (Phía sau)</li></ul><p>Hỗ trợ tốc độ truyền lên đến 40Gbps với các thiết bị Thunderbolt<br>Hỗ trợ tốc độ truyền lên đến 20Gbps với các thiết bị USB4<br>Hỗ trợ tốc độ truyền lên đến 10Gbps với các thiết bị USB 3.2<br>Hỗ trợ sạc nguồn lên đến 5V/3A, 15W<br>Mỗi cổng có thể nối tiếp tối đa ba thiết bị Thunderbolt 4 hoặc năm thiết bị Thunderbolt 3<br>Hỗ trợ màn hình lên đến 8K</p></td></tr><tr><td><strong>USB</strong></td><td>1x USB 2.0 (Phía sau)<br>4x USB 2.0 (Phía trước)<br>4x USB 5Gbps Type A (Phía sau)<br>2x USB 5Gbps Type A (Phía trước)<br>4x USB 10Gbps Type A (Phía sau)<br>1x USB 10Gbps Type C (Phía sau)<br>1x USB 20Gbps Type C (Phía trước)<br>*USB 20Gbps Type-C (Phía trước) hỗ trợ sạc nhanh USB PD 27W cho JUSBC1</td></tr><tr><td><strong>LAN</strong></td><td>1x Intel®&nbsp;Killer™ E5000B 5G LAN</td></tr><tr><td><strong>Công I/O nội bộ</strong></td><td><ul><li>1x Đầu nối thẻ Thunderbolt5 (JTBT5, hỗ trợ RTD3)</li><li>1x Đầu nối nguồn (ATX_PWR)</li><li>2x Đầu nối nguồn (CPU_PWR)</li><li>1x Đầu nối nguồn (PCIE_PWR 8 chân)</li><li>1x Quạt CPU</li><li>1x Quạt kết hợp (Quạt Pump_Sys)</li><li>6x Quạt hệ thống</li><li>1x Đầu cắm EZ Conn (JAF_2)</li><li>2x Bảng điều khiển phía trước (JFP)</li><li>1x Chống xâm nhập khung máy (JCI)</li><li>1x Âm thanh phía trước (JAUD)</li><li>1x Đầu nối cảm biến nhiệt (T_SEN)</li><li>3x Đầu nối đèn LED RGB V2 có thể định địa chỉ (JARGB_V2)</li><li>1x Đầu nối đèn LED RGB (JRGB)</li><li>1x Đầu cắm chân TPM (Hỗ trợ TPM 2.0)</li><li>4x Cổng USB 2.0</li><li>2x Cổng USB 5Gbps Loại A</li><li>1x Cổng USB 20Gbps Loại C</li></ul></td></tr><tr><td><strong>WIRELESS LAN &amp; BLUETOOTH</strong></td><td><p>Intel® Killer™ BE1750x Wi-Fi 7<br>Mô-đun không dây được cài đặt sẵn trong khe cắm M.2 (Key-E)<br>Hỗ trợ MU-MIMO TX/RX, 2,4 GHz/ 5 GHz/ 6 GHz* (320 MHz) lên đến 5,8 Gbps<br>Hỗ trợ 802.11 a/ b/ g/ n/ ac/ ax/ be</p><p>Hỗ trợ Bluetooth® 5.4**, MLO, 4KQAM</p><p>* Hỗ trợ băng tần 6 GHz có thể tùy thuộc vào quy định của từng quốc gia và Wi-Fi 7 sẽ có sẵn trong Windows 11 phiên bản 24H2.<br>** Phiên bản Bluetooth có thể được cập nhật, vui lòng tham khảo trang web của nhà cung cấp chipset Wi-Fi để biết chi tiết. Bluetooth 5.4 sẽ có sẵn trong Windows 11 phiên bản 24H2.</p></td></tr><tr><td><strong>Tính năng LED</strong></td><td>4x EZ Debug LED<br>1x EZ Digit Debug LED</td></tr><tr><td><strong>Công I/O sau</strong></td><td><ul><li>USB 5Gbps Type-A</li><li>5G LAN</li><li>USB 10Gbps Type-A</li><li>Wi-Fi / Bluetooth</li><li>Đầu nối âm thanh</li><li>Nút Clear CMOS</li><li>Nút Flash BIOS</li><li>HDMI™</li><li>Thunderbolt 4</li><li>USB 10Gbps Type-C</li><li>USB 2.0</li><li>Đầu ra S/PDIF quang</li></ul></td></tr><tr><td><strong>Hệ điều hành</strong></td><td>Support for Windows®&nbsp;11 64-bit</td></tr><tr><td><strong>Kích thước</strong></td><td><ul><li>ATX</li><li>243.84mmx304.8mm</li></ul></td></tr></tbody></table></figure>', '', 12690000, 12690000, 3, '', NULL, NULL, 0, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:11:36', '2026-01-08 20:11:36');
INSERT INTO `products` (`id`, `name`, `slug`, `description`, `specifications`, `short_description`, `price`, `sale_price`, `category_id`, `brand`, `brand_id`, `sku`, `stock`, `featured`, `status`, `rating`, `review_count`, `sold_count`, `views`, `created_at`, `updated_at`) VALUES
(30, 'Bo mạch chủ ASUS ROG MAXIMUS Z890 EXTREME (DDR5)', 'bo-mach-chu-asus-rog-maximus-z890-extreme-ddr5', '', '<figure class=\"table\"><table><tbody><tr><td><a href=\"https://gearvn.com/collections/cpu-bo-vi-xu-ly\"><strong>CPU</strong></a></td><td><ul><li>Hỗ trợ Bộ xử lý Intel Core™ Ultra (Dòng 2), LGA1851&nbsp;</li><li>Hỗ trợ Công nghệ Intel Turbo Boost 2.0 và Công nghệ Intel Turbo Boost Max 3.0**</li></ul><p>* Tham khảo https://www.asus.com/support/download-center/ để biết danh sách hỗ trợ CPU.<br>** Hỗ trợ Công nghệ Intel Turbo Boost Max 3.0 tùy thuộc vào loại CPU.</p></td></tr><tr><td><strong>Chipset</strong></td><td>Intel® Z890 Chipset</td></tr><tr><td><strong>Bộ nhớ</strong></td><td><ul><li>4 x Khe DIMM, tối đa 192GB, DDR5</li><li>Support up to 8800+MT/s (OC), Non-ECC, Un-buffered ,Clocked Unbuffered DIMM (CUDIMM)*</li><li>Kiến trúc bộ nhớ kênh đôi</li><li>Hỗ trợ mô-đun bộ nhớ Intel&nbsp;Extreme Memory Profile (XMP)</li><li>ASUS Enhanced Memory Profile III (AEMP III)</li><li>Hỗ trợ DIMM Flex</li><li>DIMM Fit&nbsp;</li><li>Công Nghệ NitroPath DRAM</li></ul><p>* Các loại bộ nhớ được hỗ trợ, tốc độ dữ liệu (tốc độ) và số lượng mô-đun DRAM khác nhau tùy thuộc vào cấu hình CPU và bộ nhớ, để biết thêm thông tin, vui lòng tham khảo danh sách Hỗ trợ CPU/bộ nhớ trong tab Hỗ trợ của trang thông tin sản phẩm hoặc truy cập https://www.asus.com/support/download-center/.<br>* Non-ECC, bộ nhớ DDR5 un-buffered hỗ trợ chức năng On-Die ECC.</p></td></tr><tr><td><strong>Đồ họa</strong></td><td><ul><li>1 x cổng HDMI™**</li><li>2 cổng Intel&nbsp;Thunderbolt™ 5 (USB Type-C) hỗ trợ đầu ra video DisplayPort và Thunderbolt™***</li></ul><p>* Thông số đồ hoạ có thể khác nhau tuỳ vào loại CPU. Vui lòng tham khảo www.intel.com để cập nhật thông tin.<br>**Hỗ trợ tối đa 8K@60Hz với DSC như được chỉ định trong HDMI 2.1.<br>***Hỗ trợ tối đa 1 màn hình 8K@60HZ(chế độ DSC)+2 màn hình 4K@60HZ, Nên kết nối chuỗi các màn hình bằng cáp Thunderbolt™ đã được xác minh.<br>****Để được hỗ trợ về độ phân giải, vui lòng kiểm tra thông số kỹ thuật DisplayPort 2.1. Băng thông bị giới hạn bởi DisplayPort™ 2.1 lên tới 77,4 Gbit/giây.<br>*****Hỗ trợ độ phân giải VGA phụ thuộc vào độ phân giải của bộ xử lý hoặc card đồ họa.</p></td></tr><tr><td><strong>Khe mở rộng</strong></td><td><p><strong>Bộ xử lý Intel® Core™ Ultra (Series 2)*</strong></p><ul><li>2 khe cắm PCIe 5.0 x16 (hỗ trợ chế độ x16 hoặc x8/x8 hoặc x8/x4/x4)**</li></ul><p><strong>Chipset Intel® Z890**</strong></p><ul><li>1 khe cắm PCIe 4.0x4</li></ul><p>* Vui lòng kiểm tra bảng phân nhánh PCIe trên trang web hỗ trợ (https://www.asus.com/support/FAQ/1037507/).<br>**M.2_3 &amp; M.2_4 chia sẻ băng thông với PCIEX16(G5)_2. Khi M.2_3 được bật, PCIEX16(G5)_1 sẽ chạy x8 &amp; PCIEX16(G5)_2 sẽ chạy x4. Khi M.2_3 &amp; M.2_4 được bật, PCIEX16(G5)_1 sẽ chạy x8 &amp; PCIEX16(G5)_2 sẽ tắt.<br>- Để đảm bảo khả năng tương thích của thiết bị được cài đặt, vui lòng tham khảo https://www.asus.com/support/download-center/ để biết danh sách các thiết bị ngoại vi được hỗ trợ.</p></td></tr><tr><td><strong>Lưu trữ</strong></td><td><p><strong>Tổng cộng hỗ trợ 6 khe cắm M.2 và 4 cổng SATA 6Gb/s*</strong><br><strong>Hỗ trợ Bộ xử lý Intel® Core™ Ultra (Series 2)*</strong></p><ul><li>Khe cắm M.2_1 (Key M), loại 2242/ 2260/ 2280/ 22110 (hỗ trợ chế độ PCIe 5.0 x4)</li><li>Khe cắm M.2_2 (Key M), loại 2242/ 2260/ 2280 (hỗ trợ chế độ PCIe 4.0 x4)</li><li>Khe cắm M.2_3 (Key M), loại 2242/ 2260/ 2280/ 22110 (hỗ trợ chế độ PCIe 5.0 x4)**</li><li>Khe cắm M.2_4 (Key M), loại 2242/ 2260/ 2280 (hỗ trợ chế độ PCIe 5.0 x4)**</li></ul><p><strong>Chipset Intel® Z890</strong></p><ul><li>Khe cắm DIMM.2_1 (Key M) qua ROG Q-DIMM.2, loại 2230/ 2242/ 2260/ 2280/ 22110 (hỗ trợ chế độ PCIe 4.0 x4)</li><li>Khe cắm DIMM.2_2 (Key M) qua ROG Q-DIMM.2, loại 2230/ 2242/ 2260/ 2280/ 22110 (hỗ trợ chế độ PCIe 4.0 x4)</li><li>4 x Cổng SATA 6Gb/giây</li></ul><p>*Công nghệ Intel® Rapid Storage hỗ trợ PCIe RAID 0/1/5/10, SATA RAID 0/1/5/10, khe cắm M.2 từ CPU chỉ hỗ trợ RAID 0/1/5.<br>**M.2_3 &amp; M.2_4 chia sẻ băng thông với PCIEX16(G5)_2. Khi M.2_3 được bật, PCIEX16(G5)_1 sẽ chạy x8 &amp; PCIEX16(G5)_2 sẽ chạy x4. Khi M.2_3 và M.2_4 được bật, PCIEX16(G5)_1 sẽ chạy x8 và PCIEX16(G5)_2 sẽ bị tắt.</p></td></tr><tr><td><strong>LAN</strong></td><td>1 x Intel® 2.5Gb Ethernet<br>1 x Marvell® AQtion 10Gb Ethernet<br>ASUS LANGuard</td></tr><tr><td><strong>Không dây &amp; Bluetooth</strong></td><td><strong>Intel® Wi-Fi 7*</strong><br>2x2 Wi-Fi 7 (802.11be)<br>Hỗ trợ băng tần 2,4/5/6GHz**<br>Hỗ trợ Wi-Fi 7 Băng thông 320 MHz, tốc độ truyền lên tới 5,8Gbps.<br>Bluetooth® v5.4***<br><br>* Các tính năng Wi-Fi có thể khác nhau tùy thuộc vào hệ điều hành Đối với Windows 11, Wi-Fi 7 sẽ yêu cầu phiên bản 24H2 trở lên để có đầy đủ chức năng, Windows 11 21H2/22H2/23H2 chỉ hỗ trợ Wi-Fi 6E. Đối với Windows 10, chỉ hỗ trợ Wi-Fi 6.<br>** Quy định về băng tần và băng thông Wi-Fi 6GHz có thể khác nhau giữa các quốc gia.<br>*** Các phiên bản Bluetooth có thể khác nhau, vui lòng tham khảo trang web của nhà sản xuất mô-đun Wi-Fi để biết thông số kỹ thuật mới nhất.</td></tr><tr><td><strong>USB</strong></td><td><p><strong>USB phía sau (Tổng số 10 cổng)</strong></p><ul><li>2 x&nbsp;Cổng Thunderbolt&nbsp;5 (2 x USB Type-C)&nbsp;</li><li>1 x Cổng USB 20Gbps (1 x USB Type-C)&nbsp;</li><li>7 x Cổng USB 10Gbps (5 x Type-A + 2 x USB Type-C)</li></ul><p><strong>USB phía trước (Tổng số 10 cổng)</strong></p><ul><li>1 x đầu nối USB 20Gbps (hỗ trợ USB Type-C&nbsp;với công suất lên tới 60W PD/QC4+)*&nbsp;</li><li>1 x đầu nối USB 10Gbps (hỗ trợ USB Type-C)&nbsp;</li><li>2 x đầu cắm&nbsp;USB 5Gbps hỗ trợ bổ sung 4 cổng USB 5Gbps&nbsp;</li><li>2 x đầu cắm&nbsp;USB 2.0 hỗ trợ bổ sung 4 cổng USB 2.0</li></ul><p>Đầu ra phân phối điện USB Type-C: tối đa 5V/3A&nbsp;</p><p>*Đầu ra phân phối nguồn USB Type-C: 5/9/15/20V tối đa 3A, PPS: 3.3—21V tối đa 3A</p></td></tr><tr><td><strong>Âm thanh</strong></td><td><p><strong>Âm thanh vòm ROG SupremeFX 7.1 Âm thanh độ phân giải cao CODEC ALC4082**</strong></p><ul><li>Cảm biến trở kháng cho đầu ra tai nghe phía trước và phía sau</li><li>Hỗ trợ: Phát hiện giắc cắm, Đa luồng, Giắc cắm MIC mặt trước</li><li>Đầu ra phát lại âm thanh nổi SNR 120 dB chất lượng cao và đầu vào ghi SNR 110 dB</li><li>Hỗ trợ phát lại lên đến 32 bit/384 kHz trên bảng điều khiển phía trước</li></ul><p><br><strong>Tính Năng Âm Thanh</strong></p><ul><li>Công nghệ giáp SupremeFX</li><li>ESS&nbsp;ES9219 QUAD DAC</li><li>Các giắc âm thanh chiếu sáng bằng LED</li><li>Cổng ra S/PDIF quang học phía sau</li><li>Tụ âm thanh cao cấp</li></ul><p><br>* Cần có khung với mô-đun âm thanh HD ở bảng điều khiển phía trước để hỗ trợ đầu ra âm thanh vòm 7.1.<br>** Cổng LINE OUT trên bảng điều khiển phía sau không hỗ trợ âm thanh không gian. Nếu bạn muốn sử dụng âm thanh không gian, hãy đảm bảo kết nối thiết bị đầu ra âm thanh của bạn với giắc âm thanh trên bảng điều khiển phía trước của khung máy hoặc sử dụng thiết bị âm thanh giao diện USB.</p></td></tr><tr><td><strong>Cổng I / O mặt sau</strong></td><td><ul><li>2 x cổng&nbsp;Thunderbolt™ 5 USB Type-C</li><li>1 x cổng USB 20Gbps (1 x USB Type-C)</li><li>7 x cổng USB 10Gbps (5 x Type-A + 2 x USB Type-C)</li><li>1 x cổng HDMI™</li><li>1 x Mô-đun Wi-Fi&nbsp;</li><li>1 x cổng Ethernet Intel® 2.5Gb</li><li>1 x cổng Ethernet Marvell® AQtion 10Gb</li><li>2 x đầu cắm âm thanh chiếu sáng bởi LED</li><li>1 x cổng ra S/PDIF quang</li><li>1 x nút BIOS FlashBack™</li><li>1 x nút Clear CMOS</li></ul></td></tr><tr><td><strong>Đầu nối I / O nội bộ</strong></td><td><p><strong>Liên Quan Đến Quạt và Làm Mát</strong></p><ul><li>1 x Đầu&nbsp;cắm&nbsp;quạt CPU 4 chân</li><li>1 x Đầu&nbsp;cắm&nbsp;quạt OPT CPU 4 chân</li><li>2 x Đầu&nbsp;cắm&nbsp;quạt khung gầm 4 chân</li><li>2 x Đầu&nbsp;cắm&nbsp;quạt Radiator 4 chân</li><li>2 x Đầu&nbsp;cắm W_PUMP+</li><li>1 x Đầu&nbsp;cắm WB_SENSOR</li></ul><p><strong>Liên Quan Đến Sức Mạnh</strong></p><ul><li>1 x Đầu nối nguồn chính 24 chân</li><li>2 x Đầu nối nguồn CPU 8 pin +12V</li><li>1 x Đầu nối nguồn PCIe 8 chân</li></ul><p><strong>Liên Quan Đến Lưu Trữ</strong></p><ul><li>4 x Khe M.2 (Key M)</li><li>1 x Khe cắm DIMM.2 hỗ trợ 2 khe M.2 (Key M)</li><li>4 x Cổng SATA 6Gb/s</li></ul><p><strong>USB</strong></p><ul><li>1 x Đầu nối USB 20Gbps (hỗ trợ USB Type-C)</li><li>1 x Đầu nối&nbsp; USB 10Gbps connector (hỗ trợ USB Type-C)</li><li>2 x Đầu cắm USB 5Gbps hỗ trợ bổ sung 4 cổng USB 5Gbps</li><li>2 x Đầu cắm&nbsp;USB 2.0 hỗ trợ bổ sung 4 cổng USB 2.0</li></ul><p><strong>Linh Linh</strong></p><ul><li>1 x đầu cắm 6-pin ARGB Gen 2 hỗ trợ ra 2 đầu cắm&nbsp;ARGB Gen 2&nbsp;</li><li>2 x đầu cắm Addressable Gen 2</li><li>1 x công tắc thay đổi chế độ PCIe</li><li>4 x nút BCLK button</li><li>1 x nut chuyển BIOS</li><li>1 x nút FlexKey</li><li>1 x đầu cắm&nbsp;âm thanh bảng điều khiển phía trước (F_AUDIO)</li><li>1 x bộ nhảy chế độ LN2</li><li>18 x điểm đo&nbsp;ProbeIt</li><li>1 x nút ReTry</li><li>2 x công tắc RSVD</li><li>1 x đầu cắm RSVD</li><li>1 x nút Safe Boot</li><li>1 x công tắc Slow Mode</li><li>1 x nút Start</li><li>1 x đầu cắm bảng hệ thống 10-1 pin</li><li>1 x đầu cắm cảm biến nhiệt</li></ul></td></tr><tr><td><strong>Các tính năng đặc biệt</strong></td><td><strong>Extreme OC Kit</strong><br>- FlexKey button<br>- LN2 Mode<br>- ProbeIt<br>- ReTry button<br>- Safe boot button<br>- Start button<br>- Slow Mode<br><strong>Extreme Engine Digi+</strong><br>- Tụ điện 10K Black Metallic<br>- MicroFine Alloy Choke<br><strong>ASUS Q-Design</strong><br>- M.2 Q-Latch<br>- M.2 Q-Release<br>- M.2 Q-Slide<br>- Q-Release Slim (with PCIe SafeSlot)<br>- Q-Antenna<br>- Q-Code<br>- Q-Connector<br>- Q-Dashboard<br>- Q-LED (CPU [màu đỏ], DRAM [màu vàng], VGA [màu trắng], Boot Device [màu xanh lá])<br>- Q-Slot<br>- Q-DIMM.2<br><strong>ASUS Thermal Solution</strong><br>- Fan bracket<br>- M.2 heatsink backplate<br>- M.2 heatsink<br>- Metal backplate<br>- VRM heatsink design<br>- 3D VC M.2 Heatsink<br><strong>ASUS EZ DIY</strong><br>- Backplate<br>- BIOS FlashBack™ button<br>- Clear CMOS button<br>- CPU Socket lever protector<br>- ProCool II<br>- Pre-mounted I/O shield<br>- SafeSlot<br>- SafeDIMM<br><strong>Aura Sync</strong><br>- Đầu cắm Addressable Gen 2<br><strong>Dual BIOS</strong><br><strong>Full Color 5\" LCD Display</strong><br><strong>ROG M.2 PowerBoost</strong><br><strong>Front Panel USB 20Gbps with Quick Charge 4+ Support</strong><br>- Support: up to 60W fast charging and USB Wattage Watcher*<br>- Output: 5/9/15/20V max. 3A, PPS:3.3–21V max. 3A<br>- Compatible with PD3.0 and PPS<br>* To support 60W, please install the power cable to the 8-pin PCIe power connector or else only 27W will be supported.</td></tr><tr><td><strong>Các tính năng độc đáo</strong></td><td><strong>Phần mềm độc quyền ROG</strong><br>- ROG CPU-Z<br>- Dolby Atmos<br>- Bảo mật Internet (phiên bản đầy đủ 1 năm)<br><strong>Phần mềm độc quyền ASUS</strong><br>Armoury Crate<br>- AIDA64 Extreme (Phiên bản đầy đủ 1 năm)<br>- Aura Creator<br>- Aura Sync<br>- Fan Xpert 4 (với AI Cooling II)<br>- GameFirst<br>- HWiNFO<br>- Power Saving<br>- LCD Display<br>ASUS AI Advisor<br>ASUS Driver Hub<br>Turbo Vcore<br>ASUS GlideX<br>Thunderbolt™ Share<br>USB Wattage Watcher<br>WinRAR (Dùng thử miễn phí 40 ngày)<br>Adobe Creative Cloud (Dùng thử miễn phí)<br><strong>UEFI BIOS</strong><br>AI Overclocking Guide<br>NPU Boost<br>ASUS EZ DIY<br>- ASUS CrashFree BIOS 3<br>- ASUS EZ Flash 3<br>- ASUS UEFI BIOS EZ Mode<br>- ASUS MyHotkey</td></tr><tr><td><strong>BIOS</strong></td><td>2 x 256 Mb Flash ROM, UEFI AMI BIOS</td></tr><tr><td><strong>Khả năng quản lý</strong></td><td>WOL by PME</td></tr><tr><td><strong>Phụ kiện đi kèm</strong></td><td><p><strong>Cáp</strong></p><ul><li>1 x Cáp chia 1-to-3 ARGB</li><li>1 x Cáp chia 1-to-2 ARGB</li><li>2 x Cáp chia quạt 1-to-4</li><li>2 x Gói cáp ROG weave SATA 6G</li><li>1 x Gói cáp Thermistor 3 trong 1</li></ul><p><strong>ROG Q-DIMM.2 có Tản nhiệt</strong></p><ul><li>1 x ROG DIMM.2 có tản nhiệt</li><li>1 x Gói miếng đệm M.2 cho ROG Q-DIMM.2</li><li>2 x Miếng đệm nhiệt cho ROG Q-DIMM.2</li></ul><p><strong>Bộ tản nhiệt bổ sung</strong></p><ul><li>2 x Miếng đệm nhiệt cho M.2 22110</li><li>1 x Giá đỡ quạt DDR5</li></ul><p><strong>Linh Linh</strong></p><ul><li>1 x Ăng ten Q-Wifi ASUS</li><li>1 x Đầu nối Q</li><li>1 x Nhãn dán logo ROG</li><li>1 x Tua vít ROG</li><li>1 x Nhãn dán ROG</li><li>1 x Thẻ VIP ROG</li><li>2 x Gói M.2 Q-Slide</li><li>6 x Gói cao su ốp lưng M.2</li><li>1 x Dụng cụ khui bia ROG</li></ul><p><strong>Phương tiện cài đặt</strong></p><ul><li>1 x Ổ USB có tiện ích và trình điều khiển</li></ul><p><strong>Tài liệu</strong></p><ul><li>1 x Hướng dẫn bắt đầu nhanh</li></ul></td></tr><tr><td><strong>Hệ điều hành</strong></td><td>Windows 11 (22H2 &amp; later)</td></tr><tr><td><strong>Kích thước</strong></td><td>Hệ số hình thức E-ATX<br>12 inch x 10,9 inch (30,5 cm x 27,7 cm)</td></tr></tbody></table></figure>', '', 28990000, 28990000, 3, '', NULL, NULL, 6, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:13:09', '2026-01-08 20:13:09'),
(31, 'Nguồn máy tính Jetek Elite 350W V2 (350W)', 'nguon-may-tinh-jetek-elite-350w-v2-350w', '', '<figure class=\"table\"><table><tbody><tr><td><strong>Thương hiệu</strong></td><td>Jetek</td></tr><tr><td><strong>Bảo hành</strong></td><td>36 Tháng</td></tr><tr><td><strong>Công suất tối đa</strong></td><td>350W</td></tr><tr><td><strong>Hiệu suất&nbsp;</strong></td><td>~ 78%</td></tr><tr><td><strong>Lọc nhiễu điện từ</strong></td><td>EMI đầu vào</td></tr><tr><td><strong>Tụ chính Samxon</strong></td><td>Ổn định hiệu năng</td></tr><tr><td><strong>Tmax</strong></td><td>85 độ C</td></tr><tr><td><strong>Số cổng cắm</strong></td><td>1 x 24-pin Main, 1 x 8-pin (4+4) CPU, 2 x (SATA + ATA)</td></tr><tr><td><strong>Quạt làm mát</strong></td><td>1 x 120 mm</td></tr><tr><td><strong>Nguồn đầu vào</strong></td><td>230VAC</td></tr><tr><td><strong>PFC</strong></td><td>Active</td></tr><tr><td><strong>Chứng nhận bảo vệ</strong></td><td>OPP, OVP, UVP, SCP</td></tr><tr><td><strong>Kích thước</strong></td><td>86 x 140 x 120 mm</td></tr></tbody></table></figure>', '', 450000, 450000, 3, '', NULL, NULL, 7, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:14:03', '2026-01-08 20:14:03'),
(32, 'Tản nhiệt AIO Cooler Master MASTERLIQUID 240L CORE ARGB', 'tan-nhiet-aio-cooler-master-masterliquid-240l-core-argb', '', '<figure class=\"table\"><table><tbody><tr><td><strong>HÃNG SẢN XUẤT:</strong></td><td>Cooler Master</td></tr><tr><td><strong>MODEL:</strong></td><td>MLW-D24M-A18PZ-R1</td></tr><tr><td><strong>SERIES:</strong></td><td>MasterLiquid Lite</td></tr><tr><td><strong>MÀU:</strong></td><td>Đen</td></tr><tr><td><strong>KÍCH THƯỚC KÉT NƯỚC</strong></td><td>240</td></tr><tr><td><strong>CPU SOCKET:</strong></td><td>LGA1700, LGA1200, LGA1151, LGA1150, LGA1155, LGA1156, AM5, AM4, AM3+, AM3, AM2+, AM2, FM2+, FM2</td></tr><tr><td><strong>VẬT LIỆU TẢN NHIỆT:</strong></td><td>Nhôm</td></tr><tr><td><strong>KÍCH THƯỚC BỘ TẢN NHIỆT:</strong></td><td>277 x 119.6 x 27.2 mm / 10.9 x 4.7 x 1.1 inch, 240</td></tr><tr><td><strong>KÍCH THƯỚC BƠM:</strong></td><td>81 x 76 x 47 mm / 3.2 x 3 x 1.9 inch</td></tr><tr><td><strong>TUỔI THỌ BƠM:</strong></td><td>&gt;70,000 Giờ</td></tr><tr><td><strong>ĐỘ ỒN CỦA BƠM:</strong></td><td>12 dBA (MAX)</td></tr><tr><td><strong>ĐẦU KẾT NỐI:</strong></td><td>3-Pin</td></tr><tr><td><strong>ĐIỆN ÁP ĐỊNH MỨC BƠM:</strong></td><td>12 VDC</td></tr><tr><td><strong>CÔNG SUẤT TIÊU THỤ:</strong></td><td>3.96W</td></tr><tr><td><strong>KÍCH THƯỚC QUẠT (D X R X C):</strong></td><td>120 x 120 x 25 mm / 4.7 x 4.7 x 1 inch</td></tr><tr><td><strong>SỐ LƯỢNG QUẠT:</strong></td><td>2 Quạt</td></tr><tr><td><strong>QUẠT LED:</strong></td><td>Addressable RGB</td></tr><tr><td><strong>TỐC ĐỘ QUẠT:</strong></td><td>650-1750 RPM ± 10%</td></tr><tr><td><strong>QUẠT AIRFLOW:</strong></td><td>71.93 CFM (Max)</td></tr><tr><td><strong>ĐỘ ỒN QUẠT:</strong></td><td>27.2 dBA (Max)</td></tr><tr><td><strong>FAN PRESSURE:</strong></td><td>1.86 mmH₂O (Max)</td></tr><tr><td><strong>Loại vòng bi quạt</strong></td><td>Rifle Bearing</td></tr><tr><td><strong>TUỔI THỌ QUẠT:</strong></td><td>160,000 Giờ</td></tr><tr><td><strong>ĐẦU KẾT NỐI:</strong></td><td>4-Pin (PWM)</td></tr><tr><td><strong>ĐỊNH MỨC ĐIỆN ÁP QUẠT</strong></td><td>12VDC</td></tr><tr><td><strong>ĐIỆN ÁP ĐẦU VÀO QUẠT</strong></td><td>0.26A</td></tr><tr><td><strong>Dòng điện an toàn</strong></td><td>0.37A</td></tr><tr><td><strong>Kiểu tản nhiệt</strong></td><td>Tản nhiệt nước CPU</td></tr></tbody></table></figure>', '', 1590000, 1590000, 3, '', NULL, NULL, 7, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:15:11', '2026-01-08 20:15:11'),
(33, 'Màn hình LG 24GS60F-B 24\" IPS 180Hz HDR10 Gsync chuyên game', 'man-hinh-lg-24gs60f-b-24-ips-180hz-hdr10-gsync-chuyen-game', '', '', '', 2890000, 2890000, 4, '', NULL, NULL, 5, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:16:03', '2026-01-08 20:16:03'),
(34, 'Màn hình MSI MAG 255F E20 25\" Rapid IPS 200Hz chuyên game', 'man-hinh-msi-mag-255f-e20-25-rapid-ips-200hz-chuyen-game', '', '', '', 2690000, 2690000, 4, '', NULL, NULL, 7, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:16:48', '2026-01-08 20:16:48'),
(35, 'Màn hình Asus ROG Strix XG27ACDMS 27\" QD-OLED 2K 280Hz Gsync chuyên game', 'man-hinh-asus-rog-strix-xg27acdms-27-qd-oled-2k-280hz-gsync-chuyen-game', '', '', '', 16490000, 16490000, 4, '', NULL, NULL, 5, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:17:33', '2026-01-08 20:17:33'),
(36, 'Màn hình MSI MAG 273QP QD-OLED X24 27\" QD-OLED 2K 240Hz', 'man-hinh-msi-mag-273qp-qd-oled-x24-27-qd-oled-2k-240hz', '', '', '', 15490000, 15490000, 4, '', NULL, NULL, 6, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:18:17', '2026-01-08 20:18:17'),
(37, 'Bàn phím cơ không dây E-Dra EK368RT Triple Mode Magnet Switch', 'ban-phim-co-khong-day-e-dra-ek368rt-triple-mode-magnet-switch', '', '', '', 1290000, NULL, 5, '', NULL, NULL, 6, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:19:12', '2026-01-08 20:19:12'),
(38, 'Bàn phím cơ E-Dra không dây EK375 Pro Alpha Brown Switch', 'ban-phim-co-e-dra-khong-day-ek375-pro-alpha-brown-switch', '', '', '', 1290000, NULL, 5, '', NULL, NULL, 7, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:19:47', '2026-01-08 20:19:47'),
(39, 'Bàn phím cơ gaming DareU EK106 Pro Haze Blue Cloud switch', 'ban-phim-co-gaming-dareu-ek106-pro-haze-blue-cloud-switch', '', '', '', 1250000, 1250000, 5, '', NULL, NULL, 0, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:20:24', '2026-01-08 20:20:24'),
(40, 'Bàn phím AKKO có dây TAC75 White HE Astrolink Magnetic Switch', 'ban-phim-akko-co-day-tac75-white-he-astrolink-magnetic-switch', '', '', '', 1190000, NULL, 5, '', NULL, NULL, 7, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:21:06', '2026-01-08 20:21:06'),
(41, 'Chuột Logitech G304 Wireless', 'chuot-logitech-g304-wireless', '', '', '', 720000, NULL, 6, '', NULL, NULL, 5, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:21:43', '2026-01-08 20:21:43'),
(42, 'Chuột ASUS P722 ROG KERIS II Origin WL Black', 'chuot-asus-p722-rog-keris-ii-origin-wl-black', '', '', '', 3350000, NULL, 6, '', NULL, NULL, 5, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:22:18', '2026-01-08 20:22:18'),
(43, 'Chuột không dây Logitech MX Master 4 Pale Grey', 'chuot-khong-day-logitech-mx-master-4-pale-grey', '', '', '', 3390000, NULL, 6, '', NULL, 'N/A', 7, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:22:55', '2026-01-08 20:23:10'),
(44, 'Chuột Logitech G Pro X Superlight 2 Dex Wireless Black', 'chuot-logitech-g-pro-x-superlight-2-dex-wireless-black', '', '', '', 3190000, 3190000, 6, '', NULL, NULL, 6, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:24:02', '2026-01-08 20:24:02'),
(45, 'Tai nghe Gaming Rapoo VH160S Black', 'tai-nghe-gaming-rapoo-vh160s-black', '', '', '', 390000, NULL, 7, '', NULL, NULL, 8, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:24:44', '2026-01-08 20:24:44'),
(46, 'Tai nghe Asus ROG Pelta WL RGB Black', 'tai-nghe-asus-rog-pelta-wl-rgb-black', '', '', '', 3490000, 3490000, 7, '', NULL, 'tainghe', 6, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:25:21', '2026-01-08 20:26:38'),
(47, 'Tai nghe không dây HyperX Cloud Flight 2 WL Black', 'tai-nghe-khong-day-hyperx-cloud-flight-2-wl-black', '', '', '', 3190000, NULL, 7, '', NULL, NULL, 8, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:26:11', '2026-01-08 20:26:11'),
(48, 'Tai nghe Logitech G522 Lightspeed Wireless White', 'tai-nghe-logitech-g522-lightspeed-wireless-white', '', '', '', 3590000, NULL, 7, '', NULL, NULL, 4, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:27:21', '2026-01-08 20:27:21'),
(49, 'Túi chống sốc GearVN 15\'\'', 'tui-chong-soc-gearvn-15', '', '', '', 100000, NULL, 8, '', NULL, NULL, 7, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:27:56', '2026-01-08 20:27:56'),
(50, 'Dây Cáp HDMI Dtech DT-UH0015G 2.0 1.5m', 'day-cap-hdmi-dtech-dt-uh0015g-20-15m', '', '', '', 100000, NULL, 8, '', NULL, NULL, 6, 0, 'active', 0.0, 0, 0, 0, '2026-01-08 20:28:35', '2026-01-08 20:28:35'),
(51, 'Sạc dự phòng Recci RPB-P12C White', 'sac-du-phong-recci-rpb-p12c-white', '', '', '', 350000, NULL, 8, '', NULL, NULL, 5, 1, 'active', 0.0, 0, 0, 0, '2026-01-08 20:29:12', '2026-01-08 20:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `is_primary`, `sort_order`, `created_at`) VALUES
(23, 13, 'uploads/products/product_13_23.png', 0, 0, '2026-01-08 01:28:23'),
(24, 13, 'uploads/products/product_13_24.png', 0, 0, '2026-01-08 01:28:23'),
(25, 13, 'uploads/products/product_13_25.png', 2, 0, '2026-01-08 01:28:23'),
(29, 15, 'uploads/products/product_15_29.jpg', 0, 0, '2026-01-08 01:55:52'),
(30, 15, 'uploads/products/product_15_30.png', 0, 0, '2026-01-08 01:55:52'),
(31, 15, 'uploads/products/product_15_31.png', 2, 0, '2026-01-08 01:55:52'),
(35, 18, 'https://product.hstatic.net/200000722513/product/go-steel-gray-04.tif-custom_d9cf3b00b19d4bb1bbbb1545a41395b5_1024x1024_1e039d29110145d1abc7e1a6ad184b65_master.png', 0, 0, '2026-01-08 10:33:50'),
(36, 18, 'https://product.hstatic.net/200000722513/product/ava_77563131fc2b48acb9a41ec545d9ed7d_master.png', 0, 0, '2026-01-08 10:33:50'),
(37, 18, 'https://product.hstatic.net/200000722513/product/swift_x_14_-_sfx14-71g_nx.kevsv.004_904714fce972422e993aba01c5880689_master.jpg', 2, 0, '2026-01-08 10:33:50'),
(38, 19, 'https://cdn.hstatic.net/products/200000722513/msi_modern_14_f13mg_bac_01_66ddd_4e8c1cfbdc6e42309ebb2a32372c638d_master.png', 0, 0, '2026-01-08 19:58:07'),
(39, 19, 'https://cdn.hstatic.net/products/200000722513/msi_modern_14_f13mg_bac_03_13f1d_069ad7b7085d4626a632dc419bb65c18_master.png', 1, 0, '2026-01-08 19:58:07'),
(40, 20, 'https://cdn.hstatic.net/products/200000722513/52713_laptop_acer_aspire_lite_15__1__91a3eab70cd1440bbed70f090d059c26_master.png', 0, 0, '2026-01-08 19:59:19'),
(41, 20, 'https://cdn.hstatic.net/products/200000722513/52713_laptop_acer_aspire_lite_15__2__6ffc5abf33294aeba3cbfaa6105cdd03_master.png', 1, 0, '2026-01-08 19:59:19'),
(42, 21, 'https://cdn.hstatic.net/products/200000722513/post-09_0bdc220b7ace476a8ff79a1ab46cc54f_master.jpg', 0, 0, '2026-01-08 20:00:50'),
(43, 22, 'https://cdn.hstatic.net/products/200000722513/pc_khach_msi_project_zero-01358_ec44feb9588946b6b6f4c5278766285a_master.jpg', 0, 0, '2026-01-08 20:02:09'),
(44, 23, 'https://product.hstatic.net/200000722513/product/pc_case_xigmatek_-_26_8cc60d3205d446d89294340c40b09d62_master.png', 0, 0, '2026-01-08 20:03:18'),
(45, 24, 'https://cdn.hstatic.net/products/200000722513/d_i_di_n_b9070076761f40318bb9cc7dbb5c8a0b_master.jpg', 0, 0, '2026-01-08 20:04:23'),
(47, 26, 'https://product.hstatic.net/200000722513/product/1024__5__993f76f3ac494f5fa788369004012b65_master.png', 0, 0, '2026-01-08 20:07:10'),
(48, 27, 'https://product.hstatic.net/200000722513/product/rog-astrix-rtx5090-o32g-gaming_box_with_card_nv_73341a59ff3e46e793de18d4ddbacd56_master.png', 0, 0, '2026-01-08 20:08:22'),
(49, 25, 'https://cdn.hstatic.net/products/200000722513/post-01_124b26a798054613a82353313947f827_master.jpg', 0, 0, '2026-01-08 20:08:45'),
(50, 28, 'https://product.hstatic.net/200000722513/product/rog-strix-z890-e-gaming-wifi-01_13f2ec4b1afe43d2bd763fc5bc31bada_master.jpg', 0, 0, '2026-01-08 20:10:12'),
(51, 29, 'https://product.hstatic.net/200000722513/product/msi-mpg_z890_edge_ti_wifi-box2_32b50bfc0c5b416a96902d38b267dafb_master.png', 0, 0, '2026-01-08 20:11:36'),
(52, 30, 'https://product.hstatic.net/200000722513/product/rog-maximus-z890-extreme-01_bd5af602b8084f85a179a46d3032ca09_master.jpg', 0, 0, '2026-01-08 20:13:09'),
(53, 31, 'https://product.hstatic.net/200000722513/product/500-elite9-500x500_37e4605e6229461fb4e952dbabead0a4_6ff479d64f094efe8ef83e47208c8b61_master.jpeg', 0, 0, '2026-01-08 20:14:03'),
(54, 32, 'https://product.hstatic.net/200000722513/product/ml-240l-core-argb-gallery-1-zoom_3d40b30854d946c1a1956c0a03509d4a_a919e5b3caa94b699c546e0d16027da8_master.jpg', 0, 0, '2026-01-08 20:15:11'),
(55, 33, 'https://product.hstatic.net/200000722513/product/lg_24gs60f-b_gearvn_d49057760de2459899aa429092fc505f_master.jpg', 0, 0, '2026-01-08 20:16:03'),
(56, 34, 'https://product.hstatic.net/200000722513/product/msi_mag_255f_e20_gearvn_ff826175c7974b9da8db347784de0b24_master.jpg', 0, 0, '2026-01-08 20:16:48'),
(57, 35, 'https://cdn.hstatic.net/products/200000722513/asus_xg27acdms_gearvn_a174ff836c2a471589810a762dfc30e3_master.jpg', 0, 0, '2026-01-08 20:17:33'),
(58, 36, 'https://product.hstatic.net/200000722513/product/msi_mag_273qp_qd-oled_x24_gearvn_e31fa8ea388d4394abe799e55e9e8ad7_master.jpg', 0, 0, '2026-01-08 20:18:17'),
(59, 37, 'https://cdn.hstatic.net/products/200000722513/gearvn-ban-phim-co-khong-day-e-dra-ek368rt-triple-mode-1_dc53097fddf6438984ccaed02799ed29_master.jpg', 0, 0, '2026-01-08 20:19:12'),
(60, 38, 'https://product.hstatic.net/200000722513/product/ek375-pro-alpha-_5__a669f964bff44da697d8f210660af0fa_master.jpg', 0, 0, '2026-01-08 20:19:47'),
(61, 39, 'https://product.hstatic.net/200000722513/product/haze_blue__7__e960d40429ad439d84_ec61afe82f0a4add830e2823b8f914b1_master.png', 0, 0, '2026-01-08 20:20:24'),
(62, 40, 'https://cdn.hstatic.net/products/200000722513/imgi_45_46_6969e9527b2e4f1ba885de6c508e35a5_master.png', 0, 0, '2026-01-08 20:21:06'),
(63, 41, 'https://product.hstatic.net/200000722513/product/gvn_log_g304_3df28cd60a48412b8fb1d2ff762dc6a9_1f12340f2e6b4b8892163de0a06676f2_master.png', 0, 0, '2026-01-08 20:21:43'),
(64, 42, 'https://cdn.hstatic.net/products/200000722513/rog-keris-ii-origin-black-01_4e3cc60a470d4a49a75fc5c781051b08_master.jpg', 0, 0, '2026-01-08 20:22:18'),
(65, 43, 'https://cdn.hstatic.net/products/200000722513/gearvn-chuot-khong-day-logitech-mx-master-4-pale-grey-1_22007516445c45d0bd9803b81dba2e86_master.png', 0, 0, '2026-01-08 20:23:10'),
(66, 44, 'https://product.hstatic.net/200000722513/product/pro-x-superlight-2-dex-black-gal_8e2163b06e86419eb2f99ecb7dccda8f_master.png', 0, 0, '2026-01-08 20:24:02'),
(67, 45, 'https://cdn.hstatic.net/products/200000722513/imgi_24_59408_tai_nghe_gaming_rapoo_vh160_0001_2_e3e354c287fa428ba5307ea5251f398f_master.jpg', 0, 0, '2026-01-08 20:24:44'),
(69, 47, 'https://cdn.hstatic.net/products/200000722513/imgi_87_tai-nghe-khong-day-hyperx-cloud-flight-2-wireless-11_7488aa158eb645de991f51ab745ac43c_master.jpg', 0, 0, '2026-01-08 20:26:11'),
(70, 46, 'https://cdn.hstatic.net/products/200000722513/rog-pelta-01_d946a172a0f84731a4e4951299ed623b_master.jpg', 0, 0, '2026-01-08 20:26:38'),
(71, 48, 'https://product.hstatic.net/200000722513/product/tai_nghe_logitech_g522_lightspeed_wireless_white_-_1_fefb8ad339b04c0a86be3fe46d5b28f7_master.png', 0, 0, '2026-01-08 20:27:21'),
(72, 49, 'https://product.hstatic.net/200000722513/product/tui_chong_sock_gearvn_size_14_-_1_e58879283933436db3233f086d9128c7_6b71ce0a4ce4437387332156f94260f6_master.jpg', 0, 0, '2026-01-08 20:27:56'),
(73, 50, 'https://product.hstatic.net/200000722513/product/dtech_dt-uh0015g_gearvn_1048491799fd46f5a2188db65925bf7f_master.jpg', 0, 0, '2026-01-08 20:28:35'),
(74, 51, 'https://product.hstatic.net/200000722513/product/recci_rpb-p12c_white_55095bb2e27c4ae6aef21ec499ce8d3e_master.png', 0, 0, '2026-01-08 20:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` varchar(500) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `pros` text DEFAULT NULL,
  `cons` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `helpful_count` int(11) DEFAULT 0,
  `reply` text DEFAULT NULL,
  `reply_by` int(11) DEFAULT NULL,
  `reply_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `content`, `pros`, `cons`, `status`, `helpful_count`, `reply`, `reply_by`, `reply_at`, `created_at`, `updated_at`) VALUES
(1, 13, 4, NULL, 5, '', '', NULL, NULL, 'approved', 0, NULL, NULL, NULL, '2026-01-07 09:34:39', '2026-01-07 09:34:39');

-- --------------------------------------------------------

--
-- Table structure for table `review_helpful`
--

CREATE TABLE `review_helpful` (
  `id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'TechShop', 'general', '2025-12-31 16:06:27', '2025-12-31 16:06:27'),
(2, 'site_email', 'contact@techshop.com', 'general', '2025-12-31 16:06:27', '2025-12-31 16:06:27'),
(3, 'site_phone', '1900 xxxx', 'general', '2025-12-31 16:06:27', '2025-12-31 16:06:27'),
(4, 'site_address', '123 Nguy?n V?n Linh, Qu?n 7, TP.HCM', 'general', '2025-12-31 16:06:27', '2025-12-31 16:06:27'),
(5, 'free_shipping_threshold', '500000', 'shipping', '2025-12-31 16:06:27', '2025-12-31 16:06:27'),
(6, 'default_shipping_fee', '30000', 'shipping', '2025-12-31 16:06:27', '2025-12-31 16:06:27'),
(7, 'tax_rate', '10', 'tax', '2025-12-31 16:06:27', '2025-12-31 16:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','employee','admin') DEFAULT 'user',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `birthday` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `google_id`, `email_verified`, `verification_token`, `remember_token`, `token_expiry`, `reset_token`, `reset_expiry`, `last_login`, `created_at`, `updated_at`, `birthday`, `gender`) VALUES
(1, 'Admin TechShop', 'admin@techshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', 'admin', 'active', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-31 16:06:27', '2026-01-07 14:59:05', '0000-00-00', 'male'),
(4, 'Mai Tuấn Đạt Đẹp Trai', 'maituandat2004@gmail.com', '$2y$10$iJdOVJeQRs.8gPFQOagKHuo76orMFP1DP6GZWJEd/4kLQzsadhoZ6', '0123456789', 'user', 'active', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-03 13:08:58', '2026-01-07 10:46:19', '0000-00-00', ''),
(5, 'Hứa Khánh Đăng', 'huakhanhdang2004@gmail.com', '$2y$10$RXv2.u4KWhLztZOxZUYcUu8KfORhQ.XVDbuXmklXkc6nqlDDPL1QK', '0123456789', 'user', 'active', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-07 10:50:57', '2026-01-07 10:50:57', NULL, NULL),
(6, 'Lê Duy', 'leduytctv2019@gmail.com', '$2y$10$LK2o5wLxnQeJSgt6KFqRcOVXGEcPCSNvDynbDqWrMduC751k209pW', '0348137209', 'user', 'active', 'https://lh3.googleusercontent.com/a/ACg8ocKbXXd6vfZr8Mt2cPV2IOuQgn6yiBCr2sBHt7fijNTeLkPpYWu3eg=s96-c', '101370127531312588985', 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-07 16:06:21', '2026-01-08 11:12:23', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `name`, `phone`, `address`, `ward`, `district`, `city`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin TechShop', '0901234567', '123 Nguy?n V?n Linh', 'Ph??ng 1', 'Qu?n 7', 'TP. H? Ch? Minh', 1, '2025-12-31 16:06:27', '2025-12-31 16:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(2, 4, 13, '2026-01-07 09:39:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_session_id` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_product` (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_cart_id` (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `replied_by` (`replied_by`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_assigned_to` (`assigned_to`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `idx_conversation_id` (`conversation_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `assigned_employee` (`assigned_employee`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_brand` (`brand`),
  ADD KEY `idx_price` (`price`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_brand_id` (`brand_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `idx_search` (`name`,`description`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `reply_by` (`reply_by`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `review_helpful`
--
ALTER TABLE `review_helpful`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_review_user` (`review_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_review_id` (`review_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_key` (`setting_key`),
  ADD KEY `idx_group` (`setting_group`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_google_id` (`google_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review_helpful`
--
ALTER TABLE `review_helpful`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`replied_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`assigned_employee`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_ibfk_4` FOREIGN KEY (`reply_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `review_helpful`
--
ALTER TABLE `review_helpful`
  ADD CONSTRAINT `review_helpful_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_helpful_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `review_images_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
