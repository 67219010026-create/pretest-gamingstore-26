<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = $_GET['id'];
$error = '';
$success = '';

// Fetch product details
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Product not found.");
    }
} catch (PDOException $e) {
    die("Error fetching product: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = trim($_POST['image_url']);

    if (empty($name) || empty($category) || empty($price) || empty($stock)) {
        $error = "All fields except Image URL are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, price = ?, stock = ?, image_url = ? WHERE id = ?");
            if ($stmt->execute([$name, $category, $price, $stock, $image_url, $id])) {
                $success = "Product updated successfully!";
                // Refresh product data
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
            } else {
                $error = "Failed to update product.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Gaming Gear Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-link {
            color: #b0b0b0;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-link:hover {
            color: #fff;
        }

        .success-msg {
            color: #00e676;
            background-color: rgba(0, 230, 118, 0.1);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-msg {
            color: #ff5252;
            background-color: rgba(255, 82, 82, 0.1);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <div class="container">
        <div class="header-actions">
            <a href="admin_dashboard.php" class="back-link">&larr; Back to Dashboard</a>
        </div>

        <div class="form-container">
            <h2 style="margin-bottom: 20px; color: #3b82f6;">Edit Product</h2>

            <?php if ($error): ?>
                <div class="error-msg">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-msg">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category"
                        value="<?php echo htmlspecialchars($product['category']); ?>" required>
                </div>

                <div class="form-group" style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label for="price">Price (THB)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0"
                            value="<?php echo $product['price']; ?>" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="stock">Stock Quantity</label>
                        <input type="number" id="stock" name="stock" min="0" value="<?php echo $product['stock']; ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL</label>
                    <input type="url" id="image_url" name="image_url"
                        value="<?php echo htmlspecialchars($product['image_url']); ?>"
                        placeholder="https://example.com/image.jpg">
                </div>

                <button type="submit" class="btn" style="background-color: #3b82f6;">Update Product</button>
            </form>
        </div>
    </div>

</body>

</html>