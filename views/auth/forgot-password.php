<?php 
$pageTitle = 'Quên mật khẩu - ' . SITE_NAME;
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
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .forgot-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 450px;
            width: 100%;
            padding: 45px 40px;
            text-align: center;
        }
        
        .forgot-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .forgot-icon i {
            font-size: 42px;
            color: #fff;
        }
        
        .forgot-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 12px;
        }
        
        .forgot-desc {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 24px;
            text-align: left;
        }
        
        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .form-control {
            padding: 15px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            width: 100%;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }
        
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            text-align: left;
            font-size: 14px;
        }
        
        .alert i {
            font-size: 18px;
            margin-top: 2px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: none;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: none;
        }
        
        .alert-info {
            background: #e0f2fe;
            color: #075985;
            border: none;
        }
        
        .back-links {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }
        
        .back-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .back-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .back-links p {
            margin: 10px 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .brand-link {
            position: fixed;
            top: 30px;
            left: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 22px;
            transition: transform 0.3s;
        }
        
        .brand-link:hover {
            color: #fff;
            transform: scale(1.05);
        }
        
        .brand-link i {
            font-size: 28px;
        }
        
        .spinner-border-sm {
            width: 1.2rem;
            height: 1.2rem;
        }
        
        .success-state .forgot-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        @media (max-width: 480px) {
            .forgot-card {
                padding: 35px 25px;
            }
            
            .forgot-icon {
                width: 85px;
                height: 85px;
            }
            
            .forgot-icon i {
                font-size: 36px;
            }
            
            .brand-link {
                top: 20px;
                left: 20px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <a href="<?= BASE_URL ?>" class="brand-link">
        <i class="fas fa-microchip"></i>
        <span>TechShop</span>
    </a>
    
    <div class="forgot-card <?= isset($messageType) && $messageType === 'success' ? 'success-state' : '' ?>">
        <div class="forgot-icon">
            <?php if (isset($messageType) && $messageType === 'success'): ?>
                <i class="fas fa-check"></i>
            <?php else: ?>
                <i class="fas fa-key"></i>
            <?php endif; ?>
        </div>
        
        <?php if (isset($messageType) && $messageType === 'success'): ?>
            <h1 class="forgot-title">Đã gửi email!</h1>
            <p class="forgot-desc">Vui lòng kiểm tra hộp thư của bạn và làm theo hướng dẫn để đặt lại mật khẩu. Email có thể mất vài phút để đến.</p>
            
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?= $message ?></span>
            </div>
            
            <div class="back-links" style="border: none; padding-top: 0; margin-top: 20px;">
                <a href="<?= BASE_URL ?>login" class="btn-submit" style="text-decoration: none; color: #fff;">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại Đăng nhập</span>
                </a>
            </div>
        <?php else: ?>
            <h1 class="forgot-title">Quên mật khẩu?</h1>
            <p class="forgot-desc">Đừng lo lắng! Nhập địa chỉ email đã đăng ký và chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu cho bạn.</p>
            
            <?php if (isset($message) && $messageType !== 'success'): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $message ?></span>
            </div>
            <?php endif; ?>
            
            <form action="<?= BASE_URL ?>forgot-password" method="POST" id="forgotForm">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Địa chỉ Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           placeholder="example@gmail.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required
                           autofocus>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane"></i>
                    <span>Gửi yêu cầu</span>
                </button>
            </form>
            
            <div class="back-links">
                <p><i class="fas fa-arrow-left"></i> Quay lại <a href="<?= BASE_URL ?>login">Đăng nhập</a></p>
                <p>Chưa có tài khoản? <a href="<?= BASE_URL ?>register">Đăng ký ngay</a></p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('forgotForm')?.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang gửi...';
        });
    </script>
</body>
</html>

