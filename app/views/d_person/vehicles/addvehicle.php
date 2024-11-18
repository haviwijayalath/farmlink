<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/addvehicle.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="addvehicle-container">
    <div class="addvehicle-content-area">
        <h2>Add Vehicle</h2>
        <form action="add_vehicle_form.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="vehicleType">Type:</label>
                <select name="vehicleType" id="vehicleType" required>
                    <option value="">Select</option>
                    <option value="Truck">Truck</option>
                    <option value="Van">Van</option>
                    <option value="Car">Car</option>
                    <!-- Add more vehicle types if needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="registerNo">Register No:</label>
                <input type="text" name="registerNo" id="registerNo" required>
            </div>
            <div class="form-group">
                <label for="driverName">Driver Name:</label>
                <input type="text" name="driverName" id="driverName" required>
            </div>
            <div class="form-group">
                <label for="driverNIC">Driver NIC:</label>
                <input type="text" name="driverNIC" id="driverNIC" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="text" name="capacity" id="capacity" required>
            </div>
            <div class="form-group">
                <label for="vehicleImage">Vehicle Image:</label>
                <input type="file" name="vehicleImage" id="vehicleImage" accept="image/png, image/jpeg" required>
                <p class="help-text">JPEG, PNG are allowed</p>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn-add">Add</button>
                <button type="reset" class="btn-delete">Delete</button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>