<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/login.css">

<div class="login-container">
    <div class="login-right">
        <h4 style="color: green;"> <?php flash('reset_password_message'); ?> </h4>
        <h2>Create New Password</h2>
        <p>Please enter your new password below.</p>
        <form action="<?php echo URLROOT; ?>/users/resettingPassword" method="POST">
            <!-- Hidden input for the reset token -->
            <input type="hidden" name="email" value="<?php echo $data['email'] ?? ''; ?>">
            
            <div class="form-group password-container">
                <input type="password" name="password" id="password" placeholder="Enter new password" 
                    <?php echo (!empty($data['password_err'])) ? 'is_invalid' : ''; ?> required>
                <i class="fa fa-eye-slash toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                <span class="error"><?php echo $data['password_err'] ?? ''; ?></span>
            </div>
            
            <div class="form-group password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" 
                    <?php echo (!empty($data['confirm_password_err'])) ? 'is_invalid' : ''; ?> required>
                <i class="fa fa-eye-slash toggle-password" id="toggleConfirmPassword" onclick="toggleConfirmPasswordVisibility()"></i>
                <span class="error"><?php echo $data['confirm_password_err'] ?? ''; ?></span>
            </div>
            
            <button type="submit" class="login-btn">Reset Password</button>

            <div class="form-footer">
                <p>Remember your password? <a href="<?php echo URLROOT; ?>/users/login">Back to Login</a></p>
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
    
    function toggleConfirmPasswordVisibility() {
        const passwordInput = document.getElementById('confirm_password');
        const toggleIcon = document.getElementById('toggleConfirmPassword');
        
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