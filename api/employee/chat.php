<?php
/**
 * Employee Chat API - MySQL Version
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
require_once __DIR__ . '/../../models/Conversation.php';

// Check employee access
$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';
if (!$userId || !in_array($userRole, ['admin', 'employee'])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$conversationModel = new Conversation();

switch ($action) {
    case 'conversations':
        listConversations($conversationModel);
        break;
    case 'my-conversations':
        myConversations($conversationModel);
        break;
    case 'messages':
        getMessages($conversationModel);
        break;
    case 'send':
        sendMessage($conversationModel, $input);
        break;
    case 'assign':
        assignConversation($conversationModel, $input);
        break;
    case 'close':
        closeConversation($conversationModel, $input);
        break;
    case 'mark-read':
        markAsRead($conversationModel, $input);
        break;
    case 'stats':
        getStats($conversationModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listConversations($conversationModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [];
    
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    if (isset($_GET['assigned'])) {
        $filters['assigned'] = $_GET['assigned'] === '1';
    }
    
    $result = $conversationModel->getAll($page, $limit, $filters);
    
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

function myConversations($conversationModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [
        'assigned_to' => $_SESSION['user_id']
    ];
    
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    
    $result = $conversationModel->getAll($page, $limit, $filters);
    
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

function getMessages($conversationModel) {
    $conversationId = $_GET['conversation_id'] ?? null;
    
    if (!$conversationId) {
        echo json_encode(['success' => false, 'message' => 'Conversation ID required']);
        return;
    }
    
    $conversation = $conversationModel->findById((int)$conversationId);
    
    if (!$conversation) {
        echo json_encode(['success' => false, 'message' => 'Cuộc trò chuyện không tồn tại']);
        return;
    }
    
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 50);
    
    $messages = $conversationModel->getMessages($conversation['id'], $page, $limit);
    
    // Mark as read by employee
    $conversationModel->markAsRead($conversation['id'], false);
    
    echo json_encode([
        'success' => true,
        'data' => [
            'conversation' => $conversation,
            'messages' => $messages
        ]
    ]);
}

function sendMessage($conversationModel, $data) {
    if (empty($data['conversation_id']) || empty($data['message'])) {
        echo json_encode(['success' => false, 'message' => 'Conversation ID and message required']);
        return;
    }
    
    $conversation = $conversationModel->findById((int)$data['conversation_id']);
    
    if (!$conversation) {
        echo json_encode(['success' => false, 'message' => 'Cuộc trò chuyện không tồn tại']);
        return;
    }
    
    // Auto-assign if not assigned
    if (!$conversation['assigned_to']) {
        $conversationModel->assign($conversation['id'], $_SESSION['user_id']);
    }
    
    $messageId = $conversationModel->addMessage(
        $conversation['id'],
        $_SESSION['user_id'],
        $data['message'],
        false // is_customer = false
    );
    
    if ($messageId) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã gửi tin nhắn',
            'message_id' => $messageId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể gửi tin nhắn']);
    }
}

function assignConversation($conversationModel, $data) {
    if (empty($data['conversation_id'])) {
        echo json_encode(['success' => false, 'message' => 'Conversation ID required']);
        return;
    }
    
    $conversation = $conversationModel->findById((int)$data['conversation_id']);
    
    if (!$conversation) {
        echo json_encode(['success' => false, 'message' => 'Cuộc trò chuyện không tồn tại']);
        return;
    }
    
    $employeeId = $data['employee_id'] ?? $_SESSION['user_id'];
    
    $conversationModel->assign($conversation['id'], (int)$employeeId);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã nhận cuộc trò chuyện'
    ]);
}

function closeConversation($conversationModel, $data) {
    if (empty($data['conversation_id'])) {
        echo json_encode(['success' => false, 'message' => 'Conversation ID required']);
        return;
    }
    
    $conversation = $conversationModel->findById((int)$data['conversation_id']);
    
    if (!$conversation) {
        echo json_encode(['success' => false, 'message' => 'Cuộc trò chuyện không tồn tại']);
        return;
    }
    
    $conversationModel->close($conversation['id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã đóng cuộc trò chuyện'
    ]);
}

function markAsRead($conversationModel, $data) {
    if (empty($data['conversation_id'])) {
        echo json_encode(['success' => false, 'message' => 'Conversation ID required']);
        return;
    }
    
    $conversationModel->markAsRead((int)$data['conversation_id'], false);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã đánh dấu đã đọc'
    ]);
}

function getStats($conversationModel) {
    $db = getDB();
    
    // Unassigned conversations
    $stmt = $db->query("SELECT COUNT(*) FROM conversations WHERE assigned_to IS NULL AND status = 'open'");
    $unassigned = $stmt->fetchColumn();
    
    // My stats
    $stmt = $db->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open,
            SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed
        FROM conversations
        WHERE assigned_to = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $myStats = $stmt->fetch();
    
    // Unread messages
    $stmt = $db->prepare("
        SELECT COUNT(*) FROM messages m
        JOIN conversations c ON m.conversation_id = c.id
        WHERE c.assigned_to = ? AND m.is_customer = 1 AND m.is_read = 0
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $unreadMessages = $stmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'unassigned' => $unassigned,
            'my_stats' => $myStats,
            'unread_messages' => $unreadMessages
        ]
    ]);
}
?>
