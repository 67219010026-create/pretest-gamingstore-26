<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Gear Store</title>
    <!-- Use a modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="container flex-between" style="padding: 0;">
            <div class="logo">
                <h1>Gaming Gear Store</h1>
            </div>
            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-muted" style="margin-right: 15px;">Welcome,
                        <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                        <a href="admin_dashboard.php" class="btn btn-sm" style="margin-right: 10px;">Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm" style="margin-right: 10px;">Login</a>
                    <a href="register.php" class="btn btn-sm">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="hero">
            <h2>Ultimate Equipment</h2>
            <p>For your ultimate performance</p>
        </div>

        <div class="product-grid">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-details">
                            <div class="product-category">
                                <?php echo htmlspecialchars($product['category']); ?>
                            </div>
                            <h2 class="product-name">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h2>
                            <div class="product-price">฿
                                <?php echo number_format($product['price'], 2); ?>
                            </div>
                            <a href="#" class="btn w-full">Add to Cart</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center" style="grid-column: 1 / -1;">No products found.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>