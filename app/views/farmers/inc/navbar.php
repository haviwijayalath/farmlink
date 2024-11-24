<!-- navbar.php -->

<header class="header">
    <div class="logo">
        <a href="/farmers">
            <img src="<?= URLROOT ?>/public/images/logo.png" alt="FarmLink Logo" style="width:200px;">
        </a>
    </div>

    <div style="display: flex; gap: 30px; font-size: 20px; font-family: 'Poppins', sans-serif;">

    <?php if(isset($_SESSION['user_id'])) : ?>

        <h3>Welcome, <?php echo $_SESSION['user_name']; ?></h3>

        <a href="<?php echo URLROOT; ?>/Users/logout" class="login-btn"><i class="fa-solid fa-user"></i> ljhkgfds</a>

        <!-- <a href="<?php echo URLROOT; ?>/Users/profile" class="profile-btn"><i class="fa-solid fa-user-circle"></i> Profile</a> -->

        

    <?php else : ?>

        <span><a href="<?php echo URLROOT; ?>/home/home2"><i class="fa-solid fa-house"></i> Home</a></span> 
        <span><a href="<?php echo URLROOT; ?>/Users/login" class="login-btn"><i class="fa-solid fa-user"></i> Login</a></span>
        
    <?php endif; ?>
    </div>
</header>