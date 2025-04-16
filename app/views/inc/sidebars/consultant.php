<!-- Consultant Sidebar -->
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/farmer.css?version=1">
<!-- You can use the same CSS file if both sidebars share the same styling -->

<div class="sidebar">
  <ul>
    <li><a href="<?= URLROOT ?>/consultants/viewprofile"><i class="fas fa-user"></i>View Profile</a></li>
    <li><a href="<?= URLROOT ?>/consultants/editprofile"><i class="fas fa-edit"></i>Edit Profile</a></li>
    <li><a href="<?= URLROOT ?>/consultants/setAvailability"><i class="fas fa-calendar-alt"></i>Availability</a></li>
    <li><a href="<?= URLROOT ?>/forums/index"><i class="fa-solid fa-comment"></i>Forum</a></li>
    <li><a href="<?= URLROOT ?>/appointments/consultantAppointments"><i class="fa-solid fa-calendar-check"></i>Appointments</a></li>
    <li><a href="javascript:void(0)" onclick="showLogoutPopup()"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
  </ul>
</div>

<!-- Logout Confirmation Popup -->
<div class="popup-container" id="logout-popup" style="display: none;">
  <div class="popup-content">
    <h2>Log out of your account?</h2>
    <div class="button-container">
      <button class="logout-button" onclick="confirmLogout()">Log out</button>
      <button class="cancel-button" onclick="closeLogoutPopup()">Cancel</button>
    </div>
  </div>
</div>

<script>
  // Show the popup
  function showLogoutPopup() {
    const popup = document.getElementById('logout-popup');
    popup.style.display = 'flex';
    popup.style.alignItems = 'center';
    popup.style.justifyContent = 'center';
  }

  // Hide the popup
  function closeLogoutPopup() {
    document.getElementById('logout-popup').style.display = 'none';
  }

  // Handle logout confirmation
  function confirmLogout() {
    window.location.href = "<?= URLROOT ?>/users/logout";
  }
</script>
