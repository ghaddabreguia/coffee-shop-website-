<?php
session_start();
include"db.php";
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];
$username=$_POST['username'];
$email=$_POST['email'];
$password=$_POST['password'];

if(empty($password)){
    $stmt=$conn->prepare(
        "UPDATE users SET username=?, email=? Where id=?"
    );
    $stmt->bind_param("ssi",$username,$email,$user_id);
}
else{
    $password_hash =password_hash($password,PASSWORD_DEFAULT);
    $stmt =$conn->prepare(
        "UPDATE users SET username=?,email=?,password_hash=? WHERE id=?"
    );
    $stmt->bind_param("sssi",$username,$email,$password_hash,$user_id);
}

$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION['username']=$username;
$_SESSION['email']=$email;
$_SESSION['success']="Profile updated successfully";
header("Location: dashboard.php");
exit();

?>
