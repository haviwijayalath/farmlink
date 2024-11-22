<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/editaccount.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="edit-profile-container">
    <h2>Edit Your Profile</h2>
    <form action="<?php echo URLROOT; ?>/dpaccounts/editprofile/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="address_id" value="<?= htmlspecialchars($data['address_id']) ?>">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($data['name']) ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($data['email']) ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($data['phone']) ?>">
        </div>

        <div class="form-group">
        <label for="email">Address</label>
          <input type="text" id="addr_no" name="addr_no" value="<?= htmlspecialchars($data['addr_no']) ?>">

          <input type="text" id="street" name="street" value="<?= htmlspecialchars($data['street']) ?>">

          <input type="text" id="city" name="city" value="<?= htmlspecialchars($data['city']) ?>">
        </div>

        <div class="form-group">
            <label for="area">Delivery Area</label>
            <input type="text" name="area" id="area" value="<?= htmlspecialchars($data['area'])?>">
        </div>

        <div class="form-group">
    <label for="image">Upload Image</label>
    <div class="image-upload-section">
        <!-- Display the existing image -->
        <div class="current-image">
            <img 
                id="currentImage" 
                src="<?= URLROOT ?>/public/uploads/<?= !empty($data['image']) && 
                file_exists(APPROOT . '/../public/uploads/' . $data['image']) ? $data['image'] : 'placeholder.png' ?>" 
                alt="Current Profile Image" 
                style="max-width: 150px; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        <!-- Upload new image -->
        <div class="upload-container">
            <input type="file" id="image" name="image" onchange="replaceCurrentImage(event)">
        </div>
    </div>
</div>

        <div class="form-group">
        <label for="password">Password</label>
            <div class="password-container">
                <input type="password" name="current_password" id="current_password" placeholder="current password">
                <i class="toggle-password" id="toggleCurrentPassword">&#128065;</i>
            </div>
            <div class="password-container">
                <input type="password" name="new_password" id="new_password" placeholder="new password">
                <i class="toggle-password" id="toggleNewPassword">&#128065;</i>
            </div>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm new password">
                <i class="toggle-password" id="toggleConfirmPassword">&#128065;</i>
            </div>
        </div>

        <div class="form-buttons">
            <a href="<?= URLROOT ?>/dpaccounts/account/<?= $data['id'] ?>" class="cancel-btn">Cancel</a>
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

function togglePasswordVisibility(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.textContent = '🙈'; // Change icon to a closed eye
    } else {
        passwordField.type = 'password';
        icon.textContent = '👁️'; // Change icon to an open eye
    }
}

// Attach event listeners for password toggles
document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
    togglePasswordVisibility('current_password', 'toggleCurrentPassword');
});
document.getElementById('toggleNewPassword').addEventListener('click', function() {
    togglePasswordVisibility('new_password', 'toggleNewPassword');
});
document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    togglePasswordVisibility('confirm_password', 'toggleConfirmPassword');
});

</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
