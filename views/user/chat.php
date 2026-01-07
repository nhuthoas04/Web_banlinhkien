<?php
$pageTitle = 'Hỗ trợ trực tuyến';
$currentPage = 'chat';
include __DIR__ . '/../layouts/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active">Hỗ trợ trực tuyến</li>
            </ol>
        </nav>
    </div>
</div>

<section class="chat-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php include __DIR__ . '/../components/account-sidebar.php'; ?>
            </div>

            <!-- Chat Content -->
            <div class="col-lg-9">
                <div class="chat-container">
                    <div class="section-header">
                        <h4><i class="fas fa-comments"></i> Hỗ trợ trực tuyến</h4>
                    </div>
                    
                    <div class="chat-box">
                        <!-- Messages -->
                        <div class="chat-messages" id="chatMessages">
                            <?php if (empty($messages)): ?>
                                <div class="text-center py-4">
                                    <p class="text-muted">Xin chào! Bạn cần hỗ trợ gì?</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($messages as $message): ?>
                                    <div class="message <?= $message['sender_type'] === 'user' ? 'message-sent' : 'message-received' ?>">
                                        <div class="message-content">
                                            <?= htmlspecialchars($message['message']) ?>
                                        </div>
                                        <div class="message-time">
                                            <?= date('H:i', strtotime($message['created_at'])) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Input -->
                        <div class="chat-input">
                            <form id="chatForm" class="d-flex gap-2">
                                <input type="hidden" name="conversation_id" value="<?= $conversation['id'] ?? '' ?>">
                                <input type="text" class="form-control" name="message" 
                                       placeholder="Nhập tin nhắn..." autocomplete="off" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.chat-container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    overflow: hidden;
}

.section-header {
    padding: 20px 25px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.section-header h4 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h4 i {
    color: var(--primary-color);
}

.chat-box {
    display: flex;
    flex-direction: column;
    height: 500px;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8fafc;
}

.message {
    max-width: 70%;
    margin-bottom: 15px;
}

.message-sent {
    margin-left: auto;
}

.message-received {
    margin-right: auto;
}

.message-content {
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
}

.message-sent .message-content {
    background: var(--primary-color);
    color: white;
    border-bottom-right-radius: 4px;
}

.message-received .message-content {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.message-time {
    font-size: 11px;
    color: #999;
    margin-top: 4px;
    text-align: right;
}

.message-received .message-time {
    text-align: left;
}

.chat-input {
    padding: 15px 20px;
    background: white;
    border-top: 1px solid #e2e8f0;
}

.chat-input .form-control {
    border-radius: 25px;
    padding: 10px 20px;
}

.chat-input .btn {
    border-radius: 50%;
    width: 45px;
    height: 45px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const message = formData.get('message').trim();
    
    if (!message) return;
    
    // Add message to UI
    const messagesDiv = document.getElementById('chatMessages');
    const messageHtml = `
        <div class="message message-sent">
            <div class="message-content">${message}</div>
            <div class="message-time">${new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</div>
        </div>
    `;
    messagesDiv.insertAdjacentHTML('beforeend', messageHtml);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    // Clear input
    this.querySelector('input[name="message"]').value = '';
    
    // Send to server
    fetch('<?= BASE_URL ?>api/chat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'send',
            conversation_id: formData.get('conversation_id'),
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Error sending message:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Scroll to bottom on load
document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
