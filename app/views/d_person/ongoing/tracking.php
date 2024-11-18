<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/tracking.css">

<div class="content">

<!-- Display the current order status -->
<div class="order-status">
    <h3>Current Order Status: <?= htmlspecialchars($data['status']) ?></h3>
  </div>

  <!-- Progress Tracker -->
  <div class="track">
    <ul id="progress">
      <!-- PLACED -->
      <li class="<?= ($data['status'] == 'new' || $data['status'] == 'ongoing' || $data['status'] == 'delivered') ? 'active' : ''; ?>">
        <div class="icon"><i class="fas fa-file-alt"></i></div>
        <p>Order Processed</p>
      </li>

      <!-- SHIPPED -->
      <li class="<?= ($data['status'] == 'ongoing' || $data['status'] == 'delivered') ? 'active' : ''; ?>">
        <div class="icon"><i class="fas fa-shipping-fast"></i></div>
        <p>Out for Deliver</p>
      </li>

      <!-- DELIVERED -->
      <li class="<?= ($data['status'] == 'delivered') ? 'active' : ''; ?>">
        <div class="icon"><i class="fas fa-home"></i></div>
        <p>Order Arrived</p>
      </li>
    </ul>
  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
