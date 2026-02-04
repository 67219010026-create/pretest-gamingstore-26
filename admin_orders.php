<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
}

$stmt = $pdo->query("
    SELECT o.*, u.fullname as user_name, u.email, u.username
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY created_at DESC
");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Gaming Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 1000px; margin-top: 4rem;">
        <header style="justify-content: space-between; margin-bottom: 2rem;">
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE <span
                    style="font-size: 0.8rem; background: var(--accent-primary); padding: 2px 6px; border-radius: 4px; margin-left: 0.5rem;">ADMIN</span>
            </div>
            <a href="index.php" class="btn btn-sm"
                style="background: transparent; border: 1px solid var(--text-secondary);">
                Back to Dashboard
            </a>
        </header>

        <h2 style="margin-bottom: 2rem;"><i class="fa-solid fa-clipboard-list"></i> Manage Orders</h2>

        <div
            style="background: var(--card-bg); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($order['user_name']); ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">
                                    @<?php echo htmlspecialchars($order['username']); ?></div>
                            </td>
                            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td>
                                <span style="
                                padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; 
                                background: <?php echo $order['status'] === 'completed' ? 'rgba(46, 213, 115, 0.2)' : ($order['status'] === 'cancelled' ? 'rgba(255, 71, 87, 0.2)' : 'rgba(255, 255, 255, 0.1)'); ?>;
                                color: <?php echo $order['status'] === 'completed' ? '#2ed573' : ($order['status'] === 'cancelled' ? '#ff4757' : '#a0a0b0'); ?>;
                                text-transform: uppercase;">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status"
                                        style="background: #333; color: white; border: none; padding: 4px; border-radius: 4px;">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm" style="padding: 2px 6px;"><i
                                            class="fa-solid fa-check"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>