<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

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
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $category, $price, $stock, $image_url])) {
                $success = "Product added successfully!";
            } else {
                $error = "Failed to add product.";
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
    <title>Add Product - Gaming Gear Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="logo">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <div class="container">
        <div class="header-actions">
            <a href="admin_dashboard.php" class="back-link">&larr; Back to Dashboard</a>
        </div>

        <div class="auth-container" style="max-width: 600px;">
            <h2 class="auth-title">Add New Product</h2>

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
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" required>
                </div>

                <div class="form-group flex-between" style="gap: 20px;">
                    <div class="w-full">
                        <label for="price">Price (THB)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="w-full">
                        <label for="stock">Stock Quantity</label>
                        <input type="number" id="stock" name="stock" min="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL</label>
                    <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                </div>

                <button type="submit" class="btn">Add Product</button>
            </form>
        </div>
    </div>

</body>

</html>