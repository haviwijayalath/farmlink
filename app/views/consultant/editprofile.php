<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/editprofile.css">

<?php require APPROOT . '/views/inc/sidebars/consultant.php'; ?>

<div class="edit-profile-container">
  <h2>Edit Your Profile</h2>
  <form action="<?= URLROOT; ?>/consultants/editprofile/<?= $data['id']; ?>" method="POST" enctype="multipart/form-data">

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
        <div class="current-image">
          <img id="currentImage"
               src="<?= URLROOT ?>/public/uploads/consultants/<?= 
                     !empty($data['image']) && file_exists(APPROOT . '/../public/uploads/consultants/' . $data['image'])
                       ? htmlspecialchars($data['image']) : 'placeholder.png' 
                   ?>"
               alt="Current Profile Image">
        </div>
        <div class="upload-container">
          <input type="file" id="image" name="image" accept="image/*" onchange="replaceCurrentImage(event)">
        </div>
      </div>
      <span class="error"><?= $data['image_err'] ?? '' ?></span>
    </div>

    <!-- === NEW: Verification Document === -->
    <div class="form-group">
      <label for="verification_doc">Verification Document</label>
      <div class="verification-section">
        <?php if (!empty($data['verification_doc'])): ?>
          <div class="current-doc">
            <a href="<?= URLROOT ?>/public/uploads/consultants/verifications/<?= htmlspecialchars($data['verification_doc']) ?>"
               target="_blank">
              View current document
            </a>
          </div>
        <?php endif; ?>
        <div class="upload-container">
          <input type="file"
                 id="verification_doc"
                 name="verification_doc"
                 accept=".pdf,.jpg,.jpeg,.png">
        </div>
      </div>
      <span class="error"><?= $data['verification_doc_err'] ?? '' ?></span>
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
      <span class="error"><?= $data['password_err'] ?? '' ?></span>
    </div>

    <div class="form-buttons">
      <button type="submit" class="save-btn">Save Changes</button>
      <a href="<?= URLROOT ?>/consultants/viewprofile" class="cancel-btn">Cancel</a>
    </div>
  </form>
</div>

<script>
function replaceCurrentImage(event) {
  const file = event.target.files[0];
  if (file) {
    const img = document.getElementById('currentImage');
    img.src = URL.createObjectURL(file);
    img.onload = () => URL.revokeObjectURL(img.src);
  }
}

function togglePasswordVisibility(inputId, iconId) {
  const fld = document.getElementById(inputId);
  const icon = document.getElementById(iconId);
  if (fld.type === 'password') {
    fld.type = 'text'; icon.textContent = '🙈';
  } else {
    fld.type = 'password'; icon.textContent = '👁️';
  }
}

['Current','New','Confirm'].forEach(kind => {
  document
    .getElementById(`toggle${kind}Password`)
    .addEventListener('click', () => togglePasswordVisibility(
      kind === 'Current' ? 'current_password' :
      kind === 'New'     ? 'new_password' : 'confirm_password',
      `toggle${kind}Password`
    ));
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
