<?php
session_start();
include __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$id = (int) ($_GET['id'] ?? 0);
$userId = (int) $_SESSION['user_id'];

$conn->query("DELETE FROM cart WHERE id = $id AND user_id = $userId");

redirect_to_route('cart');
