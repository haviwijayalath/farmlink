<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/support_more.css">


<div class="container">
    <h1>Account Activation Support</h1>
    <p>If you are having trouble activating your account, please provide the following information:</p>
    
    <form action="/farmlink/users/support_activation" method="POST">
        <div class="form-group">
            <label for="email">Your Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="4" required></textarea>
        </div>
        <button type="submit">Submit Request</button>
    </form>

    <p><a href="/farmlink/users/support">Back to Support</a></p>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
