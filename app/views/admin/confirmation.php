<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main class="confirmation-page">
  <div class="warning-box">
    <div class="icon-container">
      <i class="fas fa-exclamation-triangle"></i>
    </div>
    <div class="warning-text">
      <h2>Warning!</h2>
      <p>
        Deleting your account will remove your administrative privileges and all related data permanently.<br>
        Ensure another administrator exists before you proceed.
      </p>
    </div>
    <div class="button-container">
      <form action="<?= URLROOT ?>/admins/deactivateConfirmation" method="POST" style="display:inline;">
        <button type="submit" class="delete-button">Delete Account</button>
      </form>
      <a href="<?= URLROOT ?>/admins/account" class="cancel-button">Cancel</a>
    </div>
  </div>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
