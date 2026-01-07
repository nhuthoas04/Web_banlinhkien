<?php 
$pageTitle = 'Quen mat khau - ' . SITE_NAME;
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
                <svg viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="forgot-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea"/>
                            <stop offset="100%" style="stop-color:#764ba2"/>
                        </linearGradient>
                    </defs>
                    <rect width="400" height="300" fill="#f8f9fa"/>
                    <circle cx="200" cy="150" r="80" fill="url(#forgot-grad)" opacity="0.1"/>
                    <circle cx="200" cy="150" r="60" fill="url(#forgot-grad)" opacity="0.2"/>
                    <rect x="160" y="110" width="80" height="60" rx="5" fill="url(#forgot-grad)"/>
                    <circle cx="200" cy="100" r="15" fill="url(#forgot-grad)"/>
                    <path d="M170 150 L200 170 L230 150" stroke="white" stroke-width="3" fill="none"/>
                    <circle cx="200" cy="200" r="25" fill="url(#forgot-grad)" opacity="0.3"/>
                    <text x="200" y="207" text-anchor="middle" fill="#667eea" font-size="20">?</text>
                </svg>
            </div>
            
            <div class="auth-features">
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Bao mat tuyet doi</span>
                </div>
                <div class="feature">
                    <i class="fas fa-envelope"></i>
                    <span>Xac thuc qua email</span>
                </div>
                <div class="feature">
                    <i class="fas fa-lock"></i>
                    <span>Dat lai mat khau de dang</span>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Quen mat khau</h2>
                    <p>Nhap email de nhan huong dan dat lai mat khau</p>
                </div>
                
                <?php if (isset($message)): ?>
                <div class="alert alert-<?= $messageType ?? 'info' ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check' : 'exclamation' ?>-circle"></i> <?= $message ?>
                </div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>forgot-password" method="POST" class="auth-form" id="forgotForm">
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Nhap email cua ban"
                               required
                               autofocus>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-auth">
                        <i class="fas fa-paper-plane"></i> Gui yeu cau
                    </button>
                </form>
                
                <div class="auth-footer">
                    <p>Nho mat khau? 
                        <a href="<?= BASE_URL ?>login">Dang nhap</a>
                    </p>
                    <p>Chua co tai khoan? 
                        <a href="<?= BASE_URL ?>register">Dang ky ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

