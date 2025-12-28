<?php
/**
 * NR TRADING - Product Details Page
 */

require_once 'includes/init.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$productId) {
    redirect(SITE_URL . '/shop.php');
}

// Get product details
$db->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = :id AND p.status = 1
")->bind(':id', $productId);
$product = $db->fetch();

if (!$product) {
    redirect(SITE_URL . '/shop.php');
}

$pageTitle = htmlspecialchars($product['name']) . ' - NR TRADING';

// Get related products
$relatedProducts = $db->query("
    SELECT * FROM products 
    WHERE category_id = :category AND id != :id AND status = 1 
    ORDER BY RAND() 
    LIMIT 4
")->bind(':category', $product['category_id'])
   ->bind(':id', $productId)
   ->fetchAll();

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/shop.php">Shop</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>
    
    <!-- Product Details -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <!-- Product Image -->
            <div class="product-gallery-main">
                <img id="main-product-image" 
                     src="<?php echo $product['image'] ? UPLOADS_URL . '/products/' . $product['image'] : ASSETS_URL . '/images/no-image.jpg'; ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        </div>
        
        <div class="col-lg-6">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <div class="mb-3">
                <span class="badge bg-primary"><?php echo htmlspecialchars($product['category_name']); ?></span>
                <?php if ($product['sku']): ?>
                    <span class="text-muted ms-2">SKU: <?php echo htmlspecialchars($product['sku']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <h3 class="text-primary">
                    <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($product['price'], 2)); ?>
                    <?php if ($product['original_price'] && $product['original_price'] > $product['price']): ?>
                        <span class="h5 text-muted text-decoration-line-through ms-2">
                            <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($product['original_price'], 2)); ?>
                        </span>
                        <span class="badge bg-danger ms-2">
                            <?php echo round((($product['original_price'] - $product['price']) / $product['original_price']) * 100); ?>% OFF
                        </span>
                    <?php endif; ?>
                </h3>
            </div>
            
            <div class="mb-4">
                <h6>Availability:</h6>
                <?php if ($product['stock'] > 0): ?>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i> In Stock (<?php echo $product['stock']; ?> units)
                    </span>
                <?php else: ?>
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle me-1"></i> Out of Stock
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if ($product['description']): ?>
            <div class="mb-4">
                <h6>Description:</h6>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if ($product['stock'] > 0): ?>
            <div class="mb-4">
                <h6>Quantity:</h6>
                <div class="quantity-input d-inline-flex">
                    <button class="btn btn-outline-secondary qty-decrease" type="button">
                        <i class="bi bi-dash"></i>
                    </button>
                    <input type="number" id="product-quantity" class="form-control" 
                           value="1" min="1" max="<?php echo $product['stock']; ?>" readonly>
                    <button class="btn btn-outline-secondary qty-increase" type="button">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button onclick="addProductToCart()" class="btn btn-primary btn-lg">
                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                </button>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">This product is currently out of stock.</div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row g-4">
            <?php foreach ($relatedProducts as $relProd): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $relProd['image'] ? UPLOADS_URL . '/products/' . $relProd['image'] : ASSETS_URL . '/images/no-image.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($relProd['name']); ?>">
                    </div>
                    <div class="product-body">
                        <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $relProd['id']; ?>" 
                           class="product-title">
                            <?php echo htmlspecialchars($relProd['name']); ?>
                        </a>
                        <div class="product-price">
                            <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($relProd['price'], 2)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function addProductToCart() {
    const quantity = parseInt(document.getElementById('product-quantity').value);
    addToCart(<?php echo $productId; ?>, quantity);
}
</script>

<?php include 'includes/footer.php'; ?>
