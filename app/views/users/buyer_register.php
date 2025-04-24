<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/register.css">

  <div class="register-container">
      <div class="register-image">
        <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer Illustration">
      </div>
      
      <div class="register-form">
        <h2>Register Now</h2>

        <form action="<?php echo URLROOT; ?>/buyercontrollers/register" method="POST" enctype="multipart/form-data">

          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="Enter your name" <?php echo (!empty($data['name_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['name']; ?>" required>

          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['email']; ?>" required>
          <span><?php echo $data['email_err']; ?></span>

          <label for="phone">Phone</label>
          <input type="text" id="phone" name="phone" placeholder="Enter your phone number" <?php echo (!empty($data['phone_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['phone']; ?>" required>

            <label for="email">Address</label>
          <input type="text" id="addr_no" name="addr_no" placeholder="Home No." <?php echo (!empty($data['addr_no_err'])) ? 'is_invalid' : ''; ?> 
                value="<?php echo $data['addr_no']; ?>" required>
              <span class="error"><?php echo $data['addr_no_err']; ?></span>

          <input type="text" id="street" name="street" placeholder="Home Street" <?php echo (!empty($data['street_err'])) ? 'is_invalid' : ''; ?> 
                value="<?php echo $data['addr_street']; ?>" required>
              <span class="error"><?php echo $data['street_err']; ?></span>

          <input type="text" id="city" name="city" placeholder="City" <?php echo (!empty($data['city_err'])) ? 'is_invalid' : ''; ?> 
                value="<?php echo $data['addr_city']; ?>" required>
              <span class="error"><?php echo $data['city_err']; ?></span>
          
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