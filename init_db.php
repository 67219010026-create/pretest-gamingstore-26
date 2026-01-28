<?php
require_once 'db.php';

try {
    $sql = file_get_contents('database_setup.sql');
    $pdo->exec($sql);
    echo "Database initialized successfully! Table 'products' created.";
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>