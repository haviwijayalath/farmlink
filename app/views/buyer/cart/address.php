<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/address.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="addr-container">
    <h2>Billing & Shipping</h2>
    <form action="<?php echo URLROOT; ?>/orderControllers/saveAddress" method="POST" enctype="multipart/form-data">
        <label>Title *</label>
        <select name="title" required>
            <option value="Mr">Mr</option>
            <option value="Miss">Miss</option>
            <option value="Mrs">Mrs</option>
        </select>

        <label>First Name *</label>
        <input type="text" name="first_name" required>

        <label>Last Name *</label>
        <input type="text" name="last_name" required>

        <label>House number *</label>
        <input type="text" name="number" placeholder="House number" required>

        <label>Street Address *</label>
        <input type="text" name="street_address" placeholder="Street name" required>

        <label>City *</label>
        <select name="city" required>
            <option value="">Select a city</option>
            <option value="Colombo">Colombo</option>
            <option value="Kandy">Kandy</option>
            <option value="Galle">Galle</option>
        </select>

        <label>Country *</label>
        <input type="text" value="Sri Lanka" disabled>

        <label>Mobile Number *</label>
        <input type="text" name="mobile" required>

        <label>Email *</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($data['cartID']); ?>">

        <button type="submit">Submit</button>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
