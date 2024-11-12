<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/register.css">

  <div class="register-container">
      <div class="register-image">
        <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer Illustration">
      </div>
      
      <div class="register-form">
        <h2>Register Now</h2>

        <form action="<?php echo URLROOT; ?>/users/dpregister" method="POST" enctype="multipart/form-data">

          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="Enter your name" <?php echo (!empty($data['name_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['name']; ?>" required>

          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['email']; ?>" required>
          <span><?php echo $data['email_err']; ?></span>

          <label for="email">Address</label>
          <input type="text" id="addr_no" name="addr_no" placeholder="Home No." <?php echo (!empty($data['addr_no_err'])) ? 'is_invalid' : ''; ?> 
                value="<?php echo $data['addr_no']; ?>" required>
              <span><?php echo $data['addr_no_err']; ?></span>

          <input type="text" id="street" name="street" placeholder="Home Street" <?php echo (!empty($data['street_err'])) ? 'is_invalid' : ''; ?> 
                value="<?php echo $data['street']; ?>" required>
              <span><?php echo $data['street_err']; ?></span>

          <input type="text" id="city" name="city" placeholder="City" <?php echo (!empty($data['city_err'])) ? 'is_invalid' : ''; ?> 
                value="<?php echo $data['city']; ?>" required>
              <span><?php echo $data['city_err']; ?></span>

          <label for="phone">Phone</label>
          <input type="text" id="phone" name="phone" placeholder="Enter your phone number" <?php echo (!empty($data['phone_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['phone']; ?>" required>
          
          <label for="image">Upload Image</label>
          <div class="upload-container">
            <input type="file" id="image" name="image" <?php echo (!empty($data['image_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['image']; ?>">
            <span><?php echo $data['image_err']; ?></span>
          </div>

          <label for="vehicle">Vehicle type:</label>
          <select id="vehicle" name="vehicle" placeholder="Select a vehicle" <?php echo (!empty($data['vehicle_err'])) ? 'is_invalid' : ''; ?>>
            <option value="Lorry">Loryy</option>
            <option value="Bike">Bike</option>
            <option value="Three wheel">Three wheeler</option>
          </select>
          <span><?php echo $data['vehicle_err']; ?></span>

          <label for="area">Delivery Area</label>
          <input type="text" id="area" name="area" placeholder="Enter possible delivery area" <?php echo (!empty($data['area_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['area']; ?>" required>

            <label for="regno">Register_no</label>
          <input type="text" id="regno" name="regno" placeholder="Enter register number of the vehicle" <?php echo (!empty($data['regno_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['regno']; ?>" required>

            <label for="capacity">Capacity</label>
          <input type="text" id="capacity" name="capacity" placeholder="Enter capacity of the vehicle" <?php echo (!empty($data['capacity_err'])) ? 'is_invalid' : ''; ?> 
            value="<?php echo $data['capacity']; ?>" required>

            <label for="v_image">Vehicle Image</label>
          <div class="upload-container">
            <input type="file" id="v_image" name="v_image" <?php echo (!empty($data['v_image_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['v_image']; ?>">
            <span><?php echo $data['v_image_err']; ?></span>
          </div>
          
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