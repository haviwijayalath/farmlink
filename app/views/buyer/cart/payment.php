<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/payment.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="content">
<div class="order-summery">
    <h2>CART TOTAL</h2>

    <div class="item">
        <div class="lable">SUB TOTAL</div> 
        <div class="value">Rs.<?= number_format($data['farmer_fee'], 2) ?></div>
    </div>

    <div class="divider"></div>

    <?php 
        $deliveryFee = isset($data['delivery_fee']) ? $data['delivery_fee'] : 0;
    ?>
    
    <?php if ($deliveryFee > 0): ?>
        <div class="item">
            <div class="lable">DELIVERY FEE</div>
            <div class="value">Rs.<?= number_format($deliveryFee, 2) ?></div>
        </div>
        <div class="divider"></div>
    <?php endif; ?>

    <div class="item">
        <div class="lable">TOTAL</div>
        <div class="value">Rs.<?= number_format($data['farmer_fee'] + $deliveryFee, 2) ?></div>
    </div>
</div>


    <div class="payment-option">
        
        <!-- <div class="payment-details">
        <input placeholder="Card holder name" type="text"/>
            <input placeholder="Card number" type="text"/>
            <input placeholder="Expiry date" type="text"/>
            <input placeholder="Security code(CVV)" type="text"/>
        </div> -->

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
    </div>
</div>

<script>

function payNow() {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = () => {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            try {
                var obj = JSON.parse(xhttp.responseText);

                if (obj.error) {
                    alert(obj.error); // Display error message
                    return;
                }

                // Payment completed callback
                payhere.onCompleted = function onCompleted(orderId) {
                    console.log("Payment completed. OrderID:" + orderId);

                    // Send order details to the server for saving
                    var saveOrderXhttp = new XMLHttpRequest();
                    // saveOrderXhttp.onreadystatechange = () => {
                    //     if (saveOrderXhttp.readyState == 4 && saveOrderXhttp.status == 200) {
                    //         console.log(responseText);
                    //         var response = JSON.parse(saveOrderXhttp.responseText);
                    //         if (response.success) {
                    //             console.log("success");
                    //             window.location.href = "<?= URLROOT ?>/Buyercontrollers/orderConfirm";
                    //         } else {
                    //             console.log("fail");
                    //             alert('Failed to save order details.');
                    //         }
                    //     }
                    // };

                    saveOrderXhttp.onreadystatechange = () => {
    if (saveOrderXhttp.readyState == 4 && saveOrderXhttp.status == 200) {
        try {
            var response = JSON.parse(saveOrderXhttp.responseText); // Use saveOrderXhttp.responseText
            if (response.success) {
                window.location.href = "<?= URLROOT ?>/Buyercontrollers/orderConfirm";
            } else {
                alert('Failed to save order details: ' + (response.message || 'Unknown error'));
            }
        } catch (e) {
            console.error("Invalid JSON response:", saveOrderXhttp.responseText);
            alert("An error occurred while processing the server's response.");
        }
    }
};

                    saveOrderXhttp.open("POST", "<?= URLROOT ?>/Buyercontrollers/saveOrderSuccess", true);
                    saveOrderXhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    saveOrderXhttp.send(`order_id=${encodeURIComponent(orderId)}`);
                };

                // Payment dismissed callback
                payhere.onDismissed = function onDismissed() {
                    console.log("Payment dismissed");
                };

                // Payment error callback
                payhere.onError = function onError(error) {
                    console.log("Error:" + error);
                };

                // Pass payment details to PayHere
                var payment = {
                    "sandbox": true, // Set to false for production
                    "merchant_id": obj["merchant_id"],
                    "return_url": "<?= URLROOT ?>/Buyercontrollers/paymentDetails",
                    "cancel_url": "<?= URLROOT ?>/Buyercontrollers/paymentDetails",
                    "notify_url": "<?= URLROOT ?>/Buyercontrollers/saveOrderSuccess",
                    "order_id": obj["order_id"],
                    "items": obj["item"],
                    "amount": obj["amount"],
                    "currency": obj["currency"],
                    "hash": obj["hash"],
                    "first_name": obj["first_name"],
                    "last_name": obj["last_name"],
                    "email": obj["email"],
                    "phone": obj["phone"],
                    "address": obj["address"],
                    "city": obj["city"],
                    "country": "Sri Lanka",
                    "delivery_address": obj["address"],
                    "delivery_city": obj["city"],
                    "delivery_country": "Sri Lanka"
                };

                payhere.startPayment(payment);
            } catch (e) {
                console.error("Invalid JSON response:", xhttp.responseText);
                alert("An error occurred while processing your request.");
            }
        }
    };

    // Send request to fetch payment details
    xhttp.open("GET", "<?= URLROOT ?>/Buyercontrollers/payhereProcess", true);
    xhttp.send();
}

</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>


