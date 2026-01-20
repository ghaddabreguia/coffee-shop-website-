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

$result = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC");

$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>
