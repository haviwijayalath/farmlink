<?php require APPROOT . '/views/inc/header.php'; ?>


<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/vehicleinfo.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="vehicleinfo-container">
    <div class="vehicleinfo-content-area">
        <h2>Vehicle</h2>

        <?php if (!empty($data['type'])):?>
            <div class="vehicle-info">
                <img src="<?= URLROOT ?>/public/uploads/<?= htmlspecialchars($data['v_image']) ?>" alt="Vehicle Image" class="vehicle-image">

                <div class="vehicle-details">
                    <p><strong style="margin-right: 10px;">Type:</strong> <?= htmlspecialchars($data['type']) ?></p>
                    <p><strong style="margin-right: 10px;">Register No:</strong> <?= htmlspecialchars($data['regno']) ?></p>
                    <p><strong style="margin-right: 10px;">Capacity:</strong> <?= htmlspecialchars($data['capacity']) ?></p>
                    <p><strong style="margin-right: 10px;">Vehicle structure:</strong> <?= htmlspecialchars($data['v_strcture']) ?></p>
                
                </div>

                <!--<a href="<?= URLROOT ?>/dpaccounts/deleteVehicle?id=<?= $data['vehicle']['id'] ?>" class="delete-vehicle-btn" 
                    onclick="return confirm('Are you sure you want to delete this vehicle?');">Delete Vehicle</a> -->
            </div>

                <!-- Add New Vehicle Button -->
            <div class="add-vehicle-container">
                <a href="<?= URLROOT ?>/dpaccounts/addvehicle/" class="add-vehicle-btn">Add New Vehicle</a>
            </div>
        <?php else: ?>
            <p>No vehicle to display...</p>
            <div class="add-vehicle-container">
                <a href="<?= URLROOT ?>/dpaccounts/addvehicle/" class="add-vehicle-btn">Add New Vehicle</a>
                

            </div>
        <?php endif; ?>

        
    </div>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?> 
