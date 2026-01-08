<?php
/**
 * Contact API - MySQL Version
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
session_start();

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}
$action = $input['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'submit':
        submitContact($input);
        break;
    case 'get_messages':
        getChatMessages();
        break;
    case 'send_message':
        sendChatMessage($input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getChatMessages() {
    if (empty($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        return;
    }
    
    $db = getDB();
    $userId = $_SESSION['user_id'];
    
    // Get or create conversation
    $stmt = $db->prepare("SELECT id FROM conversations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$userId]);
    $conversation = $stmt->fetch();
    
    if (!$conversation) {
        echo json_encode([
            'success' => true,
            'conversation_id' => null,
            'messages' => []
        ]);
        return;
    }
    
    // Get messages (table name is 'messages', column is 'content')
    $stmt = $db->prepare("
        SELECT id, content as message, sender_type, created_at 
        FROM messages 
        WHERE conversation_id = ? 
        ORDER BY created_at ASC 
        LIMIT 50
    ");
    $stmt->execute([$conversation['id']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mark messages as read
    $stmt = $db->prepare("UPDATE messages SET is_read = 1, read_at = NOW() WHERE conversation_id = ? AND sender_type != 'user'");
    $stmt->execute([$conversation['id']]);
    
    echo json_encode([
        'success' => true,
        'conversation_id' => $conversation['id'],
        'messages' => $messages
    ]);
}

function sendChatMessage($data) {
    if (empty($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        return;
    }
    
    $message = trim($data['message'] ?? '');
    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Tin nhắn trống']);
        return;
    }
    
    $db = getDB();
    $userId = $_SESSION['user_id'];
    $conversationId = $data['conversation_id'] ?? null;
    
    // Get or create conversation
    if (!$conversationId) {
        $stmt = $db->prepare("SELECT id FROM conversations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $conversation = $stmt->fetch();
        
        if ($conversation) {
            $conversationId = $conversation['id'];
        } else {
            // Create new conversation
            $stmt = $db->prepare("INSERT INTO conversations (user_id, status, created_at) VALUES (?, 'open', NOW())");
            $stmt->execute([$userId]);
            $conversationId = $db->lastInsertId();
        }
    }
    
    // Insert message (table name is 'messages', column is 'content')
    $stmt = $db->prepare("
        INSERT INTO messages (conversation_id, sender_type, sender_id, content, created_at) 
        VALUES (?, 'user', ?, ?, NOW())
    ");
    $result = $stmt->execute([$conversationId, $userId, $message]);
    
    // Update conversation
    $stmt = $db->prepare("UPDATE conversations SET last_message = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$message, $conversationId]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'conversation_id' => $conversationId,
            'message_id' => $db->lastInsertId()
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể gửi tin nhắn']);
    }
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
