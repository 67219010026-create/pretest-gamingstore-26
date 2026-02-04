<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'place_order' && !empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        try {
            $pdo->beginTransaction();

            // Create Order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$user_id, $total_price]);
            $order_id = $pdo->lastInsertId();

            // Create Order Items and Update Stock
            foreach ($_SESSION['cart'] as $item) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);

                // Reduce Stock
                $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['id']]);
            }

            $pdo->commit();

            // Clear Cart
            unset($_SESSION['cart']);

            header("Location: my_orders.php");
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            die("Error placing order: " . $e->getMessage());
        }
    }
}
?>