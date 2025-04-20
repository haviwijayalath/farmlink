document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notification-bell');
    const notificationDropdown = document.querySelector('.notification-dropdown');
    const notificationBadge = document.querySelector('.notification-badge');
    const markAllReadBtn = document.querySelector('.mark-all-read');
    const notificationList = document.querySelector('.notification-list');
    
    // Toggle dropdown when clicking the bell
    if (notificationBell) {
        notificationBell.addEventListener('click', function(e) {
            e.preventDefault();
            notificationDropdown.classList.toggle('show');
            
            // If opened and has unread notifications, fetch them
            if (notificationDropdown.classList.contains('show')) {
                fetchNotifications();
            }
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationBell?.contains(e.target) && !notificationDropdown?.contains(e.target)) {
            notificationDropdown?.classList.remove('show');
        }
    });
    
    // Mark all as read
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }
    
    // Function to fetch notifications
    function fetchNotifications() {
        // This would typically be an AJAX call to your backend
        // For demo purposes, we'll use dummy data
        const dummyNotifications = [
            {
                id: 1,
                title: 'New Order',
                message: 'You have received a new order from John Doe',
                time: '5 minutes ago',
                isRead: false
            },
            {
                id: 2,
                title: 'Payment Received',
                message: 'Payment of $50 has been processed successfully',
                time: '1 hour ago',
                isRead: false
            },
            {
                id: 3,
                title: 'Profile Update',
                message: 'Your profile information has been updated',
                time: 'Yesterday',
                isRead: true
            }
        ];
        
        renderNotifications(dummyNotifications);
        updateNotificationBadge(dummyNotifications.filter(n => !n.isRead).length);
    }
    
    // Function to render notifications in the dropdown
    function renderNotifications(notifications) {
        if (!notificationList) return;
        
        if (notifications.length === 0) {
            notificationList.innerHTML = '<div class="no-notifications">No new notifications</div>';
            return;
        }
        
        let html = '';
        notifications.forEach(notification => {
            html += `
                <div class="notification-item ${notification.isRead ? '' : 'unread'}" data-id="${notification.id}">
                    <div class="notification-content">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-time">${notification.time}</div>
                    </div>
                </div>
            `;
        });
        
        notificationList.innerHTML = html;
        
        // Add click event for each notification
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                markAsRead(notificationId);
                this.classList.remove('unread');
            });
        });
    }
    
    // Function to update notification badge count
    function updateNotificationBadge(count) {
        if (!notificationBadge) return;
        
        notificationBadge.textContent = count;
        if (count > 0) {
            notificationBadge.style.display = 'flex';
        } else {
            notificationBadge.style.display = 'none';
        }
    }
    
    // Function to mark a notification as read
    function markAsRead(notificationId) {
        // This would typically be an AJAX call to your backend
        console.log('Marked notification as read:', notificationId);
        
        // Update badge count (subtract 1)
        const currentCount = parseInt(notificationBadge.textContent);
        if (currentCount > 0) {
            updateNotificationBadge(currentCount - 1);
        }
    }
    
    // Function to mark all notifications as read
    function markAllAsRead() {
        // This would typically be an AJAX call to your backend
        console.log('Marked all notifications as read');
        
        // Update all items in the UI
        document.querySelectorAll('.notification-item').forEach(item => {
            item.classList.remove('unread');
        });
        
        // Update badge count to zero
        updateNotificationBadge(0);
    }
    
    // Check for notifications periodically (every 60 seconds)
    setInterval(function() {
        if (!notificationDropdown.classList.contains('show')) {
            fetchNotifications();
        }
    }, 60000);
    
    // Initial fetch
    fetchNotifications();
});