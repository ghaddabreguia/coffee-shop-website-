<?php
require_once "admin_auth.php";

$current_page = basename($_SERVER['PHP_SELF']);



require_once 'config.php';


$reply_success = '';
$reply_error = '';
$reply_message_data = null;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_reply'])) {
    $to_email = $_POST['to_email'];
    $subject = $_POST['subject'];
    $reply_text = $_POST['reply_text'];
    $message_id = $_POST['message_id'];
    
    // تنظيف البيانات
    $to_email = filter_var($to_email, FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($subject);
    $reply_text = htmlspecialchars($reply_text);
    
    // إعداد محتوى الإيميل
    $email_headers = "From: Coffee Shop <coffeeshop@localhost>\r\n";
    $email_headers .= "Reply-To: coffeeshop@localhost\r\n";
    $email_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // محتوى HTML للإيميل
    $email_content = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #6F4E37; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background-color: #f9f9f9; }
            .reply { background-color: white; padding: 15px; border-left: 4px solid #6F4E37; margin: 15px 0; }
            .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; color: #777; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Coffee Shop</h1>
                <p>Response to Your Inquiry</p>
            </div>
            <div class='content'>
                <h2>Re: $subject</h2>
                <p>Dear Valued Customer,</p>
                <div class='reply'>
                    " . nl2br($reply_text) . "
                </div>
                <p>Thank you for contacting Coffee Shop. We appreciate your feedback!</p>
                <p><strong>Coffee Shop Team</strong><br>
                Phone: 77-945-60-22<br>
                Email: CoffeeShop@gmail.com</p>
            </div>
            <div class='footer'>
                <p>© 2026 Coffee Shop. All rights reserved.</p>
                <p>This is an automated response to your inquiry.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // send email
    if (mail($to_email, "Re: " . $subject, $email_content, $email_headers)) {
        $reply_success = "✓ Reply has been sent to $to_email";
        
        //update message state in DB
        $conn->query("UPDATE messages SET is_read = 1 WHERE id = $message_id");
        
       
        $conn->query("INSERT INTO message_replies (message_id, reply_text, replied_at) 
                      VALUES ($message_id, '" . $conn->real_escape_string($reply_text) . "', NOW())");
    } else {
        $reply_error = "✗ Failed to send email. Please check server configuration.";
    }
}


if (isset($_GET['read'])) {
    $message_id = intval($_GET['read']);
    $conn->query("UPDATE messages SET is_read = 1 WHERE id = $message_id");
}

// delete message
if (isset($_GET['delete'])) {
    $message_id = intval($_GET['delete']);
    $conn->query("DELETE FROM messages WHERE id = $message_id");
    header("Location: contact_dashbord.php");
    exit();
}

// get message
$messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");

// goback to non read message
$unread_count_result = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0");
$unread_count = $unread_count_result->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashbord.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Contact Messages - Dashboard</title>
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
            width: 90px;
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
        ul li a.active {
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
        .content {
            width: 100%;
            margin: 10px;
        }
        .title-info {
            background-color: #2196F3;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
            margin: 10px 0;
        }
        .badge {
            background-color: #FF5722;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 10px;
        }
        .messages-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .messages-table th,
        .messages-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #333;
        }
        .messages-table th {
            background-color: #1976D2;
            color: white;
        }
        .messages-table tr:hover {
            background-color: #f5f5f5;
        }
        .unread {
            background-color: #f0f8ff;
            font-weight: bold;
        }
        .message-subject {
            color: #1976D2;
            text-decoration: none;
        }
        .message-subject:hover {
            text-decoration: underline;
        }
        .message-preview {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #666;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            cursor: pointer;
            border: none;
        }
        .btn-read {
            background-color: #4CAF50;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-view {
            background-color: #2196F3;
            color: white;
        }
        .btn-reply {
            background-color: #FF9800;
            color: white;
        }
        .message-details {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .message-details h3 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1976D2;
        }
        .message-details p {
            color: #666;
            margin: 10px 0;
        }
        .message-meta {
            display: flex;
            justify-content: space-between;
            color: #999;
            font-size: 14px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .empty-state i {
            font-size: 50px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        /* تصميم نموذج الرد */
        .reply-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #FF9800;
        }
        .reply-form h4 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            color: #555;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="email"],
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 150px;
            resize: vertical;
        }
        .reply-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-send {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-send:hover {
            background-color: #45a049;
        }
        .btn-cancel {
            background-color: #757575;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-cancel:hover {
            background-color: #616161;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message-content {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            line-height: 1.6;
            color: #444;
        }
        .reply-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-unread {
            background-color: #FF9800;
            color: white;
        }
        .status-read {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>




<div class="menu">
    <ul>
       <li class="profile">
            <div class="img-box">
                <img src="image/admin.png" alt="profile">
            </div>
            <h2><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></h2>
        </li>
        <li>
            <a href="admin-dashboard.php" class="<?= $current_page == 'admin-dashboard.php' ? 'active' : '' ?>">
                <i  class="fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>
         <li>
            <a href="client_dashbord.php" class="<?= $current_page == 'client_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-user"></i>
                <p>clients</p>
            </a>
        </li>
         <li>
            <a href="product_dashbord.php"class="<?= $current_page == 'product_dashbord.php' ? 'active' : '' ?>">
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
    </a>
</li>

       
          <li>
            <a href="contact_dashbord.php" class="<?= $current_page == 'contact_dashbord.php' ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i>
                <p>Messages</p>
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
        <div>
            <p>Contact Messages</p>
            <?php if ($unread_count > 0): ?>
                <small><?php echo $unread_count; ?> unread message(s)</small>
            <?php endif; ?>
        </div>
        <i class="fas fa-envelope"></i>
    </div>

    <?php if (isset($_GET['view'])): ?>
        <?php
        $message_id = intval($_GET['view']);
        $message_result = $conn->query("SELECT * FROM messages WHERE id = $message_id");
        $message = $message_result->fetch_assoc();
        
        if ($message):
            $conn->query("UPDATE messages SET is_read = 1 WHERE id = $message_id");
        ?>
        <div class="message-details">
            <h3><?php echo htmlspecialchars($message['subject']); ?></h3>
            
            <?php if ($reply_success): ?>
                <div class="alert alert-success">
                    <?php echo $reply_success; ?>
                </div>
            <?php elseif ($reply_error): ?>
                <div class="alert alert-error">
                    <?php echo $reply_error; ?>
                </div>
            <?php endif; ?>
            
            <p><strong>From:</strong> <?php echo htmlspecialchars($message['name']); ?> 
               (<?php echo htmlspecialchars($message['email']); ?>)</p>
            <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($message['created_at'])); ?></p>
            <p><strong>Status:</strong> 
                <span class="reply-status <?php echo $message['is_read'] ? 'status-read' : 'status-unread'; ?>">
                    <?php echo $message['is_read'] ? 'Read' : 'Unread'; ?>
                </span>
            </p>
            
            <div class="message-content">
                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
            </div>
            
            <div class="message-meta">
                <span>Message ID: #<?php echo $message['id']; ?></span>
                <span>Received: <?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?></span>
            </div>
            
         
            <div class="reply-form">
                <h4><i class="fas fa-reply"></i> Send Reply via Email</h4>
                
                <form method="POST" action="contact_dashbord.php?view=<?php echo $message['id']; ?>">
                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                    <input type="hidden" name="to_email" value="<?php echo htmlspecialchars($message['email']); ?>">
                    <input type="hidden" name="subject" value="<?php echo htmlspecialchars($message['subject']); ?>">
                    
                    <div class="form-group">
                        <label>To:</label>
                        <input type="email" value="<?php echo htmlspecialchars($message['email']); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>Subject:</label>
                        <input type="text" value="Re: <?php echo htmlspecialchars($message['subject']); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="reply_text">Your Reply:</label>
                        <textarea name="reply_text" id="reply_text" required placeholder="Type your reply message here..."></textarea>
                    </div>
                    
                    <div class="reply-buttons">
                        <button type="submit" name="send_reply" class="btn-send">
                            <i class="fas fa-paper-plane"></i> Send Reply
                        </button>
                        <a href="contact_dashbord.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Back to Messages
                        </a>
                    </div>
                </form>
            </div>
            
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <a href="contact_dashbord.php" class="btn btn-view">
                    <i class="fas fa-arrow-left"></i> Back to Messages
                </a>
                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" 
                   class="btn btn-reply" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Open in Email Client
                </a>
                <a href="contact_dashbord.php?delete=<?php echo $message['id']; ?>" 
                   class="btn btn-delete" 
                   onclick="return confirm('Are you sure you want to delete this message?')">
                    <i class="fas fa-trash"></i> Delete
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-exclamation-circle"></i>
            <p>Message not found.</p>
            <a href="contact_dashbord.php" class="btn btn-view">Back to Messages</a>
        </div>
        <?php endif; ?>
    
    <?php elseif ($messages->num_rows > 0): ?>
        <table class="messages-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Name</th>
                    <th width="20%">Email</th>
                    <th width="20%">Subject</th>
                    <th width="25%">Message Preview</th>
                    <th width="10%">Date</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($msg = $messages->fetch_assoc()): ?>
                <tr class="<?php echo $msg['is_read'] ? '' : 'unread'; ?>">
                    <td><?php echo $msg['id']; ?></td>
                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                    <td>
                        <a href="contact_dashbord.php?view=<?php echo $msg['id']; ?>" 
                           class="message-subject">
                            <?php echo htmlspecialchars($msg['subject']); ?>
                        </a>
                    </td>
                    <td class="message-preview" title="<?php echo htmlspecialchars($msg['message']); ?>">
                        <?php echo substr(htmlspecialchars($msg['message']), 0, 50); ?>...
                    </td>
                    <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                    <td class="actions">
                        <a href="contact_dashbord.php?view=<?php echo $msg['id']; ?>" 
                           class="btn btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="contact_dashbord.php?delete=<?php echo $msg['id']; ?>" 
                           class="btn btn-delete"
                           onclick="return confirm('Delete this message?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 20px; text-align: center; color: #666;">
            <p><i class="fas fa-info-circle"></i> Total Messages: <?php echo $messages->num_rows; ?> | 
               Unread: <?php echo $unread_count; ?></p>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Messages Yet</h3>
            <p>No contact messages have been received.</p>
        </div>
    <?php endif; ?>
</div>

<script>

setTimeout(function() {
    location.reload();
}, 60000);

// تأكيد الحذف
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to delete this message?')) {
            e.preventDefault();
        }
    });
});


const replyTextarea = document.getElementById('reply_text');
if (replyTextarea) {
    const charCount = document.createElement('div');
    charCount.style.color = '#666';
    charCount.style.fontSize = '12px';
    charCount.style.marginTop = '5px';
    charCount.innerHTML = 'Characters: <span id="charCount">0</span>';
    replyTextarea.parentNode.appendChild(charCount);
    
    replyTextarea.addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length;
    });
}
</script>

</body>
</html>