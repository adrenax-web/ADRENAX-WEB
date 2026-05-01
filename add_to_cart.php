<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include __DIR__ . '/../includes/db.php';

if (!$conn) {
    die('Database connection failed');
}

if (!isset($_SESSION['user_id'])) {
    redirect_to_route('login');
}

$userId = (int) $_SESSION['user_id'];
$productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
$size = isset($_POST['size']) ? trim($_POST['size']) : 'M';

if ($productId <= 0) {
    die('Invalid product ID');
}

if ($quantity <= 0) {
    $quantity = 1;
}

$stmt = $conn->prepare("
    SELECT id, quantity
    FROM cart
    WHERE user_id = ? AND product_id = ? AND `size` = ?
");
$stmt->bind_param('iis', $userId, $productId, $size);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newQuantity = (int) $row['quantity'] + $quantity;

    $updateStmt = $conn->prepare('UPDATE cart SET quantity = ? WHERE id = ?');
    $updateStmt->bind_param('ii', $newQuantity, $row['id']);
    $updateStmt->execute();
} else {
    $insertStmt = $conn->prepare("
        INSERT INTO cart (user_id, product_id, quantity, `size`)
        VALUES (?, ?, ?, ?)
    ");
    $insertStmt->bind_param('iiis', $userId, $productId, $quantity, $size);
    $insertStmt->execute();
}

redirect_to_route('cart');
