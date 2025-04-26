<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/changePwrd.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main class="change-password-container">
  <h2>Change Password</h2>

  <?php if (!empty($data['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
  <?php endif; ?>

  <form action="<?= URLROOT ?>/admins/changepwrd" method="POST">
    <!-- Current Password -->
    <div class="form-group">
      <label for="current_password">Current Password</label>
      <input 
        type="password" 
        id="current_password" 
        name="current_password" 
        required
      >
    </div>

    <!-- New Password -->
    <div class="form-group">
      <label for="new_password">New Password</label>
      <input 
        type="password" 
        id="new_password" 
        name="new_password" 
        required
      >
    </div>

    <!-- Confirm New Password -->
    <div class="form-group">
      <label for="confirm_password">Confirm New Password</label>
      <input 
        type="password" 
        id="confirm_password" 
        name="confirm_password" 
        required
      >
    </div>

    <div class="form-buttons">
      <a href="<?= URLROOT ?>/admins/account" class="cancel-btn">Cancel</a>
      <button type="submit" class="save-btn">Change Password</button>
    </div>
  </form>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
