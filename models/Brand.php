<?php
/**
 * Brand Model
 */

require_once __DIR__ . '/../config/database.php';

class Brand {
    private $db;
    private $table = 'brands';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new brand
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, slug, logo, description, status, sort_order) 
                VALUES (:name, :slug, :logo, :description, :status, :sort_order)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'] ?? $this->generateSlug($data['name']),
            ':logo' => $data['logo'] ?? null,
            ':description' => $data['description'] ?? null,
            ':status' => $data['status'] ?? 'active',
            ':sort_order' => $data['sort_order'] ?? 0
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Find brand by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Find brand by slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }
    
    /**
     * Find brand by name
     */
    public function findByName($name) {
        $sql = "SELECT * FROM {$this->table} WHERE name = :name LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name]);
        return $stmt->fetch();
    }
    
    /**
     * Update brand
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete brand
     */
    public function delete($id) {
        // Update products to remove brand reference
        $this->db->prepare("UPDATE products SET brand_id = NULL WHERE brand_id = :id")
                 ->execute([':id' => $id]);
        
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get all brands
     */
    public function getAll($status = null) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY sort_order ASC, name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get active brands only
     */
    public function getActive() {
        return $this->getAll('active');
    }
    
    /**
     * Get brands with product count
     */
    public function getBrandsWithCount() {
        $sql = "SELECT b.*, COUNT(p.id) as product_count 
                FROM {$this->table} b 
                LEFT JOIN products p ON p.brand_id = b.id AND p.status = 'active'
                WHERE b.status = 'active'
                GROUP BY b.id 
                ORDER BY b.sort_order ASC, b.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Count all brands
     */
    public function count($status = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Generate slug
     */
    private function generateSlug($string) {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    
    /**
     * Ensure brands table exists
     */
    public function ensureTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) UNIQUE,
            logo VARCHAR(255),
            description TEXT,
            status ENUM('active', 'inactive') DEFAULT 'active',
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_status (status),
            INDEX idx_slug (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
        
        // Add brand_id column to products if not exists
        try {
            $this->db->exec("ALTER TABLE products ADD COLUMN brand_id INT NULL AFTER brand");
            $this->db->exec("ALTER TABLE products ADD INDEX idx_brand_id (brand_id)");
            $this->db->exec("ALTER TABLE products ADD FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL");
        } catch (Exception $e) {
            // Column might already exist
        }
        
        return true;
    }
    
    /**
     * Migrate existing brands from products table
     */
    public function migrateFromProducts() {
        // Get unique brands from products
        $sql = "SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != ''";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $existingBrands = $stmt->fetchAll();
        
        foreach ($existingBrands as $row) {
            $brandName = $row['brand'];
            
            // Check if brand already exists
            $existing = $this->findByName($brandName);
            if (!$existing) {
                $brandId = $this->create(['name' => $brandName]);
            } else {
                $brandId = $existing['id'];
            }
            
            // Update products with brand_id
            $this->db->prepare("UPDATE products SET brand_id = :brand_id WHERE brand = :brand_name")
                     ->execute([':brand_id' => $brandId, ':brand_name' => $brandName]);
        }
        
        return true;
    }
}
