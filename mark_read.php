//$conn = new mysqli("localhost","root","","coffee_shop");
//$id = intval($_POST['id']);
//$conn->query("UPDATE messages SET is_read=1 WHERE id=$id");


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
require_once '../config/database.php';

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token");
}

// Verify admin session
if (!isset($_SESSION['admin'])) {
    die("Unauthorized");
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $conn = getConnection();
    if ($conn) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        echo "OK";
    }
}
?>