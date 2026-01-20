<?php
session_start();

/* تحقق بسيط (اختياري)
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
*/

// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("Database error");
}

// جلب الرسائل
$sql = "SELECT id, name, email, subject, message, created_at, is_read
        FROM messages 
        ORDER BY id DESC";
$result = $conn->query($sql);
$total_messages = $result ? $result->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages - Dashboard</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    

    /* المحتوى الرئيسي */
    .content {
        margin-left: 10px;
        padding: 20px;
        width: 100%;
        transition: 0.3s;
    }

    /* الهيدر */
    .header {
        background: white;
        padding: 20px 25px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .header h1 {
        color: #2c3e50;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header h1 i {
        color: #3498db;
    }

    .stat-box {
        background: #3498db;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        text-align: center;
        min-width: 120px;
    }

    .stat-box strong {
        display: block;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-box span {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* حاوية الرسائل */
    .messages-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* شريط البحث */
    .search-box {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .search-box input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        outline: none;
    }

    .search-box input:focus {
        border-color: #3498db;
    }

    /* قائمة الرسائل */
    #messagesList {
        max-height: 500px;
        overflow-y: auto;
    }

    .message {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: 0.3s;
        gap: 15px;
        align-items: flex-start;
    }

    .message:hover {
        background: #f8fafd;
    }

    .message.unread {
        background: #e8f4fc;
        border-left: 4px solid #3498db;
    }

    .message.selected {
        background: #e3f2fd;
    }

    .message-avatar {
        width: 45px;
        height: 45px;
        min-width: 45px;
        border-radius: 50%;
        background: #3498db;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }

    .message-content {
        flex: 1;
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .message-header span {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .message-header small {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .message-content strong {
        display: block;
        color: #34495e;
        margin-bottom: 8px;
        font-size: 1rem;
    }

    .message-content p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.4;
    }

    /* تفاصيل الرسالة */
    #messageDetail {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-top: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    #messageDetail h2 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 1.4rem;
    }

    #messageDetail p {
        color: #34495e;
        margin-bottom: 10px;
    }

    #messageDetail hr {
        border: none;
        border-top: 1px solid #eee;
        margin: 15px 0;
    }

    #detailMessage {
        white-space: pre-wrap;
        line-height: 1.6;
        padding: 10px 0;
    }

    

    /* رسالة فارغة */
    #messagesList > p {
        text-align: center;
        padding: 40px;
        color: #7f8c8d;
        font-style: italic;
    }

    /* تصميم متجاوب */
    @media (max-width: 1024px) {
        .menu {
            transform: translateX(-100%);
        }
        
        .menu.active {
            transform: translateX(0);
        }
        
        .content {
            margin-left: 0;
        }
        
        .menu-toggle {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            text-align: center;
        }
        
        .message-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .content {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .message {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .message-avatar {
            align-self: center;
        }
    }
</style>
</head>

<body>

<button class="menu-toggle" onclick="toggleMenu()">
    <i class="fas fa-bars"></i>
</button>

<div class="menu" id="sidebar">
<ul>
    <li class="profile">
        <div class="img-box">HL</div>
        <h2>Hasna Labed</h2>
    </li>
  
    
      
        <li>
            <a class="active" href="admin-dashboard.html">
                <i  class="fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>
         <li>
            <a href="client.html">
                <i class="fas fa-user"></i>
                <p>clients</p>
            </a>
        </li>
         <li>
            <a href="product.html">
                <i class="fas fa-table"></i>
                <p>Products</p>
            </a>
        </li>
         <li>
            <a href="chart.html">
                <i class="fas fa-chart-pie"></i>
                <p>chart</p>  
            </a>
        </li>
        <li>
            <a href="order.html"></a>
            <i class="fas fa-shopping-cart"></i>
            <p>order</p>
        </li>
        <li>
            <a href="Contact.html">
                <i class="fas fa-envelope"></i>
                <p>Contact</p>
            </a>
        </li>
         <li class="log-out">
            <a href="#"> 
                <i class="fas fa-sign-out"></i>
                <p>log-out</p>
            </a>
        </li>
    </ul>



</div>

<div class="content">

<div class="header">
    <h1><i class="fas fa-envelope"></i> Customer Messages</h1>
    <div class="stat-box">
        <strong><?= $total_messages ?></strong>
        <span>Total Messages</span>
    </div>
</div>

<div class="messages-container">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search messages...">
    </div>
    
    <div id="messagesList">
    <?php
    if ($result && $total_messages > 0):
        while ($row = $result->fetch_assoc()):
            $initials = strtoupper(substr($row['name'], 0, 2));
            $time = date('H:i', strtotime($row['created_at']));
            $date = date('M d', strtotime($row['created_at']));
            $preview = substr($row['message'], 0, 80) . '...';
    ?>
    <div class="message <?= $row['is_read']==0?'unread':'' ?>"
         data-id="<?= $row['id'] ?>"
         data-message="<?= htmlspecialchars($row['message']) ?>"
         data-subject="<?= htmlspecialchars($row['subject']) ?>"
         data-sender="<?= htmlspecialchars($row['name']) ?>"
         data-time="<?= $date . ' ' . $time ?>"
         onclick="showMessage(this)">

        <div class="message-avatar"><?= $initials ?></div>

        <div class="message-content">
            <div class="message-header">
                <span><?= htmlspecialchars($row['name']) ?></span>
                <small><?= $date . ' ' . $time ?></small>
            </div>
            <strong><?= htmlspecialchars($row['subject']) ?></strong>
            <p><?= htmlspecialchars($preview) ?></p>
        </div>
    </div>
    <?php endwhile; else: ?>
    <p>No messages yet</p>
    <?php endif; ?>
    </div>
</div>

<div id="messageDetail" style="display:none">
    <h2 id="detailSubject"></h2>
    <p><strong id="detailSender"></strong> – <span id="detailTime"></span></p>
    <hr>
    <p id="detailMessage"></p>
    <br>
    <button onclick="deleteMessage()"><i class="fas fa-trash"></i> Delete Message</button>
</div>

</div>

<script>
let currentId = null;

function toggleMenu(){
    document.getElementById('sidebar').classList.toggle('active');
}

function showMessage(el){
    document.querySelectorAll('.message').forEach(m=>m.classList.remove('selected'));
    el.classList.add('selected');
    el.classList.remove('unread');

    currentId = el.dataset.id;

    document.getElementById('detailSubject').textContent = el.dataset.subject;
    document.getElementById('detailSender').textContent = el.dataset.sender;
    document.getElementById('detailTime').textContent = el.dataset.time;
    document.getElementById('detailMessage').textContent = el.dataset.message;

    document.getElementById('messageDetail').style.display = 'block';

    // Scroll to message detail on mobile
    if (window.innerWidth <= 768) {
        document.getElementById('messageDetail').scrollIntoView({ behavior: 'smooth' });
    }

    fetch('mark_read.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'id='+currentId
    });
}

function deleteMessage(){
    if(!confirm('Delete this message?')) return;
    fetch('delete_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'id='+currentId
    }).then(()=>location.reload());
}

document.getElementById('searchInput').addEventListener('input', e=>{
    const val = e.target.value.toLowerCase();
    document.querySelectorAll('.message').forEach(m=>{
        m.style.display = m.innerText.toLowerCase().includes(val)?'flex':'none';
    });
});

// Close message detail when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
        const detail = document.getElementById('messageDetail');
        const messagesList = document.getElementById('messagesList');
        
        if (detail.style.display !== 'none' && 
            !detail.contains(e.target) && 
            !e.target.closest('.message')) {
            detail.style.display = 'none';
        }
    }
});
</script>

</body>
</html>
<?php $conn->close(); ?>