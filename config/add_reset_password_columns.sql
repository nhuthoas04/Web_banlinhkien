-- Thêm cột reset_token và reset_expiry vào bảng users (nếu chưa có)
-- Chạy file này trong phpMyAdmin hoặc MySQL client

-- Thêm cột reset_token nếu chưa có
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'computer_shop' 
     AND TABLE_NAME = 'users' 
     AND COLUMN_NAME = 'reset_token') > 0,
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN reset_token VARCHAR(100) NULL'
));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Thêm cột reset_expiry nếu chưa có  
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'computer_shop' 
     AND TABLE_NAME = 'users' 
     AND COLUMN_NAME = 'reset_expiry') > 0,
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN reset_expiry DATETIME NULL'
));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Hoặc chạy trực tiếp nếu biết chắc chưa có cột:
-- ALTER TABLE users ADD COLUMN reset_token VARCHAR(100) NULL;
-- ALTER TABLE users ADD COLUMN reset_expiry DATETIME NULL;
