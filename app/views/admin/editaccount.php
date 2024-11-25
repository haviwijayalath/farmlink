<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/editaccount.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="edit-profile-container">
    <h2>Edit Your Profile</h2>
    <form action="" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="address_id">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your name">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email">
        </div>

        <div class="form-group">
            <label for="image">Upload Image</label>
            <div class="image-upload-section">
                <div class="current-image">
                    <img src="<?= URLROOT ?>/public/images/adminProPic.png" alt="Profile Picture" class="profile-pic">
                </div>
                <div class="upload-container">
                    <input type="file" id="image" name="image" onchange="replaceCurrentImage(event)">
                </div>
            </div>
        </div>

        <div class="form-buttons">
            <a href="<?= URLROOT ?>/admins/account/<?= $data['id'] ?>" class="cancel-btn">Cancel</a>
            <button type="submit" class="save-btn">Save</button>
        </div>
    </form>
</div>

<script>
function replaceCurrentImage(event) {
    const file = event.target.files[0]; // Get the uploaded file
    if (file) {
        const currentImageDiv = document.querySelector('.current-image img'); // Select the current image
        const newImageURL = URL.createObjectURL(file); // Create a URL for the uploaded image

        currentImageDiv.src = newImageURL; // Replace the current image source with the new one

        // Free up memory after the image has loaded
        currentImageDiv.onload = () => URL.revokeObjectURL(newImageURL);
    }
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
