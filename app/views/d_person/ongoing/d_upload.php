<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/d_upload.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="delivery-container">
    <h2>Upload Delivery Images</h2>
    <form action="<?= URLROOT; ?>/dpersons/deliveryUploadPickup" method="POST" enctype="multipart/form-data">

        <!-- Pickup Image -->
        <div class="form-group">
            <label for="pickup_image">Pickup Image</label>
            <div class="upload-container">
                <input type="file" name="pickup_image" id="pickup_image" onchange="previewPickupImage(event)" required>
            </div>
            <div id="image-preview" style="margin: 20px;">
            <img id="output" src="<?= isset($_SESSION['pickup_image']) ? URLROOT . '/' . htmlspecialchars($_SESSION['pickup_image']) 
                    : ''; ?>" style="max-width: 400px">
            </div>

        <!-- Submit Button -->
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
    

    
<script>
function previewPickupImage(event) {
    const imagePreview = document.getElementById('output');
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
}

function previewDropoffImage(event) {
    const dropoffImagePreview = document.getElementById('dropoff_output');
    dropoffImagePreview.src = URL.createObjectURL(event.target.files[0]);
    dropoffImagePreview.style.display = 'block';
    dropoffImagePreview.onload = () => URL.revokeObjectURL(dropoffImagePreview.src); // Free memory
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>