<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Coffee shop website</title>
    <link rel="stylesheet" href="contact.css">
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
                <a href="dashboard.php" class="nav-link">
                    <img src="image/profile.jpg" class="nav-profile-img" alt="Profile">
                </a>
            </li>
        <?php else: ?>
            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
            <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
  
<header class="header">
<h1>Contact Us</h1>
<p>We are here to serve you, contact us for any inquiry or special request.</p>
</header>

<section class="contact">
<div class="contact-info">
    <div class="box">
        <h3> Phone</h3>
        <p>77-945-60-22</p>
        <a href="" target="_blank" class="icon-link">
        <img src="image/phone.png" class="icon">
        </a>
    </div>
    <div class="box">
        <h3> WhatsApp</h3>
        <p>77-945-60-22</p>
        <a href="" target="_blank" class="icon-link">
        <img src="image/whatsapp.png"class="icon">
        </a>
    </div>
    <div class="box">
        <h3> Email</h3>
        <p>CoffeeShop@gmail.com</p>
        <a href="mailto:" class="icon-link">
        <img src="image/email.png"class="icon">
        </a>
    </div>
    <div class="box">
        <h3> Our Shop</h3>
        <p>BP 145 RP، 07000</p>
        <a href="https://maps.app.goo.gl/96FGbMpXz1eguEui7" target="_blank" class="icon-link">
        <img src="image/address.png"class="icon">
        </a>
    </div>
</div>
<div class="contact-from">
<h2>Get In Touch</h2>
<form action="send_message.php" method="POST">
    <input type="text" name="name" placeholder="Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="text" name="subject" placeholder="Subject" required>
<textarea name="message" placeholder="Message" required></textarea>
<button type="submit">Send Now</button>
</form>
</div>
</section>
 

<footer>
    <p>© 2026 Coffee Shop.All Rights Reserved </p>
</footer>


<script src="https://cdn.botpress.cloud/webchat/v3.5/inject.js"></script>
<script src="https://files.bpcontent.cloud/2025/11/21/17/20251121172213-E7XTI0NW.js" defer></script>
</body>

</html>

