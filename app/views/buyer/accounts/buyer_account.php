<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/account.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

    <div class="account-container">
        <div class="account-content">
            <div class="profile-info">
                <img src="<?= URLROOT ?>/public/images/farmer_propic.jpg" alt="Profile Picture" class="profile-pic">
                <h2>Name</h2>
                <p><strong>Phone Number: </p>
                <p><strong>Email: </p>
                <!-- <p><strong>Address: </p>
                <p><strong>Delivery Areas: </p> -->
                <a href="<?php echo URLROOT?>Buyercontrollers/editprofile" class="edit-btn">Edit Profile</a>
            </div>
        </div>
    </div>

  <?php require APPROOT . '/views/inc/footer.php'; ?>