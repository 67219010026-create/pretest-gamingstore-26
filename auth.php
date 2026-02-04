<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, email, tel, address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $password, $fullname, $email, $tel, $address]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $fullname;
            $_SESSION['user_role'] = 'user';

            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                echo "Username or Email already exists!";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    } elseif ($action === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_role'] = strtolower($user['role']);

                header("Location: index.php");
                exit();
            } else {
                echo "Invalid username or password!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>