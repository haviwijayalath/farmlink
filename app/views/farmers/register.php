<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/farmers/register.css">

<div class="register-container">

  <div class="register-image">
    <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer Illustration">
  </div>
  
  <div class="register-form">
    <h2>Register Now</h2>

    <form action="<?php echo URLROOT; ?>/farmers/register" method="POST" enctype="multipart/form-data">

      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" <?php echo (!empty($data['name_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['name']; ?>" required>

      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['email']; ?>" required>
      <span style="color: red;"><?php echo $data['email_err']; ?></span>

      <label for="phone">Phone</label>
      <input type="text" id="phone" name="phone_number" placeholder="Enter your phone number" <?php echo (!empty($data['phone_number_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['phone_number']; ?>" required>

      <label for="address">Address:</label>
      <input type="text" id="addr_no" name="addr_no" placeholder="Home No." value="<?php echo $data['phone_number']; ?>" required>
      <input type="text" id="addr_street" name="addr_street" placeholder="Home Street" value="<?php echo $data['phone_number']; ?>" required>
      <input type="text" id="addr_City" name="addr_City" placeholder="City" value="<?php echo $data['phone_number']; ?>" required>
      
      
      <label for="image">Upload Image</label>
      <div class="upload-container">
        <input type="file" id="image" name="image" value="<?php echo $data['image']; ?>">
      </div>
      
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?> 
        value="<?php echo $data['password']; ?>">
      <span style="color: red;"><?php echo $data['password_err']; ?></span>

      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" <?php echo (!empty($data['confirm_password_err'])) ? 'is_invalid' : ''; ?> 
        value="<?php echo $data['confirm_password']; ?>">
      <span style="color: red;"><?php echo $data['confirm_password_err']; ?></span>
      
      <button type="submit" class="register-btn">Register</button>
    </form>
  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>