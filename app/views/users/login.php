<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/login.css">

<div class="login-container">
    <div class="login-left">
        <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer" class="farmer-image">
    </div>
    
    <div class="login-right">
        <h4 style="color: green;"> <?php flash('register_success'); ?> </h4>
        <h4 style="color: green;"> <?php flash('reset_link'); ?> </h4>
        <h2>Welcome back!</h2>
        <form action="<?php echo URLROOT; ?>/users/login" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['email']; ?>" required>
                <span class="error"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="form-group password-container">
                <input type="password" name="password" id="password" placeholder="Enter your password" <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['password']; ?>" required>
                <i class="fa fa-eye-slash toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                <span class="error"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="forgot-password">
                <a href="<?php echo URLROOT; ?>/users/forgotPassword">Forgot Password?</a>
            </div>
        
            <button type="submit" class="login-btn">Log In</button>

            <div class="form-footer">
                <p>OR</p>
                <p>Don't have an account? <a href="<?php echo URLROOT; ?>/home/home2">Sign Up</a></p>
            </div>
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
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
