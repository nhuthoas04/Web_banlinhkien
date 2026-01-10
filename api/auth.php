<?php
/**
 * API Xác thực người dùng - Authentication API
 * File này xử lý tất cả các tác vụ liên quan đến đăng nhập, đăng ký, quên mật khẩu
 */

// Khởi động session để lưu thông tin đăng nhập
session_start();

// Thiết lập header trả về JSON
header('Content-Type: application/json');

// Import các file cần thiết
require_once __DIR__ . '/../config/database.php';  // Kết nối database
require_once __DIR__ . '/../models/User.php';      // Model người dùng

// Đọc dữ liệu JSON từ request body
$input = json_decode(file_get_contents('php://input'), true);

// Lấy action từ request (login, register, logout, etc.)
$action = $input['action'] ?? $_GET['action'] ?? '';

// Khởi tạo User model
$userModel = new User();

// ============================================================
// ROUTER - Điều hướng đến các chức năng xác thực
// ============================================================
switch ($action) {
    case 'login':
        // Đăng nhập người dùng
        login($userModel, $input);
        break;
    case 'register':
        // Đăng ký tài khoản mới
        register($userModel, $input);
        break;
    case 'logout':
        // Đăng xuất người dùng
        logout();
        break;
    case 'forgot-password':
        // Quên mật khẩu - gửi email reset
        forgotPassword($userModel, $input);
        break;
    case 'reset-password':
        // Đặt lại mật khẩu mới
        resetPassword($userModel, $input);
        break;
    case 'check-auth':
        // Kiểm tra trạng thái đăng nhập
        checkAuth();
        break;
    default:
        // Action không hợp lệ
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

// ============================================================
// FUNCTION: Đăng nhập người dùng
// ============================================================
function login($userModel, $data) {
    // 1. Kiểm tra dữ liệu đầu vào
    if (empty($data['email']) || empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email và mật khẩu']);
        return;
    }
    
    // 2. Làm sạch và validate email
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $password = $data['password'];
    
    // 3. Tìm user trong database theo email
    $user = $userModel->findByEmail($email);
    
    // 4. Kiểm tra user có tồn tại không
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng']);
        return;
    }
    
    // 5. Kiểm tra trạng thái tài khoản (active/blocked)
    if ($user['status'] !== 'active') {
        echo json_encode(['success' => false, 'message' => 'Tài khoản đã bị khóa']);
        return;
    }
    
    // 6. Xác thực mật khẩu (so sánh với hash trong database)
    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng']);
        return;
    }
    
    // 7. Cập nhật thời gian đăng nhập cuối cùng
    $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_avatar'] = $user['avatar'];
    
    // Merge cart if exists
    if (!empty($_SESSION['cart_session_id'])) {
        require_once __DIR__ . '/../models/Cart.php';
        $cartModel = new Cart();
        $cartModel->mergeCart($user['id'], $_SESSION['cart_session_id']);
        unset($_SESSION['cart_session_id']);
    }
    
    // 10. Xác định trang redirect dựa theo vai trò
    $redirectUrl = '/';  // Mặc định về trang chủ
    switch ($user['role']) {
        case 'admin':
            $redirectUrl = '/admin';      // Admin về trang quản trị
            break;
        case 'employee':
            $redirectUrl = '/employee';   // Nhân viên về trang nhân viên
            break;
        // User về trang chủ (default)
    }
    
    // 11. Trả về kết quả thành công
    echo json_encode([
        'success' => true,
        'message' => 'Đăng nhập thành công',
        'redirect' => $redirectUrl,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'avatar' => $user['avatar']
        ]
    ]);
}

// ============================================================
// FUNCTION: Đăng ký tài khoản mới
// ============================================================
function register($userModel, $data) {
    // 1. Kiểm tra các trường bắt buộc
    $required = ['name', 'email', 'password', 'password_confirm'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            return;
        }
    }
    
    // 2. Làm sạch dữ liệu đầu vào
    $name = htmlspecialchars(trim($data['name']));    // Tên người dùng
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);  // Email
    $password = $data['password'];                     // Mật khẩu
    $passwordConfirm = $data['password_confirm'];      // Xác nhận mật khẩu
    $phone = isset($data['phone']) ? htmlspecialchars(trim($data['phone'])) : null;  // SĐT (không bắt buộc)  // SĐT (không bắt buộc)
    
    // 3. Validate độ dài tên (2-100 ký tự)
    if (strlen($name) < 2 || strlen($name) > 100) {
        echo json_encode(['success' => false, 'message' => 'Tên phải từ 2-100 ký tự']);
        return;
    }
    
    // 4. Validate định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        return;
    }
    
    // 5. Kiểm tra email đã tồn tại chưa
    if ($userModel->findByEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Email đã được sử dụng']);
        return;
    }
    
    // 6. Validate độ dài mật khẩu (tối thiểu 6 ký tự)
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
        return;
    }
    
    // 7. Kiểm tra mật khẩu xác nhận có khớp không
    if ($password !== $passwordConfirm) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
        return;
    }
    
    // 8. Tạo tài khoản mới trong database
    try {
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),  // Hash mật khẩu (bảo mật)
            'phone' => $phone,
            'role' => 'user',            // Vai trò mặc định là user
            'status' => 'active',        // Kích hoạt ngay
            'email_verified' => 1        // Đã xác thực email (có thể thay đổi)
        ]);
        
        // 9. Nếu tạo thành công, tự động đăng nhập luôn
        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = 'user';
            $_SESSION['user_avatar'] = null;
            
            echo json_encode([
                'success' => true,
                'message' => 'Đăng ký thành công!',
                'redirect' => '/'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể tạo tài khoản']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
    }
}

