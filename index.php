<?php
/**
 * NR TRADING - Homepage
 */

require_once 'includes/init.php';

$pageTitle = 'NR TRADING - Premium Wholesale Spices & Food Products';

// Get featured products
$featuredProducts = $db->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.status = 1 AND p.is_featured = 1 
    ORDER BY p.created_at DESC 
    LIMIT 8
")->fetchAll();

// Get latest products
$latestProducts = $db->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.status = 1 
    ORDER BY p.created_at DESC 
    LIMIT 12
")->fetchAll();

// Get categories
$categories = $db->query("SELECT * FROM categories WHERE status = 1 ORDER BY display_order, name")->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">প্রিমিয়ান কোয়ালিটি ফুড</h1>
                <h2 class="hero-subtitle">সুস্থ থাকার <span class="text-warning">ন্যাচারাল</span></h2>
                <h2 class="hero-subtitle"><span class="text-warning">সিক্রেট</span></h2>
                <a href="<?php echo SITE_URL; ?>/shop.php" class="btn btn-shop-now mt-4">
                    <i class="bi bi-chevron-right"></i> SHOP NOW
                </a>
            </div>
            <div class="col-lg-6">
                <div class="hero-products">
                    <div class="product-bowl">
                        <img src="<?php echo ASSETS_URL; ?>/images/ChatGPT_Image_Dec_28__2025__04_43_53_PM-removebg-preview.png" alt="Products" class="img-fluid" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Section -->
<section class="py-5 category-section">
    <div class="container">
        <div class="section-title text-center">
            <h2>Shop by Category</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $category['id']; ?>" 
                   class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-image">
                            <?php if ($category['image']): ?>
                                <img src="<?php echo UPLOADS_URL; ?>/categories/<?php echo $category['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($category['name']); ?>">
                            <?php else: ?>
                                <i class="bi bi-box-seam"></i>
                            <?php endif; ?>
                        </div>
                        <div class="category-body">
                            <h5><?php echo htmlspecialchars($category['name']); ?></h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if (!empty($featuredProducts)): ?>
<section class="py-5 featured-section">
    <div class="container">
        <div class="section-title text-center">
            <h2>Featured Products</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        <?php if ($product['original_price'] && $product['original_price'] > $product['price']): ?>
                        <span class="product-badge">
                            <?php echo round((($product['original_price'] - $product['price']) / $product['original_price']) * 100); ?>% OFF
                        </span>
                        <?php endif; ?>
                        <img src="<?php echo $product['image'] ? UPLOADS_URL . '/products/' . $product['image'] : ASSETS_URL . '/images/no-image.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="product-body">
                        <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" 
                           class="product-title">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </a>
                        <div class="product-price">
                            <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($product['price'], 2)); ?>
                            <?php if ($product['original_price'] && $product['original_price'] > $product['price']): ?>
                                <span class="product-original-price">
                                    <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($product['original_price'], 2)); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="product-stock">
                            <?php if ($product['stock'] > 0): ?>
                                <i class="bi bi-check-circle text-success"></i> In Stock (<?php echo $product['stock']; ?>)
                            <?php else: ?>
                                <i class="bi bi-x-circle text-danger"></i> Out of Stock
                            <?php endif; ?>
                        </div>
                        <div class="product-actions">
                            <?php if ($product['stock'] > 0): ?>
                                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-add-to-cart w-100">
                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100" disabled>
                                    Out of Stock
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Products Section -->
<?php if (!empty($latestProducts)): ?>
<section class="py-5 latest-section">
    <div class="container">
        <div class="section-title text-center">
            <h2>Latest Products</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($latestProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        <?php if ($product['original_price'] && $product['original_price'] > $product['price']): ?>
                        <span class="product-badge">
                            <?php echo round((($product['original_price'] - $product['price']) / $product['original_price']) * 100); ?>% OFF
                        </span>
                        <?php endif; ?>
                        <img src="<?php echo $product['image'] ? UPLOADS_URL . '/products/' . $product['image'] : ASSETS_URL . '/images/no-image.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="product-body">
                        <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" 
                           class="product-title">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </a>
                        <div class="product-price">
                            <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($product['price'], 2)); ?>
                            <?php if ($product['original_price'] && $product['original_price'] > $product['price']): ?>
                                <span class="product-original-price">
                                    <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($product['original_price'], 2)); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="product-stock">
                            <?php if ($product['stock'] > 0): ?>
                                <i class="bi bi-check-circle text-success"></i> In Stock (<?php echo $product['stock']; ?>)
                            <?php else: ?>
                                <i class="bi bi-x-circle text-danger"></i> Out of Stock
                            <?php endif; ?>
                        </div>
                        <div class="product-actions">
                            <?php if ($product['stock'] > 0): ?>
                                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-add-to-cart w-100">
                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100" disabled>
                                    Out of Stock
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo SITE_URL; ?>/shop.php" class="btn btn-primary btn-lg">
                View All Products <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-truck text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Fast Delivery</h5>
                    <p class="text-muted">Quick and reliable delivery across Bangladesh</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Quality Assured</h5>
                    <p class="text-muted">Premium quality products guaranteed</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-headset text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">24/7 Support</h5>
                    <p class="text-muted">Always here to help you</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-cash-coin text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Best Prices</h5>
                    <p class="text-muted">Competitive wholesale prices</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
