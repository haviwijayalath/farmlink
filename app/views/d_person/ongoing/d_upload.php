<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/d_upload.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="delivery-container">
    <h2>Upload Delivery Images</h2>
    <form action="<?= URLROOT; ?>/delivery/upload" method="POST" enctype="multipart/form-data">
        <!-- Pickup Image -->
        <div class="form-group">
            <label for="pickup_image">Pickup Image</label>
            <input type="file" name="pickup_image" id="pickup_image" required>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <input type="submit" value="Upload Images" class="btn-submit">
        </div>
    </form>


    <form action="<?= URLROOT; ?>/delivery/upload" method="POST" enctype="multipart/form-data">
        <!-- Dropoff Image -->
    <div class="form-group">
            <label for="dropoff_image">Dropoff Image</label>
            <input type="file" name="dropoff_image" id="dropoff_image" required>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <input type="submit" value="Upload Images" class="btn-submit">
        </div>
    </form>
    

    <!-- Display uploaded images if they exist -->
    <?php if (isset($data['pickup_image']) && isset($data['dropoff_image'])) : ?>
        <div class="uploaded-images">
            <h3>Uploaded Images</h3>

            <div class="image-display">
                <h4>Pickup Image: <?= htmlspecialchars(basename($data['pickup_image'])) ?></h4>
                <img src="<?= htmlspecialchars($data['pickup_image']) ?>" alt="Pickup Image" class="uploaded-img">
            </div>

            <div class="image-display">
                <h4>Dropoff Image: <?= htmlspecialchars(basename($data['dropoff_image'])) ?></h4>
                <img src="<?= htmlspecialchars($data['dropoff_image']) ?>" alt="Dropoff Image" class="uploaded-img">
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
