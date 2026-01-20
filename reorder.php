<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success"=>false,"message"=>"Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = (int)($_GET['order_id'] ?? 0);

$sql = "SELECT id FROM orders 
        WHERE id=? AND user_id=? AND status='completed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii",$order_id,$user_id);
$stmt->execute();
if($stmt->get_result()->num_rows === 0){
    echo json_encode(["success"=>false,"message"=>"Invalid order"]);
    exit;
}

$sql = "SELECT oi.product_id, oi.quantity, oi.price, p.name
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$order_id);
$stmt->execute();
$res = $stmt->get_result();

$basket = [];

while($row = $res->fetch_assoc()){
    $basket[] = [
        "id" => $row['product_id'],
        "title" => $row['name'],
        "qty" => (int)$row['quantity'],
        "totalPrice" => $row['quantity'] * $row['price']
    ];
}

echo json_encode([
    "success"=>true,
    "basket"=>$basket
]);
