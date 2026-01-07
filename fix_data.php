<?php
/**
 * Fix Vietnamese Data in Database
 * Truy cập: http://localhost/doan_web_php/fix_data.php
 */

require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

// Đặt charset
$db->exec("SET NAMES utf8mb4");

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Fix Data</title></head><body>";
echo "<h1>Fix Vietnamese Data</h1>";
echo "<pre>";

try {
    // Fix Categories
    $categories = [
        ['slug' => 'laptop', 'name' => 'Laptop', 'description' => 'Laptop gaming, văn phòng, đồ họa các thương hiệu'],
        ['slug' => 'pc-gaming', 'name' => 'PC Gaming', 'description' => 'Máy tính để bàn gaming cấu hình cao'],
        ['slug' => 'linh-kien', 'name' => 'Linh kiện máy tính', 'description' => 'CPU, RAM, VGA, SSD và các linh kiện khác'],
        ['slug' => 'man-hinh', 'name' => 'Màn hình', 'description' => 'Màn hình gaming, đồ họa các kích thước'],
        ['slug' => 'ban-phim', 'name' => 'Bàn phím', 'description' => 'Bàn phím cơ, bàn phím gaming'],
        ['slug' => 'chuot', 'name' => 'Chuột', 'description' => 'Chuột gaming, chuột văn phòng'],
        ['slug' => 'tai-nghe', 'name' => 'Tai nghe', 'description' => 'Tai nghe gaming, tai nghe bluetooth'],
        ['slug' => 'phu-kien', 'name' => 'Phụ kiện', 'description' => 'Phụ kiện máy tính các loại'],
    ];
    
    $stmt = $db->prepare("UPDATE categories SET name = :name, description = :description WHERE slug = :slug");
    
    foreach ($categories as $cat) {
        $stmt->execute([
            ':slug' => $cat['slug'],
            ':name' => $cat['name'],
            ':description' => $cat['description']
        ]);
        $affected = $stmt->rowCount();
        echo "Updated category '{$cat['slug']}': $affected row(s)\n";
    }
    
    echo "\n✅ Categories updated!\n";
    
    // Kiểm tra kết quả
    echo "\n--- Current Categories ---\n";
    $result = $db->query("SELECT id, name, slug, description FROM categories");
    while ($row = $result->fetch()) {
        echo "ID: {$row['id']} | Name: {$row['name']} | Slug: {$row['slug']}\n";
        echo "   Description: {$row['description']}\n\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}

echo "</pre>";
echo "<p><strong>Xong!</strong> <a href='".BASE_URL."admin?page=categories'>Quay lại trang Categories</a></p>";
echo "<p style='color:red'>⚠️ Nhớ xóa file này sau khi chạy xong!</p>";
echo "</body></html>";
?>
