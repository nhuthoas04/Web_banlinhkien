<?php
/**
 * Cart API - MySQL Version
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$cartModel = new Cart();
$productModel = new Product();

// Get user ID or session ID
$userId = $_SESSION['user_id'] ?? null;
$sessionId = null;
if (!$userId) {
    if (empty($_SESSION['cart_session_id'])) {
        $_SESSION['cart_session_id'] = session_id();
    }
    $sessionId = $_SESSION['cart_session_id'];
}

switch ($action) {
    case 'get':
        getCart($cartModel, $userId, $sessionId);
        break;
    case 'add':
        addToCart($cartModel, $productModel, $userId, $sessionId, $input);
        break;
    case 'update':
        updateCart($cartModel, $userId, $sessionId, $input);
        break;
    case 'remove':
        removeFromCart($cartModel, $userId, $sessionId, $input);
        break;
    case 'remove_multiple':
        removeMultipleFromCart($cartModel, $userId, $sessionId, $input);
        break;
    case 'clear':
        clearCart($cartModel, $userId, $sessionId);
        break;
    case 'count':
        getCartCount($cartModel, $userId, $sessionId);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getCart($cartModel, $userId, $sessionId) {
    $cart = $cartModel->getCart($userId, $sessionId);
    echo json_encode([
        'success' => true,
        'cart' => $cart
    ]);
}

function addToCart($cartModel, $productModel, $userId, $sessionId, $data) {
    if (empty($data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $productId = (int)$data['product_id'];
    $quantity = (int)($data['quantity'] ?? 1);
    
    // Check product exists and in stock
    $product = $productModel->findById($productId);
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        return;
    }
    
    if ($product['status'] !== 'active') {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không còn bán']);
        return;
    }
    
    if ($product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Số lượng vượt quá tồn kho']);
        return;
    }
    
    $cart = $cartModel->addItem($productId, $quantity, $userId, $sessionId);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã thêm vào giỏ hàng',
        'cart' => $cart,
        'cart_count' => $cart['count'] ?? 0
    ]);
}

function updateCart($cartModel, $userId, $sessionId, $data) {
    if (empty($data['product_id']) || !isset($data['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $productId = (int)$data['product_id'];
    $quantity = (int)$data['quantity'];
    
    $cart = $cartModel->updateItem($productId, $quantity, $userId, $sessionId);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã cập nhật giỏ hàng',
        'cart' => $cart,
        'cart_count' => $cart['count'] ?? 0
    ]);
}

function removeFromCart($cartModel, $userId, $sessionId, $data) {
    if (empty($data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $productId = (int)$data['product_id'];
    $cart = $cartModel->removeItem($productId, $userId, $sessionId);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa khỏi giỏ hàng',
        'cart' => $cart,
        'cart_count' => $cart['count'] ?? 0
    ]);
}

function removeMultipleFromCart($cartModel, $userId, $sessionId, $data) {
    if (empty($data['item_ids']) || !is_array($data['item_ids'])) {
        echo json_encode(['success' => false, 'message' => 'Item IDs required']);
        return;
    }
    
    $cart = $cartModel->removeMultipleItems($data['item_ids'], $userId, $sessionId);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa các sản phẩm đã chọn',
        'cart' => $cart,
        'cart_count' => $cart['count'] ?? 0
    ]);
}

function clearCart($cartModel, $userId, $sessionId) {
    $cart = $cartModel->clear($userId, $sessionId);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa toàn bộ giỏ hàng',
        'cart' => $cart
    ]);
}

function getCartCount($cartModel, $userId, $sessionId) {
    $count = $cartModel->getCount($userId, $sessionId);
    
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
}
?>
