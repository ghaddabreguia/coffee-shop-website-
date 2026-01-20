<?php
session_start();
include"db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
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
                 <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
              <?php endif; ?>
            </ul>
        </nav>

    <div class="dashboard-box">
        <?php if(isset($_SESSION['success'])): ?>
    <div class="success-msg">
        <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']); 
        ?>
    </div>
<?php endif; ?>
        <h2> Welcome</h2>
        <a href="profile.php" class="dash-link">My Profile</a>
        <a href="edit_profile.php" class="dash-link"> Edit Profile</a>
        <a href="contactus.php" class="dash-link"> Contact Admin</a>
        <a href="order_history.php" class="dash-link"> Order History</a>
        <a href="logout.php" class="dash-link logout"> Logout</a>
    </div>


 </body>
 </html>