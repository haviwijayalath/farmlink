<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/vehicleinfo.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="vehicleinfo-container">
    <div class="vehicleinfo-content-area">
        <h2>Vehicle</h2>
        <div class="vehicle-info">
            <img src="<?= URLROOT ?>/public/images/lorry.jpg" alt="Vehicle Image" class="vehicle-image">
            <div class="vehicle-details">
                <p><strong>Type:</strong> Lorry</p>
                <p><strong>Register No:</strong> ABC1234</p>

                <!-- Editable Driver Info -->
                <div class="driver-info">
                    <p><strong>Driver Name:</strong> 
                        <span id="driverNameDisplay">John Doe</span>
                        <input type="text" id="driverNameInput" value="John Doe" class="edit-field" style="display: none;">
                        <button class="edit-btn" onclick="toggleEdit('driverName')">Edit</button>
                    </p>

                    <p><strong>Driver NIC:</strong> 
                        <span id="driverNICDisplay">123456789V</span>
                        <input type="text" id="driverNICInput" value="123456789V" class="edit-field" style="display: none;">
                        <button class="edit-btn" onclick="toggleEdit('driverNIC')">Edit</button>
                    </p>

                    <!-- Save and Cancel buttons for editing driver info -->
                    <div class="edit-buttons" style="display: none;" id="editControls">
                        <button onclick="saveChanges()">Save</button>
                        <button onclick="cancelChanges()">Cancel</button>
                    </div>
                </div>
                
                <p><strong>Capacity:</strong> 10 tons</p>
            </div>
        </div>

        <!-- Buttons for adding and deleting the vehicle -->
        <a href="<?php echo URLROOT; ?>/vehicles/delete" class="delete-vehicle-btn" onclick="return confirm('Are you sure you want to delete this vehicle?');">Delete Vehicle</a>
    </div>

    <a href="<?php echo URLROOT; ?>/Dpersoncontrollers/addvehicle" class="add-vehicle-btn">Add New Vehicle</a>
    
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 

<script>
// Function to toggle between view and edit mode
function toggleEdit(field) {
    document.getElementById(field + "Display").style.display = "none";
    document.getElementById(field + "Input").style.display = "inline";
    document.getElementById("editControls").style.display = "block";
}

// Save changes and send data to the server
function saveChanges() {
    const driverName = document.getElementById("driverNameInput").value;
    const driverNIC = document.getElementById("driverNICInput").value;

    // Update display with new values
    document.getElementById("driverNameDisplay").textContent = driverName;
    document.getElementById("driverNICDisplay").textContent = driverNIC;

    // Send data to the server (AJAX or form submission can be used here)
    // Example: Save data using AJAX
    // $.post('<?php echo URLROOT; ?>/drivers/update', { name: driverName, nic: driverNIC });

    // Hide edit fields and reset display
    cancelChanges();
}

// Cancel edit and reset display
function cancelChanges() {
    document.getElementById("driverNameDisplay").style.display = "inline";
    document.getElementById("driverNICDisplay").style.display = "inline";
    document.getElementById("driverNameInput").style.display = "none";
    document.getElementById("driverNICInput").style.display = "none";
    document.getElementById("editControls").style.display = "none";
}
</script>