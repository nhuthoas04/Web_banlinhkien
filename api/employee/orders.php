<?php
/**
 * API Quản lý Đơn hàng cho Nhân viên - Phiên bản MySQL
 * File này xử lý tất cả các yêu cầu API liên quan đến quản lý đơn hàng của nhân viên
 */

// Tắt hiển thị lỗi để đảm bảo output JSON sạch sẽ
error_reporting(0);
ini_set('display_errors', 0);

// Khởi động session nếu chưa được khởi động
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thiết lập header trả về dữ liệu dạng JSON
header('Content-Type: application/json');

// Import các file cần thiết
require_once __DIR__ . '/../../config/database.php';  // Kết nối database
require_once __DIR__ . '/../../models/Order.php';      // Model xử lý đơn hàng

// ============================================================
// KIỂM TRA QUYỀN TRUY CẬP
// ============================================================
// Lấy thông tin user từ session (hỗ trợ nhiều định dạng session)
$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';

// Chỉ cho phép admin và employee truy cập API này
if (!$userId || !in_array($userRole, ['admin', 'employee'])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

// Đọc dữ liệu JSON từ request body
$input = json_decode(file_get_contents('php://input'), true);

// Lấy action từ request (hỗ trợ cả POST và GET)
$action = $input['action'] ?? $_GET['action'] ?? '';

// Khởi tạo model đơn hàng
$orderModel = new Order();

// ============================================================
// ROUTER - Điều hướng đến các chức năng tương ứng
// ============================================================
switch ($action) {
    case 'list':
        // Lấy danh sách tất cả đơn hàng (có phân trang, lọc)
        listOrders($orderModel);
        break;
    case 'my-orders':
        // Lấy danh sách đơn hàng được phân công cho nhân viên này
        myOrders($orderModel);
        break;
    case 'detail':
        // Xem chi tiết một đơn hàng cụ thể
        getOrder($orderModel, $input);
        break;
    case 'update_status':
    case 'update-status':
        // Cập nhật trạng thái đơn hàng (hỗ trợ cả _ và -)
        updateStatus($orderModel, $input);
        break;
    case 'add-note':
        // Thêm ghi chú vào đơn hàng
        addNote($orderModel, $input);
        break;
    case 'stats':
        // Lấy thống kê đơn hàng
        getStats($orderModel);
        break;
    default:
        // Action không hợp lệ
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

// ============================================================
// FUNCTION: Lấy danh sách đơn hàng (có phân trang và bộ lọc)
// ============================================================
function listOrders($orderModel) {
    // Lấy thông số phân trang từ URL, mặc định trang 1, 20 đơn/trang
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    // Mảng chứa các điều kiện lọc
    $filters = [];
    
    // Lọc theo từ khóa tìm kiếm (mã đơn, tên khách hàng, SĐT)
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    // Lọc theo trạng thái đơn hàng
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    // Lọc theo ngày tạo đơn
    if (!empty($_GET['date'])) {
        $filters['date'] = $_GET['date'];
    }
    
    // Nhân viên chỉ được xem đơn pending, confirmed, processing theo mặc định
    // Admin xem được tất cả
    $userRole = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';
    if ($userRole === 'employee' && empty($_GET['status'])) {
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

// ============================================================
// FUNCTION: Lấy danh sách đơn hàng được phân công cho nhân viên này
// ============================================================
function myOrders($orderModel) {
    // Lấy thông số phân trang
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    // Lọc theo nhân viên được phân công (assigned_to = user_id hiện tại)
    $filters = [
        'assigned_to' => $_SESSION['user_id']
    ];
    
    // Có thể lọc thêm theo trạng thái
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    
    // Lấy dữ liệu từ database
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

// ============================================================
// FUNCTION: Xem chi tiết một đơn hàng
// ============================================================
function getOrder($orderModel, $data) {
    // Lấy ID đơn hàng từ request (hỗ trợ cả POST và GET)
    $id = $data['id'] ?? $_GET['id'] ?? null;
    
    // Kiểm tra ID có tồn tại không
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    // Tìm đơn hàng trong database theo ID
    $order = $orderModel->findById((int)$id);
    
    // Kiểm tra đơn hàng có tồn tại không
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Lấy lịch sử thay đổi trạng thái của đơn hàng (từ bảng order_history)
    $order['history'] = $orderModel->getHistory($order['id']);
    
    echo json_encode([
        'success' => true,
        'data' => $order
    ]);
}

// ============================================================
// FUNCTION: Cập nhật trạng thái đơn hàng
// ============================================================
function updateStatus($orderModel, $data) {
    // Lấy ID đơn hàng (hỗ trợ cả 'order_id' và 'id' để tương thích)
    $orderId = $data['order_id'] ?? $data['id'] ?? null;
    
    // Kiểm tra dữ liệu đầu vào
    if (empty($orderId) || empty($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID and status required']);
        return;
    }
    
    // Nhân viên chỉ được phép cập nhật một số trạng thái nhất định
    // Admin có thể cập nhật tất cả trạng thái
    $allowedStatuses = ['pending', 'confirmed', 'processing', 'shipping', 'delivered'];
    $userRole = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';
    
    // Kiểm tra quyền của nhân viên
    if ($userRole === 'employee' && !in_array($data['status'], $allowedStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Không có quyền cập nhật trạng thái này']);
        return;
    }
    
    // Kiểm tra đơn hàng có tồn tại không
    $order = $orderModel->findById((int)$orderId);
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Không cho phép thay đổi trạng thái của đơn hàng đã hoàn thành/hủy/hoàn tiền
    if (in_array($order['status'], ['delivered', 'cancelled', 'refunded'])) {
        echo json_encode(['success' => false, 'message' => 'Không thể thay đổi trạng thái đơn hàng này']);
        return;
    }
    
    // Cập nhật trạng thái đơn hàng trong database
    $orderModel->updateStatus($order['id'], $data['status']);
    
    // Thêm bản ghi vào lịch sử thay đổi (order_history)
    $note = $data['note'] ?? getStatusNote($data['status']); // Lấy note từ request hoặc tự động tạo
    $orderModel->addHistory($order['id'], $data['status'], $note, $_SESSION['user_id']);
    
    // Tự động phân công đơn hàng cho nhân viên nếu chưa được phân công
    if (!$order['assigned_to']) {
        $orderModel->update($order['id'], ['assigned_to' => $_SESSION['user_id']]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật trạng thái thành công'
    ]);
}

// ============================================================
// FUNCTION: Tạo ghi chú tự động dựa trên trạng thái
// ============================================================
function getStatusNote($status) {
    // Danh sách ghi chú mặc định cho từng trạng thái
    $notes = [
        'confirmed' => 'Đơn hàng đã được xác nhận',
        'processing' => 'Đơn hàng đang được xử lý',
        'shipping' => 'Đơn hàng đang được giao'
    ];
    
    // Trả về ghi chú tương ứng, hoặc ghi chú mặc định nếu không có
    return $notes[$status] ?? 'Cập nhật trạng thái';
}

// ============================================================
// FUNCTION: Thêm ghi chú vào đơn hàng (không thay đổi trạng thái)
// ============================================================
function addNote($orderModel, $data) {
    // Kiểm tra dữ liệu đầu vào
    if (empty($data['order_id']) || empty($data['note'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID and note required']);
        return;
    }
    
    // Kiểm tra đơn hàng có tồn tại không
    $order = $orderModel->findById((int)$data['order_id']);
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Thêm ghi chú vào lịch sử (giữ nguyên trạng thái hiện tại)
    $orderModel->addHistory($order['id'], $order['status'], $data['note'], $_SESSION['user_id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã thêm ghi chú'
    ]);
}

// ============================================================
// FUNCTION: Lấy thống kê đơn hàng cho nhân viên
// ============================================================
function getStats($orderModel) {
    // Lấy kết nối database
    $db = getDB();
    
    // ===== Thống kê đơn hàng hôm nay =====
    $stmt = $db->query("
        SELECT 
            COUNT(*) as total,                                                    -- Tổng số đơn
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,      -- Đơn chờ xử lý
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,  -- Đơn đã xác nhận
            SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing -- Đơn đang xử lý
        FROM orders
        WHERE DATE(created_at) = CURDATE()  -- Chỉ lấy đơn tạo hôm nay
    ");
    $today = $stmt->fetch();
    
    // ===== Thống kê đơn hàng được phân công cho nhân viên này =====
    $stmt = $db->prepare("
        SELECT 
            COUNT(*) as total,                                                    -- Tổng số đơn được phân công
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,      -- Đơn chờ xử lý
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,  -- Đơn đã xác nhận
            SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,-- Đơn đang xử lý
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered   -- Đơn đã giao
        FROM orders
        WHERE assigned_to = ?  -- Lọc theo nhân viên hiện tại
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $myOrders = $stmt->fetch();
    
    // Trả về kết quả thống kê dạng JSON
    echo json_encode([
        'success' => true,
        'data' => [
            'today' => $today,          // Thống kê hôm nay
            'my_orders' => $myOrders    // Thống kê đơn của tôi
        ]
    ]);
}
?>
