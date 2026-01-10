<?php 
$pageTitle = 'Đăng nhập - ' . SITE_NAME;
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
                <i class="fas fa-laptop-code fa-6x" style="color: rgba(255,255,255,0.3);"></i>
            </div>
            
            <div class="auth-features">
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Bảo mật tuyệt đối</span>
                </div>
                <div class="feature">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Giao hàng nhanh</span>
                </div>
                <div class="feature">
                    <i class="fas fa-headset"></i>
                    <span>Hỗ trợ 24/7</span>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Đăng nhập</h2>
                    <p>Chào mừng bạn quay trở lại!</p>
                </div>
                
                <?php if ($error = flash('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success = flash('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>login" method="POST" class="auth-form" id="loginForm">
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Nhập email của bạn"
                               required
                               autofocus>
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
                                   placeholder="Nhập mật khẩu"
                                   required>
                            <button type="button" class="toggle-password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Ghi nhớ đăng nhập
                        </label>
                        <a href="<?= BASE_URL ?>forgot-password" class="forgot-link">
                            Quên mật khẩu?
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-auth">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </button>
                </form>
                
                <div class="auth-divider">
                    <span>Hoặc đăng nhập với</span>
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
                    <p>Chưa có tài khoản? 
                        <a href="<?= BASE_URL ?>register">Đăng ký ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
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
    </script>
</body>
</html>


