<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/account.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

    <div class="account-container">
        <div class="account-content">
            <div class="profile-info">
            <img src="<?= URLROOT ?>/public/images/<?= !empty($data['image']) ? $data['image'] : 'default.jpg' ?>" alt="Profile Picture" class="profile-pic">
            <h2><?= htmlspecialchars($data['name']) ?></h2>
            <p><strong>Phone Number: </strong><?= htmlspecialchars($data['phone']) ?></p>
            <p><strong>Email: </strong><?= htmlspecialchars($data['email']) ?></p>
            <p><strong>Address: </strong><?= htmlspecialchars($data['address']) ?></p>
            <p><strong>Delivery Areas: </strong><?= htmlspecialchars($data['area']) ?></p>
                <a href="<?php echo URLROOT?>/accountcontrollers/editprofile" class="edit-btn">Edit Profile</a>
            </div>
        </div>
    </div>

  <?php require APPROOT . '/views/inc/footer.php'; ?>