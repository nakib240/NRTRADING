<?php
/**
 * NR TRADING - Contact Page
 */

require_once 'includes/init.php';

$pageTitle = 'Contact Us - NR TRADING';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields';
    } else {
        // Here you can add email functionality or save to database
        $success = 'Thank you for contacting us! We will get back to you soon.';
    }
}

include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Contact Us</h2>
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Address</h5>
                    <p><?php echo nl2br($siteSettings['site_address'] ?? 'Dhaka, Bangladesh'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-telephone-fill text-primary me-2"></i> Phone</h5>
                    <p><a href="tel:<?php echo $siteSettings['site_phone'] ?? ''; ?>"><?php echo $siteSettings['site_phone'] ?? '01700000000'; ?></a></p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-envelope-fill text-primary me-2"></i> Email</h5>
                    <p><a href="mailto:<?php echo $siteSettings['site_email'] ?? ''; ?>"><?php echo $siteSettings['site_email'] ?? 'info@nrtrading.com'; ?></a></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Send us a Message</h5>
                    <hr>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Your Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
