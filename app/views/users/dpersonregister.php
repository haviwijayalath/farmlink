<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/register.css">

<div class="register-container">
  <div class="register-image">
    <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer Illustration">
  </div>

  <div class="register-form">
    <h2>Register Now</h2>

    <form action="<?php echo URLROOT; ?>/dpersons/register" method="POST" enctype="multipart/form-data">

      <label for="name">Full Name*</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" <?php echo (!empty($data['name_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['name']; ?>" required>
      <span class="error"><?php echo $data['name_err']; ?></span>

      <label for="email">E-mail*</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['email']; ?>" required>
      <span class="error"><?php echo $data['email_err']; ?></span>

      <label for="addr_no">Address*</label>
      <input type="text" id="addr_no" name="addr_no" placeholder="Home No." <?php echo (!empty($data['addr_no_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['addr_no']; ?>" required>
      <span class="error"><?php echo $data['addr_no_err']; ?></span>

      <input type="text" id="street" name="street" placeholder="Home Street" <?php echo (!empty($data['street_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['street']; ?>" required>
      <span class="error"><?php echo $data['street_err']; ?></span>

      <input type="text" id="city" name="city" placeholder="City" <?php echo (!empty($data['city_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['city']; ?>" required>
      <span class="error"><?php echo $data['city_err']; ?></span>

      <label for="phone">Phone*</label>
      <input type="text" id="phone" name="phone" placeholder="Enter your phone number" <?php echo (!empty($data['phone_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['phone']; ?>" required>
      <span class="error"><?php echo $data['phone_err']; ?></span>

      <label for="image">Profile Image*</label>
      <div class="upload-container">
        <input type="file" id="image" name="image" <?php echo (!empty($data['image_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['image']; ?>" onchange="previewImage(event)" required>
      </div>
      <div id="image-preview" style="margin-top: 15px;">
        <img id="output" src="" alt="Image Preview" style="max-width: 200px; display: none;">

        <span class="error"><?php echo $data['image_err']; ?></span>
      </div>

      <label for="vehicle">Vehicle type*</label>
      <select id="vehicle" name="vehicle" placeholder="Select a vehicle" <?php echo (!empty($data['vehicle_err'])) ? 'is_invalid' : ''; ?>>
        <option value="Lorry">Lorry</option>
        <option value="Bike">Bike</option>
        <option value="Three wheel">Three wheeler</option>
      </select>
      <span class="error"><?php echo $data['vehicle_err']; ?></span>

      <div class="form-group">
        <label for="area">Delivery Area*</label>
        <select id="area" name="area" required class="<?= !empty($data['area_err']) ? 'is_invalid' : ''; ?>">
          <option value="">-- Select --</option>
          <?php
          $districts = [
            'Ampara',
            'Anuradhapura',
            'Badulla',
            'Batticaloa',
            'Colombo',
            'Galle',
            'Gampaha',
            'Hambantota',
            'Jaffna',
            'Kalutara',
            'Kandy',
            'Kegalle',
            'Kilinochchi',
            'Kurunegala',
            'Mannar',
            'Matale',
            'Matara',
            'Monaragala',
            'Mullaitivu',
            'Nuwara Eliya',
            'Polonnaruwa',
            'Puttalam',
            'Ratnapura',
            'Trincomalee',
            'Vavuniya'
          ];

          foreach ($districts as $district) {
            $selected = ($data['area'] === $district) ? 'selected' : '';
            echo "<option value=\"$district\" $selected>$district</option>";
          }
          ?>
        </select>
        <span class="error"><?= $data['area_err']; ?></span>
      </div>


      <label for="regno">Register_no*</label>
      <input type="text" id="regno" name="regno" placeholder="Enter register number of the vehicle" 
        required pattern="[A-Z]{2}[0-9]{4}" title="Format: 2 capital letters followed by 4 digits (e.g., AD2598)" 
          <?php echo (!empty($data['regno_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['regno']; ?>" required>
      <span class="error"><?php echo $data['regno_err']; ?></span>

      <label for="capacity">Capacity(Kg)*</label>
      <input type="number" id="capacity" name="capacity" placeholder="Enter capacity of the vehicle" <?php echo (!empty($data['capacity_err'])) ? 'is_invalid' : ''; ?>
        value="<?php echo $data['capacity']; ?>" required>
      <span class="error"><?php echo $data['capacity_err']; ?></span>

      <label for="v_image">Vehicle Image*</label>
      <div class="upload-container">
        <input type="file" id="v_image" name="v_image" <?php echo (!empty($data['v_image_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['v_image']; ?>" onchange="previewVehicleImage(event)" required>
      </div>
      <div id="vehicle-image-preview" style="margin-top: 15px;">
        <img id="outputVehicle" src="" alt="Vehicle Image Preview" style="max-width: 200px; display: none;">
        <span class="error"><?php echo $data['v_image_err']; ?></span>
      </div>

      <label for="l_image">License Image*</label>
      <div class="upload-container">
        <input type="file" id="l_image" name="l_image" <?php echo (!empty($data['l_image_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['l_image']; ?>" onchange="previewlImage(event)" required>
      </div>
      <div id="l_image-preview" style="margin-top: 15px;">
        <img id="limage" src="" alt="L_Image Preview" style="max-width: 200px; display: none;">
        <span class="error"><?php echo $data['l_image_err']; ?></span>
      </div>

      <label for="password">Password*</label>
      <div class="password-container">
        <input type="password" id="password" name="password" placeholder="Enter your password"
          <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?>
          value="<?php echo $data['password']; ?>" required>
        <i class="fa fa-eye-slash toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
      </div>
      <span class="error"><?php echo $data['password_err']; ?></span>

      <label for="confirm_password">Confirm Password*</label>
      <div class="password-container">
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password"
          <?php echo (!empty($data['confirm_password_err'])) ? 'is_invalid' : ''; ?>
          value="<?php echo $data['confirm_password']; ?>" required>
        <i class="fa fa-eye-slash toggle-password" id="toggleConfirmPassword" onclick="toggleConfirmPasswordVisibility()"></i>
      </div>
      <span class="error"><?php echo $data['confirm_password_err']; ?></span>


      <input type="hidden" id="role" name="role" value="<?php echo isset($_GET['role']) ? $_GET['role'] : ''; ?>">

      <button type="submit" class="register-btn">Register</button>
    </form>
  </div>
</div>

<script>
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

  function previewImage(event) {
    const imagePreview = document.getElementById('output');
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
  }

  function previewVehicleImage(event) {
    const vehicleImagePreview = document.getElementById('outputVehicle');
    vehicleImagePreview.src = URL.createObjectURL(event.target.files[0]);
    vehicleImagePreview.style.display = 'block';
    vehicleImagePreview.onload = () => URL.revokeObjectURL(vehicleImagePreview.src); // Free memory
  }

  function previewlImage(event) {
    const lImagePreview = document.getElementById('limage');
    lImagePreview.src = URL.createObjectURL(event.target.files[0]);
    lImagePreview.style.display = 'block';
    lImagePreview.onload = () => URL.revokeObjectURL(lImagePreview.src); // Free memory
  }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>