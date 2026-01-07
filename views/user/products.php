<?php
$pageTitle = $category ? htmlspecialchars($category['name']) : 'Tất cả sản phẩm';

// Khởi tạo các biến mặc định
$products = $products ?? [];
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$page = $page ?? 1;
$categories = $categories ?? [];
$category = $category ?? null;
$sort = $sort ?? 'newest';
$minPrice = $minPrice ?? null;
$maxPrice = $maxPrice ?? null;

include __DIR__ . '/../layouts/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                <?php if ($category && isset($category['parent_name'])): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>?page=products&category=<?= $category['parent_slug'] ?>">
                            <?= htmlspecialchars($category['parent_name']) ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?= $pageTitle ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Products Section -->
<section class="products-page py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <!-- Categories -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-list"></i> Danh mục
                        </h5>
                        <div class="filter-body">
                            <ul class="category-list">
                                <li class="<?= !$category ? 'active' : '' ?>">
                                    <a href="<?= BASE_URL ?>products">Tất cả sản phẩm</a>
                                </li>
                                <?php foreach ($categories as $cat): ?>
                                    <li class="<?= ($category && $category['id'] == $cat['id']) ? 'active' : '' ?>">
                                        <a href="<?= BASE_URL ?>products?category=<?= $cat['id'] ?>">
                                            <?= htmlspecialchars($cat['name']) ?>
                                            <?php if (!empty($cat['product_count'])): ?>
                                                <span class="count">(<?= $cat['product_count'] ?>)</span>
                                            <?php endif; ?>
                                        </a>
                                        <?php if (!empty($cat['children'])): ?>
                                            <ul class="sub-category-list">
                                                <?php foreach ($cat['children'] as $child): ?>
                                                    <li>
                                                        <a href="<?= BASE_URL ?>?page=products&category=<?= $child['slug'] ?>">
                                                            <?= htmlspecialchars($child['name']) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-dollar-sign"></i> Khoảng giá
                        </h5>
                        <div class="filter-body">
                            <form id="priceFilterForm">
                                <div class="price-range">
                                    <input type="number" name="min_price" class="form-control" 
                                           placeholder="Từ" value="<?= $minPrice ?? '' ?>">
                                    <span>-</span>
                                    <input type="number" name="max_price" class="form-control" 
                                           placeholder="Đến" value="<?= $maxPrice ?? '' ?>">
                                </div>
                                <button type="submit" class="btn btn-filter-apply w-100 mt-3">
                                    Áp dụng
                                </button>
                            </form>
                            <div class="price-quick mt-3">
                                <button type="button" class="price-btn" data-min="0" data-max="5000000">
                                    Dưới 5 triệu
                                </button>
                                <button type="button" class="price-btn" data-min="5000000" data-max="10000000">
                                    5 - 10 triệu
                                </button>
                                <button type="button" class="price-btn" data-min="10000000" data-max="20000000">
                                    10 - 20 triệu
                                </button>
                                <button type="button" class="price-btn" data-min="20000000" data-max="">
                                    Trên 20 triệu
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-tag"></i> Thương hiệu
                        </h5>
                        <div class="filter-body">
                            <div class="brand-list">
                                <?php foreach ($brands ?? [] as $brand): ?>
                                    <label class="brand-checkbox">
                                        <input type="checkbox" name="brands[]" 
                                               value="<?= htmlspecialchars($brand) ?>"
                                               <?= in_array($brand, $selectedBrands ?? []) ? 'checked' : '' ?>>
                                        <span><?= htmlspecialchars($brand) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="filter-card">
                        <h5 class="filter-title">
                            <i class="fas fa-star"></i> Đánh giá
                        </h5>
                        <div class="filter-body">
                            <div class="rating-filter">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <label class="rating-checkbox">
                                        <input type="radio" name="rating" value="<?= $i ?>"
                                               <?= ($selectedRating ?? 0) == $i ? 'checked' : '' ?>>
                                        <span class="stars">
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <i class="fas fa-star <?= $j <= $i ? 'text-warning' : 'text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </span>
                                        <span class="rating-text">từ <?= $i ?> sao</span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Clear Filter -->
                    <a href="<?= BASE_URL ?>?page=products" class="btn btn-clear-filter w-100">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </a>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="toolbar-left">
                        <h4 class="products-title"><?= $pageTitle ?></h4>
                        <p class="products-count">
                            Hiển thị <?= count($products) ?> / <?= $total ?> sản phẩm
                        </p>
                    </div>
                    <div class="toolbar-right">
                        <div class="view-mode">
                            <button type="button" class="view-btn active" data-view="grid">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        <div class="sort-dropdown">
                            <select class="form-select" id="sortSelect">
                                <option value="newest" <?= ($sort ?? '') == 'newest' ? 'selected' : '' ?>>
                                    Mới nhất
                                </option>
                                <option value="price_asc" <?= ($sort ?? '') == 'price_asc' ? 'selected' : '' ?>>
                                    Giá thấp đến cao
                                </option>
                                <option value="price_desc" <?= ($sort ?? '') == 'price_desc' ? 'selected' : '' ?>>
                                    Giá cao đến thấp
                                </option>
                                <option value="bestselling" <?= ($sort ?? '') == 'bestselling' ? 'selected' : '' ?>>
                                    Bán chạy nhất
                                </option>
                                <option value="rating" <?= ($sort ?? '') == 'rating' ? 'selected' : '' ?>>
                                    Đánh giá cao
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <?php if (empty($products)): ?>
                    <!-- Empty State -->
                    <div class="empty-products">
                        <img src="<?= BASE_URL ?>assets/images/empty-products.svg" alt="No products">
                        <h5>Không tìm thấy sản phẩm</h5>
                        <p>Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                        <a href="<?= BASE_URL ?>products" class="btn btn-primary">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Products Grid -->
                    <div class="products-grid" id="productsContainer">
                        <?php foreach ($products as $product): ?>
                            <?php include __DIR__ . '/../components/product-card.php'; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php 
                    // Helper function cho pagination URL
                    if (!function_exists('buildPaginationUrl')) {
                        function buildPaginationUrl($pageNum) {
                            $params = $_GET;
                            $params['p'] = $pageNum;
                            unset($params['page']); // Remove old page param
                            return BASE_URL . 'products?' . http_build_query($params);
                        }
                    }
                    
                    if ($totalPages > 1): 
                    ?>
                        <nav class="products-pagination">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= buildPaginationUrl($page - 1) ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                
                                if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildPaginationUrl(1) ?>">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= buildPaginationUrl($i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildPaginationUrl($totalPages) ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>
                                
                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= buildPaginationUrl($page + 1) ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
