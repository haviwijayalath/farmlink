<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/editprofile.css">

<?php require APPROOT . '/views/inc/sidebars/consultant.php'; ?>

<div class="edit-profile-container">
  <h2>Edit Your Profile</h2>
  <form method="POST" action="">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="<? /*= $user['name'] */?>" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?/*= $user['email']*/ ?>" required>
    </div>
    <div class="form-group">
      <label for="specialization">Specialization</label>
      <input type="text" name="specialization" id="specialization" 1  value="<?/*= $user['specialization']*/ ?>" required>
    </div>
    <div class="form-group">
      <label for="experience">Experience (Years)</label>
      <input type="number" name="experience" id="experience" value="<?/*= $user['experience']*/ ?>" required>
    </div>
    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" name="address" id="address" value="<?/*= $user['address'] */?>" required>
    </div>

    <div class="form-group">
      <label for="image">Upload Image (Optional)</label>
      <div class="upload-container">
        <input type="file" id="image" name="image" placeholder="upload profile image">
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
<br><br>

<?php require APPROOT . '/views/inc/footer.php'; ?>