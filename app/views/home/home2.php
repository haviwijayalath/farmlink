<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/home/home2.css">

    <main>
        <section class="welcome-section">
            <div class="welcome-text">
                <h1>Welcome to FarmLink!</h1>
            </div>
            <div class="market-image">
            <img src="<?= URLROOT ?>/public/images/market.png" alt="Market" class="market-image">
            </div>
        </section>

        <section class="register-section">
            <h2>Register Now</h2>
            <ul class="role-list">
                <li><a href="<?php echo URLROOT; ?>/users/register?role=Farmer">Farmer</a></li>
                <li><a href="<?php echo URLROOT; ?>/users/register?role=Supplier">Supplier</a></li>
                <li><a href="<?php echo URLROOT; ?>/users/register?role=Consultant">Consultant</a></li>
                <li><a href="<?php echo URLROOT; ?>/users/register?role=WholesaleBuyer">Wholesale Buyer</a></li>
                <li><a href="<?php echo URLROOT; ?>/DpersonRegistrations/register?role=DeliveryPerson">Delivery Person</a></li>
            </ul>
        </section>
    </main>

<?php require APPROOT . '/views/inc/footer.php'; ?>