<?php
require_once "admin_auth.php";
include"db.php";

$current_page = basename($_SERVER['PHP_SELF']);





$resRevenue = $conn->query("
    SELECT SUM(total) AS revenue
    FROM orders
    WHERE status = 'completed'
");
$revenue = $resRevenue->fetch_assoc()['revenue'] ?? 0;

//  Total Orders
$resOrders = $conn->query("
    SELECT COUNT(*) AS total_orders
    FROM orders
    WHERE status = 'completed'
");
$totalOrders = $resOrders->fetch_assoc()['total_orders'] ?? 0;

//  Total Products 
$resProducts = $conn->query("
    SELECT COUNT(*) AS total_products
    FROM products
");
$totalProducts = $resProducts->fetch_assoc()['total_products'] ?? 0;

//  Best-selling Products
$bestProducts = $conn->query("
    SELECT 
        p.name,
        SUM(oi.quantity) AS total_qty,
        SUM(oi.quantity * oi.price) AS revenue
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.status = 'completed'
    GROUP BY oi.product_id
    ORDER BY total_qty DESC
    LIMIT 5
");

//  Sales Chart (Current Month) 
$chartLabels = [];
$chartData = [];


$currentMonth = date('m');
$currentYear  = date('Y');


$chartQuery = $conn->query("
    SELECT 
        DAY(created_at) as day,
        SUM(total) as daily_total
    FROM orders
    WHERE status = 'completed'
      AND MONTH(created_at) = $currentMonth
      AND YEAR(created_at) = $currentYear
    GROUP BY DAY(created_at)
    ORDER BY day
");


while ($row = $chartQuery->fetch_assoc()) {
    $chartLabels[] = $row['day'];
    $chartData[]   = $row['daily_total'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Hasna Labed</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="chart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: sans-serif; background-color: #F5F7FA; display: flex; margin:0; }
        .menu { background-color: #2677b1; width: 90px; height: 100vh; padding: 20px; overflow: hidden; transition: 0.5s; }
        .menu:hover { width: 260px; }
        ul { list-style: none; padding: 0; margin: 0; position: relative; height: 95%; }
        ul li a { text-decoration: none; padding: 10px; margin: 10px 0; border-radius: 8px; display: flex; align-items: center; gap: 40px; color: white; }
        ul li a:hover, ul li a.active { background-color: #ffffff55; }
        ul li i { font-size: 25px; }
        .log-out { position: absolute; bottom: 0; width: 100%; }
        .log-out a { background-color: #D32F2F; display: flex; align-items:center; gap:10px; padding:10px; border-radius:8px; color:white; text-decoration:none;}
        .content { width: 100%; margin: 10px; }
        .title-info { background-color: #2196F3; padding: 10px; display: flex; justify-content: space-between; align-items: center; border-radius: 8px; margin: 10px 0; color: white; }
        .stats-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; transition: transform .3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card i { font-size: 40px; margin-bottom: 15px; }
        .stat-card h3 { color: #333; margin-bottom: 10px; }
        .stat-card .value { font-size: 28px; font-weight: bold; color: #333; }
        .stat-card .change { font-size: 14px; }
        .stat-card.sales { border-top: 5px solid #4CAF50; } .stat-card.profit { border-top: 5px solid #2196F3; } .stat-card.loss { border-top: 5px solid #f44336; }
        .charts-container { display: flex; gap: 20px; margin: 30px 0; }
        .chart-container{height:220px}
        .chart-box, .products-table { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .chart-box { flex: 1.5; } .products-table { flex: 1; }
        table { width:100%; border-collapse: collapse; }
        table th, table td { padding:10px; border-bottom:1px solid #ddd; text-align:left; }
    
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
</head>
<body>
<div class="menu">
    <ul>
        <li class="profile">
            <div class="img-box">
                <img src="admin.png" alt="profile" style="width:100%; height:100%;">
            </div>
            <h2>Admin</h2>
        </li>
        <li><a href="admin-dashboard.php"class="<?= $current_page == 'admin-dashboard.php' ? 'active' : '' ?>"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
        <li><a href="client_dashbord.php" class="<?= $current_page == 'client_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-user"></i><p>Clients</p></a></li>
        <li><a href="product_dashbord.php"class="<?= $current_page == 'product_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-table"></i><p>Products</p></a></li>
        <li><a href="chart_dashbord.php"  class="<?= $current_page == 'chart_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i><p>Chart</p><?php if ($unread_count > 0): ?>
                    <span class="badge"><?php echo $unread_count; ?></span>
                <?php endif; ?></a></li>
        <li><a href="order_dashbord.php"  class="<?= $current_page == 'order_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i><p>Orders</p></a></li>
        <li><a href="contact_dashbord.php"><i class="fas fa-envelope"></i><p>Contact</p></a></li>
        <li class="log-out"><a href="logout.php"><i class="fas fa-sign-out"></i> Log-out</a></li>
    </ul>
</div>

<div class="content">
    <div class="title-info">
        <p>Dashboard</p>
        <i class="fas fa-chart-bar"></i>
    </div>

    <div class="stats-cards">
        <div class="stat-card sales">
    <i class="fas fa-shopping-cart"></i>
    <h3>Total Orders</h3>
    <div class="value"><?php echo $totalOrders; ?></div>
</div>

        <div class="stat-card profit">
    <i class="fas fa-money-bill-wave"></i>
    <h3>Revenue</h3>
    <div class="value"><?php echo number_format($revenue, 2); ?> DA</div>
</div>

        <div class="stat-card loss">
    <i class="fas fa-box"></i>
    <h3>Products</h3>
    <div class="value"><?php echo $totalProducts; ?></div>
</div>

    </div>

    <div class="charts-container">
        <div class="chart-box">
            <h2>Sales of the Month</h2>
            <div class="chart-container">
            <canvas id="salesChart"></canvas>
            </div>
        </div>
        <div class="products-table">
            <h2>Best-selling Products</h2>
            <table>
                <thead>
                    <tr><th>Product</th><th>Sales</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    
                       <?php while($p = $bestProducts->fetch_assoc()): ?>
                        <tr>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo $p['total_qty']; ?></td>
                        <td><?php echo number_format($p['revenue'], 2); ?> DA</td>
                        </tr>
                      <?php endwhile; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chartLabels); ?>,
        datasets: [{
            label: 'Daily Sales (DA)',
            data: <?php echo json_encode($chartData); ?>,
            borderColor: '#2196F3',
            backgroundColor: 'rgba(33,150,243,0.2)',
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#2196F3'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>
