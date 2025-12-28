<?php
/**
 * NR TRADING - Checkout Page
 */

require_once 'includes/init.php';

$pageTitle = 'Checkout - NR TRADING';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    redirect(SITE_URL . '/cart.php');
}

// Get cart items and calculate totals
$cartItems = [];
$subtotal = 0;

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

// Default shipping cost (will be updated based on location)
$shippingCost = 60; // Inside Chittagong default
$total = $subtotal + $shippingCost;

// Pre-fill data if logged in
$user = getLoggedInUser();

$error = '';
$success = '';

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city'] ?? '');
    $postal_code = sanitize($_POST['postal_code'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    $shipping_location = sanitize($_POST['shipping_location'] ?? 'inside');
    
    // Calculate shipping cost based on location
    $shippingCost = ($shipping_location === 'outside') ? 120 : 60;
    $total = $subtotal + $shippingCost;
    
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $db->beginTransaction();
            
            // Generate order number
            $orderNumber = generateOrderNumber();
            
            // Insert order
            $db->query("
                INSERT INTO orders (user_id, order_number, customer_name, customer_email, customer_phone, 
                                   delivery_address, city, postal_code, subtotal, shipping_cost, total, order_notes)
                VALUES (:user_id, :order_number, :name, :email, :phone, :address, :city, :postal_code, 
                        :subtotal, :shipping, :total, :notes)
            ")->bind(':user_id', $user['id'] ?? null)
               ->bind(':order_number', $orderNumber)
               ->bind(':name', $name)
               ->bind(':email', $email)
               ->bind(':phone', $phone)
               ->bind(':address', $address)
               ->bind(':city', $city)
               ->bind(':postal_code', $postal_code)
               ->bind(':subtotal', $subtotal)
               ->bind(':shipping', $shippingCost)
               ->bind(':total', $total)
               ->bind(':notes', $notes)
               ->execute();
            
            $orderId = $db->lastInsertId();
            
            // Insert order items
            foreach ($cartItems as $item) {
                $db->query("
                    INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal)
                    VALUES (:order_id, :product_id, :product_name, :price, :quantity, :subtotal)
                ")->bind(':order_id', $orderId)
                   ->bind(':product_id', $item['product']['id'])
                   ->bind(':product_name', $item['product']['name'])
                   ->bind(':price', $item['product']['price'])
                   ->bind(':quantity', $item['quantity'])
                   ->bind(':subtotal', $item['total'])
                   ->execute();
                
                // Update stock
                $db->query("UPDATE products SET stock = stock - :quantity WHERE id = :id")
                   ->bind(':quantity', $item['quantity'])
                   ->bind(':id', $item['product']['id'])
                   ->execute();
            }
            
            // Add initial status history
            $db->query("
                INSERT INTO order_status_history (order_id, status, note)
                VALUES (:order_id, 'pending', 'Order placed')
            ")->bind(':order_id', $orderId)->execute();
            
            $db->commit();
            
            // Clear cart
            $_SESSION['cart'] = [];
            
            // Redirect to success page
            redirect(SITE_URL . '/order-success.php?order=' . $orderNumber);
            
        } catch (Exception $e) {
            $db->rollback();
            $error = 'Failed to place order. Please try again.';
        }
    }
}

include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="row">
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Billing & Shipping Details</h5>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?php echo $user['name'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php echo $user['email'] ?? ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Delivery Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Shipping Location <span class="text-danger">*</span></label>
                            <select name="shipping_location" id="shippingLocation" class="form-select" required>
                                <option value="inside">Inside Chittagong (৳60)</option>
                                <option value="outside">Outside Chittagong (৳120)</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Order Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" 
                                      placeholder="Special instructions for delivery..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Your Order</h5>
                        <hr>
                        
                        <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <?php echo htmlspecialchars($item['product']['name']); ?> 
                                <small class="text-muted">× <?php echo $item['quantity']; ?></small>
                            </span>
                            <span><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($item['total'], 2)); ?></span>
                        </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal"><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($subtotal, 2)); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span id="shippingCost"><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($shippingCost, 2)); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary" id="orderTotal"><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($total, 2)); ?></strong>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Payment Method:</strong> Cash on Delivery
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-2"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Bengali number conversion
function toBengaliDigits(str) {
    const map = {
        '0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪',
        '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'
    };
    return String(str).replace(/[0-9]/g, d => map[d]);
}

// Update shipping cost when location changes
document.getElementById('shippingLocation').addEventListener('change', function() {
    const subtotal = <?php echo $subtotal; ?>;
    const shippingCost = this.value === 'outside' ? 120 : 60;
    const total = subtotal + shippingCost;
    
    document.getElementById('shippingCost').textContent = '৳ ' + toBengaliDigits(shippingCost.toFixed(2));
    document.getElementById('orderTotal').textContent = '৳ ' + toBengaliDigits(total.toFixed(2));
});
</script>

<?php include 'includes/footer.php'; ?>
