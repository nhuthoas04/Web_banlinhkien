<?php
/**
 * Wishlist API - MySQL Version
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        getWishlist();
        break;
    case 'add':
        addToWishlist($input);
        break;
    case 'remove':
        removeFromWishlist($input);
        break;
    case 'toggle':
        toggleWishlist($input);
        break;
    case 'check':
        checkWishlist();
        break;
    case 'count':
        getWishlistCount();
        break;
    case 'clear':
        clearWishlist();
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

function getWishlist() {
    checkAuth();
    
    $db = getDB();
    
    $sql = "SELECT w.*, p.name, p.slug, p.price, p.sale_price, p.stock, p.status,
            (SELECT pi.image_url FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.sort_order LIMIT 1) as image
            FROM wishlist w
            JOIN products p ON w.product_id = p.id
            WHERE w.user_id = ?
            ORDER BY w.created_at DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $items = $stmt->fetchAll();
    
    // Format items
    foreach ($items as &$item) {
        $item['final_price'] = $item['sale_price'] ?: $item['price'];
        $item['discount_percent'] = $item['sale_price'] ? round((1 - $item['sale_price'] / $item['price']) * 100) : 0;
        $item['in_stock'] = $item['stock'] > 0 && $item['status'] === 'active';
    }
    
    echo json_encode([
        'success' => true,
        'data' => $items
    ]);
}

function addToWishlist($data) {
    checkAuth();
    
    if (empty($data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $productId = (int)$data['product_id'];
    
    // Check if product exists
    $productModel = new Product();
    $product = $productModel->findById($productId);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        return;
    }
    
    $db = getDB();
    
    // Check if already in wishlist
    $stmt = $db->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $productId]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm đã có trong danh sách yêu thích']);
        return;
    }
    
    // Add to wishlist
    $stmt = $db->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $result = $stmt->execute([$_SESSION['user_id'], $productId]);
    
    if ($result) {
        $count = getCount();
        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích',
            'count' => $count
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể thêm vào danh sách yêu thích']);
    }
}

function removeFromWishlist($data) {
    checkAuth();
    
    if (empty($data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $result = $stmt->execute([$_SESSION['user_id'], (int)$data['product_id']]);
    
    if ($result) {
        $count = getCount();
        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích',
            'count' => $count
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa khỏi danh sách yêu thích']);
    }
}

function toggleWishlist($data) {
    checkAuth();
    
    if (empty($data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $productId = (int)$data['product_id'];
    $db = getDB();
    
    // Check if in wishlist
    $stmt = $db->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $productId]);
    
    if ($stmt->fetch()) {
        // Remove from wishlist
        $stmt = $db->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $productId]);
        
        $count = getCount();
        echo json_encode([
            'success' => true,
            'in_wishlist' => false,
            'message' => 'Đã xóa khỏi danh sách yêu thích',
            'count' => $count
        ]);
    } else {
        // Check if product exists
        $productModel = new Product();
        $product = $productModel->findById($productId);
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            return;
        }
        
        // Add to wishlist
        $stmt = $db->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $productId]);
        
        $count = getCount();
        echo json_encode([
            'success' => true,
            'in_wishlist' => true,
            'message' => 'Đã thêm vào danh sách yêu thích',
            'count' => $count
        ]);
    }
}

function checkWishlist() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'data' => []]);
        return;
    }
    
    $productIds = $_GET['product_ids'] ?? '';
    
    if (empty($productIds)) {
        echo json_encode(['success' => true, 'data' => []]);
        return;
    }
    
    $ids = array_map('intval', explode(',', $productIds));
    
    if (empty($ids)) {
        echo json_encode(['success' => true, 'data' => []]);
        return;
    }
    
    $db = getDB();
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    
    $stmt = $db->prepare("SELECT product_id FROM wishlist WHERE user_id = ? AND product_id IN ($placeholders)");
    $stmt->execute(array_merge([$_SESSION['user_id']], $ids));
    
    $inWishlist = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode([
        'success' => true,
        'data' => $inWishlist
    ]);
}

function getWishlistCount() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'count' => 0]);
        return;
    }
    
    $count = getCount();
    
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
}

function clearWishlist() {
    checkAuth();
    
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM wishlist WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa tất cả sản phẩm khỏi danh sách yêu thích',
        'count' => 0
    ]);
}

function getCount() {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return (int)$stmt->fetchColumn();
}
?>
