<?php
$pageTitle = 'Chat hỗ trợ';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content chat-page">
    <div class="row g-0 chat-container">
        <!-- Conversations List -->
        <div class="col-md-4 chat-sidebar">
            <div class="chat-sidebar-header">
                <h5><i class="fas fa-comments me-2"></i>Tin nhắn</h5>
                <div class="chat-filter">
                    <select class="form-select form-select-sm" id="chatFilter">
                        <option value="all">Tất cả</option>
                        <option value="pending">Chưa trả lời</option>
                        <option value="active">Đang chat</option>
                        <option value="closed">Đã đóng</option>
                    </select>
                </div>
            </div>
            <div class="chat-search">
                <input type="text" class="form-control" placeholder="Tìm kiếm..." id="chatSearch">
            </div>
            <div class="conversation-list" id="conversationList">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv): ?>
                    <div class="conversation-item <?= ($activeConversation['id'] ?? null) == $conv['id'] ? 'active' : '' ?>" 
                         data-id="<?= $conv['id'] ?>">
                        <div class="avatar">
                            <img src="<?= BASE_URL . ($conv['user_avatar'] ?? 'assets/images/default-avatar.svg') ?>" alt="">
                            <?php if ($conv['status'] == 'pending'): ?>
                            <span class="status-dot pending"></span>
                            <?php endif; ?>
                        </div>
                        <div class="conv-info">
                            <div class="conv-header">
                                <span class="conv-name"><?= htmlspecialchars($conv['user_name'] ?? 'Khách') ?></span>
                                <span class="conv-time"><?= timeAgo($conv['last_message_at'] ?? $conv['created_at']) ?></span>
                            </div>
                            <p class="conv-preview"><?= htmlspecialchars(mb_substr($conv['last_message'] ?? '', 0, 50)) ?></p>
                        </div>
                        <?php if (($conv['unread_count'] ?? 0) > 0): ?>
                        <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-conversations">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có tin nhắn nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8 chat-main">
            <?php if (!empty($activeConversation)): ?>
            <div class="chat-header">
                <div class="chat-user-info">
                    <img src="<?= BASE_URL . ($activeConversation['user_avatar'] ?? 'assets/images/default-avatar.svg') ?>" alt="">
                    <div>
                        <h6><?= htmlspecialchars($activeConversation['user_name'] ?? 'Khách hàng') ?></h6>
                        <span class="user-status online">Đang hoạt động</span>
                    </div>
                </div>
                <div class="chat-actions">
                    <button class="btn btn-sm btn-outline-secondary" title="Thông tin khách hàng" data-bs-toggle="offcanvas" data-bs-target="#customerInfo">
                        <i class="fas fa-user"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" title="Xóa cuộc hội thoại" onclick="deleteConversation(<?= $activeConversation['id'] ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $msg): ?>
                    <div class="message <?= $msg['sender_type'] == 'user' ? 'incoming' : 'outgoing' ?>">
                        <div class="message-content">
                            <p><?= nl2br(htmlspecialchars($msg['content'])) ?></p>
                            <span class="message-time"><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="chat-input">
                <form id="chatForm" onsubmit="sendMessage(event)">
                    <input type="hidden" name="conversation_id" value="<?= $activeConversation['id'] ?>">
                    <div class="input-group">
                        <button type="button" class="btn btn-light" title="Đính kèm file">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <input type="text" class="form-control" name="message" placeholder="Nhập tin nhắn..." autocomplete="off">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <div class="chat-empty">
                <i class="fas fa-comments"></i>
                <h5>Chọn một cuộc hội thoại</h5>
                <p>Chọn cuộc hội thoại từ danh sách bên trái để bắt đầu trả lời</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Customer Info Offcanvas -->
