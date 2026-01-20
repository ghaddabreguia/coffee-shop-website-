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
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db.php";

header('Content-Type: application/json');

// تحقق من نوع الطلب
if($_SERVER['REQUEST_METHOD'] !== "POST"){
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// تحقق من وصول البيانات
if(!isset($_POST['id']) || !isset($_POST['image'])){
    echo json_encode(["success" => false, "message" => "Missing id or image"]);
    exit;
}

// جلب البيانات
$id = intval($_POST['id']);
$image = basename($_POST['image']); // فقط اسم الملف بدون مسار
$path = "image/" . $image;

// تحقق من وجود الصورة
if(!file_exists($path)){
    echo json_encode(["success" => false, "message" => "File not found: $path"]);
    exit;
}

// محاولة حذف الصورة
if(!unlink($path)){
    echo json_encode(["success" => false, "message" => "Cannot delete file: $path. Check folder permissions."]);
    exit;
}

// حذف المنتج من قاعدة البيانات
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
if(!$stmt){
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);

if($stmt->execute()){
    if($stmt->affected_rows > 0){
        echo json_encode(["success" => true, "message" => "Product deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "No product found with this ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Execute failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
