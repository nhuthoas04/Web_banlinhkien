<?php
/**
 * Admin Reviews API - MySQL Version
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

// Check admin access - kiểm tra nhiều cách lưu session
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
    case 'list':
        listReviews($reviewModel);
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
    case 'delete':
        deleteReview($reviewModel, $input);
        break;
    case 'reply':
        replyReview($reviewModel, $input);
        break;
    case 'bulk-approve':
        bulkApprove($reviewModel, $input);
        break;
    case 'bulk-reject':
        bulkReject($reviewModel, $input);
        break;
    case 'stats':
        getStats($reviewModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listReviews($reviewModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [];
    
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    if (!empty($_GET['product_id'])) {
        $filters['product_id'] = (int)$_GET['product_id'];
    }
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    if (!empty($_GET['rating'])) {
        $filters['rating'] = (int)$_GET['rating'];
    }
    if (!empty($_GET['sort'])) {
        $filters['sort'] = $_GET['sort'];
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

function deleteReview($reviewModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Review ID required']);
        return;
    }
    
    $review = $reviewModel->findById((int)$data['id']);
    
    if (!$review) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        return;
    }
    
    $productId = $review['product_id'];
    $wasApproved = $review['status'] === 'approved';
    
    $reviewModel->delete($review['id']);
    
    // Update product rating if was approved
    if ($wasApproved) {
        updateProductRating($productId);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa đánh giá'
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

function bulkApprove($reviewModel, $data) {
    if (empty($data['ids']) || !is_array($data['ids'])) {
        echo json_encode(['success' => false, 'message' => 'Review IDs required']);
        return;
    }
    
    $approved = 0;
    $productIds = [];
    
    foreach ($data['ids'] as $id) {
        $review = $reviewModel->findById((int)$id);
        if ($review && $review['status'] !== 'approved') {
            $reviewModel->update($review['id'], ['status' => 'approved']);
            $productIds[] = $review['product_id'];
            $approved++;
        }
    }
    
    // Update product ratings
    foreach (array_unique($productIds) as $productId) {
        updateProductRating($productId);
    }
    
    echo json_encode([
        'success' => true,
        'message' => "Đã duyệt $approved đánh giá"
    ]);
}

function bulkReject($reviewModel, $data) {
    if (empty($data['ids']) || !is_array($data['ids'])) {
        echo json_encode(['success' => false, 'message' => 'Review IDs required']);
        return;
    }
    
    $rejected = 0;
    $productIds = [];
    
    foreach ($data['ids'] as $id) {
        $review = $reviewModel->findById((int)$id);
        if ($review && $review['status'] !== 'rejected') {
            if ($review['status'] === 'approved') {
                $productIds[] = $review['product_id'];
            }
            $reviewModel->update($review['id'], [
                'status' => 'rejected',
                'reject_reason' => $data['reason'] ?? ''
            ]);
            $rejected++;
        }
    }
    
    // Update product ratings
    foreach (array_unique($productIds) as $productId) {
        updateProductRating($productId);
    }
    
    echo json_encode([
        'success' => true,
        'message' => "Đã từ chối $rejected đánh giá"
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
    
    // Total by status
    $stmt = $db->query("SELECT status, COUNT(*) as count FROM reviews GROUP BY status");
    $byStatus = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Average rating
    $stmt = $db->query("SELECT AVG(rating) FROM reviews WHERE status = 'approved'");
    $avgRating = round($stmt->fetchColumn(), 1);
    
    // Rating distribution
    $stmt = $db->query("
        SELECT rating, COUNT(*) as count
        FROM reviews
        WHERE status = 'approved'
        GROUP BY rating
        ORDER BY rating DESC
    ");
    $ratingDistribution = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Recent reviews
    $stmt = $db->query("
        SELECT r.*, u.fullname as user_name, p.name as product_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN products p ON r.product_id = p.id
        ORDER BY r.created_at DESC
        LIMIT 5
    ");
    $recentReviews = $stmt->fetchAll();
    
    // Pending count
    $pendingCount = $byStatus['pending'] ?? 0;
    
    echo json_encode([
        'success' => true,
        'data' => [
            'by_status' => $byStatus,
            'average_rating' => $avgRating,
            'rating_distribution' => $ratingDistribution,
            'recent_reviews' => $recentReviews,
            'pending_count' => $pendingCount
        ]
    ]);
}
?>
