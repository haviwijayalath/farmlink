<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/vehicleinfo.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="vehicleinfo-container">
<div class="vehicleinfo-content-area">
    <h2>Vehicle</h2>

    <?php if (!empty($data['type'])): ?>
        <div class="vehicle-info">
            <img src="<?= URLROOT ?>/public/uploads/<?= htmlspecialchars($data['vehicle']['v_image']) ?>" alt="Vehicle Image" class="vehicle-image">

            <div class="vehicle-details">
                <p><strong>Type:</strong> <?= htmlspecialchars($data['vehicle']['type']) ?></p>
                <p><strong>Register No:</strong> <?= htmlspecialchars($data['vehicle']['regno']) ?></p>
                <p><strong>Capacity:</strong> <?= htmlspecialchars($data['vehicle']['capacity']) ?></p>
            </div>

            <a href="<?= URLROOT ?>/Dpaccounts/deleteVehicle?id=<?= $data['vehicle']['id'] ?>" class="delete-vehicle-btn" 
                        onclick="return confirm('Are you sure you want to delete this vehicle?');">Delete Vehicle</a>
        </div>
    <?php else: ?>
        <a href="<?= URLROOT ?>/Dpaccounts/addVehicle" class="add-vehicle-btn">Add New Vehicle</a>
    <?php endif; ?>
</div>

    
    
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 

<!--<script>
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
    // $.post('<?php /*echo URLROOT; */?>*//drivers/update', { name: driverName, nic: driverNIC });

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
</script>-->