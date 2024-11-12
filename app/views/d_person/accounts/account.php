<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/account.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="account-container">
    <div class="account-content">
        <div class="profile-info">
            <!-- Ensure correct image path -->
            <img src="<?= URLROOT ?>/public/uploads/<?= !empty($data['image']) && file_exists(APPROOT . '/public/uploads/' . $data['image']) ? $data['image'] : 'default.jpg' ?>" alt="Profile Picture" class="profile-pic">

             <!-- Debug the image path -->
             <?php
                echo "Image path: " . URLROOT . "/public/uploads/" . $data['image'];
                echo " File exists: " . (file_exists(APPROOT . '/public/uploads/' . $data['image']) ? 'Yes' : 'No');
            ?>

            <!-- Display user data with htmlspecialchars for safety -->
            <h2><?= htmlspecialchars($data['name']) ?></h2>
            <p><strong>Phone Number: </strong><?= htmlspecialchars($data['phone']) ?></p>
            <p><strong>Email: </strong><?= htmlspecialchars($data['email']) ?></p>
            <p><strong>Address: </strong><?= htmlspecialchars($data['address']) ?></p>
            <p><strong>Delivery Areas: </strong><?= htmlspecialchars($data['area']) ?></p>

            <!-- Edit profile link with user ID -->
            <a href="<?= URLROOT ?>/accountcontrollers/editprofile/<?= $data['id'] ?>" class="edit-btn">Edit Profile</a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
