<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $id = $_POST['product_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image = $_POST['image_url'];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'image_url' => $image,
                'quantity' => 1
            ];
        }
    } elseif ($action === 'remove') {
        $id = $_POST['product_id'];
        unset($_SESSION['cart'][$id]);
    }

    // Redirect back to where the user came from
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>