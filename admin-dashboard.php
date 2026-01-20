<?php
require_once "admin_auth.php"; 
include "db.php";
$current_page = basename($_SERVER['PHP_SELF']); 

/* counters */
$usersCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$soldProducts = $conn->query("
    SELECT IFNULL(SUM(quantity),0) AS total
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    WHERE o.status = 'completed'
")->fetch_assoc()['total'];
$revenue = $conn->query("
    SELECT IFNULL(SUM(total),0) AS revenue 
    FROM orders 
    WHERE status='completed'
")->fetch_assoc()['revenue'];

/* recent orders (last 10) */
$recentOrders = $conn->query("
    SELECT 
        o.id,
        u.username,
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
    LIMIT 10
");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css" >
    <link rel="stylesheet" href="dashbord.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title> Dashboard  Hasna Labed </title>
</head>
<body>
    <style>
        * {
   padding: 0;
   margin: 0; 
   color: white; 
   font-family: sans-serif;
}
body {
    background-color: #F5F7FA;
    display: flex;
}


.img-box {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid white;
    flex-shrink: 0;
}

.profile {
    display: flex;
    align-items: center;
    gap: 30px;
}

.profile h2 {
    font-size: 20px;
    text-transform: capitalize;
}

.menu {
    background-color: #2677b1;
    width:90px;
    height: 100vh;
    padding: 20px;
    overflow: hidden;
    transition: 0.5s;
}

.menu:hover {
    width: 260px;
}

ul {
    list-style: none;
    height: 95%;
    position: relative;
    margin: 0;
    padding: 0;
}

ul li a {
    display: flex;
    text-decoration: none;
    padding: 10px;
    margin: 10px 0;
    border-radius: 8px;
    align-items: center;
    gap: 40px;
}

ul li a:hover,
ul li a.active,
.data-info .box:hover,
td:hover {
    background-color: #ffffff55;
}

.log-out {
    position: absolute;
    bottom: 0;
    width: 100%;
}

.log-out a {
    background-color: #D32F2F;
}

ul li i {
    font-size: 25px;
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
.data-info{
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.data-info .box{
    background-color: #529cdc;
    height: 150px;
    width: 150px;
    flex-basis: 150px;
    flex-grow: 1;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-around;


}
.data-info .box i{
font-size:  20px;
}
.data-info .box .data{
    text-align: 30px;
}
.data-info .box .data span{
    font-size: 30px;
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
.price,.count{
    padding: 6px;
    border-radius: 6px;
}
.price{
    background-color: rgb(78, 159, 78);
}
.count{
    background-color: #FFD54F;
    color: black;
}
.content{

    width: 100%;
    margin: 10px;

}

ul li a {
    min-height: 50px; /* same height for all items */
}


/* badge style */
.badge {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: red;
    color: white;
    font-size: 12px;
    padding: 3px 7px;
    border-radius: 50%;
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
                <img src="image/admin.jpg" alt="profile">
            </div>
            <h2>Hasna Labed</h2>
        </li>
        <li>
            <a href="admin-dashboard.php" class="<?= $current_page == 'admin-dashboard.php' ? 'active' : '' ?>">
                <i  class="fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>
         <li>
            <a href="client_dashbord.php"class="<?= $current_page == 'client_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-user"></i>
                <p>Clients</p>
            </a>
        </li>
         <li>
            <a href="product_dashbord.php"class="<?= $current_page == 'product_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-table"></i>
                <p>Products</p>
            </a>
        </li>
         <li>
            <a href="chart_dashbord.php"class="<?= $current_page == 'chart_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i>
                <p>Chart</p>  
            </a>
        </li>
     <li>
    <a href="order_dashbord.php" class="<?= $current_page == 'order_dashbord.php' ? 'active' : '' ?>">
        <i class="fas fa-shopping-cart"></i>
        <p>Order</p>
    </a>
</li>

       
         <li>
            <a href="contact_dashbord.php" class="<?= $current_page == 'contact_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i>
                <p>Contact</p>
                <?php if ($unread_count > 0): ?>
            <span class="badge"><?php echo $unread_count; ?></span>
        <?php endif; ?>
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

<div class="content">
    <div class="title-info">
        <p>Dashboard</p>
         <i class="fas fa-chart-bar">

         </i>
    </div>
    <div class="data-info">
        <div class="box">
            <i class="fas fa-user">  
                <div class="data">
                    <p> user</p>
                    <span><?=$usersCount ?></span>
                </div>
            </i> 
                 
        </div>

        <div class="box">
            <i class="fas fa-table">
                <div class="product">
                    <p> Products</p>
                    <span><?=$soldProducts ?></span>
                </div>

            </i>        
        </div>
        <div class="box">
            <i class="fas fa-dollar">
                <div class="revenue">
                    <p>revenue</p>
                    <span><?=$revenue ?> DA</span>
                </div>

            </i>        
        </div>
    </div>
    <div class="title-info">
        <p>Recent Order</p>
         <i class="fas fa-table">
         </i>
    </div>
    <table>
        <thead>
            <tr>
                <th>Customer name</th>
                <th>Customer E-mail</th>
                <th>number of items </th>
                <th> total price</th>
                <th>product</th>
                <th>Order Status</th>
                <th>order date </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
<?php while($order = $recentOrders->fetch_assoc()): ?>
<tr>
    <td><?= $order['username'] ?></td>
    <td><?= $order['email'] ?></td>
    <td>
        <span class="count"><?= $order['items_count'] ?></span>
    </td>
    <td>
        <span class="price"><?= $order['total'] ?> DA</span>
    </td>
    <td>—</td>
    <td><?= $order['status'] ?></td>
    <td><?= $order['created_at'] ?></td>
    <td>
        <a href="order_dashbord.php" style="color:#1976D2;">View</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>

    </table>
</div>


</body>
</html>