<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/delivery_details.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>


<div class="delivery-options">
    <h2>Delivery options</h2>
    <form method="POST" action="" onsubmit="return redirectToPayment()">
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

        
        <button type="submit" onclick="redirectToPayment()">Confirm Selection</button>
    </form>
</div>

<script>
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

    function redirectToPayment() {
        const selectedOption = document.querySelector('input[name="delivery"]:checked');
        
        // Check if "In-Store Pickup" is selected
        if (selectedOption && selectedOption.value === "In-Store Pickup") {
            console.log("correct");
            // Redirect to payment.php if "In-Store Pickup" is selected
            window.location.href = "<?= URLROOT ?>/buyercontrollers/paymentDetails";
            return false; // Prevent form submission to avoid refresh
        }
        // Submit the form for other options (if needed)
        return true;
    }

</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
