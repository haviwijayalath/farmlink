<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/admin.css">

<div class="sidebar">
    <ul>
        <li><a href="<?php echo URLROOT ?>/Admins/index">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a></li>
        <li><a href="<?php echo URLROOT ?>/AdminControllers/index">
            <i class="fas fa-users"></i> Users
        </a></li>
        <li><a href="<?php echo URLROOT ?>/Admins/products">
            <i class="fas fa-shopping-basket"></i> Products
        </a></li>
        <li><a href="<?php echo URLROOT ?>/Admins/viewComplaints">
            <i class="fas fa-exclamation-circle"></i> Complaints
        </a></li>
        <li><a href="<?php echo URLROOT ?>/Admins/orders">
            <i class="fas fa-clipboard-list"></i> Orders
        </a></li>
        <li><a href="<?php echo URLROOT ?>/Admins/viewReports">
            <i class="fas fa-chart-line"></i> Reports
        </a></li>
        <li><a href="<?php echo URLROOT ?>/AdminControllers/viewSupport">
            <i class="fas fa-headset"></i> Support Requests
        </a></li>
        <div class="sidebar-spacer"></div>
        <li><a href="<?php echo URLROOT ?>/Admins/account">
            <i class="fas fa-cog"></i> Settings
        </a></li>
        <!-- Logout -->
        <li><a href="javascript:void(0)" onclick="showPopup()">
            <i class="fa-solid fa-right-from-bracket fa-rotate-180"></i> Logout
        </a></li>
    </ul>
</div>

<!-- Logout Confirmation Popup -->
<div class="popup-container" id="logout-popup" style="display: none;">
    <div class="popup-content">
        <h2>Log out of your account?</h2>
        <div class="button-container">
            <button class="logout-button" onclick="confirmLogout()">Log out</button>
            <button class="cancel-button" onclick="closePopup()">Cancel</button>
        </div>
    </div>
</div>

<script>
    // Show the popup
    function showPopup() {
        document.getElementById('logout-popup').style.display = 'flex';
    }

    // Hide the popup
    function closePopup() {
        document.getElementById('logout-popup').style.display = 'none';
    }

    // Handle logout confirmation
    function confirmLogout() {
        // Redirect to logout URL
        window.location.href = "<?= URLROOT ?>/Users/logout";
    }
</script>
