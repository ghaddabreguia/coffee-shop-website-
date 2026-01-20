<?php
session_start();

/* حماية الملف – غير admin */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}
include "db.php";
header('Content-Type: application/json');

$clients = [];
$users = $conn->query("SELECT * FROM users");
while($user = $users->fetch_assoc()){
    $userId = $user['id'];


    $orderCountRes = $conn->query("SELECT COUNT(*) AS total_orders FROM orders WHERE user_id=$userId");
    $orderCount = $orderCountRes->fetch_assoc()['total_orders'];

   
    $favRes = $conn->query("
        SELECT p.name, COUNT(*) AS cnt
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN orders o ON oi.order_id = o.id
        WHERE o.user_id = $userId
        GROUP BY p.id
        ORDER BY cnt DESC
        LIMIT 3
    ");
    $favs = [];
    while($row = $favRes->fetch_assoc()){
        $favs[] = $row['name'];
    }

    $clients[] = [
        'id' => $user['id'],
        'name' => $user['username'],
        'email' => $user['email'],
        'orders' => $orderCount,
        'fav1' => isset($favs[0]) ? $favs[0] : '-',
        'fav2' => isset($favs[1]) ? $favs[1] : '-',
        'fav3' => isset($favs[2]) ? $favs[2] : '-'
    ];
}

echo json_encode($clients);
?>
