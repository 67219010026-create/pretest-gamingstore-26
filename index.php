<?php
require_once 'lang.php';
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}

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
    <title>Gaming Gear Store</title>
    <!-- Use a modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="container flex-between">
            <div class="logo">
                <h1><a href="index.php"><?php echo t('store_name'); ?></a></h1>
            </div>

            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้าที่ต้องการ...">
                <button class="search-btn">🔍</button>
            </div>

            <nav class="flex-between">
                <a href="cart.php" class="btn btn-sm btn-primary">
                    <?php echo t('cart'); ?> (<?php echo $cart_count; ?>)
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-muted" style="color: white;"><?php echo t('welcome'); ?>,
                        <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                        <a href="admin_dashboard.php" class="btn btn-sm btn-primary"><?php echo t('dashboard'); ?></a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-danger btn-sm"><?php echo t('logout'); ?></a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-primary"><?php echo t('login'); ?></a>
                    <a href="register.php" class="btn btn-sm btn-primary"><?php echo t('register'); ?></a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="secondary-nav">
        <div class="container flex-between" style="justify-content: flex-start;">
            <a href="#" class="cat-btn">☰ หมวดหมู่สินค้า</a>
            <nav style="margin-left: 20px;">
                <a href="index.php">สินค้าทั้งหมด</a>
                <a href="#">โปรโมชั่น</a>
                <a href="#">ติดต่อเรา</a>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <div class="hero">
            <h2>Peak CPU - Ultimate Gaming Gear</h2>
            <p>High-performance accessories for the serious gamer.</p>
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
                            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>"
                                class="btn btn-primary w-full"><?php echo t('add_to_cart'); ?></a>
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