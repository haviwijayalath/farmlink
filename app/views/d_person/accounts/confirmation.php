<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/confirmation.css">



<div class="delete-container">
    <div class="delete-card">
        <h2>Delete profile?</h2>
        <p>Deleting your profile will remove all personal data.</p>


        <img src="<?= URLROOT ?>/public/uploads/<?= !empty( $_SESSION['user_image']) && 
            file_exists(APPROOT . '/../public/uploads/' .  $_SESSION['user_image']) ?  $_SESSION['user_image'] : 'Farmer-bro.jpg' ?>" alt="Profile Picture" class="profile-pic">
        
        <div class="action-buttons">
            <a href="<?= URLROOT ?>/dpaccounts/deactivate" class="delete-btn" onsubmit="return confirmDelete();">Delete</a>
            <a href="<?= URLROOT ?>/dpaccounts/account" class="cancel-btn">Cancel</a>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete your account? This action cannot be undone.");
    }
</script>



