<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/confirmation.css">



  <div class="warning-box">
    <div class="icon-container">
      <i class="fas fa-exclamation-triangle"></i>
    </div>
    <div class="warning-text">
      <h2>Warning!</h2>
      <p>"Warning: Deleting your account will remove your administrative privileges and 
        related data permanently. Ensure another administrator is available to manage the system."</p>
    </div>
    <div class="button-container">
      <a href="<?= URLROOT ?>/admins/deactivate/" class="delete-button">Delete Account</button>
      <a href="<?= URLROOT ?>/admins/account/" class="cancel-button">Cancel</a>
    </div>
  </div>

  
  <?php require APPROOT . '/views/inc/footer.php'; ?>