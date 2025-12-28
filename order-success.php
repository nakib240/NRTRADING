<?php
/**
 * NR TRADING - Order Success Page
 */

require_once 'includes/init.php';

$orderNumber = isset($_GET['order']) ? sanitize($_GET['order']) : '';

if (!$orderNumber) {
    redirect(SITE_URL);
}

// Get order details
$db->query("SELECT * FROM orders WHERE order_number = :order_number")
   ->bind(':order_number', $orderNumber);
$order = $db->fetch();

if (!$order) {
    redirect(SITE_URL);
}

$pageTitle = 'Order Successful - NR TRADING';

include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Order Placed Successfully!</h2>
                    <p class="lead mb-4">Thank you for your order. We have received your order and will process it soon.</p>
                    
                    <div class="alert alert-info">
                        <h5>Order Number: <strong><?php echo htmlspecialchars($order['order_number']); ?></strong></h5>
                        <p class="mb-0">Please save this order number for tracking your order.</p>
                    </div>
                    
                    <div class="row text-start my-4">
                        <div class="col-md-6">
                            <h6>Order Details:</h6>
                            <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Order Summary:</h6>
                            <p class="mb-1"><strong>Subtotal:</strong> <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['subtotal'], 2)); ?></p>
                            <p class="mb-1"><strong>Shipping:</strong> <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['shipping_cost'], 2)); ?></p>
                            <p class="mb-1"><strong>Total:</strong> <span class="text-primary"><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['total'], 2)); ?></span></p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="<?php echo SITE_URL; ?>/tracking.php?order=<?php echo $order['order_number']; ?>" class="btn btn-primary">
                            <i class="bi bi-box-seam me-2"></i> Track Order
                        </a>
                        <a href="<?php echo SITE_URL; ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
