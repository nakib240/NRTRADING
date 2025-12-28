<?php
/**
 * NR TRADING - Shop Page
 */

require_once 'includes/init.php';

$pageTitle = 'Shop - NR TRADING';

// Get filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'latest';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = PRODUCTS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Build query
$where = ["p.status = 1"];
$params = [];

if ($search) {
    $where[] = "(p.name LIKE :search_name OR p.description LIKE :search_desc)";
    $params[':search_name'] = '%' . $search . '%';
    $params[':search_desc'] = '%' . $search . '%';
}

if ($category) {
    $where[] = "p.category_id = :category";
    $params[':category'] = $category;
}

$whereClause = implode(' AND ', $where);

// Sorting
$orderBy = match($sort) {
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'name' => 'p.name ASC',
    default => 'p.created_at DESC'
};

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM products p WHERE $whereClause";
$countStmt = $db->query($countQuery);
foreach ($params as $key => $value) {
    $countStmt->bind($key, $value);
}
$totalProducts = $countStmt->fetch()['total'];
$totalPages = ceil($totalProducts / $limit);

// Get products
$query = "
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE $whereClause 
    ORDER BY $orderBy 
    LIMIT $limit OFFSET $offset
";
$productStmt = $db->query($query);
foreach ($params as $key => $value) {
    $productStmt->bind($key, $value);
}
$products = $productStmt->fetchAll();

// Get categories for filter
$categories = $db->query("SELECT * FROM categories WHERE status = 1 ORDER BY name")->fetchAll();

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2>Shop</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    
                    <!-- Search -->
                    <form method="GET" action="" class="mb-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search products..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary w-100 mt-2">Search</button>
                    </form>
                    
                    <!-- Categories -->
                    <h6>Categories</h6>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo SITE_URL; ?>/shop.php" 
                           class="list-group-item list-group-item-action <?php echo !$category ? 'active' : ''; ?>">
                            All Categories
                        </a>
                        <?php foreach ($categories as $cat): ?>
                        <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $cat['id']; ?>" 
                           class="list-group-item list-group-item-action <?php echo $category == $cat['id'] ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products -->
        <div class="col-lg-9">
            <!-- Sort & Results -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    Showing <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $totalProducts); ?> 
                    of <?php echo $totalProducts; ?> products
                </div>
                <form method="GET" action="" class="d-flex align-items-center">
                    <?php if ($search): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <?php endif; ?>
                    <?php if ($category): ?>
                        <input type="hidden" name="category" value="<?php echo $category; ?>">
                    <?php endif; ?>
                    <label class="me-2 mb-0">Sort by:</label>
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Latest</option>
                        <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                    </select>
                </form>
            </div>
            
            <!-- Products Grid -->
            <?php if (empty($products)): ?>
                <div class="alert alert-info">No products found.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                    <div class="col-md-4 col-sm-6">
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
                                        <i class="bi bi-check-circle text-success"></i> In Stock
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
                                        <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="mt-5">
                    <?php 
                    $url = SITE_URL . '/shop.php';
                    $queryParams = [];
                    if ($search) $queryParams[] = 'search=' . urlencode($search);
                    if ($category) $queryParams[] = 'category=' . $category;
                    if ($sort) $queryParams[] = 'sort=' . $sort;
                    $url .= '?' . implode('&', $queryParams);
                    echo getPagination($page, $totalPages, $url);
                    ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
