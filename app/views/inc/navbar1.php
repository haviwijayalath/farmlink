<header>
    <div class="navbar">
        <!-- Logo Section -->
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/public/images/logo.png" alt="FarmLink Logo" class="logo-img">
            
        </div>
        
        <!-- Navigation Menu -->
        <nav class="menu">
            <ul>
                <li><a href="<?php echo URLROOT; ?>/home/home">Home</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home">Products</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home">Our Story</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home">About Us</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home">Contact</a></li>
                <li><a href="<?php echo URLROOT; ?>/home/home">Buy Now</a></li>
                <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) : ?>
                    <li><a href="<?php echo URLROOT; ?>/Users/logout" class="logout-btn">Logout</a></li>
                <?php else : ?>
                    <li><a href="<?php echo URLROOT; ?>/Users/login" class="login-btn">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
