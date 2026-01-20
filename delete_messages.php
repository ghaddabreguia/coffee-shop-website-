<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}
$conn = new mysqli("localhost","root","","coffee_shop");
$id = intval($_POST['id']);
$conn->query("DELETE FROM messages WHERE id=$id");
