<?php
$host = 'db';
$db = 'stock_system';
$user = 'user';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Check if tables exist, if not, create them
    try {
        $pdo->query("SELECT 1 FROM users LIMIT 1");
    } catch (\PDOException $e) {
        if ($e->getCode() == '42S02') { // Table or view not found
            if (file_exists(__DIR__ . '/database_setup.sql')) {
                $sql = file_get_contents(__DIR__ . '/database_setup.sql');
                $pdo->exec($sql);
            }
        }
    }
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
?>