<?php
require_once 'lang.php';
require_once 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

// Ensure cart is not empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;
$error = '';

// Calculate total based on latest DB prices
$subtotal = 0;
$cart_products = [];
$ids = array_map('intval', array_keys($_SESSION['cart']));
$placeholders = str_repeat('?,', count($ids) - 1) . '?';

try {
    $stmt = $pdo->prepare("SELECT id, price, stock FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        // Ensure not buying more than stock
        if ($qty > $product['stock']) {
            $qty = $product['stock'];
            $_SESSION['cart'][$product['id']] = $qty; // Update session just in case
        }
        $subtotal += ($product['price'] * $qty);
        $cart_products[$product['id']] = [
            'price' => $product['price'],
            'quantity' => $qty
        ];
    }
} catch (PDOException $e) {
    $error = "Error verifying products.";
}

if (!$error && empty($cart_products)) {
    $error = "Cart items are invalid.";
}

// Calculate discount and total
$discount_percent = $_SESSION['discount']['percent'] ?? 0;
$discount_amount = ($subtotal * $discount_percent) / 100;
$total_price = $subtotal - $discount_amount;

// Process the order
if (!$error && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // 1. Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, original_price, discount_amount, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$user_id, $total_price, $subtotal, $discount_amount]);
        $order_id = $pdo->lastInsertId();

        // 2. Insert into order_items and update stock
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        foreach ($cart_products as $id => $item) {
            $stmtItem->execute([$order_id, $id, $item['quantity'], $item['price']]);
            $stmtStock->execute([$item['quantity'], $id]);
        }

        $pdo->commit();
        $success = true;

        // Clear the cart
        unset($_SESSION['cart']);
        unset($_SESSION['discount']);

    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Failed to process order: " . $e->getMessage();
    }
}

// Cart Count for header
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo t('checkout'); ?> - Gaming Gear Store
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="container flex-between" style="padding: 0;">
            <div class="logo">
                <h1><a href="index.php" style="color: white; text-decoration: none;">Gaming Gear Store</a></h1>
            </div>
            <nav>
                <?php if (!$success): ?>
                    <a href="cart.php" class="btn btn-sm"
                        style="margin-right: 15px; background-color: #f1c40f; color: #333; font-weight: bold;">
                        <?php echo t('cart'); ?> (
                        <?php echo $cart_count; ?>)
                    </a>
                <?php endif; ?>
                <span class="text-muted" style="margin-right: 15px;">
                    <?php echo t('welcome'); ?>,
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="logout.php" class="btn btn-danger btn-sm">
                    <?php echo t('logout'); ?>
                </a>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 40px;">

        <?php if ($success): ?>
            <div
                style="background: white; padding: 40px; text-align: center; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div style="color: #28a745; font-size: 3rem; margin-bottom: 20px;">✓</div>
                <h2 style="margin-bottom: 20px; color: #28a745;">
                    <?php echo t('checkout_success'); ?>
                </h2>
                <p style="font-size: 1.1rem; color: #666; margin-bottom: 30px;">Your order #
                    <?php echo $order_id; ?> has been placed successfully.
                </p>
                <a href="index.php" class="btn">
                    <?php echo t('continue_shopping'); ?>
                </a>
            </div>
        <?php else: ?>
            <h2>
                <?php echo t('checkout'); ?>
            </h2>

            <?php if ($error): ?>
                <div
                    style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-top: 20px;">
                <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <h3 style="margin-bottom: 20px;">Shipping & Payment</h3>
                    <p style="color: #666; margin-bottom: 15px;">You are checking out as <strong>
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </strong>.</p>
                    <p style="color: #666; margin-bottom: 20px;">Please confirm your order details.</p>

                    <form method="POST" action="" id="checkoutForm">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 10px;">Select Payment
                                Method:</label>

                            <div style="margin-bottom: 10px;">
                                <input type="radio" id="pay_cod" name="payment_method" value="cod" checked
                                    onchange="toggleQR(false)">
                                <label for="pay_cod">Cash on Delivery (COD)</label>
                            </div>

                            <div style="margin-bottom: 10px;">
                                <input type="radio" id="pay_card" name="payment_method" value="card"
                                    onchange="toggleQR(false)">
                                <label for="pay_card">Credit Card</label>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <input type="radio" id="pay_qr" name="payment_method" value="qr" onchange="toggleQR(true)">
                                <label for="pay_qr">QR Code Scan</label>
                            </div>

                            <div id="qr_section"
                                style="display: none; text-align: center; margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                                <p style="margin-bottom: 10px; font-weight: bold;">Scan to Pay</p>
                                <!-- Dummy QR Code -->
                                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg"
                                    alt="Dummy QR Code" style="width: 150px; height: 150px; margin: 0 auto;">
                                <p style="margin-top: 10px; font-size: 0.9rem; color: #666;">Please transfer
                                    <?php echo number_format($total_price, 2); ?> ฿
                                </p>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary"
                                style="width: 100%; padding: 15px; font-size: 1.1rem; margin-bottom: 10px;">
                                <?php echo t('payment_button'); ?>
                            </button>
                            <a href="cart.php" class="btn btn-secondary"
                                style="display: block; width: 100%; text-align: center; padding: 15px; font-size: 1.1rem; background-color: #6c757d; color: white; text-decoration: none; box-sizing: border-box;">
                                <?php echo t('back_to_cart'); ?>
                            </a>
                        </div>
                    </form>

                    <script>
                        function toggleQR(show) {
                            document.getElementById('qr_section').style.display = show ? 'block' : 'none';
                        }
                    </script>
                </div>

                <!-- Order Summary -->
                <div
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); height: fit-content;">
                    <h3 style="margin-bottom: 20px; font-size: 1.2rem;">
                        <?php echo t('order_summary'); ?>
                    </h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #666;">
                        <span>
                            <?php echo t('subtotal'); ?>
                        </span>
                        <span>฿
                            <?php echo number_format($subtotal, 2); ?>
                        </span>
                    </div>

                    <?php if ($discount_percent > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #28a745;">
                            <span>
                                <?php echo t('discount'); ?> (
                                <?php echo $discount_percent; ?>%)
                            </span>
                            <span>-฿
                                <?php echo number_format($discount_amount, 2); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div
                        style="display: flex; justify-content: space-between; margin-top: 15px; border-top: 2px solid #eee; padding-top: 15px; font-size: 1.25rem; font-weight: 700; color: #1a1a1a;">
                        <span>
                            <?php echo t('total'); ?>
                        </span>
                        <span>฿
                            <?php echo number_format($total_price, 2); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>