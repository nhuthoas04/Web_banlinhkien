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
            return ['success' => false, 'message' => 'Vui long nhap day du thong tin'];
        }
        
        // Tim user theo email
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email khong ton tai'];
        }
        
        // Kiem tra mat khau
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mat khau khong dung'];
        }
        
        // Kiem tra trang thai tai khoan
        if (isset($user['status']) && $user['status'] !== 'active') {
            return ['success' => false, 'message' => 'Tai khoan da bi khoa'];
        }
        
        // Luu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['fullname'] = $user['name'] ?? $user['fullname'] ?? '';
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'] ?? 'user';
        $_SESSION['avatar'] = $user['avatar'] ?? null;
        
        return ['success' => true, 'message' => 'Dang nhap thanh cong', 'user' => $user];
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
     * Quen mat khau
     */
    public function forgotPassword($email) {
        if (empty($email)) {
            return ['success' => false, 'message' => 'Vui long nhap email'];
        }
        
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email khong ton tai trong he thong'];
        }
        
        // TODO: Gui email reset password
        // Hien tai chi tra ve thong bao
        return ['success' => true, 'message' => 'Huong dan dat lai mat khau da duoc gui den email cua ban'];
    }
}
