<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/register.css">

<div class="register-container">

  <div class="register-image">
    <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer Illustration">
  </div>
  
  <div class="register-form">
    <h2>Register Now</h2>

    <form action="<?php echo URLROOT; ?>/consultants/register" method="POST" enctype="multipart/form-data">

      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" <?php echo (!empty($data['name_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['name']; ?>" required>

      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['email']; ?>" required>
      <span style="color: red;"><?php echo $data['email_err']; ?></span>

      <label for="specialization">Specialization</label>
      <input type="text" id="specialization" name="specialization" placeholder="Enter your area of expertise" <?php echo (!empty($data['specialization_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['specialization']; ?>" required>

      <label for="experience">Experience (Years)</label>
      <input type="number" id="experience" name="experience" placeholder="Enter your experience" <?php echo (!empty($data['experience_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['experience']; ?>" required>

      <label for="phone">Phone</label>
      <input type="text" id="phone" name="phone_number" placeholder="Enter your phone number" <?php echo (!empty($data['phone_number_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['phone_number']; ?>" required>

      <label for="address">Address:</label>
      <input type="text" id="addr_no" name="addr_no" placeholder="Home No." value="<?php echo $data['addr_no']; ?>" required>
      <input type="text" id="addr_street" name="addr_street" placeholder="Home Street" value="<?php echo $data['addr_street']; ?>" required>
      <input type="text" id="addr_city" name="addr_city" placeholder="City" value="<?php echo $data['addr_city']; ?>" required>
      
      <label for="image">Upload Image (Optional)</label>
      <div class="upload-container">
        <input type="file" id="image" name="image" value="<?php echo $data['image']; ?>" onchange="previewImage(event)">
      </div>
      <div id="image-preview" style="margin-top: 15px;">
        <img id="output" src="" alt="Image Preview" style="max-width: 200px; display: none;">
        
        <span style="color: red;"><?php echo $data['image_err']; ?></span>
      </div>

      <label for="verification_doc">Verification Document <span style="color:red;">*</span></label>
      <input type="file" id="verification_doc" name="verification_doc" accept=".pdf,image/*" required>
      <span style="color: red;"><?= $data['verification_doc_err'] ?? ''; ?></span>
      
      <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Enter your password" 
                      <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?> 
                      value="<?php echo $data['password']; ?>" required>
                <i class="fa fa-eye-slash toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
            <span class="error"><?php echo $data['password_err']; ?></span>

            <label for="confirm_password">Confirm Password</label>
            <div class="password-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" 
                      <?php echo (!empty($data['confirm_password_err'])) ? 'is_invalid' : ''; ?> 
                      value="<?php echo $data['confirm_password']; ?>" required>
                <i class="fa fa-eye-slash toggle-password" id="toggleConfirmPassword" onclick="toggleConfirmPasswordVisibility()"></i>
            </div>
            <span class="error"><?php echo $data['confirm_password_err']; ?></span>

      
      <button type="submit" class="register-btn">Register</button>
    </form>
  </div>
</div>

<script>
  function previewImage(event) {
    const imagePreview = document.getElementById('output');
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
  }

  function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePassword');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text'; // Show password
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye'); // Change icon to eye-open
        } else {
            passwordInput.type = 'password'; // Hide password
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash'); // Change icon to eye-slash
        }
    }

    function toggleConfirmPasswordVisibility() {
        const confirmPasswordInput = document.getElementById('confirm_password');
        const toggleIcon = document.getElementById('toggleConfirmPassword');
        
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text'; // Show password
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye'); // Change icon to eye-open
        } else {
            confirmPasswordInput.type = 'password'; // Hide password
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash'); // Change icon to eye-slash
        }
    }



</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>