<?php
/**
 * NR TRADING - Login & Register Page
 */

require_once 'includes/init.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(SITE_URL . '/account.php');
}

$pageTitle = 'Login / Register - NR TRADING';
$error = '';
$success = '';

// Handle Login
if (isset($_POST['login'])) {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $db->query("SELECT * FROM users WHERE email = :email AND status = 1")
           ->bind(':email', $email);
        $user = $db->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION[USER_SESSION] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            redirect(SITE_URL . '/account.php');
        } else {
            $error = 'Invalid email or password';
        }
    }
}

// Handle Registration
if (isset($_POST['register'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if email already exists
        $db->query("SELECT id FROM users WHERE email = :email")
           ->bind(':email', $email);
        if ($db->fetch()) {
            $error = 'Email already registered';
        } else {
            // Register user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $db->query("INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)")
               ->bind(':name', $name)
               ->bind(':email', $email)
               ->bind(':phone', $phone)
               ->bind(':password', $hashedPassword);
            
            if ($db->execute()) {
                $success = 'Registration successful! Please login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Login Form -->
                        <div class="col-md-6 p-5 border-end">
                            <h3 class="mb-4">Login</h3>
                            
                            <?php if ($error && isset($_POST['login'])): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                                </button>
                            </form>
                        </div>
                        
                        <!-- Register Form -->
                        <div class="col-md-6 p-5">
                            <h3 class="mb-4">Register</h3>
                            
                            <?php if ($error && isset($_POST['register'])): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" 
                                           minlength="6" required>
                                    <small class="text-muted">At least 6 characters</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <button type="submit" name="register" class="btn btn-success w-100">
                                    <i class="bi bi-person-plus me-2"></i> Register
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
