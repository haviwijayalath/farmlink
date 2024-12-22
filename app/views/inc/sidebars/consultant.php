<!-- Sidebar -->
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/consultant.css">

<div class="sidebar">
  <ul>
    <li><a href="<?= URLROOT ?>/consultants/index"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
    <li><a href="<?= URLROOT ?>/consultants/viewprofile"><i class="fas fa-user"></i><span>View Profile</span></a></li>
    <li><a href="<?= URLROOT ?>/consultants/editprofile"><i class="fas fa-edit"></i><span>Edit Profile</span></a></li>
    <li><a href="<?= URLROOT ?>/consultants/getQuestions"><i class="fa-solid fa-comment"></i><span>Forum</span></a></li>
    
    <!-- Logout -->
    <li><a href="javascript:void(0)" onclick="showPopup()"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
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
