<?php require APPROOT . '/views/inc/header.php'; ?>
<div></div>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/editaccount.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="edit-profile-container">
    <h2>Edit Your Profile</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<? /*= $user['name'] */?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?/*= $user['email']*/ ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" value="<?/*= $user['address'] */?>">
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" id="address" value="<?/*= $user['address'] */?>">
        </div>

        <div class="form-group">
            <label for="area">Delivery Area</label>
            <input type="text" name="area" id="area" value="<?/*= $user['address'] */?>">
        </div>

        <div class="form-group">
        <label for="image">Upload Image</label>
        <div class="upload-container">
          <input type="file" id="image" name="image" placeholder="upload profile image" onchange="previewImage(event)">
        </div>
        <div id="image-preview" style="margin-top: 15px;">
        <img id="output" src="" alt="Image Preview" style="max-width: 200px; display: none;">
        </div>
        </div>

        <div class="form-group">
        <label for="password">Password</label>
            <input type="password" name="current_password" id="current_password" placeholder="current password" required>
            <input type="password" name="new_password" id="new_password" placeholder="new password">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm new password">
        </div>

        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="window.location.href='profile.php';">Cancel</button>
            <button type="submit" class="save-btn">Save Changes</button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const imagePreview = document.getElementById('output');
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>


