<?php
require_once 'db.php';

try {
    $sql = file_get_contents('database_setup.sql');

    // Split SQL into individual queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($queries as $query) {
        if (!empty($query)) {
            $pdo->exec($query);
            echo "<p style='color: green;'>Successfully executed: " . htmlspecialchars(substr($query, 0, 50)) . "...</p>";
        }
    }
    echo "<h2>Database initialized successfully!</h2>";
    echo "<p>All tables (products, users, orders, order_items) have been checked/created.</p>";
    echo "<a href='index.php'>Go to Home</a>";
} catch (PDOException $e) {
    die("<h2 style='color: red;'>DB ERROR: " . $e->getMessage() . "</h2>");
}
?>