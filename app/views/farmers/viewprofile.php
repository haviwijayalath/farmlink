<?php require APPROOT . '/views/farmers/inc/header.php'; ?>

<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<center>
    
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/farmers/viewprofile.css">

    <div class="acc-main-content">
            <div class="acc-profile-details">
                <h2>Profile Details</h2>
                <div class="acc-profile-image">
                    <img src="<?= URLROOT ?>/public/uploads/farmer/profile/<?= !empty($data['image']) && file_exists(APPROOT . '/../public/uploads/farmer/profile/' . $data['image']) ? $data['image'] : 'Farmer-bro.jpg' ?>" alt="Profile Picture" class="profile-pic">
                    <div class="acc-image-actions">
                    <a href="javascript:void(0);"  class="edit-profile-link">
                        <button><i class="fas fa-edit"></i></button>
                    </a>
                        <button><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="acc-profile-info">
                    <p><strong>Username:</strong> <?php echo $data['name']; ?></p>
                    <p><strong>Phone Number: </strong> <?php echo $data['phone']; ?></p>
                    <p><strong>Email:</strong> <?php echo $data['email']; ?></p>
                </div>
            </div>

            <div class="acc-actions">
                <div class="acc-action-card">
                    <h3>Change Password</h3>
                    <p>Change your password here.</p>
                    <a href="javascript:void(0);" class="changepw">Change Password</a>
                </div>
                <div class="acc-action-card">
                    <h3>Close Account</h3>
                    <p>You can permanently delete or temporarily freeze your account.</p>
                    <a href="<?= URLROOT ?>/admins/deactivateConfirmation/" class="changepw">Close Account</a>
                </div>
            </div>
        </div>

        <div class="change-password-overlay" id="overlay"></div>
    <div class="change-password-container" id="change-password-modal">
        <h2>Change Password</h2>
        <form action="<?php echo URLROOT; ?>/farmers/changepassword" method="POST">
            <div class="form-group">
                <label for="current-password">Current Password:</label>
                <input type="password" id="current-password" name="current-password">
                <i class="fa fa-eye-slash" id="current-password-toggle"></i>
            </div>
            <div class="form-group">
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password">
                <i class="fa fa-eye-slash" id="new-password-toggle"></i>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm New Password:</label>
                <input type="password" id="confirm-password" name="confirm-password">
                <i class="fa fa-eye-slash" id="confirm-password-toggle"></i>
            </div>
            <button type="submit">Change Password</button>
        </form>
    </div>

    <div class="edit-profile-overlay" id="edit-overlay"></div>
<div class="edit-profile-container" id="edit-profile-modal">
    <h2>Edit Your Profile</h2>
    <form action="<?php echo URLROOT; ?>/farmers/editprofile" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="address_id">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your name" value="<?php echo $data['name']; ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" value="<?php echo $data['email']; ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" placeholder="Enter your phone number" value="<?php echo $data['phone']; ?>">
        </div>

        <div class="form-group">
            <label for="image">Upload Image</label>
            <div class="image-upload-section">
                <input type="file" id="image" name="image" class="form-control form-control-lg" onchange="previewImage(event)">
            </div>

            <div id="image-preview" style="margin-top: 15px;">
                <img id="output" src="<?php echo URLROOT . '/public/uploads/farmer/profile/' . $data['image']; ?>" alt="Image Preview" style="max-width: 200px; <?php echo empty($data['image']) ? 'display: none;' : ''; ?>">
                <span style="color: red;"><?php echo $data['image_err']; ?></span>
            </div>
                
        </div>

        <div class="form-buttons">
            <button type="button" class="cancel-btn" id="edit-cancel-btn">Cancel</button>
            <button type="submit" class="save-btn">Save</button>
        </div>
    </form>
</div>

</center>

<script>
    // Get elements
    const changePasswordButton = document.querySelector('.changepw'); // Change Password link
    const modal = document.getElementById('change-password-modal');
    const overlay = document.getElementById('overlay');

    // Show the modal
    changePasswordButton.addEventListener('click', () => {
        modal.style.display = 'block';
        overlay.style.display = 'block';
    });

    // Hide the modal
    overlay.addEventListener('click', () => {
        modal.style.display = 'none';
        overlay.style.display = 'none';
    });


    // Get elements
    const editProfileButton = document.querySelector('.edit-profile-link button'); // Edit Profile link button
    const editProfileModal = document.querySelector('.edit-profile-container');
    const editOverlay = document.querySelector('.edit-profile-overlay');
    const cancelEditButton = document.querySelector('.cancel-btn');

    // Show the modal and overlay
    editProfileButton.addEventListener('click', () => {
        editProfileModal.style.display = 'block';
        editOverlay.style.display = 'block';
        document.body.classList.add('modal-open'); // Disable scrolling
    });

    // Hide the modal and overlay
    editOverlay.addEventListener('click', closeModal);
    cancelEditButton.addEventListener('click', closeModal);

    function closeModal() {
        editProfileModal.style.display = 'none';
        editOverlay.style.display = 'none';
        document.body.classList.remove('modal-open'); // Enable scrolling
    }

    // Preview image
    function previewImage(event) {
        const imagePreview = document.getElementById('output');
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.style.display = 'block';
        imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
    }
</script>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>