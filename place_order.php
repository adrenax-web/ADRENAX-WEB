<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../includes/app.php';
    redirect_to_route('login');
}

include __DIR__ . '/../includes/db.php';

$userId = (int) $_SESSION['user_id'];

$cartStmt = $conn->prepare("
    SELECT cart.product_id, cart.quantity, cart.size, products.price
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$cartStmt->bind_param('i', $userId);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

if ($cartResult->num_rows === 0) {
    die('Cart is empty');
}

$total = 0;
$items = [];

while ($row = $cartResult->fetch_assoc()) {
    $subtotal = (float) $row['price'] * (int) $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}

if ($total <= 0) {
    die('Total calculation failed');
}

$orderStmt = $conn->prepare('INSERT INTO orders (user_id, total) VALUES (?, ?)');
$orderStmt->bind_param('id', $userId, $total);
$orderStmt->execute();

$orderId = $conn->insert_id;

$itemStmt = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price, size)
    VALUES (?, ?, ?, ?, ?)
");

foreach ($items as $item) {
    $productId = (int) $item['product_id'];
    $quantity = (int) $item['quantity'];
    $price = (float) $item['price'];
    $size = (string) $item['size'];

    $itemStmt->bind_param('iiids', $orderId, $productId, $quantity, $price, $size);
    $itemStmt->execute();
}

$clearStmt = $conn->prepare('DELETE FROM cart WHERE user_id = ?');
$clearStmt->bind_param('i', $userId);
$clearStmt->execute();

redirect_to_route('orders');
