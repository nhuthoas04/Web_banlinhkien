<?php
/**
 * Orders API - MySQL Version
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

// Support both JSON and FormData input
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
} else {
    $input = $_POST;
}
$action = $input['action'] ?? $_GET['action'] ?? '';

$orderModel = new Order();

switch ($action) {
    case 'create':
        createOrder($orderModel, $input);
        break;
    case 'list':
        listOrders($orderModel);
        break;
    case 'detail':
        getOrderDetail($orderModel, $input);
        break;
    case 'track':
        trackOrder($orderModel, $input);
        break;
    case 'cancel':
        cancelOrder($orderModel, $input);
        break;
    case 'reorder':
        reorder($orderModel, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function createOrder($orderModel, $data) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt hàng']);
        return;
    }
    
    // Validate required fields
    $required = ['fullname', 'phone', 'address'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            return;
        }
    }
    
    // Get cart items
    $cartModel = new Cart();
    $productModel = new Product();
    
    $cart = $cartModel->getByUserId($_SESSION['user_id']);
    if (!$cart) {
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
        return;
    }
    
    $cartItems = $cartModel->getItems($cart['id']);
    if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
        return;
    }
    
    // Validate stock and prepare items
    $items = [];
    $subtotal = 0;
    
    foreach ($cartItems as $item) {
        $product = $productModel->findById($item['product_id']);
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại: ' . $item['product_name']]);
            return;
        }
        
        if ($product['stock'] < $item['quantity']) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm "' . $product['name'] . '" chỉ còn ' . $product['stock'] . ' sản phẩm']);
            return;
        }
        
        $price = $product['sale_price'] ?: $product['price'];
        $itemTotal = $price * $item['quantity'];
        $subtotal += $itemTotal;
        
        $items[] = [
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'product_image' => $product['images'][0]['image_url'] ?? '',
            'price' => $price,
            'original_price' => $product['price'],
            'quantity' => $item['quantity'],
            'total' => $itemTotal
        ];
    }
    
    // Calculate shipping
    $shipping_fee = $subtotal >= 5000000 ? 0 : 30000;
    
    // Apply coupon if provided
    $discount = 0;
    if (!empty($data['coupon_code'])) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'active' AND start_date <= NOW() AND end_date >= NOW()");
        $stmt->execute([$data['coupon_code']]);
        $coupon = $stmt->fetch();
        
        if ($coupon) {
            if ($subtotal >= $coupon['min_order']) {
                if ($coupon['discount_type'] === 'percent') {
                    $discount = $subtotal * ($coupon['discount_value'] / 100);
                    if ($coupon['max_discount'] && $discount > $coupon['max_discount']) {
                        $discount = $coupon['max_discount'];
                    }
                } else {
                    $discount = $coupon['discount_value'];
                }
                
                // Update coupon usage
                $stmt = $db->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?");
                $stmt->execute([$coupon['id']]);
            }
        }
    }
    
    $total = $subtotal + $shipping_fee - $discount;
    
    // Create order
    $orderData = [
        'user_id' => $_SESSION['user_id'],
        'customer_name' => $data['fullname'],
        'customer_email' => $data['email'] ?? '',
        'customer_phone' => $data['phone'],
        'shipping_address' => $data['address'],
        'shipping_city' => $data['province'] ?? '',
        'shipping_district' => $data['district'] ?? '',
        'shipping_ward' => $data['ward'] ?? '',
        'subtotal' => $subtotal,
        'shipping_fee' => $shipping_fee,
        'discount' => $discount,
        'coupon_code' => $data['coupon_code'] ?? null,
        'total' => $total,
        'payment_method' => $data['payment_method'] ?? 'cod',
        'note' => $data['note'] ?? '',
        'items' => $items
    ];
    
    $orderId = $orderModel->create($orderData);
    
    if (!$orderId) {
        echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng']);
        return;
    }
    
    // Update product stock
    foreach ($items as $item) {
        $productModel->updateStock($item['product_id'], $item['quantity']);
    }
    
    // Clear cart
    $cartModel->clear($_SESSION['user_id'], null);
    
    // Get order details
    $order = $orderModel->findById($orderId);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đặt hàng thành công',
        'order_id' => $orderId,
        'order_code' => $order['order_number'] ?? ('DH' . str_pad($orderId, 6, '0', STR_PAD_LEFT)),
        'data' => $order
    ]);
}

function listOrders($orderModel) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 10);
    $status = $_GET['status'] ?? null;
    
    $filters = ['user_id' => $_SESSION['user_id']];
    if ($status) {
        $filters['status'] = $status;
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

function getOrderDetail($orderModel, $data) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    $orderId = $data['order_id'] ?? $_GET['order_id'] ?? $_GET['id'] ?? $data['id'] ?? null;
    
    if (!$orderId) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $order = $orderModel->findById((int)$orderId);
    
    if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    echo json_encode([
        'success' => true,
        'order' => $order,
        'data' => $order
    ]);
}

function trackOrder($orderModel, $data) {
    $orderNumber = $data['order_number'] ?? $_GET['order_number'] ?? '';
    
    if (empty($orderNumber)) {
        echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ']);
        return;
    }
    
    $order = $orderModel->findByOrderNumber($orderNumber);
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
        return;
    }
    
    // Get order history
    $history = $orderModel->getHistory($order['id']);
    
    echo json_encode([
        'success' => true,
        'data' => [
            'order' => $order,
            'history' => $history
        ]
    ]);
}

function cancelOrder($orderModel, $data) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    if (empty($data['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $order = $orderModel->findById((int)$data['order_id']);
    
    if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Only allow canceling pending orders
    if ($order['status'] !== 'pending') {
        echo json_encode(['success' => false, 'message' => 'Chỉ có thể hủy đơn hàng chưa xác nhận']);
        return;
    }
    
    // Update order status
    $updated = $orderModel->updateStatus($order['id'], 'cancelled');
    
    if ($updated) {
        // Restore stock
        $productModel = new Product();
        foreach ($order['items'] as $item) {
            $productModel->restoreStock($item['product_id'], $item['quantity']);
        }
        
        // Add history
        $reason = $data['reason'] ?? 'Khách hàng hủy đơn';
        $orderModel->addHistory($order['id'], 'cancelled', $reason);
        
        echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng']);
    }
}

function reorder($orderModel, $data) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    if (empty($data['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Order ID required']);
        return;
    }
    
    $order = $orderModel->findById((int)$data['order_id']);
    
    if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        return;
    }
    
    // Add items to cart
    $cartModel = new Cart();
    $productModel = new Product();
    
    $cart = $cartModel->getByUserId($_SESSION['user_id']);
    if (!$cart) {
        $cartId = $cartModel->create(['user_id' => $_SESSION['user_id']]);
        $cart = ['id' => $cartId];
    }
    
    $added = 0;
    foreach ($order['items'] as $item) {
        $product = $productModel->findById($item['product_id']);
        if ($product && $product['status'] === 'active' && $product['stock'] > 0) {
            $quantity = min($item['quantity'], $product['stock']);
            $cartModel->addItem($cart['id'], $item['product_id'], $quantity);
            $added++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => "Đã thêm $added sản phẩm vào giỏ hàng"
    ]);
}
?>
