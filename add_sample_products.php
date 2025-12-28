<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'config/config.php';
require_once 'config/database.php';

try {
    $db = new Database();
    
    echo "<h2>Adding Sample Products</h2>";
    
    // Check if products already exist
    $count = $db->query("SELECT COUNT(*) as count FROM products")->fetch();
    if ($count['count'] > 0) {
        echo "<p>Products already exist: {$count['count']}</p>";
        echo "<p><a href='index.php'>Go to Homepage</a></p>";
        exit;
    }
    
    // Check categories
    $categories = $db->query("SELECT * FROM categories WHERE status = 1")->fetchAll();
    if (empty($categories)) {
        echo "<p style='color: red;'>No categories found! Please add categories first.</p>";
        echo "<p><a href='admin/categories.php'>Add Categories</a></p>";
        exit;
    }
    
    echo "<p>Found " . count($categories) . " categories</p>";
    
    // Sample products in Bengali
    $sampleProducts = [
        [
            'name' => 'বাসমতী চাল',
            'description' => 'প্রিমিয়াম বাসমতী চাল - ১ কেজি',
            'price' => 150.00,
            'stock' => 100,
            'category_id' => $categories[0]['id']
        ],
        [
            'name' => 'মিনিকেট চাল',
            'description' => 'উচ্চমানের মিনিকেট চাল - ১ কেজি',
            'price' => 65.00,
            'stock' => 200,
            'category_id' => $categories[0]['id']
        ],
        [
            'name' => 'সয়াবিন তেল',
            'description' => 'বিশুদ্ধ সয়াবিন তেল - ১ লিটার',
            'price' => 180.00,
            'stock' => 50,
            'category_id' => $categories[0]['id']
        ],
        [
            'name' => 'আটা',
            'description' => 'খাঁটি গমের আটা - ১ কেজি',
            'price' => 55.00,
            'stock' => 150,
            'category_id' => $categories[0]['id']
        ],
        [
            'name' => 'চিনি',
            'description' => 'সাদা চিনি - ১ কেজি',
            'price' => 75.00,
            'stock' => 100,
            'category_id' => $categories[0]['id']
        ]
    ];
    
    foreach ($sampleProducts as $product) {
        $slug = strtolower(trim(preg_replace('/[\s]+/', '-', $product['name']), '-'));
        
        $db->query("INSERT INTO products (name, slug, description, price, stock, category_id, status, is_featured) 
                    VALUES (:name, :slug, :description, :price, :stock, :category_id, 1, 1)")
           ->bind(':name', $product['name'])
           ->bind(':slug', $slug)
           ->bind(':description', $product['description'])
           ->bind(':price', $product['price'])
           ->bind(':stock', $product['stock'])
           ->bind(':category_id', $product['category_id'])
           ->execute();
        
        echo "<p>✅ Added: {$product['name']}</p>";
    }
    
    echo "<hr>";
    echo "<p><strong>Sample products added successfully!</strong></p>";
    echo "<p><a href='index.php'>Go to Homepage</a> | <a href='shop.php'>View Shop</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
