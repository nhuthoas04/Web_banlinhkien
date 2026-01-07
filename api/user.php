<?php
/**
 * User API - MySQL Version
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$input = json_decode(file_get_contents('php://input'), true);
if ($input === null) {
    $input = $_POST;
}
$action = $input['action'] ?? $_GET['action'] ?? $_POST['action'] ?? '';

$userModel = new User();

switch ($action) {
    case 'profile':
        getProfile($userModel);
        break;
    case 'update-profile':
        updateProfile($userModel, $input);
        break;
    case 'change-password':
        changePassword($userModel, $input);
        break;
    case 'upload-avatar':
        uploadAvatar($userModel);
        break;
    case 'addresses':
        getAddresses($userModel);
        break;
    case 'add-address':
        addAddress($userModel, $input);
        break;
    case 'update-address':
        updateAddress($userModel, $input);
        break;
    case 'delete-address':
        deleteAddress($userModel, $input);
        break;
    case 'set-default-address':
        setDefaultAddress($userModel, $input);
        break;
    case 'stats':
        getUserStats($userModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        exit;
    }
}

function getProfile($userModel) {
    checkAuth();
    
    $user = $userModel->findById($_SESSION['user_id']);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
        return;
    }
    
    unset($user['password']);
    
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
}

function updateProfile($userModel, $data) {
    checkAuth();
    
    $updateData = [];
    
    // Map form field names to database column names
    if (isset($data['fullname']) && !empty($data['fullname'])) {
        $updateData['name'] = $data['fullname'];
    }
    if (isset($data['phone'])) {
        $updateData['phone'] = $data['phone'];
    }
    if (isset($data['birthday'])) {
        $updateData['birthday'] = $data['birthday'];
    }
    if (isset($data['gender'])) {
        $updateData['gender'] = $data['gender'];
    }
    
    if (empty($updateData)) {
        echo json_encode(['success' => false, 'message' => 'Không có dữ liệu cập nhật']);
        return;
    }
    
    $result = $userModel->update($_SESSION['user_id'], $updateData);
    
    if ($result) {
        // Update session
        if (isset($updateData['name'])) {
            $_SESSION['fullname'] = $updateData['name'];
        }
        
        echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông tin']);
    }
}

function changePassword($userModel, $data) {
    checkAuth();
    
    if (empty($data['current_password']) || empty($data['new_password'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
        return;
    }
    
    if (strlen($data['new_password']) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự']);
        return;
    }
    
    $user = $userModel->findById($_SESSION['user_id']);
    
    if (!password_verify($data['current_password'], $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
        return;
    }
    
    $result = $userModel->update($_SESSION['user_id'], [
        'password' => password_hash($data['new_password'], PASSWORD_DEFAULT)
    ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể đổi mật khẩu']);
    }
}

function uploadAvatar($userModel) {
    checkAuth();
    
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn file ảnh']);
        return;
    }
    
    $file = $_FILES['avatar'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)']);
        return;
    }
    
    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'File ảnh không được lớn hơn 2MB']);
        return;
    }
    
    // Create upload directory if not exists
    $uploadDir = __DIR__ . '/../public/uploads/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $_SESSION['user_id'] . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $avatarUrl = '/uploads/avatars/' . $filename;
        
        // Delete old avatar
        $user = $userModel->findById($_SESSION['user_id']);
        if (!empty($user['avatar']) && file_exists(__DIR__ . '/../public' . $user['avatar'])) {
            unlink(__DIR__ . '/../public' . $user['avatar']);
        }
        
        // Update user
        $userModel->update($_SESSION['user_id'], ['avatar' => $avatarUrl]);
        $_SESSION['user_avatar'] = $avatarUrl;
        
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật ảnh đại diện thành công',
            'avatar' => $avatarUrl
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể upload ảnh']);
    }
}

function getAddresses($userModel) {
    checkAuth();
    
    $addresses = $userModel->getAddresses($_SESSION['user_id']);
    
    echo json_encode([
        'success' => true,
        'data' => $addresses
    ]);
}

function addAddress($userModel, $data) {
    checkAuth();
    
    $required = ['fullname', 'phone', 'address'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            return;
        }
    }
    
    $addressData = [
        'user_id' => $_SESSION['user_id'],
        'fullname' => $data['fullname'],
        'phone' => $data['phone'],
        'address' => $data['address'],
        'province' => $data['province'] ?? '',
        'district' => $data['district'] ?? '',
        'ward' => $data['ward'] ?? '',
        'is_default' => !empty($data['is_default']) ? 1 : 0
    ];
    
    $addressId = $userModel->addAddress($addressData);
    
    if ($addressId) {
        echo json_encode([
            'success' => true,
            'message' => 'Thêm địa chỉ thành công',
            'address_id' => $addressId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể thêm địa chỉ']);
    }
}

function updateAddress($userModel, $data) {
    checkAuth();
    
    if (empty($data['address_id'])) {
        echo json_encode(['success' => false, 'message' => 'Address ID required']);
        return;
    }
    
    // Verify ownership
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([(int)$data['address_id'], $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Địa chỉ không tồn tại']);
        return;
    }
    
    $allowedFields = ['fullname', 'phone', 'address', 'province', 'district', 'ward'];
    $updateData = [];
    
    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }
    
    if (!empty($updateData)) {
        $userModel->updateAddress((int)$data['address_id'], $updateData);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật địa chỉ thành công'
    ]);
}

function deleteAddress($userModel, $data) {
    checkAuth();
    
    if (empty($data['address_id'])) {
        echo json_encode(['success' => false, 'message' => 'Address ID required']);
        return;
    }
    
    // Verify ownership
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([(int)$data['address_id'], $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Địa chỉ không tồn tại']);
        return;
    }
    
    $userModel->deleteAddress((int)$data['address_id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Xóa địa chỉ thành công'
    ]);
}

function setDefaultAddress($userModel, $data) {
    checkAuth();
    
    if (empty($data['address_id'])) {
        echo json_encode(['success' => false, 'message' => 'Address ID required']);
        return;
    }
    
    // Verify ownership
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([(int)$data['address_id'], $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Địa chỉ không tồn tại']);
        return;
    }
    
    $userModel->setDefaultAddress($_SESSION['user_id'], (int)$data['address_id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã đặt địa chỉ mặc định'
    ]);
}

function getUserStats($userModel) {
    checkAuth();
    
    $stats = $userModel->getUserStats($_SESSION['user_id']);
    
    echo json_encode([
        'success' => true,
        'data' => $stats
    ]);
}
?>
