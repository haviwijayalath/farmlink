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
    // Get cart ID from URL (assuming it's in the query string)
    const urlParams = new URLSearchParams(window.location.search);
    const cartId = urlParams.get('cart_id');

    // Function to select delivery options
    function selectOption(optionId) {
        // Deselect all options
        document.querySelectorAll('.option').forEach(opt => {
            opt.classList.remove('selected');
        });
        // Select the clicked option
        document.getElementById(optionId).classList.add('selected');
        // Set the radio input for the selected option
        document.getElementById(optionId + 'Radio').checked = true;
    }

    // Redirect user based on selected delivery option and cartId
    function redirectToPayment() {
        const selectedOption = document.querySelector('input[name="delivery"]:checked');

        if (!selectedOption) {
            alert("Please select a delivery option.");
            return false; // Prevent form submission if no option is selected
        }

        if (!cartId) {
            alert("Cart ID not found.");
            return false; // Prevent form submission if cartId is not available
        }

        if (selectedOption.value === "Home Delivery") {
            // Redirect to the address filling page with cart ID
            window.location.href = "<?= URLROOT ?>/orderControllers/address/" + cartId;
            return false; // Prevent form submission since we're redirecting
        } else if (selectedOption.value === "In-Store Pickup") {
            // Redirect to payment details page with cart ID
            window.location.href = "<?= URLROOT ?>/buyercontrollers/paymentDetails?cart_id=" + cartId;
            return false; // Prevent form submission since we're redirecting
        }

        return true; // In case everything is fine and form should be submitted
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
