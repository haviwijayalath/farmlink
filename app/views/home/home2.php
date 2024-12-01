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
            <h1>Register Now</h1>
            <ul class="role-list">
                <li><a href="<?php echo URLROOT; ?>/farmers/register">Farmer</a></li>
                <li><a href="<?php echo URLROOT; ?>/consultants/register?role=Consultant">Consultant</a></li>
                <li><a href="<?php echo URLROOT; ?>/Buyercontrollers/register?role=WholesaleBuyer">Wholesale Buyer</a></li>
                <li><a href="<?php echo URLROOT; ?>/dpersons/register?role=DeliveryPerson">Delivery Person</a></li>
            </ul>
        </section>
    </main>

<?php require APPROOT . '/views/inc/footer.php'; ?>