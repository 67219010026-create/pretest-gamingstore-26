<?php
require_once 'db.php';
require_once 'lang.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $address = trim($_POST['address']);

    if (empty($username) || empty($password) || empty($fullname) || empty($email) || empty($tel) || empty($address)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already exists.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, email, tel, address, role) VALUES (?, ?, ?, ?, ?, ?, 'User')");
                if ($stmt->execute([$username, $hashed_password, $fullname, $email, $tel, $address])) {
                    $success = "Registration successful! You can now <a href='login.php' class='text-green-500 hover:text-green-400'>Login</a>.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Gaming Gear Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="logo">
            <h1>Gaming Gear Store</h1>
        </div>
    </header>

    <div class="auth-container">
        <h2 class="auth-title">Create Account</h2>

        <?php if ($error): ?>
            <div class="error-msg">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-msg">
                <?php echo $success; ?>
            </div>
        <?php else: ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="tel">Telephone</label>
                    <input type="tel" id="tel" name="tel" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary w-full">Register</button>
                <a href="cart.php" class="btn"
                    style="display: block; text-align: center; margin-top: 10px; background-color: #6c757d; text-decoration: none;">
                    <?php echo t('back_to_cart'); ?>
                </a>
            </form>

            <p class="text-center text-muted" style="margin-top: 20px;">
                Already have an account? <a href="login.php" class="text-green-500 hover:text-green-400">Login</a>
            </p>
        <?php endif; ?>
    </div>

</body>

</html>