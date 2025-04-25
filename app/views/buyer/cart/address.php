<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/address.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="addr-container">
    <h2>Billing & Shipping</h2>
    <form action="<?php echo URLROOT; ?>/orderControllers/saveAddress" method="POST" enctype="multipart/form-data">

    <label>
    <input type="checkbox" id="autoFillCheckbox"> Auto-fill with saved address
    </label>

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

        <label>House number</label>
        <input type="text" name="number" placeholder="House number">

        <label>Street Address *</label>
        <input type="text" name="street_address" placeholder="Street name" pattern="^[A-Za-z0-9\s.,'-]{5,100}$" 
    title="Street address must be 5-100 characters and can contain letters, numbers, spaces, commas, periods, apostrophes, and hyphens." required>

        <label>City *</label>
        <select name="city" required>
            <option value="">Select a city</option>
            <option value="Ampara">Ampara</option>
            <option value="Anuradhapura">Anuradhapura</option>
            <option value="Badulla">Badulla</option>
            <option value="Batticaloa">Batticaloa</option>
            <option value="Chilaw">Chilaw</option>
            <option value="Colombo">Colombo</option>
            <option value="Dambulla">Dambulla</option>
            <option value="Vavuniya">Embilipitiya</option>
            <option value="Galle">Galle</option>
            <option value="Gampaha">Gampaha</option>
            <option value="Hambantota">Hambantota</option>
            <option value="Hatton">Hatton</option>
            <option value="Jaffna">Jaffna</option>
            <option value="Kalmunai">Kalmunai</option>
            <option value="Kalutara">Kalutara</option>
            <option value="Kandy">Kandy</option>
            <option value="Kegalle">Kegalle</option>
            <option value="Kilinochchi">Kilinochchi</option>
            <option value="Kurunegala">Kurunegala</option>
            <option value="Mannar">Mannar</option>
            <option value="Matale">Matale</option>
            <option value="Matara">Matara</option>
            <option value="Monaragala">Monaragala</option>
            <option value="Mullaitivu">Mullaitivu</option>
            <option value="Negombo">Negombo</option>
            <option value="Nuwara Eliya">Nuwara Eliya</option>
            <option value="Polonnaruwa">Polonnaruwa</option>
            <option value="Puttalam">Puttalam</option>
            <option value="Ratnapura">Ratnapura</option>
            <option value="Trincomalee">Trincomalee</option>
            <option value="Vavuniya">Vavuniya</option>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const autoFillCheckbox = document.getElementById('autoFillCheckbox');
    const formFields = {
        title: document.querySelector('select[name="title"]'),
        first_name: document.querySelector('input[name="first_name"]'),
        last_name: document.querySelector('input[name="last_name"]'),
        number: document.querySelector('input[name="number"]'),
        street_address: document.querySelector('input[name="street_address"]'),
        city: document.querySelector('select[name="city"]'),
        mobile: document.querySelector('input[name="mobile"]'),
        email: document.querySelector('input[name="email"]')
    };

    autoFillCheckbox.addEventListener('change', function () {
        if (this.checked) {
            // Fetch buyer's address via AJAX
            fetch('<?= URLROOT ?>/orderControllers/getBuyerAddress')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        this.checked = false; // Uncheck if no data is found
                    } else {
                        // Populate form fields
                        formFields.title.value = data.title;
                        formFields.first_name.value = data.first_name;
                        formFields.last_name.value = data.last_name;
                        formFields.number.value = data.number;
                        formFields.street_address.value = data.street;
                        formFields.city.value = data.city;
                        formFields.mobile.value = data.mobile;
                        formFields.email.value = data.email;

                        // Disable fields to prevent manual editing
                        // Object.values(formFields).forEach(field => field.disabled = true);
                    }
                })
                .catch(error => console.error('Error fetching buyer data:', error));
        } else {
            // Enable fields for manual input
            Object.values(formFields).forEach(field => {
                // field.disabled = false;
                field.value = '';
            });
        }
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
