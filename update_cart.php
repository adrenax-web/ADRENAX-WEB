<?php
session_start();
include __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$userId = (int) $_SESSION['user_id'];
$cartId = (int) ($_POST['cart_id'] ?? 0);
$action = $_POST['action'] ?? '';

$stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cartId, $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    exit();
}

if ($action === "inc") {
    $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE id = $cartId");
}

if ($action === "dec") {
    $conn->query("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = $cartId");
}

echo "ok";
