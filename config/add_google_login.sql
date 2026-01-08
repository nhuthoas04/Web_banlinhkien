-- Thêm cột google_id vào bảng users để hỗ trợ đăng nhập bằng Google
-- Chạy file SQL này trong phpMyAdmin hoặc MySQL CLI

ALTER TABLE `users` ADD COLUMN `google_id` VARCHAR(255) NULL DEFAULT NULL AFTER `avatar`;

-- Thêm index cho cột google_id để tìm kiếm nhanh hơn
ALTER TABLE `users` ADD INDEX `idx_google_id` (`google_id`);
