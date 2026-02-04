<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$grand_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $grand_total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Gaming Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 800px; margin-top: 4rem;">
        <header style="justify-content: space-between; margin-bottom: 2rem;">
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE
            </div>
            <a href="cart.php" class="btn btn-sm"
                style="background: transparent; border: 1px solid var(--text-secondary);">
                Back to Cart
            </a>
        </header>

        <h2 style="margin-bottom: 2rem;"><i class="fa-solid fa-credit-card"></i> Checkout</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div class="product-card" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem;">Shipping Details</h3>
                <p style="color: var(--text-secondary); margin-bottom: 0.5rem;"><strong>Name:</strong>
                    <?php echo htmlspecialchars($user['name']); ?>
                </p>
                <p style="color: var(--text-secondary); margin-bottom: 0.5rem;"><strong>Address:</strong>
                    <?php echo nl2br(htmlspecialchars($user['address'])); ?>
                </p>
                <p style="color: var(--text-secondary); margin-bottom: 0.5rem;"><strong>Phone:</strong>
                    <?php echo htmlspecialchars($user['phone']); ?>
                </p>
                <p style="color: var(--text-secondary); margin-bottom: 1rem;"><strong>Email:</strong>
                    <?php echo htmlspecialchars($user['email']); ?>
                </p>

                <a href="profile.php" class="btn btn-sm"
                    style="background: transparent; border: 1px solid var(--accent-primary);">Edit Details</a>
            </div>

            <div class="product-card" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem;">Order Summary</h3>
                <ul style="list-style: none; margin-bottom: 1rem;">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <li
                            style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--text-secondary);">
                            <span>
                                <?php echo htmlspecialchars($item['name']); ?> x
                                <?php echo $item['quantity']; ?>
                            </span>
                            <span>$
                                <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div
                    style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem; display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold;">
                    <span>Total</span>
                    <span style="color: var(--success);">$
                        <?php echo number_format($grand_total, 2); ?>
                    </span>
                </div>

                <form action="order_actions.php" method="POST" style="margin-top: 2rem;">
                    <input type="hidden" name="action" value="place_order">
                    <button type="submit" class="btn" style="width: 100%; justify-content: center;">Confirm
                        Order</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>