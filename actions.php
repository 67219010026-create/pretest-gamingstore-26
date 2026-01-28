<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['name'],
                $_POST['category'],
                $_POST['price'],
                $_POST['stock'],
                $_POST['image_url']
            ]);
        } elseif ($action === 'update') {
            $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, price=?, stock=?, image_url=? WHERE id=?");
            $stmt->execute([
                $_POST['name'],
                $_POST['category'],
                $_POST['price'],
                $_POST['stock'],
                $_POST['image_url'],
                $_POST['id']
            ]);
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
            $stmt->execute([$_POST['id']]);
        }

        // Redirect back to dashboard
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>