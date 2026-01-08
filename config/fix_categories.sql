-- Fix Vietnamese Data for TechShop
-- Chạy file này trong phpMyAdmin

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Xóa tất cả categories cũ và thêm mới
DELETE FROM categories;

-- Thêm lại categories với dữ liệu tiếng Việt đúng
INSERT INTO categories (id, name, slug, description, status, sort_order) VALUES
(1, 'Laptop', 'laptop', 'Laptop gaming, văn phòng, đồ họa các thương hiệu', 'active', 1),
(2, 'PC Gaming', 'pc-gaming', 'Máy tính để bàn gaming cấu hình cao', 'active', 2),
(3, 'Linh kiện máy tính', 'linh-kien', 'CPU, RAM, VGA, SSD và các linh kiện khác', 'active', 3),
(4, 'Màn hình', 'man-hinh', 'Màn hình gaming, đồ họa các kích thước', 'active', 4),
(5, 'Bàn phím', 'ban-phim', 'Bàn phím cơ, bàn phím gaming', 'active', 5),
(6, 'Chuột', 'chuot', 'Chuột gaming, chuột văn phòng', 'active', 6),
(7, 'Tai nghe', 'tai-nghe', 'Tai nghe gaming, tai nghe bluetooth', 'active', 7),
(8, 'Phụ kiện', 'phu-kien', 'Phụ kiện máy tính các loại', 'active', 8);

-- Kiểm tra kết quả
SELECT * FROM categories;
