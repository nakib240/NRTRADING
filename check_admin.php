<?php
require_once 'config/config.php';
require_once 'config/database.php';

header('Content-Type: text/html; charset=utf-8');
echo '<h2>Admin Account Check</h2>';

try {
    $db = new Database();

    // Check if admin exists
    $admin = $db->query('SELECT id, name, email, status FROM admins WHERE email = "admin@nrtrading.com"')->fetch();

    if ($admin) {
        echo "<p>✅ Admin exists:</p>";
        echo "<pre>" . print_r($admin, true) . "</pre>";
        echo "<p>Status: " . ($admin['status'] ? 'Active' : 'Inactive') . "</p>";
        
        // Test password
        $testAdmin = $db->query('SELECT password FROM admins WHERE email = "admin@nrtrading.com"')->fetch();
        $passwordWorks = password_verify('admin123', $testAdmin['password']);
        echo "<p>Password 'admin123' works: " . ($passwordWorks ? '✅ YES' : '❌ NO') . "</p>";
        
        if (!$passwordWorks) {
            echo '<hr><p>⚠️ Password doesn\'t match. Resetting to admin123...</p>';
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $db->query("UPDATE admins SET password = :password WHERE email = 'admin@nrtrading.com'")
               ->bind(':password', $hashedPassword)
               ->execute();
            echo '<p>✅ Password reset complete!</p>';
        }
    } else {
        echo "<p>❌ No admin found with email: admin@nrtrading.com</p>";
        echo "<p>Creating admin account...</p>";
        
        // Create admin account
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $db->query("INSERT INTO admins (name, email, password, phone, role, status) VALUES (:name, :email, :password, :phone, :role, :status)")
           ->bind(':name', 'Admin')
           ->bind(':email', 'admin@nrtrading.com')
           ->bind(':password', $hashedPassword)
           ->bind(':phone', '01700000000')
           ->bind(':role', 'super_admin')
           ->bind(':status', 1)
           ->execute();
        
        echo "<p>✅ Admin account created successfully!</p>";
    }
    
    echo '<hr><p><a href="admin/login.php">Go to Admin Login</a></p>';
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
