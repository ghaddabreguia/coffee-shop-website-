<?php
// delete_message.php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $conn = new mysqli("localhost", "root", "", "coffeeshop");
    
    if ($conn->connect_error) {
        die("Database connection failed");
    }
    
    $id = intval($_POST['id']);
    
    // حذف الرسالة
    $sql = "DELETE FROM messages WHERE id = $id";
    
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Message deleted successfully";
    } else {
        $_SESSION['message'] = "Error deleting message: " . $conn->error;
    }
    
    $conn->close();
    
    header("Location: contact.php");
    exit();
}
?>