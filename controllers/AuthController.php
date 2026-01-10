<?php
/**
 * Authentication Controller
 * Xu ly dang nhap, dang ky, dang xuat
 */

class AuthController {
    private $userModel;
    
    public function __construct($userModel = null) {
        $this->userModel = $userModel ?? new User();
    }
    
    /**
     * Xu ly dang nhap - return ket qua
     */
    public function login($data = null) {
        // Neu khong co data, lay tu POST
        if ($data === null) {
            $data = $_POST;
        }
        
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin'];
        }
        
        // Tim user theo email
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email hoặc mật khẩu không đúng'];
        }
        
        // Kiem tra mat khau
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Email hoặc mật khẩu không đúng'];
        }
        
        // Kiem tra trang thai tai khoan
        if (isset($user['status']) && $user['status'] !== 'active') {
            return ['success' => false, 'message' => 'Tài khoản đã bị khóa'];
        }
        
        // Luu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['fullname'] = $user['name'] ?? $user['fullname'] ?? '';
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'] ?? 'user';
        $_SESSION['avatar'] = $user['avatar'] ?? null;
        
        return ['success' => true, 'message' => 'Đăng nhập thành công', 'user' => $user];
    }
    
    /**
     * Xu ly dang ky - return ket qua
     */
    public function register($data = null) {
        // Neu khong co data, lay tu POST
        if ($data === null) {
            $data = $_POST;
        }
        
        $fullname = $data['fullname'] ?? '';
        $email = $data['email'] ?? '';
        $phone = $data['phone'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';
        $address = $data['address'] ?? '';
        
        // Validation
        if (empty($fullname) || empty($email) || empty($phone) || empty($password)) {
            return ['success' => false, 'message' => 'Vui long nhap day du thong tin'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email khong hop le'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Mat khau phai co it nhat 6 ky tu'];
        }
        
        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Mat khau xac nhan khong khop'];
        }
        
        // Kiem tra email da ton tai chua
        $existingUser = $this->userModel->getByEmail($email);
        if ($existingUser) {
            return ['success' => false, 'message' => 'Email da duoc su dung'];
        }
        
        // Tao user moi
        $userData = [
            'name' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'address' => $address,
            'role' => 'user',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->userModel->create($userData);
        
        if ($result) {
            return ['success' => true, 'message' => 'Dang ky thanh cong'];
        }
        
        return ['success' => false, 'message' => 'Co loi xay ra, vui long thu lai'];
    }
    
    /**
     * Dang xuat
     */
    public function logout() {
        // Xoa session
        unset($_SESSION['user_id']);
        unset($_SESSION['user']);
        unset($_SESSION['fullname']);
        unset($_SESSION['email']);
        unset($_SESSION['role']);
        unset($_SESSION['avatar']);
        
        // Huy session
        session_destroy();
        
        return ['success' => true, 'message' => 'Dang xuat thanh cong'];
    }
    
    /**
     * Tao URL dang nhap Google
     */
    public function getGoogleLoginUrl() {
        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];
        
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }
    
    /**
     * Xu ly callback tu Google
     */
    public function handleGoogleCallback($code) {
        if (empty($code)) {
            return ['success' => false, 'message' => 'Khong co ma xac thuc tu Google'];
        }
        
        // Doi code lay access token
        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $tokenData = [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $tokenResponse = curl_exec($ch);
        curl_close($ch);
        
        $tokenInfo = json_decode($tokenResponse, true);
        
        if (!isset($tokenInfo['access_token'])) {
            return ['success' => false, 'message' => 'Khong the lay access token tu Google'];
        }
        
        // Lay thong tin user tu Google
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $tokenInfo['access_token'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $userResponse = curl_exec($ch);
        curl_close($ch);
        
        $googleUser = json_decode($userResponse, true);
        
        if (!isset($googleUser['email'])) {
            return ['success' => false, 'message' => 'Khong the lay thong tin tu Google'];
        }
        
        // Kiem tra user da ton tai chua
        $existingUser = $this->userModel->getByEmail($googleUser['email']);
        
        if ($existingUser) {
            // User da ton tai - dang nhap luon
            // Kiem tra trang thai tai khoan
            if (isset($existingUser['status']) && $existingUser['status'] !== 'active') {
                return ['success' => false, 'message' => 'Tai khoan da bi khoa'];
            }
            
            // Luon cap nhat avatar va google_id tu Google
            $updateData = ['google_id' => $googleUser['id']];
            if (!empty($googleUser['picture'])) {
                $updateData['avatar'] = $googleUser['picture'];
                $existingUser['avatar'] = $googleUser['picture'];
            }
            $this->userModel->update($existingUser['id'], $updateData);
            
            // Luu session
            $this->setUserSession($existingUser);
            
            return ['success' => true, 'message' => 'Dang nhap thanh cong', 'user' => $existingUser];
        } else {
            // Tao user moi
            $userData = [
                'name' => $googleUser['name'] ?? $googleUser['email'],
                'email' => $googleUser['email'],
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // Random password
                'phone' => null,
                'role' => 'user',
                'status' => 'active',
                'avatar' => $googleUser['picture'] ?? null,
                'google_id' => $googleUser['id'],
                'email_verified' => 1
            ];
            
            $userId = $this->userModel->create($userData);
            
            if ($userId) {
                $newUser = $this->userModel->findById($userId);
                $this->setUserSession($newUser);
                return ['success' => true, 'message' => 'Dang ky va dang nhap thanh cong', 'user' => $newUser];
            }
            
            return ['success' => false, 'message' => 'Co loi xay ra khi tao tai khoan'];
        }
    }
    
    /**
     * Luu thong tin user vao session
     */
    private function setUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['fullname'] = $user['name'] ?? $user['fullname'] ?? '';
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'] ?? 'user';
        $_SESSION['avatar'] = $user['avatar'] ?? null;
    }
    
    /**
     * Quen mat khau
     */
    public function forgotPassword($email) {
        if (empty($email)) {
            return ['success' => false, 'message' => 'Vui lòng nhập email'];
        }
        
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email không tồn tại trong hệ thống'];
        }
        
        // Tạo token reset password
        $resetToken = bin2hex(random_bytes(32));
        $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Lưu token vào database
        $updateResult = $this->userModel->update($user['id'], [
            'reset_token' => $resetToken,
            'reset_expiry' => $resetExpiry
        ]);
        
        if (!$updateResult) {
            return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
        }
        
        // Tạo link reset password
        $resetLink = BASE_URL . 'reset-password?token=' . $resetToken;
        
        // Gửi email
        $emailSent = $this->sendResetPasswordEmail($user['email'], $user['name'] ?? 'Khách hàng', $resetLink);
        
        if ($emailSent) {
            return ['success' => true, 'message' => 'Hướng dẫn đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư (kể cả thư mục Spam).'];
        } else {
            return ['success' => false, 'message' => 'Không thể gửi email. Vui lòng thử lại sau hoặc liên hệ hỗ trợ.'];
        }
    }
    
    /**
     * Gửi email reset password
     */
    private function sendResetPasswordEmail($toEmail, $toName, $resetLink) {
        $subject = 'Đặt lại mật khẩu - TechShop';
        
        $htmlBody = $this->getResetEmailTemplate($toName, $resetLink);
        
        // Kiểm tra nếu SMTP được bật
        if (defined('SMTP_ENABLED') && SMTP_ENABLED === true) {
            return $this->sendEmailViaSMTP($toEmail, $toName, $subject, $htmlBody);
        }
        
        // Fallback: sử dụng mail() function (có thể không hoạt động trên Windows/XAMPP)
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: TechShop <noreply@techshop.com>',
            'Reply-To: support@techshop.com',
            'X-Mailer: PHP/' . phpversion()
        ];
        
        return @mail($toEmail, $encodedSubject, $htmlBody, implode("\r\n", $headers));
    }
    
    /**
     * Template email reset password
     */
    private function getResetEmailTemplate($toName, $resetLink) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px 10px 0 0; text-align: center;">
                    <h1 style="color: #fff; margin: 0; font-size: 28px;">🖥️ TechShop</h1>
                    <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0;">Thiết bị công nghệ chính hãng</p>
                </div>
                
                <div style="background: #fff; padding: 40px 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <h2 style="color: #333; margin-top: 0; font-size: 22px;">Xin chào ' . htmlspecialchars($toName) . ',</h2>
                    
                    <p style="color: #555; font-size: 16px;">Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn tại TechShop.</p>
                    
                    <p style="color: #555; font-size: 16px;">Nhấn vào nút bên dưới để đặt lại mật khẩu:</p>
                    
                    <div style="text-align: center; margin: 35px 0;">
                        <a href="' . $resetLink . '" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 15px rgba(102,126,234,0.4);">
                            🔐 Đặt lại mật khẩu
                        </a>
                    </div>
                    
                    <div style="background: #fff8e1; border-left: 4px solid #ffc107; padding: 15px; margin: 25px 0; border-radius: 0 5px 5px 0;">
                        <p style="margin: 0; color: #856404; font-size: 14px;">
                            <strong>⏰ Lưu ý:</strong> Link này sẽ hết hạn sau <strong>1 giờ</strong>.
                        </p>
                    </div>
                    
                    <p style="color: #888; font-size: 14px;">Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này. Tài khoản của bạn vẫn an toàn.</p>
                    
                    <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
                    
                    <p style="color: #999; font-size: 12px; margin-bottom: 10px;">Nếu nút không hoạt động, copy link sau vào trình duyệt:</p>
                    <p style="word-break: break-all; color: #667eea; font-size: 12px; background: #f8f9fa; padding: 10px; border-radius: 5px;">' . $resetLink . '</p>
                </div>
                
                <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
                    <p style="margin: 5px 0;">© ' . date('Y') . ' TechShop. All rights reserved.</p>
                    <p style="margin: 5px 0;">Email này được gửi tự động, vui lòng không trả lời.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Gửi email qua SMTP sử dụng socket (không cần PHPMailer)
     */
    private function sendEmailViaSMTP($toEmail, $toName, $subject, $body) {
        $host = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
        $port = defined('SMTP_PORT') ? SMTP_PORT : 587;
        $username = defined('SMTP_USERNAME') ? SMTP_USERNAME : '';
        $password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
        $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : $username;
        $fromName = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'TechShop';
        
        if (empty($username) || empty($password)) {
            error_log('SMTP credentials not configured');
            return false;
        }
        
        try {
            // Kết nối tới SMTP server
            $socket = @fsockopen($host, $port, $errno, $errstr, 30);
            if (!$socket) {
                error_log("SMTP Connection failed: $errstr ($errno)");
                return false;
            }
            
            stream_set_timeout($socket, 30);
            
            // Đọc greeting
            $response = fgets($socket, 515);
            if (substr($response, 0, 3) != '220') {
                fclose($socket);
                return false;
            }
            
            // EHLO
            fputs($socket, "EHLO localhost\r\n");
            while ($line = fgets($socket, 515)) {
                if (substr($line, 3, 1) == ' ') break;
            }
            
            // STARTTLS
            fputs($socket, "STARTTLS\r\n");
            $response = fgets($socket, 515);
            if (substr($response, 0, 3) != '220') {
                fclose($socket);
                return false;
            }
            
            // Enable TLS
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            
            // EHLO again after STARTTLS
            fputs($socket, "EHLO localhost\r\n");
            while ($line = fgets($socket, 515)) {
                if (substr($line, 3, 1) == ' ') break;
            }
            
            // AUTH LOGIN
            fputs($socket, "AUTH LOGIN\r\n");
            $response = fgets($socket, 515);
            
            // Username
            fputs($socket, base64_encode($username) . "\r\n");
            $response = fgets($socket, 515);
            
            // Password
            fputs($socket, base64_encode($password) . "\r\n");
            $response = fgets($socket, 515);
            if (substr($response, 0, 3) != '235') {
                error_log("SMTP Auth failed: $response");
                fclose($socket);
                return false;
            }
            
            // MAIL FROM
            fputs($socket, "MAIL FROM:<$fromEmail>\r\n");
            $response = fgets($socket, 515);
            
            // RCPT TO
            fputs($socket, "RCPT TO:<$toEmail>\r\n");
            $response = fgets($socket, 515);
            
            // DATA
            fputs($socket, "DATA\r\n");
            $response = fgets($socket, 515);
            
            // Headers & Body
            $boundary = md5(time());
            $headers = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <$fromEmail>\r\n";
            $headers .= "To: =?UTF-8?B?" . base64_encode($toName) . "?= <$toEmail>\r\n";
            $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: base64\r\n";
            $headers .= "\r\n";
            $headers .= chunk_split(base64_encode($body));
            $headers .= "\r\n.\r\n";
            
            fputs($socket, $headers);
            $response = fgets($socket, 515);
            
            // QUIT
            fputs($socket, "QUIT\r\n");
            fclose($socket);
            
            return substr($response, 0, 3) == '250';
            
        } catch (Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác thực token reset password
     */
    public function verifyResetToken($token) {
        if (empty($token)) {
            return ['success' => false, 'message' => 'Token không hợp lệ'];
        }
        
        $user = $this->userModel->getByResetToken($token);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn'];
        }
        
        // Kiểm tra thời hạn token
        if (strtotime($user['reset_expiry']) < time()) {
            return ['success' => false, 'message' => 'Link đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu link mới.'];
        }
        
        return ['success' => true, 'user' => $user];
    }
    
    /**
     * Đặt lại mật khẩu
     */
    public function resetPassword($token, $newPassword, $confirmPassword) {
        // Xác thực token
        $tokenResult = $this->verifyResetToken($token);
        
        if (!$tokenResult['success']) {
            return $tokenResult;
        }
        
        $user = $tokenResult['user'];
        
        // Validate mật khẩu
        if (empty($newPassword)) {
            return ['success' => false, 'message' => 'Vui lòng nhập mật khẩu mới'];
        }
        
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự'];
        }
        
        if ($newPassword !== $confirmPassword) {
            return ['success' => false, 'message' => 'Mật khẩu xác nhận không khớp'];
        }
        
        // Cập nhật mật khẩu và xóa token
        $updateResult = $this->userModel->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_expiry' => null
        ]);
        
        if ($updateResult) {
            return ['success' => true, 'message' => 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập bằng mật khẩu mới.'];
        }
        
        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
    }
}
