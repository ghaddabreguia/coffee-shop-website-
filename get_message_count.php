<?php
// get_messages.php
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
$conn = new mysqli("localhost", "root", "", "coffeeshop");

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection error";
    exit();
}

$sql = "SELECT COUNT(*) as count FROM messages WHERE DATE(sent_at) = CURDATE()";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo "0";
}

$conn->close();
?>