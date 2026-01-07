<?php
/**
 * Employee Orders API - MySQL Version
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
require_once __DIR__ . '/../../models/Order.php';

// Check employee access
$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';
if (!$userId || !in_array($userRole, ['admin', 'employee'])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$orderModel = new Order();

switch ($action) {
    case 'list':
        listOrders($orderModel);
        break;
    case 'my-orders':
        myOrders($orderModel);
        break;
    case 'detail':
        getOrder($orderModel, $input);
        break;
    case 'update-status':
        updateStatus($orderModel, $input);
        break;
    case 'add-note':
        addNote($orderModel, $input);
        break;
    case 'stats':
        getStats($orderModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listOrders($orderModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [];
    
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    if (!empty($_GET['date'])) {
        $filters['date'] = $_GET['date'];
    }
    
    // Employee can only see pending and confirmed orders by default
    if ($_SESSION['user_role'] === 'employee' && empty($_GET['status'])) {
        $filters['status_in'] = ['pending', 'confirmed', 'processing'];
    }
    
    $result = $orderModel->getAll($page, $limit, $filters);
    
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

function myOrders($orderModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [
        'assigned_to' => $_SESSION['user_id']
    ];
    
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    
    $result = $orderModel->getAll($page, $limit, $filters);
    
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

function getOrder($orderModel, $data) {
    $id = $data['id'] ?? $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $order = $orderModel->findById((int)$id);
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Get order history
    $order['history'] = $orderModel->getHistory($order['id']);
    
    echo json_encode([
        'success' => true,
        'data' => $order
    ]);
}

function updateStatus($orderModel, $data) {
    if (empty($data['id']) || empty($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID and status required']);
        return;
    }
    
    // Employee can only update to certain statuses
    $allowedStatuses = ['confirmed', 'processing', 'shipping'];
    if ($_SESSION['user_role'] === 'employee' && !in_array($data['status'], $allowedStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Không có quyền cập nhật trạng thái này']);
        return;
    }
    
    $order = $orderModel->findById((int)$data['id']);
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Cannot change completed orders
    if (in_array($order['status'], ['delivered', 'cancelled', 'refunded'])) {
        echo json_encode(['success' => false, 'message' => 'Không thể thay đổi trạng thái đơn hàng này']);
        return;
    }
    
    $orderModel->updateStatus($order['id'], $data['status']);
    
    // Add history
    $note = $data['note'] ?? getStatusNote($data['status']);
    $orderModel->addHistory($order['id'], $data['status'], $note, $_SESSION['user_id']);
    
    // Auto-assign to employee if not assigned
    if (!$order['assigned_to']) {
        $orderModel->update($order['id'], ['assigned_to' => $_SESSION['user_id']]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật trạng thái thành công'
    ]);
}

function getStatusNote($status) {
    $notes = [
        'confirmed' => 'Đơn hàng đã được xác nhận',
        'processing' => 'Đơn hàng đang được xử lý',
        'shipping' => 'Đơn hàng đang được giao'
    ];
    
    return $notes[$status] ?? 'Cập nhật trạng thái';
}

function addNote($orderModel, $data) {
    if (empty($data['order_id']) || empty($data['note'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID and note required']);
        return;
    }
    
    $order = $orderModel->findById((int)$data['order_id']);
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    $orderModel->addHistory($order['id'], $order['status'], $data['note'], $_SESSION['user_id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã thêm ghi chú'
    ]);
}

function getStats($orderModel) {
    $db = getDB();
    
    // Today's stats
    $stmt = $db->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing
        FROM orders
        WHERE DATE(created_at) = CURDATE()
    ");
    $today = $stmt->fetch();
    
    // My assigned orders
    $stmt = $db->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered
        FROM orders
        WHERE assigned_to = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $myOrders = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'today' => $today,
            'my_orders' => $myOrders
        ]
    ]);
}
?>
