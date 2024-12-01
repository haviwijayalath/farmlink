<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/viewprofile.css">

<div class="account-container">
  <div class="account-content">
    <div class="profile-info">
      <img src="<?= URLROOT ?>/public/uploads/consultant/profile/<?= !empty($data['image']) && file_exists(APPROOT . '/../public/uploads/consultant/profile/' . $data['image']) ? $data['image'] : 'Consultant-bro.jpg' ?>" alt="Profile Picture" class="profile-pic">
      <h3><?php echo $data['name']; ?></h3>
      <p><strong>Specialization: </strong> <?php echo $data['specialization']; ?></p>
      <p><strong>Experience: </strong> <?php echo $data['experience']; ?> years</p>
      <p><strong>Phone Number: </strong> <?php echo $data['phone']; ?></p>
      <p><strong>Email:</strong> <?php echo $data['email']; ?></p>
      <a href="<?php echo URLROOT?>/consultant/editprofile" class="edit-btn">Edit Profile</a>
    </div>
  </div>
</div>

<?php require APPROOT . '/views/consultant/inc/footer.php'; ?>