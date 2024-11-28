<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/confirmOrder.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="container">
    <div class="icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="message">
        Your order is successfully place
    </div>
    <a class="track-order-btn" href="<?php echo URLROOT?>/Buyercontrollers/buyerOrders">
        Track Order
    </a>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
