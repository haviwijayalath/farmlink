<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/d_upload.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="delivery-container">
    <h2>Upload Delivery Images</h2>

   <!-- Pickup Image Form + Confirm -->
    <div class="form-group">
        <label for="pickup_image">Pickup Image</label>

        <div class="upload-container">
            <!-- ✅ FORM STARTS HERE -->
            <form action="<?= URLROOT; ?>/dpersons/deliveryUploadPickup" method="POST" enctype="multipart/form-data">
                <input type="file" name="pickup_image" id="pickup_image" onchange="previewPickupImage(event)" required>

                <div id="image-preview">
                    <img id="output" 
                        src="<?= isset($_SESSION['pickup_image']) ? URLROOT . '/' . htmlspecialchars($_SESSION['pickup_image']) : ''; ?>">
                </div>

                <input type="submit" class="btn-upload" value="Upload Pickup Image">
            </form>
            <!-- ✅ FORM ENDS HERE -->
        </div>

        <div class="button-row">
            <form action="<?= URLROOT; ?>/dpersons/confirmpickup" method="POST">
                <input type="submit" class="btn-confirm" value="Confirm pickup">
            </form>
        </div>
    </div>



    <!-- Drop-off Image Form + Confirm -->
<div class="form-group">
    <label for="dropoff_image">Dropoff Image</label>

    <div class="upload-container">
        <!-- ✅ FORM STARTS HERE -->
        <form action="<?= URLROOT; ?>/dpersons/deliveryUploadDropoff" method="POST" enctype="multipart/form-data">
            <input type="file" name="dropoff_image" id="dropoff_image" onchange="previewDropoffImage(event)" required>

            <div id="dropoff-preview">
                <img id="dropoff_output" 
                     src="<?= isset($_SESSION['dropoff_image']) ? URLROOT . '/' . htmlspecialchars($_SESSION['dropoff_image']) : ''; ?>">
            </div>

            <input type="submit" class="btn-upload" value="Upload Drop-off Image">
        </form>
        <!-- ✅ FORM ENDS HERE -->
    </div>

    <div class="button-row">
        <form action="<?= URLROOT; ?>/dpersons/endDelivery" method="POST">
            <input type="submit" class="btn-confirm" value="End the delivery">
        </form>
    </div>
</div>

</div>

<!-- JavaScript to Handle Image Preview -->
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
