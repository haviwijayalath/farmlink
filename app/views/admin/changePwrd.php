<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/changePwrd.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="change-password-container">
        <h2>Change Password</h2>

        <form>
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

            <button type="button" id="suggest-password">Suggest a Password</button>
            <button type="submit">Change Password</button>
        </form>
    </div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<div class="change-password-container">
    <h2>Change Password</h2>

    <form action="<?= URLROOT ?>/admins/updatePassword/" method="POST">
        <!-- Current Password -->
        <div class="form-group">
            <label for="current-password">Current Password:</label>
            <div class="password-wrapper">
                <input type="password" id="current-password" name="current-password" required>
                <i class="fa fa-eye-slash toggle-password" data-target="current-password"></i>
            </div>
        </div>

        <!-- New Password -->
        <div class="form-group">
            <label for="new-password">New Password:</label>
            <div class="password-wrapper">
                <input type="password" id="new-password" name="new-password" required>
                <i class="fa fa-eye-slash toggle-password" data-target="new-password"></i>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirm-password">Confirm New Password:</label>
            <div class="password-wrapper">
                <input type="password" id="confirm-password" name="confirm-password" required>
                <i class="fa fa-eye-slash toggle-password" data-target="confirm-password"></i>
            </div>
        </div>

        <!-- Suggest Password Button -->

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">Change Password</button>
    </form>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>

    <script>
    // Get modal elements
    const modal = document.getElementById("changePasswordModal");
    const openModalBtn = document.querySelector(".changepw"); // Button that opens the modal
    const closeModalBtn = modal.querySelector(".close"); // Close button in the modal

    // Show modal when the button is clicked
    openModalBtn.addEventListener("click", (e) => {
        e.preventDefault(); // Prevent default link action
        modal.style.display = "flex"; // Show the modal
    });

    // Hide modal when the close button is clicked
    closeModalBtn.addEventListener("click", () => {
        modal.style.display = "none"; // Hide the modal
    });

    // Hide modal when clicking outside the modal content
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none"; // Hide the modal
        }
    });
</script>
