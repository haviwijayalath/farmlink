<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>
<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/payment.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="content">
    <div class="order-summery">
        <h2>CART TOTAL</h2>
        <div class="item">
            <div class="lable">SUB TOTAL</div> 
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

        <a class="place-order-btn" onclick="payNow();"> Buy Now </a>
        <!-- <script src="http://localhost/farmlink/script.js"></script> -->
        <!-- <script src="script.js"></script> -->
        <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

        <!-- <a class="place-order-btn" href="<?php echo URLROOT?>/Buyercontrollers/orderConfirm">
            Place Order
        </a> -->
        </a> -->
    </div>
</div>

<script>

    function payNow(){

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = ()=>{
        if(xhttp.readyState == 4 && xhttp.status == 200){
            //alert(xhttp.responseText); // display the respone

            var obj = JSON.parse(xhttp.responseText);

            // Payment completed. It can be a successful failure.
            payhere.onCompleted = function onCompleted(orderId) {
                console.log("Payment completed. OrderID:" + orderId);
                // Note: validate the payment and show success or failure page to the customer

                  // Redirect to confirmOrder page only on success
                  if (orderId) {
                    window.location.href = "<?= URLROOT ?>/Buyercontrollers/orderConfirm";
                }
            };

            // Payment window closed
            payhere.onDismissed = function onDismissed() {
                // Note: Prompt user to pay again or show an error page
                console.log("Payment dismissed");
            };

            // Error occurred
            payhere.onError = function onError(error) {
                // Note: show an error page
                console.log("Error:"  + error);
            };

            // Put the payment variables here
            var payment = {
                "sandbox": true,
                "merchant_id": "1229272",    // Replace your Merchant ID
                "return_url": "localhost/farmlink/buyercontrollers/paymentDetails",     // Important
                "cancel_url": "localhost/farmlink/buyercontrollers/paymentDetails",     // Important
                "notify_url": "http://sample.com/notify",
                "order_id": obj["order_id"],
                "items": obj["item"],
                "amount": obj["amount"],
                "currency": obj["currency"],
                "hash": obj["hash"], // *Replace with generated hash retrieved from backend
                "first_name": obj["first_name"],
                "last_name": obj["last_name"],
                "email": obj["email"],
                "phone": obj["phone"],
                "address": obj["address"],
                "city": obj["city"],
                "country": "Sri Lanka",
                "delivery_address": "No. 46, Galle road, Kalutara South",
                "delivery_city": "Kalutara",
                "delivery_country": "Sri Lanka",
                "custom_1": "",
                "custom_2": ""
            };

            payhere.startPayment(payment);

        }
    }
    xhttp.open("GET","<?= URLROOT ?>/Buyercontrollers/payhereProcess",true);
    xhttp.send();
}

</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>


