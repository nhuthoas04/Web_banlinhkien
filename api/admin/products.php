<?php
/**
 * Admin Products API - MySQL Version
 */

// Clean output buffer
ob_start();

// Enable error logging to file instead of output
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear any previous output
ob_clean();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';

// Check admin access - kiểm tra nhiều cách lưu session
$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? '';

if (!$userId || !in_array($userRole, ['admin', 'employee'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

// Support both JSON and FormData input
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    // FormData - merge POST and GET
    $input = $_POST;
}

$action = $input['action'] ?? $_GET['action'] ?? $_POST['action'] ?? '';

$productModel = new Product();

switch ($action) {
    case 'list':
        listProducts($productModel);
        break;
    case 'detail':
        getProduct($productModel, $input);
        break;
    case 'create':
        createProduct($productModel, $input);
        break;
    case 'update':
        updateProduct($productModel, $input);
        break;
    case 'delete':
        deleteProduct($productModel, $input);
        break;
    case 'update-status':
    case 'update_status':
        updateStatus($productModel, $input);
        break;
    case 'update-featured':
    case 'update_featured':
        updateFeatured($productModel, $input);
        break;
    case 'bulk-delete':
    case 'bulk_delete':
        bulkDelete($productModel, $input);
        break;
    case 'bulk-status':
    case 'bulk_status':
        bulkStatus($productModel, $input);
        break;
    case 'bulk_activate':
    case 'bulk-activate':
        // Gọi bulkStatus với status = active
        $input['status'] = 'active';
        bulkStatus($productModel, $input);
        break;
    case 'bulk_deactivate':
    case 'bulk-deactivate':
        // Gọi bulkStatus với status = inactive
        $input['status'] = 'inactive';
        bulkStatus($productModel, $input);
        break;
    case 'upload-image':
        uploadProductImage();
        break;
    case 'delete-image':
        deleteProductImage($input);
        break;
    case 'add_brand':
        addBrand($input);
        break;
    case 'stats':
        getStats($productModel);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function listProducts($productModel) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 20);
    
    $filters = [];
    
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    if (!empty($_GET['category_id'])) {
        $filters['category_id'] = (int)$_GET['category_id'];
    }
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    if (!empty($_GET['brand'])) {
        $filters['brand'] = $_GET['brand'];
    }
    if (isset($_GET['featured'])) {
        $filters['featured'] = (int)$_GET['featured'];
    }
    if (!empty($_GET['sort'])) {
        $filters['sort'] = $_GET['sort'];
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

function getProduct($productModel, $data) {
    $id = $data['id'] ?? $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $product = $productModel->findById((int)$id);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        return;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $product
    ]);
}

function createProduct($productModel, $data) {
    // Validate required fields
    $required = ['name', 'category_id', 'price'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Trường $field là bắt buộc"]);
            return;
        }
    }
    
    // Check SKU uniqueness
    if (!empty($data['sku'])) {
        $existing = $productModel->findBySku($data['sku']);
        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'Mã SKU đã tồn tại']);
            return;
        }
    }
    
    $productData = [
        'name' => $data['name'],
        'slug' => generateSlug($data['name']),
        'sku' => $data['sku'] ?? '',
        'category_id' => (int)$data['category_id'],
        'brand' => $data['brand'] ?? '',
        'brand_id' => !empty($data['brand_id']) ? (int)$data['brand_id'] : null,
        'price' => (int)$data['price'],
        'sale_price' => !empty($data['sale_price']) ? (int)$data['sale_price'] : null,
        'stock' => (int)($data['stock'] ?? 0),
        'description' => $data['description'] ?? '',
        'short_description' => $data['short_description'] ?? '',
        'specifications' => $data['specifications'] ?? '',
        'status' => $data['status'] ?? 'active',
        'featured' => !empty($data['featured']) ? 1 : 0
    ];
    
    $productId = $productModel->create($productData);
    
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'Không thể tạo sản phẩm']);
        return;
    }
    
    // Add images from direct URLs (image_urls[])
    $imageUrls = $data['image_urls'] ?? [];
    if (!empty($imageUrls) && is_array($imageUrls)) {
        foreach ($imageUrls as $index => $imageUrl) {
            if (!empty($imageUrl)) {
                $productModel->addImage($productId, $imageUrl, $index);
            }
        }
    }
    
    // Add images from array (for JSON requests)
    if (!empty($data['images']) && is_array($data['images'])) {
        $startIndex = count($imageUrls);
        foreach ($data['images'] as $index => $imageUrl) {
            $productModel->addImage($productId, $imageUrl, $startIndex + $index);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Tạo sản phẩm thành công',
        'product_id' => $productId
    ]);
}

