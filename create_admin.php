<?php
require_once 'db.php';

try {
    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'Admin'");
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<h2>Admin user already exists!</h2>";
        echo "<p>You can login with your existing admin account.</p>";
    } else {
        // Create Admin User
        $username = 'admin';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $fullname = 'System Administrator';
        $email = 'admin@gamingstore.com';
        $tel = '0000000000';
        $address = 'HQ';
        $role = 'Admin';

        $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, email, tel, address, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $fullname, $email, $tel, $address, $role]);

        echo "<h2>Admin Created Successfully!</h2>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
    }
    echo "<br><a href='login.php'>Go to Login</a>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>