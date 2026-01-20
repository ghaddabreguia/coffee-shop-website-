<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user['password_hash'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: product.php");
            }
            exit;
        }
    }

    $error = "Email or password incorrect";
}
?>


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
                <h2>Login</h2>
                <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Login</button>
        </form>
            </div>
            </div>
            <div class="right-sec">
                <h2>Welcome back</h2>
                <p> We are delighted to welcome our new guests and look forward to providing you with the best coffee experience.
</p>
                <a href="register.php" class="Signin-link">Sign up</a>
            </div>
        </section>
  
        
</body>


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