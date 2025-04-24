<?php require APPROOT . '/views/inc/header1.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/home/home.css">

    <!-- Hero Section -->
    <section class="hero">
    <img src= "<?php echo URLROOT; ?>/public/images/heroimage.jpg" class = "a">
    <div class="hero-content">
        <h1>Welcome to the place where farming meets innovation</h1>
        <p>Empowering farmers and connecting them to markets and expert services.</p>
    </div>
</section>


    <!-- Products Section -->
    <section id="products" class="products"> 
        <h2>Discover Our Products</h2>
        <div class="product-grid">
            <div class="product-item">
                <img src="<?php echo URLROOT; ?>/public/images/vegetable.jpg" alt="Product 1">
                <h3>Fresh Vegetables</h3>
            </div>
            <div class="product-item">
                <img src="<?php echo URLROOT; ?>/public/images/grains.jpg" alt="Product 2">
                <h3>Organic Wheat</h3>
            </div>
            <div class="product-item">
                <img src="<?php echo URLROOT; ?>/public/images/fruits.jpg" alt="Product 3">
                <h3>Fresh Fruits</h3>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about">
        <h2>About FarmLink</h2>
            <p>Welcome to FarmLink, your trusted agricultural marketplace designed to connect farmers and 
              wholesale buyers directly. Our platform empowers farmers to showcase and sell their harvests, 
              while buyers benefit from easy access to fresh produce at competitive prices.
            </p>
            <p>
              In addition to being a marketplace, FarmLink offers expert consultation services to help farmers optimize their yields, 
              tackle challenges, and adopt sustainable farming practices. We also provide seamless delivery services, ensuring that goods 
              are transported efficiently from farm to buyer.
            </p>
            <p>
              At FarmLink, we aim to foster a thriving agricultural community that benefits everyone, from farm to table. 
              Join us and be a part of the future of farming.
            </p>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="about">
        <h2>Contact Us</h2>
        <p>If you have any questions or inquiries, feel free to reach out to us:</p>
        <ul>
            <li>Email: info@farmlink.com</li>
            <li>Phone: (123) 456-7890</li>
            <li>Address: 123 FarmLink St, Agriculture City, AG 12345</li>
        </ul>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2025 FarmLink. All rights reserved.</p>
    </footer>
</body>
</html>
