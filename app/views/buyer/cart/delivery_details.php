<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/delivery_details.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="delivery-options">
    <h2>Delivery options</h2>
    <form method="POST" action="<?= URLROOT ?>/buyercontrollers/deliveryOptions" onsubmit="return redirectToPayment()">
        <div class="option" id="homeDelivery" onclick="selectOption('homeDelivery')">
            <input type="radio" name="delivery" value="Home Delivery" id="homeDeliveryRadio" hidden>
            <label for="homeDeliveryRadio">
                <span class="option-title">Home delivery</span>
                <span class="option-icon">&#10004;</span> <!-- Check mark -->
            </label>
        </div>

        <div class="option" id="inStorePickup" onclick="selectOption('inStorePickup')">
            <input type="radio" name="delivery" value="In-Store Pickup" id="inStorePickupRadio" hidden>
            <label for="inStorePickupRadio">
                <span class="option-title">In-store pickup</span>
                <span class="option-subtitle">Pick from farmer location</span>
                <span class="option-icon">&#10004;</span> <!-- Check mark -->
            </label>
        </div>

        <button type="submit">Confirm Selection</button>
    </form>
</div>

<script>
    // Get cart ID and product ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const cartId = urlParams.get('cart_id');
    const productId = urlParams.get('product_id'); // 👈 NEW

    // Function to select delivery options
    function selectOption(optionId) {
        document.querySelectorAll('.option').forEach(opt => {
            opt.classList.remove('selected');
        });
        document.getElementById(optionId).classList.add('selected');
        document.getElementById(optionId + 'Radio').checked = true;
    }

    // Redirect user based on selected delivery option and cartId + productId
    function redirectToPayment() {
        const selectedOption = document.querySelector('input[name="delivery"]:checked');

        if (!selectedOption) {
            alert("Please select a delivery option.");
            return false;
        }

        if (!cartId || !productId) {
            alert("Cart ID or Product ID not found.");
            return false;
        }

        if (selectedOption.value === "Home Delivery") {
            window.location.href = "<?= URLROOT ?>/orderControllers/address/" + cartId  + "/" + productId;
            return false;
        } else if (selectedOption.value === "In-Store Pickup") {
            window.location.href = "<?= URLROOT ?>/buyercontrollers/paymentDetails?cart_id=" + cartId + "/" + productId;
            return false;
        }

        return true;
    }
</script>


<?php require APPROOT . '/views/inc/footer.php'; ?>
