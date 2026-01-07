<?php
/**
 * Product Model - MySQL Version
 */

require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;
    private $table = 'products';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new product
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, slug, description, short_description, price, sale_price, 
                category_id, brand, sku, stock, featured, status) 
                VALUES (:name, :slug, :description, :short_description, :price, :sale_price, 
                :category_id, :brand, :sku, :stock, :featured, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'] ?? generateSlug($data['name']),
            ':description' => $data['description'] ?? null,
            ':short_description' => $data['short_description'] ?? null,
            ':price' => $data['price'],
            ':sale_price' => $data['sale_price'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':brand' => $data['brand'] ?? null,
            ':sku' => $data['sku'] ?? null,
            ':stock' => $data['stock'] ?? 0,
            ':featured' => $data['featured'] ?? 0,
            ':status' => $data['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Find product by ID
     */
    public function findById($id) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();
        
        if ($product) {
            $product['images'] = $this->getImages($id);
            $product['specifications'] = $this->getSpecifications($id);
        }
        
        return $product;
    }
    
    /**
     * Find product by slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $product = $stmt->fetch();
        
        if ($product) {
            $product['images'] = $this->getImages($product['id']);
            $product['specifications'] = $this->getSpecifications($product['id']);
        }
        
        return $product;
    }
    
    /**
     * Update product
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'images' && $key !== 'specifications') {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete product
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get all products with pagination
     */
    public function getAll($page = 1, $limit = 12, $filters = []) {
        // Ensure valid values
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        $orderBy = 'p.created_at DESC';
        
        if (!empty($filters['category_id'])) {
            $where[] = "p.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        
        if (!empty($filters['brand'])) {
            $where[] = "p.brand = :brand";
            $params[':brand'] = $filters['brand'];
        }
        
        if (!empty($filters['status'])) {
            $where[] = "p.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (isset($filters['featured'])) {
            $where[] = "p.featured = :featured";
            $params[':featured'] = $filters['featured'];
        }
        
        if (!empty($filters['min_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(p.name LIKE :search OR p.description LIKE :search OR p.brand LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $orderBy = 'COALESCE(p.sale_price, p.price) ASC';
                    break;
                case 'price_desc':
                    $orderBy = 'COALESCE(p.sale_price, p.price) DESC';
                    break;
                case 'name_asc':
                    $orderBy = 'p.name ASC';
                    break;
                case 'name_desc':
                    $orderBy = 'p.name DESC';
                    break;
                case 'newest':
                    $orderBy = 'p.created_at DESC';
                    break;
                case 'bestselling':
                    $orderBy = 'p.sold_count DESC';
                    break;
                case 'rating':
                    $orderBy = 'p.rating DESC';
                    break;
            }
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} p {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Get products
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                {$whereClause} 
                ORDER BY {$orderBy} 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ];
    }
    
    /**
     * Get featured products
     */
    public function getFeatured($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.featured = 1 AND p.status = 'active' 
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get bestselling products
     */
    public function getBestselling($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' 
                ORDER BY p.sold_count DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get related products
     */
    public function getRelated($productId, $categoryId, $limit = 4) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id AND p.id != :product_id AND p.status = 'active' 
                ORDER BY RAND() 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get product images
     */
    public function getImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Add product image
     */
    public function addImage($productId, $imageUrl, $isPrimary = 0) {
        if ($isPrimary) {
            $this->db->prepare("UPDATE product_images SET is_primary = 0 WHERE product_id = :product_id")
                     ->execute([':product_id' => $productId]);
        }
        
        $sql = "INSERT INTO product_images (product_id, image_url, is_primary) VALUES (:product_id, :image_url, :is_primary)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':product_id' => $productId,
            ':image_url' => $imageUrl,
            ':is_primary' => $isPrimary
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Delete product image
     */
    public function deleteImage($imageId) {
        $sql = "DELETE FROM product_images WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $imageId]);
    }
    
    /**
     * Delete all images for a product
     */
    public function deleteImages($productId) {
        $sql = "DELETE FROM product_images WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':product_id' => $productId]);
    }
    
    /**
     * Get product specifications
     */
    public function getSpecifications($productId) {
        $sql = "SELECT * FROM product_specifications WHERE product_id = :product_id ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Add specification
     */
    public function addSpecification($productId, $name, $value, $sortOrder = 0) {
        $sql = "INSERT INTO product_specifications (product_id, spec_name, spec_value, sort_order) VALUES (:product_id, :spec_name, :spec_value, :sort_order)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':product_id' => $productId,
            ':spec_name' => $name,
            ':spec_value' => $value,
            ':sort_order' => $sortOrder
        ]);
    }
    
    /**
     * Delete all specifications for a product
     */
    public function deleteSpecifications($productId) {
        $sql = "DELETE FROM product_specifications WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':product_id' => $productId]);
    }
    
    /**
     * Find product by SKU
     */
    public function findBySku($sku) {
        $sql = "SELECT * FROM {$this->table} WHERE sku = :sku LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sku' => $sku]);
        return $stmt->fetch();
    }
    
    /**
     * Set product specifications
     */
    public function setSpecifications($productId, $specifications) {
        // Delete existing specifications
        $this->db->prepare("DELETE FROM product_specifications WHERE product_id = :product_id")
                 ->execute([':product_id' => $productId]);
        
        // Insert new specifications
        $sql = "INSERT INTO product_specifications (product_id, spec_name, spec_value, sort_order) VALUES (:product_id, :spec_name, :spec_value, :sort_order)";
        $stmt = $this->db->prepare($sql);
        
        $order = 0;
        foreach ($specifications as $name => $value) {
            $stmt->execute([
                ':product_id' => $productId,
                ':spec_name' => $name,
                ':spec_value' => $value,
                ':sort_order' => $order++
            ]);
        }
    }
    
    /**
     * Update stock (decrease)
     */
    public function updateStock($productId, $quantity) {
        $sql = "UPDATE {$this->table} SET stock = stock - :quantity, sold_count = sold_count + :sold WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $productId, ':quantity' => $quantity, ':sold' => $quantity]);
    }
    
    /**
     * Restore stock (increase when order cancelled)
     */
    public function restoreStock($productId, $quantity) {
        $sql = "UPDATE {$this->table} SET stock = stock + :quantity, sold_count = sold_count - :sold WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $productId, ':quantity' => $quantity, ':sold' => $quantity]);
    }
    
    /**
     * Increment views
     */
    public function incrementViews($productId) {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $productId]);
    }
    
    /**
     * Increment sold count
     */
    public function incrementSoldCount($productId, $quantity = 1) {
        $sql = "UPDATE {$this->table} SET sold_count = sold_count + :quantity WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $productId, ':quantity' => $quantity]);
    }
    
    /**
     * Update rating
     */
    public function updateRating($productId) {
        $sql = "UPDATE {$this->table} p SET 
                rating = (SELECT AVG(rating) FROM reviews WHERE product_id = :id AND status = 'approved'),
                review_count = (SELECT COUNT(*) FROM reviews WHERE product_id = :id AND status = 'approved')
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $productId]);
    }
    
    /**
     * Get all brands
     */
    public function getBrands() {
        $sql = "SELECT DISTINCT brand FROM {$this->table} WHERE brand IS NOT NULL ORDER BY brand ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Count products
     */
    public function count($status = null) {
        if ($status) {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = :status";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':status' => $status]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM {$this->table}";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetch()['count'];
    }
    
    /**
     * Search products
     */
    public function search($keyword, $limit = 10) {
        $sql = "SELECT p.id, p.name, p.slug, p.price, p.sale_price,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                WHERE p.status = 'active' AND (p.name LIKE :keyword OR p.brand LIKE :keyword OR p.sku LIKE :keyword)
                ORDER BY p.name ASC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get new products
     */
    public function getNew($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' 
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get on sale products
     */
    public function getOnSale($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' AND p.sale_price IS NOT NULL AND p.sale_price < p.price
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get filtered products
     */
    public function getFiltered($filters, $sort = 'newest', $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        $where = ["p.status = 'active'"];
        $params = [];
        
        if (!empty($filters['category'])) {
            $where[] = "c.slug = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['brand'])) {
            $where[] = "p.brand = :brand";
            $params[':brand'] = $filters['brand'];
        }
        
        if (!empty($filters['price_min'])) {
            $where[] = "COALESCE(p.sale_price, p.price) >= :price_min";
            $params[':price_min'] = $filters['price_min'];
        }
        
        if (!empty($filters['price_max'])) {
            $where[] = "COALESCE(p.sale_price, p.price) <= :price_max";
            $params[':price_max'] = $filters['price_max'];
        }
        
        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $where[] = "(p.name LIKE :search1 OR p.description LIKE :search2 OR p.brand LIKE :search3)";
            $params[':search1'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }
        
        // Sort order
        switch($sort) {
            case 'price_asc':
                $orderBy = 'COALESCE(p.sale_price, p.price) ASC';
                break;
            case 'price_desc':
                $orderBy = 'COALESCE(p.sale_price, p.price) DESC';
                break;
            case 'name_asc':
                $orderBy = 'p.name ASC';
                break;
            case 'name_desc':
                $orderBy = 'p.name DESC';
                break;
            case 'bestselling':
                $orderBy = 'p.sold_count DESC';
                break;
            default:
                $orderBy = 'p.created_at DESC';
                break;
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $where);
        
        // Count total
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} p 
                     LEFT JOIN categories c ON p.category_id = c.id {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];
        
        // Get products
        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                {$whereClause}
                ORDER BY {$orderBy}
                LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return [
            'products' => $stmt->fetchAll(),
            'total' => $total
        ];
    }
    
    /**
     * Get all brands
     */
    public function getAllBrands() {
        return $this->getBrands();
    }
    
    /**
     * Get product by slug (alias)
     */
    public function getBySlug($slug) {
        return $this->findBySlug($slug);
    }
    
    /**
     * Get product by ID (alias)
     */
    public function getById($id) {
        return $this->findById($id);
    }
    
    /**
     * Get top selling products
     */
    public function getTopSelling($limit = 5) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' 
                ORDER BY p.sold_count DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get low stock products
     */
    public function getLowStock($limit = 5, $threshold = 10) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' AND p.stock <= :threshold
                ORDER BY p.stock ASC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get all products for admin
     */
    public function getAllAdmin($search = null, $category = null, $status = null, $page = 1, $limit = 20) {
        return $this->getAll($page, $limit, [
            'search' => $search,
            'category_id' => $category,
            'status' => $status
        ]);
    }
    
    /**
     * Get product statistics
     */
    public function getStatistics() {
        // Total products
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $total = $stmt->fetch()['total'];
        
        // Active products
        $sql = "SELECT COUNT(*) as active FROM {$this->table} WHERE status = 'active'";
        $stmt = $this->db->query($sql);
        $active = $stmt->fetch()['active'];
        
        // Inactive products
        $sql = "SELECT COUNT(*) as inactive FROM {$this->table} WHERE status = 'inactive'";
        $stmt = $this->db->query($sql);
        $inactive = $stmt->fetch()['inactive'];
        
        // Out of stock
        $sql = "SELECT COUNT(*) as out_of_stock FROM {$this->table} WHERE stock = 0";
        $stmt = $this->db->query($sql);
        $outOfStock = $stmt->fetch()['out_of_stock'];
        
        // Low stock (<=10)
        $sql = "SELECT COUNT(*) as low_stock FROM {$this->table} WHERE stock > 0 AND stock <= 10";
        $stmt = $this->db->query($sql);
        $lowStock = $stmt->fetch()['low_stock'];
        
        // Featured products
        $sql = "SELECT COUNT(*) as featured FROM {$this->table} WHERE featured = 1";
        $stmt = $this->db->query($sql);
        $featured = $stmt->fetch()['featured'];
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'featured' => $featured
        ];
    }
}
