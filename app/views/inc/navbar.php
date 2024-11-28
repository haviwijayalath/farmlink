<!-- navbar.php -->
<!-- navbar.php -->
<header class="header">
    <div class="logo">
        <img src="<?= URLROOT ?>/public/images/FarmLink-logo.png" alt="FarmLink Logo" style="width:200px;"> <!-- Replace with your logo -->
    </div>

    <div style="display: flex; gap: 30px; font-size: 20px; font-family: 'Poppins', sans-serif;">

    <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) : ?>
        
        <a href="<?php echo URLROOT; ?>/buyercontrollers/cartDetails"><i class="fa-solid fa-cart-shopping"></i></a>
        <a href="<?php echo URLROOT; ?>/Users/logout" class="login-btn"><i class="fa-solid fa-user"></i> Logout</a>

    <?php else : ?>

        <span><a href="<?php echo URLROOT; ?>/home/home"><i class="fa-solid fa-house"></i> Home</a></span> 
        <span><a href="<?php echo URLROOT; ?>/Users/login" class="login-btn"><i class="fa-solid fa-user"></i> Login</a></span>
        
    <?php endif; ?>
    </div>
</header>