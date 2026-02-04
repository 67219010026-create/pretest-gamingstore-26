<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, address, phone, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $address, $phone, $email, $password]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;

            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                echo "Email already exists!";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    } elseif ($action === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                header("Location: index.php");
                exit();
            } else {
                echo "Invalid email or password!";
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