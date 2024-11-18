<?php require APPROOT . '/views/inc/header.php'; ?>
<div></div>
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
        <div class="upload-container">
          <input type="file" id="image" name="image" onchange="previewImage(event)">
        </div>
        <div id="image-preview" style="margin-top: 15px;">
        <img id="output" src="" alt="Image Preview" style="max-width: 200px; display: none;">
        </div>
        </div>

        <div class="form-group">
        <label for="password">Password</label>
            <input type="password" name="current_password" id="current_password" placeholder="current password">
            <input type="password" name="new_password" id="new_password" placeholder="new password">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm new password">
        </div>

        <div class="form-buttons">
            <a href="<?= URLROOT ?>/dpaccounts/account/<?= $data['id'] ?>" class="cancel-btn">cancel</a>
            <button type="submit" class="save-btn">save changes</button>
            
            
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


