<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/account.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main class="acc-main-content">
  <div class="acc-profile-details">
    <h2>Profile Details</h2>
    <div class="acc-profile-image">
      <img 
        src="<?= URLROOT ?>/public/uploads/<?= !empty($data['admin']->image) ? htmlspecialchars($data['admin']->image) : 'adminProPic.png' ?>" 
        alt="Profile Picture" 
        class="profile-pic"
      >
      <div class="acc-image-actions">
        <a href="<?= URLROOT ?>/admins/editAccount" class="edit-profile-link">
          <button><i class="fas fa-edit"></i></button>
        </a>
        <form action="<?= URLROOT ?>/admins/deactivateConfirmation" method="post" style="display:inline;">
          <button type="submit"><i class="fas fa-trash"></i></button>
        </form>
      </div>
    </div>
    <div class="acc-profile-info">
      <p><strong>Name:</strong> <?= htmlspecialchars($data['admin']->name) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($data['admin']->email) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($data['admin']->phone) ?></p>
    </div>
  </div>

  <div class="acc-actions">
    <div class="acc-action-card">
      <h3>Change Password</h3>
      <p>Update your account password.</p>
      <a href="<?= URLROOT ?>/admins/changepwrd" class="changepw">Change Password</a>
    </div>
    <div class="acc-action-card">
      <h3>Close Account</h3>
      <p>Permanently deactivate your admin account.</p>
      <form action="<?= URLROOT ?>/admins/deactivateConfirmation" method="post">
        <button type="submit" class="changepw">Close Account</button>
      </form>
    </div>
  </div>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
