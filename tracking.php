<?php
/**
 * NR TRADING - Order Tracking Page
 */

require_once 'includes/init.php';

$pageTitle = 'Track Order - NR TRADING';

$orderNumber = isset($_GET['order']) ? sanitize($_GET['order']) : '';
$order = null;
$orderItems = [];
$statusHistory = [];

if ($orderNumber && $_SERVER['REQUEST_METHOD'] === 'GET' && !empty($orderNumber)) {
    // Get order details
    $db->query("SELECT * FROM orders WHERE order_number = :order_number")
       ->bind(':order_number', $orderNumber);
    $order = $db->fetch();
    
    if ($order) {
        // Get order items
        $orderItems = $db->query("SELECT * FROM order_items WHERE order_id = :order_id")
                        ->bind(':order_id', $order['id'])
                        ->fetchAll();
        
        // Get status history
        $statusHistory = $db->query("
            SELECT osh.*, a.name as admin_name 
            FROM order_status_history osh 
            LEFT JOIN admins a ON osh.updated_by = a.id 
            WHERE osh.order_id = :order_id 
            ORDER BY osh.created_at DESC
        ")->bind(':order_id', $order['id'])->fetchAll();
    }
}

include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Track Your Order</h2>
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Enter Order Number</h5>
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label class="form-label">Order Number</label>
                            <input type="text" name="order" class="form-control" 
                                   value="<?php echo htmlspecialchars($orderNumber); ?>" 
                                   placeholder="NRT20251228XXXXXX" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i> Track Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <?php if ($orderNumber && !$order): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i> Order not found. Please check your order number.
                </div>
            <?php elseif ($order): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Order #<?php echo htmlspecialchars($order['order_number']); ?></h5>
                            <?php echo getOrderStatusBadge($order['order_status']); ?>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Customer Details</h6>
                                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Order Information</h6>
                                <p class="mb-1"><strong>Order Date:</strong> <?php echo formatDate($order['created_at']); ?></p>
                                <p class="mb-1"><strong>Total Amount:</strong> <?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['total'], 2)); ?></p>
                                <p class="mb-1"><strong>Payment:</strong> Cash on Delivery</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Status Timeline -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Order Status Timeline</h5>
                        <div class="order-timeline">
                            <?php
                            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                            $currentStatusIndex = array_search($order['order_status'], $statuses);
                            
                            foreach ($statuses as $index => $status):
                                $isCompleted = $index <= $currentStatusIndex && $order['order_status'] !== 'cancelled';
                                $isActive = $index === $currentStatusIndex;
                                $statusClass = $isCompleted ? 'completed' : ($isActive ? 'active' : '');
                            ?>
                            <div class="timeline-item <?php echo $statusClass; ?>">
                                <div class="timeline-icon">
                                    <?php if ($isCompleted): ?>
                                        <i class="bi bi-check"></i>
                                    <?php else: ?>
                                        <i class="bi bi-circle"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?php echo ucfirst($status); ?></h6>
                                    <?php
                                    $statusDate = null;
                                    foreach ($statusHistory as $history) {
                                        if ($history['status'] === $status) {
                                            $statusDate = $history['created_at'];
                                            break;
                                        }
                                    }
                                    ?>
                                    <?php if ($statusDate): ?>
                                        <small class="text-muted"><?php echo formatDate($statusDate, 'd M Y, h:i A'); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if ($order['order_status'] === 'cancelled'): ?>
                            <div class="timeline-item completed">
                                <div class="timeline-icon bg-danger border-danger">
                                    <i class="bi bi-x"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-danger">Cancelled</h6>
                                    <small class="text-muted"><?php echo formatDate($order['updated_at'], 'd M Y, h:i A'); ?></small>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Items</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($item['price'], 2)); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($item['subtotal'], 2)); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['subtotal'], 2)); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                        <td><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['shipping_cost'], 2)); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong class="text-primary"><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['total'], 2)); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
