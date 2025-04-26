<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>
<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/account.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

    <div class="account-container" style="margin-top: 170px; margin-left: 250px;">
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
                        <strong>Phone:</strong><?= $data['phone_num'] ?>
                    </div>
                    <div class="detail-item">
                        <strong>Email:</strong><?= $data['email'] ?>
                    </div>
                </div>
            </div>
            <div class="action-buttons">
                <a href="<?php echo URLROOT?>/Buyercontrollers/editprofile" class="btn edit-btn">Edit Profile</a>

                <!-- trigger the confirmation popup -->
                <a href="javascript:void(0)" class="btn delete-btn" onclick="showDeactivatePopup();">Delete Account</a>
            </div>
        </div>
    </div> 

    <!-- confirmation popup -->
     <div id="deactivate-popup" class="popup-container" style="display: none;">
        <div class="popup-content">
            <h2>Are you sure you want to deactivate your account?</h2>
            <p>This action cannot be undone.</p>

            <div class="button-container">
                <a href="<?= URLROOT ?>/Buyercontrollers/deactivate" id="confirm-deactivate" class="remove-button" >Yes, Deactivate</a>
                <button onclick="closeDeactivatePopup()" class="cancel-button">Cancel</button>
            </div>
        </div>
     </div>

    <script>
         // Show the confirmation popup
        function showDeactivatePopup() {
            document.getElementById('deactivate-popup').style.display = 'flex';
        }

        // Close the confirmation popup
        function closeDeactivatePopup() {
            document.getElementById('deactivate-popup').style.display = 'none';
        }
    </script>

  <?php require APPROOT . '/views/inc/footer.php'; ?>