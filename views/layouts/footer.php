    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main py-4">
            <div class="container">
                <div class="row">
                    <!-- About -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="footer-widget">
                            <div class="footer-logo mb-2">
                                <div class="footer-logo-icon">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                <div class="footer-logo-text">
                                    <span class="footer-brand">TechStore</span>
                                    <span class="footer-slogan">Linh kiện máy tính</span>
                                </div>
                            </div>
                            <p class="footer-desc small mb-2">
                                Chuyên cung cấp thiết bị và phụ kiện máy tính chính hãng.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link youtube"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="social-link tiktok"><i class="fab fa-tiktok"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-3">
                        <div class="footer-widget">
                            <h6 class="footer-title">Liên kết</h6>
                            <ul class="footer-links compact">
                                <li><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                                <li><a href="<?= BASE_URL ?>products">Sản phẩm</a></li>
                                <li><a href="<?= BASE_URL ?>about">Giới thiệu</a></li>
                                <li><a href="<?= BASE_URL ?>contact">Liên hệ</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="col-lg-2 col-md-6 mb-3">
                        <div class="footer-widget">
                            <h6 class="footer-title">Danh mục</h6>
                            <ul class="footer-links compact">
                                <li><a href="<?= BASE_URL ?>products?category=1">Laptop</a></li>
                                <li><a href="<?= BASE_URL ?>products?category=2">PC Gaming</a></li>
                                <li><a href="<?= BASE_URL ?>products?category=3">Linh kiện</a></li>
                                <li><a href="<?= BASE_URL ?>products?category=4">Phụ kiện</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="footer-widget">
                            <h6 class="footer-title">Liên hệ</h6>
                            <ul class="footer-contact compact">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= SITE_ADDRESS ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-phone-alt"></i>
                                    <span><?= SITE_PHONE ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span><?= SITE_EMAIL ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Map -->
                    <div class="col-lg-2 col-md-6 mb-3">
                        <div class="footer-widget">
                            <h6 class="footer-title">Vị trí</h6>
                            <div class="footer-map">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.6444755825943!2d106.34162407569496!3d9.93676459016344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0176a78dc0acf%3A0x85c42b38d72e4ec6!2zMTI2IE5ndXnhu4VuIFRoaeG7h24gVGjDoG5oLCBQaMaw4budbmcgNSwgVHLDoCBWaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1736348100000!5m2!1svi!2s"
                                    width="100%" 
                                    height="120" 
                                    style="border:0; border-radius: 8px;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom py-2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="copyright">
                            © <?= date('Y') ?> <strong>TechStore</strong> - Thiết Bị Máy Tính. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-bottom-links">
                            <a href="<?= BASE_URL ?>chinh-sach-bao-mat">Chính sách bảo mật</a>
                            <span>|</span>
                            <a href="<?= BASE_URL ?>dieu-khoan-su-dung">Điều khoản sử dụng</a>
                            <span>|</span>
                            <a href="<?= BASE_URL ?>chinh-sach-doi-tra">Đổi trả</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>
    
    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xem nhanh sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quickViewContent">
                    <!-- Product content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Base URL for JS - defined before main.js loads -->
    <script>
        var BASE_URL = '<?= BASE_URL ?>';
        var ASSETS_URL = '<?= ASSETS_URL ?>';
    </script>
    
    <!-- Floating Chat Button -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="floating-chat">
        <div class="chat-popup" id="chatPopup">
            <div class="chat-popup-header">
                <div class="chat-popup-title">
                    <i class="fas fa-headset"></i>
                    <span>Hỗ trợ trực tuyến</span>
                </div>
                <button class="chat-popup-close" onclick="toggleChatPopup()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chat-popup-body" id="chatPopupMessages">
                <div class="chat-welcome">
                    <div class="chat-welcome-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <p>Xin chào! Bạn cần hỗ trợ gì?</p>
                </div>
            </div>
            <div class="chat-popup-footer">
                <form id="chatPopupForm" onsubmit="sendChatMessage(event)">
                    <input type="text" id="chatPopupInput" placeholder="Nhập tin nhắn..." autocomplete="off">
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <button class="chat-toggle-btn" id="chatToggleBtn" onclick="toggleChatPopup()">
            <i class="fas fa-comments"></i>
            <span class="chat-badge" id="chatBadge" style="display: none;">0</span>
        </button>
    </div>
    
    <style>
    .floating-chat {
        position: fixed;
        bottom: 30px;
        right: 100px;
        z-index: 9999;
    }
    
    .chat-toggle-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        border: none;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(185, 28, 28, 0.4);
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    
    .chat-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(185, 28, 28, 0.5);
        color: #fff;
    }
    
    .chat-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .chat-popup {
        position: absolute;
        bottom: 75px;
        right: 0;
        width: 380px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s ease;
    }
    
    .chat-popup.active {
        display: flex;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .chat-popup-header {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #fff;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chat-popup-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 16px;
    }
    
    .chat-popup-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .chat-popup-close:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .chat-popup-body {
        height: 350px;
        overflow-y: auto;
        padding: 15px;
        background: #f8fafc;
    }
    
    .chat-welcome {
        text-align: center;
        padding: 40px 20px;
    }
    
    .chat-welcome-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: #fff;
        font-size: 24px;
    }
    
    .chat-welcome p {
        color: #64748b;
        margin: 0;
    }
    
    .chat-popup-footer {
        padding: 15px;
        background: #fff;
        border-top: 1px solid #e2e8f0;
    }
    
    .chat-popup-footer form {
        display: flex;
        gap: 10px;
    }
    
    .chat-popup-footer input {
        flex: 1;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 25px;
        outline: none;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    
    .chat-popup-footer input:focus {
        border-color: #dc2626;
    }
    
    .chat-popup-footer button {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        border: none;
        color: #fff;
        cursor: pointer;
        transition: transform 0.3s;
    }
    
    .chat-popup-footer button:hover {
        transform: scale(1.05);
    }
    
    /* Chat messages */
    .chat-msg {
        margin-bottom: 15px;
        display: flex;
    }
    
    .chat-msg.sent {
        justify-content: flex-end;
    }
    
    .chat-msg-content {
        max-width: 85%;
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
    }
    
    .chat-msg.received .chat-msg-content {
        background: #fff;
        color: #333;
        border-bottom-left-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .chat-msg.sent .chat-msg-content {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #fff;
        border-bottom-right-radius: 5px;
    }
    
    .chat-msg-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 5px;
    }
    
    .chat-msg.sent .chat-msg-time {
        text-align: right;
    }
    
    /* Responsive */
    @media (max-width: 480px) {
        .floating-chat {
            bottom: 20px;
            right: 20px;
        }
        
        .chat-popup {
            width: calc(100vw - 40px);
            right: 0;
        }
        
        .chat-toggle-btn {
            width: 55px;
            height: 55px;
            font-size: 22px;
        }
    }
    </style>
    
    <script>
    let chatConversationId = null;
    let chatPollingInterval = null;
    
    function toggleChatPopup() {
        const popup = document.getElementById('chatPopup');
        const btn = document.getElementById('chatToggleBtn');
        
        popup.classList.toggle('active');
        
        if (popup.classList.contains('active')) {
            btn.innerHTML = '<i class="fas fa-times"></i>';
            loadChatMessages();
            startChatPolling();
        } else {
            btn.innerHTML = '<i class="fas fa-comments"></i><span class="chat-badge" id="chatBadge" style="display: none;">0</span>';
            stopChatPolling();
        }
    }
    
    function loadChatMessages() {
        fetch(BASE_URL + 'api/contact.php?action=get_messages')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    chatConversationId = data.conversation_id;
                    renderChatMessages(data.messages || []);
                }
            })
            .catch(err => console.error('Chat error:', err));
    }
    
    function renderChatMessages(messages) {
        const container = document.getElementById('chatPopupMessages');
        
        if (messages.length === 0) {
            container.innerHTML = `
                <div class="chat-welcome">
                    <div class="chat-welcome-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <p>Xin chào! Bạn cần hỗ trợ gì?</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        messages.forEach(msg => {
            const isSent = msg.sender_type === 'user';
            const content = msg.content || '';
            const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'});
            html += `
                <div class="chat-msg ${isSent ? 'sent' : 'received'}">
                    <div>
                        <div class="chat-msg-content">${escapeHtml(content)}</div>
                        <div class="chat-msg-time">${time}</div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
        container.scrollTop = container.scrollHeight;
    }
    
    function sendChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatPopupInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        // Add message to UI immediately
        const container = document.getElementById('chatPopupMessages');
        const welcomeDiv = container.querySelector('.chat-welcome');
        if (welcomeDiv) welcomeDiv.remove();
        
        const time = new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'});
        container.innerHTML += `
            <div class="chat-msg sent">
                <div>
                    <div class="chat-msg-content">${escapeHtml(message)}</div>
                    <div class="chat-msg-time">${time}</div>
                </div>
            </div>
        `;
        container.scrollTop = container.scrollHeight;
        input.value = '';
        
        // Send to server
        const formData = new FormData();
        formData.append('action', 'send_message');
        formData.append('message', message);
        if (chatConversationId) {
            formData.append('conversation_id', chatConversationId);
        }
        
        fetch(BASE_URL + 'api/contact.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.conversation_id) {
                chatConversationId = data.conversation_id;
            }
        })
        .catch(err => console.error('Send error:', err));
    }
    
    function startChatPolling() {
        chatPollingInterval = setInterval(loadChatMessages, 3000);
    }
    
    function stopChatPolling() {
        if (chatPollingInterval) {
            clearInterval(chatPollingInterval);
            chatPollingInterval = null;
        }
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    </script>
    <?php else: ?>
    <!-- Chat button for guests - redirect to login -->
    <div class="floating-chat">
        <a href="<?= BASE_URL ?>login" class="chat-toggle-btn" title="Đăng nhập để chat">
            <i class="fas fa-comments"></i>
        </a>
    </div>
    <?php endif; ?>
    
    <!-- Custom JS -->
    <script src="<?= ASSETS_URL ?>/js/main.js?v=20260107"></script>
    
    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <script src="<?= ASSETS_URL ?>/js/<?= $js ?>?v=20260107"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
        // Hide preloader when page is loaded
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 300);
            }
        });
        
        // Fallback: hide preloader after 3 seconds anyway
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        }, 3000);
        
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>


