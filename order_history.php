<?php
// order_history.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// جلب الطلبات
$sql = "SELECT o.id as order_id, o.created_at, o.total, o.status, 
               COUNT(oi.id) as items_count
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = ? 
        GROUP BY o.id
        ORDER BY o.created_at DESC";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Coffee Shop</title>
    <style>
      
        :root {
            --primary-color: #fcf5ed;
            --secondary-color: #78553f;
            --dark-brown: #54362b;
            --light-beige: #e1d4c2;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Domine", sans-serif;
        }
        
        body {
            background-color: var(--primary-color);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .back-btn {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: var(--dark-brown);
            transform: translateY(-2px);
        }
        
        h1 {
            color: var(--dark-brown);
            margin-bottom: 30px;
            text-align: center;
            font-size: 32px;
            position: relative;
            padding-bottom: 15px;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
        }
        
      
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        
        .orders-table thead {
            background: var(--secondary-color);
        }
        
        .orders-table th {
            color: white;
            padding: 18px 15px;
            text-align: center;
            font-weight: 700;
            font-size: 16px;
        }
        
        .orders-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid var(--light-beige);
            color: #333;
        }
        
        .orders-table tbody tr:hover {
            background: var(--primary-color);
        }
        
        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }
        
      
        .status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            min-width: 120px;
        }
        
        .status-pending {
            background: #FFF3CD;
            color: #856404;
            border: 1px solid #FFEEBA;
        }
        
        .status-completed {
            background: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
        }
        
        .status-canceled {
            background: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }
        
     
        .reorder-btn {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .reorder-btn:hover {
            background: var(--dark-brown);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(120, 85, 63, 0.3);
        }
        
        .reorder-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
        }
        
  
        .no-orders {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        
        .no-orders h3 {
            color: var(--dark-brown);
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .no-orders p {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .no-orders .shop-btn {
            background: var(--secondary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .no-orders .shop-btn:hover {
            background: var(--dark-brown);
            transform: translateY(-2px);
        }
        
    
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            border-top: 4px solid var(--secondary-color);
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 10px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
        }
     
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .orders-table {
                display: block;
                overflow-x: auto;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .orders-table th,
            .orders-table td {
                padding: 12px 8px;
                font-size: 14px;
            }
            
            .status {
                padding: 6px 12px;
                font-size: 12px;
                min-width: 100px;
            }
            
            .reorder-btn {
                padding: 8px 16px;
                font-size: 13px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        
        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <h1><i class="fas fa-history"></i> Order History</h1>
        
        <?php
      
        $stats_sql = "SELECT 
                        COUNT(*) as total_orders,
                        SUM(total) as total_spent
                      FROM orders 
                      WHERE user_id = ? AND status = 'completed'";
        $stats_stmt = $conn->prepare($stats_sql);
        $stats_stmt->bind_param("i", $user_id);
        $stats_stmt->execute();
        $stats_result = $stats_stmt->get_result();
        $stats = $stats_result->fetch_assoc();
        ?>
        
       
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">$<?php echo number_format($stats['total_spent'] ?? 0, 2); ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
        </div>
        
        
        <?php if ($result->num_rows > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?php echo $order['order_id']; ?></strong></td>
                            <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                            <td><?php echo $order['items_count']; ?> item(s)</td>
                            <td><strong>$<?php echo number_format($order['total'], 2); ?></strong></td>
                            <td>
                                <span class="status status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <button class="reorder-btn" 
                                        onclick="reorder(<?php echo $order['order_id']; ?>)" 
                                        <?php echo ($order['status'] != 'completed') ? 'disabled' : ''; ?>>
                                    <i class="fas fa-redo"></i> Reorder
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-orders">
                <i class="fas fa-shopping-bag" style="font-size: 60px; color: var(--secondary-color); margin-bottom: 20px;"></i>
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Start exploring our menu!</p>
                <a href="product.php" class="shop-btn">
                    <i class="fas fa-coffee"></i> Browse Menu
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function reorder(orderId) {
            if (confirm('Add all items from this order to your cart?')) {
              
                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                btn.disabled = true;
                
            
                fetch('reorder.php?order_id=' + orderId)
  .then(response => response.json())

                    .then(data => {
    if(data.success){
        localStorage.setItem("reorderBasket", JSON.stringify(data.basket));
        window.location.href = "product.php";
    } else {
        alert(data.message);
    }
})
;
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>