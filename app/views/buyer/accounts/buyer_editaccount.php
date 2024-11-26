<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/editaccount.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="edit-profile-container">
    <h2>Edit Your Profile</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="" required>
        </div>

        <div class="form-group">
        <label for="password">Password</label>
            <input type="password" name="current_password" id="current_password" placeholder="current password" required>
            <input type="password" name="new_password" id="new_password" placeholder="new password">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm new password">
        </div>

        <div class="form-buttons">
            <a href="<?= URLROOT ?>/Buyercontrollers/viewProfile" class="cancel-btn">Cancel</a>
            <button type="submit" class="save-btn">Save Changes</button>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>


