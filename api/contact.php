<?php
/**
 * Contact API - MySQL Version
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'submit':
        submitContact($input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function submitContact($data) {
    // Validate required fields
    $required = ['name', 'email', 'subject', 'message'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            return;
        }
    }
    
    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        return;
    }
    
    // Validate phone if provided
    if (!empty($data['phone'])) {
        if (!preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            echo json_encode(['success' => false, 'message' => 'Số điện thoại không hợp lệ']);
            return;
        }
    }
    
    // Rate limiting - simple check by IP
    $db = getDB();
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $stmt = $db->prepare("SELECT COUNT(*) FROM contacts WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt->execute([$ip]);
    $count = $stmt->fetchColumn();
    
    if ($count >= 5) {
        echo json_encode(['success' => false, 'message' => 'Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau.']);
        return;
    }
    
    // Insert contact
    $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'] ?? null,
        $data['subject'],
        $data['message'],
        $ip
    ]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể gửi tin nhắn. Vui lòng thử lại.']);
    }
}
?>
