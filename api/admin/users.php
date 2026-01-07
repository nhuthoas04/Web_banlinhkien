<?php
/**
 * Admin Users API - MySQL Version
 */

// Suppress errors in output for clean JSON
error_reporting(0);
ini_set('display_errors', 0);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';

// Check admin access - kiểm tra nhiều cách lưu session
$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';

if (!$userId || $userRole !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$userModel = new User();

switch ($action) {
    case 'list':
        listUsers($userModel);
        break;
    case 'detail':
        getUser($userModel, $input);
        break;
    case 'create':
        createUser($userModel, $input);
        break;
    case 'update':
        updateUser($userModel, $input);
        break;
    case 'delete':
        deleteUser($userModel, $input);
        break;
    case 'update-status':
        updateStatus($userModel, $input);
        break;
    case 'reset-password':
        resetPassword($userModel, $input);
        break;
    case 'stats':
        getStats($userModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listUsers($userModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [];
    
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    if (!empty($_GET['role'])) {
        $filters['role'] = $_GET['role'];
    }
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    if (!empty($_GET['sort'])) {
        $filters['sort'] = $_GET['sort'];
    }
    
    $result = $userModel->getAll($page, $limit, $filters);
    
    // Remove passwords
    foreach ($result['data'] as &$user) {
        unset($user['password']);
    }
    
    echo json_encode([
        'success' => true,
        'data' => $result['data'],
        'pagination' => [
            'total' => $result['total'],
            'page' => $result['page'],
            'limit' => $result['limit'],
            'total_pages' => $result['total_pages']
        ]
    ]);
}

function getUser($userModel, $data) {
    $id = $data['id'] ?? $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        return;
    }
    
    $user = $userModel->findById((int)$id);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
        return;
    }
    
    unset($user['password']);
    
    // Get user stats
    $user['stats'] = $userModel->getUserStats($user['id']);
    
    // Get addresses
    $user['addresses'] = $userModel->getAddresses($user['id']);
    
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
}

function createUser($userModel, $data) {
    // Validate required fields
    $required = ['email', 'password', 'fullname'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Trường $field là bắt buộc"]);
            return;
        }
    }
    
    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        return;
    }
    
    // Check if email exists
    if ($userModel->findByEmail($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email đã tồn tại']);
        return;
    }
    
    // Validate password
    if (strlen($data['password']) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
        return;
    }
    
    $userData = [
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        'fullname' => $data['fullname'],
        'phone' => $data['phone'] ?? '',
        'role' => $data['role'] ?? 'user',
        'status' => $data['status'] ?? 'active'
    ];
    
    $userId = $userModel->create($userData);
    
    if ($userId) {
        echo json_encode([
            'success' => true,
            'message' => 'Tạo tài khoản thành công',
            'user_id' => $userId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể tạo tài khoản']);
    }
}

function updateUser($userModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        return;
    }
    
    $user = $userModel->findById((int)$data['id']);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
        return;
    }
    
    // Prevent editing superadmin by non-superadmin
    if ($user['email'] === 'admin@techshop.com' && $_SESSION['user_id'] !== $user['id']) {
        echo json_encode(['success' => false, 'message' => 'Không thể chỉnh sửa tài khoản này']);
        return;
    }
    
    $allowedFields = ['fullname', 'phone', 'role', 'status', 'birthday', 'gender'];
    $updateData = [];
    
    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }
    
    // Update email if provided and different
    if (!empty($data['email']) && $data['email'] !== $user['email']) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
            return;
        }
        
        if ($userModel->findByEmail($data['email'])) {
            echo json_encode(['success' => false, 'message' => 'Email đã tồn tại']);
            return;
        }
        
        $updateData['email'] = $data['email'];
    }
    
    // Update password if provided
    if (!empty($data['password'])) {
        if (strlen($data['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
            return;
        }
        $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    
    if (!empty($updateData)) {
        $userModel->update($user['id'], $updateData);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật thành công'
    ]);
}

function deleteUser($userModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        return;
    }
    
    $user = $userModel->findById((int)$data['id']);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
        return;
    }
    
    // Cannot delete self
    if ($user['id'] === $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa tài khoản của chính mình']);
        return;
    }
    
    // Cannot delete superadmin
    if ($user['email'] === 'admin@techshop.com') {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa tài khoản admin']);
        return;
    }
    
    $userModel->delete($user['id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Xóa tài khoản thành công'
    ]);
}

function updateStatus($userModel, $data) {
    if (empty($data['id']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'User ID and status required']);
        return;
    }
    
    $user = $userModel->findById((int)$data['id']);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
        return;
    }
    
    // Cannot change status of self or superadmin
    if ($user['id'] === $_SESSION['user_id'] || $user['email'] === 'admin@techshop.com') {
        echo json_encode(['success' => false, 'message' => 'Không thể thay đổi trạng thái tài khoản này']);
        return;
    }
    
    $validStatuses = ['active', 'inactive', 'banned'];
    if (!in_array($data['status'], $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
        return;
    }
    
    $userModel->update($user['id'], ['status' => $data['status']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật trạng thái thành công'
    ]);
}

function resetPassword($userModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        return;
    }
    
    $user = $userModel->findById((int)$data['id']);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
        return;
    }
    
    // Generate random password
    $newPassword = bin2hex(random_bytes(4)); // 8 characters
    
    $userModel->update($user['id'], [
        'password' => password_hash($newPassword, PASSWORD_DEFAULT)
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đặt lại mật khẩu thành công',
        'new_password' => $newPassword
    ]);
}

function getStats($userModel) {
    $db = getDB();
    
    // Total users by role
    $stmt = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $byRole = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Total users by status
    $stmt = $db->query("SELECT status, COUNT(*) as count FROM users GROUP BY status");
    $byStatus = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // New users this month
    $stmt = $db->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')");
    $newThisMonth = $stmt->fetchColumn();
    
    // User registrations by month (last 6 months)
    $stmt = $db->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
        FROM users
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC
    ");
    $monthlyRegistrations = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'by_role' => $byRole,
            'by_status' => $byStatus,
            'new_this_month' => $newThisMonth,
            'monthly_registrations' => $monthlyRegistrations
        ]
    ]);
}
?>
