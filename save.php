<?php
session_start();
include "db.php";
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<p>Email already exist</p>";
        exit();
    }
    $stmt->close();


    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password_hash);
    if($stmt->execute()){
      
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;

        header("Location: product.php");
        exit();
    } else {
        echo "<p>erro ,try again</p>";
    }
    $stmt->close();
    $conn->close();
}
?>