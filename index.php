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
    <style>
        /* Additional inline styles for quick setup, ideally move to style.css */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1f1f1f;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #00e676;
            /* Vibrant Green */
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background-color: #1e1e1e;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 230, 118, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #333;
        }

        .product-info {
            padding: 20px;
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #fff;
        }

        .product-category {
            color: #b0b0b0;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-price {
            font-size: 1.5rem;
            color: #00e676;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            background-color: #00e676;
            color: #121212;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.2s;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .btn:hover {
            background-color: #00c853;
        }
    </style>
</head>

<body>

    <header>
        <h1>Gaming Gear Store</h1>
        <p>Ultimate equipment for your ultimate performance</p>
    </header>

    <div class="container">
        <div class="product-grid">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <div class="product-info">
                            <div class="product-category">
                                <?php echo htmlspecialchars($product['category']); ?>
                            </div>
                            <h2 class="product-title">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h2>
                            <div class="product-price">฿
                                <?php echo number_format($product['price'], 2); ?>
                            </div>
                            <a href="#" class="btn">Add to Cart</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>