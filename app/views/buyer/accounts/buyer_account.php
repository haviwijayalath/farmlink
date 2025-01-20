<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

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
                        <strong>Name:</strong><?= $data['name'] ?>
                    </div>
                    <div class="detail-item">
                        <strong>Phone Number:</strong><?= $data['phone_num'] ?>
                    </div>
                    <div class="detail-item">
                        <strong>Email:</strong><?= $data['email'] ?>
                    </div>
                </div>
            </div>
            <div class="action-buttons">
                <a href="<?php echo URLROOT?>/Buyercontrollers/editprofile" class="btn edit-btn">Edit Profile</a>

                <a href="<?= URLROOT ?>/Buyercontrollers/deactivate" class="btn delete-btn" onclick="confirmDelete();">Delete Account</a>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(){
            return confirm("Are you sure you want to delete your account?")
        }
    </script>

  <?php require APPROOT . '/views/inc/footer.php'; ?>