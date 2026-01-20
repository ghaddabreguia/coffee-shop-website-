<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success'=>false, 'message'=>'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$basket = $data['basket'] ?? [];
$total = $data['total'] ?? 0;

if(empty($basket)){
    echo json_encode(['success'=>false, 'message'=>'Basket is empty']);
    exit;
}


$stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'completed')");
$stmt->bind_param("id", $userId, $total);
$stmt->execute();
$orderId = $stmt->insert_id;


$stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
foreach($basket as $item){
    $productId = $item['id'];
    $qty = $item['qty'];
    $stmtItem->bind_param("iii", $orderId, $productId, $qty);
    $stmtItem->execute();
}

echo json_encode(['success'=>true, 'message'=>'Order completed successfully']);
?>
