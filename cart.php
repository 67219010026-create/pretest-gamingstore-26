<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Gaming Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 800px; margin-top: 4rem;">
        <header style="justify-content: space-between; margin-bottom: 2rem;">
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE
            </div>
            <a href="index.php" class="btn btn-sm"
                style="background: transparent; border: 1px solid var(--text-secondary);">
                Back to Store
            </a>
        </header>

        <h2 style="margin-bottom: 2rem;"><i class="fa-solid fa-cart-shopping"></i> Shopping Cart</h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <div
                style="text-align: center; padding: 4rem; background: var(--card-bg); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <i class="fa-solid fa-basket-shopping" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3>Your cart is empty</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Looks like you haven't added any games yet.
                </p>
                <a href="index.php" class="btn">Start Shopping</a>
            </div>
        <?php else: ?>
            <div
                style="background: var(--card-bg); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); overflow: hidden;">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $total = $item['price'] * $item['quantity'];
                            $grand_total += $total;
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <?php if ($item['image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <?php else: ?>
                                            <div
                                                style="width: 50px; height: 50px; background: #333; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa-solid fa-image"></i></div>
                                        <?php endif; ?>
                                        <span>
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </span>
                                    </div>
                                </td>
                                <td>$
                                    <?php echo number_format($item['price'], 2); ?>
                                </td>
                                <td>
                                    <?php echo $item['quantity']; ?>
                                </td>
                                <td>$
                                    <?php echo number_format($total, 2); ?>
                                </td>
                                <td>
                                    <form action="cart_actions.php" method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div
                    style="padding: 1.5rem; display: flex; justify-content: flex-end; align-items: center; gap: 2rem; border-top: 1px solid rgba(255,255,255,0.05);">
                    <div style="font-size: 1.25rem;">Total: <span style="color: var(--success); font-weight: bold;">$
                            <?php echo number_format($grand_total, 2); ?>
                        </span></div>
                    <a href="checkout.php" class="btn">Proceed to Checkout <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>