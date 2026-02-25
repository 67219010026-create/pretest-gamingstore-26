<?php
require_once 'lang.php';
require_once 'db.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? '';
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Handle Add to Cart
if ($action === 'add' && $product_id > 0) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header('Location: cart.php');
    exit;
}

// Handle Remove from Cart
if ($action === 'remove' && $product_id > 0) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    // If cart is empty, remove discount
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['discount']);
    }
    header('Location: cart.php');
    exit;
}

// Handle Update Cart Quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $id => $quantity) {
            $qty = (int) $quantity;
            if ($qty > 0) {
                $_SESSION['cart'][$id] = $qty;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
    }
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['discount']);
    }
    header('Location: cart.php');
    exit;
}

// Handle Apply Discount
$discount_msg = '';
$discount_err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_discount'])) {
    $code = trim($_POST['discount_code']);

    if (!empty($code)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM discount_codes WHERE code = ?");
            $stmt->execute([$code]);
            $discount = $stmt->fetch();

            if ($discount) {
                $_SESSION['discount'] = [
                    'code' => $discount['code'],
                    'percent' => $discount['discount_percent']
                ];
                $discount_msg = "Discount code applied!";
            } else {
                $discount_err = "Invalid discount code.";
            }
        } catch (PDOException $e) {
            $discount_err = "Error verifying discount code.";
        }
    } else {
        // remove discount if empty
        unset($_SESSION['discount']);
    }
}

// Fetch Cart Products
$cart_products = [];
$subtotal = 0;

if (!empty($_SESSION['cart'])) {
    // Sanitize keys for IN clause
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';

    try {
        $stmt = $pdo->prepare("SELECT id, name, price, image_url, stock FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $fetched_products = $stmt->fetchAll();

        foreach ($fetched_products as $product) {
            $qty = $_SESSION['cart'][$product['id']];

            // Adjust to max stock if quantity exceeds stock
            if ($qty > $product['stock']) {
                $qty = $product['stock'];
                $_SESSION['cart'][$product['id']] = $qty;
            }

            $product['quantity'] = $qty;
            $product['subtotal'] = $qty * $product['price'];
            $subtotal += $product['subtotal'];
            $cart_products[] = $product;
        }
    } catch (PDOException $e) {
        die("Error fetching cart products.");
    }
}

// Calculate Total
$discount_percent = $_SESSION['discount']['percent'] ?? 0;
$discount_amount = ($subtotal * $discount_percent) / 100;
$total = $subtotal - $discount_amount;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo t('cart'); ?> -
        <?php echo t('store_name'); ?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cart-table th,
        .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-summary {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-total {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-top: 15px;
            border-top: 2px solid #eee;
            padding-top: 15px;
        }

        .qty-input {
            width: 60px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .discount-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>

    <header>
        <div class="container flex-between" style="padding: 0;">
            <div class="logo">
                <h1><a href="index.php" style="color: white; text-decoration: none;">
                        <?php echo t('store_name'); ?>
                    </a></h1>
            </div>
            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-muted" style="margin-right: 15px;">
                        <?php echo t('welcome'); ?>,
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-danger btn-sm">
                        <?php echo t('logout'); ?>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm" style="margin-right: 10px;">
                        <?php echo t('login'); ?>
                    </a>
                    <a href="register.php" class="btn btn-sm">
                        <?php echo t('register'); ?>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 40px;">
        <h2 style="margin-bottom: 20px;">
            <?php echo t('cart'); ?>
        </h2>

        <?php if ($discount_msg): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($discount_msg); ?>
            </div>
        <?php endif; ?>
        <?php if ($discount_err): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($discount_err); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_products)): ?>
            <div
                style="background: white; padding: 40px; text-align: center; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <p style="font-size: 1.1rem; color: #666; margin-bottom: 20px;">
                    <?php echo t('empty_cart'); ?>
                </p>
                <a href="index.php" class="btn">
                    <?php echo t('continue_shopping'); ?>
                </a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

                <!-- Cart Items -->
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <form method="POST" action="cart.php">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        <?php echo t('product'); ?>
                                    </th>
                                    <th>
                                        <?php echo t('price'); ?>
                                    </th>
                                    <th>
                                        <?php echo t('quantity'); ?>
                                    </th>
                                    <th>
                                        <?php echo t('total'); ?>
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_products as $item): ?>
                                    <tr>
                                        <td style="width: 90px;">
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                                alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-img">
                                        </td>
                                        <td><strong>
                                                <?php echo htmlspecialchars($item['name']); ?>
                                            </strong></td>
                                        <td>฿
                                            <?php echo number_format($item['price'], 2); ?>
                                        </td>
                                        <td>
                                            <input type="number" name="quantities[<?php echo $item['id']; ?>]"
                                                value="<?php echo $item['quantity']; ?>" min="1"
                                                max="<?php echo $item['stock']; ?>" class="qty-input">
                                        </td>
                                        <td><strong>฿
                                                <?php echo number_format($item['subtotal'], 2); ?>
                                            </strong></td>
                                        <td>
                                            <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>"
                                                class="btn btn-danger btn-sm">
                                                <?php echo t('remove'); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="text-align: right;">
                            <button type="submit" name="update_cart" class="btn" style="background-color: #6c757d;">
                                <?php echo t('update_cart'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="cart-summary">
                    <h3 style="margin-bottom: 20px; font-size: 1.2rem;">
                        <?php echo t('order_summary'); ?>
                    </h3>

                    <form method="POST" action="cart.php" class="discount-form">
                        <input type="text" name="discount_code" placeholder="<?php echo t('discount_code'); ?>"
                            value="<?php echo htmlspecialchars($_SESSION['discount']['code'] ?? ''); ?>"
                            style="flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <button type="submit" name="apply_discount" class="btn btn-sm">
                            <?php echo t('apply_discount'); ?>
                        </button>
                    </form>

                    <div class="summary-row" style="color: #666;">
                        <span>
                            <?php echo t('subtotal'); ?>
                        </span>
                        <span>฿
                            <?php echo number_format($subtotal, 2); ?>
                        </span>
                    </div>

                    <?php if ($discount_percent > 0): ?>
                        <div class="summary-row" style="color: #28a745;">
                            <span>
                                <?php echo t('discount'); ?> (
                                <?php echo $discount_percent; ?>%)
                            </span>
                            <span>-฿
                                <?php echo number_format($discount_amount, 2); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="summary-row summary-total">
                        <span>
                            <?php echo t('total'); ?>
                        </span>
                        <span>฿
                            <?php echo number_format($total, 2); ?>
                        </span>
                    </div>

                    <a href="checkout.php" class="btn w-full"
                        style="margin-top: 20px; text-align: center; font-size: 1.1rem; padding: 15px;">
                        <?php echo t('checkout'); ?>
                    </a>
                </div>

            </div>
        <?php endif; ?>
    </div>

</body>

</html>