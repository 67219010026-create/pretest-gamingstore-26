<?php
require_once 'db.php';

$products = [
    ['name' => 'Razer DeathAdder V3 Pro', 'category' => 'Mouse', 'price' => 4990, 'stock' => 50, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Razer+DeathAdder'],
    ['name' => 'Logitech G Pro X Superlight 2', 'category' => 'Mouse', 'price' => 5290, 'stock' => 45, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=G+Pro+X+Superlight'],
    ['name' => 'SteelSeries Apex Pro TKL', 'category' => 'Keyboard', 'price' => 7990, 'stock' => 30, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Apex+Pro+TKL'],
    ['name' => 'Corsair K70 RGB PRO', 'category' => 'Keyboard', 'price' => 6490, 'stock' => 40, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=K70+RGB+PRO'],
    ['name' => 'HyperX Cloud Alpha Wireless', 'category' => 'Headset', 'price' => 6990, 'stock' => 25, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Cloud+Alpha+Wireless'],
    ['name' => 'Razer BlackShark V2 Pro', 'category' => 'Headset', 'price' => 6590, 'stock' => 35, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=BlackShark+V2+Pro'],
    ['name' => 'ASUS ROG Swift OLED PG27AQDM', 'category' => 'Monitor', 'price' => 42900, 'stock' => 10, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=ROG+Swift+OLED'],
    ['name' => 'Secretlab TITAN Evo 2022', 'category' => 'Chair', 'price' => 17900, 'stock' => 15, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=TITAN+Evo'],
    ['name' => 'Elgato Stream Deck MK.2', 'category' => 'Streaming', 'price' => 5490, 'stock' => 20, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Stream+Deck'],
    ['name' => 'Blue Yeti X', 'category' => 'Microphone', 'price' => 6290, 'stock' => 25, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Blue+Yeti+X'],
    ['name' => 'Logitech G29 Driving Force', 'category' => 'Racing', 'price' => 9990, 'stock' => 12, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=G29+Accessory'],
    ['name' => 'Razer Kitsune', 'category' => 'Controller', 'price' => 10990, 'stock' => 8, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Razer+Kitsune'],
    ['name' => 'ASUS ROG Ally', 'category' => 'Consoles', 'price' => 24990, 'stock' => 20, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=ROG+Ally'],
    ['name' => 'DualSense Edge Wireless Controller', 'category' => 'Controller', 'price' => 7590, 'stock' => 30, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=DualSense+Edge'],
    ['name' => 'Xbox Elite Series 2', 'category' => 'Controller', 'price' => 6490, 'stock' => 25, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Xbox+Elite+2'],
    ['name' => 'Lian Li O11 Dynamic Evo', 'category' => 'PC Case', 'price' => 5990, 'stock' => 18, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=O11+Dynamic'],
    ['name' => 'NVIDIA GeForce RTX 4090', 'category' => 'GPU', 'price' => 72900, 'stock' => 5, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=RTX+4090'],
    ['name' => 'AMD Ryzen 9 7950X3D', 'category' => 'CPU', 'price' => 24900, 'stock' => 15, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Ryzen+9+7950X3D'],
    ['name' => 'Samsung 990 PRO 2TB', 'category' => 'Storage', 'price' => 6990, 'stock' => 40, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=990+PRO+SSD'],
    ['name' => 'G.SKILL Trident Z5 RGB 32GB', 'category' => 'Memory', 'price' => 5490, 'stock' => 35, 'image_url' => 'https://placehold.co/400x300/050505/00e676?text=Trident+Z5+RGB']
];

echo "Adding products...\n";

try {
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