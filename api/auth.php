<?php
/**
 * Authentication API - MySQL Version
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$userModel = new User();

switch ($action) {
    case 'login':
        login($userModel, $input);
        break;
    case 'register':
        register($userModel, $input);
        break;
    case 'logout':
        logout();
        break;
    case 'forgot-password':
        forgotPassword($userModel, $input);
        break;
    case 'reset-password':
        resetPassword($userModel, $input);
        break;
    case 'check-auth':
        checkAuth();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function login($userModel, $data) {
    if (empty($data['email']) || empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email và mật khẩu']);
        return;
    }
    
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $password = $data['password'];
    
    $user = $userModel->findByEmail($email);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng']);
        return;
    }
    
    if ($user['status'] !== 'active') {
        echo json_encode(['success' => false, 'message' => 'Tài khoản đã bị khóa']);
        return;
    }
    
    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng']);
        return;
    }
    
    // Update last login
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
    
    $redirectUrl = '/';
    switch ($user['role']) {
        case 'admin':
            $redirectUrl = '/admin';
            break;
        case 'employee':
            $redirectUrl = '/employee';
            break;
    }
    
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

function register($userModel, $data) {
    $required = ['name', 'email', 'password', 'password_confirm'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            return;
        }
    }
    
    $name = htmlspecialchars(trim($data['name']));
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $password = $data['password'];
    $passwordConfirm = $data['password_confirm'];
    $phone = isset($data['phone']) ? htmlspecialchars(trim($data['phone'])) : null;
    
    if (strlen($name) < 2 || strlen($name) > 100) {
        echo json_encode(['success' => false, 'message' => 'Tên phải từ 2-100 ký tự']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        return;
    }
    
    if ($userModel->findByEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Email đã được sử dụng']);
        return;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
        return;
    }
    
    if ($password !== $passwordConfirm) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
        return;
    }
    
    try {
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'phone' => $phone,
            'role' => 'user',
            'status' => 'active',
            'email_verified' => 1
        ]);
        
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

function logout() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    
    echo json_encode([
        'success' => true,
        'message' => 'Đăng xuất thành công',
        'redirect' => '/login'
    ]);
}

function forgotPassword($userModel, $data) {
    if (empty($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email']);
        return;
    }
    
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $user = $userModel->findByEmail($email);
    
    // Always show success to prevent email enumeration
    echo json_encode([
        'success' => true,
        'message' => 'Nếu email tồn tại, bạn sẽ nhận được link đặt lại mật khẩu.'
    ]);
    
    if ($user) {
        $resetToken = bin2hex(random_bytes(32));
        $userModel->update($user['id'], [
            'reset_token' => $resetToken,
            'reset_expiry' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ]);
        // TODO: Send email with reset link
    }
}

function resetPassword($userModel, $data) {
    if (empty($data['token']) || empty($data['password']) || empty($data['password_confirm'])) {
        echo json_encode(['success' => false, 'message' => 'Thông tin không hợp lệ']);
        return;
    }
    
    $password = $data['password'];
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
        return;
    }
    
    if ($password !== $data['password_confirm']) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
        return;
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expiry > NOW()");
    $stmt->execute([':token' => $data['token']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Link không hợp lệ hoặc đã hết hạn']);
        return;
    }
    
    $userModel->update($user['id'], [
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'reset_token' => null,
        'reset_expiry' => null
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đổi mật khẩu thành công',
        'redirect' => '/login'
    ]);
}

function checkAuth() {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => true,
            'authenticated' => true,
            'user' => [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'user',
                'avatar' => $_SESSION['user_avatar'] ?? null
            ]
        ]);
    } else {
        echo json_encode(['success' => true, 'authenticated' => false]);
    }
}
?>
