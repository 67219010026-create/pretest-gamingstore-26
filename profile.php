<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET fullname=?, address=?, tel=?, email=? WHERE id=?");
        $stmt->execute([$fullname, $address, $tel, $email, $user_id]);
        $_SESSION['user_name'] = $fullname;
        $message = "Profile updated successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Gaming Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 600px; margin-top: 4rem;">
        <header style="justify-content: space-between; margin-bottom: 2rem;">
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE
            </div>
            <a href="index.php" class="btn btn-sm"
                style="background: transparent; border: 1px solid var(--text-secondary);">
                Back to Store
            </a>
        </header>

        <div class="product-card" style="padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                <i class="fa-solid fa-user-circle"></i> My Profile
            </h2>

            <div style="margin-bottom: 1.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                Username: <strong
                    style="color: var(--text-primary);"><?php echo htmlspecialchars($user['username']); ?></strong>
            </div>

            <?php if ($message): ?>
                <div
                    style="background: rgba(46, 213, 115, 0.2); color: #2ed573; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Fullname</label>
                    <input type="text" name="fullname" class="form-control"
                        value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3"
                        required><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Tel</label>
                    <input type="tel" name="tel" class="form-control"
                        value="<?php echo htmlspecialchars($user['tel']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <button type="submit" class="btn" style="width: 100%;">Update Profile</button>
            </form>
        </div>
    </div>
</body>

</html>