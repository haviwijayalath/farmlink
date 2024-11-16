<!-- navbar.php -->
<!-- navbar.php -->
<header class="header">
    <div class="logo">
        <img src="<?= URLROOT ?>/public/images/logo.png" alt="FarmLink Logo" style="width:200px;"> <!-- Replace with your logo -->
    </div>

    <div style="display: flex; gap: 30px; font-size: 20px; font-family: 'Poppins', sans-serif;">

    <?php if(isset($_SESSION['user_id'])) : ?>

        <a href="<?php echo URLROOT; ?>/DpersonRegistrations/logout" class="login-btn"><i class="fa-solid fa-user"></i> Logout</a>

    <?php else : ?>

        <span><a href="<?php echo URLROOT; ?>/home/home2"><i class="fa-solid fa-house"></i> Home</a></span> 
        <span><a href="<?php echo URLROOT; ?>/DpersonRegistrations/login" class="login-btn"><i class="fa-solid fa-user"></i> Login</a></span>
        
    <?php endif; ?>
    </div>
</header>