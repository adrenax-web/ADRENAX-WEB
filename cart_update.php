<?php
session_start();
include __DIR__ . "/../includes/db.php";

$id = $_POST['id'];
$change = $_POST['change'];

// get current quantity
$res = $conn->query("SELECT quantity FROM cart WHERE id=$id");
$row = $res->fetch_assoc();

$newQty = $row['quantity'] + $change;

// prevent 0 or negative
if($newQty <= 0){
    $conn->query("DELETE FROM cart WHERE id=$id");
} else {
    $stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
    $stmt->bind_param("ii", $newQty, $id);
    $stmt->execute();
}

// return updated total cart count + total price
$uid = $_SESSION['user_id'];

$res = $conn->query("
SELECT cart.quantity, products.price 
FROM cart 
JOIN products ON cart.product_id = products.id 
WHERE user_id=$uid
");

$total = 0;
$count = 0;

while($r = $res->fetch_assoc()){
    $total += $r['price'] * $r['quantity'];
    $count += $r['quantity'];
}

echo json_encode([
  "total"=>$total,
  "count"=>$count
]);
