<?php
require_once 'db.php';

echo "Updating database...<br>";

try {
    // 1. Add discount_codes table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS discount_codes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL UNIQUE,
            discount_percent INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'discount_codes' checked/created successfully.<br>";

    // 2. Add new columns to orders table if they don't exist
    // MySQL alter table add column if not exists is not directly supported in single statement in older versions, 
    // but we can try/catch it or check the information_schema

    // Check if original_price exists
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'original_price'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN original_price DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER total_price");
        echo "Column 'original_price' added to 'orders' table.<br>";
    } else {
        echo "Column 'original_price' already exists.<br>";
    }

    // Check if discount_amount exists
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'discount_amount'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN discount_amount DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER original_price");
        echo "Column 'discount_amount' added to 'orders' table.<br>";
    } else {
        echo "Column 'discount_amount' already exists.<br>";
    }

    echo "<br><strong>Database update completed successfully!</strong>";
    echo "<br><br><a href='index.php'>Go to Homepage</a>";

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>