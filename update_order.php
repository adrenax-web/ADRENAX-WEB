<?php
include __DIR__ . '/../includes/db.php';
session_start();

if (!$conn) {
    die("Database connection failed");
}

$id = $_POST['id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

redirect_to_route('admin.orders');
