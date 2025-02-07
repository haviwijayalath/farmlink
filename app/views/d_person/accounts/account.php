<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/account.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="account-container">
    <div class="account-content">
        <div class="profile-info">
            <!-- Profile Image -->
            <div class="profile-image">
                <img src="<?= URLROOT ?>/public/uploads/<?= !empty($data['image']) && 
                file_exists(APPROOT . '/../public/uploads/' . $data['image']) ? $data['image'] : 'Farmer-bro.jpg' ?>" alt="Profile Picture" class="profile-pic">
            </div>

            <!-- User Information -->
            <div class="user-details">
                <h2><?= htmlspecialchars($data['name']) ?></h2>
                <div class="detail-item">
                    <strong>Phone Number:</strong> <?= htmlspecialchars($data['phone']) ?>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong> <?= htmlspecialchars($data['email']) ?>
                </div>
                <div class="detail-item">
                    <strong>Address:</strong> <?= htmlspecialchars($data['address']) ?>
                </div>
                <div class="detail-item">
                    <strong>Delivery Areas:</strong> <?= htmlspecialchars($data['area']) ?>
                </div>
            </div>
    </div>

        <div class="action-buttons">
            <!-- Edit Profile Button -->
            <a href="<?= URLROOT ?>/dpaccounts/editprofile/<?= $data['id'] ?>" class="btn edit-btn">Edit Profile</a>
            
            <!-- Delete Account Button -->
            <a href="<?= URLROOT ?>/dpaccounts/confirmdelete/<?= $data['id'] ?>" class="btn delete-btn" onclick="return confirmDelete();">Delete Account</a>
            
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete your account? This action cannot be undone.");
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>     