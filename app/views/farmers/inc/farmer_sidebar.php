<!-- Sidebar -->

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/farmer.css?version=1">


<div class="sidebar">
  <ul>
  <li><a href="<?php echo URLROOT?>/farmers"><i class="fa-solid fa-house"></i>Home</a></li>
    <li><a href="<?php echo URLROOT?>/farmers/managestocks"><i class="fa-solid fa-boxes-stacked"></i>Stocks</a></li>
    <li><a href="<?php echo URLROOT?>/farmers/manageorders"><i class="fa-solid fa-bars"></i>Orders</a></li>
    <li><a href="<?php echo URLROOT?>/farmers/viewsales"><i class="fa-solid fa-clipboard-check"></i>Sales</a></li>
    <li><a href="<?php echo URLROOT?>/forums/index"><i class="fa-solid fa-comment"></i>Forum</a></li>
    <li><a href="<?php echo URLROOT?>/farmers/bookconsultant"><i class="fa-solid fa-user-tie"></i>Consultants</a></li>
    <li><a href="<?php echo URLROOT?>/appointments/index"><i class="fa-solid fa-calendar-check"></i>Appointments</a></li>
    <li><a href="javascript:void(0)" onclick="showLogoutPopup()"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
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
    popup.style.alignItems = 'center'; // Ensure popup is centered
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