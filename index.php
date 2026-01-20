<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Coffee shop website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
        <nav class="navbar">
    <a href="index.php" class="nav-logo">
        <img src="image/logo.png" class="logo-img">
        <h2 class="logo-text">coffee shop</h2>
    </a> 

    <ul class="nav-menu">
        <li class="nav-item">
            <a href="product.php" class="nav-link">Product</a>
        </li>

        <li class="nav-item">
            <a href="about.php" class="nav-link">About Us</a>
        </li>

        <li class="nav-item">
            <a href="contactus.php" class="nav-link">Contact</a>
        </li>

        <?php if(isset($_SESSION['user_id'])): ?>
    <li class="nav-item">
        <a href="dashboard.php" class="nav-link">
            <img src="image/profile.jpg" class="nav-profile-img" alt="Profile">
        </a>
    </li>
<?php else: ?>
    <li class="nav-item">
        <a href="login.php" class="nav-link">Login</a>
    </li>
    <li class="nav-item">
        <a href="register.php" class="nav-link">Register</a>
    </li>
<?php endif; ?>
    </ul>
</nav>

   <section class="hero-section">
    <div class="text-side">
<h1 class="main-title">START YOUR DAY WITH PERFECT COFFEE</h1>
        <p class="description">From bean to cup, we ensure every step meets the highest standards. Experience the rich aroma and authentic taste that defines our coffee.</p>
        <p class="join-word">Join us and discover a world of unique flavors</p>
        
                 <a href="register.php" class="Sign-link">Sign up</a> 
  
  </div> 
  <div class="img-side">
<img src="image/coffee.png">
   </div> 
   </section>
       
     
</body>

</html>

