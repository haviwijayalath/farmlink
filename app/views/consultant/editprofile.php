<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/editprofile.css">

<?php require APPROOT . '/views/inc/sidebars/consultant.php'; ?>

<div class="edit-profile-container">
  <h2>Edit Your Profile</h2>
  <form action="<?= URLROOT; ?>/consultants/editprofile/<?= $data['id']; ?>" method="POST" enctype="multipart/form-data">
    <!-- Hidden field if needed for an address or any other ID -->
    <!-- <input type="hidden" name="address_id" value="<?= htmlspecialchars($data['address_id'] ?? '') ?>"> -->

    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="<?= htmlspecialchars($data['name']); ?>" required>
    </div>
    
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($data['email']); ?>" required>
    </div>
    
    <div class="form-group">
      <label for="specialization">Specialization</label>
      <input type="text" name="specialization" id="specialization" value="<?= htmlspecialchars($data['specialization']); ?>" required>
    </div>
    
    <div class="form-group">
      <label for="experience">Experience (Years)</label>
      <input type="number" name="experience" id="experience" value="<?= htmlspecialchars($data['experience']); ?>" required>
    </div>
    
    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" name="address" id="address" value="<?= htmlspecialchars($data['address']); ?>" required>
    </div>
    
    <div class="form-group">
      <label for="image">Upload Image (Optional)</label>
      <div class="image-upload-section">
        <!-- Display current image -->
        <div class="current-image">
          <img id="currentImage" src="<?= URLROOT ?>/public/uploads/<?= !empty($data['image']) && file_exists(APPROOT . '/../public/uploads/' . $data['image']) ? $data['image'] : 'placeholder.png' ?>" alt="Current Profile Image" style="max-width:150px; border:1px solid #ddd; border-radius:5px;">
        </div>
        <!-- New image upload -->
        <div class="upload-container">
          <input type="file" id="image" name="image" onchange="replaceCurrentImage(event)">
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <label for="password">Password</label>
      <div class="password-container">
        <input type="password" name="current_password" id="current_password" placeholder="Current Password" required>
        <i class="toggle-password" id="toggleCurrentPassword">&#128065;</i>
      </div>
      <div class="password-container">
        <input type="password" name="new_password" id="new_password" placeholder="New Password">
        <i class="toggle-password" id="toggleNewPassword">&#128065;</i>
      </div>
      <div class="password-container">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password">
        <i class="toggle-password" id="toggleConfirmPassword">&#128065;</i>
      </div>
    </div>
    
    <div class="form-buttons">
      <a href="<?= URLROOT ?>/consultants/viewprofile" class="cancel-btn">Cancel</a>
      <button type="submit" class="save-btn">Save Changes</button>
    </div>
  </form>
</div>

<script>
function replaceCurrentImage(event) {
    const file = event.target.files[0];
    if(file) {
        const currentImage = document.getElementById('currentImage');
        const newImageURL = URL.createObjectURL(file);
        currentImage.src = newImageURL;
        currentImage.onload = () => URL.revokeObjectURL(newImageURL);
    }
}

function togglePasswordVisibility(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if(passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        icon.textContent = '👁️';
    }
}

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
