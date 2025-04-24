<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/support_more.css">

<div class="container">
    <h1>Support - Other Inquiries</h1>
    <p>If you have any other inquiries or issues that do not fall under the categories of activation or approval, please fill out the form below:</p>
    
    <form action="/farmlink/users/support_other" method="POST">
        <div class="form-group">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Your Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
