<?php
require_once 'db.php';

$product = [
    'id' => '',
    'name' => '',
    'category' => '',
    'price' => '',
    'stock' => '',
    'image_url' => ''
];

$is_edit = false;

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $fetched = $stmt->fetch();
    if ($fetched) {
        $product = $fetched;
        $is_edit = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $is_edit ? 'Edit Product' : 'Add New Product'; ?> - Gaming Store
    </title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 600px; margin-top: 4rem;">
        <header style="border-bottom: none; margin-bottom: 1rem;">
            <div class="logo">
                <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        </header>

        <div
            style="background: var(--card-bg); padding: 2rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.05);">
            <h2 style="margin-bottom: 2rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 1rem;">
                <?php echo $is_edit ? 'Edit Product' : 'Add New Product'; ?>
            </h2>

            <form action="actions.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">
                <?php if ($is_edit): ?>
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required
                        value="<?php echo htmlspecialchars($product['name']); ?>" placeholder="e.g. PS5 Console">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="Console" <?php if ($product['category'] == 'Console')
                            echo 'selected'; ?>>Console
                        </option>
                        <option value="Game" <?php if ($product['category'] == 'Game')
                            echo 'selected'; ?>>Game</option>
                        <option value="Accessory" <?php if ($product['category'] == 'Accessory')
                            echo 'selected'; ?>
                            >Accessory</option>
                        <option value="Merchandise" <?php if ($product['category'] == 'Merchandise')
                            echo 'selected'; ?>
                            >Merchandise</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Price ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required
                            value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" required
                            value="<?php echo htmlspecialchars($product['stock']); ?>" placeholder="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Image URL</label>
                    <input type="url" name="image_url" class="form-control"
                        value="<?php echo htmlspecialchars($product['image_url']); ?>"
                        placeholder="https://example.com/image.jpg">
                    <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">Enter a valid image
                        URL for the product cover.</small>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn" style="width: 100%;">
                        <i class="fa-solid fa-save"></i>
                        <?php echo $is_edit ? 'Update Product' : 'Save Product'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>