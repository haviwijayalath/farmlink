<!-- Sidebar -->
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/d_person.css">

<div class="sidebar">
  <ul>
    <li><a href="<?= URLROOT ?>/dpaccounts/revenueCheck"><i class="fa-solid fa-hand-holding-dollar"></i><span>Revenue</span></a></li>
    <li><a href="<?= URLROOT ?>/dpersons/neworder"><i class="fas fa-shopping-cart"></i><span>New Orders</span></a></li>
    <li><a href="<?= URLROOT ?>/dpersons/ongoingDeliveries"><i class="fa-solid fa-truck-fast"></i><span>Ongoing</span></a></li>
    <li><a href="<?= URLROOT ?>/dpersons/history"><i class="fas fa-history"></i><span>Delivery History</span></a></li>
    <li><a href="<?= URLROOT ?>/orderControllers/showcomplaint_sb"><i class="fa-solid fa-triangle-exclamation"></i><span>Complaints</span></a></li>
    <li><a href="<?= URLROOT ?>/dpaccounts/account"><i class="fas fa-user"></i><span>Profile</span></a></li>
    <li><a href="<?= URLROOT ?>/dpaccounts/vehicleinfo"><i class="fas fa-truck"></i><span>Vehicle</span></a></li>
    <!-- Logout -->
    <li><a href="javascript:void(0)" onclick="showPopup()"><i class="fa-solid fa-right-from-bracket fa-rotate-180"></i><span>Logout</span></a></li>
  </ul>

  
  <!-- Logout Confirmation Popup -->
  <div class="popup-container" id="logout-popup" style="display: none;">
    <div class="popup-content">
      <h2>Log out of your account?</h2>
      <div class="button-container">
        <button class="logout-button" onclick="confirmLogout()">Log out</button>
        <button class="cancel-button" onclick="closePopup()">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Hamburger Button -->
<div class="hamburger" onclick="toggleSidebar()">
  <i class="fas fa-bars"></i>
</div>

<script>
  // Show the popup
  function showPopup() {
    const popup = document.getElementById('logout-popup');
    popup.style.display = 'flex';
    popup.style.alignItems = 'center'; // Ensure popup is centered
    popup.style.justifyContent = 'center';
  }

  // Hide the popup
  function closePopup() {
    document.getElementById('logout-popup').style.display = 'none';
  }

  // Handle logout confirmation
  function confirmLogout() {
    window.location.href = "<?= URLROOT ?>/users/logout";
  }

  // Toggle sidebar visibility
  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
  }
</script>
