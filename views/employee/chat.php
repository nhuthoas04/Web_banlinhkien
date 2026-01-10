<?php
$pageTitle = 'Hỗ trợ khách hàng';
include __DIR__ . '/../layouts/employee-header.php';
?>

<div class="admin-content chat-page">
    <div class="chat-container">
        <!-- Conversations List -->
        <div class="conversations-panel">
            <div class="panel-header">
                <h5>Hội thoại</h5>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchConversation" placeholder="Tìm kiếm...">
                </div>
            </div>
            
            <div class="conversations-list" id="conversationsList">
                <?php foreach ($conversations as $conv): ?>
                    <div class="conversation-item <?= ($activeConversation && $activeConversation['id'] == $conv['id']) ? 'active' : '' ?> <?= $conv['unread_count'] > 0 ? 'unread' : '' ?>"
                         data-id="<?= $conv['id'] ?>">
                        <img src="<?= BASE_URL . ($conv['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                             alt="Avatar" class="avatar">
                        <div class="conv-info">
                            <div class="conv-header">
                                <span class="name"><?= htmlspecialchars($conv['user']['name'] ?? $conv['user_name'] ?? 'Khách') ?></span>
                                <span class="time"><?= timeAgo($conv['last_message_at'] ?? '') ?></span>
                            </div>
                            <p class="last-message"><?= htmlspecialchars(mb_substr($conv['last_message'] ?? '', 0, 40)) ?>...</p>
                        </div>
                        <?php if ($conv['unread_count'] > 0): ?>
                            <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($conversations)): ?>
                    <div class="empty-conversations">
                        <i class="fas fa-comments"></i>
                        <p>Chưa có hội thoại nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-panel">
            <?php if ($activeConversation): ?>
                <div class="chat-header">
                    <div class="user-info">
                        <img src="<?= BASE_URL . ($activeConversation['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                             alt="Avatar" class="avatar">
                        <div>
                            <span class="name"><?= htmlspecialchars($activeConversation['user']['name'] ?? $activeConversation['user_name'] ?? 'Khách') ?></span>
                            <span class="status <?= ($activeConversation['user']['is_online'] ?? false) ? 'online' : 'offline' ?>">
                                <?= ($activeConversation['user']['is_online'] ?? false) ? 'Đang hoạt động' : 'Offline' ?>
                            </span>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="btn-icon" title="Xem thông tin khách hàng" id="viewUserInfo">
                            <i class="fas fa-user"></i>
                        </button>
                        <button class="btn-icon" title="Xem đơn hàng" id="viewUserOrders">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                </div>

                <div class="messages-container" id="messagesContainer">
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= $message['sender_type'] == 'employee' ? 'sent' : 'received' ?>">
                            <?php if ($message['sender_type'] == 'user'): ?>
                                <img src="<?= BASE_URL . ($activeConversation['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                                     alt="Avatar" class="avatar">
                            <?php endif; ?>
                            <div class="message-content">
                                <?php if (!empty($message['image'])): ?>
                                    <img src="<?= BASE_URL . $message['image'] ?>" alt="Image" class="message-image">
                                <?php endif; ?>
                                <?php if (!empty($message['content'])): ?>
                                    <p><?= nl2br(htmlspecialchars($message['content'])) ?></p>
                                <?php endif; ?>
                                <span class="time"><?= date('H:i', strtotime($message['created_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="chat-input">
                    <div class="quick-replies">
                        <button class="quick-reply-btn" data-text="Xin chào! TechShop có thể giúp gì cho bạn?">
                            👋 Chào
                        </button>
                        <button class="quick-reply-btn" data-text="Cảm ơn bạn đã liên hệ. Chúng tôi sẽ hỗ trợ bạn ngay.">
                            🙏 Cảm ơn
                        </button>
                        <button class="quick-reply-btn" data-text="Vui lòng cho biết mã đơn hàng để chúng tôi kiểm tra.">
                            📦 Mã đơn
                        </button>
                        <button class="quick-reply-btn" data-text="Bạn có thể liên hệ hotline 1900xxxx để được hỗ trợ nhanh hơn.">
                            📞 Hotline
                        </button>
                    </div>
                    <form id="messageForm" class="input-area">
                        <input type="hidden" name="conversation_id" value="<?= $activeConversation['id'] ?>">
                        <button type="button" class="btn-attach" id="attachBtn">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <input type="file" id="imageInput" accept="image/*" hidden>
                        <textarea name="content" id="messageInput" placeholder="Nhập tin nhắn..." rows="1"></textarea>
                        <button type="submit" class="btn-send">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    <div id="imagePreview" class="image-preview" style="display: none;">
                        <img src="" alt="Preview">
                        <button type="button" class="remove-image"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-conversation">
                    <i class="fas fa-comments"></i>
                    <h4>Chọn một hội thoại</h4>
                    <p>Chọn hội thoại từ danh sách bên trái để bắt đầu hỗ trợ khách hàng</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Info Panel -->
        <div class="user-info-panel" id="userInfoPanel" style="display: none;">
            <?php if ($activeConversation): ?>
                <div class="panel-header">
                    <h5>Thông tin khách hàng</h5>
                    <button class="btn-close-panel" id="closeUserPanel">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="panel-body">
                    <div class="user-profile">
                        <img src="<?= BASE_URL . ($activeConversation['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                             alt="Avatar">
                        <h6><?= htmlspecialchars($activeConversation['user']['name'] ?? $activeConversation['user_name'] ?? 'Khách') ?></h6>
                        <p><?= htmlspecialchars($activeConversation['user']['email'] ?? '') ?></p>
                    </div>
                    
                    <div class="info-section">
                        <h6>Thông tin liên hệ</h6>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span><?= htmlspecialchars($activeConversation['user']['email'] ?? 'N/A') ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span><?= htmlspecialchars($activeConversation['user']['phone'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <h6>Thống kê</h6>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="value"><?= $userStats['total_orders'] ?? 0 ?></span>
                                <span class="label">Đơn hàng</span>
                            </div>
                            <div class="stat-item">
                                <span class="value"><?= formatPrice($userStats['total_spent'] ?? 0) ?></span>
                                <span class="label">Đã chi tiêu</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <h6>Đơn hàng gần đây</h6>
                        <div class="recent-orders">
                            <?php foreach ($userRecentOrders ?? [] as $order): ?>
                                <a href="?page=order-detail&id=<?= $order['id'] ?>" class="order-item">
                                    <span class="order-code">#<?= $order['order_code'] ?></span>
                                    <span class="order-status <?= $order['status'] ?>"><?= ORDER_STATUSES[$order['status']] ?></span>
                                    <span class="order-total"><?= formatPrice($order['total']) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Chat Page Layout */
.chat-page {
    padding: 0 !important;
    height: calc(100vh - 70px);
}

.chat-container {
    display: flex;
    height: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
}

/* Conversations Panel */
.conversations-panel {
    width: 320px;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
}

.conversations-panel .panel-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.conversations-panel .panel-header h5 {
    margin: 0 0 15px;
    font-weight: 600;
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

.search-box input {
    width: 100%;
    padding: 10px 12px 10px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
}

.conversation-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
    border-bottom: 1px solid #f1f5f9;
}

.conversation-item:hover {
    background: #f8fafc;
}

.conversation-item.active {
    background: #fef2f2;
    border-left: 3px solid #e53935;
}

.conversation-item.unread {
    background: #fff7ed;
}

.conversation-item .avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.conversation-item .conv-info {
    flex: 1;
    min-width: 0;
}

.conversation-item .conv-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 4px;
}

.conversation-item .name {
    font-weight: 500;
    color: #1e293b;
}

.conversation-item .time {
    font-size: 12px;
    color: #94a3b8;
}

.conversation-item .last-message {
    margin: 0;
    font-size: 13px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-item .unread-badge {
    background: #e53935;
    color: #fff;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 50px;
    font-weight: 600;
}

.empty-conversations {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.empty-conversations i {
    font-size: 48px;
    margin-bottom: 15px;
}

/* Chat Panel */
.chat-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
}

.chat-header .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.chat-header .avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-header .name {
    display: block;
    font-weight: 600;
    color: #1e293b;
}

.chat-header .status {
    font-size: 12px;
}

.chat-header .status.online {
    color: #10b981;
}

.chat-header .status.offline {
    color: #94a3b8;
}

.chat-header .btn-icon {
    width: 40px;
    height: 40px;
    border: none;
    background: #f1f5f9;
    border-radius: 8px;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s;
}

.chat-header .btn-icon:hover {
    background: #e53935;
    color: #fff;
}

/* Messages */
.messages-container {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8fafc;
}

.message {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    max-width: 70%;
}

.message.sent {
    margin-left: auto;
    flex-direction: row-reverse;
}

.message .avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    align-self: flex-end;
}

.message-content {
    background: #fff;
    padding: 12px 16px;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.message.sent .message-content {
    background: #e53935;
    color: #fff;
}

.message-content p {
    margin: 0;
    line-height: 1.5;
}

.message-content .time {
    display: block;
    font-size: 11px;
    margin-top: 5px;
    opacity: 0.7;
}

.message-image {
    max-width: 250px;
    border-radius: 8px;
    margin-bottom: 8px;
}

/* Chat Input */
.chat-input {
    padding: 15px 20px;
    border-top: 1px solid #e2e8f0;
}

.quick-replies {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.quick-reply-btn {
    padding: 6px 12px;
    background: #f1f5f9;
    border: none;
    border-radius: 50px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.quick-reply-btn:hover {
    background: #e53935;
    color: #fff;
}

.input-area {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border-radius: 12px;
    padding: 8px 12px;
}

.input-area .btn-attach {
    width: 40px;
    height: 40px;
    border: none;
    background: none;
    color: #64748b;
    cursor: pointer;
    font-size: 18px;
}

.input-area textarea {
    flex: 1;
    border: none;
    background: none;
    resize: none;
    font-size: 14px;
    max-height: 100px;
}

.input-area textarea:focus {
    outline: none;
}

.input-area .btn-send {
    width: 44px;
    height: 44px;
    border: none;
    background: #e53935;
    color: #fff;
    border-radius: 10px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.input-area .btn-send:hover {
    background: #c62828;
}

.image-preview {
    margin-top: 10px;
    position: relative;
    display: inline-block;
}

.image-preview img {
    max-height: 100px;
    border-radius: 8px;
}

.image-preview .remove-image {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border: none;
    background: #e53935;
    color: #fff;
    border-radius: 50%;
    cursor: pointer;
    font-size: 12px;
}

/* No Conversation */
.no-conversation {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
}

.no-conversation i {
    font-size: 64px;
    margin-bottom: 20px;
}

.no-conversation h4 {
    color: #64748b;
    margin-bottom: 10px;
}

/* User Info Panel */
.user-info-panel {
    width: 320px;
    border-left: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
}

.user-info-panel .panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.user-info-panel .panel-header h5 {
    margin: 0;
    font-weight: 600;
}

.btn-close-panel {
    width: 32px;
    height: 32px;
    border: none;
    background: #f1f5f9;
    border-radius: 8px;
    cursor: pointer;
}

.panel-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.user-profile {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 20px;
}

.user-profile img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}

.user-profile h6 {
    margin: 0;
    font-weight: 600;
}

.user-profile p {
    margin: 5px 0 0;
    color: #94a3b8;
    font-size: 14px;
}

.info-section {
    margin-bottom: 20px;
}

.info-section h6 {
    font-size: 12px;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
}

.info-item i {
    width: 20px;
    color: #64748b;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
}

.stat-item .value {
    display: block;
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.stat-item .label {
    font-size: 12px;
    color: #94a3b8;
}

.recent-orders .order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: #f8fafc;
    border-radius: 8px;
    margin-bottom: 8px;
    text-decoration: none;
    color: inherit;
}

.recent-orders .order-code {
    font-weight: 500;
    color: #e53935;
}

.recent-orders .order-status {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 50px;
}

.recent-orders .order-status.pending { background: #fef3c7; color: #d97706; }
.recent-orders .order-status.processing { background: #dbeafe; color: #2563eb; }
.recent-orders .order-status.shipping { background: #ede9fe; color: #7c3aed; }
.recent-orders .order-status.delivered { background: #d1fae5; color: #059669; }

@media (max-width: 992px) {
    .user-info-panel {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .conversations-panel {
        width: 100%;
        position: absolute;
        z-index: 10;
        background: #fff;
    }
    
    .chat-panel {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    const conversationsList = document.getElementById('conversationsList');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const userInfoPanel = document.getElementById('userInfoPanel');
    
    // Auto scroll to bottom
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Switch conversation
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.addEventListener('click', function() {
            const convId = this.dataset.id;
            window.location.href = '?page=chat&id=' + convId;
        });
    });
    
    // Send message
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const content = messageInput.value.trim();
            if (!content) return;
            
            const formData = new FormData(this);
            formData.append('action', 'send');
            
            // Add image if exists
            const imageInput = document.getElementById('imageInput');
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            fetch('<?= BASE_URL ?>api/employee/chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to UI
                    appendMessage(data.message);
                    messageInput.value = '';
                    document.getElementById('imagePreview').style.display = 'none';
                    imageInput.value = '';
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            });
        });
    }
    
    // Quick replies
    document.querySelectorAll('.quick-reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            messageInput.value = this.dataset.text;
            messageInput.focus();
        });
    });
    
    // Image upload
    const attachBtn = document.getElementById('attachBtn');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    
    if (attachBtn) {
        attachBtn.addEventListener('click', () => imageInput.click());
        
        imageInput.addEventListener('change', function() {
            if (this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.querySelector('img').src = e.target.result;
                    imagePreview.style.display = 'inline-block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        imagePreview.querySelector('.remove-image').addEventListener('click', function() {
            imagePreview.style.display = 'none';
            imageInput.value = '';
        });
    }
    
    // Auto-resize textarea
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
    }
    
    // Toggle user info panel
    const viewUserInfo = document.getElementById('viewUserInfo');
    const closeUserPanel = document.getElementById('closeUserPanel');
    
    if (viewUserInfo) {
        viewUserInfo.addEventListener('click', function() {
            userInfoPanel.style.display = userInfoPanel.style.display === 'none' ? 'flex' : 'none';
        });
    }
    
    if (closeUserPanel) {
        closeUserPanel.addEventListener('click', function() {
            userInfoPanel.style.display = 'none';
        });
    }
    
    // Search conversations
    const searchInput = document.getElementById('searchConversation');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.conversation-item').forEach(item => {
                const name = item.querySelector('.name').textContent.toLowerCase();
                item.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });
    }
    
    // Append message to UI
    function appendMessage(message) {
        const div = document.createElement('div');
        div.className = 'message sent';
        div.innerHTML = `
            <div class="message-content">
                ${message.image ? '<img src="' + message.image + '" alt="Image" class="message-image">' : ''}
                <p>${message.content.replace(/\n/g, '<br>')}</p>
                <span class="time">${new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</span>
            </div>
        `;
        messagesContainer.appendChild(div);
    }
    
    // Poll for new messages (simple implementation)
    <?php if ($activeConversation): ?>
    setInterval(function() {
        fetch('<?= BASE_URL ?>api/employee/chat.php?action=check_new&conversation_id=<?= $activeConversation['id'] ?>&last_id=' + getLastMessageId())
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    if (msg.sender_type === 'user') {
                        appendReceivedMessage(msg);
                    }
                });
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    }, 5000);
    
    function getLastMessageId() {
        const messages = document.querySelectorAll('.message');
        return messages.length > 0 ? messages[messages.length - 1].dataset.id || 0 : 0;
    }
    
    function appendReceivedMessage(message) {
        const div = document.createElement('div');
        div.className = 'message received';
        div.dataset.id = message._id;
        div.innerHTML = `
            <img src="<?= BASE_URL . ($activeConversation['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                 alt="Avatar" class="avatar">
            <div class="message-content">
                ${message.image ? '<img src="' + message.image + '" alt="Image" class="message-image">' : ''}
                <p>${message.content.replace(/\n/g, '<br>')}</p>
                <span class="time">${new Date(message.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</span>
            </div>
        `;
        messagesContainer.appendChild(div);
    }
    <?php endif; ?>
});
</script>

<?php include __DIR__ . '/../layouts/employee-footer.php'; ?>


