<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/register.css">

  <div class="register-container">
      <div class="register-image">
        <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer Illustration">
      </div>
      
      <div class="register-form">
        <h2>Register Now</h2>

        <form action="<?php echo URLROOT; ?>/users/register" method="POST" enctype="multipart/form-data">

          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="Enter your name" <?php echo (!empty($data['name_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['name']; ?>" required>

          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['email']; ?>" required>
          <span><?php echo $data['email_err']; ?></span>

          <label for="address">Address</label>
          <textarea name="address" id="address" placeholder="Enter your address" <?php echo (!empty($data['address_err'])) ? 'is_invalid' : ''; ?>
            value="<?php echo $data['address']; ?>" ></textarea>
          <span><?php echo $data['address_err']; ?></span>

          <label for="phone">Phone</label>
          <input type="text" id="phone" name="phone_number" placeholder="Enter your phone number" <?php echo (!empty($data['phone_number_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['phone_number']; ?>" required>
          
          <label for="image">Upload Image</label>
          <div class="upload-container">
            <input type="file" id="image" name="image" <?php echo (!empty($data['image_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['image']; ?>">
            <span><?php echo $data['image_err']; ?></span>
          </div>

          <label for="vehicle type">Vehicle type:</label>
          <select id="vehicle" name="vehicle" placeholder="Select a vehicle" <?php echo (!empty($data['vehicle_err'])) ? 'is_invalid' : ''; ?>>
            <option value="Lorry">Volvo</option>
            <option value="Bike">Saab</option>
            <option value="Three wheel">Fiat</option>
          </select>
          <span><?php echo $data['vehicle_err']; ?></span>
          
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['password']; ?>">
          <span><?php echo $data['password_err']; ?></span>

          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" <?php echo (!empty($data['confirm_password_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['confirm_password']; ?>">
          <span><?php echo $data['confirm_password_err']; ?></span>

          <!--get the value for role using the url --> 
          <input type="hidden" id="role" name="role" value="<?php echo isset($_GET['role']) ? $_GET['role'] : ''; ?>">
          
          <button type="submit" class="register-btn">Register</button>
        </form>
      </div>
  </div>

  <?php require APPROOT . '/views/inc/footer.php'; ?>