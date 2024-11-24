
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/farmers/viewprofile.css">

    <div class="account-container">
        <div class="account-content">
            <div class="profile-info">
                <img src="<?= URLROOT ?>/public/uploads/<?= !empty($data['image']) && file_exists(APPROOT . '/../public/uploads/' . $data['image']) ? $data['image'] : 'Farmer-bro.jpg' ?>" alt="Profile Picture" class="profile-pic">
                <h3><?php echo $data['name']; ?></h3>
                <p><strong>Phone Number: </strong> <?php echo $data['phone']; ?></p>
                <p><strong>Email:</strong> <?php echo $data['email']; ?></p>
                <a href="<?php echo URLROOT?>/farmers/editprofile" class="edit-btn">Edit Profile</a>
            </div>
        </div>
    </div>

  <?php require APPROOT . '/views/farmers/inc/footer.php'; ?>