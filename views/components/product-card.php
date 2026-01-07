<div class="product-card">
    <?php 
    $hasSale = !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
    $discountPercent = $hasSale ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
    ?>
    
    <div class="product-badges">
        <?php if ($hasSale): ?>
        <span class="badge badge-sale">-<?= $discountPercent ?>%</span>
        <?php endif; ?>
        
        <?php if (!empty($product['featured'])): ?>
        <span class="badge badge-featured">Nổi bật</span>
        <?php endif; ?>
        
        <?php if ($product['stock'] <= 0): ?>
        <span class="badge badge-outofstock">Hết hàng</span>
        <?php elseif ($product['stock'] <= 5): ?>
        <span class="badge badge-lowstock">Còn <?= $product['stock'] ?></span>
        <?php endif; ?>
    </div>
    
    <div class="product-image">
        <?php 
        // Get primary image URL
        $primaryImg = $product['primary_image'] ?? ($product['images'][0] ?? null);
        if (is_array($primaryImg)) $primaryImg = $primaryImg['image_url'] ?? '';
        if (empty($primaryImg)) $primaryImg = 'assets/images/no-image.jpg';
        $primaryImageUrl = (strpos($primaryImg, 'http') === 0) ? $primaryImg : BASE_URL . $primaryImg;
        
        // Get secondary image URL  
        $secondaryImg = $product['images'][1] ?? ($product['images'][0] ?? null);
        if (is_array($secondaryImg)) $secondaryImg = $secondaryImg['image_url'] ?? '';
        $secondaryImageUrl = !empty($secondaryImg) ? ((strpos($secondaryImg, 'http') === 0) ? $secondaryImg : BASE_URL . $secondaryImg) : '';
        ?>
        <a href="<?= BASE_URL ?>product/<?= $product['slug'] ?>">
            <img src="<?= $primaryImageUrl ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>"
                 class="img-primary">
            <?php if (!empty($secondaryImageUrl)): ?>
            <img src="<?= $secondaryImageUrl ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>"
                 class="img-secondary">
            <?php endif; ?>
        </a>
    </div>
    
    <div class="product-info">
        <div class="product-category">
            <?php 
            if (!empty($product['category_name'])) {
                echo $product['category_name'];
            } else {
                echo 'Chưa phân loại';
            }
            ?>
        </div>
        
        <h3 class="product-name">
            <a href="<?= BASE_URL ?>product/<?= $product['slug'] ?>">
                <?= htmlspecialchars($product['name']) ?>
            </a>
        </h3>
        
        <div class="product-rating">
            <?php 
            $rating = $product['rating'] ?? 0;
            $ratingCount = $product['rating_count'] ?? 0;
            for ($i = 1; $i <= 5; $i++):
                if ($i <= $rating):
            ?>
                <i class="fas fa-star"></i>
            <?php elseif ($i - 0.5 <= $rating): ?>
                <i class="fas fa-star-half-alt"></i>
            <?php else: ?>
                <i class="far fa-star"></i>
            <?php endif; endfor; ?>
            <span class="rating-count">(<?= $ratingCount ?>)</span>
        </div>
        
        <div class="product-price">
            <?php if ($hasSale): ?>
            <span class="price-current"><?= formatPrice($product['sale_price']) ?></span>
            <span class="price-old"><?= formatPrice($product['price']) ?></span>
            <?php else: ?>
            <span class="price-current"><?= formatPrice($product['price']) ?></span>
            <?php endif; ?>
        </div>
        
        <?php if ($product['stock'] > 0): ?>
        <button class="btn btn-add-cart" 
                onclick="addToCart('<?= $product['id'] ?>')"
                data-product-id="<?= $product['id'] ?>">
            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
        </button>
        <?php else: ?>
        <button class="btn btn-notify" data-product-id="<?= $product['id'] ?>">
            <i class="fas fa-bell"></i> Thông báo khi có hàng
        </button>
        <?php endif; ?>
    </div>
</div>


