<?php
/**
 * Order Model - MySQL Version
 */

require_once __DIR__ . '/../config/database.php';

class Order {
    private $db;
    private $table = 'orders';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new order
     */
    public function create($data) {
        $this->db->beginTransaction();
        
        try {
            // Generate order number
            $orderNumber = generateOrderNumber();
            
            $sql = "INSERT INTO {$this->table} (order_number, user_id, customer_name, customer_email, customer_phone,
                    shipping_address, shipping_ward, shipping_district, shipping_city, subtotal, shipping_fee, 
                    discount, total, payment_method, note) 
                    VALUES (:order_number, :user_id, :customer_name, :customer_email, :customer_phone,
                    :shipping_address, :shipping_ward, :shipping_district, :shipping_city, :subtotal, :shipping_fee,
                    :discount, :total, :payment_method, :note)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':order_number' => $orderNumber,
                ':user_id' => $data['user_id'] ?? null,
                ':customer_name' => $data['customer_name'],
                ':customer_email' => $data['customer_email'],
                ':customer_phone' => $data['customer_phone'],
                ':shipping_address' => $data['shipping_address'],
                ':shipping_ward' => $data['shipping_ward'] ?? null,
                ':shipping_district' => $data['shipping_district'] ?? null,
                ':shipping_city' => $data['shipping_city'],
                ':subtotal' => $data['subtotal'],
                ':shipping_fee' => $data['shipping_fee'] ?? 0,
                ':discount' => $data['discount'] ?? 0,
                ':total' => $data['total'],
                ':payment_method' => $data['payment_method'] ?? 'cod',
                ':note' => $data['note'] ?? null
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            // Insert order items
            if (!empty($data['items'])) {
                $itemSql = "INSERT INTO order_items (order_id, product_id, product_name, product_image, product_sku, price, quantity, total) 
                            VALUES (:order_id, :product_id, :product_name, :product_image, :product_sku, :price, :quantity, :total)";
                $itemStmt = $this->db->prepare($itemSql);
                
                foreach ($data['items'] as $item) {
                    $itemStmt->execute([
                        ':order_id' => $orderId,
                        ':product_id' => $item['product_id'],
                        ':product_name' => $item['product_name'],
                        ':product_image' => $item['product_image'] ?? null,
                        ':product_sku' => $item['product_sku'] ?? null,
                        ':price' => $item['price'],
                        ':quantity' => $item['quantity'],
                        ':total' => $item['price'] * $item['quantity']
                    ]);
                }
            }
            
            // Add to order history
            $this->addHistory($orderId, 'pending', 'Đơn hàng được tạo', $data['user_id']);
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Find order by ID
     */
    public function findById($id) {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email,
                e.name as employee_name
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.id 
                LEFT JOIN users e ON o.assigned_employee = e.id
                WHERE o.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch();
        
        if ($order) {
            $order['items'] = $this->getItems($id);
            $order['history'] = $this->getHistory($id);
        }
        
        return $order;
    }
    
    /**
     * Find order by order number
     */
    public function findByOrderNumber($orderNumber) {
        $sql = "SELECT o.*, u.name as user_name 
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.order_number = :order_number LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_number' => $orderNumber]);
        $order = $stmt->fetch();
        
        if ($order) {
            $order['items'] = $this->getItems($order['id']);
        }
        
        return $order;
    }
    
    /**
     * Update order
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
     * Delete order
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get order items
     */
    public function getItems($orderId) {
        $sql = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get order history
     */
    public function getHistory($orderId) {
        $sql = "SELECT oh.*, u.name as created_by_name 
                FROM order_history oh 
                LEFT JOIN users u ON oh.created_by = u.id 
                WHERE oh.order_id = :order_id 
                ORDER BY oh.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Add order history
     */
    public function addHistory($orderId, $status, $note = null, $createdBy = null) {
        $sql = "INSERT INTO order_history (order_id, status, note, created_by) VALUES (:order_id, :status, :note, :created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':order_id' => $orderId,
            ':status' => $status,
            ':note' => $note,
            ':created_by' => $createdBy
        ]);
    }
    
    /**
     * Update order status
     */
    public function updateStatus($id, $status, $note = null, $userId = null) {
        $this->update($id, ['status' => $status]);
        
        if ($status === 'delivered') {
            $this->update($id, ['delivered_at' => date('Y-m-d H:i:s')]);
        }
        
        return $this->addHistory($id, $status, $note, $userId);
    }
    
    /**
     * Get all orders with pagination
     */
    public function getAll($page = 1, $limit = 10, $filters = []) {
        // Ensure valid values
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $where[] = "o.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['payment_status'])) {
            $where[] = "o.payment_status = :payment_status";
            $params[':payment_status'] = $filters['payment_status'];
        }
        
        if (!empty($filters['user_id'])) {
            $where[] = "o.user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }
        
        if (!empty($filters['assigned_employee'])) {
            $where[] = "o.assigned_employee = :assigned_employee";
            $params[':assigned_employee'] = $filters['assigned_employee'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(o.order_number LIKE :search OR o.customer_name LIKE :search OR o.customer_phone LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = "DATE(o.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = "DATE(o.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} o {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Get orders
        $sql = "SELECT o.*, u.name as user_name, e.name as employee_name
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.id 
                LEFT JOIN users e ON o.assigned_employee = e.id
                {$whereClause} 
                ORDER BY o.created_at DESC 
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
     * Get orders by user
     */
    public function getByUser($userId, $page = 1, $limit = 10) {
        return $this->getAll($page, $limit, ['user_id' => $userId]);
    }
    
    /**
     * Count orders by status
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
     * Get order statistics
     */
    public function getStatistics($dateFrom = null, $dateTo = null) {
        $stats = [];
        $where = [];
        $params = [];
        
        if ($dateFrom) {
            $where[] = "DATE(created_at) >= :date_from";
            $params[':date_from'] = $dateFrom;
        }
        if ($dateTo) {
            $where[] = "DATE(created_at) <= :date_to";
            $params[':date_to'] = $dateTo;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Total orders and revenue
        $sql = "SELECT COUNT(*) as total_orders, COALESCE(SUM(total), 0) as total_revenue 
                FROM {$this->table} {$whereClause}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $stats['total_orders'] = $result['total_orders'];
        $stats['total_revenue'] = $result['total_revenue'];
        
        // Orders by status
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} {$whereClause} GROUP BY status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $statusCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Format status_counts với tất cả các status
        $stats['status_counts'] = [
            'pending' => $statusCounts['pending'] ?? 0,
            'confirmed' => $statusCounts['confirmed'] ?? 0,
            'processing' => $statusCounts['processing'] ?? 0,
            'shipping' => $statusCounts['shipping'] ?? 0,
            'delivered' => $statusCounts['delivered'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0
        ];
        $stats['by_status'] = $statusCounts; // Keep backward compatibility
        
        // Revenue by payment status
        $sql = "SELECT payment_status, COALESCE(SUM(total), 0) as revenue FROM {$this->table} {$whereClause} GROUP BY payment_status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats['by_payment_status'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Daily orders (last 30 days)
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as orders, COALESCE(SUM(total), 0) as revenue 
                FROM {$this->table} 
                WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at) 
                ORDER BY date ASC";
        $stmt = $this->db->query($sql);
        $stats['daily'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Get recent orders
     */
    public function getRecent($status = null, $limit = 5) {
        $sql = "SELECT o.*, 
                       o.order_number as order_code,
                       o.customer_name,
                       o.customer_phone,
                       u.name as user_name,
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) as item_count
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.id ";
        
        if ($status) {
            $sql .= "WHERE o.status = :status ";
        }
        
        $sql .= "ORDER BY o.created_at DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        if ($status) {
            $stmt->bindValue(':status', $status);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get order by ID (alias)
     */
    public function getById($id) {
        return $this->findById($id);
    }
    
    /**
     * Get orders by user ID
     */
    public function getByUserId($userId, $status = null) {
        $filters = ['user_id' => $userId];
        if ($status) {
            $filters['status'] = $status;
        }
        return $this->getAll(1, 100, $filters)['data'];
    }
    
    /**
     * Get user order stats
     */
    public function getUserStats($userId) {
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as completed_orders,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    COALESCE(SUM(total), 0) as total_spent
                FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Get today's order count
     */
    public function getTodayCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE DATE(created_at) = CURRENT_DATE()";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['count'];
    }
    
    /**
     * Get count by status (alias)
     */
    public function getCountByStatus($status) {
        return $this->countByStatus($status);
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
     * Get stats by status (formatted for admin view)
     */
    public function getStatsByStatus() {
        $counts = $this->getCountsByStatus();
        return [
            'pending' => $counts['pending'] ?? 0,
            'confirmed' => $counts['confirmed'] ?? 0,
            'processing' => $counts['processing'] ?? 0,
            'shipping' => $counts['shipping'] ?? 0,
            'delivered' => $counts['delivered'] ?? 0,
            'cancelled' => $counts['cancelled'] ?? 0
        ];
    }
    
    /**
     * Get total count of all orders
     */
    public function getTotalCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['count'];
    }
    
    /**
     * Get revenue by days
     */
    public function getRevenueByDays($days = 7) {
        $sql = "SELECT DATE(created_at) as date, SUM(total) as revenue 
                FROM {$this->table} 
                WHERE status = 'delivered' 
                AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(created_at) 
                ORDER BY date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get revenue by month
     */
    public function getRevenueByMonth($months = 12) {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue 
                FROM {$this->table} 
                WHERE status = 'delivered' 
                AND created_at >= DATE_SUB(NOW(), INTERVAL :months MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                ORDER BY month ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Cancel order
     */
    public function cancel($orderId, $userId, $reason = '') {
        // Check if order belongs to user and is pending
        $order = $this->findById($orderId);
        
        if (!$order) {
            return ['success' => false, 'message' => 'Don hang khong ton tai'];
        }
        
        if ((string)$order['user_id'] !== (string)$userId) {
            return ['success' => false, 'message' => 'Ban khong co quyen huy don hang nay'];
        }
        
        if ($order['status'] !== 'pending') {
            return ['success' => false, 'message' => 'Chi co the huy don hang o trang thai cho xac nhan'];
        }
        
        $this->update($orderId, [
            'status' => 'cancelled',
            'cancel_reason' => $reason,
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);
        
        return ['success' => true, 'message' => 'Da huy don hang thanh cong'];
    }
}
