<?php
require_once 'db.php';

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
    <style>
        .auth-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #b0b0b0;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            background-color: #2c2c2c;
            border: 1px solid #333;
            border-radius: 6px;
            color: #fff;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #00e676;
        }

        .auth-title {
            text-align: center;
            margin-bottom: 30px;
            color: #00e676;
        }

        .error-msg {
            color: #ff5252;
            background-color: rgba(255, 82, 82, 0.1);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-msg {
            color: #00e676;
            background-color: rgba(0, 230, 118, 0.1);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .text-green-500 {
            color: #00e676;
        }

        .hover\:text-green-400:hover {
            color: #66ffa6;
        }
    </style>
</head>

<body>

    <header>
        <h1>Gaming Gear Store</h1>
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

                <button type="submit" class="btn">Register</button>
            </form>

            <p style="text-align: center; margin-top: 20px; color: #b0b0b0;">
                Already have an account? <a href="login.php" class="text-green-500 hover:text-green-400">Login</a>
            </p>
        <?php endif; ?>
    </div>

</body>

</html>