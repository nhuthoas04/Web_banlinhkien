<?php
/**
 * Application Configuration
 * Website Bán Thiết Bị Máy Tính
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load environment variables from .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// Helper function to get env value
function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// Set default encoding for PHP
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
header('Content-Type: text/html; charset=UTF-8');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base URL Configuration
define('BASE_URL', 'http://localhost/doan_web_php/');
define('ASSETS_URL', BASE_URL . 'assets');

// Site Information
define('SITE_NAME', 'TechShop - Thiết Bị Máy Tính');
define('SITE_DESCRIPTION', 'Cửa hàng thiết bị và phụ kiện máy tính hàng đầu');
define('SITE_EMAIL', 'leduytctv2019@gmail.com');
define('SITE_PHONE', '0348137209');
define('SITE_ADDRESS', '126 Nguyễn Thiện Thành, Phường 5, Trà Vinh, Việt Nam');

// Upload Configuration
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Pagination
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// User Roles
define('ROLE_USER', 'user');
define('ROLE_EMPLOYEE', 'employee');
define('ROLE_ADMIN', 'admin');

// Role Labels (for display)
define('ROLES', [
    'user' => 'Khách hàng',
    'employee' => 'Nhân viên',
    'admin' => 'Quản trị viên'
]);

// Order Status
define('ORDER_PENDING', 'pending');
define('ORDER_CONFIRMED', 'confirmed');
define('ORDER_SHIPPING', 'shipping');
define('ORDER_DELIVERED', 'delivered');
define('ORDER_CANCELLED', 'cancelled');

// Order Status Labels
define('ORDER_STATUSES', [
    'pending' => 'Chờ xử lý',
    'confirmed' => 'Đã xác nhận',
    'processing' => 'Đang xử lý',
    'shipping' => 'Đang giao',
    'delivered' => 'Đã giao',
    'cancelled' => 'Đã hủy',
    'returned' => 'Đã trả hàng'
]);

// Payment Methods
define('PAYMENT_COD', 'cod');
define('PAYMENT_BANKING', 'banking');
define('PAYMENT_MOMO', 'momo');
define('PAYMENT_VNPAY', 'vnpay');

// Currency
define('CURRENCY', 'VNĐ');
define('CURRENCY_SYMBOL', '₫');

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Security
define('HASH_COST', 12);

// Google OAuth Configuration
// Để sử dụng đăng nhập Google, bạn cần:
// 1. Truy cập https://console.cloud.google.com/
// 2. Tạo project mới hoặc chọn project có sẵn
// 3. Bật Google+ API và Google Identity API
// 4. Tạo OAuth 2.0 Client ID (Web application)
// 5. Thêm Authorized redirect URI: http://localhost/doan_web_php/google-callback
// 6. Copy Client ID và Client Secret vào file .env
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID', ''));
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET', ''));
define('GOOGLE_REDIRECT_URI', BASE_URL . 'google-callback');

// ============================================
// Email Configuration (SMTP)
// ============================================
// Để gửi email thực sự, bạn cần cấu hình SMTP trong file .env
// Có thể sử dụng Gmail SMTP hoặc các dịch vụ khác như Mailgun, SendGrid

// Gmail SMTP Configuration
// 1. Truy cập: https://myaccount.google.com/apppasswords (cần bật 2FA trước)
// 2. Tạo App Password cho "Mail"
// 3. Copy password 16 ký tự (VD: abcd efgh ijkl mnop) và nhập KHÔNG có dấu cách vào file .env
define('SMTP_ENABLED', env('SMTP_ENABLED', 'false') === 'true');
define('SMTP_HOST', env('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', (int) env('SMTP_PORT', 587));
define('SMTP_SECURE', env('SMTP_SECURE', 'tls'));
define('SMTP_USERNAME', env('SMTP_USERNAME', ''));
define('SMTP_PASSWORD', env('SMTP_PASSWORD', ''));
define('SMTP_FROM_EMAIL', env('SMTP_FROM_EMAIL', ''));
define('SMTP_FROM_NAME', env('SMTP_FROM_NAME', 'TechShop'));

// Include database configuration
require_once __DIR__ . '/database.php';

// Helper Functions
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['user']);
}

function isAdmin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === ROLE_ADMIN) ||
           (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === ROLE_ADMIN);
}

function isEmployee() {
    $role = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';
    return $role === ROLE_EMPLOYEE; // Chỉ employee, không bao gồm admin
}

/**
 * Kiểm tra xem user có phải là Employee hoặc Admin không
 * Dùng cho các tính năng mà cả employee và admin đều có thể truy cập
 */
function isEmployeeOrAdmin() {
    $role = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';
    return $role === ROLE_EMPLOYEE || $role === ROLE_ADMIN;
}

function formatPrice($price) {
    $price = $price ?? 0;
    return number_format((float)$price, 0, ',', '.') . ' ' . CURRENCY;
}

function formatDate($date) {
    if ($date instanceof MongoDB\BSON\UTCDateTime) {
        return $date->toDateTime()->format('d/m/Y H:i');
    }
    return date('d/m/Y H:i', strtotime($date));
}

function timeAgo($datetime) {
    if (empty($datetime)) return '';
    
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'Vừa xong';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' phút trước';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' giờ trước';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' ngày trước';
    } else {
        return date('d/m/Y', $time);
    }
}

function generateOrderCode() {
    return 'ORD' . date('YmdHis') . rand(100, 999);
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function uploadImage($file, $folder = 'products') {
    $uploadDir = UPLOAD_PATH . $folder . '/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => 'File không hợp lệ'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File quá lớn'];
    }
    
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $folder . '/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Lỗi upload file'];
}

function getStatusBadge($status) {
    $badges = [
        ORDER_PENDING => '<span class="badge bg-warning">Chờ xác nhận</span>',
        ORDER_CONFIRMED => '<span class="badge bg-info">Đã xác nhận</span>',
        ORDER_SHIPPING => '<span class="badge bg-primary">Đang giao</span>',
        ORDER_DELIVERED => '<span class="badge bg-success">Đã giao</span>',
        ORDER_CANCELLED => '<span class="badge bg-danger">Đã hủy</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Không xác định</span>';
}

function flash($key, $message = null) {
    if ($message) {
        $_SESSION['flash'][$key] = $message;
    } else {
        $msg = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
}

function getOrderStatusColor($status) {
    $colors = [
        ORDER_PENDING => 'warning',
        ORDER_CONFIRMED => 'info',
        ORDER_SHIPPING => 'primary',
        ORDER_DELIVERED => 'success',
        ORDER_CANCELLED => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}

function getOrderStatusText($status) {
    $texts = [
        ORDER_PENDING => 'Cho xac nhan',
        ORDER_CONFIRMED => 'Da xac nhan',
        ORDER_SHIPPING => 'Dang giao',
        ORDER_DELIVERED => 'Da giao',
        ORDER_CANCELLED => 'Da huy'
    ];
    return $texts[$status] ?? 'Khong xac dinh';
}

// CSRF Token Functions
function generateToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function getToken() {
    return $_SESSION['csrf_token'] ?? generateToken();
}

function verifyToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function tokenField() {
    $token = getToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// Debug mode
define('DEBUG_MODE', true);
?>
