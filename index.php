<?php
require_once 'db.php';

// Prepare statement for products
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    // If table doesn't exist (Error 42S02), create it and retry
    if ($e->getCode() == '42S02') {
        $sql = file_get_contents('database_setup.sql');
        $pdo->exec($sql);
        
        $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
        $products = $stmt->fetchAll();
    } else {
        // Re-throw if it's another error
        throw $e;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Store Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in">
        <header>
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE
            </div>
            <a href="product_form.php" class="btn">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </header>

        <!-- Stats Overview (Optional Placeholder) -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="product-card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2rem; color: var(--accent-primary);"><i class="fa-solid fa-box"></i></div>
                <div>
                    <div style="color: var(--text-secondary); font-size: 0.8rem;">Total Products</div>
                    <div style="font-size: 1.5rem; font-weight: bold;">
                        <?php echo count($products); ?>
                    </div>
                </div>
            </div>
            <div class="product-card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2rem; color: var(--accent-secondary);"><i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div>
                    <div style="color: var(--text-secondary); font-size: 0.8rem;">Total Inventory Value</div>
                    <div style="font-size: 1.5rem; font-weight: bold;">
                        $
                        <?php
                        $total = 0;
                        foreach ($products as $p)
                            $total += $p['price'] * $p['stock'];
                        echo number_format($total, 2);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (count($products) > 0): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($product['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <i class="fa-solid fa-image" style="font-size: 3rem; opacity: 0.5;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="product-details">
                            <div class="product-category">
                                <?php echo htmlspecialchars($product['category']); ?>
                            </div>
                            <h3 class="product-name">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h3>
                            <div class="product-meta">
                                <div class="product-price">$
                                    <?php echo number_format($product['price'], 2); ?>
                                </div>
                                <div class="product-stock">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <?php echo $product['stock']; ?>
                                </div>
                            </div>
                            <div class="actions">
                                <a href="product_form.php?id=<?php echo $product['id']; ?>" class="btn btn-sm"
                                    style="flex: 1; text-align: center;">Edit</a>
                                <form action="actions.php" method="POST" style="flex: 1;"
                                    onsubmit="return confirm('Delete this product?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" style="width: 100%;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div
                style="text-align: center; padding: 4rem; color: var(--text-secondary); background: var(--card-bg); border-radius: 12px;">
                <i class="fa-solid fa-ghost" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3>No products found</h3>
                <p>Get started by adding your first gaming product.</p>
                <div style="margin-top: 1rem;">
                    <a href="http://localhost:8001/init_db.php" target="_blank" class="btn btn-sm"
                        style="background: transparent; border: 1px solid var(--accent-secondary); color: var(--accent-secondary);">
                        <i class="fa-solid fa-database"></i> Initialize Database (Run Once)
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>