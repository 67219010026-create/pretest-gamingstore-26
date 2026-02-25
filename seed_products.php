<?php
require_once 'db.php';

$products = [
    ['name' => 'Razer DeathAdder V3 Pro', 'category' => 'Mouse', 'price' => 4990, 'stock' => 50, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Razer+Mouse'],
    ['name' => 'Logitech G Pro X Superlight 2', 'category' => 'Mouse', 'price' => 5290, 'stock' => 45, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Logitech+Mouse'],
    ['name' => 'SteelSeries Apex Pro TKL', 'category' => 'Keyboard', 'price' => 7990, 'stock' => 30, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Apex+Keyboard'],
    ['name' => 'ZOWIE EC2-CW Wireless', 'category' => 'Mouse', 'price' => 5990, 'stock' => 20, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=ZOWIE+Mouse'],
    ['name' => 'HyperX Cloud Alpha Wireless', 'category' => 'Headset', 'price' => 6990, 'stock' => 25, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Cloud+Alpha'],
    ['name' => 'Razer BlackShark V2 Pro', 'category' => 'Headset', 'price' => 6590, 'stock' => 35, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=BlackShark'],
    ['name' => 'Secretlab TITAN Evo 2022', 'category' => 'Chair', 'price' => 17900, 'stock' => 15, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Gaming+Chair'],
    ['name' => 'Elgato Stream Deck MK.2', 'category' => 'Streaming', 'price' => 5490, 'stock' => 20, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Stream+Deck'],
    ['name' => 'Artisan Ninja FX Zero Soft XL', 'category' => 'Mousepad', 'price' => 2200, 'stock' => 10, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Artisan+Pad'],
    ['name' => 'Glorious Model O 2 Wireless', 'category' => 'Mouse', 'price' => 3490, 'stock' => 30, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Glorious+Mouse'],
    ['name' => 'Ducky One 3 Daybreak', 'category' => 'Keyboard', 'price' => 4290, 'stock' => 15, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Ducky+Keyboard'],
    ['name' => 'Razer Wolverine V2 Pro', 'category' => 'Controller', 'price' => 8990, 'stock' => 12, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Razer+Controller'],
    ['name' => 'BenQ ZOWIE XL2566K 360Hz', 'category' => 'Monitor', 'price' => 24900, 'stock' => 8, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=ZOWIE+Monitor'],
    ['name' => 'SteelSeries Arena 7 Speakers', 'category' => 'Audio', 'price' => 12500, 'stock' => 5, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Arena+7'],
    ['name' => 'Razer Mouse Bungee V3', 'category' => 'Accessory', 'price' => 990, 'stock' => 40, 'image_url' => 'https://placehold.co/400x300/212d50/ffffff?text=Razer+Bungee']
];

echo "Clearing and adding products...\n";

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE order_items");
    $pdo->exec("TRUNCATE TABLE orders");
    $pdo->exec("TRUNCATE TABLE products");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");

    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['category'],
            $product['price'],
            $product['stock'],
            $product['image_url']
        ]);
        echo "Added: " . $product['name'] . "\n";
    }

    echo "Successfully added 20 unique gamer products!\n";

} catch (PDOException $e) {
    echo "Error inserting products: " . $e->getMessage() . "\n";
}
?>