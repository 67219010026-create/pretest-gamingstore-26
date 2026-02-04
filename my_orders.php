<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Gaming Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 900px; margin-top: 4rem;">
        <header style="justify-content: space-between; margin-bottom: 2rem;">
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE
            </div>
            <a href="index.php" class="btn btn-sm"
                style="background: transparent; border: 1px solid var(--text-secondary);">
                Back to Store
            </a>
        </header>

        <h2 style="margin-bottom: 2rem;"><i class="fa-solid fa-box-open"></i> My Orders</h2>

        <?php if (count($orders) === 0): ?>
            <div style="text-align: center; padding: 4rem; background: var(--card-bg); border-radius: 12px;">
                <p style="color: var(--text-secondary);">You haven't placed any orders yet.</p>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <?php foreach ($orders as $order):
                    $stmt_items = $pdo->prepare("
                        SELECT oi.*, p.name 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?
                    ");
                    $stmt_items->execute([$order['id']]);
                    $items = $stmt_items->fetchAll();
                    ?>
                    <div class="product-card" style="padding: 1.5rem;">
                        <div
                            style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem; margin-bottom: 1rem;">
                            <div>
                                <div style="font-weight: bold; font-size: 1.1rem;">Order #
                                    <?php echo $order['id']; ?>
                                </div>
                                <div style="color: var(--text-secondary); font-size: 0.85rem;">
                                    <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: bold; color: var(--success);">$
                                    <?php echo number_format($order['total_price'], 2); ?>
                                </div>
                                <span
                                    style="padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; background: rgba(255,255,255,0.1); color: var(--accent-secondary); text-transform: uppercase;">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </div>
                        </div>

                        <ul style="list-style: none;">
                            <?php foreach ($items as $item): ?>
                                <li
                                    style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--text-secondary);">
                                    <span>
                                        <?php echo htmlspecialchars($item['name']); ?> x
                                        <?php echo $item['quantity']; ?>
                                    </span>
                                    <span>$
                                        <?php echo number_format($item['price'], 2); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>