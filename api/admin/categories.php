<?php
/**
 * Admin Categories API - MySQL Version
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
require_once __DIR__ . '/../../models/Category.php';

// Check admin access - kiểm tra nhiều cách lưu session
$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';

if (!$userId || !in_array($userRole, ['admin', 'employee'])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$categoryModel = new Category();

switch ($action) {
    case 'list':
        listCategories($categoryModel);
        break;
    case 'tree':
        getCategoryTree($categoryModel);
        break;
    case 'detail':
        getCategory($categoryModel, $input);
        break;
    case 'create':
        createCategory($categoryModel, $input);
        break;
    case 'update':
        updateCategory($categoryModel, $input);
        break;
    case 'delete':
        deleteCategory($categoryModel, $input);
        break;
    case 'update-status':
        updateStatus($categoryModel, $input);
        break;
    case 'reorder':
        reorderCategories($categoryModel, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listCategories($categoryModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 50);
    
    $filters = [];
    
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    if (isset($_GET['parent_id'])) {
        $filters['parent_id'] = $_GET['parent_id'] === '' ? null : (int)$_GET['parent_id'];
    }
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    
    $result = $categoryModel->getAll($page, $limit, $filters);
    
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

function getCategoryTree($categoryModel) {
    $tree = $categoryModel->getTree();
    
    echo json_encode([
        'success' => true,
        'data' => $tree
    ]);
}

function getCategory($categoryModel, $data) {
    $id = $data['id'] ?? $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Category ID required']);
        return;
    }
    
    $category = $categoryModel->findById((int)$id);
    
    if (!$category) {
        echo json_encode(['success' => false, 'message' => 'Danh mục không tồn tại']);
        return;
    }
    
    // Get children
    $category['children'] = $categoryModel->getChildren($category['id']);
    
    echo json_encode([
        'success' => true,
        'data' => $category
    ]);
}

function createCategory($categoryModel, $data) {
    // Validate required fields
    if (empty($data['name'])) {
        echo json_encode(['success' => false, 'message' => 'Tên danh mục là bắt buộc']);
        return;
    }
    
    // Check if slug exists
    $slug = generateSlug($data['name']);
    if ($categoryModel->findBySlug($slug)) {
        echo json_encode(['success' => false, 'message' => 'Danh mục với tên này đã tồn tại']);
        return;
    }
    
    $categoryData = [
        'name' => $data['name'],
        'slug' => $slug,
        'parent_id' => !empty($data['parent_id']) ? (int)$data['parent_id'] : null,
        'description' => $data['description'] ?? '',
        'icon' => $data['icon'] ?? '',
        'image' => $data['image'] ?? '',
        'sort_order' => (int)($data['sort_order'] ?? 0),
        'status' => $data['status'] ?? 'active',
        'meta_title' => $data['meta_title'] ?? '',
        'meta_description' => $data['meta_description'] ?? ''
    ];
    
    $categoryId = $categoryModel->create($categoryData);
    
    if ($categoryId) {
        echo json_encode([
            'success' => true,
            'message' => 'Tạo danh mục thành công',
            'category_id' => $categoryId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể tạo danh mục']);
    }
}

function updateCategory($categoryModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Category ID required']);
        return;
    }
    
    $category = $categoryModel->findById((int)$data['id']);
    
    if (!$category) {
        echo json_encode(['success' => false, 'message' => 'Danh mục không tồn tại']);
        return;
    }
    
    $updateData = [];
    $allowedFields = ['name', 'parent_id', 'description', 'icon', 'image', 'sort_order', 'status', 'meta_title', 'meta_description'];
    
    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }
    
    // Update slug if name changed
    if (isset($data['name']) && $data['name'] !== $category['name']) {
        $newSlug = generateSlug($data['name']);
        $existingSlug = $categoryModel->findBySlug($newSlug);
        if ($existingSlug && $existingSlug['id'] !== $category['id']) {
            echo json_encode(['success' => false, 'message' => 'Danh mục với tên này đã tồn tại']);
            return;
        }
        $updateData['slug'] = $newSlug;
    }
    
    // Prevent setting parent to self or children
    if (!empty($data['parent_id'])) {
        if ((int)$data['parent_id'] === $category['id']) {
            echo json_encode(['success' => false, 'message' => 'Danh mục không thể là danh mục cha của chính nó']);
            return;
        }
        
        $children = $categoryModel->getAllChildren($category['id']);
        if (in_array((int)$data['parent_id'], array_column($children, 'id'))) {
            echo json_encode(['success' => false, 'message' => 'Không thể chọn danh mục con làm danh mục cha']);
            return;
        }
    }
    
    if (!empty($updateData)) {
        $categoryModel->update($category['id'], $updateData);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật danh mục thành công'
    ]);
}

function deleteCategory($categoryModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Category ID required']);
        return;
    }
    
    $category = $categoryModel->findById((int)$data['id']);
    
    if (!$category) {
        echo json_encode(['success' => false, 'message' => 'Danh mục không tồn tại']);
        return;
    }
    
    // Check if category has children
    $children = $categoryModel->getChildren($category['id']);
    if (!empty($children)) {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa danh mục có danh mục con']);
        return;
    }
    
    // Check if category has products
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $stmt->execute([$category['id']]);
    $productCount = $stmt->fetchColumn();
    
    if ($productCount > 0) {
        echo json_encode(['success' => false, 'message' => "Không thể xóa danh mục có $productCount sản phẩm"]);
        return;
    }
    
    $categoryModel->delete($category['id']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Xóa danh mục thành công'
    ]);
}

function updateStatus($categoryModel, $data) {
    if (empty($data['id']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Category ID and status required']);
        return;
    }
    
    $validStatuses = ['active', 'inactive'];
    if (!in_array($data['status'], $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
        return;
    }
    
    $categoryModel->update((int)$data['id'], ['status' => $data['status']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật trạng thái thành công'
    ]);
}

function reorderCategories($categoryModel, $data) {
    if (empty($data['orders']) || !is_array($data['orders'])) {
        echo json_encode(['success' => false, 'message' => 'Orders data required']);
        return;
    }
    
    $db = getDB();
    
    foreach ($data['orders'] as $item) {
        if (isset($item['id']) && isset($item['sort_order'])) {
            $stmt = $db->prepare("UPDATE categories SET sort_order = ? WHERE id = ?");
            $stmt->execute([(int)$item['sort_order'], (int)$item['id']]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật thứ tự thành công'
    ]);
}
?>
