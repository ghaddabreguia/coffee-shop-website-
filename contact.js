// contact.js - الجافاسكريبت الخاص بصفحة الرسائل

let currentMessage = null;
let selectedMessageElement = null;

// عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل البحث
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterMessages('search', this.value);
        });
    }
    
    // تحديث تلقائي كل 30 ثانية
    setInterval(checkNewMessages, 30000);
});

// اختيار رسالة
function selectMessage(element, messageId) {
    // إزالة التحديد من جميع الرسائل
    document.querySelectorAll('.message-item').forEach(msg => {
        msg.classList.remove('selected');
    });
    
    // تحديد الرسالة المختارة
    element.classList.add('selected');
    element.classList.remove('unread');
    
    // حفظ العنصر المحدد
    selectedMessageElement = element;
    
    // الحصول على البيانات
    const messageId = element.getAttribute('data-id');
    const name = element.getAttribute('data-name');
    const email = element.getAttribute('data-email');
    const subject = element.getAttribute('data-subject');
    const message = element.getAttribute('data-message');
    const time = element.getAttribute('data-time');
    
    // حفظ البيانات الحالية
    currentMessage = {
        id: messageId,
        name: name,
        email: email,
        subject: subject,
        message: message,
        time: time
    };
    
    // إظهار تفاصيل الرسالة
    showMessageDetail();
}

// إظهار تفاصيل الرسالة
function showMessageDetail() {
    if (!currentMessage) return;
    
    const detailSection = document.getElementById('detailSection');
    
    // إخفاء الرسالة الفارغة
    const emptyDetail = document.getElementById('emptyDetail');
    if (emptyDetail) emptyDetail.style.display = 'none';
    
    // تعبئة تفاصيل الرسالة
    detailSection.innerHTML = `
        <div class="message-detail active">
            <div class="detail-header">
                <h4>${currentMessage.subject}</h4>
                <div class="message-meta">
                    <div class="message-sender-info">
                        <div class="sender-avatar">${currentMessage.name.charAt(0).toUpperCase()}</div>
                        <div>
                            <div><strong>From:</strong> ${currentMessage.name}</div>
                            <div><strong>Email:</strong> ${currentMessage.email}</div>
                            <div><strong>Time:</strong> ${currentMessage.time}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="message-body">
                <p>${currentMessage.message.replace(/\n/g, '<br>')}</p>
            </div>
            <div class="message-actions">
                <button class="btn btn-primary" onclick="showReplyForm()">
                    <i class="fas fa-reply"></i> Reply
                </button>
                <button class="btn btn-danger" onclick="deleteMessage()">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    `;
}

// تصفية الرسائل
function filterMessages(filterType, searchTerm = '') {
    const messages = document.querySelectorAll('.message-item');
    let visibleCount = 0;
    
    messages.forEach(message => {
        let show = true;
        const name = message.getAttribute('data-name') || '';
        const email = message.getAttribute('data-email') || '';
        const subject = message.getAttribute('data-subject') || '';
        const isUnread = message.classList.contains('unread');
        
        if (filterType === 'unread') {
            show = isUnread;
        } else if (filterType === 'today') {
            const time = message.querySelector('.message-time').textContent;
            show = !time.includes('Dec');
        } else if (filterType === 'search' && searchTerm) {
            const searchLower = searchTerm.toLowerCase();
            show = name.toLowerCase().includes(searchLower) || 
                   email.toLowerCase().includes(searchLower) || 
                   subject.toLowerCase().includes(searchLower);
        }
        
        if (show) {
            message.style.display = 'flex';
            visibleCount++;
        } else {
            message.style.display = 'none';
        }
    });
    
    // تحديث علامات التبويب النشطة
    document.querySelectorAll('.inbox-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    if (filterType === 'all') {
        document.querySelector('.inbox-tab:nth-child(1)').classList.add('active');
    } else if (filterType === 'unread') {
        document.querySelector('.inbox-tab:nth-child(2)').classList.add('active');
    } else if (filterType === 'today') {
        document.querySelector('.inbox-tab:nth-child(3)').classList.add('active');
    }
}