function buildPaginationUrl($page) {
    $params = $_GET;
    $params['p'] = $page;
    return BASE_URL . '?' . http_build_query($params);
}
?>

<style>
/* Filter Sidebar */
.filter-sidebar {
    position: sticky;
    top: 100px;
}

.filter-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    overflow: hidden;
}

.filter-title {
    font-size: 15px;
    font-weight: 600;
    padding: 15px 20px;
    margin: 0;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-title i {
    color: var(--primary-color);
}

.filter-body {
    padding: 15px 20px;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    margin-bottom: 8px;
}

.category-list li a {
    color: #555;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s;
}

.category-list li a:hover,
.category-list li.active a {
    background: var(--primary-color);
    color: #fff;
}

.category-list li a .count {
    font-size: 12px;
    opacity: 0.7;
}

.sub-category-list {
    list-style: none;
    padding: 5px 0 0 20px;
    margin: 0;
}

.sub-category-list li a {
    font-size: 13px;
    padding: 6px 12px;
}

.price-range {
    display: flex;
    align-items: center;
    gap: 10px;
}

.price-range input {
    font-size: 13px;
    padding: 8px 12px;
}

.price-range span {
    color: #999;
}

.btn-filter-apply {
    background: var(--primary-color);
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 10px;
    font-weight: 500;
}

.price-quick {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.price-btn {
    padding: 6px 12px;
    font-size: 12px;
    border: 1px solid #e0e0e0;
    background: #fff;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s;
}

.price-btn:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.brand-list {
    max-height: 200px;
    overflow-y: auto;
}

.brand-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    font-size: 14px;
}

.rating-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    cursor: pointer;
}

