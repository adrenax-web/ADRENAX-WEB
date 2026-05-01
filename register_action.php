<?php
session_start();
include __DIR__ . '/../includes/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$address = $_POST['address'];

$stmt = $conn->prepare("INSERT INTO users (name, email, password, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $password, $address);

if ($stmt->execute()) {
    redirect_to_route('login');
} else {
    echo "Error: " . $conn->error;
}