<div class="offcanvas offcanvas-end" id="customerInfo">
    <div class="offcanvas-header">
        <h5>Thông tin khách hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <?php if (!empty($activeConversation)): ?>
        <div class="customer-profile text-center mb-4">
            <img src="<?= BASE_URL . ($activeConversation['user_avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                 class="rounded-circle mb-3" width="80" height="80">
            <h6><?= htmlspecialchars($activeConversation['user_name'] ?? 'Khách') ?></h6>
            <p class="text-muted"><?= htmlspecialchars($activeConversation['user_email'] ?? '') ?></p>
        </div>
        <div class="customer-stats">
            <div class="stat-item">
                <span class="stat-label">Tổng đơn hàng</span>
                <span class="stat-value"><?= $userStats['total_orders'] ?? 0 ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Tổng chi tiêu</span>
                <span class="stat-value"><?= formatPrice($userStats['total_spent'] ?? 0) ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Thành viên từ</span>
                <span class="stat-value"><?= formatDate($activeConversation['user_created_at'] ?? '') ?></span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.chat-page {
    height: calc(100vh - 120px);
}
.chat-container {
    height: 100%;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.chat-sidebar {
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
}
.chat-sidebar-header {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chat-sidebar-header h5 {
    margin: 0;
    font-size: 1rem;
}
.chat-search {
    padding: 10px 15px;
    border-bottom: 1px solid #e5e7eb;
}
.conversation-list {
    flex: 1;
    overflow-y: auto;
}
.conversation-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background 0.2s;
}
.conversation-item:hover,
.conversation-item.active {
    background-color: #f3f4f6;
}
.conversation-item .avatar {
    position: relative;
    margin-right: 12px;
}
.conversation-item .avatar img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}
.status-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid white;
}
.status-dot.pending {
    background-color: #f59e0b;
}
.conv-info {
    flex: 1;
    min-width: 0;
}
.conv-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}
.conv-name {
    font-weight: 600;
    font-size: 0.9rem;
}
.conv-time {
    font-size: 0.75rem;
    color: #9ca3af;
}
.conv-preview {
    font-size: 0.85rem;
    color: #6b7280;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.unread-badge {
    background: #ef4444;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 8px;
}
.chat-main {
    display: flex;
    flex-direction: column;
    height: 100%;
}
.chat-header {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chat-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.chat-user-info img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
.chat-user-info h6 {
    margin: 0;
    font-size: 0.95rem;
}
.user-status {
    font-size: 0.8rem;
    color: #10b981;
}
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background-color: #f9fafb;
}
.message {
    display: flex;
    margin-bottom: 15px;
}
.message.incoming {
    justify-content: flex-start;
}
.message.outgoing {
    justify-content: flex-end;
}
.message-content {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 15px;
}
.message.incoming .message-content {
    background: white;
    border: 1px solid #e5e7eb;
}
.message.outgoing .message-content {
    background: #3b82f6;
    color: white;
}
.message-content p {
    margin: 0 0 5px;
}
.message-time {
    font-size: 0.7rem;
    opacity: 0.7;
}
.chat-input {
    padding: 15px;
    border-top: 1px solid #e5e7eb;
    background: white;
}
.chat-input .input-group {
    gap: 10px;
}
.chat-input .form-control {
    border-radius: 20px;
}
.chat-input .btn-primary {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 0;
}
.chat-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #9ca3af;
}
.chat-empty i {
    font-size: 4rem;
    margin-bottom: 20px;
}
.empty-conversations {
    padding: 40px 20px;
    text-align: center;
    color: #9ca3af;
}
.empty-conversations i {
    font-size: 3rem;
    margin-bottom: 15px;
}
.customer-stats .stat-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e5e7eb;
}
</style>

<script>
function sendMessage(e) {
    e.preventDefault();
    const form = e.target;
    const input = form.querySelector('input[name="message"]');
    const message = input.value.trim();
    
    if (!message) return;
    
    const conversationId = form.querySelector('input[name="conversation_id"]').value;
    
    // Add message to UI immediately
    const messagesContainer = document.getElementById('chatMessages');
    messagesContainer.innerHTML += `
        <div class="message outgoing">
            <div class="message-content">
                <p>${message}</p>
                <span class="message-time">${new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</span>
            </div>
        </div>
    `;
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    input.value = '';
    
    // Send to server
    fetch('<?= BASE_URL ?>api/employee/chat.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'send',
            conversation_id: conversationId,
            message: message
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log('Send response:', data);
        if (!data.success) {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Send error:', err);
        alert('Có lỗi xảy ra khi gửi tin nhắn');
    });
}

function closeConversation(id) {
    if (confirm('Đóng cuộc hội thoại này?')) {
        fetch('<?= BASE_URL ?>api/employee/chat.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                action: 'close',
                conversation_id: id
            })
        }).then(() => location.reload());
    }
}

function deleteConversation(id) {
    Swal.fire({
        title: 'Xóa cuộc hội thoại?',
        text: 'Toàn bộ tin nhắn trong cuộc hội thoại sẽ bị xóa vĩnh viễn!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= BASE_URL ?>api/employee/chat.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'delete',
                    conversation_id: id,
                    csrf_token: '<?= getToken() ?>'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa!',
                        text: 'Cuộc hội thoại đã được xóa.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '<?= BASE_URL ?>admin?page=chats';
                    });
                } else {
                    Swal.fire('Lỗi!', data.message || 'Không thể xóa cuộc hội thoại', 'error');
                }
            })
            .catch(err => {
                console.error('Delete error:', err);
                Swal.fire('Lỗi!', 'Có lỗi xảy ra khi xóa', 'error');
            });
        }
    });
}

// Click conversation
document.querySelectorAll('.conversation-item').forEach(item => {
    item.addEventListener('click', function() {
        const id = this.dataset.id;
        window.location.href = '<?= BASE_URL ?>admin?page=chats&conv=' + id;
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
