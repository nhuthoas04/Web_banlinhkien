<?php 
$pageTitle = 'Đặt lại mật khẩu - ' . SITE_NAME;
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
        
        .reset-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 450px;
            width: 100%;
            padding: 45px 40px;
            text-align: center;
        }
        
        .reset-icon {
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
        
        .reset-icon i {
            font-size: 42px;
            color: #fff;
        }
        
        .reset-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 12px;
        }
        
        .reset-desc {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 20px;
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
        
        .input-group-custom {
            position: relative;
        }
        
        .form-control {
            padding: 15px 50px 15px 18px;
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
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 5px;
            font-size: 18px;
            transition: color 0.3s;
        }
        
        .toggle-password:hover {
            color: #667eea;
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
            margin-top: 10px;
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
        
        .success-state .reset-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .password-requirements {
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .password-requirements h6 {
            font-size: 13px;
            color: #475569;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .password-requirements li {
            font-size: 13px;
            color: #64748b;
            padding: 3px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .password-requirements li i {
            font-size: 12px;
        }
        
        .password-requirements li.valid {
            color: #059669;
        }
        
        .password-requirements li.valid i {
            color: #059669;
        }
        
        @media (max-width: 480px) {
            .reset-card {
                padding: 35px 25px;
            }
            
            .reset-icon {
                width: 85px;
                height: 85px;
            }
            
            .reset-icon i {
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
    
    <div class="reset-card <?= (isset($messageType) && $messageType === 'success') ? 'success-state' : '' ?>">
        <div class="reset-icon">
            <?php if (isset($messageType) && $messageType === 'success'): ?>
                <i class="fas fa-check"></i>
            <?php elseif (isset($messageType) && $messageType === 'error'): ?>
                <i class="fas fa-times"></i>
            <?php else: ?>
                <i class="fas fa-lock"></i>
            <?php endif; ?>
        </div>
        
        <?php if (isset($messageType) && $messageType === 'success'): ?>
            <!-- Success State -->
            <h1 class="reset-title">Thành công!</h1>
            <p class="reset-desc">Mật khẩu của bạn đã được đặt lại thành công.</p>
            
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?= $message ?></span>
            </div>
            
            <a href="<?= BASE_URL ?>login" class="btn-submit" style="text-decoration: none;">
                <i class="fas fa-sign-in-alt"></i>
                <span>Đăng nhập ngay</span>
            </a>
            
        <?php elseif (isset($messageType) && $messageType === 'error' && !$validToken): ?>
            <!-- Invalid/Expired Token State -->
            <h1 class="reset-title">Link không hợp lệ</h1>
            <p class="reset-desc">Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.</p>
            
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $message ?></span>
            </div>
            
            <a href="<?= BASE_URL ?>forgot-password" class="btn-submit" style="text-decoration: none;">
                <i class="fas fa-redo"></i>
                <span>Yêu cầu link mới</span>
            </a>
            
        <?php else: ?>
            <!-- Reset Form -->
            <h1 class="reset-title">Đặt mật khẩu mới</h1>
            <p class="reset-desc">Nhập mật khẩu mới cho tài khoản của bạn.</p>
            
            <?php if (isset($message) && $messageType === 'error'): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $message ?></span>
            </div>
            <?php endif; ?>
            
            <form action="<?= BASE_URL ?>reset-password" method="POST" id="resetForm">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mật khẩu mới
                    </label>
                    <div class="input-group-custom">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Nhập mật khẩu mới"
                               required
                               minlength="6"
                               autofocus>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Xác nhận mật khẩu
                    </label>
                    <div class="input-group-custom">
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               class="form-control" 
                               placeholder="Nhập lại mật khẩu mới"
                               required
                               minlength="6">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="password-requirements">
                    <h6><i class="fas fa-info-circle"></i> Yêu cầu mật khẩu:</h6>
                    <ul>
                        <li id="req-length"><i class="fas fa-circle"></i> Ít nhất 6 ký tự</li>
                        <li id="req-match"><i class="fas fa-circle"></i> Mật khẩu xác nhận phải khớp</li>
                    </ul>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    <span>Đặt lại mật khẩu</span>
                </button>
            </form>
            
            <div class="back-links">
                <p><i class="fas fa-arrow-left"></i> Quay lại <a href="<?= BASE_URL ?>login">Đăng nhập</a></p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Password validation
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('confirm_password');
        const reqLength = document.getElementById('req-length');
        const reqMatch = document.getElementById('req-match');
        
        function validatePassword() {
            const password = passwordField?.value || '';
            const confirm = confirmField?.value || '';
            
            // Check length
            if (password.length >= 6) {
                reqLength?.classList.add('valid');
                reqLength.querySelector('i')?.classList.replace('fa-circle', 'fa-check-circle');
            } else {
                reqLength?.classList.remove('valid');
                reqLength.querySelector('i')?.classList.replace('fa-check-circle', 'fa-circle');
            }
            
            // Check match
            if (password && confirm && password === confirm) {
                reqMatch?.classList.add('valid');
                reqMatch.querySelector('i')?.classList.replace('fa-circle', 'fa-check-circle');
            } else {
                reqMatch?.classList.remove('valid');
                reqMatch.querySelector('i')?.classList.replace('fa-check-circle', 'fa-circle');
            }
        }
        
        passwordField?.addEventListener('input', validatePassword);
        confirmField?.addEventListener('input', validatePassword);
        
        // Form submit
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const password = passwordField.value;
            const confirm = confirmField.value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Mật khẩu phải có ít nhất 6 ký tự');
                return;
            }
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp');
                return;
            }
            
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
        });
    </script>
</body>
</html>
