<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/d_upload.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="delivery-container">
    <h2>Upload Delivery Images</h2>

    <!-- Pickup Image Form -->
    <form action="<?= URLROOT; ?>/dpersons/deliveryUploadPickup" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="pickup_image">Pickup Image</label>
            <div class="upload-container">
                <input type="file" name="pickup_image" id="pickup_image" onchange="previewPickupImage(event)" required>
            </div>
            <div id="image-preview" style="margin: 20px;">
                <img id="output" src="<?= isset($_SESSION['pickup_image']) ? URLROOT . '/' . htmlspecialchars($_SESSION['pickup_image']) : ''; ?>" style="max-width: 400px">
            </div>
            <div class="form-group">
                <input type="submit" value="Upload Pickup Image" class="btn-submit">
            </div>
        </div>
    </form>

    <form action="<?= URLROOT; ?>/dpersons/deliveryUploadDropoff" method="POST" enctype="multipart/form-data">
        <!-- Dropoff Image -->
    <div class="form-group">
            <label for="dropoff_image">Dropoff Image</label>
            <div class="upload-container">
                <input type="file" name="dropoff_image" id="dropoff_image" onchange="previewDropoffImage(event)" required>
            </div>

            <div id="dropoff-preview" style="margin: 20px;">
            <img id="dropoff_output" src="<?= isset($_SESSION['dropoff_image']) ? URLROOT . '/' . htmlspecialchars($_SESSION['dropoff_image']) 
                    : ''; ?>" style="max-width: 400px">
            </div>

        <!-- Submit Button -->
        <div class="form-group">
            <input type="submit" value="End the Delivery" class="btn-submit">
        </div>
    </div>
    </form>
</div>

<!-- Order Summary Modal -->
<div id="orderSummaryModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Order Summary</h2>
        <p><strong>Order ID:</strong> <span id="summaryOrderId"><?= $_SESSION['summary']['order_id'] ?? 'N/A' ?></span></p>
        <p><strong>Delivery Earnings:</strong> <span id="summaryEarnings">$<?= $_SESSION['summary']['earnings'] ?? '0.00' ?></span></p>
        <p><strong>Total Earnings:</strong> <span id="totearnings">$<?= $_SESSION['summary']['total_earnings'] ?? '0.00' ?></span></p>
        <button class="btn-submit" id="confirmEndDelivery">Confirm</button>
    </div>
</div>

<!-- Modal Styles -->
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    border-radius: 5px;
    width: 50%;
    text-align: center;
}

.close {
    float: right;
    font-size: 28px;
    cursor: pointer;
}
</style>

<!-- JavaScript to Handle Image Preview and Popup -->
<script>
function previewPickupImage(event) {
    const imagePreview = document.getElementById('output');
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src);
}

function previewDropoffImage(event) {
    const dropoffImagePreview = document.getElementById('dropoff_output');
    dropoffImagePreview.src = URL.createObjectURL(event.target.files[0]);
    dropoffImagePreview.style.display = 'block';
    dropoffImagePreview.onload = () => URL.revokeObjectURL(dropoffImagePreview.src);
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
