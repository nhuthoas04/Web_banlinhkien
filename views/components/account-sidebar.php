<!-- Account Sidebar Component -->
<?php 
// Đảm bảo có dữ liệu user
$userName = $user['name'] ?? $_SESSION['fullname'] ?? 'Người dùng';
$userEmail = $user['email'] ?? $_SESSION['email'] ?? '';
$userAvatar = $user['avatar'] ?? $_SESSION['avatar'] ?? null;

// Xử lý URL avatar (hỗ trợ cả URL từ Google và ảnh local)
function getAvatarUrl($avatar) {
    if (empty($avatar)) {
        return BASE_URL . 'assets/images/default-avatar.svg';
    }
    // Nếu là URL đầy đủ (từ Google, Facebook...) thì dùng trực tiếp
    if (strpos($avatar, 'http://') === 0 || strpos($avatar, 'https://') === 0) {
        return $avatar;
    }
    // Nếu là ảnh local thì thêm BASE_URL
    return BASE_URL . $avatar;
}
?>
<div class="account-sidebar">
    <div class="sidebar-user-box">
        <img src="<?= getAvatarUrl($userAvatar) ?>" alt="Avatar" class="sidebar-avatar">
        <div class="sidebar-user-details">
            <p class="sidebar-username"><?= htmlspecialchars($userName) ?></p>
            <p class="sidebar-useremail"><?= htmlspecialchars($userEmail) ?></p>
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li class="<?= ($currentPage ?? '') == 'profile' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>profile">
                <i class="fas fa-user"></i> Thông tin tài khoản
            </a>
        </li>
        <?php if (!isAdmin()): ?>
        <li class="<?= ($currentPage ?? '') == 'orders' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>don-hang">
                <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
                <?php if (($pendingOrders ?? 0) > 0): ?>
                    <span class="badge"><?= $pendingOrders ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php endif; ?>
        <li class="<?= ($currentPage ?? '') == 'reviews' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>my-reviews">
                <i class="fas fa-star"></i> Đánh giá của tôi
            </a>
        </li>
        <li class="<?= ($currentPage ?? '') == 'notifications' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>notifications">
                <i class="fas fa-bell"></i> Thông báo
            </a>
        </li>
        <li class="<?= ($currentPage ?? '') == 'chat' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>user-chat">
                <i class="fas fa-comments"></i> Hỗ trợ trực tuyến
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="<?= BASE_URL ?>logout" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </li>
    </ul>
</div>

<style>
.account-sidebar {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    overflow: hidden;
    position: sticky;
    top: 100px;
}

.sidebar-user-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 25px;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
}

.sidebar-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,0.3);
    flex-shrink: 0;
}

.sidebar-user-details {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.sidebar-username {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 5px 0;
    color: #ffffff !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-useremail {
    font-size: 13px;
    margin: 0;
    color: rgba(255,255,255,0.8) !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-menu {
    list-style: none;
    padding: 15px 0;
    margin: 0;
}

.sidebar-menu li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 25px;
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
    position: relative;
}

.sidebar-menu li a:hover,
.sidebar-menu li.active a {
    color: var(--primary-color);
    background: #f0f9ff;
}

.sidebar-menu li.active a::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--primary-color);
}

.sidebar-menu li a i {
    width: 20px;
    font-size: 16px;
}

.sidebar-menu li a .badge {
    margin-left: auto;
    background: #ef4444;
    color: #fff;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 50px;
}

.sidebar-menu .divider {
    height: 1px;
    background: #e2e8f0;
    margin: 10px 0;
}

.logout-link {
    color: #ef4444 !important;
}

.logout-link:hover {
    background: #fee2e2 !important;
}
</style>


