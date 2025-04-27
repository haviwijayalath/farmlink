<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/showaccount.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="admin-dashboard">
    <main>
        <div class="head">
            <h1><?= ucfirst($data['role']) ?> Account Details</h1>
        </div>

        <div class="user-details-card">
            <div class="profile-section">
                <img src="<?= URLROOT ?>/public/uploads/<?= substr(strtolower($data['role']), 0, -1) ?>/profile/<?= $data['user']->image ?? 'default.png' ?>" alt="Profile Image">
                <h2><?= $data['user']->name ?></h2>
                <p class="role-badge"><?= ucfirst($data['role']) ?></p>
                <p class="status <?= strtolower($data['user']->status ?? 'approved') ?>"><?= ucfirst($data['user']->status ?? 'N/A') ?></p>
                <p class="suspend-date"><?= !empty($data['user']->suspend_date) ? 'Suspended until ' . date('d-m-Y', strtotime($data['user']->suspend_date)) : '' ?></p>

                <div class="action-buttons">
                    <form method="post" action="<?= URLROOT ?>/adminControllers/changeStatus">
                        <input type="hidden" name="id" value="<?= $data['user']->id ?>">
                        <input type="hidden" name="role" value="<?= $data['role'] ?>">
                        <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
                        <button type="button" class="btn-suspend" onclick="showSuspendModal()">Suspend</button>
                        <button type="submit" name="action" value="deactivate" class="btn-delete" onclick="return confirm('Are you sure you want to deactivate this account?')">Deactivate</button>
                    </form>
                    
                    <!-- Moved suspend modal outside the main form -->
                    <div id="suspendModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
                        <div class="modal-content">
                            <h3>Suspend Until</h3>
                            <form method="post" action="<?= URLROOT ?>/adminControllers/changeStatus">
                                <input type="hidden" name="id" value="<?= $data['user']->id ?>">
                                <input type="hidden" name="role" value="<?= $data['role'] ?>">
                                <input type="date" id="suspendDate" name="suspend_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                <div class="modal-buttons">
                                    <button type="button" onclick="closeModal()" class="btn-cancel">Cancel</button>
                                    <button type="submit" name="action" value="suspend" onclick="return validateSuspendDate()" class="btn-confirm-suspend">Confirm Suspension</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <div class="info-row"><strong>Email:</strong> <?= $data['user']->email ?></div>
                <div class="info-row"><strong>Phone:</strong> <?= $data['user']->phone ?></div>
                <div class="info-row"><strong>Status:</strong> <?= $data['user']->status ?></div>

                <!-- Optional fields (only shown if they exist) -->
                <?php if (!empty($data['user']->experience)): ?>
                    <div class="info-row"><strong>Experience:</strong> <?= $data['user']->experience ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->address)): ?>
                  <div class="info-row"><strong>Address:</strong> <?= $data['user']->address ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->specialization)): ?>
                    <div class="info-row"><strong>Specialization:</strong> <?= $data['user']->specialization ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->area)): ?>
                    <div class="info-row"><strong>Delivery Area:</strong> <?= $data['user']->area ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->rate)): ?>
                    <div class="info-row"><strong>Rate:</strong> <?= $data['user']->rate ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->type)): ?>
                    <div class="info-row"><strong>Vehicle Type:</strong> <?= $data['user']->type ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->regno)): ?>
                    <div class="info-row"><strong>Registration No:</strong> <?= $data['user']->regno ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->capacity)): ?>
                    <div class="info-row"><strong>Capacity:</strong> <?= $data['user']->capacity ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->license_image)): ?>
                    <div class="info-row vehicle-image">
                        <strong>License Image:</strong><br>
                        <img src="<?= URLROOT ?>/public/uploads/<?= $data['user']->license_image ?>" alt="License Image">
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['user']->v_image)): ?>
                    <div class="info-row vehicle-image">
                        <strong>Vehicle Image:</strong><br>
                        <img src="<?= URLROOT ?>/public/uploads/<?= $data['user']->v_image ?>" alt="Vehicle Image">
                    </div>
                <?php endif; ?>
              
                <!-- ID Card Section -->
                <?php if (!empty($data['user']->id_card_front) || !empty($data['user']->id_card_back)): ?>
                    <div class="info-row id-cards">
                        <strong>ID Card:</strong>
                        <div class="id-card-images">
                            <?php if (!empty($data['user']->id_card_front)): ?>
                                <div class="id-card">
                                    <img src="<?= URLROOT ?>/public/uploads/<?= substr(strtolower($data['role']), 0, -1) ?>/id_cards/<?= $data['user']->id_card_front ?>" alt="ID Card Front" height="250">
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($data['user']->id_card_back)): ?>
                                <div class="id-card">
                                    <img src="<?= URLROOT ?>/public/uploads/<?= substr(strtolower($data['role']), 0, -1) ?>/id_cards/<?= $data['user']->id_card_back ?>" alt="ID Card Back" height="250">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['user']->verification_doc)): ?>
                    <div class="info-row vehicle-image">
                        <strong>Vehicle Image:</strong><br>
                        <img src="<?= URLROOT ?>/public/uploads/<?= $data['user']->verification_doc ?>" alt="Vehicle Image">
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>
</div>

<style>
    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border-radius: 5px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .modal-content h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .modal-content input[type="date"] {
        width: 90%;
        padding: 8px;
        margin: 10px 0px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .modal-buttons {
        margin-top: 15px;
        /* text-align: right; */
    }
    .btn-cancel {
        background-color: #ccc;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 10px;
    }
    .btn-confirm-suspend {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
    }
</style>


<script>
    function showSuspendModal() {
        document.getElementById('suspendModal').style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('suspendModal').style.display = 'none';
    }
    
    function validateSuspendDate() {
        const dateInput = document.getElementById('suspendDate');
        if (!dateInput.value) {
            alert('Please select a suspension end date');
            return false;
        }
        return true;
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>