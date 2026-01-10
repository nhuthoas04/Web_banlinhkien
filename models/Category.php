<?php
/**
 * Category Model - MySQL Version
 */

require_once __DIR__ . '/../config/database.php';

class Category {
    private $db;
    private $table = 'categories';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new category
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, slug, description, image, parent_id, sort_order, status) 
                VALUES (:name, :slug, :description, :image, :parent_id, :sort_order, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'] ?? generateSlug($data['name']),
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
            ':parent_id' => $data['parent_id'] ?? null,
            ':sort_order' => $data['sort_order'] ?? 0,
            ':status' => $data['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Find category by ID
     */
    public function findById($id) {
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c 
                LEFT JOIN {$this->table} p ON c.parent_id = p.id 
                WHERE c.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Find category by slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c 
                LEFT JOIN {$this->table} p ON c.parent_id = p.id 
                WHERE c.slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }
    
    /**
     * Update category
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete category
     */
    public function delete($id) {
        // Set parent_id to NULL for children
        $this->db->prepare("UPDATE {$this->table} SET parent_id = NULL WHERE parent_id = :id")
                 ->execute([':id' => $id]);
        
        // Set category_id to NULL for products
        $this->db->prepare("UPDATE products SET category_id = NULL WHERE category_id = :id")
                 ->execute([':id' => $id]);
        
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get all categories
     */
    public function getAll($status = null) {
        $sql = "SELECT c.*, p.name as parent_name,
                (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count
                FROM {$this->table} c 
                LEFT JOIN {$this->table} p ON c.parent_id = p.id";
        
        if ($status) {
            $sql .= " WHERE c.status = :status";
        }
        
        $sql .= " ORDER BY c.sort_order ASC, c.name ASC";
        
        $stmt = $this->db->prepare($sql);
        if ($status) {
            $stmt->execute([':status' => $status]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get parent categories (categories without parent)
     */
    public function getParentCategories() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE parent_id IS NULL AND status = 'active' 
                ORDER BY sort_order ASC, name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get child categories
     */
    public function getChildren($parentId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE parent_id = :parent_id AND status = 'active' 
                ORDER BY sort_order ASC, name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':parent_id' => $parentId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get category tree
     */
    public function getCategoryTree() {
        $categories = $this->getAll('active');
        return $this->buildTree($categories);
    }
    
    /**
     * Build tree from flat array
     */
    private function buildTree($categories, $parentId = null) {
        $tree = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $category['children'] = $this->buildTree($categories, $category['id']);
                $tree[] = $category;
            }
        }
        return $tree;
    }
    
    /**
     * Update sort order
     */
    public function updateOrder($id, $order) {
        $sql = "UPDATE {$this->table} SET sort_order = :sort_order WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':sort_order' => $order]);
    }
    
    /**
     * Count categories
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
     * Get categories for dropdown
     */
    public function getForDropdown() {
        $sql = "SELECT id, name, parent_id FROM {$this->table} WHERE status = 'active' ORDER BY sort_order ASC, name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all categories with product count
     */
    public function getAllWithProductCount() {
        $sql = "SELECT c.*, p.name as parent_name,
                (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count
                FROM {$this->table} c 
                LEFT JOIN {$this->table} p ON c.parent_id = p.id
                ORDER BY c.sort_order ASC, c.name ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get category by slug (alias)
     */
    public function getBySlug($slug) {
        return $this->findBySlug($slug);
    }
    
    /**
     * Get category by ID (alias)
     */
    public function getById($id) {
        return $this->findById($id);
    }
}
?>
