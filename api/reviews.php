<?php
/**
 * Reviews API - MySQL Version
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Order.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$reviewModel = new Review();

switch ($action) {
    case 'list':
        listReviews($reviewModel);
        break;
    case 'create':
    case 'add_review':
        createReview($reviewModel, $input);
        break;
    case 'update':
        updateReview($reviewModel, $input);
        break;
    case 'delete':
        deleteReview($reviewModel, $input);
        break;
    case 'helpful':
        markHelpful($reviewModel, $input);
        break;
    case 'product-stats':
        getProductStats($reviewModel);
        break;
    case 'user-reviews':
        getUserReviews($reviewModel);
        break;
    case 'can-review':
        canReview($reviewModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listReviews($reviewModel) {
    $productId = $_GET['product_id'] ?? null;
    
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 10);
    $rating = isset($_GET['rating']) ? (int)$_GET['rating'] : null;
    
    $filters = [
        'product_id' => (int)$productId,
        'status' => 'approved'
    ];
    
    if ($rating) {
        $filters['rating'] = $rating;
    }
    
    $result = $reviewModel->getAll($page, $limit, $filters);
    $stats = $reviewModel->getProductStats((int)$productId);
    
    echo json_encode([
        'success' => true,
        'data' => $result['data'],
        'stats' => $stats,
        'pagination' => [
            'total' => $result['total'],
            'page' => $result['page'],
            'limit' => $result['limit'],
            'total_pages' => $result['total_pages']
        ]
    ]);
}

function createReview($reviewModel, $data) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đánh giá']);
        return;
    }
    
    // Handle multiple reviews from order
    if (isset($data['reviews']) && is_array($data['reviews'])) {
        $orderModel = new Order();
        $successCount = 0;
        $errors = [];
        
        foreach ($data['reviews'] as $review) {
            if (empty($review['product_id']) || empty($review['rating'])) {
                continue;
            }
            
            $rating = (int)$review['rating'];
            if ($rating < 1 || $rating > 5) {
                continue;
            }
            
            // Check if already reviewed
            $existingReview = $reviewModel->findByUserAndProduct($_SESSION['user_id'], (int)$review['product_id']);
            if ($existingReview) {
                continue;
            }
            
            $reviewData = [
                'user_id' => $_SESSION['user_id'],
                'product_id' => (int)$review['product_id'],
                'rating' => $rating,
                'title' => '',
                'content' => $review['comment'] ?? '',
                'status' => 'approved'
            ];
            
            $reviewId = $reviewModel->create($reviewData);
            if ($reviewId) {
                $successCount++;
                // Update product rating
                updateProductRating((int)$review['product_id']);
            }
        }
        
        if ($successCount > 0) {
            echo json_encode(['success' => true, 'message' => "Đã gửi $successCount đánh giá thành công"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể tạo đánh giá']);
        }
        return;
    }
    
    // Single review (existing logic)
    // Validate required fields
    if (empty($data['product_id']) || empty($data['rating'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn số sao đánh giá']);
        return;
    }
    
    $rating = (int)$data['rating'];
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Số sao không hợp lệ']);
        return;
    }
    
    // Check if user can review this product (has purchased it)
    $orderModel = new Order();
    $canReview = $orderModel->hasUserPurchasedProduct($_SESSION['user_id'], (int)$data['product_id']);
    
    if (!$canReview) {
        echo json_encode(['success' => false, 'message' => 'Bạn cần mua sản phẩm này trước khi đánh giá']);
        return;
    }
    
    // Check if already reviewed
    $existingReview = $reviewModel->findByUserAndProduct($_SESSION['user_id'], (int)$data['product_id']);
    
    if ($existingReview) {
        echo json_encode(['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này']);
        return;
    }
    
    $reviewData = [
        'user_id' => $_SESSION['user_id'],
        'product_id' => (int)$data['product_id'],
        'rating' => $rating,
        'title' => $data['title'] ?? '',
        'content' => $data['content'] ?? '',
        'status' => 'pending'
    ];
    
    $reviewId = $reviewModel->create($reviewData);
    
    if (!$reviewId) {
        echo json_encode(['success' => false, 'message' => 'Không thể tạo đánh giá']);
        return;
    }
    
    // Handle images if provided
    if (!empty($data['images']) && is_array($data['images'])) {
        foreach ($data['images'] as $imageUrl) {
            $reviewModel->addImage($reviewId, $imageUrl);
        }
    }
    
    // Update product rating (if status is approved)
    if ($reviewData['status'] === 'approved') {
        updateProductRating((int)$data['product_id']);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Đánh giá của bạn đã được gửi và đang chờ duyệt'
    ]);
}

function updateReview($reviewModel, $data) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    if (empty($data['review_id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID required']);
        return;
    }
    
    $review = $reviewModel->findById((int)$data['review_id']);
    
    if (!$review || $review['user_id'] !== $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        return;
    }
    
    $updateData = [];
    
    if (isset($data['rating'])) {
        $rating = (int)$data['rating'];
        if ($rating >= 1 && $rating <= 5) {
            $updateData['rating'] = $rating;
        }
    }
    
    if (isset($data['title'])) {
        $updateData['title'] = $data['title'];
    }
    
    if (isset($data['content'])) {
        $updateData['content'] = $data['content'];
    }
    
    if (!empty($updateData)) {
        $updateData['status'] = 'pending'; // Re-review after edit
        $reviewModel->update($review['id'], $updateData);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã cập nhật đánh giá'
    ]);
}

function deleteReview($reviewModel, $data) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    if (empty($data['review_id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID required']);
        return;
    }
    
    $review = $reviewModel->findById((int)$data['review_id']);
    
    if (!$review || $review['user_id'] !== $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        return;
    }
    
    $reviewModel->delete($review['id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa đánh giá'
    ]);
}

function markHelpful($reviewModel, $data) {
    if (empty($data['review_id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID required']);
        return;
    }
    
    $userId = $_SESSION['user_id'] ?? null;
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    
    $result = $reviewModel->markHelpful((int)$data['review_id'], $userId, $ipAddress);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Đã đánh dấu hữu ích']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Bạn đã đánh dấu đánh giá này']);
    }
}

function getProductStats($reviewModel) {
    $productId = $_GET['product_id'] ?? null;
    
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $stats = $reviewModel->getProductStats((int)$productId);
    
    echo json_encode([
        'success' => true,
        'data' => $stats
    ]);
}

function getUserReviews($reviewModel) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 10);
    
    $filters = ['user_id' => $_SESSION['user_id']];
    $result = $reviewModel->getAll($page, $limit, $filters);
    
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

function canReview($reviewModel) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'can_review' => false, 'reason' => 'not_logged_in']);
        return;
    }
    
    $productId = $_GET['product_id'] ?? null;
    
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    // Check if already reviewed
    $existingReview = $reviewModel->findByUserAndProduct($_SESSION['user_id'], (int)$productId);
    if ($existingReview) {
        echo json_encode(['success' => true, 'can_review' => false, 'reason' => 'already_reviewed']);
        return;
    }
    
    // Check if user purchased product
    $orderModel = new Order();
    $hasPurchased = $orderModel->hasUserPurchasedProduct($_SESSION['user_id'], (int)$productId);
    
    if (!$hasPurchased) {
        echo json_encode(['success' => true, 'can_review' => false, 'reason' => 'not_purchased']);
        return;
    }
    
    echo json_encode(['success' => true, 'can_review' => true]);
}

/**
 * Update product rating after review is created/updated/deleted
 */
function updateProductRating($productId) {
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT AVG(rating) as avg_rating, COUNT(*) as review_count
        FROM reviews
        WHERE product_id = ? AND status = 'approved'
    ");
    $stmt->execute([$productId]);
    $result = $stmt->fetch();
    
    $stmt = $db->prepare("UPDATE products SET rating = ?, review_count = ? WHERE id = ?");
    $stmt->execute([
        $result['avg_rating'] ? round($result['avg_rating'], 1) : 0,
        $result['review_count'],
        $productId
    ]);
}
?>
