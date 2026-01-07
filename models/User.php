<?php
/**
 * User Model - MySQL Version
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new user
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, email, password, phone, role, status, avatar, email_verified, verification_token) 
                VALUES (:name, :email, :password, :phone, :role, :status, :avatar, :email_verified, :verification_token)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':phone' => $data['phone'] ?? null,
            ':role' => $data['role'] ?? 'user',
            ':status' => $data['status'] ?? 'active',
            ':avatar' => $data['avatar'] ?? null,
            ':email_verified' => $data['email_verified'] ?? 0,
            ':verification_token' => $data['verification_token'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Update user
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
     * Delete user
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get all users with pagination
     */
    public function getAll($page = 1, $limit = 10, $filters = []) {
        // Ensure valid values
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        
        if (!empty($filters['role'])) {
            $where[] = "role = :role";
            $params[':role'] = $filters['role'];
        }
        
        if (!empty($filters['status'])) {
            $where[] = "status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(name LIKE :search OR email LIKE :search OR phone LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Get users
        $sql = "SELECT id, name, email, phone, role, status, avatar, email_verified, created_at, last_login 
                FROM {$this->table} {$whereClause} 
                ORDER BY created_at DESC 
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
     * Get employees
     */
    public function getEmployees() {
        $sql = "SELECT id, name, email, phone, avatar FROM {$this->table} 
                WHERE role IN ('employee', 'admin') AND status = 'active' 
                ORDER BY name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get user addresses
     */
    public function getAddresses($userId) {
        $sql = "SELECT * FROM user_addresses WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get default address
     */
    public function getDefaultAddress($userId) {
        $sql = "SELECT * FROM user_addresses WHERE user_id = :user_id AND is_default = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Add address
     */
    public function addAddress($userId, $data) {
        // If this is default, unset other defaults
        if (!empty($data['is_default'])) {
            $this->db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :user_id")
                     ->execute([':user_id' => $userId]);
        }
        
        $sql = "INSERT INTO user_addresses (user_id, name, phone, address, ward, district, city, is_default) 
                VALUES (:user_id, :name, :phone, :address, :ward, :district, :city, :is_default)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':address' => $data['address'],
            ':ward' => $data['ward'] ?? null,
            ':district' => $data['district'] ?? null,
            ':city' => $data['city'],
            ':is_default' => $data['is_default'] ?? 0
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update address
     */
    public function updateAddress($addressId, $userId, $data) {
        if (!empty($data['is_default'])) {
            $this->db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :user_id")
                     ->execute([':user_id' => $userId]);
        }
        
        $sql = "UPDATE user_addresses SET 
                name = :name, phone = :phone, address = :address, 
                ward = :ward, district = :district, city = :city, is_default = :is_default 
                WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $addressId,
            ':user_id' => $userId,
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':address' => $data['address'],
            ':ward' => $data['ward'] ?? null,
            ':district' => $data['district'] ?? null,
            ':city' => $data['city'],
            ':is_default' => $data['is_default'] ?? 0
        ]);
    }
    
    /**
     * Delete address
     */
    public function deleteAddress($addressId, $userId) {
        $sql = "DELETE FROM user_addresses WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $addressId, ':user_id' => $userId]);
    }
    
    /**
     * Set default address
     */
    public function setDefaultAddress($addressId, $userId) {
        $this->db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :user_id")
                 ->execute([':user_id' => $userId]);
        
        $sql = "UPDATE user_addresses SET is_default = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $addressId, ':user_id' => $userId]);
    }
    
    /**
     * Count users by role
     */
    public function countByRole($role = null) {
        if ($role) {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE role = :role";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':role' => $role]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM {$this->table}";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetch()['count'];
    }
    
    /**
     * Get user statistics
     */
    public function getStatistics() {
        $stats = [];
        
        // Total users by role
        $sql = "SELECT role, COUNT(*) as count FROM {$this->table} GROUP BY role";
        $stmt = $this->db->query($sql);
        $stats['by_role'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // New users this month
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
        $stmt = $this->db->query($sql);
        $stats['new_this_month'] = $stmt->fetch()['count'];
        
        // Active users
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'active'";
        $stmt = $this->db->query($sql);
        $stats['active'] = $stmt->fetch()['count'];
        
        return $stats;
    }
    
    /**
     * Get user by ID (alias)
     */
    public function getById($id) {
        $user = $this->findById($id);
        if ($user) {
            $user['addresses'] = $this->getAddresses($id);
        }
        return $user;
    }
    
    /**
     * Get user by email (alias)
     */
    public function getByEmail($email) {
        return $this->findByEmail($email);
    }
    
    /**
     * Get counts by role for admin
     */
    public function getCountsByRole() {
        $sql = "SELECT role, COUNT(*) as count FROM {$this->table} GROUP BY role";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        $counts = [];
        foreach ($results as $row) {
            $counts[$row['role']] = $row['count'];
        }
        return $counts;
    }
    
    /**
     * Login user - set session
     */
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email khong ton tai'];
        }
        
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mat khau khong dung'];
        }
        
        if (isset($user['status']) && $user['status'] !== 'active') {
            return ['success' => false, 'message' => 'Tai khoan da bi khoa'];
        }
        
        // Update last login
        $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['fullname'] = $user['name'] ?? $user['fullname'] ?? '';
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'] ?? 'user';
        $_SESSION['avatar'] = $user['avatar'] ?? null;
        
        return ['success' => true, 'message' => 'Dang nhap thanh cong', 'user' => $user];
    }
    
    /**
     * Logout user
     */
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user']);
        unset($_SESSION['fullname']);
        unset($_SESSION['email']);
        unset($_SESSION['role']);
        unset($_SESSION['avatar']);
        session_destroy();
        return true;
    }
    
    /**
     * Register new user
     */
    public function register($data) {
        // Check if email exists
        if ($this->findByEmail($data['email'])) {
            return ['success' => false, 'message' => 'Email da duoc su dung'];
        }
        
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Map fullname to name
        if (isset($data['fullname']) && !isset($data['name'])) {
            $data['name'] = $data['fullname'];
            unset($data['fullname']);
        }
        
        // Set defaults
        $data['role'] = $data['role'] ?? 'user';
        $data['status'] = $data['status'] ?? 'active';
        
        $userId = $this->create($data);
        
        if ($userId) {
            return ['success' => true, 'message' => 'Dang ky thanh cong', 'user_id' => $userId];
        }
        
        return ['success' => false, 'message' => 'Co loi xay ra'];
    }
    
    /**
     * Get top customers by total spent
     */
    public function getTopCustomers($limit = 10) {
        $sql = "SELECT u.*, 
                       COUNT(o.id) as order_count,
                       COALESCE(SUM(o.total), 0) as total_spent
                FROM {$this->table} u
                LEFT JOIN orders o ON u.id = o.user_id AND o.status = 'delivered'
                WHERE u.role = 'user'
                GROUP BY u.id
                ORDER BY total_spent DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
