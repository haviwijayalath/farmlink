<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/login.css">

<div class="login-container">
    <div class="login-left">
        <img src="<?= URLROOT ?>/public/images/Farmer-bro.png" alt="Farmer" class="farmer-image">
    </div>
    
    <div class="login-right">
        <h4 style="color: green;"> <?php flash('forgot_password_message'); ?> </h4>
        <h2>Reset Your Password</h2>
        <p>Enter your email address below to receive a password reset link.</p>
        <form action="<?php echo URLROOT; ?>/users/forgotPassword" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email" <?php echo (!empty($data['email_err'])) ? 'is_invalid' : ''; ?> value="<?php echo $data['email'] ?? ''; ?>" required>
                <span class="error"><?php echo $data['email_err'] ?? ''; ?></span>
            </div>
            
            <button type="submit" class="login-btn">Send Reset Link</button>

            <div class="form-footer">
                <p>Remember your password? <a href="<?php echo URLROOT; ?>/users/login">Back to Login</a></p>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>