.rating-checkbox .stars i {
    font-size: 12px;
}

.rating-text {
    font-size: 13px;
    color: #666;
}

.btn-clear-filter {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 12px;
    font-weight: 500;
}

.btn-clear-filter:hover {
    background: #e9ecef;
}

/* Toolbar */
.products-toolbar {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.products-title {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.products-count {
    font-size: 14px;
    color: #666;
    margin: 5px 0 0 0;
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.view-mode {
    display: flex;
    gap: 5px;
}

.view-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #e0e0e0;
    background: #fff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}

.view-btn.active,
.view-btn:hover {
    background: var(--primary-color);
    color: #fff;
    border-color: var(--primary-color);
}

.sort-dropdown select {
    border-radius: 10px;
    padding: 10px 15px;
    font-size: 14px;
    border-color: #e0e0e0;
    min-width: 180px;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.products-grid.list-view {
    grid-template-columns: 1fr;
}

/* Empty State */
.empty-products {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 16px;
}

.empty-products img {
    max-width: 200px;
    margin-bottom: 25px;
    opacity: 0.7;
}

.empty-products h5 {
    font-size: 20px;
    margin-bottom: 10px;
}

.empty-products p {
    color: #666;
    margin-bottom: 20px;
}

/* Pagination */
.products-pagination {
    margin-top: 40px;
}

.products-pagination .page-link {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    margin: 0 3px;
    font-weight: 500;
}

.products-pagination .page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

/* Responsive */
@media (max-width: 991px) {
    .filter-sidebar {
        display: none;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .products-toolbar {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort select
    document.getElementById('sortSelect').addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', this.value);
        url.searchParams.delete('p');
        window.location.href = url.toString();
    });
    
    // Price quick buttons
    document.querySelectorAll('.price-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const min = this.dataset.min;
            const max = this.dataset.max;
            document.querySelector('input[name="min_price"]').value = min;
            document.querySelector('input[name="max_price"]').value = max;
            document.getElementById('priceFilterForm').dispatchEvent(new Event('submit'));
        });
    });
    
    // Price filter form
    document.getElementById('priceFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const url = new URL(window.location.href);
        const min = document.querySelector('input[name="min_price"]').value;
        const max = document.querySelector('input[name="max_price"]').value;
        
        if (min) url.searchParams.set('min_price', min);
        else url.searchParams.delete('min_price');
        
        if (max) url.searchParams.set('max_price', max);
        else url.searchParams.delete('max_price');
        
        url.searchParams.delete('p');
        window.location.href = url.toString();
    });
    
    // View mode toggle
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const container = document.getElementById('productsContainer');
            if (this.dataset.view === 'list') {
                container.classList.add('list-view');
            } else {
                container.classList.remove('list-view');
            }
        });
    });
    
    // Brand checkboxes
    document.querySelectorAll('.brand-checkbox input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const url = new URL(window.location.href);
            const brands = [];
            document.querySelectorAll('.brand-checkbox input:checked').forEach(cb => {
                brands.push(cb.value);
            });
            
            if (brands.length > 0) {
                url.searchParams.set('brands', brands.join(','));
            } else {
                url.searchParams.delete('brands');
            }
            
            url.searchParams.delete('p');
            window.location.href = url.toString();
        });
    });
    
    // Rating filter
    document.querySelectorAll('.rating-checkbox input').forEach(radio => {
        radio.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('rating', this.value);
            url.searchParams.delete('p');
            window.location.href = url.toString();
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


