<!-- Sidebar -->
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/d_person.css">

<div class="sidebar">
      <ul>
        <li><a href="<?php echo URLROOT?>/dpersons/neworder"><i class="fas fa-shopping-cart"></i> New Orders</a></li>
        <li><a href="<?php echo URLROOT?>/dpaccounts/account"><i class="fas fa-user"></i> Account</a></li>
        <li><a href="<?php echo URLROOT?>/dpaccounts/vehicleinfo"><i class="fas fa-truck"></i> Vehicle</a></li>
        <li><a href="<?php echo URLROOT?>/dpersons/history"><i class="fas fa-history"></i> Delivery History</a></li>
        <li><a href="<?php echo URLROOT?>/dpersons/ongoingDeliveries"><i class="fa-solid fa-truck-fast"></i>Ongoing</a></li>
        <!-- Logout -->
        <li><a href="javascript:void(0)" onclick="showPopup()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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

<script>
    // Show the popup
    function showPopup() {
        document.getElementById('logout-popup').style.display = 'flex';
    }

    // Hide the popup
    function closePopup() {
        document.getElementById('logout-popup').style.display = 'none';
    }

    // Handle logout confirmation
    function confirmLogout() {
        // Redirect to logout URL
        window.location.href = "<?= URLROOT ?>/Users/logout";
    }
</script>