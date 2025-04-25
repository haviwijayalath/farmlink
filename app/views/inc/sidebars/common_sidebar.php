<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/common_sidebar.css">

<div class="sidebar">
  <ul>
    <?php if(isset($_SESSION['user_role'])): ?>
      <?php if($_SESSION['user_role'] === 'farmer'): ?>
        <li>
          <a href="<?php echo URLROOT?>/farmers">
            <i class="fa-solid fa-house"></i>Home</a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/farmers/managestocks">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>Stocks</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/farmers/manageorders">
            <i class="fa-solid fa-bars"></i>
            <span>Orders</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/farmers/viewsales">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Sales</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/forums/index">
            <i class="fa-solid fa-comment"></i>
            <span>Forum</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/farmers/bookconsultant">
            <i class="fa-solid fa-user-tie"></i>
            <span>Consultants</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/appointments/index">
            <i class="fa-solid fa-calendar-check"></i>
            <span>Appointments</span>
          </a>
        </li>
      <?php elseif($_SESSION['user_role'] === 'consultant'): ?>
        <li>
          <a href="<?= URLROOT ?>/consultants/viewprofile">
            <i class="fas fa-user"></i>
            <span>View Profile</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/consultants/editprofile">
            <i class="fas fa-edit"></i>
            <span>Edit Profile</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/consultants/setAvailability">
            <i class="fas fa-calendar-alt"></i>
            <span>Availability</span>
          </a>
        </li>
        <li>
          <a href="<?= URLROOT ?>/appointments/consultantAppointments">
            <i class="fa-solid fa-calendar-check"></i>
            <span>Appointments</span>
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>
    
    <!-- Logout link (common for all) -->
    <li>
      <a href="javascript:void(0)" onclick="showLogoutPopup()">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </li>
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
  function showLogoutPopup() {
    const popup = document.getElementById('logout-popup');
    popup.style.display = 'flex';
    popup.style.alignItems = 'center';
    popup.style.justifyContent = 'center';
  }

  function closeLogoutPopup() {
    document.getElementById('logout-popup').style.display = 'none';
  }

  function confirmLogout() {
    window.location.href = "<?= URLROOT ?>/users/logout";
  }
</script>
