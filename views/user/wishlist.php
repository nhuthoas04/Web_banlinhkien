<?php
$pageTitle = 'Sản phẩm yêu thích';
$currentPage = 'wishlist';
include __DIR__ . '/../layouts/header.php';
?>

<section class="wishlist-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php include __DIR__ . '/../components/account-sidebar.php'; ?>
            </div>

            <!-- Wishlist Content -->
            <div class="col-lg-9">
                <div class="wishlist-container">
                    <div class="section-header">
                        <h4><i class="fas fa-heart"></i> Sản phẩm yêu thích</h4>
                    </div>
                    
                    <div class="wishlist-content p-4">
                        <div class="text-center py-5">
                            <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                            <h5>Chưa có sản phẩm yêu thích</h5>
                            <p class="text-muted">Hãy thêm sản phẩm vào danh sách yêu thích để theo dõi giá và mua sau!</p>
                            <a href="<?= BASE_URL ?>products" class="btn btn-primary mt-3">
                                <i class="fas fa-shopping-bag"></i> Khám phá sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.wishlist-container {
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
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
