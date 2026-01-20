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


include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $calories = $_POST['calories'];
    $has_milk = $_POST['hasMilk'];

    // upload image
    $imageName = time() . "_" . $_FILES['image']['name'];
    $target = "image/" . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

        $sql = "INSERT INTO products 
        (name, price, description, category, calories, has_milk, image_url)
        VALUES 
        ('$name', '$price', '$description', '$category', '$calories', '$has_milk', '$imageName')";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "DB error"]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
    }
}
?>
