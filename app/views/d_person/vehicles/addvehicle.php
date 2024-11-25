<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/addvehicle.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="addvehicle-container">
    <div class="addvehicle-content-area">
        <h2>Add Vehicle</h2>
        <form action="<?php echo URLROOT; ?>/dpaccounts/addvehicle" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="vehicleType">Type:</label>
                <select name="type" id="vehicleType" required>
                    <option value="">Select</option>
                    <option value="Truck">Lorry</option>
                    <option value="Van">Van</option>
                    <option value="Car">Car</option>
                    <!-- Add more vehicle types if needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="registerNo">Register No:</label>
                <input type="text" name="regno" id="registerNo" required>
            </div>
            <!--<div class="form-group">
                <label for="driverName">Driver Name:</label>
                <input type="text" name="driverName" id="driverName" required>
            </div>
            <div class="form-group">
                <label for="driverNIC">Driver NIC:</label>
                <input type="text" name="driverNIC" id="driverNIC" required>
            </div>-->
            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="text" name="capacity" id="capacity" required>
            </div>
            <div class="form-group">
                <label for="vehicleImage">Vehicle Image:</label>
                
                <input type="file" name="v_image" id="vehicleImage" accept="image/png, image/jpeg" onchange="previewImage(event)" required>
                <p class="help-text">JPEG, PNG are allowed</p>
               
                <div id="image-preview" style="margin-top: 15px;">
                <img id="vehicle" src="" alt="Vimage Preview" style="max-width: 200px; display: none;">
                </div>
            </div>

            <div class="form-buttons">
            <button type="submit" class="btn-add">Add New Vehicle</button>
            <a href="<?= URLROOT ?>/dpaccounts/vehicleinfo" class="delete-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
function previewImage(event) {
    const imagePreview = document.getElementById('vehicle');
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
}
</script>