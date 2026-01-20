<?php
session_start();
include"db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT username, email FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="nav-logo">
        <img src="image/logo.png" class="logo-img">
        <h2 class="logo-text">coffee shop</h2>
    </a>
</nav>
<div class="profile-box">
    <h2>Edit Profile</h2>

    <form action="update_profile.php" method="POST">
        <input type="text" name="username"
               value="<?php echo htmlspecialchars($user['username']); ?>"
               required>

        <input type="email" name="email"
               value="<?php echo htmlspecialchars($user['email']); ?>"
               required>

        <input type="password" name="password"
               placeholder="New password (leave empty if no change)">
<button type="submit">Save Changes</button>
    </form>

    <a href="dashboard.php" class="dash-link">Back</a>
</div>

</body>
</html>