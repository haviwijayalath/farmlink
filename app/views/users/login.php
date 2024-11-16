<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/login.css">

  <div class="login-container">
        <div class="login-left">
            <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer" class="farmer-image">
        </div>

        <div class="login-right">
            <h2>Welcome back!</h2>
            <form action="<?php echo URLROOT; ?>/DpersonRegistrations/login" method="POST">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['email']; ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Enter your password" <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['password']; ?>" required>
                    <span><?php echo $data['password_err']; ?></span>
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <a href="#">Forgotten password?</a>
                </div>
            
                <button type="submit" class="login-btn">Log In</button>

                <div class="form-footer">
                    <p>OR</p>
                    <p>Don't have an account? <a href="<?php echo URLROOT; ?>/home/home2">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>

  <?php require APPROOT . '/views/inc/footer.php'; ?>
