<?php
$servername = "localhost";
$username = "root";
$password = "";    
$dbname = "coffeeshop";
$conn = mysqli_connect("localhost", "root", "", "coffeeshop");

if (!$conn) {
    die("connected faild" . mysqli_connect_error());
}
?>