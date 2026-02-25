<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Set default language to Thai if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'th';
}

$translations = [
    'th' => [
        'store_name' => 'ร้านอุปกรณ์เกม',
        'welcome' => 'ยินดีต้อนรับ',
        'dashboard' => 'แดชบอร์ด',
        'logout' => 'ออกจากระบบ',
        'login' => 'เข้าสู่ระบบ',
        'register' => 'สมัครสมาชิก',
        'add_to_cart' => 'เพิ่มในตะกร้า',
        'checkout' => 'ชำระเงิน',
        'payment' => 'ชำระเงิน',
        'quantity' => 'จำนวน',
        'total' => 'รวม',
        'product' => 'สินค้า',
        'price' => 'ราคา',
        'category' => 'หมวดหมู่',
        'home' => 'หน้าแรก',
        'cart' => 'ตะกร้า',
        'order_summary' => 'สรุปการสั่งซื้อ',
        'payment_button' => 'ชำระเงิน',
        'error_missing_fields' => 'กรุณากรอกข้อมูลให้ครบ',
        'empty_cart' => 'ตะกร้าสินค้าว่างเปล่า',
        'update_cart' => 'อัปเดตตะกร้า',
        'remove' => 'ลบ',
        'discount_code' => 'โค้ดส่วนลด',
        'apply_discount' => 'ใช้ส่วนลด',
        'subtotal' => 'ยอดรวม',
        'discount' => 'ส่วนลด',
        'checkout_success' => 'สั่งซื้อสำเร็จ!',
        'continue_shopping' => 'เลือกซื้อสินค้าต่อ',
        'back_to_cart' => 'กลับไปหน้าตะกร้าสินค้า',
    ],
    // Add other languages here if needed
];

function t(string $key): string
{
    $lang = $_SESSION['lang'] ?? 'th';
    global $translations;
    return $translations[$lang][$key] ?? $key;
}
?>