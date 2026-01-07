<?php
/**
 * Review Model - MySQL Version
 */

require_once __DIR__ . '/../config/database.php';

class Review {
    private $db;
    private $table = 'reviews';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new review
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (product_id, user_id, order_id, rating, title, content, pros, cons, status) 
                VALUES (:product_id, :user_id, :order_id, :rating, :title, :content, :pros, :cons, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':product_id' => $data['product_id'],
            ':user_id' => $data['user_id'],
            ':order_id' => $data['order_id'] ?? null,
            ':rating' => $data['rating'],
            ':title' => $data['title'] ?? null,
            ':content' => $data['content'] ?? null,
            ':pros' => $data['pros'] ?? null,
            ':cons' => $data['cons'] ?? null,
            ':status' => $data['status'] ?? 'pending'
        ]);
        
        $reviewId = $this->db->lastInsertId();
        
        // Add images if provided
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $this->addImage($reviewId, $image);
            }
        }
        
        return $reviewId;
    }
    
    /**
     * Find review by user and product
     */
    public function findByUserAndProduct($userId, $productId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id AND product_id = :product_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
        return $stmt->fetch();
    }
    
    /**
     * Find review by ID
     */
    public function findById($id) {
        $sql = "SELECT r.*, u.name as user_name, u.avatar as user_avatar, p.name as product_name, p.slug as product_slug
                FROM {$this->table} r 
                LEFT JOIN users u ON r.user_id = u.id 
                LEFT JOIN products p ON r.product_id = p.id 
                WHERE r.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $review = $stmt->fetch();
        
        if ($review) {
            $review['images'] = $this->getImages($id);
        }
        
        return $review;
    }
    
    /**
     * Update review
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'images') {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete review
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get review images
     */
    public function getImages($reviewId) {
        $sql = "SELECT * FROM review_images WHERE review_id = :review_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':review_id' => $reviewId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Add review image
     */
    public function addImage($reviewId, $imageUrl) {
        $sql = "INSERT INTO review_images (review_id, image_url) VALUES (:review_id, :image_url)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':review_id' => $reviewId, ':image_url' => $imageUrl]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Get reviews by product
     */
    public function getByProduct($productId, $page = 1, $limit = 10, $status = 'approved') {
        // Ensure valid values
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE product_id = :product_id AND status = :status";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute([':product_id' => $productId, ':status' => $status]);
        $total = $stmt->fetch()['total'];
        
        // Get reviews
        $sql = "SELECT r.*, u.name as user_name, u.avatar as user_avatar
                FROM {$this->table} r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = :product_id AND r.status = :status
                ORDER BY r.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $reviews = $stmt->fetchAll();
        
        // Get images for each review
        foreach ($reviews as &$review) {
            $review['images'] = $this->getImages($review['id']);
        }
        
        return [
            'data' => $reviews,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ];
    }
    
    /**
     * Get all reviews with pagination
     */
    public function getAll($page = 1, $limit = 10, $filters = []) {
        // Ensure valid values
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $where[] = "r.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['product_id'])) {
            $where[] = "r.product_id = :product_id";
            $params[':product_id'] = $filters['product_id'];
        }
        
        if (!empty($filters['user_id'])) {
            $where[] = "r.user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }
        
        if (!empty($filters['rating'])) {
            $where[] = "r.rating = :rating";
            $params[':rating'] = $filters['rating'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(r.content LIKE :search OR u.name LIKE :search OR p.name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} r 
                     LEFT JOIN users u ON r.user_id = u.id 
                     LEFT JOIN products p ON r.product_id = p.id 
                     {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Get reviews
        $sql = "SELECT r.*, u.name as user_name, u.avatar as user_avatar, p.name as product_name, p.slug as product_slug,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as product_image
                FROM {$this->table} r 
                LEFT JOIN users u ON r.user_id = u.id 
                LEFT JOIN products p ON r.product_id = p.id 
                {$whereClause} 
                ORDER BY r.created_at DESC 
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
     * Get product rating statistics (alias)
     */
    public function getProductStats($productId) {
        return $this->getProductRatingStats($productId);
    }
    
    /**
     * Get product rating statistics
     */
    public function getProductRatingStats($productId) {
        $sql = "SELECT 
                    COUNT(*) as total_reviews,
                    AVG(rating) as average_rating,
                    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
                    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
                    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
                    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
                    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
                FROM {$this->table} 
                WHERE product_id = :product_id AND status = 'approved'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetch();
    }
    
    /**
     * Mark review as helpful
     */
    public function markHelpful($reviewId, $userId, $ipAddress = null) {
        // Check if already marked (by user or IP)
        if ($userId) {
            $sql = "SELECT id FROM review_helpful WHERE review_id = :review_id AND user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':review_id' => $reviewId, ':user_id' => $userId]);
        } else {
            $sql = "SELECT id FROM review_helpful WHERE review_id = :review_id AND ip_address = :ip_address";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':review_id' => $reviewId, ':ip_address' => $ipAddress]);
        }
        
        if ($stmt->fetch()) {
            return false; // Already marked
        }
        
        // Add helpful mark
        $sql = "INSERT INTO review_helpful (review_id, user_id, ip_address) VALUES (:review_id, :user_id, :ip_address)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':review_id' => $reviewId, ':user_id' => $userId, ':ip_address' => $ipAddress]);
        
        // Update helpful count
        $sql = "UPDATE {$this->table} SET helpful_count = helpful_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $reviewId]);
        
        return true;
    }
    
    /**
     * Check if user has reviewed product
     */
    public function hasUserReviewed($productId, $userId) {
        $sql = "SELECT id FROM {$this->table} WHERE product_id = :product_id AND user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId, ':user_id' => $userId]);
        return $stmt->fetch() ? true : false;
    }
    
    /**
     * Get reviews by user ID
     */
    public function getByUserId($userId) {
        $sql = "SELECT r.*, p.name as product_name, p.slug as product_slug,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC LIMIT 1) as product_image
                FROM {$this->table} r
                LEFT JOIN products p ON r.product_id = p.id
                WHERE r.user_id = :user_id
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Add reply to review
     */
    public function addReply($reviewId, $reply, $replyBy) {
        $sql = "UPDATE {$this->table} SET reply = :reply, reply_by = :reply_by, reply_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $reviewId,
            ':reply' => $reply,
            ':reply_by' => $replyBy
        ]);
    }
    
    /**
     * Count reviews by status
     */
    public function countByStatus($status = null) {
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
     * Get review statistics
     */
    public function getStatistics() {
        $stats = [];
        
        // By status
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $stmt = $this->db->query($sql);
        $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // By rating
        $sql = "SELECT rating, COUNT(*) as count FROM {$this->table} WHERE status = 'approved' GROUP BY rating ORDER BY rating DESC";
        $stmt = $this->db->query($sql);
        $stats['by_rating'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Average rating
        $sql = "SELECT AVG(rating) as avg_rating FROM {$this->table} WHERE status = 'approved'";
        $stmt = $this->db->query($sql);
        $stats['average_rating'] = round($stmt->fetch()['avg_rating'] ?? 0, 1);
        
        return $stats;
    }
    
    /**
     * Get reviews by product ID (alias)
     */
    public function getByProductId($productId) {
        return $this->getByProduct($productId)['data'];
    }
    
    /**
     * Get pending count
     */
    public function getPendingCount() {
        return $this->countByStatus('pending');
    }
    
    /**
     * Get recent reviews
     */
    public function getRecent($limit = 5) {
        $sql = "SELECT r.*, u.name as user_name, p.name as product_name 
                FROM {$this->table} r 
                LEFT JOIN users u ON r.user_id = u.id 
                LEFT JOIN products p ON r.product_id = p.id 
                ORDER BY r.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get counts by all statuses
     */
    public function getCountsByStatus() {
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    
    /**
     * Get all reviews for admin
     */
    public function getAllAdmin($statusFilter = null, $ratingFilter = null, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        
        if ($statusFilter) {
            $where[] = "r.status = :status";
            $params[':status'] = $statusFilter;
        }
        
        if ($ratingFilter) {
            $where[] = "r.rating = :rating";
            $params[':rating'] = $ratingFilter;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} r {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Get reviews
        $sql = "SELECT r.*, u.name as user_name, u.avatar as user_avatar, p.name as product_name, p.slug as product_slug,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as product_image
                FROM {$this->table} r 
                LEFT JOIN users u ON r.user_id = u.id 
                LEFT JOIN products p ON r.product_id = p.id 
                {$whereClause}
                ORDER BY r.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'reviews' => $stmt->fetchAll(),
            'total' => $total
        ];
    }
    
    /**
     * Get stats by rating
     */
    public function getStatsByRating() {
        $sql = "SELECT rating, COUNT(*) as count FROM {$this->table} GROUP BY rating ORDER BY rating DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    
    /**
     * Get average rating
     */
    public function getAverageRating() {
        $sql = "SELECT AVG(rating) as avg_rating FROM {$this->table} WHERE status = 'approved'";
        $stmt = $this->db->query($sql);
        return round($stmt->fetch()['avg_rating'] ?? 0, 1);
    }
    
    /**
     * Get pending reviews
     */
    public function getPending($page = 1, $limit = 10) {
        return $this->getAllAdmin('pending', null, $page, $limit);
    }
}
