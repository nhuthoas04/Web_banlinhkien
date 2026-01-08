<?php 
$pageTitle = 'Đăng ký - ' . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= ASSETS_URL ?>/css/auth.css?v=20260106002" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-brand">
                <a href="<?= BASE_URL ?>">
                    <i class="fas fa-microchip"></i>
                    <span>TechShop</span>
                </a>
            </div>
            
            <div class="auth-illustration">
                <i class="fas fa-user-plus fa-6x" style="color: rgba(255,255,255,0.3);"></i>
            </div>
            
            <div class="auth-features">
                <div class="feature">
                    <i class="fas fa-gift"></i>
                    <span>Ưu đãi thành viên</span>
                </div>
                <div class="feature">
                    <i class="fas fa-percent"></i>
                    <span>Giảm giá độc quyền</span>
                </div>
                <div class="feature">
                    <i class="fas fa-history"></i>
                    <span>Theo dõi đơn hàng</span>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Đăng ký</h2>
                    <p>Tạo tài khoản mới để mua sắm</p>
                </div>
                
                <?php if ($error = flash('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>register" method="POST" class="auth-form" id="registerForm">
                    <div class="form-group">
                        <label for="fullname">
                            <i class="fas fa-user"></i> Họ và tên
                        </label>
                        <input type="text" 
                               id="fullname" 
                               name="fullname" 
                               class="form-control" 
                               placeholder="Nhập họ và tên"
                               value="<?= $_POST['fullname'] ?? '' ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Nhập email của bạn"
                               value="<?= $_POST['email'] ?? '' ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Số điện thoại
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-control" 
                               placeholder="Nhập số điện thoại"
                               value="<?= $_POST['phone'] ?? '' ?>"
                               pattern="[0-9]{10,11}"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Mật khẩu
                        </label>
                        <div class="password-input">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Nhập mật khẩu (ít nhất 6 ký tự)"
                                   minlength="6"
                                   required>
                            <button type="button" class="toggle-password" data-target="password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-lock"></i> Xác nhận mật khẩu
                        </label>
                        <div class="password-input">
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   class="form-control" 
                                   placeholder="Nhập lại mật khẩu"
                                   required>
                            <button type="button" class="toggle-password" data-target="confirm_password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">
                            <i class="fas fa-map-marker-alt"></i> Địa chỉ (không bắt buộc)
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  class="form-control" 
                                  placeholder="Nhập địa chỉ của bạn"
                                  rows="2"><?= $_POST['address'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="form-options">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="agree" required>
                            <span class="checkmark"></span>
                            Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách bảo mật</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-auth">
                        <i class="fas fa-user-plus"></i> Đăng ký
                    </button>
                </form>
                
                <div class="auth-divider">
                    <span>Hoặc đăng ký với</span>
                </div>
                
                <div class="social-login">
                    <a href="<?= BASE_URL ?>google-login" class="btn btn-social btn-google">
                        <i class="fab fa-google"></i> Google
                    </a>
                    <button class="btn btn-social btn-facebook" disabled title="Sắp ra mắt">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </button>
                </div>
                
                <div class="auth-footer">
                    <p>Đã có tài khoản? 
                        <a href="<?= BASE_URL ?>login">Đăng nhập</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.querySelector('.strength-bar');
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'strength-bar';
            if (strength <= 2) strengthBar.classList.add('weak');
            else if (strength <= 3) strengthBar.classList.add('medium');
            else strengthBar.classList.add('strong');
            
            strengthBar.style.width = (strength * 20) + '%';
        });
        
        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
            }
        });
    </script>
</body>
</html>


