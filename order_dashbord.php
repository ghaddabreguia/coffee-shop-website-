<?php
require_once "admin_auth.php";
include "db.php";
$current_page = basename($_SERVER['PHP_SELF']);



$sql = "
SELECT 
    o.id AS order_id,
    u.username AS customer_name,
    u.email,
    COUNT(oi.id) AS items_count,
    o.total,
    o.status,
    o.created_at
FROM orders o
JOIN users u ON o.user_id = u.id
LEFT JOIN order_items oi ON oi.order_id = o.id
GROUP BY o.id
ORDER BY o.created_at DESC
";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title> Dashboard  Hasna Labed </title>
</head>
<body>
<style>
    *{
   padding: 0;
   margin:0; 
   color: white; 
   font-family: sans-serif;
}
body{
    background-color: #F5F7FA;
    display: flex;
}
.img-box{
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid white;
    flex-shrink: 0;
} 
.profile{
    display: flex;
    align-items: center;
    gap: 30px;
}
.profile h2{
    font-size: 20px;
    text-transform: capitalize;


}

.menu{
    background-color: #2677b1;
    width:90px;
    height: 100vh;
    padding:  20px;
    overflow: hidden;
    transition: .5s;
    
    
}
.menu:hover{
    width: 260px;

}
ul{
    list-style: none ;
    height: 95%;
    position: relative;
     margin: 0; 
    padding: 0;
}
ul li a{
    display: block;
    text-decoration:  none;
    padding: 10px;
    margin: 10px 0;
    border-radius:  8px;
    display: flex;
    align-items: center;
    gap: 40px;
}
ul li a:hover, 
ul li a.active,
.data-info .box:hover,
td:hover{
    background-color: #ffffff55;
}
.log-out {
    position: absolute;
    bottom: 0;
    width: 100%;
}
.log-out a{
    background-color: #D32F2F;

}
ul li i{
    font-size:  25px;
}
.content{
    width: 100%;
    margin: 10px;
}
.title-info{
    background-color: #2196F3;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 8px;
    margin: 10px 0;
}
.client-table {
    width: 100%;
    text-align: center;
    border-spacing: 8px;
    table-layout: fixed; 
}
        
.client-table td,
.client-table th {
    background-color: #E3F2FD;
    height: 40px; 
    border-radius: 8px;
    color: rgb(0, 0, 0); 
    padding: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.client-table th {
    background-color: #1976D2;
    color: rgb(255, 255, 255); 
}



.col-id { width: 5%; }
.col-name { width: 10%; }
.col-e { width: 25%; }
.col-no { width: 6%; }
.col-fav1 { width: 12%; }
.col-fav2 { width: 12%; }
.col-fav3 { width: 12%; }
.contents{

    width: 100%;
    margin: 10px;

}

table{
    width: 100%;
    text-align: center;
    border-spacing: 8px;
}
td,th{
background-color: #E3F2FD;
height: 40px;
border-radius: 8px;
color:black;

}
th{
    background-color: #1976D2;
}


ul li a p {
    margin: 0; /* remove default p margin */
}

ul li a {
    height: 50px;              /* force same height */
    align-items: center;       /* vertical center */
}

ul li a p {
    margin: 0;
    line-height: 1;            /* fix line height */
}

ul li a i {
    line-height: 1;
}
</style>

  
<div class="menu">
    <ul>
        <li class="profile">
            <div class="img-box">
                <img src="admin" alt="profile">
            </div>
            <h2>Admin</h2>
        </li>
        <li>
            <a href="admin-dashboard.php" class="<?= $current_page == 'admin-dashboard.php' ? 'active' : '' ?>">
                <i  class="fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>
         <li>
            <a href="client_dashbord.php" class="<?= $current_page == 'client_dashbord.php' ? 'active' : '' ?>" >
                <i class="fas fa-user"></i>
                <p>clients</p>
            </a>
        </li>
         <li>
            <a href="product_dashbord.php" class="<?= $current_page == 'product_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-table"></i>
                <p>Products</p>
            </a>
        </li>
         <li>
            <a href="chart_dashbord.php" class="<?= $current_page == 'chart_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i>
                <p>chart</p>  
            </a>
        </li>
       
        <li>
            <a href="order_dashbord.php"  class="<?= $current_page == 'order_dashbord.php' ? 'active' : '' ?>">
            <i class="fas fa-shopping-cart"></i>
            <p>order</p>
        <?php if ($unread_count > 0): ?>
                    <span class="badge"><?php echo $unread_count; ?></span>
                <?php endif; ?>
        </a>
        </li>

         <li>
            <a href="contact_dashbord.php">
                <i class="fas fa-envelope"></i>
                <p>Contact</p>
            </a>
        </li>
         <li class="log-out">
            <a href="logout.php"> 
                <i class="fas fa-sign-out"></i>
                <p>log-out</p>
            </a>
        </li>
    </ul>




</div>

<div class="contents">
    <div class="title-info">
        <p>Dashboard</p>
         <i class="fas fa-chart-bar"></i>
    </div>
 <table class="order-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>E-mail</th>
            <th>Number of order</th>
            <th>Total price</th>
            <th>Order Status</th>
            <th>Order Date</th>
            
        </tr>
    </thead>

    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['order_id'] ?></td>
            <td><?= $row['customer_name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['items_count'] ?></td>
            <td><?= $row['total'] ?> DA</td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['created_at'] ?></td>
           
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>


</div>
 



</body>
</html>