<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

// Fetch all products
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
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
    <title>Admin Dashboard - Gaming Gear Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .table-container {
            overflow-x: auto;
            background-color: #1e1e1e;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #e0e0e0;
        }

        th,
        td {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #333;
        }

        th {
            background-color: #2c2c2c;
            color: #00e676;
            font-weight: 600;
        }

        tr:hover {
            background-color: #2c2c2c;
        }

        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            font-weight: 600;
        }

        .edit-link {
            color: #3b82f6;
        }

        /* Blue */
        .delete-link {
            color: #ff5252;
        }

        /* Red */
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">
            <h1>Admin Dashboard</h1>
        </div>
        <nav>
            <a href="index.php" class="btn"
                style="background-color: transparent; border: 1px solid #00e676; color: #00e676; margin-right: 10px;">View
                Store</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </nav>
    </header>

    <div class="container">
        <div class="admin-header">
            <h2>Product Management</h2>
            <a href="add_product.php" class="btn">Add New Product</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php echo $product['id']; ?>
                                </td>
                                <td>
                                    <?php if ($product['image_url']): ?>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product"
                                            class="product-thumb">
                                    <?php else: ?>
                                        <span>No Img</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </td>
                                <td>฿
                                    <?php echo number_format($product['price'], 2); ?>
                                </td>
                                <td>
                                    <?php echo $product['stock']; ?>
                                </td>
                                <td class="action-links">
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="edit-link">Edit</a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="delete-link"
                                        onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>