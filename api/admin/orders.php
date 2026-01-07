<?php
/**
 * Admin Orders API - MySQL Version
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

// Check admin access - kiểm tra nhiều cách lưu session
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
    case 'detail':
        getOrder($orderModel, $input);
        break;
    case 'update-status':
    case 'update_status':
        updateStatus($orderModel, $input);
        break;
    case 'assign':
        assignOrder($orderModel, $input);
        break;
    case 'add-note':
        addNote($orderModel, $input);
        break;
    case 'stats':
        getStats($orderModel);
        break;
    case 'export':
        exportOrders($orderModel);
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
    if (!empty($_GET['payment_status'])) {
        $filters['payment_status'] = $_GET['payment_status'];
    }
    if (!empty($_GET['date_from'])) {
        $filters['date_from'] = $_GET['date_from'];
    }
    if (!empty($_GET['date_to'])) {
        $filters['date_to'] = $_GET['date_to'];
    }
    if (!empty($_GET['sort'])) {
        $filters['sort'] = $_GET['sort'];
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
    $orderId = $data['order_id'] ?? $data['id'] ?? null;
    $status = $data['status'] ?? null;
    
    if (empty($orderId) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'Order ID and status required']);
        return;
    }
    
    $validStatuses = ['pending', 'confirmed', 'processing', 'shipping', 'delivered', 'cancelled', 'refunded'];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
        return;
    }
    
    $order = $orderModel->findById((int)$orderId);
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Cannot change status of completed orders
    if (in_array($order['status'], ['delivered', 'cancelled', 'refunded'])) {
        echo json_encode(['success' => false, 'message' => 'Không thể thay đổi trạng thái đơn hàng này']);
        return;
    }
    
    $orderModel->updateStatus($order['id'], $status);
    
    // Add history entry
    $note = $data['note'] ?? getStatusNote($status);
    $orderModel->addHistory($order['id'], $status, $note, $_SESSION['user_id']);
    
    // If cancelled, restore stock
    if ($status === 'cancelled' && $order['status'] !== 'cancelled') {
        require_once __DIR__ . '/../../models/Product.php';
        $productModel = new Product();
        
        foreach ($order['items'] as $item) {
            $productModel->restoreStock($item['product_id'], $item['quantity']);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật trạng thái thành công'
    ]);
}

function getStatusNote($status) {
    $notes = [
        'pending' => 'Đơn hàng đang chờ xác nhận',
        'confirmed' => 'Đơn hàng đã được xác nhận',
        'processing' => 'Đơn hàng đang được xử lý',
        'shipping' => 'Đơn hàng đang được giao',
        'delivered' => 'Đơn hàng đã giao thành công',
        'cancelled' => 'Đơn hàng đã bị hủy',
        'refunded' => 'Đơn hàng đã được hoàn tiền'
    ];
    
    return $notes[$status] ?? 'Cập nhật trạng thái';
}

function assignOrder($orderModel, $data) {
    if (empty($data['order_id']) || empty($data['employee_id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID and Employee ID required']);
        return;
    }
    
    $order = $orderModel->findById((int)$data['order_id']);
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    $orderModel->update($order['id'], ['assigned_to' => (int)$data['employee_id']]);
    
    // Add history
    $orderModel->addHistory($order['id'], $order['status'], 'Đơn hàng được phân công cho nhân viên', $_SESSION['user_id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã phân công đơn hàng'
    ]);
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
    $stats = $orderModel->getStats();
    
    // Get recent orders
    $recentOrders = $orderModel->getAll(1, 5, ['sort' => 'newest']);
    
    // Get monthly revenue
    $db = getDB();
    $stmt = $db->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue, COUNT(*) as orders
        FROM orders
        WHERE status NOT IN ('cancelled', 'refunded')
        AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC
    ");
    $monthlyRevenue = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'stats' => $stats,
            'recent_orders' => $recentOrders['data'],
            'monthly_revenue' => $monthlyRevenue
        ]
    ]);
}

function exportOrders($orderModel) {
    $filters = [];
    
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    if (!empty($_GET['date_from'])) {
        $filters['date_from'] = $_GET['date_from'];
    }
    if (!empty($_GET['date_to'])) {
        $filters['date_to'] = $_GET['date_to'];
    }
    
    $result = $orderModel->getAll(1, 10000, $filters);
    
    // Return CSV data
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=orders_' . date('Y-m-d') . '.csv');
    
    echo "\xEF\xBB\xBF"; // UTF-8 BOM
    
    $output = fopen('php://output', 'w');
    
    // Headers
    fputcsv($output, ['Mã đơn', 'Ngày đặt', 'Khách hàng', 'SĐT', 'Địa chỉ', 'Tổng tiền', 'Trạng thái', 'Thanh toán']);
    
    foreach ($result['data'] as $order) {
        fputcsv($output, [
            $order['order_number'],
            $order['created_at'],
            $order['shipping_fullname'],
            $order['shipping_phone'],
            $order['shipping_address'],
            $order['total'],
            $order['status'],
            $order['payment_status']
        ]);
    }
    
    fclose($output);
    exit;
}
?>
