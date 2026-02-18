<?php
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role or to home
                if ($user['role'] === 'Admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = "Invalid username or password.";
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
    <title>Login - Gaming Gear Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 80px auto;
            background-color: #1e1e1e;
            padding: 40px;
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

        .form-group input {
            width: 100%;
            padding: 12px;
            background-color: #2c2c2c;
            border: 1px solid #333;
            border-radius: 6px;
            color: #fff;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #00e676;
        }

        .auth-title {
            text-align: center;
            margin-bottom: 30px;
            color: #00e676;
            font-size: 1.8rem;
        }

        .error-msg {
            color: #ff5252;
            background-color: rgba(255, 82, 82, 0.1);
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
        <h2 class="auth-title">Welcome Back</h2>

        <?php if ($error): ?>
            <div class="error-msg">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #b0b0b0;">
            Don't have an account? <a href="register.php" class="text-green-500 hover:text-green-400">Register</a>
        </p>
    </div>

</body>

</html>