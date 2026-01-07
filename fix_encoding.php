<?php
/**
 * Fix Encoding Script
 * Chạy file này một lần để sửa lỗi encoding tiếng Việt trong database
 * Truy cập: http://localhost/doan_web_php/fix_encoding.php
 */

require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

// Đặt charset cho connection
$db->exec("SET NAMES utf8mb4");
$db->exec("SET CHARACTER SET utf8mb4");

echo "<h1>Fix Encoding Database</h1>";
echo "<pre>";

// Danh sách các bảng và cột cần fix
$tables = [
    'categories' => ['name', 'description'],
    'products' => ['name', 'description', 'short_description'],
    'users' => ['name'],
    'reviews' => ['comment'],
    'contacts' => ['name', 'message', 'subject']
];

// Kiểm tra và convert các bảng
foreach ($tables as $table => $columns) {
    echo "\n--- Checking table: $table ---\n";
    
    // Kiểm tra bảng có tồn tại không
    $checkTable = $db->query("SHOW TABLES LIKE '$table'");
    if ($checkTable->rowCount() == 0) {
        echo "Table $table không tồn tại, bỏ qua.\n";
        continue;
    }
    
    // Đổi charset của bảng
    $db->exec("ALTER TABLE `$table` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Đã convert table $table sang utf8mb4\n";
}

echo "\n\n=== HOÀN THÀNH ===\n";
echo "Vui lòng:\n";
echo "1. Xóa file này sau khi chạy xong\n";
echo "2. Nhập lại dữ liệu tiếng Việt nếu vẫn bị lỗi\n";
echo "</pre>";

// Hiển thị hướng dẫn nhập lại dữ liệu
echo "<h2>SQL để cập nhật lại dữ liệu categories:</h2>";
echo "<pre style='background:#f5f5f5;padding:15px;'>";
echo htmlspecialchars("
-- Cập nhật dữ liệu categories với tiếng Việt đúng
UPDATE categories SET 
    name = 'Laptop',
    description = 'Laptop gaming, văn phòng, đồ họa các thương hiệu'
WHERE slug = 'laptop';

UPDATE categories SET 
    name = 'PC Gaming',
    description = 'Máy tính để bàn gaming cấu hình cao'
WHERE slug = 'pc-gaming';

UPDATE categories SET 
    name = 'Linh kiện máy tính',
    description = 'CPU, RAM, VGA, SSD và các linh kiện khác'
WHERE slug = 'linh-kien';

UPDATE categories SET 
    name = 'Màn hình',
    description = 'Màn hình gaming, đồ họa các kích thước'
WHERE slug = 'man-hinh';

UPDATE categories SET 
    name = 'Bàn phím',
    description = 'Bàn phím cơ, bàn phím gaming'
WHERE slug = 'ban-phim';

UPDATE categories SET 
    name = 'Chuột',
    description = 'Chuột gaming, chuột văn phòng'
WHERE slug = 'chuot';

UPDATE categories SET 
    name = 'Tai nghe',
    description = 'Tai nghe gaming, tai nghe bluetooth'
WHERE slug = 'tai-nghe';

UPDATE categories SET 
    name = 'Phụ kiện',
    description = 'Phụ kiện máy tính các loại'
WHERE slug = 'phu-kien';
");
echo "</pre>";
?>
