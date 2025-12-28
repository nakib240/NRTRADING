<?php
/**
 * NR TRADING - Shopping Cart Page
 */

require_once 'includes/init.php';

$pageTitle = 'Shopping Cart - NR TRADING';

// Get cart items
$cartItems = [];
$subtotal = 0;

if (!empty($_SESSION['cart'])) {
    $productIds = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $query = "SELECT * FROM products WHERE id IN ($placeholders) AND status = 1";
    $db->query($query);
    foreach ($productIds as $index => $id) {
        $db->bind($index + 1, $id);
    }
    $products = $db->fetchAll();
    
    foreach ($products as $product) {
        $quantity = $_SESSION['cart'][$product['id']];
        $itemTotal = $product['price'] * $quantity;
        $subtotal += $itemTotal;
        
        $cartItems[] = [
            'product' => $product,
            'quantity' => $quantity,
            'total' => $itemTotal
        ];
    }
}

$shippingCost = !empty($cartItems) ? ($siteSettings['shipping_cost'] ?? 60) : 0;
$total = $subtotal + $shippingCost;

include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Shopping Cart</h2>
    
    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            <i class="bi bi-cart-x me-2"></i> Your cart is empty.
            <a href="<?php echo SITE_URL; ?>/shop.php" class="alert-link">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo $item['product']['image'] ? UPLOADS_URL . '/products/' . $item['product']['image'] : ASSETS_URL . '/images/no-image.jpg'; ?>" 
                                                     alt="<?php echo htmlspecialchars($item['product']['name']); ?>" 
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                                <div class="ms-3">
                                                    <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $item['product']['id']; ?>" 
                                                       class="text-decoration-none fw-bold">
                                                        <?php echo htmlspecialchars($item['product']['name']); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($item['product']['price'], 2)); ?></td>
                                        <td>
                                            <div class="quantity-input d-inline-flex">
                                                <button class="btn btn-sm btn-outline-secondary" 
                                                        onclick="updateCartQuantity(<?php echo $item['product']['id']; ?>, <?php echo $item['quantity'] - 1; ?>)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="form-control form-control-sm" 
                                                       value="<?php echo $item['quantity']; ?>" readonly style="width: 60px; text-align: center;">
                                                <button class="btn btn-sm btn-outline-secondary" 
                                                        onclick="updateCartQuantity(<?php echo $item['product']['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="fw-bold"><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($item['total'], 2)); ?></td>
                                        <td>
                                            <button onclick="removeFromCart(<?php echo $item['product']['id']; ?>)" 
                                                    class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($subtotal, 2)); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($shippingCost, 2)); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary"><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($total, 2)); ?></strong>
                        </div>
                        <a href="<?php echo SITE_URL; ?>/checkout.php" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-credit-card me-2"></i> Proceed to Checkout
                        </a>
                        <a href="<?php echo SITE_URL; ?>/shop.php" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
