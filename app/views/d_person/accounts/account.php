<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/account.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="account-container">
    <div class="account-content">
        <div class="profile-info">
            <!-- Ensure correct image path -->
            <img src="<?= URLROOT ?>/public/uploads/<?= !empty($data['image']) && 
            file_exists(APPROOT . '/../public/uploads/' . $data['image']) ? $data['image'] : 'Farmer-bro.jpg' ?>" alt="Profile Picture" class="profile-pic">


            <!-- Display user data with htmlspecialchars for safety -->
            <h2><?= htmlspecialchars($data['name']) ?></h2>
            <p><strong style="margin-right: 10px;">Phone Number: </strong><?= htmlspecialchars($data['phone']) ?></p>
            <p><strong style="margin-right: 10px;">Email: </strong><?= htmlspecialchars($data['email']) ?></p>
            <p><strong style="margin-right: 10px;">Address: </strong><?= htmlspecialchars($data['address']) ?></p>
            <p><strong style="margin-right: 10px;">Delivery Areas: </strong><?= htmlspecialchars($data['area']) ?></p>

            <!-- Edit profile link with user ID -->
            <a href="<?= URLROOT ?>/dpaccounts/editprofile/<?= $data['id'] ?>" class="edit-btn">Edit Profile</a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
