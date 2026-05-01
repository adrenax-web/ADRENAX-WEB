<?php
session_start();
include __DIR__ . "/../includes/db.php";

$uid = $_SESSION['user_id'];

if($_POST['action'] == "add"){
    $pid = $_POST['product_id'];
    $q = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $uid, $pid, $q);
    $stmt->execute();
}

$count = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id=$uid")->fetch_assoc()['total'];

echo json_encode(["count"=>$count]);
