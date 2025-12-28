<?php
/**
 * NR TRADING - Customer Account Page
 */

require_once 'includes/init.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect(SITE_URL . '/login.php');
}

$user = getLoggedInUser();
$pageTitle = 'My Account - NR TRADING';

// Get user orders
$orders = $db->query("
    SELECT * FROM orders 
    WHERE user_id = :user_id 
    ORDER BY created_at DESC
")->bind(':user_id', $user['id'])->fetchAll();

include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">My Account</h2>
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                    </div>
                    <h5 class="card-title text-center"><?php echo htmlspecialchars($user['name']); ?></h5>
                    <p class="text-center text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    <hr>
                    <a href="<?php echo SITE_URL; ?>/logout.php" class="btn btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order History</h5>
                    <hr>
                    
                    <?php if (empty($orders)): ?>
                        <div class="alert alert-info">
                            You have no orders yet. <a href="<?php echo SITE_URL; ?>/shop.php">Start shopping!</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                        <td><?php echo formatDate($order['created_at']); ?></td>
                                        <td><?php echo CURRENCY_SYMBOL; ?> <?php echo toBengaliNumber(number_format($order['total'], 2)); ?></td>
                                        <td><?php echo getOrderStatusBadge($order['order_status']); ?></td>
                                        <td>
                                            <a href="<?php echo SITE_URL; ?>/tracking.php?order=<?php echo $order['order_number']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
