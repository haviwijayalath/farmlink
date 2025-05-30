<?php require APPROOT . '/views/farmers/inc/header.php'; ?>

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
      <input type="text" id="addr_no" name="addr_no" placeholder="Home No." value="<?php echo $data['addr_no']; ?>" required>
      <input type="text" id="addr_street" name="addr_street" placeholder="Home Street" value="<?php echo $data['addr_street']; ?>" required>
      <input type="text" id="addr_city" name="addr_city" placeholder="City" value="<?php echo $data['addr_city']; ?>" required>
      
      <label for="image">Profile Picture</label>
      <div class="upload-container">
        <input type="file" id="image" name="image" value="<?php echo $data['image']; ?>" onchange="previewImage(event, 'output')" required>
      </div>
      <div id="image-preview" style="margin-top: 15px;">
        <img id="output" src="" alt="Image Preview" style="max-width: 200px; display: none;">
        <span style="color: red;"><?php echo $data['image_err']; ?></span>
      </div>
      
      <!-- ID Card Front Image Upload -->
      <label for="id_card_front">ID Card (Front)</label>
      <div class="upload-container">
        <input type="file" id="id_card_front" name="id_card_front" onchange="previewImage(event, 'id-front-preview')" required>
      </div>
      <div style="margin-top: 15px;">
        <img id="id-front-preview" src="" alt="ID Front Preview" style="max-width: 200px; display: none;">
        <span style="color: red;"><?php echo $data['id_card_front_err']; ?></span>
      </div>
      
      <!-- ID Card Back Image Upload -->
      <label for="id_card_back">ID Card (Back)</label>
      <div class="upload-container">
        <input type="file" id="id_card_back" name="id_card_back" onchange="previewImage(event, 'id-back-preview')" required>
      </div>
      <div style="margin-top: 15px;">
        <img id="id-back-preview" src="" alt="ID Back Preview" style="max-width: 200px; display: none;">
        <span style="color: red;"><?php echo $data['id_card_back_err']; ?></span>
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

<script>
  function previewImage(event, previewId) {
    const imagePreview = document.getElementById(previewId);
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
  }
</script>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>