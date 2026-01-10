<?php
/**
 * Employee Reviews API - MySQL Version
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
require_once __DIR__ . '/../../models/Review.php';

// Check employee access
$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';
if (!$userId || !in_array($userRole, ['admin', 'employee'])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$reviewModel = new Review();

switch ($action) {
    case 'pending':
        getPendingReviews($reviewModel);
        break;
    case 'detail':
        getReview($reviewModel, $input);
        break;
    case 'approve':
        approveReview($reviewModel, $input);
        break;
    case 'reject':
        rejectReview($reviewModel, $input);
        break;
    case 'reply':
        replyReview($reviewModel, $input);
        break;
    case 'stats':
        getStats($reviewModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getPendingReviews($reviewModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [
        'status' => 'pending'
    ];
    
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    
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

function getReview($reviewModel, $data) {
    $id = $data['id'] ?? $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Review ID required']);
        return;
    }
    
    $review = $reviewModel->findById((int)$id);
    
    if (!$review) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        return;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $review
    ]);
}

function approveReview($reviewModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID required']);
        return;
    }
    
    $review = $reviewModel->findById((int)$data['id']);
    
    if (!$review) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        return;
    }
    
    $reviewModel->update($review['id'], ['status' => 'approved']);
    
    // Update product rating
    updateProductRating($review['product_id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã duyệt đánh giá'
    ]);
}

function rejectReview($reviewModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID required']);
        return;
    }
    
    $review = $reviewModel->findById((int)$data['id']);
    
    if (!$review) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        return;
    }
    
    $reviewModel->update($review['id'], [
        'status' => 'rejected',
        'reject_reason' => $data['reason'] ?? ''
    ]);
    
    // Update product rating if was approved
    if ($review['status'] === 'approved') {
        updateProductRating($review['product_id']);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã từ chối đánh giá'
    ]);
}

function replyReview($reviewModel, $data) {
    if (empty($data['id']) || empty($data['reply'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID and reply required']);
        return;
    }
    
    $review = $reviewModel->findById((int)$data['id']);
    
    if (!$review) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        return;
    }
    
    $reviewModel->update($review['id'], [
        'admin_reply' => $data['reply'],
        'admin_reply_at' => date('Y-m-d H:i:s'),
        'admin_reply_by' => $_SESSION['user_id']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã trả lời đánh giá'
    ]);
}

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

function getStats($reviewModel) {
    $db = getDB();
    
    // Pending count
    $stmt = $db->query("SELECT COUNT(*) FROM reviews WHERE status = 'pending'");
    $pending = $stmt->fetchColumn();
    
    // Today's reviews
    $stmt = $db->query("
        SELECT COUNT(*) as total,
               SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
               SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM reviews
        WHERE DATE(created_at) = CURDATE()
    ");
    $today = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'pending_count' => $pending,
            'today' => $today
        ]
    ]);
}
?>
