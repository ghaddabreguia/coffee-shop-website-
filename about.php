<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Coffee shop website</title>
    <link rel="stylesheet" href="about.css">
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
            <li class="nav-item"><a href="signup.php" class="nav-link">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

      <section class="about-us">
        <div class="header">
            <h2> About Coffee Shop</h2>
            <p>Where every cup tells a story and every moment feels like home. </p>
        </div>

        <div class="photos">
            <img src="image/baristaman.jpg" alt="barista making coffee">
            <img src="image/pickup.jpg" alt="a waiter pick up the breads">
            <img src="image/makecoffee.jpg" alt="barista with coffee machine">
            <img src="image/bacagin.jpg" alt="backgin of items">
        </div>

        <div class="mission">
            <h3>Our Mission</h3>
            <p>To serve freshly brewed coffee, create a cozy atmosphere, and bring people together.</p>
        </div>
        <div class="value">
            <img src="image/coffeebeans.jpg">
            <div class="text">
            <h4>Fresh Coffee</h4>
            <p>We pay attention to every detail in every cup. Our coffee beans are roasted daily to ensure a rich, fresh flavor in every sip. Whether you prefer a strong espresso or a smooth cappuccino, each cup is a unique experience that leaves you feeling relaxed and energized, with an aroma and taste that invigorate you every moment.</p>
             </div>   
        </div>
    
      </section> 
     

      <script src="https://cdn.botpress.cloud/webchat/v3.5/inject.js"></script>
<script src="https://files.bpcontent.cloud/2025/11/21/17/20251121172213-E7XTI0NW.js" defer></script>
</body>

</html>

