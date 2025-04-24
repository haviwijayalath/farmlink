<header>
    <div class="navbar">
        <!-- Logo Section -->
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/public/images/FarmLink-logo.png" alt="FarmLink Logo" class="logo-img">
            
        </div>
        
        <!-- Navigation Menu -->
        <nav class="menu">
            <ul>
                <li><a href="<?php echo URLROOT; ?>/home/home">Home</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home#products">Products</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home#about">About Us</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home#contact">Contact</a></li>
                <li><a href="<?php echo URLROOT; ?>/Buyercontrollers/browseproducts">Buy Now</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home2">Register</a></li>
                <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) : ?>
                     <?php redirect($_SESSION['user_role'].'s'); ?>
                <?php else : ?>
                    <li><a href="<?php echo URLROOT; ?>/Users/login" class="login-btn">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
