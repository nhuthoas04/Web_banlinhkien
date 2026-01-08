<?php
/**
 * Conversation Model - MySQL Version
 */

require_once __DIR__ . '/../config/database.php';

class Conversation {
    private $db;
    private $table = 'conversations';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new conversation
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, subject, status) 
                VALUES (:user_id, :subject, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':subject' => $data['subject'] ?? null,
            ':status' => $data['status'] ?? 'open'
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Find conversation by ID
     */
    public function findById($id) {
        $sql = "SELECT c.*, u.name as user_name, u.email as user_email, u.avatar as user_avatar,
                e.name as assigned_name
                FROM {$this->table} c 
                LEFT JOIN users u ON c.user_id = u.id 
                LEFT JOIN users e ON c.assigned_to = e.id
                WHERE c.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get or create conversation for user
     */
    public function getOrCreateForUser($userId) {
        // Check for existing open conversation
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id AND status = 'open' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $conversation = $stmt->fetch();
        
        if (!$conversation) {
            $id = $this->create(['user_id' => $userId]);
            $conversation = $this->findById($id);
        }
        
        return $conversation;
    }
    
    /**
     * Update conversation
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
     * Get all conversations with pagination
     */
    public function getAll($page = 1, $limit = 20, $filters = []) {
        // Ensure valid values
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $where[] = "c.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['assigned_to'])) {
            $where[] = "c.assigned_to = :assigned_to";
            $params[':assigned_to'] = $filters['assigned_to'];
        }
        
        if (isset($filters['unassigned']) && $filters['unassigned']) {
            $where[] = "c.assigned_to IS NULL";
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} c {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Get conversations
        $sql = "SELECT c.*, u.name as user_name, u.avatar as user_avatar, e.name as assigned_name,
                (SELECT content FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND is_read = 0 AND sender_type = 'user') as unread_count
                FROM {$this->table} c 
                LEFT JOIN users u ON c.user_id = u.id 
                LEFT JOIN users e ON c.assigned_to = e.id
                {$whereClause} 
                ORDER BY c.last_message_at DESC, c.created_at DESC
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
     * Get messages for conversation
     */
    public function getMessages($conversationId, $limit = 50, $beforeId = null) {
        $sql = "SELECT m.*, u.name as sender_name, u.avatar as sender_avatar
                FROM messages m 
                LEFT JOIN users u ON m.sender_id = u.id 
                WHERE m.conversation_id = :conversation_id";
        
        $params = [':conversation_id' => $conversationId];
        
        if ($beforeId) {
            $sql .= " AND m.id < :before_id";
            $params[':before_id'] = $beforeId;
        }
        
        $sql .= " ORDER BY m.created_at DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $messages = $stmt->fetchAll();
        return array_reverse($messages); // Return in chronological order
    }
    
    /**
     * Send message
     */
    public function sendMessage($data) {
        $sql = "INSERT INTO messages (conversation_id, sender_id, sender_type, content, image, created_at) 
                VALUES (:conversation_id, :sender_id, :sender_type, :content, :image, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':conversation_id' => $data['conversation_id'],
            ':sender_id' => $data['sender_id'],
            ':sender_type' => $data['sender_type'],
            ':content' => $data['content'],
            ':image' => $data['image'] ?? null
        ]);
        
        if (!$result) {
            return false;
        }
        
        $messageId = $this->db->lastInsertId();
        
        // Update conversation last_message_at
        $this->update($data['conversation_id'], ['last_message_at' => date('Y-m-d H:i:s')]);
        
        // Get the message with sender info
        $sql = "SELECT m.*, u.name as sender_name, u.avatar as sender_avatar
                FROM messages m 
                LEFT JOIN users u ON m.sender_id = u.id 
                WHERE m.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $messageId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Mark messages as read
     */
    public function markAsRead($conversationId, $readerType) {
        $senderType = $readerType === 'user' ? 'employee' : 'user';
        
        $sql = "UPDATE messages SET is_read = 1, read_at = NOW() 
                WHERE conversation_id = :conversation_id AND sender_type = :sender_type AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':conversation_id' => $conversationId,
            ':sender_type' => $senderType
        ]);
    }
    
    /**
     * Get unread count for employee
     */
    public function getUnreadCountForEmployee($employeeId = null) {
        $sql = "SELECT COUNT(*) as count FROM messages m 
                JOIN {$this->table} c ON m.conversation_id = c.id 
                WHERE m.is_read = 0 AND m.sender_type = 'user' AND c.status = 'open'";
        
        if ($employeeId) {
            $sql .= " AND (c.assigned_to = :employee_id OR c.assigned_to IS NULL)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':employee_id' => $employeeId]);
        } else {
            $stmt = $this->db->query($sql);
        }
        
        return $stmt->fetch()['count'];
    }
    
    /**
     * Get unread count for user
     */
    public function getUnreadCountForUser($userId) {
        $sql = "SELECT COUNT(*) as count FROM messages m 
                JOIN {$this->table} c ON m.conversation_id = c.id 
                WHERE c.user_id = :user_id AND m.is_read = 0 AND m.sender_type != 'user'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch()['count'];
    }
    
    /**
     * Assign conversation to employee
     */
    public function assignTo($conversationId, $employeeId) {
        return $this->update($conversationId, ['assigned_to' => $employeeId]);
    }
    
    /**
     * Close conversation
     */
    public function close($conversationId) {
        return $this->update($conversationId, ['status' => 'closed']);
    }
    
    /**
     * Get new messages (for polling)
     */
    public function getNewMessages($conversationId, $afterId) {
        $sql = "SELECT m.*, u.name as sender_name, u.avatar as sender_avatar
                FROM messages m 
                LEFT JOIN users u ON m.sender_id = u.id 
                WHERE m.conversation_id = :conversation_id AND m.id > :after_id
                ORDER BY m.created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':conversation_id' => $conversationId,
            ':after_id' => $afterId
        ]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count conversations by status
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
}
?>
