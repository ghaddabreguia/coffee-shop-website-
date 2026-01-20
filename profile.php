<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT username, email, created_at FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="profile.css"> 
<body>

<nav class="navbar">
    <a href="index.php" class="nav-logo">
        <img src="image/logo.png" class="logo-img">
        <h2 class="logo-text">coffee shop</h2>
    </a> 
    <ul class="nav-menu">
        <li class="nav-item"><a href="product.php" class="nav-link">Product</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
        <li class="nav-item"><a href="contactus.php" class="nav-link">Contact</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a href="profile.php" class="nav-link">
                    <img src="image/profile.jpg" class="nav-profile-img" alt="Profile">
                </a>
            </li>
        <?php else: ?>
            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
            <li class="nav-item"><a href="signup.php" class="nav-link">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="profile-box">
    <img src="image/profile.jpg" alt="Profile Image">
    <h2><?php echo htmlspecialchars($user['username']); ?></h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Joined:</strong> <?php echo $user['created_at']; ?></p>
    <a href="dashboard.php">Edit Profile</a>
     <a href="logout.php" class="btn logout">Logout</a>       
</div>
        </head>

        <script src="https://cdn.botpress.cloud/webchat/v3.5/inject.js"></script>
<script src="https://files.bpcontent.cloud/2025/11/21/17/20251121172213-E7XTI0NW.js" defer></script>
</body>
</html>
