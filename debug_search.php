<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'config/config.php';
require_once 'config/database.php';

try {
    $db = new Database();
    
    // Check total products
    $totalResult = $db->query("SELECT COUNT(*) as count FROM products")->fetch();
    echo "Total products in database: " . $totalResult['count'] . "<br><br>";
    
    // Check active products
    $activeResult = $db->query("SELECT COUNT(*) as count FROM products WHERE status = 1")->fetch();
    echo "Active products (status=1): " . $activeResult['count'] . "<br><br>";
    
    // Show first 5 products
    echo "First 5 products:<br>";
    $products = $db->query("SELECT id, name, status, category_id FROM products LIMIT 5")->fetchAll();
    foreach ($products as $p) {
        echo "- ID: {$p['id']}, Name: {$p['name']}, Status: {$p['status']}, Category: {$p['category_id']}<br>";
    }
    
    // Test Bengali search
    echo "<br><br>Testing Bengali search for 'চাল' (rice):<br>";
    $stmt = $db->query("SELECT p.id, p.name FROM products p WHERE p.status = 1 AND (p.name LIKE :search_name OR p.description LIKE :search_desc)");
    $stmt->bind(':search_name', '%চাল%');
    $stmt->bind(':search_desc', '%চাল%');
    $results = $stmt->fetchAll();
    echo "Found: " . count($results) . " products<br>";
    foreach ($results as $r) {
        echo "- {$r['name']}<br>";
    }
    
    // Test English search
    echo "<br><br>Testing English search for 'rice':<br>";
    $stmt2 = $db->query("SELECT p.id, p.name FROM products p WHERE p.status = 1 AND (p.name LIKE :search_name OR p.description LIKE :search_desc)");
    $stmt2->bind(':search_name', '%rice%');
    $stmt2->bind(':search_desc', '%rice%');
    $results2 = $stmt2->fetchAll();
    echo "Found: " . count($results2) . " products<br>";
    foreach ($results2 as $r) {
        echo "- {$r['name']}<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
