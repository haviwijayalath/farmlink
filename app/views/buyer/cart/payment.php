<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/payment.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="content">
    <div class="order-summery">
        <h2>CART TOTAL</h2>
        <div class="item">
            <div class="lable">SUB TOTAL</div> 
            <div class="value">Rs.1750</div>
        </div>
        <div class="divider"></div>
        <div class="item">
            <div class="lable">SHIPPING</div>
            <div class="value">FREE</div>
        </div>
        <div class="divider"></div>
        <div class="item">
            <div class="lable">TOTAL</div>
            <div class="value">Rs.1750</div>
        </div>
    </div>

    <div class="payment-option">
        <h2>Payment Options</h2>
        <div class="payment-method">
            <input checked="" id="card" name="payment" type="radio" value="card"/>
            <label for="card">
                Credit/Debit card
            </label>
            <img alt="Visa logo" height="20" width="20" src="<?= URLROOT ?>/public/images/1.jpg"/>
            <img alt="American Express logo" height="20" width="20" src="<?= URLROOT ?>/public/images/2.jpg"/>
            <img alt="Maestro logo" height="20" width="20" src="<?= URLROOT ?>/public/images/3.jpg"/>
        </div>
        <span class="payment-method-info">
            Pay with your Visa, American Express or Mastercard.
        </span>


        <div class="payment-details">
        <input placeholder="Card holder name" type="text"/>
            <input placeholder="Card number" type="text"/>
            <input placeholder="Expiry date" type="text"/>
            <input placeholder="Security code(CVV)" type="text"/>
        </div>

        <div class="review-order">
            <h2>
                Review &amp; Place Order
            </h2>
            <p>
                Please review the order details and payment details before proceeding to confirm your order
            </p>
        </div>

        <a class="place-order-btn" href="<?php echo URLROOT?>/Buyercontrollers/orderConfirm">
            Place Order
        </a>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>


