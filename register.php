<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Coffee shop website</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <nav class="navbar">
        <div class="left-nav">
           <a href="index.php" class="nav-logo">
                <img src="image/logo.png" class="logo-img">
                <h2 class="logo-text">coffee shop</h2>
            </a> 
          </div> 
          <div class="right-nav"></div> 
        </nav>
        <section class="main-section">
            <div class="left-sec">
            <div class="sign-up-card">
                <h2>Create Account</h2>
                <form action="save.php" method="POST">
            <input type="text" name="username" placeholder="Full Name" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <div class="input-box">
                <input type="password" name="password" class="password-field" placeholder="Password" required>
            </div><br>
            <button type="submit">Sign Up</button>
        </form>
            </div>
            </div>
            <div class="right-sec">
                <h2>Welcome </h2>
                <p> Access your account and start your day with a fresh brew</p>
                <a href="login.php" class="Signin-link">Sign in</a>
            </div>
        </section>
        


<script>
    const passwordBoxes =document.querySelectorAll('.input-box');
    passwordBoxes.forEach(box =>{
/*let password=document.getElementById("password");
let confirmPassword =document.getElementById("confirmPassword");
*/
const input =box.querySelector('.password-field');
const closedIcon=box.querySelector('.closed-eye');
const openIcon=box.querySelector('.open-eye');

closedIcon.addEventListener('click',()=>toggle(input,closedIcon,openIcon));
openIcon.addEventListener('click',()=> toggle(input,closedIcon,openIcon));
    });

function toggle(input,closedIcon,openIcon){
    if(input.type==='password'){
        input.type='text';
        closedIcon.style.display='none';
        openIcon.style.display='block';
    }else{
        input.type='password';
        closedIcon.style.display='block';
        openIcon.style.display='none';
    }
}



</script>
</html>