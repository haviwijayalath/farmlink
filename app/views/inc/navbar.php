<!-- navbar.php -->
<header class="header">
    <div class="logo">
        <img src="<?= URLROOT ?>/public/images/FarmLink-logo.png" alt="FarmLink Logo" style="width:200px;"> <!-- Replace with your logo -->
    </div>

    <div style="display: flex; gap: 30px; font-size: 20px; font-family: 'Poppins', sans-serif;">

    <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) : ?>
        
        <div class="notification-container">
            <a href="#" id="notification-bell">
                <i class="fa-solid fa-bell"></i>
                <span class="notification-badge">0</span>
            </a>
            <div class="notification-dropdown">
                <div class="notification-header">
                    <h3>Notifications</h3>
                    <a href="#" class="mark-all-read">Mark all as read</a>
                </div>
                <div class="notification-list">
                    <!-- Notifications will be loaded here -->
                    <div class="no-notifications">No new notifications</div>
                </div>
            </div>
        </div>
        
    <?php else : ?>

        <span><a href="<?php echo URLROOT; ?>/home/home"><i class="fa-solid fa-house"></i> Home</a></span> 
        <span><a href="<?php echo URLROOT; ?>/Users/login" class="login-btn"><i class="fa-solid fa-user"></i> Login</a></span>
        
    <?php endif; ?>
    </div>
</header>

<!-- Add this just before the closing body tag of your layout file -->
<script src="<?php echo URLROOT; ?>/public/js/notifications.js"></script>