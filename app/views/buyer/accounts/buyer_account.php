<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/account.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

    <div class="account-container">
        <div class="account-content">
            <div class="profile-info">

                <div class="profile-image">
                    <img src="<?= URLROOT ?>/public/images/farmer_propic.jpg" alt="Profile Picture" class="profile-pic">
                </div>

                <div class="user-details">
                    <div class="detail-item">
                        <strong>Name: </strong>
                    </div>
                    <div class="detail-item">
                        <strong>Phone Number: </strong>
                    </div>
                    <div class="detail-item">
                        <strong>Email: </strong>
                    </div>
                </div>
            </div>
            <div class="action-buttons">
                <a href="<?php echo URLROOT?>/Buyercontrollers/editprofile" class="btn edit-btn">Edit Profile</a>
                <a href="" class="btn delete-btn" onclick=";">Delete Account</a>
            </div>
        </div>
    </div>

  <?php require APPROOT . '/views/inc/footer.php'; ?>