function updateProduct($productModel, $data) {
    // Debug log
    error_log("updateProduct called with data: " . print_r($data, true));
    
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $product = $productModel->findById((int)$data['id']);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        return;
    }
    
    // Check SKU uniqueness if changed
    if (!empty($data['sku']) && $data['sku'] !== $product['sku']) {
        $existing = $productModel->findBySku($data['sku']);
        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'Mã SKU đã tồn tại']);
            return;
        }
    }
    
    $updateData = [];
    $allowedFields = ['name', 'sku', 'category_id', 'brand', 'brand_id', 'price', 'sale_price', 
                      'stock', 'description', 'short_description', 'specifications', 'status'];
    
    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }
    
    // Handle brand_id - convert empty to null
    if (isset($updateData['brand_id']) && $updateData['brand_id'] === '') {
        $updateData['brand_id'] = null;
    }
    
    // Handle featured checkbox - if not set, it means unchecked = 0
    $updateData['featured'] = !empty($data['featured']) ? 1 : 0;
    
    // Handle sale_price - convert empty to null
    if (isset($updateData['sale_price']) && $updateData['sale_price'] === '') {
        $updateData['sale_price'] = null;
    }
    
    // Update slug if name changed
    if (isset($data['name']) && $data['name'] !== $product['name']) {
        $updateData['slug'] = generateSlug($data['name']);
    }
    
    // Debug log
    error_log("updateData to save: " . print_r($updateData, true));
    
    if (!empty($updateData)) {
        try {
            $result = $productModel->update($product['id'], $updateData);
            error_log("Update result: " . ($result ? 'true' : 'false'));
            if (!$result) {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật sản phẩm']);
                return;
            }
        } catch (Exception $e) {
            error_log("Update error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
            return;
        }
    }
    
    // Update images - handle existing_images[], image_urls[], and images[]
    $existingImages = $data['existing_images'] ?? [];
    $imageUrls = $data['image_urls'] ?? [];
    $images = $data['images'] ?? [];
    
    // Collect all images to save
    $allImages = [];
    
    // Add existing images that weren't removed
    if (is_array($existingImages)) {
        foreach ($existingImages as $img) {
            if (!empty($img)) {
                $allImages[] = $img;
            }
        }
    }
    
    // Add new image URLs
    if (is_array($imageUrls)) {
        foreach ($imageUrls as $img) {
            if (!empty($img)) {
                $allImages[] = $img;
            }
        }
    }
    
    // Add images from JSON array
    if (is_array($images)) {
        foreach ($images as $img) {
            if (!empty($img)) {
                $allImages[] = $img;
            }
        }
    }
    
    // Update images in database
    if (!empty($allImages) || isset($data['existing_images']) || isset($data['image_urls'])) {
        $productModel->deleteImages($product['id']);
        foreach ($allImages as $index => $imageUrl) {
            $productModel->addImage($product['id'], $imageUrl, $index);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật sản phẩm thành công'
    ]);
}

function deleteProduct($productModel, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $product = $productModel->findById((int)$data['id']);
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        return;
    }
    
    $productModel->delete($product['id']);
    
    echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
}

function updateStatus($productModel, $data) {
    $productId = $data['id'] ?? $data['product_id'] ?? null;
    if (empty($productId) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID and status required']);
        return;
    }
    
    $validStatuses = ['active', 'inactive', 'out_of_stock'];
    if (!in_array($data['status'], $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
        return;
    }
    
    $productModel->update((int)$productId, ['status' => $data['status']]);
    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
}

function updateFeatured($productModel, $data) {
    $productId = $data['id'] ?? $data['product_id'] ?? null;
    if (empty($productId) || !isset($data['featured'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID and featured status required']);
        return;
    }
    
    $productModel->update((int)$productId, ['featured' => $data['featured'] ? 1 : 0]);
    echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);
}

function bulkDelete($productModel, $data) {
    if (empty($data['ids']) || !is_array($data['ids'])) {
        echo json_encode(['success' => false, 'message' => 'Product IDs required']);
        return;
    }
    
    $deleted = 0;
    foreach ($data['ids'] as $id) {
        $productModel->delete((int)$id);
        $deleted++;
    }
    
    echo json_encode(['success' => true, 'message' => "Đã xóa $deleted sản phẩm"]);
}

function bulkStatus($productModel, $data) {
    if (empty($data['ids']) || !is_array($data['ids']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Product IDs and status required']);
        return;
    }
    
    $updated = 0;
    foreach ($data['ids'] as $id) {
        $productModel->update((int)$id, ['status' => $data['status']]);
        $updated++;
    }
    
    echo json_encode(['success' => true, 'message' => "Đã cập nhật $updated sản phẩm"]);
}

function uploadProductImage() {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn file ảnh']);
        return;
    }
    
    $file = $_FILES['image'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024;
    
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file ảnh']);
        return;
    }
    
    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'File ảnh không được lớn hơn 5MB']);
        return;
    }
    
    $uploadDir = __DIR__ . '/../../uploads/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_') . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        echo json_encode(['success' => true, 'url' => 'uploads/products/' . $filename]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể upload ảnh']);
    }
}

function deleteProductImage($data) {
    if (empty($data['url'])) {
        echo json_encode(['success' => false, 'message' => 'Image URL required']);
        return;
    }
    
    $filepath = __DIR__ . '/../../' . $data['url'];
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    
    echo json_encode(['success' => true, 'message' => 'Đã xóa ảnh']);
}

function addBrand($data) {
    require_once __DIR__ . '/../../models/Brand.php';
    
    $name = trim($data['name'] ?? '');
    $logo = trim($data['logo'] ?? '');
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Tên thương hiệu là bắt buộc']);
        return;
    }
    
    $brandModel = new Brand();
    $brandModel->ensureTable();
    
    // Check if brand already exists
    $existing = $brandModel->findByName($name);
    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'Thương hiệu đã tồn tại']);
        return;
    }
    
    $brandId = $brandModel->create([
        'name' => $name,
        'logo' => $logo ?: null
    ]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Đã thêm thương hiệu', 
        'brand_id' => $brandId
    ]);
}

function getStats($productModel) {
    $stats = $productModel->getStatistics();
    echo json_encode(['success' => true, 'data' => $stats]);
}
?>
