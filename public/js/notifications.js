document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notification-bell');
    const notificationDropdown = document.querySelector('.notification-dropdown');
    const notificationBadge = document.querySelector('.notification-badge');
    const markAllReadBtn = document.querySelector('.mark-all-read');
    const notificationList = document.querySelector('.notification-list');
    let userType = ''; // Will be determined from the notification data
    
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
    
    // Function to fetch notifications using AJAX
    function fetchNotifications() {
        // Get user type from the window if available
        if (window.userType && !userType) {
            userType = window.userType;
        }
        fetch(`${window.location.origin}/farmlink/Notifications/getNotifications`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Notification data:', data); // Debug logging
            
            if (Array.isArray(data)) {
                // Handle case when data is a direct array (for backward compatibility)
                renderNotifications(data);
                updateNotificationBadge(data.filter(n => n.status === 'unread').length);
            } else if (Array.isArray(data) && data.length === 2) {
                // Handle expected format [notifications, count]
                const notifications = data[0];
                const count = data[1];
                
                // If we have notifications, extract user type from the first one
                if (notifications && notifications.length > 0 && !userType) {
                    userType = determineUserType(notifications[0]);
                }
                
                renderNotifications(notifications);
                updateNotificationBadge(notifications.filter(n => n.status === 'unread').length);
            } else if (data.error) {
                // Handle error case
                console.error('Error in notification data:', data.error);
                notificationList.innerHTML = '<div class="no-notifications">No new notifications</div>';
                updateNotificationBadge(0);
            } else {
                // Handle unexpected data format
                console.error('Unexpected notification data format', data);
                notificationList.innerHTML = '<div class="no-notifications">No new notifications</div>';
                updateNotificationBadge(0);
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            if (notificationList) {
                notificationList.innerHTML = '<div class="no-notifications">Error loading notifications</div>';
            }
            updateNotificationBadge(0);
        });
    }
    
    // Determine user type from notification
    function determineUserType(notification) {
        // Check if to_type exists directly
        if (notification.hasOwnProperty('to_type')) {
            return notification.to_type;
        }
        
        // Otherwise, try to determine from the origin table
        // This might be needed if the system doesn't include the to_type in response
        if (window.userType) {
            return window.userType;
        }
        
        // Fallback to 'f' if we can't determine
        return 'f';
    }
    
    // Function to render notifications in the dropdown
    function renderNotifications(notifications) {
        if (!notificationList) return;
        
        if (!notifications || notifications.length === 0) {
            notificationList.innerHTML = '<div class="no-notifications">No new notifications</div>';
            return;
        }
        
        let html = '';
        notifications.forEach(notification => {
            const isUnread = notification.status === 'unread';
            const timeAgo = formatTimeAgo(notification.date_time);
            
            html += `
                <div class="notification-item ${isUnread ? 'unread' : ''}" data-id="${notification.id}">
                    <div class="notification-content">
                        <div class="notification-title">${notification.subject}</div>
                        <div class="notification-message">${notification.content}</div>
                        <div class="notification-time">${timeAgo}</div>
                    </div>
                </div>
            `;
        });
        
        notificationList.innerHTML = html;
        
        // Add click event for each notification
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                const isUnread = this.classList.contains('unread');
                
                // If it's an unread notification, mark it as read
                if (isUnread) {
                    // Pass both the notification ID and user type
                    markAsRead(notificationId, userType);
                    this.classList.remove('unread');
                }
                
                // If there's a URL associated with this notification, navigate to it
                const matchingNotification = notifications.find(n => n.id == notificationId);
                if (matchingNotification && matchingNotification.url && matchingNotification.url !== '#') {
                    window.location.href = matchingNotification.url;
                }
            });
        });
    }
    
    // Function to format time ago
    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) {
            return `${diffInSeconds} seconds ago`;
        }
        
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) {
            return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
        }
        
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) {
            return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
        }
        
        const diffInDays = Math.floor(diffInHours / 24);
        if (diffInDays < 7) {
            return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
        }
        
        // For older notifications, show the date
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return date.toLocaleDateString(undefined, options);
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
    function markAsRead(notificationId, userTypeParam) {
        // Use the passed user type or fall back to the global one
        const typeToUse = userTypeParam || userType;
        
        console.log('Marking notification as read:', { id: notificationId, type: typeToUse }); // Debug log
        
        fetch(`${window.location.origin}/farmlink/Notifications/markAsRead`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                notification_id: notificationId,
                to_type: typeToUse
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Mark as read response:', data); // Debug log
            
            if (data.success) {
                // Update badge count (subtract 1)
                const currentCount = parseInt(notificationBadge.textContent);
                if (currentCount > 0) {
                    updateNotificationBadge(currentCount - 1);
                }
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }
    
    // Function to mark all notifications as read
    function markAllAsRead() {
        fetch(`${window.location.origin}/farmlink/Notifications/markAllAsRead`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update all items in the UI
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('unread');
                });
                
                // Update badge count to zero
                updateNotificationBadge(0);
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as read:', error);
        });
    }
    
    // Check for notifications periodically (every 30 seconds)
    setInterval(function() {
        if (!notificationDropdown.classList.contains('show')) {
            fetchNotifications();
        }
    }, 30000);
    
    // Initial fetch
    fetchNotifications();
});