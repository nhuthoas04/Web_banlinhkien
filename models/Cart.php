<?php
/**
 * Cart Model - MySQL Version
 */

require_once __DIR__ . '/../config/database.php';

class Cart {
    private $db;
    private $table = 'carts';
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Get or create cart for user/session
     */
    public function getOrCreate($userId = null, $sessionId = null) {
        $cart = null;
        
        if ($userId) {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            $cart = $stmt->fetch();
        } elseif ($sessionId) {
            $sql = "SELECT * FROM {$this->table} WHERE session_id = :session_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':session_id' => $sessionId]);
            $cart = $stmt->fetch();
        }
        
        if (!$cart) {
            $sql = "INSERT INTO {$this->table} (user_id, session_id) VALUES (:user_id, :session_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':session_id' => $sessionId
            ]);
            
            $cartId = $this->db->lastInsertId();
            $cart = ['id' => $cartId, 'user_id' => $userId, 'session_id' => $sessionId];
        }
        
        return $cart;
    }
    
    /**
     * Get cart with items
     */
    public function getCart($userId = null, $sessionId = null) {
        $cart = $this->getOrCreate($userId, $sessionId);
        $cart['items'] = $this->getItems($cart['id']);
        $cart['total'] = $this->calculateTotal($cart['items']);
        $cart['count'] = count($cart['items']);
        
        return $cart;
    }
    
    /**
     * Get cart items
     */
    public function getItems($cartId) {
        $sql = "SELECT ci.*, p.name, p.slug, p.price, p.sale_price, p.stock, p.status, p.brand,
                (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as image
                FROM cart_items ci 
                LEFT JOIN products p ON ci.product_id = p.id 
                WHERE ci.cart_id = :cart_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cart_id' => $cartId]);
        
        $items = $stmt->fetchAll();
        
        // Calculate item totals
        foreach ($items as &$item) {
            $item['item_price'] = $item['sale_price'] ?? $item['price'];
            $item['item_total'] = $item['item_price'] * $item['quantity'];
        }
        
        return $items;
    }
    
    /**
     * Calculate cart total
     */
    private function calculateTotal($items) {
        $total = 0;
        foreach ($items as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $total += $price * $item['quantity'];
        }
        return $total;
    }
    
    /**
     * Add item to cart
     */
    public function addItem($productId, $quantity = 1, $userId = null, $sessionId = null) {
        $cart = $this->getOrCreate($userId, $sessionId);
        
        // Check if item already in cart
        $sql = "SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cart_id' => $cart['id'], ':product_id' => $productId]);
        $existingItem = $stmt->fetch();
        
        if ($existingItem) {
            // Update quantity
            $sql = "UPDATE cart_items SET quantity = quantity + :quantity WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $existingItem['id'], ':quantity' => $quantity]);
        } else {
            // Insert new item
            $sql = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cart_id' => $cart['id'],
                ':product_id' => $productId,
                ':quantity' => $quantity
            ]);
        }
        
        return $this->getCart($userId, $sessionId);
    }
    
    /**
     * Update item quantity
     */
    public function updateItem($productId, $quantity, $userId = null, $sessionId = null) {
        $cart = $this->getOrCreate($userId, $sessionId);
        
        if ($quantity <= 0) {
            return $this->removeItem($productId, $userId, $sessionId);
        }
        
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':cart_id' => $cart['id'],
            ':product_id' => $productId,
            ':quantity' => $quantity
        ]);
        
        return $this->getCart($userId, $sessionId);
    }
    
    /**
     * Remove item from cart
     */
    public function removeItem($productId, $userId = null, $sessionId = null) {
        $cart = $this->getOrCreate($userId, $sessionId);
        
        $sql = "DELETE FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cart_id' => $cart['id'], ':product_id' => $productId]);
        
        return $this->getCart($userId, $sessionId);
    }
    
    /**
     * Remove multiple items from cart by item IDs
     */
    public function removeMultipleItems($itemIds, $userId = null, $sessionId = null) {
        $cart = $this->getOrCreate($userId, $sessionId);
        
        if (!empty($itemIds)) {
            $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
            $sql = "DELETE FROM cart_items WHERE cart_id = ? AND id IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            $params = array_merge([$cart['id']], $itemIds);
            $stmt->execute($params);
        }
        
        return $this->getCart($userId, $sessionId);
    }
    
    /**
     * Clear cart
     */
    public function clear($userId = null, $sessionId = null) {
        $cart = $this->getOrCreate($userId, $sessionId);
        
        $sql = "DELETE FROM cart_items WHERE cart_id = :cart_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cart_id' => $cart['id']]);
        
        return $this->getCart($userId, $sessionId);
    }
    
    /**
     * Get cart count
     */
    public function getCount($userId = null, $sessionId = null) {
        $cart = $this->getOrCreate($userId, $sessionId);
        
        $sql = "SELECT COUNT(*) as count FROM cart_items WHERE cart_id = :cart_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cart_id' => $cart['id']]);
        return $stmt->fetch()['count'];
    }
    
    /**
     * Merge guest cart with user cart (after login)
     */
    public function mergeCart($userId, $sessionId) {
        // Get session cart
        $sql = "SELECT * FROM {$this->table} WHERE session_id = :session_id AND user_id IS NULL LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':session_id' => $sessionId]);
        $sessionCart = $stmt->fetch();
        
        if (!$sessionCart) {
            return;
        }
        
        // Get or create user cart
        $userCart = $this->getOrCreate($userId, null);
        
        // Get session cart items
        $sessionItems = $this->getItems($sessionCart['id']);
        
        foreach ($sessionItems as $item) {
            // Check if item already in user cart
            $sql = "SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cart_id' => $userCart['id'], ':product_id' => $item['product_id']]);
            $existingItem = $stmt->fetch();
            
            if ($existingItem) {
                // Update quantity
                $sql = "UPDATE cart_items SET quantity = quantity + :quantity WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $existingItem['id'], ':quantity' => $item['quantity']]);
            } else {
                // Move item to user cart
                $sql = "UPDATE cart_items SET cart_id = :new_cart_id WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':new_cart_id' => $userCart['id'], ':id' => $item['id']]);
            }
        }
        
        // Delete session cart
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $sessionCart['id']]);
    }
    
    /**
     * Validate cart items (check stock, status)
     */
    public function validateCart($userId = null, $sessionId = null) {
        $cart = $this->getCart($userId, $sessionId);
        $errors = [];
        
        foreach ($cart['items'] as $item) {
            if ($item['status'] !== 'active') {
                $errors[] = "Sản phẩm '{$item['name']}' không còn bán";
            } elseif ($item['stock'] < $item['quantity']) {
                $errors[] = "Sản phẩm '{$item['name']}' chỉ còn {$item['stock']} sản phẩm";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'cart' => $cart
        ];
    }
    
    /**
     * Get cart by user ID (alias for getCart)
     */
    public function getByUserId($userId) {
        return $this->getCart($userId, null);
    }
}