// ============================================================
// FUNCTION: Đăng xuất người dùng
// ============================================================
function logout() {
    // 1. Xóa tất cả biến session
    $_SESSION = [];
    
    // 2. Xóa cookie session nếu đang sử dụng
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    
    // 3. Hủy session hoàn toàn
    session_destroy();
    
    // 4. Trả về kết quả
    echo json_encode([
        'success' => true,
        'message' => 'Đăng xuất thành công',
        'redirect' => '/login'
    ]);
}

// ============================================================
// FUNCTION: Quên mật khẩu - Gửi link reset qua email
// ============================================================
function forgotPassword($userModel, $data) {
    // 1. Kiểm tra email có được nhập không
    if (empty($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email']);
        return;
    }
    
    // 2. Làm sạch email
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $user = $userModel->findByEmail($email);
    
    // 3. Luôn trả về thành công để tránh lộ thông tin email có tồn tại hay không
    // (Bảo mật - ngăn kẻ xấu dò email người dùng)
    echo json_encode([
        'success' => true,
        'message' => 'Nếu email tồn tại, bạn sẽ nhận được link đặt lại mật khẩu.'
    ]);
    
    // 4. Nếu user tồn tại, tạo token reset password
    if ($user) {
        // Tạo token ngẫu nhiên (64 ký tự)
        $resetToken = bin2hex(random_bytes(32));
        
        // Lưu token và thời gian hết hạn (1 tiếng)
        $userModel->update($user['id'], [
            'reset_token' => $resetToken,
            'reset_expiry' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ]);
        
        // TODO: Gửi email chứa link reset (cần cài đặt thêm)
        // Link sẽ có dạng: /reset-password?token=xxx
    }
}

// ============================================================
// FUNCTION: Đặt lại mật khẩu mới (sau khi click link trong email)
// ============================================================
function resetPassword($userModel, $data) {
    // 1. Kiểm tra dữ liệu đầu vào đầy đủ
    if (empty($data['token']) || empty($data['password']) || empty($data['password_confirm'])) {
        echo json_encode(['success' => false, 'message' => 'Thông tin không hợp lệ']);
        return;
    }
    
    $password = $data['password'];
    
    // 2. Validate độ dài mật khẩu
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
        return;
    }
    
    // 3. Kiểm tra mật khẩu xác nhận
    if ($password !== $data['password_confirm']) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
        return;
    }
    
    // 4. Kiểm tra token có hợp lệ và chưa hết hạn không
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expiry > NOW()");
    $stmt->execute([':token' => $data['token']]);
    $user = $stmt->fetch();
    
    // 5. Token không hợp lệ hoặc đã quá 1 giờ
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Link không hợp lệ hoặc đã hết hạn']);
        return;
    }
    
    // 6. Cập nhật mật khẩu mới và xóa token
    $userModel->update($user['id'], [
        'password' => password_hash($password, PASSWORD_DEFAULT),  // Hash mật khẩu mới
        'reset_token' => null,      // Xóa token (chỉ dùng 1 lần)
        'reset_expiry' => null      // Xóa thời gian hết hạn
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đổi mật khẩu thành công',
        'redirect' => '/login'
    ]);
}

// ============================================================
// FUNCTION: Kiểm tra trạng thái đăng nhập hiện tại
// ============================================================
function checkAuth() {
    // Kiểm tra có user_id trong session không
    if (isset($_SESSION['user_id'])) {
        // Đã đăng nhập - Trả về thông tin user
        echo json_encode([
            'success' => true,
            'authenticated' => true,  // Đã xác thực
            'user' => [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'user',
                'avatar' => $_SESSION['user_avatar'] ?? null
            ]
        ]);
    } else {
        // Chưa đăng nhập
        echo json_encode(['success' => true, 'authenticated' => false]);
    }
}
?>
