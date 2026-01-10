<?php
/**
 * Products API - MySQL Version
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$productModel = new Product();

switch ($action) {
    case 'list':
        listProducts($productModel, $input);
        break;
    case 'search':
        searchProducts($productModel, $input);
        break;
    case 'detail':
        getProductDetail($productModel, $input);
        break;
    case 'quick-view':
        quickView($productModel, $input);
        break;
    case 'related':
        getRelatedProducts($productModel, $input);
        break;
    case 'featured':
        getFeaturedProducts($productModel);
        break;
    case 'bestselling':
        getBestsellingProducts($productModel);
        break;
    case 'brands':
        getBrands($productModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listProducts($productModel, $data) {
    $page = (int)($data['page'] ?? 1);
    $limit = (int)($data['limit'] ?? 12);
    
    $filters = [
        'status' => 'active'
    ];
    
    if (!empty($data['category_id'])) {
        $filters['category_id'] = (int)$data['category_id'];
    }
    if (!empty($data['brand'])) {
        $filters['brand'] = $data['brand'];
    }
    if (!empty($data['min_price'])) {
        $filters['min_price'] = (int)$data['min_price'];
    }
    if (!empty($data['max_price'])) {
        $filters['max_price'] = (int)$data['max_price'];
    }
    if (!empty($data['search'])) {
        $filters['search'] = $data['search'];
    }
    if (!empty($data['sort'])) {
        $filters['sort'] = $data['sort'];
    }
    
    $result = $productModel->getAll($page, $limit, $filters);
    
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

function searchProducts($productModel, $data) {
    $keyword = $data['keyword'] ?? $data['q'] ?? '';
    $limit = (int)($data['limit'] ?? 10);
    
    if (strlen($keyword) < 2) {
        echo json_encode(['success' => true, 'data' => []]);
        return;
    }
    
    $products = $productModel->search($keyword, $limit);
    
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
}

function getProductDetail($productModel, $data) {
    if (empty($data['id']) && empty($data['slug'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID or slug required']);
        return;
    }
    
    if (!empty($data['slug'])) {
        $product = $productModel->findBySlug($data['slug']);
    } else {
        $product = $productModel->findById((int)$data['id']);
    }
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        return;
    }
    
    // Increment views
    $productModel->incrementViews($product['id']);
    
    echo json_encode([
        'success' => true,
        'data' => $product
    ]);
}

function quickView($productModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $product = $productModel->findById((int)$data['id']);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        return;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $product
    ]);
}

function getRelatedProducts($productModel, $data) {
    if (empty($data['product_id']) || empty($data['category_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID and Category ID required']);
        return;
    }
    
    $limit = (int)($data['limit'] ?? 4);
    $products = $productModel->getRelated((int)$data['product_id'], (int)$data['category_id'], $limit);
    
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
}

function getFeaturedProducts($productModel) {
    $limit = (int)($_GET['limit'] ?? 8);
    $products = $productModel->getFeatured($limit);
    
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
}

function getBestsellingProducts($productModel) {
    $limit = (int)($_GET['limit'] ?? 8);
    $products = $productModel->getBestselling($limit);
    
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
}

function getBrands($productModel) {
    $brands = $productModel->getBrands();
    
    echo json_encode([
        'success' => true,
        'data' => $brands
    ]);
}
?>
