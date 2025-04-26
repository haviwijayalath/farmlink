<link rel="stylesheet" href="<?= URLROOT ?>/public/css/sidebars/buyer_sidebar.css">


<div class="sidebar" style="width: 200px; margin-top: 120px ;">
      <ul>
        <li><a href="<?php echo URLROOT?>"><i class="fa-solid fa-house"></i> <span>Home </span></a></li>
        <li><a href="<?php echo URLROOT?>/Buyercontrollers/viewprofile"><i class="fas fa-user"></i><span> Account</span> </a></li>
        <li><a href="<?php echo URLROOT?>/Buyercontrollers/cartDetails"><i class="fa-solid fa-cart-shopping"></i> <span>Cart</span> </a></li>
        <li><a href="<?php echo URLROOT?>/Buyercontrollers/buyerOrders"><i class="fas fa-history"></i><span> Orders </span></a></li>
        <li><a href="<?php echo URLROOT?>/Buyercontrollers/wishlistDetails"><i class="fa-solid fa-bookmark"></i> <span>Wishlist</span></a></li>
        <li><a href="<?= URLROOT ?>/orderControllers/show_buyer_complaint_sb"><i class="fa-solid fa-triangle-exclamation"></i><span>Complaints</span></a></li>
        <!-- Logout -->
        <!-- <li><a href="javascript:void(0)" onclick="showPopup()"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li> -->
         <!-- Logout -->
        <li><a href="javascript:void(0)" onclick="showPopup('logout-popup')"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
      </ul>
    </div>

    <!-- Logout Confirmation Popup -->
  <div class="popup-container" id="logout-popup" style="display: none;">
    <div class="popup-content">
      <h2>Log out of your account?</h2>
      <div class="button-container">
        <button class="logout-button" onclick="confirmLogout()">Log out</button>
        <button class="cancel-button" onclick="closePopup('logout-popup')">Cancel</button>
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
  // function showPopup() {
  //   const popup = document.getElementById('logout-popup');
  //   popup.style.display = 'flex';
  //   popup.style.alignItems = 'center'; // Ensure popup is centered
  //   popup.style.justifyContent = 'center';
  // }

  // Show the popup
function showPopup(popupId, cartId = null) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = 'flex';
        popup.style.alignItems = 'center'; // Ensure popup is centered
        popup.style.justifyContent = 'center';

        // If cartId is provided, store it for removal confirmation
        if (cartId !== null) {
            cartIdToRemove = cartId;
        }
    }
}

  // // Hide the popup
  // function closePopup() {
  //   document.getElementById('logout-popup').style.display = 'none';
  // }

     // Close the popup
     function closePopup(popupId) {
        const popup = document.getElementById(popupId);
        if (popup) {
            popup.style.display = 'none';
        }
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