// تحديث الرسائل
function refreshMessages() {
    const refreshBtn = document.querySelector('.inbox-actions button:first-child');
    if (refreshBtn) {
        const originalHtml = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        refreshBtn.disabled = true;
        
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
}

// فحص رسائل جديدة
function checkNewMessages() {
    // في الحقيقة، هنا يمكنك إضافة AJAX للتحقق من الرسائل الجديدة
    console.log('Checking for new messages...');
}

// عرض نموذج الرد
function showReplyForm() {
    if (!currentMessage) {
        alert('Please select a message first');
        return;
    }
    
    // تعبئة النموذج
    document.getElementById('replyToEmail').value = currentMessage.email;
    document.getElementById('originalSubject').value = currentMessage.subject;
    document.getElementById('toEmail').value = currentMessage.email;
    document.getElementById('replySubject').value = 'Re: ' + currentMessage.subject;
    document.getElementById('replyMessage').value = `\n\n--- Original Message ---\nFrom: ${currentMessage.name}\nSubject: ${currentMessage.subject}\n\n${currentMessage.message}`;
    
    // إظهار المودال
    const modal = document.getElementById('replyModal');
    modal.classList.add('active');
    
    // التركيز على حقل الرسالة
    setTimeout(() => {
        const messageField = document.getElementById('replyMessage');
        if (messageField) {
            messageField.focus();
            messageField.setSelectionRange(0, 0);
        }
    }, 100);
}

// إغلاق نموذج الرد
function closeReplyForm() {
    const modal = document.getElementById('replyModal');
    modal.classList.remove('active');
}

// إرسال الرد
function sendReply(event) {
    event.preventDefault();
    
    const toEmail = document.getElementById('toEmail').value;
    const subject = document.getElementById('replySubject').value;
    const message = document.getElementById('replyMessage').value;
    
    // التحقق من البيانات
    if (!toEmail || !subject || !message) {
        alert('Please fill in all fields');
        return;
    }
    
    // إرسال الرد فعلياً
    sendEmail(toEmail, subject, message);
}

// إرسال البريد الإلكتروني فعلياً
function sendEmail(to, subject, message) {
    // زر الإرسال
    const sendBtn = document.querySelector('#replyForm button[type="submit"]');
    const originalText = sendBtn.innerHTML;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    sendBtn.disabled = true;
    
    // استخدام AJAX لإرسال البريد
    fetch('send_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `to=${encodeURIComponent(to)}&subject=${encodeURIComponent(subject)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            alert('Reply sent successfully!');
            closeReplyForm();
            
            // حفظ الرد في قاعدة البيانات
            saveReplyToDB(to, subject, message);
        } else {
            alert('Error sending reply: ' + data);
        }
    })
    .catch(error => {
        alert('Network error: ' + error);
    })
    .finally(() => {
        sendBtn.innerHTML = originalText;
        sendBtn.disabled = false;
    });
}

// حفظ الرد في قاعدة البيانات
function saveReplyToDB(to, subject, message) {
    fetch('save_reply.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `to=${encodeURIComponent(to)}&subject=${encodeURIComponent(subject)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.text())
    .then(data => {
        console.log('Reply saved:', data);
    })
    .catch(error => {
        console.error('Error saving reply:', error);
    });
}

// حذف الرسالة
function deleteMessage() {
    if (!currentMessage) {
        alert('Please select a message first');
        return;
    }
    
    if (!confirm('Are you sure you want to delete this message?')) {
        return;
    }
    
    fetch('delete_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + currentMessage.id
    })
    .then(response => response.text())
    .then(data => {
        alert('Message deleted successfully!');
        location.reload();
    })
    .catch(error => {
        alert('Error deleting message: ' + error);
    });
}

// إغلاق المودال عند النقر خارجها
document.addEventListener('click', function(event) {
    const modal = document.getElementById('replyModal');
    if (event.key === 'Escape') {
        closeReplyForm();
    }
});