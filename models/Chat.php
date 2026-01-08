<?php
/**
 * Chat Model - MySQL Version
 * Quản lý box chat tư vấn khách hàng
 */

require_once __DIR__ . '/../config/database.php';

class Chat {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Tạo hoặc lấy cuộc hội thoại
     */
    public function getOrCreateConversation($userId, $userName) {
        // Tìm cuộc hội thoại đang mở
        $sql = "SELECT * FROM conversations 
                WHERE user_id = :user_id 
                AND status IN ('open', 'pending')
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $conversation = $stmt->fetch();
        
        if ($conversation) {
            return $conversation;
        }
        
        // Tạo mới cuộc hội thoại
        $sql = "INSERT INTO conversations (user_id, subject, status, last_message_at) 
                VALUES (:user_id, :subject, 'pending', NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':subject' => 'Hỗ trợ từ ' . $userName
        ]);
        
        $conversationId = $this->db->lastInsertId();
        return $this->getConversationById($conversationId);
    }
    
    /**
     * Lấy cuộc hội thoại theo ID
     */
    public function getConversationById($conversationId) {
        $sql = "SELECT c.*, u.name as user_name, u.email as user_email,
                       e.name as employee_name
                FROM conversations c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN users e ON c.assigned_to = e.id
                WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $conversationId]);
        return $stmt->fetch();
    }
    
    /**
     * Gửi tin nhắn
     */
    public function sendMessage($conversationId, $senderId, $senderName, $senderRole, $content, $image = null) {
        $sql = "INSERT INTO messages (conversation_id, sender_id, sender_type, content, image) 
                VALUES (:conversation_id, :sender_id, :sender_type, :content, :image)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':conversation_id' => $conversationId,
            ':sender_id' => $senderId,
            ':sender_type' => $senderRole,
            ':content' => sanitize($content),
            ':image' => $image
        ]);
        
        if ($result) {
            // Cập nhật cuộc hội thoại
            $sql = "UPDATE conversations 
                    SET last_message_at = NOW(), updated_at = NOW() 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $conversationId]);
            
            return [
                'success' => true, 
                'message' => [
                    'id' => $this->db->lastInsertId(),
                    'content' => $content
                ]
            ];
        }
        
        return ['success' => false, 'message' => 'Gửi tin nhắn thất bại'];
    }
    
    /**
     * Lấy tin nhắn của cuộc hội thoại
     */
    public function getMessages($conversationId, $page = 1, $limit = 50) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT m.*, u.name as sender_name, u.avatar as sender_avatar
                FROM messages m
                LEFT JOIN users u ON m.sender_id = u.id
                WHERE m.conversation_id = :conversation_id
                ORDER BY m.created_at ASC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':conversation_id', $conversationId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Đánh dấu đã đọc
     */
    public function markAsRead($conversationId, $role) {
        // Đánh dấu tất cả tin nhắn đã đọc
        $sql = "UPDATE messages 
                SET is_read = 1, read_at = NOW() 
                WHERE conversation_id = :conversation_id 
                AND sender_type != :role 
                AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':conversation_id' => $conversationId,
            ':role' => $role
        ]);
        
        return true;
    }
    
    /**
     * Nhân viên nhận hỗ trợ cuộc hội thoại
     */
    public function assignEmployee($conversationId, $employeeId, $employeeName) {
        $sql = "UPDATE conversations 
                SET assigned_to = :employee_id, 
                    status = 'open',
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':employee_id' => $employeeId,
            ':id' => $conversationId
        ]);
        
        if ($result && $stmt->rowCount() > 0) {
            // Gửi tin nhắn thông báo
            $this->sendMessage(
                $conversationId,
                $employeeId,
                $employeeName,
                'system',
                "Xin chào! Tôi là {$employeeName}, tôi sẽ hỗ trợ bạn. Bạn cần giúp đỡ gì ạ?"
            );
            
            return ['success' => true, 'message' => 'Đã nhận hỗ trợ cuộc hội thoại'];
        }
        
        return ['success' => false, 'message' => 'Nhận hỗ trợ thất bại'];
    }
    
    /**
     * Đóng cuộc hội thoại
     */
    public function closeConversation($conversationId, $closedBy) {
        $sql = "UPDATE conversations 
                SET status = 'closed', updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([':id' => $conversationId]);
        
        if ($result && $stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Đã đóng cuộc hội thoại'];
        }
        
        return ['success' => false, 'message' => 'Đóng cuộc hội thoại thất bại'];
    }
    
    /**
     * Lấy danh sách cuộc hội thoại (Employee/Admin)
     */
    public function getConversations($filter = [], $page = 1, $limit = 20) {
        // Ensure valid values
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;
        
        $where = [];
        $params = [];
        
        if (isset($filter['status'])) {
            $where[] = "c.status = :status";
            $params[':status'] = $filter['status'];
        }
        
        if (isset($filter['employee_id'])) {
            $where[] = "c.assigned_to = :employee_id";
            $params[':employee_id'] = $filter['employee_id'];
        }
        
        if (isset($filter['user_id'])) {
            $where[] = "c.user_id = :user_id";
            $params[':user_id'] = $filter['user_id'];
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT c.*, u.name as user_name, u.email as user_email,
                       e.name as employee_name,
                       (SELECT COUNT(*) FROM messages m 
                        WHERE m.conversation_id = c.id AND m.is_read = 0) as unread_count
                FROM conversations c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN users e ON c.assigned_to = e.id
                {$whereClause}
                ORDER BY c.last_message_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $conversations = $stmt->fetchAll();
        
        // Đếm tổng số
        $countSql = "SELECT COUNT(*) as total FROM conversations c {$whereClause}";
        $stmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $total = $stmt->fetch()['total'];
        
        return [
            'conversations' => $conversations,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }
    
    /**
     * Lấy cuộc hội thoại chờ hỗ trợ
     */
    public function getPendingConversations() {
        return $this->getConversations(['status' => 'pending']);
    }
    
    /**
     * Lấy cuộc hội thoại của nhân viên
     */
    public function getEmployeeConversations($employeeId) {
        return $this->getConversations([
            'employee_id' => $employeeId,
            'status' => 'open'
        ]);
    }
    
    /**
     * Lấy cuộc hội thoại của user
     */
    public function getUserConversations($userId) {
        return $this->getConversations(['user_id' => $userId]);
    }
    
    /**
     * Đếm cuộc hội thoại chờ hỗ trợ
     */
    public function countPending() {
        $sql = "SELECT COUNT(*) as total FROM conversations WHERE status = 'pending'";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['total'];
    }
    
    /**
     * Đếm tin nhắn chưa đọc
     */
    public function countUnreadForEmployee($employeeId = null) {
        $sql = "SELECT COUNT(DISTINCT c.id) as total 
                FROM conversations c
                INNER JOIN messages m ON c.id = m.conversation_id
                WHERE c.status = 'open' 
                AND m.is_read = 0 
                AND m.sender_type = 'user'";
        
        $params = [];
        if ($employeeId) {
            $sql .= " AND c.assigned_to = :employee_id";
            $params[':employee_id'] = $employeeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }
    
    /**
     * Thống kê chat
     */
    public function getStatistics() {
        $stats = [];
        
        $sql = "SELECT 
                    COUNT(*) as total_conversations,
                    SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open_conversations,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_conversations,
                    SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_conversations
                FROM conversations";
        $stmt = $this->db->query($sql);
        $stats = $stmt->fetch();
        
        $sql = "SELECT COUNT(*) as total_messages FROM messages";
        $stmt = $this->db->query($sql);
        $stats['total_messages'] = $stmt->fetch()['total_messages'];
        
        return $stats;
    }
    
    /**
     * Get pending count (alias)
     */
    public function getPendingCount() {
        return $this->countPending();
    }
    
    /**
     * Get recent conversations
     */
    public function getRecent($limit = 5) {
        $sql = "SELECT c.*, u.name as user_name, u.avatar,
                (SELECT content FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND is_read = 0 AND sender_type = 'user') as unread_count
                FROM conversations c
                LEFT JOIN users u ON c.user_id = u.id
                ORDER BY c.last_message_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $conversations = $stmt->fetchAll();
        
        // Add user data to each conversation
        foreach ($conversations as &$conv) {
            $conv['user'] = [
                'name' => $conv['user_name'],
                'avatar' => $conv['avatar']
            ];
        }
        
        return $conversations;
    }
    
    /**
     * Get all conversations (alias)
     */
    public function getAllConversations() {
        return $this->getConversations()['conversations'];
    }
    
    /**
     * Get conversation by ID (alias)
     */
    public function getConversation($id) {
        return $this->getConversationById($id);
    }
}
